<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Cache_Controller {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		/**
		 * Fires after a WooCommerce system status tool has been executed.
		 *
		 * @param array $tool Details about the tool that has been executed.
		 */
		add_action( 'woocommerce_system_status_tool_executed', [ $this, 'system_status_tool_executed' ] );

		add_action( 'woocommerce_delete_product_transients', [ $this, 'delete_product_transients' ] );
	}

	public function system_status_tool_executed( $tool ) {
		$action_id = $tool['id'];

		switch ( $action_id ) {
			case 'clear_transients':
				/**
				 * @see \Minimog_WC_Widget_Base::get_filtered_term_product_counts()
				 */
				$nav_counts_transients = minimog_get_transient_like( 'wc_layered_nav_counts' );
				if ( ! empty( $nav_counts_transients ) ) {
					foreach ( $nav_counts_transients as $transient ) {
						delete_transient( $transient );
					}
				}
				break;
			case 'regenerate_thumbnails':
				\Minimog_Attachment::instance()->delete_all_cropped_info();
				break;
		}
	}

	public function delete_product_transients() {
		/**
		 * @see Product_Category::get_min_price()
		 */
		delete_transient( 'minimog_product_category_min_price' );

		/**
		 * @see \Minimog_Woo::get_product_ids_best_selling()
		 */
		delete_transient( 'minimog_product_ids_best_selling' );

		/**
		 * @see \Minimog_Woo::get_product_ids_best_selling()
		 */
		delete_transient( 'minimog_product_ids_new_arrivals' );
	}
}

Cache_Controller::instance()->initialize();
