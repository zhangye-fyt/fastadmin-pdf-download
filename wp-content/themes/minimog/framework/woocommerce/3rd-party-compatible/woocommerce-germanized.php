<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

/**
 * Compatible with Germanized for WooCommerce plugin.
 *
 * @see https://wordpress.org/plugins/woocommerce-germanized/
 */
class WooCommerce_Germanized {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		// Fix duplicate info about shipping + tax in mini cart total.
		remove_action( 'woocommerce_widget_shopping_cart_before_buttons', 'woocommerce_gzd_template_mini_cart_taxes', 10 );

		// Improvement checkout's layout when multistep enabled pro version.
		add_filter( 'minimog/checkout/left_column/css_class', [ $this, 'change_left_column_css_class' ] );
		add_filter( 'minimog/checkout/right_column/css_class', [ $this, 'change_right_column_css_class' ] );
	}

	/**
	 * Check whether the plugin activated
	 *
	 * @return boolean true if plugin activated
	 */
	public function is_activated() {
		return class_exists( '\WooCommerce_Germanized' );
	}

	public function change_left_column_css_class( $classes ) {
		global $woocommerce_germanized_pro;
		if ( isset( $woocommerce_germanized_pro->multistep_checkout ) ) {
			/**
			 * @var \WC_GZDP_Multistep_Checkout $wc_gzdp_multistep
			 */
			$wc_gzdp_multistep = $woocommerce_germanized_pro->multistep_checkout;

			if ( $wc_gzdp_multistep->is_enabled() ) {
				foreach ( $classes as $key => $class ) {
					if ( 'col-md-7' === $class ) {
						unset( $classes[ $key ] );
						break;
					}
				}
				$classes[] = 'col-md-12';
			}
		}

		return $classes;
	}

	public function change_right_column_css_class( $classes ) {
		global $woocommerce_germanized_pro;
		if ( isset( $woocommerce_germanized_pro->multistep_checkout ) ) {
			/**
			 * @var \WC_GZDP_Multistep_Checkout $wc_gzdp_multistep
			 */
			$wc_gzdp_multistep = $woocommerce_germanized_pro->multistep_checkout;

			if ( $wc_gzdp_multistep->is_enabled() ) {
				foreach ( $classes as $key => $class ) {
					if ( 'col-md-5' === $class ) {
						unset( $classes[ $key ] );
						break;
					}
				}
				$classes[] = 'col-md-12';
			}
		}

		return $classes;
	}
}

WooCommerce_Germanized::instance()->initialize();
