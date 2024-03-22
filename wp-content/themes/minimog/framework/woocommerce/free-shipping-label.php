<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Free_Shipping_Label {

	protected static $instance = null;

	private $ignore_discounts = false;

	private $shipping_packages = null;
	private $shipping_methods = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'woocommerce_before_cart', [ $this, 'output_cart_goal_html' ], 30 );

		add_filter( 'body_class', [ $this, 'body_class' ] );
	}

	/**
	 * Return true if init data for shipping okay.
	 *
	 * @return bool
	 */
	public function init_shipping_data() {
		/**
		 * @var \WC_Shipping $wc_shipping
		 * @var \WC_Cart     $wc_cart
		 */
		if ( ( $wc_shipping = WC()->shipping ) && $wc_shipping->enabled && $wc_cart = WC()->cart ) {
			if ( null === $this->shipping_packages ) {
				$this->shipping_packages = $wc_cart->get_shipping_packages();
			}

			if ( null === $this->shipping_methods ) {
				$this->shipping_methods = $wc_shipping->load_shipping_methods( $this->shipping_packages[0] );
			}

			return true;
		}

		return false;
	}

	public function body_class( $classes ) {
		if ( $this->cart_contains_only_items_free_shipping_class() ) {
			$classes [] = 'cart-includes-only-free-shipping-class';
		}

		return $classes;
	}

	public function output_cart_goal_html() {
		if ( '1' !== \Minimog::setting( 'shopping_cart_free_shipping_bar_enable' ) ) {
			return;
		}

		$amount_for_free_shipping = $this->get_min_free_shipping_amount();
		$min_amount               = (float) $amount_for_free_shipping['amount'];
		$cart_total               = $this->get_cart_total();

		if ( $min_amount > 0 ) {
			$amount_left         = 0;
			$percent_amount_done = 100;

			if ( $cart_total < $min_amount ) {
				$amount_left         = $min_amount - $cart_total;
				$percent_amount_done = \Minimog_Helper::calculate_percentage( $cart_total, $min_amount );
			}

			$template_args = [
				'min_amount'          => $min_amount,
				'amount_left'         => $amount_left,
				'percent_amount_done' => $percent_amount_done,
				'cart_total'          => $cart_total,
			];

			wc_get_template( 'cart/cart-goal.php', $template_args );
		}
	}

	public function get_min_free_shipping_amount() {
		$is_available = false;
		// Automatic min amount.
		$min_free_shipping_amount = 0;
		$this->ignore_discounts   = false;

		/**
		 * @var \WC_Shipping $wc_shipping
		 * @var \WC_Cart     $wc_cart
		 */

		$result = $this->init_shipping_data();
		if ( $result ) {
			foreach ( $this->shipping_methods as $shipping_method ) {
				if ( ! $shipping_method instanceof \WC_Shipping_Free_Shipping || 'yes' !== $shipping_method->enabled || 0 === $shipping_method->instance_id ) {
					continue;
				}

				if ( in_array( $shipping_method->requires, array( 'min_amount', 'either', 'both' ), true ) ) {
					if ( $shipping_method->is_available( $this->shipping_packages[0] ) ) {
						$is_available = true;
					}

					$this->ignore_discounts   = 'yes' === $shipping_method->ignore_discounts;
					$min_free_shipping_amount = $shipping_method->min_amount;
				}
			}
		}

		return array(
			'amount'       => $min_free_shipping_amount,
			'is_available' => $is_available,
		);
	}

	/**
	 * @see \WC_Shipping_Free_Shipping::is_available()
	 */
	public function get_cart_total() {
		if ( ! function_exists( 'WC' ) || ! isset( WC()->cart ) ) {
			return 0;
		}

		$total = WC()->cart->get_displayed_subtotal();

		if ( WC()->cart->display_prices_including_tax() ) {
			$total = $total - WC()->cart->get_discount_tax();
		}

		if ( ! $this->ignore_discounts ) {
			$total = $total - WC()->cart->get_discount_total();
		}

		/*
		$exclude_shipping       = false;
		$exclude_shipping_taxes = false;
		if ( $exclude_shipping ) {
			$shipping_taxes = $exclude_shipping_taxes ? WC()->cart->get_shipping_tax() : 0;
			$total          = $total - ( WC()->cart->get_shipping_total() + $shipping_taxes );
		}
		*/

		$total = round( $total, wc_get_price_decimals() );

		return (float) $total;
	}

	public function cart_contains_only_items_free_shipping_class() {
		$result = $this->init_shipping_data();
		if ( ! $result ) {
			return false;
		}

		$wc_cart                    = WC()->cart;
		$shipping_classes_in_cart   = [];
		$has_shipping_class_in_cart = false;
		$flag                       = false;

		foreach ( $wc_cart->get_cart() as $cart_item ) {
			/**
			 * @var \WC_Product $product
			 */
			$product           = $cart_item['data'];
			$shipping_class_id = $product->get_shipping_class_id();
			if ( $shipping_class_id ) {
				$shipping_classes_in_cart[] = 'class_cost_' . $shipping_class_id;
				$has_shipping_class_in_cart = true;
			} else {
				$shipping_classes_in_cart[] = 'no_class_cost';
			}
		}

		if ( $has_shipping_class_in_cart ) {
			$shipping_classes_in_cart = array_unique( $shipping_classes_in_cart );

			foreach ( $this->shipping_methods as $shipping_method ) {
				if ( ! $shipping_method instanceof \WC_Shipping_Flat_Rate || 'yes' !== $shipping_method->enabled || 0 === $shipping_method->instance_id || empty( $shipping_method->instance_settings ) ) {
					continue;
				}

				$shipping_settings = $shipping_method->instance_settings;

				foreach ( $shipping_classes_in_cart as $class_cost_id ) {
					if ( isset( $shipping_settings[ $class_cost_id ] ) ) {
						if ( $shipping_settings[ $class_cost_id ] === '0' ) {
							$flag = true;
						} else { // Exit if any shipping classes cost != 0
							return false;
						}
					}
				}
			}
		}

		return $flag;
	}
}

Free_Shipping_Label::instance()->initialize();
