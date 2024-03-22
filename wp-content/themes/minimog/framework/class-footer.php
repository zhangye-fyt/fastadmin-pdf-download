<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Footer' ) ) {

	class Minimog_Footer {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_footer', [ $this, 'scroll_top' ] );
			add_action( 'wp_footer', [ $this, 'popup_search' ] );
			add_action( 'wp_footer', [ $this, 'mobile_menu_template' ] );
			add_action( 'wp_footer', [ $this, 'mobile_tabs' ] );
		}

		/**
		 * Add popup search template to footer
		 */
		function popup_search() {
			$enable = Minimog_Global::instance()->get_popup_search();
			if ( $enable !== true ) {
				return;
			}

			minimog_load_template( 'popup-search/entry' );
		}

		/**
		 * Add mobile menu template to footer
		 */
		function mobile_menu_template() {
			minimog_load_template( 'mobile-menu/entry' );
		}

		/**
		 * Add scroll to top template to footer
		 */
		function scroll_top() {
			if ( ! Minimog::setting( 'scroll_top_enable' ) ) {
				return;
			}
			?>
			<a class="page-scroll-up" id="page-scroll-up">
				<span class="scroll-up-icon arrow-top"><i class="far fa-long-arrow-up"></i></span>
				<span class="scroll-up-icon arrow-bottom"><i class="far fa-long-arrow-up"></i></span>
			</a>
			<?php
		}

		/**
		 * Add mobile menu template to footer
		 */
		function mobile_tabs() {
			if ( ! Minimog::setting( 'mobile_tabs_enable' ) ) {
				return;
			}

			$mobile_tabs = Minimog::setting( 'mobile_tabs' );

			if ( empty( $mobile_tabs ) ) {
				return;
			}

			minimog_load_template( 'mobile-tabs/entry', '', [ 'tab_items' => $mobile_tabs ] );
		}
	}

	Minimog_Footer::instance()->initialize();
}
