<?php
defined( 'ABSPATH' ) || exit;

/**
 * Plugin installation and activation for WordPress themes
 */
if ( ! class_exists( 'Minimog_Register_Plugins' ) ) {
	class Minimog_Register_Plugins {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_filter( 'insight_core_tgm_plugins', [ $this, 'register_required_plugins' ] );

			add_filter( 'insight_core_compatible_plugins', [ $this, 'register_compatible_plugins' ] );
		}

		public function register_required_plugins( $plugins ) {
			/*
			 * Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */
			$new_plugins = array(
				array(
					'name'        => 'Insight Core',
					'description' => 'Core functions for WordPress theme',
					'slug'        => 'insight-core',
					'logo'        => 'insight',
					'source'      => 'https://www.dropbox.com/s/u4o8qjg3pzk26xz/insight-core-2.6.4.zip?dl=1',
					'version'     => '2.6.4',
					'required'    => true,
				),
				array(
					'name'        => 'Redux Framework',
					'description' => 'Build better sites in WordPress fast',
					'slug'        => 'redux-framework',
					'logo'        => 'redux-framework',
					'required'    => true,
				),
				array(
					'name'        => 'Elementor',
					'description' => 'The Elementor Website Builder has it all: drag and drop page builder, pixel perfect design, mobile responsive editing, and more.',
					'slug'        => 'elementor',
					'logo'        => 'elementor',
					'required'    => true,
				),
				array(
					'name'        => 'Thememove Addons For Elementor',
					'description' => 'Additional functions for Elementor',
					'slug'        => 'tm-addons-for-elementor',
					'logo'        => 'insight',
					'source'      => 'https://www.dropbox.com/s/mabcomq7s1lgkje/tm-addons-for-elementor-1.3.0.zip?dl=1',
					'version'     => '1.3.0',
					'required'    => true,
				),
				array(
					'name'        => 'WPForms',
					'description' => 'Beginner friendly WordPress contact form plugin. Use our Drag & Drop form builder to create your WordPress forms',
					'slug'        => 'wpforms-lite',
					'logo'        => 'wpforms-lite',
				),
				array(
					'name'        => 'WooCommerce',
					'description' => 'An eCommerce toolkit that helps you sell anything. Beautifully.',
					'slug'        => 'woocommerce',
					'logo'        => 'woocommerce',
				),
				array(
					'name'        => 'Insight Swatches',
					'description' => 'Allows you set a style for each attribute variation as color, image, or label on product page.',
					'slug'        => 'insight-swatches',
					'logo'        => 'insight',
					'source'      => 'https://www.dropbox.com/s/sk7bt4mneusbhlb/insight-swatches-1.4.0.zip?dl=1',
					'version'     => '1.4.0',
				),
				array(
					'name'        => 'Insight Product Brands',
					'description' => 'Add brands for products',
					'slug'        => 'insight-product-brands',
					'logo'        => 'insight',
					'source'      => 'https://www.dropbox.com/s/i693kiu0gg21wb5/insight-product-brands-1.1.0.zip?dl=1',
					'version'     => '1.1.0',
				),
				array(
					'name'        => 'Conditional Discounts for WooCommerce',
					'description' => 'This plugin is a simple yet advanced WooCommerce dynamic discount plugin ideal for all types of deals.',
					'slug'        => 'woo-advanced-discounts',
					'logo'        => 'woo-advanced-discounts',
					'version'     => '2.28.2',
				),
				array(
					'name'        => 'Sales Countdown Timer (Premium)',
					'description' => 'Create a sense of urgency with a countdown to the beginning or end of sales, store launch or other events for higher conversions.',
					'slug'        => 'sctv-sales-countdown-timer',
					'logo'        => 'sctv-sales-countdown-timer',
					'source'      => 'https://www.dropbox.com/s/tfwsgwfkcjczu49/sctv-sales-countdown-timer-1.0.7.2.zip?dl=1',
					'version'     => '1.0.7.2',
				),
				array(
					'name'        => 'WPC Smart Compare for WooCommerce (Premium)',
					'description' => 'Allows your visitors to compare some products of your shop.',
					'slug'        => 'woo-smart-compare-premium',
					'logo'        => 'woo-smart-compare',
					'source'      => 'https://www.dropbox.com/s/stp654oipi6noyu/woo-smart-compare-premium-537.zip?dl=1',
					'version'     => '5.3.7',
				),
				array(
					'name'        => 'WPC Smart Wishlist for WooCommerce (Premium)',
					'description' => 'Allows your visitors save products for buy later.',
					'slug'        => 'woo-smart-wishlist-premium',
					'logo'        => 'woo-smart-wishlist',
					'source'      => 'https://www.dropbox.com/s/xe785edjml8fozp/woo-smart-wishlist-premium-464.zip?dl=1',
					'version'     => '4.6.4',
				),
				array(
					'name'        => 'WPC Frequently Bought Together for WooCommerce (Premium)',
					'description' => 'Increase your sales with personalized product recommendations',
					'slug'        => 'woo-bought-together-premium',
					'logo'        => 'woo-bought-together-premium',
					'source'      => 'https://www.dropbox.com/s/8v8esabbitd3wxk/woo-bought-together-premium-511.zip?dl=1',
					'version'     => '5.1.1',
				),
				array(
					'name'        => 'WPC Product Bundles for WooCommerce (Premium)',
					'description' => 'This plugin helps you bundle a few products, offer them at a discount and watch the sales go up.',
					'slug'        => 'woo-product-bundle-premium',
					'logo'        => 'woo-product-bundle-premium',
					'source'      => 'https://www.dropbox.com/s/l3f6yvqzgvo3whi/woo-product-bundle-premium-705.zip?dl=1',
					'version'     => '7.0.5',
				),
				array(
					'name'        => 'WPC Product Tabs for WooCommerce (Premium)',
					'description' => 'Allows adding custom tabs to your products and provide your buyers with extra details for boosting customers’ confidence in the items.',
					'slug'        => 'wpc-product-tabs-premium',
					'logo'        => 'wpc-product-tabs-premium',
					'source'      => 'https://www.dropbox.com/s/vsgtnuyy7487rjr/wpc-product-tabs-premium-205.zip?dl=1',
					'version'     => '2.0.5',
				),
				array(
					'name'        => 'Shoppable Images',
					'description' => 'Easily add \'shoppable images\' (images with hotspots) to your website or store',
					'slug'        => 'mabel-shoppable-images-lite',
					'logo'        => 'mabel-shoppable-images-lite',
					'source'      => Minimog_Google_Manager::get_google_driver_url( '1kYgyy0zZ-Q4Dn8PLHbrfXlIC86i0VRpD' ),
					'version'     => '1.1.8',
				),
			);

			$plugins = array_merge( $plugins, $new_plugins );

			return $plugins;
		}

		public function register_compatible_plugins( $plugins ) {
			/**
			 * Each Item should have 'compatible'
			 * 'compatible': set be "true" to work correctly
			 */
			$new_plugins = [
				array(
					'name'        => 'Multi Currency for WooCommerce (Premium)',
					'description' => 'Allows to display prices and accepts payments in multiple currencies.',
					'slug'        => 'woocommerce-multi-currency',
					'logo'        => 'woocommerce-multi-currency',
					'source'      => 'https://www.dropbox.com/s/qltg4yplg4qzj3t/woocommerce-multi-currency-2.2.2.1.zip?dl=1',
					'version'     => '2.2.2.1',
					'compatible'  => true,
				),
				array(
					'name'        => 'WPC Smart Notification for WooCommerce (Premium)',
					'description' => 'Increase trust, credibility, and sales with smart notifications.',
					'slug'        => 'wpc-smart-notification-premium',
					'logo'        => 'wpc-smart-notification',
					'source'      => 'https://www.dropbox.com/s/owerbkzaooj5s9h/wpc-smart-notification-premium-222.zip?dl=1',
					'version'     => '2.2.2',
					'compatible'  => true,
				),
				array(
					'name'        => 'Revolution Slider',
					'description' => 'This plugin helps beginner-and mid-level designers WOW their clients with pro-level visuals. You’ll be able to create anything you can imagine, not just amazing, responsive sliders.',
					'slug'        => 'revslider',
					'logo'        => 'revslider',
					'source'      => 'https://www.dropbox.com/s/zfakncfqtjoh19a/revslider-6.6.12.zip?dl=1',
					'version'     => '6.6.12',
					'compatible'  => true,
				),
				array(
					'name'        => 'WordPress Social Login',
					'description' => 'Allows your visitors to login, comment and share with Facebook, Google, Apple, Twitter, LinkedIn etc using customizable buttons.',
					'slug'        => 'miniorange-login-openid',
					'logo'        => 'miniorange-login-openid',
					'compatible'  => true,
				),
				array(
					'name'        => 'User Profile Picture',
					'description' => 'Allows your visitors upload their avatar with the native WP uploader.',
					'slug'        => 'metronet-profile-picture',
					'logo'        => 'metronet-profile-picture',
					'compatible'  => true,
				),
				array(
					'name'        => 'DCO Comment Attachment',
					'description' => 'Allows your visitors to attach files with their comments.',
					'slug'        => 'dco-comment-attachment',
					'logo'        => 'dco-comment-attachment',
					'compatible'  => true,
				),
				array(
					'name'        => 'hCaptcha for WordPress',
					'description' => 'Add captcha to protects user privacy, rewards websites, and helps companies get their data labeled. Help build a better web.',
					'slug'        => 'hcaptcha-for-forms-and-more',
					'logo'        => 'hcaptcha-for-forms-and-more',
					'compatible'  => true,
				),
			];

			$plugins = array_merge( $plugins, $new_plugins );

			return $plugins;
		}
	}

	Minimog_Register_Plugins::instance()->initialize();
}
