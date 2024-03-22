<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

/**
 * Compatible with WooCommerce Extra Product Options plugin.
 *
 * @see https://codecanyon.net/item/woocommerce-extra-product-options/7908619
 */
class WooCommerce_Extra_Product_Options {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'wc_epo_override_edit_options', '__return_false' );
	}
}

WooCommerce_Extra_Product_Options::instance()->initialize();
