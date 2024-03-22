<?php
defined( 'ABSPATH' ) || exit;

/**
 * Hook & filter that run only on admin pages.
 */
if ( ! class_exists( 'Minimog_Admin' ) ) {
	class Minimog_Admin {

		protected static $instance = null;

		/**
		 * Minimum Insight Core version required to run the theme.
		 *
		 * @var string
		 */
		const RECOMMENDED_INSIGHT_CORE_VERSION = '2.6.0';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_action( 'wp_before_admin_bar_render', [ $this, 'simplify_admin_bar_menu' ] );

			// Do nothing if not an admin page.
			if ( ! is_admin() ) {
				return;
			}

			add_action( 'after_switch_theme', [ $this, 'count_switch_time' ], 1 );

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

			add_action( 'enqueue_block_editor_assets', [ $this, 'gutenberg_editor' ] );

			if ( class_exists( 'InsightCore' ) ) {
				if ( ! defined( 'INSIGHT_CORE_VERSION' ) || ( defined( 'INSIGHT_CORE_VERSION' ) && version_compare( INSIGHT_CORE_VERSION, self::RECOMMENDED_INSIGHT_CORE_VERSION, '<' ) ) ) {
					add_action( 'admin_notices', [ $this, 'admin_notice_minimum_insight_core_version' ] );
				}
			}
		}

		public function admin_notice_minimum_insight_core_version() {
			minimog_notice_required_plugin_version( 'Insight Core', self::RECOMMENDED_INSIGHT_CORE_VERSION );
		}

		public function gutenberg_editor() {
			/**
			 * Enqueue fonts for gutenberg editor.
			 */
			wp_enqueue_style( 'font-dmsans', MINIMOG_THEME_URI . '/assets/fonts/dm-sans/font-dmsans.css', null, null );
		}

		public function count_switch_time() {
			$count = get_option( 'minimog_switch_theme_count' );

			if ( $count ) {
				$count++;
			} else {
				$count = 1;
			}

			update_option( 'minimog_switch_theme_count', $count );
		}

		/**
		 * Enqueue scrips & styles.
		 *
		 * @access public
		 */
		function enqueue_scripts() {
			$this->enqueue_fonts_for_rev_slider_page();

			wp_register_style( 'font-minimog', MINIMOG_THEME_URI . '/assets/fonts/minimog/css/minimog.css', null, '' );

			wp_enqueue_style( 'font-minimog' );
			wp_enqueue_style( 'minimog-admin', MINIMOG_THEME_URI . '/assets/admin/css/style.min.css' );
		}

		/**
		 * Enqueue fonts for Rev Slider edit page.
		 */
		function enqueue_fonts_for_rev_slider_page() {
			$screen = get_current_screen();

			if ( 'toplevel_page_revslider' !== $screen->base ) {
				return;
			}

			$typo_fields = array(
				'typography_body',
				'typography_heading',
				'button_typography',
			);

			if ( ! is_array( $typo_fields ) || empty( $typo_fields ) ) {
				return;
			}

			foreach ( $typo_fields as $field ) {
				$value = Minimog::setting( $field );

				if ( is_array( $value ) && ! empty( $value['font-family'] ) ) {
					switch ( $value['font-family'] ) {
						case Minimog::SECONDARY_FONT:
							wp_enqueue_style( 'font-newyork', MINIMOG_THEME_URI . '/assets/fonts/new-york/font-newyork.css', null, null );
							break;
						default:
							do_action( 'minimog_enqueue_custom_font', $value['font-family'] ); // hook to custom do enqueue fonts
							break;
					}
				}
			}
		}

		public function simplify_admin_bar_menu() {
			/**
			 * @var $wp_admin_bar WP_Admin_Bar
			 */
			global $wp_admin_bar;

			$wp_admin_bar->remove_menu( 'wpforms-menu' );
			$wp_admin_bar->remove_menu( 'villatheme' );
		}
	}

	Minimog_Admin::instance()->initialize();
}
