<?php
defined( 'ABSPATH' ) || exit;

class Minimog_Hcaptcha {

	const RECOMMEND_PLUGIN_VERSION = '2.4.0';

	protected static $instance = null;
	protected        $settings = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		if ( ! $this->is_activated() ) {
			return;
		}

		if ( version_compare( HCAPTCHA_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_recommend_plugin_version' ] );
		}

		add_action( 'minimog/modal_user_login/after_form_fields', [ $this, 'add_captcha_for_login' ] );
		add_action( 'minimog/modal_user_register/after_form_fields', [ $this, 'add_captcha_for_register' ] );
		add_action( 'minimog/modal_user_lost_password/after_form_fields', [
			$this,
			'add_captcha_for_lost_password',
		] );
		add_filter( 'minimog/user_login/errors', [ $this, 'verify_captcha_on_login' ] );
		add_filter( 'minimog/user_register/errors', [ $this, 'verify_captcha_on_register' ], 10, 3 );
		add_filter( 'minimog/user_reset_password/errors', [ $this, 'verify_captcha_on_reset_password' ] );
	}

	public function is_activated() {
		return class_exists( 'HCaptcha\Main' );
	}

	public function admin_notice_recommend_plugin_version() {
		minimog_notice_required_plugin_version( 'hCaptcha for WordPress', self::RECOMMEND_PLUGIN_VERSION );
	}

	public function get_setting( $option_name ) {
		if ( null === $this->settings ) {
			$this->settings = get_option( 'hcaptcha_settings' );
		}

		return isset( $this->settings[ $option_name ] ) ? $this->settings[ $option_name ] : '';
	}

	public function get_form_status( $context = 'wp' ) {
		$status = $this->get_setting( "{$context}_status" );

		return $status;
	}

	/**
	 * @return bool
	 */
	public function is_show_on_login_form() {
		$wp_form_status = $this->get_form_status();

		return ( is_array( $wp_form_status ) && in_array( 'login', $wp_form_status, true ) )
		       || 'on' === get_option( 'hcaptcha_lf_status' ); // Old version compatible.
	}

	/**
	 * @return bool
	 */
	public function is_show_on_register_form() {
		$wp_form_status = $this->get_form_status();

		return ( is_array( $wp_form_status ) && in_array( 'register', $wp_form_status, true ) )
		       || 'on' === get_option( 'hcaptcha_rf_status' ); // Old version compatible.
	}

	/**
	 * @return bool
	 */
	public function is_show_on_lost_password_form() {
		$wp_form_status = $this->get_form_status();

		return ( is_array( $wp_form_status ) && in_array( 'lost_pass', $wp_form_status, true ) )
		       || 'on' === get_option( 'hcaptcha_lpf_status' ); // Old version compatible.
	}

	public function add_captcha_for_login() {
		if ( $this->is_show_on_login_form() ) {
			hcap_form_display( 'hcaptcha_login', 'hcaptcha_login_nonce' );
		}
	}

	public function add_captcha_for_register() {
		if ( $this->is_show_on_register_form() ) {
			hcap_form_display( 'hcaptcha_registration', 'hcaptcha_registration_nonce' );
		}
	}

	public function add_captcha_for_lost_password() {
		if ( $this->is_show_on_lost_password_form() ) {
			hcap_form_display( 'hcaptcha_lost_password', 'hcaptcha_lost_password_nonce' );
		}
	}

	/**
	 * @param \WP_Error $errors
	 *
	 * @return mixed
	 */
	public function verify_captcha_on_login( $errors ) {
		if ( $this->is_show_on_login_form() ) {
			$error_message = $this->get_verify_captcha_message( 'hcaptcha_login_nonce', 'hcaptcha_login' );

			if ( null !== $error_message ) {
				$errors->add( 'invalid_captcha', $error_message );
			}
		}

		return $errors;
	}

	/**
	 * @param \WP_Error $errors
	 *
	 * @return mixed
	 */
	public function verify_captcha_on_register( $errors, $user_login, $email ) {
		if ( $this->is_show_on_register_form() ) {
			$error_message = $this->get_verify_captcha_message( 'hcaptcha_registration_nonce', 'hcaptcha_registration' );

			if ( null !== $error_message ) {
				$errors->add( 'invalid_captcha', $error_message );
			}
		}

		return $errors;
	}

	/**
	 * @param \WP_Error $errors
	 *
	 * @return mixed
	 */
	public function verify_captcha_on_reset_password( $errors ) {
		if ( $this->is_show_on_lost_password_form() ) {
			$error_message = $this->get_verify_captcha_message( 'hcaptcha_lost_password_nonce', 'hcaptcha_lost_password' );

			if ( null !== $error_message ) {
				$errors->add( 'invalid_captcha', $error_message );
			}
		}

		return $errors;
	}

	/**
	 * Get verify message.
	 *
	 * @param string $nonce_field_name  Nonce field name.
	 * @param string $nonce_action_name Nonce action name.
	 *
	 * @return null|string
	 */
	public function get_verify_captcha_message( $nonce_field_name, $nonce_action_name ) {
		return hcaptcha_get_verify_output(
			esc_html__( 'Please complete the captcha', 'minimog' ),
			esc_html__( 'The captcha is invalid', 'minimog' ),
			$nonce_field_name,
			$nonce_action_name
		);
	}
}

Minimog_Hcaptcha::instance()->initialize();
