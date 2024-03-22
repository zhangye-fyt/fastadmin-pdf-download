<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Ajax_Handlers {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'minimog_ajax_woocommerce_add_to_cart', [ $this, 'add_to_cart_variable' ] );

		/**
		 * Compatible back with CDN issue.
		 *
		 * @todo Remove it after few months.
		 */
		add_action( 'wp_ajax_minimog_woocommerce_add_to_cart', [ $this, 'add_to_cart_variable' ] );
		add_action( 'wp_ajax_nopriv_minimog_woocommerce_add_to_cart', [ $this, 'add_to_cart_variable' ] );

		add_action( 'woocommerce_ajax_added_to_cart', [ $this, 'add_to_cart_message' ], 10, 1 );

		add_action( 'wp_ajax_minimog_search_products', [ $this, 'search_products' ] );
		add_action( 'wp_ajax_nopriv_minimog_search_products', [ $this, 'search_products' ] );

		add_action( 'wp_ajax_get_product_tabs', [ $this, 'ajax_get_product_tab_content' ] );
		add_action( 'wp_ajax_nopriv_get_product_tabs', [ $this, 'ajax_get_product_tab_content' ] );
	}

	/**
	 * By default WC print notices after redirect to cart pages.
	 * We need notices for Fly Cart.
	 * Check != 'yet' to avoid duplicate
	 *
	 * @param $product_id
	 */
	public function add_to_cart_message( $product_id ) {
		$quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );

		if ( 'yes' !== get_option( 'woocommerce_cart_redirect_after_add' ) ) {
			wc_add_to_cart_message( array( $product_id => $quantity ), true );
		}
	}

	/**
	 * @see \WC_AJAX::add_to_cart()
	 * @see \WC_Form_Handler::add_to_cart_action()
	 */
	public function add_to_cart_variable() {
		ob_start();

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! isset( $_POST['product_id'] ) ) {
			return;
		}

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$product           = wc_get_product( $product_id );
		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );
		$variation_id      = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		$variation         = array();

		if ( $product && 'variation' === $product->get_type() && empty( $variation_id ) ) {
			$variation_id = $product_id;
			$product_id   = $product->get_parent_id();
		}

		foreach ( $_POST as $key => $value ) {
			if ( 'attribute_' !== substr( $key, 0, 10 ) ) {
				continue;
			}

			$variation[ sanitize_title( wp_unslash( $key ) ) ] = wp_unslash( $value );
		}

		if ( $passed_validation && false !== \WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}

			\WC_AJAX::get_refreshed_fragments();

		} else {
			// If there was an error adding to the cart, redirect to the product page to show any errors.
			$data = array(
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			);

			wp_send_json( $data );
		}
	}

	public function search_products() {
		$search_terms    = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
		$search_category = ! empty( $_GET['product_cat'] ) ? sanitize_text_field( $_GET['product_cat'] ) : '';

		$search_in_sku     = \Minimog::setting( 'popup_search_in_sku' );
		$search_in_content = \Minimog::setting( 'popup_search_in_content' );
		$search_in_excerpt = \Minimog::setting( 'popup_search_in_excerpt' );

		$search_args = [
			'number'   => intval( \Minimog::setting( 'section_popup_search_number_results' ) ),
			'title'    => $search_terms,
			'category' => $search_category,
			'sku'      => ! empty( $search_in_sku ) ? $search_terms : '',
			'content'  => ! empty( $search_in_content ) ? $search_terms : '',
			'excerpt'  => ! empty( $search_in_excerpt ) ? $search_terms : '',
		];

		$products = $this->get_products( $search_args );

		$template = '';

		if ( ! empty( $products ) ) {
			ob_start();

			global $post;

			foreach ( $products as $post ):
				setup_postdata( $post );

				wc_get_template_part( 'content', 'product' );

			endforeach;
			wp_reset_postdata();

			$template = ob_get_clean();
		}

		wp_send_json_success( [
			'template' => $template,
		] );
	}

	public function get_products( $args = array() ) {
		$defaults  = [
			'title'       => '',
			'category'    => '',
			'sku'         => '',
			'content'     => '',
			'excerpt'     => '',
			'number'      => 10,
			'offset'      => 0,
			'post_status' => array( 'publish' ),
			'get_total'   => false,
		];
		$tax_query = [];

		$args = wp_parse_args( $args, $defaults );

		global $wpdb;

		$sql_select     = "SELECT $wpdb->posts.* FROM $wpdb->posts";
		$sql_join       = "";
		$sql_where      = "WHERE 1 = 1 AND $wpdb->posts.post_type = 'product'";
		$sql_orderby    = "ORDER BY $wpdb->posts.post_date DESC";
		$sql_limit      = "LIMIT {$args['offset']}, {$args['number']}";
		$sql_group_by   = '';
		$sql_where_like = [];
		$sql_variables  = [];

		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) { // WPML Compatible.
				$sql_join  .= " LEFT JOIN {$wpdb->prefix}icl_translations AS wpml_translations ON ($wpdb->posts.ID = wpml_translations.element_id) AND wpml_translations.element_type = 'post_product'";
				$sql_where .= " AND wpml_translations.language_code = '" . ICL_LANGUAGE_CODE . "'";
			} elseif ( defined( 'POLYLANG' ) ) { // Polylang Compatible.
				$pll_term = get_term_by( 'slug', ICL_LANGUAGE_CODE, $taxonomy = 'language' );
				$lang_id  = $pll_term->term_taxonomy_id;

				$sql_join  .= " LEFT JOIN {$wpdb->term_relationships} AS ppl_translations ON ($wpdb->posts.ID = ppl_translations.object_id)";
				$sql_where .= " AND ppl_translations.term_taxonomy_id IN ( $lang_id ) ";
			}
		}

		if ( ! empty( $args['post_status'] ) ) {
			$post_status = (array) $args['post_status'];
			$statuses    = "'" . implode( "','", $post_status ) . "'";
			$sql_where   .= " AND $wpdb->posts.post_status IN({$statuses})";
		}

		if ( ! empty( $args['title'] ) ) {
			$sql_where_like[] = "$wpdb->posts.post_title LIKE %s";
			$sql_variables[]  = '%' . $wpdb->esc_like( $args['title'] ) . '%';
		}

		if ( ! empty( $args['content'] ) ) {
			$sql_where_like[] = "$wpdb->posts.post_content LIKE %s";
			$sql_variables[]  = '%' . $wpdb->esc_like( $args['content'] ) . '%';
		}

		if ( ! empty( $args['excerpt'] ) ) {
			$sql_where_like[] = "$wpdb->posts.post_excerpt LIKE %s";
			$sql_variables[]  = '%' . $wpdb->esc_like( $args['excerpt'] ) . '%';
		}

		if ( ! empty( $args['sku'] ) ) {
			$sql_join         .= " LEFT JOIN {$wpdb->postmeta} AS pm_sku ON $wpdb->posts.ID = pm_sku.post_id";
			$sql_where_like[] = "(pm_sku.meta_key = '_sku' AND pm_sku.meta_value LIKE %s)";
			$sql_variables[]  = '%' . $wpdb->esc_like( $args['sku'] ) . '%';
		}

		if ( ! empty( $sql_where_like ) ) {
			$sql_where .= " AND (" . implode( ' OR ', $sql_where_like ) . ")";
		}

		if ( ! empty( $args['category'] ) ) {
			$tax_query[] = [
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => [ $args['category'] ],
			];
		}

		$product_visibility_terms  = wc_get_product_visibility_term_ids();
		$product_visibility_not_in = array(
			$product_visibility_terms['exclude-from-search'],
		);

		// Hide out of stock products.
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$product_visibility_not_in[] = $product_visibility_terms['outofstock'];
		}

		if ( ! empty( $product_visibility_not_in ) ) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_not_in,
				'operator' => 'NOT IN',
			);
		}

		if ( ! empty( $tax_query ) ) {
			$tax_query['relation'] = 'AND';
			$wp_tax_query          = new \WP_Tax_Query( $tax_query );
			$tax_query_sql         = $wp_tax_query->get_sql( $wpdb->posts, 'ID' );

			$sql_join     .= $tax_query_sql['join'];
			$sql_where    .= $tax_query_sql['where'];
			$sql_group_by = " GROUP BY $wpdb->posts.ID";
		}

		if ( ! empty( $args['get_total'] ) ) {
			$sql_select = "SELECT COUNT(DISTINCT $wpdb->posts.ID) FROM {$wpdb->posts}";

			if ( empty( $sql_variables ) ) {
				$sql_query = $wpdb->prepare( "$sql_select $sql_join $sql_where;" );
			} else {
				$sql_query = $wpdb->prepare( "$sql_select $sql_join $sql_where;", $sql_variables );
			}

			return absint( $wpdb->get_var( $sql_query ) );
		} else {
			if ( empty( $sql_variables ) ) {
				$sql_query = $wpdb->prepare( "$sql_select $sql_join $sql_where $sql_group_by $sql_orderby $sql_limit;" );
			} else {
				$sql_query = $wpdb->prepare( "$sql_select $sql_join $sql_where $sql_group_by $sql_orderby $sql_limit;", $sql_variables );
			}

			return $wpdb->get_results( $sql_query, OBJECT );
		}
	}

	public function get_product_tab_content( $args = array() ) {
		/**
		 * @var string $source
		 * @var int    $number
		 * @var string $layout
		 * @var string $style
		 * @var array  $include_term_ids
		 * @var array  $settings Product loop settings
		 */
		$defaults = [
			'source'           => 'latest',
			'number'           => 10,
			'layout'           => 'grid',
			'style'            => 'grid-01',
			'include_term_ids' => [],
			'loop_settings'    => [],
		];

		$args = wp_parse_args( $args, $defaults );
		extract( $args );
		/**
		 * Important Note:
		 * Used wpdb instead WP_Query because WP_Query auto appended logged author id & their post IDs.
		 * This happening only on admin_ajax
		 */

		global $wpdb;

		$product_post_type = 'product';

		$sql_select   = "SELECT {$wpdb->posts}.* FROM {$wpdb->posts}";
		$sql_join     = '';
		$sql_group_by = '';
		$sql_orderby  = " ORDER BY {$wpdb->posts}.post_date DESC";
		$sql_where    = " WHERE {$wpdb->posts}.post_type = '{$product_post_type}' AND {$wpdb->posts}.post_status = 'publish' ";
		$sql_limit    = " LIMIT 0, {$number}";
		$include_ids  = [];
		$tax_query    = [];

		switch ( $source ) {
			case 'trending' :
				/**
				 * WP-PostViews
				 */

				if ( function_exists( 'the_views' ) ) {
					$sql_join     = " INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )";
					$sql_where    .= " AND ( {$wpdb->postmeta}.meta_key = 'views')";
					$sql_orderby  = " ORDER BY {$wpdb->postmeta}.meta_value+0 DESC";
					$sql_group_by = " GROUP BY {$wpdb->posts}.ID";
				}
				break;
			case 'on_sale' :
				$product_ids_on_sale = wc_get_product_ids_on_sale();
				$include_ids         = array_merge( $include_ids, array_values( $product_ids_on_sale ) );
				break;
			case 'best_selling':
				$sql_join     = " INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )";
				$sql_where    .= " AND ( {$wpdb->postmeta}.meta_key = 'total_sales')";
				$sql_orderby  = " ORDER BY {$wpdb->postmeta}.meta_value+0 DESC";
				$sql_group_by = " GROUP BY {$wpdb->posts}.ID";
				break;
			case 'top_rated':
				$sql_join     = " INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )";
				$sql_where    .= " AND ( {$wpdb->postmeta}.meta_key = '_wc_average_rating')";
				$sql_orderby  = " ORDER BY {$wpdb->postmeta}.meta_value+0 DESC";
				$sql_group_by = " GROUP BY {$wpdb->posts}.ID";
				break;
			case 'featured' :
				$tax_query[] = [
					'taxonomy' => 'product_visibility',
					'field'    => 'slug',
					'terms'    => [ 'featured' ],
				];
				break;
		}

		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) { // WPML Compatible.
				$sql_join  .= " LEFT JOIN {$wpdb->prefix}icl_translations AS wpml_translations ON ($wpdb->posts.ID = wpml_translations.element_id) AND wpml_translations.element_type = 'post_product'";
				$sql_where .= " AND wpml_translations.language_code = '" . ICL_LANGUAGE_CODE . "'";
			} elseif ( defined( 'POLYLANG' ) ) { // Polylang Compatible.
				$pll_term = get_term_by( 'slug', ICL_LANGUAGE_CODE, $taxonomy = 'language' );
				$lang_id  = $pll_term->term_taxonomy_id;

				$sql_join  .= " LEFT JOIN {$wpdb->term_relationships} AS ppl_translations ON ($wpdb->posts.ID = ppl_translations.object_id)";
				$sql_where .= " AND ppl_translations.term_taxonomy_id IN ( $lang_id ) ";
			}
		}

		$product_visibility_terms  = wc_get_product_visibility_term_ids();
		$product_visibility_not_in = array( $product_visibility_terms['exclude-from-search'] );

		// Hide out of stock products.
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$product_visibility_not_in[] = $product_visibility_terms['outofstock'];
		}

		if ( ! empty( $product_visibility_not_in ) ) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_not_in,
				'operator' => 'NOT IN',
			);
		}

		if ( ! empty( $include_term_ids ) ) {
			$tax_query = $this->build_terms_query( $tax_query, $include_term_ids );
		}

		if ( ! empty( $tax_query ) ) {
			$tax_query['relation'] = 'AND';

			$wp_tax_query = new \WP_Tax_Query( $tax_query );

			$tax_query_sql = $wp_tax_query->get_sql( $wpdb->posts, 'ID' );

			$sql_join     .= $tax_query_sql['join'];
			$sql_where    .= $tax_query_sql['where'];
			$sql_group_by = " GROUP BY {$wpdb->posts}.ID";
		}

		if ( ! empty( $include_ids ) ) {
			$include_ids = array_unique( array_map( 'intval', $include_ids ) );
			$implode_ids = implode( ',', $include_ids );

			$sql_where .= " AND {$wpdb->posts}.ID IN ( {$implode_ids} )";
		}

		$sql           = "{$sql_select} {$sql_join} {$sql_where} {$sql_group_by} {$sql_orderby} {$sql_limit}";
		$query_results = $wpdb->get_results( $sql, OBJECT );

		if ( is_array( $query_results ) && count( $query_results ) ) :
			ob_start();

			global $post;

			foreach ( $query_results as $post ) :
				setup_postdata( $post );

				/**
				 * Hook: woocommerce_shop_loop.
				 *
				 * @hooked WC_Structured_Data::generate_product_data() - 10
				 */
				do_action( 'woocommerce_shop_loop' );

				minimog_get_wc_template_part( 'content-product', $style, [
					'settings' => $args['loop_settings'],
				] );
				?>
			<?php endforeach; ?>
			<?php
			wp_reset_postdata();

			$template   = ob_get_clean();
			$template   = preg_replace( '~>\s+<~', '><', $template );
			$post_found = true;
		else :
			$template   = esc_html__( 'Sorry, we can not find any products for this search.', 'minimog' );
			$post_found = false;
		endif;

		$results = [
			'found'    => $post_found,
			'template' => $template,
		];

		return $results;
	}

	public function ajax_get_product_tab_content() {
		$args = [];

		if ( isset( $_GET['source'] ) ) {
			$args['source'] = sanitize_text_field( $_GET['source'] );
		}

		if ( isset( $_GET['number'] ) ) {
			$args['number'] = sanitize_text_field( $_GET['number'] );
		}

		if ( isset( $_GET['layout'] ) ) {
			$args['layout'] = sanitize_text_field( $_GET['layout'] );
		}

		if ( isset( $_GET['style'] ) ) {
			$args['style'] = sanitize_text_field( $_GET['style'] );
		}

		if ( isset( $_GET['include_term_ids'] ) ) {
			$args['include_term_ids'] = array_map( 'intval', $_GET['include_term_ids'] );
		}

		if ( isset( $_GET['loop_settings'] ) ) {
			$args['loop_settings'] = $_GET['loop_settings'];
		}

		$response = $this->get_product_tab_content( $args );

		wp_send_json_success( $response );
	}

	public function build_terms_query( $tax_query, $term_ids ) {
		$terms = [];

		// Switch to term_id in order to get all term children (sub-categories):
		foreach ( $term_ids as $id ) {
			$term_data = get_term_by( 'term_taxonomy_id', $id );

			if ( false !== $term_data ) {
				$taxonomy             = $term_data->taxonomy;
				$terms[ $taxonomy ][] = $term_data->slug;
			}
		}

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $taxonomy => $ids ) {
				$query = [
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $ids,
				];

				$tax_query[] = $query;
			}
		}

		return $tax_query;
	}
}

Ajax_Handlers::instance()->initialize();
