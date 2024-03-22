<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Product_Category {
	protected static $instance = null;

	const TAXONOMY_NAME = 'product_cat';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {

	}

	/**
	 * Get min price of all products in given category.
	 *
	 * @param int $term_id
	 *
	 * @return int|float
	 */
	public function get_min_price( $term_id ) {
		global $wpdb;

		$terms          = [
			$term_id,
		];
		$children_terms = get_term_children( $term_id, self::TAXONOMY_NAME );

		if ( is_array( $children_terms ) ) {
			$terms = array_merge( $terms, $children_terms );
		}

		$sql_terms_in = implode( ',', $terms );

		$query_sql = "SELECT min( min_price ) FROM {$wpdb->wc_product_meta_lookup} AS product
				LEFT JOIN {$wpdb->term_relationships} AS category ON (product.product_id = category.object_id)
				WHERE category.term_taxonomy_id IN ( {$sql_terms_in} )";

		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5( $query_sql );
		// Maybe store a transient of the count values.
		$cache     = apply_filters( 'minimog/product_category/min_price/maybe_cache', true );
		$cache_key = 'minimog_product_category_min_price';
		if ( true === $cache ) {
			$cached_values = get_transient( $cache_key );
			$cached_values = is_array( $cached_values ) ? $cached_values : array();
		} else {
			$cached_values = array();
		}

		if ( ! isset( $cached_values[ $query_hash ] ) ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$min_price = $wpdb->get_var( $query_sql );

			$cached_values[ $query_hash ] = $min_price;
			if ( true === $cache ) {
				set_transient( $cache_key, $cached_values, DAY_IN_SECONDS );
			}
		}

		return $cached_values[ $query_hash ];
	}
}

Product_Category::instance()->initialize();
