<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

/**
 * Compatible with WooCommerce Multilingual & Multicurrency plugin.
 *
 * @see https://wpml.org/
 */
class WCML_Multi_Currency {

	private static $instance = null;

	public $ajax_actions = [
		'minimog_search_products',
		'product_quick_view',
		'get_product_tabs',
		'product_infinite_load',
		'minimog_woocommerce_add_to_cart',
		'minimog_update_product_quantity',
		'minimog_update_shipping_method',
		'minimog_calculate_shipping',
		'minimog_apply_coupon',
		'minimog_remove_coupon',
	];

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'minimog/top_bar/components/currency_switcher/output', [ $this, 'get_currency_switcher_html' ] );
		add_filter( 'minimog/header/components/currency_switcher/output', [ $this, 'get_currency_switcher_html' ] );

		add_filter( 'wcml_multi_currency_ajax_actions', [ $this, 'add_ajax_actions' ], 10, 1 );

		add_action( 'admin_init', [ $this, 'load_multi_currency_in_ajax' ], 0 );
		add_action( 'init', [ $this, 'load_multi_currency_in_ajax' ], 0 );
	}

	/**
	 * Check whether the plugin activated
	 *
	 * @return boolean true if plugin activated
	 */
	public function is_activated() {
		return class_exists( 'WCML_Multi_Currency' );
	}

	/**
	 * @param $actions
	 *
	 * @see https://wpml.org/wcml-hook/wcml_multi_currency_ajax_actions
	 *
	 * @return array
	 */
	public function add_ajax_actions( $actions ) {
		$actions[] = 'minimog_search_products';
		$actions[] = 'product_quick_view';
		$actions[] = 'get_product_tabs';
		$actions[] = 'product_infinite_load';
		$actions[] = 'minimog_woocommerce_add_to_cart';
		$actions[] = 'minimog_update_product_quantity';
		$actions[] = 'minimog_update_shipping_method';
		$actions[] = 'minimog_calculate_shipping';
		$actions[] = 'minimog_apply_coupon';
		$actions[] = 'minimog_remove_coupon';

		return $actions;
	}

	public function load_multi_currency_in_ajax() {
		/**
		 * @see \WCML_Multi_Currency::are_filters_need_loading()
		 */
		$wp_ajax_actions = apply_filters( 'wcml_multi_currency_ajax_actions', [
			'woocommerce_get_refreshed_fragments',
			'woocommerce_update_order_review',
			'woocommerce_checkout',
			'woocommerce_add_to_cart',
			'woocommerce_update_shipping_method',
			'woocommerce_json_search_products_and_variations',
			'woocommerce_add_coupon_discount',
		] );

		$minimog_ajax_actions = [
			'woocommerce_add_to_cart',
		];

		/**
		 * @see \WC_AJAX::add_ajax_events()
		 */
		$wc_ajax_actions = [
			'get_refreshed_fragments',
		];

		if ( ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $wp_ajax_actions, true ) )
		     || ( isset( $_REQUEST['minimog-ajax'] ) && in_array( $_REQUEST['minimog-ajax'], $minimog_ajax_actions, true ) )
		     || ( isset( $_REQUEST['wc-ajax'] ) && in_array( $_REQUEST['wc-ajax'], $wc_ajax_actions, true ) )

		) {
			add_filter( 'wcml_load_multi_currency_in_ajax', '__return_true' );
		}
	}

	public function get_currency_switcher_html() {
		ob_start();
		?>
		<div class="currency-switcher-menu-wrap wcml">
			<?php
			do_action( 'wcml_currency_switcher', array(
				'format'         => '%code%',
				'switcher_style' => 'wcml-dropdown',
			) );
			?>
		</div>
		<?php
		return ob_get_clean();
	}
}

WCML_Multi_Currency::instance()->initialize();
