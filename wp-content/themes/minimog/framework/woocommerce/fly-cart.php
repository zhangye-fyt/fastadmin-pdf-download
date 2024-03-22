<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Fly_Cart {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'wp_footer', [ $this, 'fly_cart_template' ] );

		/**
		 * Change button order.
		 */
		remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
		remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );

		add_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 10 );
		add_action( 'woocommerce_widget_shopping_cart_buttons', [ $this, 'shopping_cart_button_view_cart' ], 20 );

		add_action( 'wp_ajax_minimog_update_product_quantity', [ $this, 'update_product_quantity' ] );
		add_action( 'wp_ajax_nopriv_minimog_update_product_quantity', [ $this, 'update_product_quantity' ] );

		add_filter( 'woocommerce_checkout_get_value', [ $this, 'output_order_notes' ], 10, 2 );
		add_action( 'wp_ajax_minimog_save_order_notes', [ $this, 'save_order_notes' ] );
		add_action( 'wp_ajax_nopriv_minimog_save_order_notes', [ $this, 'save_order_notes' ] );

		add_action( 'wp_ajax_minimog_calculate_shipping', [ $this, 'calculate_shipping' ] );
		add_action( 'wp_ajax_nopriv_minimog_calculate_shipping', [ $this, 'calculate_shipping' ] );

		add_action( 'wp_ajax_minimog_update_shipping_method', [ $this, 'update_shipping_method' ] );
		add_action( 'wp_ajax_nopriv_minimog_update_shipping_method', [ $this, 'update_shipping_method' ] );

		add_action( 'wp_ajax_minimog_apply_coupon', [ $this, 'apply_coupon' ] );
		add_action( 'wp_ajax_nopriv_minimog_apply_coupon', [ $this, 'apply_coupon' ] );

		add_action( 'wp_ajax_minimog_remove_coupon', [ $this, 'remove_coupon' ] );
		add_action( 'wp_ajax_nopriv_minimog_remove_coupon', [ $this, 'remove_coupon' ] );

		add_action( 'minimog_ajax_remove_from_cart', [ $this, 'remove_from_cart' ] );
	}

	public static function remove_from_cart() {
		ob_start();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$cart_item_key = wc_clean( isset( $_POST['cart_item_key'] ) ? wp_unslash( $_POST['cart_item_key'] ) : '' );

		if ( $cart_item_key && false !== WC()->cart->remove_cart_item( $cart_item_key ) ) {
			$data = self::get_refreshed_fragments();

			$output = [];

			ob_start();
			wc_get_template( 'cart/cart-content.php' );
			$output['#cart-table-wrap .cart-content'] = ob_get_clean();

			$data['output'] = $output;

			wp_send_json( $data );
		} else {
			wp_send_json_error();
		}
	}

	/**
	 * @see \WC_AJAX::get_refreshed_fragments()
	 * Get a refreshed cart fragment, including the mini cart HTML.
	 * Return data instead of send it.
	 */
	public static function get_refreshed_fragments() {
		ob_start();

		woocommerce_mini_cart();

		$mini_cart = ob_get_clean();

		$data = array(
			'fragments' => apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
				)
			),
			'output'    => [

			],
			'cart_hash' => WC()->cart->get_cart_hash(),
		);

		return $data;
	}

	/**
	 * @see \WC_Shortcode_Cart::output()
	 */
	public function calculate_shipping() {
		// Constants.
		wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

		\WC_Shortcode_Cart::calculate_shipping();

		WC()->cart->calculate_totals();

		$fragments = array();

		ob_start();
		wc_get_template( 'cart/cart-totals-table.php' );
		$fragments['.cart-totals-table'] = ob_get_clean();

		$notices_html = wc_print_notices( true );

		ob_start();
		echo '<div class="woocommerce-notices-wrapper">' . $notices_html . '</div>';
		$fragments['.woocommerce-notices-wrapper'] = ob_get_clean();

		ob_start();
		echo '<div class="fly-cart-messages">' . $notices_html . '</div>';
		$fragments['.fly-cart-messages'] = ob_get_clean();

		ob_start();
		Free_Shipping_Label::instance()->output_cart_goal_html();
		$fragments['.cart-goal-wrap'] = ob_get_clean();

		wp_send_json_success( [
			'fragments' => $fragments,
		] );
	}

	/**
	 * @see \WC_AJAX::update_shipping_method()
	 */
	public function update_shipping_method() {
		check_ajax_referer( 'minimog-security', 'security' );

		wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		$posted_shipping_methods = isset( $_POST['shipping_method'] ) ? wc_clean( wp_unslash( $_POST['shipping_method'] ) ) : array();

		if ( is_array( $posted_shipping_methods ) ) {
			foreach ( $posted_shipping_methods as $i => $value ) {
				$chosen_shipping_methods[ $i ] = $value;
			}
		}

		WC()->session->set( 'chosen_shipping_methods', $chosen_shipping_methods );

		WC()->cart->calculate_totals();

		$fragments = array();

		ob_start();
		wc_get_template( 'cart/cart-totals-table.php' );
		$fragments['.cart-totals-table'] = ob_get_clean();

		wp_send_json_success( [
			'fragments' => $fragments,
		] );
	}

	public function fly_cart_template() {
		if ( ! \Minimog_Global::instance()->get_fly_cart() ) {
			return;
		}

		if ( is_cart() || is_checkout() ) {
			wc_get_template( 'cart/cart-data-js.php' );

			return;
		}

		wc_get_template( 'cart/fly-cart.php' );
	}

	public function shopping_cart_button_view_cart() {
		if ( '1' !== \Minimog::setting( 'shopping_cart_drawer_view_cart_button_enable' ) ) {
			return;
		}

		\Minimog_Templates::render_button( [
			'text'        => esc_html__( 'View cart', 'minimog' ),
			'link'        => [
				'url' => esc_url( wc_get_cart_url() ),
			],
			'extra_class' => 'view-cart',
			'style'       => 'bottom-line',
			'wrapper'     => false,
		] );
	}

	public function update_product_quantity() {
		/**
		 * Required define this constant to make it working properly.
		 * Some 3rd plugins has hooks check is_cart. For eg: Condition Discount.
		 */
		wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

		$cart_item_key      = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : false;
		$cart_item_quantity = isset( $_POST['cart_item_qty'] ) ? floatval( sanitize_text_field( $_POST['cart_item_qty'] ) ) : '';
		$fragments          = array();
		$errors             = new \WP_Error();

		if ( ! empty( $cart_item_key ) && ! empty( WC()->cart->get_cart_item( $cart_item_key ) ) && '' !== $cart_item_quantity ) {
			if ( $cart_item_quantity > 0 ) {
				WC()->cart->set_quantity( $cart_item_key, $cart_item_quantity );
			} else {
				WC()->cart->remove_cart_item( $cart_item_key );
			}

			WC()->cart->check_cart_coupons();
		} else {
			$errors->add( 'cart-key-invalid', esc_html__( 'Cart key not exist!', 'minimog' ) );
		}

		ob_start();
		wc_get_template( 'cart/cart-content.php' );
		$fragments['#cart-table-wrap .cart-content'] = ob_get_clean();

		if ( ! $errors->has_errors() ) {
			wp_send_json_success( [
				'fragments' => $fragments,
			] );
		} else {
			wp_send_json_error( [
				'fragments' => $fragments,
			] );
		}
	}

	public function save_order_notes() {
		$order_notes = isset( $_POST['order_notes'] ) ? sanitize_textarea_field( $_POST['order_notes'] ) : '';

		WC()->session->set( 'minimog_order_notes', $order_notes );

		wc_add_notice( __( 'Your order notes saved.', 'minimog' ) );

		$fragments = array();

		$notices_html = wc_print_notices( true );

		ob_start();
		echo '<div class="woocommerce-notices-wrapper">' . $notices_html . '</div>';
		$fragments['.woocommerce-notices-wrapper'] = ob_get_clean();

		ob_start();
		echo '<div class="fly-cart-messages">' . $notices_html . '</div>';
		$fragments['.fly-cart-messages'] = ob_get_clean();

		wp_send_json_success( [
			'fragments' => $fragments,
		] );
	}

	public function output_order_notes( $value, $input ) {
		if ( 'order_comments' === $input ) {
			$notes = WC()->session->get( 'minimog_order_notes' );

			if ( ! empty( $notes ) ) {
				return $notes;
			}
		}

		return $value;
	}

	/**
	 * AJAX apply coupon.
	 *
	 * @see \WC_AJAX::apply_coupon()
	 */
	public function apply_coupon() {
		/**
		 * Required define this constant to make it working properly.
		 * Some 3rd plugins has hooks check is_cart. For eg: Condition Discount.
		 */
		wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

		check_ajax_referer( 'apply-coupon', 'security' );
		$fragments = array();

		if ( ! empty( $_POST['coupon_code'] ) ) {
			\WC()->cart->add_discount( wc_format_coupon_code( wp_unslash( $_POST['coupon_code'] ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			ob_start();
			wc_get_template( 'cart/cart-totals-table.php' );
			$fragments['.cart-totals-table'] = ob_get_clean();

			if ( wc_coupons_enabled() ) {
				ob_start();
				Coupon::instance()->output_available_coupons();
				$fragments['.minimog-coupon-list'] = ob_get_clean();
			}

			$this->get_cart_goal_text_fragment( $fragments );
		} else {
			wc_add_notice( \WC_Coupon::get_generic_coupon_error( \WC_Coupon::E_WC_COUPON_PLEASE_ENTER ), 'error' );
		}

		$notices_html = wc_print_notices( true );

		ob_start();
		echo '<div class="woocommerce-notices-wrapper">' . $notices_html . '</div>';
		$fragments['.woocommerce-notices-wrapper'] = ob_get_clean();

		ob_start();
		echo '<div class="fly-cart-messages">' . $notices_html . '</div>';
		$fragments['.fly-cart-messages'] = ob_get_clean();

		wp_send_json_success( [
			'fragments' => $fragments,
		] );
	}

	/**
	 * AJAX remove coupon.
	 *
	 * @see \WC_AJAX::remove_coupon()
	 */
	public function remove_coupon() {
		/**
		 * Required define this constant to make it working properly.
		 * Some 3rd plugins has hooks check is_cart. For eg: Condition Discount.
		 */
		wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

		check_ajax_referer( 'remove-coupon', 'security' );

		$coupon    = isset( $_POST['coupon_code'] ) ? wc_format_coupon_code( wp_unslash( $_POST['coupon_code'] ) ) : false; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$fragments = array();

		if ( empty( $coupon ) ) {
			wc_add_notice( __( 'Sorry there was a problem removing this coupon.', 'minimog' ), 'error' );
		} else {
			WC()->cart->remove_coupon( $coupon );
			wc_add_notice( __( 'Coupon has been removed.', 'minimog' ) );

			/**
			 * We need calculate cart total value when removed a coupon.
			 * Priority 1 required to calculate before value set to session
			 *
			 * @hooked woocommerce_removed_coupon
			 * @see    \WC_Cart_Session::set_session()
			 *
			 * @note   Do it directly instead of via hook: woocommerce_removed_coupon
			 *         This make wrong total with Woocommerce Subscription
			 */
			WC()->cart->calculate_totals();

			ob_start();
			wc_get_template( 'cart/cart-totals-table.php' );
			$fragments['.cart-totals-table'] = ob_get_clean();

			if ( wc_coupons_enabled() ) {
				ob_start();
				Coupon::instance()->output_available_coupons();
				$fragments['.minimog-coupon-list'] = ob_get_clean();
			}

			$this->get_cart_goal_text_fragment( $fragments );
		}

		ob_start();
		wc_print_notices();

		if ( class_exists( '\YITH_WC_Points_Rewards_Frontend' ) ) {
			\YITH_WC_Points_Rewards_Frontend::get_instance()->print_rewards_message_in_cart();
		}

		$notices_html = ob_get_clean();

		ob_start();
		echo '<div class="woocommerce-notices-wrapper">' . $notices_html . '</div>';
		$fragments['.woocommerce-notices-wrapper'] = ob_get_clean();

		ob_start();
		echo '<div class="fly-cart-messages">' . $notices_html . '</div>';
		$fragments['.fly-cart-messages'] = ob_get_clean();

		wp_send_json_success( [ 'fragments' => $fragments ] );
	}

	public function get_cart_goal_text_fragment( &$fragments ) {
		$amount_for_free_shipping = Free_Shipping_Label::instance()->get_min_free_shipping_amount();
		$min_amount               = (float) $amount_for_free_shipping['amount'];

		if ( $min_amount > 0 ) {
			ob_start();
			wc_get_template( 'cart/cart-goal-text.php', [
				'min_amount' => $min_amount,
			] );
			$fragments['.cart-goal-text'] = ob_get_clean();
		}

		return $fragments;
	}
}

Fly_Cart::instance()->initialize();
