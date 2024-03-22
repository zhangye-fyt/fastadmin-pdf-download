<?php
defined( 'ABSPATH' ) || exit;

/**
 * Abstract Class: Woocommerce Widget Base
 *
 * @version  1.0
 * @extends  WP_Widget
 */
if ( ! class_exists( 'Minimog_WC_Widget_Base' ) ) {
	abstract class Minimog_WC_Widget_Base extends Minimog_Widget {

		/**
		 * Get current page URL with various filtering props supported by WC.
		 *
		 * @return string
		 * @since  3.3.0
		 */
		protected function get_current_page_url() {
			$link = \Minimog_Woo::instance()->get_shop_active_filters_url( $_GET );

			return apply_filters( 'woocommerce_widget_get_current_page_url', $link, $this );
		}

		/**
		 * Return the currently viewed taxonomy name.
		 *
		 * @return string
		 */
		protected function get_current_taxonomy() {
			return is_tax() ? get_queried_object()->taxonomy : '';
		}

		/**
		 * Return the currently viewed term ID.
		 *
		 * @return int
		 */
		protected function get_current_term_id() {
			return absint( Minimog_Woo::instance()->is_product_taxonomy() ? get_queried_object()->term_id : 0 );
		}

		/**
		 * Return the currently viewed term slug.
		 *
		 * @return int
		 */
		protected function get_current_term_slug() {
			return absint( is_tax() ? get_queried_object()->slug : 0 );
		}

		/**
		 * Get current values filtered from url.
		 *
		 * @param $filter_name
		 *
		 * @return array
		 */
		protected function get_chosen_terms( $filter_name ) {
			$terms = [];

			if ( ! empty( $_GET[ $filter_name ] ) ) {
				$terms = array_map( 'intval', explode( ',', $_GET[ $filter_name ] ) );
			}

			if ( Minimog_Woo::instance()->is_product_taxonomy() ) {
				$terms[] = get_queried_object()->term_id;
			}

			return $terms;
		}

		/**
		 * Count products within certain terms, taking the main WP query into consideration.
		 *
		 * @see \Automattic\WooCommerce\Internal\ProductAttributesLookup\Filterer::get_filtered_term_product_counts()
		 *
		 * @param  array  $term_ids
		 * @param  string $taxonomy
		 * @param  string $query_type
		 * @param  bool   $log Debugging.
		 *
		 * @return array term_id => product_count
		 */
		protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type, $log = false ) {
			global $wpdb;

			$tax_query  = \Minimog\Woo\Product_Query::get_main_tax_query();
			$meta_query = \Minimog\Woo\Product_Query::get_main_meta_query();

			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}

			$meta_query      = new WP_Meta_Query( $meta_query );
			$tax_query       = new WP_Tax_Query( $tax_query );
			$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );
			$price_query_sql = \Minimog\Woo\Product_Query::instance()->get_main_price_query_sql();

			// Generate query.
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, {$wpdb->posts}.ID, terms.term_id as term_count_id";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'] . $price_query_sql['join'];

			$query['where'] = "
			WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . $price_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";

			$search_query_sql = \Minimog\Woo\Product_Query::get_main_search_query_sql();
			if ( $search_query_sql ) {
				$query['where'] .= ' AND ' . $search_query_sql;
			}

			$query['group_by'] = "GROUP BY terms.term_id";
			$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query_sql         = implode( ' ', $query );

			// We have a query - let's see if cached results of this query already exist.
			$query_hash = md5( $query_sql );
			// Maybe store a transient of the count values.
			$cache     = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
			$cache_key = 'wc_layered_nav_counts_' . sanitize_title( $taxonomy );
			if ( true === $cache ) {
				$cached_counts = (array) get_transient( $cache_key );
			} else {
				$cached_counts = array();
			}

			if ( ! isset( $cached_counts[ $query_hash ] ) ) {
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$results = $wpdb->get_results( $query_sql, ARRAY_A );
				$counts  = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );

				$cached_counts[ $query_hash ] = $counts;
				if ( true === $cache ) {
					set_transient( $cache_key, $cached_counts, DAY_IN_SECONDS );
				}
			}

			return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
		}

		/**
		 * Taxonomy has hierarchy like product_cat then it make wrong count when product add to multi sibling categories or add both to parent and child cat.
		 * Use this function to fix it.
		 *
		 * @param      $term_ids
		 * @param      $taxonomy
		 * @param      $query_type
		 * @param bool $log
		 *
		 * @return mixed
		 */
		protected function get_filtered_term_product_counts_for_hierarchy_tax( $term_ids, $taxonomy, $query_type, $log = false ) {
			global $wpdb;

			$tax_query  = \Minimog\Woo\Product_Query::get_main_tax_query();
			$meta_query = \Minimog\Woo\Product_Query::get_main_meta_query();

			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}

			$meta_query      = new WP_Meta_Query( $meta_query );
			$tax_query       = new WP_Tax_Query( $tax_query );
			$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );
			$price_query_sql = \Minimog\Woo\Product_Query::instance()->get_main_price_query_sql();

			// Generate query.
			$query           = array();
			$query['select'] = "SELECT {$wpdb->posts}.ID";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
			LEFT JOIN {$wpdb->term_relationships} AS product_cat ON {$wpdb->posts}.ID = product_cat.object_id
			" . $tax_query_sql['join'] . $meta_query_sql['join'] . $price_query_sql['join'];

			$query['where'] = "
			WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . $price_query_sql['where'] . "
			AND product_cat.term_taxonomy_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";

			$search_query_sql = \Minimog\Woo\Product_Query::get_main_search_query_sql();
			if ( $search_query_sql ) {
				$query['where'] .= ' AND ' . $search_query_sql;
			}
			
			$query     = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
			$query_sql = implode( ' ', $query );

			// We have a query - let's see if cached results of this query already exist.
			$query_hash = md5( $query_sql );
			// Maybe store a transient of the count values.
			$cache     = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', false );
			$cache_key = 'wc_layered_nav_counts_' . sanitize_title( $taxonomy );
			if ( true === $cache ) {
				$cached_counts = (array) get_transient( $cache_key );
			} else {
				$cached_counts = array();
			}

			if ( ! isset( $cached_counts[ $query_hash ] ) ) {
				// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$results  = $wpdb->get_results( $query_sql, ARRAY_A );
				$products = [];

				foreach ( $results as $record ) {
					if ( ! in_array( $record['ID'], $products ) ) {
						$products[] = $record['ID'];
					}
				}

				$product_counts = count( $products );

				$cached_counts[ $query_hash ] = $product_counts;
				if ( true === $cache ) {
					set_transient( $cache_key, $cached_counts, DAY_IN_SECONDS );
				}
			}

			return $cached_counts[ $query_hash ];
		}
	}
}
