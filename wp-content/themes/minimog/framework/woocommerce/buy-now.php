<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Buy_Now {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'woocommerce_add_to_cart_redirect', [ $this, 'redirect_to_checkout' ], 99 );
	}

	/**
	 * Try redirect to checkout page after product added to cart.
	 * This function fallback when ajax add to cart disabled.
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public function redirect_to_checkout( $url ) {
		if ( isset( $_REQUEST['minimog-buy-now'] ) ) {
			return wc_get_checkout_url();
		}

		return $url;
	}

	public function get_button_text() {
		$button_text = \Minimog::setting( 'single_product_buy_now_text' );

		if ( empty( $button_text ) ) {
			$button_text = __( 'Buy Now', 'minimog' );
		}

		return $button_text;
	}
}

Buy_Now::instance()->initialize();
