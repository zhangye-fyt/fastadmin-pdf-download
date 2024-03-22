<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Product_Query {
	protected static $instance = null;

	/**
	 * Stores chosen attributes.
	 *
	 * @var array
	 */
	private static $chosen_attributes;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		/**
		 * Change main query for shop catalog.
		 */
		add_filter( 'loop_shop_per_page', [ $this, 'loop_shop_per_page' ], 20 );

		add_filter( 'loop_shop_post_in', [ $this, 'filter_by_ids' ] );

		add_action( 'woocommerce_product_query', [ $this, 'update_product_query' ] );

		add_filter( 'woocommerce_product_query_tax_query', [ $this, 'update_product_query_tax_query', ], 10, 2 );

		/**
		 * Add query vars min_price + max_price
		 */
		add_action( 'woocommerce_product_query', [ $this, 'add_wc_query_vars' ], 10, 2 );
	}

	public function add_wc_query_vars( $q, $wc_query ) {
		if ( isset( $_GET['product-page'] ) ) {
			$page = intval( sanitize_text_field( $_GET['product-page'] ) );
			$q->set( 'paged', $page );
		}

		if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			$current_min_price = isset( $_GET['min_price'] ) ? floatval( wp_unslash( $_GET['min_price'] ) ) : 0;
			$current_max_price = isset( $_GET['max_price'] ) ? floatval( wp_unslash( $_GET['max_price'] ) ) : PHP_INT_MAX;
			// phpcs:enable WordPress.Security.NonceVerification.Recommended

			$q->set( 'min_price', $current_min_price );
			$q->set( 'max_price', $current_max_price );
		}
	}

	public function loop_shop_per_page() {
		$per_page = \Minimog::setting( 'shop_archive_number_item' );

		return isset( $_GET['product_per_page'] ) ? wc_clean( $_GET['product_per_page'] ) : $per_page;
	}

	public function filter_by_ids( $ids ) {
		if ( ! empty( $_GET['highlight_filter'] ) ) {
			$highlight_filter = wc_clean( $_GET['highlight_filter'] );

			switch ( $highlight_filter ) {
				case 'on_sale':
					$product_ids_on_sale = wc_get_product_ids_on_sale();
					$ids                 = array_merge( $ids, $product_ids_on_sale );

					break;
				case 'best_selling':
					$product_ids_best_selling = \Minimog_Woo::instance()->get_product_ids_best_selling();
					$ids                      = array_merge( $ids, $product_ids_best_selling );

					break;
				case 'new_arrivals':
					$product_ids_new_arrivals = \Minimog_Woo::instance()->get_product_ids_new_arrivals();
					$ids                      = array_merge( $ids, $product_ids_new_arrivals );

					break;
			}
		}

		return $ids;
	}

	public function update_product_query() {
		add_filter( 'posts_clauses', [ $this, 'product_query_post_clauses' ], 10, 2 );
	}

	/**
	 * Add extra clauses to the product query.
	 *
	 * @param array     $args     Product query clauses.
	 * @param \WP_Query $wp_query The current product query.
	 *
	 * @return array The updated product query clauses array.
	 */
	public function product_query_post_clauses( $args, $wp_query ) {
		$args = $this->highlight_filter_post_clauses( $args, $wp_query );

		return $args;
	}

	/**
	 * Join wc_product_meta_lookup to posts if not already joined.
	 * Note: Should be same as function by wc
	 *
	 * @param string $sql SQL join.
	 *
	 * @return string
	 * @see \WC_Query::append_product_sorting_table_join()
	 */
	private function append_product_sorting_table_join( $sql ) {
		global $wpdb;

		if ( ! strstr( $sql, 'wc_product_meta_lookup' ) ) {
			$sql .= " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";
		}

		return $sql;
	}

	public function highlight_filter_post_clauses( $args, $wp_query ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! $wp_query->is_main_query() || empty( $_GET['highlight_filter'] ) ) {
			return $args;
		}

		if ( 'best_selling' !== $_GET['highlight_filter'] ) {
			return $args;
		}

		$args['join']  = $this->append_product_sorting_table_join( $args['join'] );
		$args['where'] .= " AND wc_product_meta_lookup.total_sales > '0'";

		return $args;
	}

	/**
	 * @param array     $tax_query
	 * @param \WC_Query $wc_query
	 *
	 * @return mixed
	 */
	public function update_product_query_tax_query( $tax_query, $wc_query ) {
		// Filter by featured.
		if ( ! empty( $_GET['highlight_filter'] ) ) {
			$highlight_filter = wc_clean( $_GET['highlight_filter'] );

			switch ( $highlight_filter ) {
				case 'featured':
					$tax_query[] = array(
						'taxonomy'         => 'product_visibility',
						'terms'            => 'featured',
						'field'            => 'name',
						'operator'         => 'IN',
						'include_children' => false,
					);
					break;
			}
		}

		// Filter by category.
		if ( isset( $_GET['filter_product_cat'] ) ) { // WPCS: input var ok, CSRF ok.
			$cats = array_filter( array_map( 'absint', explode( ',', $_GET['filter_product_cat'] ) ) ); // WPCS: input var ok, CSRF ok, Sanitization ok.

			if ( $cats ) {
				$tax_query[] = array(
					'taxonomy' => 'product_cat',
					'terms'    => $cats,
					'operator' => 'IN',
				);
			}
		}

		// Filter by tag.
		if ( isset( $_GET['filter_product_tag'] ) ) { // WPCS: input var ok, CSRF ok.
			$tags = array_filter( array_map( 'absint', explode( ',', $_GET['filter_product_tag'] ) ) ); // WPCS: input var ok, CSRF ok, Sanitization ok.

			if ( $tags ) {
				$tax_query[] = array(
					'taxonomy' => 'product_tag',
					'terms'    => $tags,
					'operator' => 'IN',
				);
			}
		}

		if ( isset( $_GET['filter_product_brand'] ) ) { // WPCS: input var ok, CSRF ok.
			$brands = array_filter( array_map( 'absint', explode( ',', $_GET['filter_product_brand'] ) ) ); // WPCS: input var ok, CSRF ok, Sanitization ok.

			if ( $brands ) {
				$tax_query[] = array(
					'taxonomy' => 'product_brand',
					'terms'    => $brands,
					'operator' => 'IN',
				);
			}
		}

		// Filter by by stock status.
		if ( 'yes' !== get_option( 'woocommerce_hide_out_of_stock_items' ) && isset( $_GET['stock_status'] ) && 'in_stock' === $_GET['stock_status'] ) {
			$product_visibility_terms  = wc_get_product_visibility_term_ids();
			$product_visibility_not_in = [
				$product_visibility_terms['outofstock'],
			];

			$appended = false;

			// Append terms if exist instead add new tax query.
			foreach ( $tax_query as $key => $tax_query_term ) {
				if ( is_array( $tax_query_term )
				     && ! empty( $tax_query_term['taxonomy'] )
				     && 'product_visibility' === $tax_query_term['taxonomy']
				     && 'term_taxonomy_id' === $tax_query_term['field']
				     && 'NOT IN' === $tax_query_term['operator']
				) {
					$new_terms               = array_merge( $tax_query_term['terms'], $product_visibility_not_in );
					$tax_query_term['terms'] = $new_terms;
					$tax_query[ $key ]       = $tax_query_term;

					$appended = true;
					break;
				}
			}

			if ( false === $appended ) {
				$tax_query[] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_not_in,
					'operator' => 'NOT IN',
				);
			}
		}

		return $tax_query;
	}

	public static function get_main_tax_query() {
		global $wp_query;

		$tax_query = isset( $wp_query->tax_query, $wp_query->tax_query->queries ) ? $wp_query->tax_query->queries : array();

		return $tax_query;
	}

	/**
	 * Get the meta query which was used by the main query.
	 *
	 * @return array
	 */
	public static function get_main_meta_query() {
		global $wp_query;

		$args       = $wp_query->query_vars;
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		return $meta_query;
	}

	public static function is_main_query_has_post() {
		global $wp_query;

		return isset( $wp_query->post_count ) && $wp_query->post_count > 0 ? true : false;
	}

	/**
	 * Based on WP_Query::parse_search
	 */
	public static function get_main_search_query_sql() {
		global $wpdb;
		global $wp_query;

		$args = $wp_query->query_vars;

		$search_terms = isset( $args['search_terms'] ) ? $args['search_terms'] : array();
		$sql          = array();

		foreach ( $search_terms as $term ) {
			// Terms prefixed with '-' should be excluded.
			$include = '-' !== substr( $term, 0, 1 );

			if ( $include ) {
				$like_op  = 'LIKE';
				$andor_op = 'OR';
			} else {
				$like_op  = 'NOT LIKE';
				$andor_op = 'AND';
				$term     = substr( $term, 1 );
			}

			$like = '%' . $wpdb->esc_like( $term ) . '%';
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$sql[] = $wpdb->prepare( "(($wpdb->posts.post_title $like_op %s) $andor_op ($wpdb->posts.post_excerpt $like_op %s) $andor_op ($wpdb->posts.post_content $like_op %s))", $like, $like, $like );
		}

		if ( ! empty( $sql ) && ! is_user_logged_in() ) {
			$sql[] = "($wpdb->posts.post_password = '')";
		}

		return implode( ' AND ', $sql );
	}

	public function get_main_price_query_sql( $min_price = null, $max_price = null ) {
		global $wp_query;
		global $wpdb;

		$args = $wp_query->query_vars;

		$sql = [
			'join'  => '',
			'where' => '',
		];

		$current_min_price = $current_min_price = null;

		if ( isset( $min_price ) && isset( $max_price ) ) {
			$current_min_price = floatval( $min_price );
			$current_max_price = floatval( $max_price );
		} elseif ( isset( $args['min_price'] ) && isset( $args['max_price'] ) ) {
			// phpcs:enable WordPress.Security.NonceVerification.Recommended
			$current_min_price = floatval( wp_unslash( $args['min_price'] ) );
			$current_max_price = floatval( wp_unslash( $args['max_price'] ) );
		}

		if ( isset( $current_min_price ) && isset( $current_max_price ) ) {
			/**
			 * Adjust if the store taxes are not displayed how they are stored.
			 * Kicks in when prices excluding tax are displayed including tax.
			 */
			if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
				$tax_class = apply_filters( 'woocommerce_price_filter_widget_tax_class', '' ); // Uses standard tax class.
				$tax_rates = \WC_Tax::get_rates( $tax_class );

				if ( $tax_rates ) {
					$current_min_price -= \WC_Tax::get_tax_total( \WC_Tax::calc_inclusive_tax( $current_min_price, $tax_rates ) );
					$current_max_price -= \WC_Tax::get_tax_total( \WC_Tax::calc_inclusive_tax( $current_max_price, $tax_rates ) );
				}
			}

			$sql['join']  = " LEFT JOIN {$wpdb->wc_product_meta_lookup} wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";
			$sql['where'] = $wpdb->prepare(
				' AND NOT (%f<wc_product_meta_lookup.min_price OR %f>wc_product_meta_lookup.max_price ) ',
				$current_max_price,
				$current_min_price
			);
		}

		return $sql;
	}

	public function get_tax_query( $tax_query = array(), $main_query = false ) {
		if ( ! is_array( $tax_query ) ) {
			$tax_query = array(
				'relation' => 'AND',
			);
		}

		// Layered nav filters on terms.
		foreach ( self::get_layered_nav_chosen_attributes() as $taxonomy => $data ) {
			$tax_query[] = array(
				'taxonomy'         => $taxonomy,
				'field'            => 'slug',
				'terms'            => $data['terms'],
				'operator'         => 'and' === $data['query_type'] ? 'AND' : 'IN',
				'include_children' => false,
			);
		}

		$product_visibility_terms  = wc_get_product_visibility_term_ids();
		$product_visibility_not_in = array( is_search() && $main_query ? $product_visibility_terms['exclude-from-search'] : $product_visibility_terms['exclude-from-catalog'] );

		// Hide out of stock products.
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$product_visibility_not_in[] = $product_visibility_terms['outofstock'];
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		// Filter by rating.
		if ( isset( $_GET['rating_filter'] ) ) {
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$rating_filter = array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) );
			$rating_terms  = array();
			for ( $i = 1; $i <= 5; $i++ ) {
				if ( in_array( $i, $rating_filter, true ) && isset( $product_visibility_terms[ 'rated-' . $i ] ) ) {
					$rating_terms[] = $product_visibility_terms[ 'rated-' . $i ];
				}
			}
			if ( ! empty( $rating_terms ) ) {
				$tax_query[] = array(
					'taxonomy'      => 'product_visibility',
					'field'         => 'term_taxonomy_id',
					'terms'         => $rating_terms,
					'operator'      => 'IN',
					'rating_filter' => true,
				);
			}
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( ! empty( $product_visibility_not_in ) ) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_not_in,
				'operator' => 'NOT IN',
			);
		}

		return array_filter( apply_filters( 'minimog/product_query/tax_query', $tax_query, $this ) );
	}

	/**
	 * Get an array of attributes and terms selected with the layered nav widget.
	 *
	 * @return array
	 */
	public static function get_layered_nav_chosen_attributes() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( ! is_array( self::$chosen_attributes ) ) {
			self::$chosen_attributes = array();

			if ( ! empty( $_GET ) ) {
				foreach ( $_GET as $key => $value ) {
					if ( 0 === strpos( $key, 'filter_' ) ) {
						$attribute = wc_sanitize_taxonomy_name( str_replace( 'filter_', '', $key ) );
						$taxonomy  = wc_attribute_taxonomy_name( $attribute );

						$filter_terms = ! empty( $value ) ? explode( ',', wc_clean( wp_unslash( $value ) ) ) : array();

						if ( empty( $filter_terms ) || ! taxonomy_exists( $taxonomy ) || ! wc_attribute_taxonomy_id_by_name( $attribute ) ) {
							continue;
						}

						$query_type                                         = ! empty( $_GET[ 'query_type_' . $attribute ] ) && in_array( $_GET[ 'query_type_' . $attribute ], array(
							'and',
							'or',
						), true ) ? wc_clean( wp_unslash( $_GET[ 'query_type_' . $attribute ] ) ) : '';
						self::$chosen_attributes[ $taxonomy ]['terms']      = array_map( 'sanitize_title', $filter_terms ); // Ensures correct encoding.
						self::$chosen_attributes[ $taxonomy ]['query_type'] = $query_type ? $query_type : apply_filters( 'minimog/layered_nav/default_query_type', 'and' );
					}
				}
			}
		}

		return self::$chosen_attributes;
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
	}

	public static function set_meta_query_min_price( $meta_query, $min_price ) {
		if ( ! is_array( $meta_query ) ) {
			$meta_query = array();
		}

		$meta_query[] = array(
			'key'     => '_price',
			'value'   => $min_price,
			'compare' => '>=',
			'type'    => 'NUMERIC',
		);

		return $meta_query;
	}

	public static function set_meta_query_max_price( $meta_query, $max_price ) {
		if ( ! is_array( $meta_query ) ) {
			$meta_query = array();
		}

		$meta_query[] = array(
			'key'     => '_price',
			'value'   => $max_price,
			'compare' => '<=',
			'type'    => 'NUMERIC',
		);

		return $meta_query;
	}
}

Product_Query::instance()->initialize();
