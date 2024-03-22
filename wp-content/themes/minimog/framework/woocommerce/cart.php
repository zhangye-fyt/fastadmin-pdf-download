<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Cart {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'woocommerce_before_cart', [ $this, 'output_cart_countdown_timer' ], 20 );

		add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'cart_fragments' ] );

		add_action( 'init', [ $this, 'cart_cross_sell_display' ], 999 );

		add_filter( 'wc_empty_cart_message', [ $this, 'change_empty_cart_messages' ] );
	}

	/**
	 * Update mini cart when products are added or removed to the cart via AJAX
	 *
	 * @param $fragments
	 *
	 * @return array
	 */
	public function cart_fragments( $fragments ) {
		/**
		 * Required define this constant to make it working properly.
		 * Some 3rd plugins has hooks check is_cart. For eg: Condition Discount.
		 */
		wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

		$fragments['.mini-cart-badge'] = $this->get_mini_cart_badge();
		//$fragments['.mini-cart-text-badge'] = $this->get_mini_cart_text_badge();
		$fragments['.mini-cart-total'] = $this->get_mini_cart_total();

		ob_start();
		wc_get_template( 'cart/cart-totals-table.php' );
		$fragments['.cart-totals-table'] = ob_get_clean();

		ob_start();
		wc_get_template( 'cart/cart-data-js.php' );
		$fragments['.cart-data-js'] = ob_get_clean();

		Fly_Cart::instance()->get_cart_goal_text_fragment( $fragments );

		if ( wc_coupons_enabled() ) {
			ob_start();
			Coupon::instance()->output_available_coupons();
			$fragments['.minimog-coupon-list'] = ob_get_clean();
		}

		if ( ! empty( WC()->session->get( 'wc_notices', array() ) ) ) {
			$notices_html = wc_print_notices( true );

			ob_start();
			echo '<div class="woocommerce-notices-wrapper">' . $notices_html . '</div>';
			$fragments['.woocommerce-notices-wrapper'] = ob_get_clean();

			ob_start();
			echo '<div class="fly-cart-messages">' . $notices_html . '</div>';
			$fragments['.fly-cart-messages'] = ob_get_clean();
		}

		return $fragments;
	}

	public function get_mini_cart_badge() {
		if ( empty( WC()->cart ) ) {
			return '';
		}

		$qty = WC()->cart->get_cart_contents_count();

		return '<div class="icon-badge mini-cart-badge" data-count="' . esc_attr( $qty ) . '">' . $qty . '</div>';
	}

	public function get_mini_cart_text_badge() {
		if ( empty( WC()->cart ) ) {
			return '';
		}

		$qty = WC()->cart->get_cart_contents_count();

		return '<span class="mini-cart-text-badge">' . $qty . '</span>';
	}

	public function get_mini_cart_total() {
		ob_start();
		echo '<div class="mini-cart-total">';
		wc_cart_totals_order_total_html();
		echo '<div>';
		$cart_total = ob_get_clean();

		return $cart_total;
	}

	public function cart_cross_sell_display() {
		// Remove Cross Sells from default position at Cart. Then add them back UNDER the Cart Table.
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		if ( '1' === \Minimog::setting( 'shopping_cart_cross_sells_enable' ) ) {
			add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
		}
	}

	public function change_empty_cart_messages() {
		$cart_empty_image = \Minimog::setting( 'shopping_cart_empty_image' );

		if ( ! empty( $cart_empty_image['id'] ) ) {
			$image_html = \Minimog_Image::get_attachment_by_id( [
				'id' => $cart_empty_image['id'],
			] );
		} else {
			$image_attrs = [
				'class'  => '',
				'src'    => MINIMOG_THEME_ASSETS_URI . '/woocommerce/empty-cart.png',
				'width'  => 350,
				'height' => 307,
				'alt'    => __( 'Cart empty', 'minimog' ),
			];

			if ( \Minimog::setting( 'image_lazy_load_enable' ) ) {
				$image_attrs['class']    .= ' ll-image';
				$image_attrs['data-src'] = $image_attrs['src'];
				$image_attrs['src']      = \Minimog_Image::get_lazy_image_src();
			}

			$image_html = \Minimog_Image::build_img_tag( $image_attrs );

			if ( \Minimog::setting( 'image_lazy_load_enable' ) ) {
				$image_html = \Minimog_Image::build_lazy_img_tag( $image_html, 350, 307 );
			}
		}
		?>
		<div class="empty-cart-messages">
			<div class="empty-cart-icon"><?php echo '' . $image_html; ?></div>
			<h2 class="empty-cart-heading"><?php esc_html_e( 'Your cart is currently empty.', 'minimog' ); ?></h2>
			<p class="empty-cart-text"><?php esc_html_e( 'You may check out all the available products and buy some in the shop.', 'minimog' ); ?></p>
		</div>
		<?php
	}

	public function output_cart_countdown_timer() {
		if ( '1' === \Minimog::setting( 'shopping_cart_countdown_enable' ) ) :
			wc_get_template( 'cart/cart-countdown.php' );
		endif;
	}
}

Cart::instance()->initialize();
