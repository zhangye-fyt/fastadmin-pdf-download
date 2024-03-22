<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Enqueue {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		// Disable woocommerce all styles.
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'update_checkout_script' ], 20 );

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
	}

	/**
	 * Load scripts and style in admin area
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function admin_scripts( $hook ) {
		$screen = get_current_screen();

		if ( in_array( $hook, [ 'post-new.php', 'post.php' ], true ) && 'product' === $screen->post_type ) {
			wp_enqueue_style( 'minimog-product-admin', MINIMOG_THEME_ASSETS_URI . '/admin/css/product-edit.min.css' );
			wp_enqueue_script( 'minimog-product-admin', MINIMOG_THEME_ASSETS_URI . '/admin/js/product.js', [ 'jquery' ], null, true );
		}
	}

	public function update_checkout_script() {
		/**
		 * Compatible with FunnelKit Checkout plugin.
		 * Somehow this script not enqueue when updated src, so do not update it in that case.
		 */
		if ( class_exists( '\WFACP_Core' ) ) {
			if ( \WFACP_Core()->public->is_checkout_override() ) {
				return;
			}
		}

		$min = '';
		/**
		 * Used edition version for better performance.
		 * Replace src instead dequeue to avoid missing scripts depends on it.
		 */
		$new_wc_checkout = MINIMOG_THEME_URI . "/assets/js/woo/checkout{$min}.js";
		\Minimog_Enqueue::instance()->update_handle_src( 'wc-checkout', $new_wc_checkout );
	}

	public function frontend_scripts() {
		$min = \Minimog_Enqueue::instance()->get_min_suffix();
		$rtl = \Minimog_Enqueue::instance()->get_rtl_suffix();

		// Remove prettyPhoto, default light box of woocommerce.
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );

		/**
		 * Dequeue style in compare
		 * Also all third party.
		 */
		wp_dequeue_style( 'hint' );

		/**
		 * Dequeue style in compare / wishlist...
		 */
		wp_dequeue_style( 'perfect-scrollbar-wpc' );

		// Check Pincode/Zipcode Shipping plugin.
		if ( class_exists( 'WPCC' ) ) {
			wp_dequeue_style( 'WPCC_front_style' );
			wp_enqueue_style( 'minimog-wpcc', MINIMOG_THEME_URI . "/assets/css/wc/check-pincode-zipcode{$min}.css", null, MINIMOG_THEME_VERSION );
		}

		if ( Compare::instance()->is_activate() ) {
			wp_dequeue_style( 'woosc-frontend' );

			wp_register_style( 'minimog-wc-compare', MINIMOG_THEME_URI . "/assets/css/wc/compare{$rtl}{$min}.css", null, MINIMOG_THEME_VERSION );
			wp_enqueue_style( 'minimog-wc-compare' );
		}

		if ( Wishlist::instance()->is_activate() ) {
			wp_dequeue_style( 'woosw-feather' ); // Old version.
			wp_dequeue_style( 'woosw-icons' ); // New version.
			wp_dequeue_style( 'woosw-frontend' );

			wp_register_style( 'minimog-wc-wishlist', MINIMOG_THEME_URI . "/assets/css/wc/wishlist{$rtl}{$min}.css", null, MINIMOG_THEME_VERSION );
			wp_enqueue_style( 'minimog-wc-wishlist' );

			wp_register_script( 'minimog-wc-wishlist', MINIMOG_THEME_URI . "/assets/js/woo/wishlist{$min}.js", [
				'jquery',
				'woosw-frontend',
			], MINIMOG_THEME_VERSION, true );
			wp_enqueue_script( 'minimog-wc-wishlist' );
		}

		if ( class_exists( 'WPCleverWoosb' ) ) {
			wp_dequeue_style( 'woosb-frontend' );

			wp_register_style( 'minimog-wc-product-bundle', MINIMOG_THEME_URI . "/assets/css/wc/product-bundle{$rtl}{$min}.css", null, MINIMOG_THEME_VERSION );
			wp_enqueue_style( 'minimog-wc-product-bundle' );
		}

		if ( Product_Bought_Together::instance()->is_activate() ) {
			wp_dequeue_style( 'woobt-frontend' );

			wp_register_style( 'minimog-wc-bought-together', MINIMOG_THEME_URI . "/assets/css/wc/bought-together{$rtl}{$min}.css", null, MINIMOG_THEME_VERSION );
			wp_enqueue_style( 'minimog-wc-bought-together' );
		}

		if ( Notification::instance()->is_activate() ) {
			wp_dequeue_style( 'wpcsn' );

			wp_register_style( 'minimog-wc-notification', MINIMOG_THEME_URI . "/assets/css/wc/notification{$rtl}{$min}.css", null, MINIMOG_THEME_VERSION );
			wp_enqueue_style( 'minimog-wc-notification' );
		}

		if ( Auction::instance()->is_activate() ) {
			wp_register_style( 'minimog-wc-simple-auction', MINIMOG_THEME_URI . "/assets/css/wc/simple-auction{$rtl}{$min}.css", null, MINIMOG_THEME_VERSION );
			wp_enqueue_style( 'minimog-wc-simple-auction' );
		}

		if ( Sales_Countdown_Timer::instance()->is_activate() ) {
			wp_register_style( 'minimog-wc-sale-countdown-timer', MINIMOG_THEME_URI . "/assets/css/wc/sale-countdown-timer{$rtl}{$min}.css", null, MINIMOG_THEME_VERSION );
			wp_enqueue_style( 'minimog-wc-sale-countdown-timer' );
		}

		if ( defined( 'WOOMULTI_CURRENCY_VERSION' ) ) {
			wp_register_style( 'minimog-wc-multi-currency', MINIMOG_THEME_URI . "/assets/css/wc/multi-currency{$rtl}{$min}.css", null, MINIMOG_THEME_VERSION );
			wp_enqueue_style( 'minimog-wc-multi-currency' );
		}

		// Swatches.
		wp_dequeue_style( 'isw-frontend' );

		wp_register_style( 'minimog-wc-frontend', MINIMOG_THEME_URI . "/assets/css/wc/frontend{$rtl}{$min}.css", null, MINIMOG_THEME_VERSION );
		wp_register_style( 'minimog-wc-my-account', MINIMOG_THEME_URI . "/assets/css/wc/my-account{$rtl}{$min}.css", null, MINIMOG_THEME_VERSION );
		wp_register_script( 'minimog-wc-general', MINIMOG_THEME_URI . "/assets/js/woo/general{$min}.js", [
			'jquery',
			'minimog-script', // Run after main script to use Helpers.
			'js-cookie',
		], MINIMOG_THEME_VERSION, true );

		/**
		 * Used edition version for better performance.
		 */
		wp_deregister_script( 'wc-add-to-cart-variation' );
		wp_register_script( 'wc-add-to-cart-variation', MINIMOG_THEME_URI . "/assets/js/woo/add-to-cart-variation{$min}.js", [
			'jquery',
			'wp-util',
			'jquery-blockui',
		], MINIMOG_THEME_VERSION, true );

		wp_deregister_script( 'wc-cart-fragments' );
		wp_register_script( 'wc-cart-fragments', MINIMOG_THEME_URI . "/assets/js/woo/cart-fragments{$min}.js", [
			'jquery',
			'js-cookie',
		], MINIMOG_THEME_VERSION, true );

		wp_register_script( 'minimog-wc-my-account', MINIMOG_THEME_URI . "/assets/js/woo/my-account{$min}.js", [ 'jquery' ], MINIMOG_THEME_VERSION, true );
		wp_register_script( 'minimog-wc-cart', MINIMOG_THEME_URI . "/assets/js/woo/cart{$min}.js", [
			'jquery',
			'minimog-modal',
			'wc-country-select',
			'wc-address-i18n',
		], MINIMOG_THEME_VERSION, true );
		wp_register_script( 'minimog-wc-coupon', MINIMOG_THEME_URI . "/assets/js/woo/coupon{$min}.js", [
			'jquery',
			'minimog-modal',
			'minimog-wc-general',
		], MINIMOG_THEME_VERSION, true );
		wp_register_script( 'minimog-wc-product', MINIMOG_THEME_URI . "/assets/js/woo/single{$min}.js", [
			'jquery',
			'wc-add-to-cart-variation',
		], MINIMOG_THEME_VERSION, true );
		wp_register_script( 'minimog-wc-questions', MINIMOG_THEME_URI . "/assets/js/woo/product-questions{$min}.js", [
			'jquery',
		], MINIMOG_THEME_VERSION, true );

		$fly_cart_depends = [
			'jquery',
			'minimog-quantity-button',
		];

		if ( '1' === \Minimog::setting( 'shopping_cart_countdown_enable' ) ) {
			$fly_cart_depends[] = 'minimog-countdown-timer';
		}

		$coupon_enable = wc_coupons_enabled();

		if ( $coupon_enable ) {
			$fly_cart_depends[] = 'minimog-wc-coupon';
		}

		wp_register_script( 'minimog-wc-fly-cart', MINIMOG_THEME_URI . "/assets/js/woo/fly-cart{$min}.js", $fly_cart_depends, MINIMOG_THEME_VERSION, true );

		$woo_variables = array(
			'wc_ajax_url'           => \WC_AJAX::get_endpoint( '%%endpoint%%' ),
			'apply_coupon_nonce'    => wp_create_nonce( 'apply-coupon' ),
			'remove_coupon_nonce'   => wp_create_nonce( 'remove-coupon' ),
			'is_checkout'           => is_checkout(),
			'add_to_cart_behaviour' => \Minimog::setting( 'add_to_cart_behaviour' ),
		);
		wp_localize_script( 'minimog-wc-general', '$minimogWoo', $woo_variables );

		wp_enqueue_style( 'minimog-wc-frontend' );

		if ( '1' === \Minimog::setting( 'shop_quick_view_enable' ) ) {
			wp_enqueue_script( 'minimog-modal' );

			/**
			 * Enable ajax add to cart variation in Quick View popup on all pages.
			 */
			wp_enqueue_script( 'wc-add-to-cart-variation' );

			// Quick view need change quantity.
			wp_enqueue_script( 'minimog-quantity-button' );

			wp_enqueue_script( 'minimog-nice-select' );
		}

		wp_enqueue_script( 'minimog-wc-general' );

		/**
		 * Calculate Shipping for Fly Cart
		 * Don't used WC()->cart->needs_shipping()
		 * because scripts won't load when cart empty
		 **/
		if ( 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) && wc_shipping_enabled() ) {
			// Optional for better select.
			wp_enqueue_script( 'selectWoo' );
			wp_enqueue_style( 'select2' );

			// Required.
			wp_enqueue_script( 'wc-country-select' );
		}

		if ( ! is_checkout() ) {
			wp_enqueue_script( 'minimog-wc-fly-cart' );
		}

		if ( \Minimog_Woo::instance()->is_product_archive() ) {
			wp_enqueue_script( 'minimog-nice-select' );
			wp_enqueue_script( 'minimog-grid-layout' );
			wp_enqueue_script( 'minimog-common-archive' );
		}

		if ( is_account_page() ) {
			wp_enqueue_style( 'minimog-wc-my-account' );
			wp_enqueue_script( 'minimog-wc-my-account' );
		}

		if ( is_cart() ) {
			wp_dequeue_script( 'wc-cart' );

			wp_enqueue_script( 'minimog-wc-coupon' );
			wp_enqueue_script( 'minimog-wc-cart' );
			wp_enqueue_script( 'minimog-quantity-button' );
		}

		if ( is_checkout() ) {
			wp_enqueue_script( 'minimog-wc-coupon' );
			wp_enqueue_script( 'minimog-wc-cart' );
		}

		if ( is_product() ) {
			if ( 'grid' === \Minimog_Woo::instance()->get_single_product_images_style() ) {
				wp_enqueue_script( 'minimog-grid-layout' );
			}

			if ( 'toggles' === \Minimog_Woo::instance()->get_product_setting( 'single_product_tabs_style' ) ) {
				wp_enqueue_script( 'minimog-accordion' );
			}

			// We don't need this script because we use modal form.
			wp_dequeue_script( 'comment-reply' );

			wp_enqueue_style( 'lightgallery' );
			wp_enqueue_script( 'lightgallery' );
			wp_enqueue_script( 'minimog-quantity-button' );
			wp_enqueue_script( 'minimog-tab-panel' );
			wp_enqueue_script( 'minimog-modal' );
			wp_enqueue_script( 'spritespin' ); // Product 360.
			wp_enqueue_script( 'readmore' ); // For collapsed content & comments.

			/**
			 * Used for quantity select.
			 */
			wp_enqueue_script( 'minimog-nice-select' );

			wp_enqueue_script( 'minimog-wc-product' );
			wp_enqueue_script( 'minimog-wc-questions' );

			if ( '1' === \Minimog_Woo::instance()->get_product_setting( 'single_product_sticky_enable' ) ) {
				wp_enqueue_script( 'hc-sticky' );
			}

			$single_product_variables = array(
				'featureStyle'           => \Minimog_Woo::instance()->get_single_product_images_style(),
				'singleProductStickyBar' => \Minimog::setting( 'single_product_sticky_bar_enable' ),
				'i18n'                   => [
					'filesSelected' => sprintf( esc_html__( '%s files selected', 'minimog' ), '{count}' ),
					'readMore'      => esc_html__( 'Read more', 'minimog' ),
					'readLess'      => esc_html__( 'Read less', 'minimog' ),
				],
			);
			wp_localize_script( 'minimog-wc-product', '$minimogProductSingle', $single_product_variables );

			/**
			 * Replace src instead dequeue to avoid missing scripts depends on it.
			 */
			$new_wc_single_product = MINIMOG_THEME_URI . "/assets/js/woo/single-product{$min}.js";
			\Minimog_Enqueue::instance()->update_handle_src( 'wc-single-product', $new_wc_single_product );
		}
	}
}

Enqueue::instance()->initialize();
