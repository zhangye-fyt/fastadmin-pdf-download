<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Customer {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'minimog/user_login/url', [ $this, 'change_login_url' ] );
		add_filter( 'minimog/user_register/url', [ $this, 'change_register_url' ] );
		add_filter( 'minimog/user_profile/text', [ $this, 'change_profile_text' ] );
		add_filter( 'minimog/user_profile/url', [ $this, 'change_profile_url' ] );
	}

	public function change_login_url( $url ) {
		return wc_get_page_permalink( 'myaccount' );
	}

	public function change_register_url( $url ) {
		/**
		 * Go to my account register page instead of default register page.
		 */
		if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) {
			$url = wc_get_page_permalink( 'myaccount' );
		}

		return $url;
	}

	public function change_profile_text( $text ) {
		return __( 'My account', 'minimog' );
	}

	public function change_profile_url( $url ) {
		return wc_get_page_permalink( 'myaccount' );
	}
}

Customer::instance()->initialize();
