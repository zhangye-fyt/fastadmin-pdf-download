<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Cookie_Notice' ) ) {

	class Minimog_Cookie_Notice {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_action( 'wp_footer', [ $this, 'output_cookie_notice' ] );

			// Notice Cookie Confirm.
			add_action( 'wp_ajax_minimog_cookie_accepted', [ $this, 'cookie_accepted' ] );
			add_action( 'wp_ajax_nopriv_minimog_cookie_accepted', [ $this, 'cookie_accepted' ] );
		}

		public function output_cookie_notice() {
			$accepted = ! empty( $_COOKIE['minimog_cookie_accepted'] ) && 'yes' === $_COOKIE['minimog_cookie_accepted'];
			$enable   = Minimog::setting( 'notice_cookie_enable' );

			if ( '1' === $enable && ! $accepted ) {
				minimog_load_template( 'cookie-notice' );
			}
		}

		public function cookie_accepted() {
			$result = setcookie( 'minimog_cookie_accepted', 'yes', time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
			$result ? wp_send_json_success() : wp_send_json_error();
		}
	}

	Minimog_Cookie_Notice::instance()->initialize();
}
