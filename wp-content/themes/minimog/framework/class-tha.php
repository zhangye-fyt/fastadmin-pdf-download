<?php
defined( 'ABSPATH' ) || exit;

/**
 * Theme Hook Alliance hook stub list.
 */
if ( ! class_exists( 'Minimog_THA' ) ) {
	class Minimog_THA {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function head_top() {
			do_action( 'minimog/head/top' );
		}

		public function head_bottom() {
			do_action( 'minimog/head/bottom' );
		}

		public function header_wrap_top() {
			do_action( 'minimog/header/wrap_top' );
		}

		public function header_wrap_bottom() {
			do_action( 'minimog/header/wrap_bottom' );
		}

		public function header_right_top() {
			do_action( 'minimog/header/right_top' );
		}

		public function header_right_bottom() {
			do_action( 'minimog/header/right_bottom' );
		}

		public function footer_before() {
			do_action( 'minimog/footer/before' );
		}

		public function footer_after() {
			do_action( 'minimog/footer/after' );
		}

		public function title_bar_heading_before() {
			do_action( 'minimog/title_bar/heading_before' );
		}

		public function title_bar_heading_after() {
			do_action( 'minimog/title_bar/heading_after' );
		}

		public function title_bar_meta() {
			do_action( 'minimog/title_bar/meta' );
		}
	}
}
