<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Widgets' ) ) {
	class Minimog_Widgets {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_action( 'widgets_init', [ $this, 'register_widgets' ], 11 );

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_widgets_scripts' ] );
		}

		public function register_widgets() {
			minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/posts.php' );
			minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/instagram.php' );

			register_widget( 'Minimog_WP_Widget_Posts' );
			register_widget( 'Minimog_WP_Widget_Instagram' );

			if ( Minimog_Woo::instance()->is_activated() ) {
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/class-wc-widget-base.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-badge.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-banner.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-categories-nav.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-tags-nav.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-sorting.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-price-filter.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-rating-filter.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-highlight-filter.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-stock-filter.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-layered-nav.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/products.php' );
				minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/linked-products.php' );

				register_widget( 'Minimog_WP_Widget_Product_Badge' );
				register_widget( 'Minimog_WP_Widget_Product_Banner' );
				register_widget( 'Minimog_WP_Widget_Product_Categories_Layered_Nav' );
				register_widget( 'Minimog_WP_Widget_Product_Tags_Layered_Nav' );
				register_widget( 'Minimog_WP_Widget_Product_Sorting' );
				register_widget( 'Minimog_WP_Widget_Product_Price_Filter' );
				register_widget( 'Minimog_WP_Widget_Product_Rating_Filter' );
				register_widget( 'Minimog_WP_Widget_Product_Highlight_Filter' );
				register_widget( 'Minimog_WP_Widget_Product_Stock_Filter' );
				register_widget( 'Minimog_WP_Widget_Product_Layered_Nav' );
				register_widget( 'Minimog_WP_Widget_Products' );
				register_widget( 'Minimog_WP_Widget_Linked_Products' );

				if ( class_exists( 'Insight_Product_Brands' ) ) {
					minimog_require_file_once( MINIMOG_WIDGETS_DIR . '/product-brand-nav.php' );
					register_widget( 'Minimog_WP_Widget_Product_Brand_Nav' );
				}
			}
		}

		/**
		 * Enqueue scrips & styles for widget screen.
		 *
		 * @access public
		 */
		public function enqueue_widgets_scripts() {
			$screen = get_current_screen();

			if ( 'widgets' !== $screen->id ) {
				return;
			}

			wp_enqueue_style( 'minimog-admin-widgets', MINIMOG_THEME_URI . '/assets/admin/css/widgets.min.css' );

			wp_enqueue_media();
			wp_enqueue_script( 'minimog-media', MINIMOG_THEME_URI . '/assets/admin/js/attach.js', [ 'jquery' ], null, true );
			wp_enqueue_script( 'minimog-admin-widgets-condition', MINIMOG_THEME_URI . '/assets/admin/js/wp-widgets-condition.js', [ 'jquery' ], null, true );
		}
	}

	Minimog_Widgets::instance()->initialize();
}
