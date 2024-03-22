<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Login_Register' ) ) {

	class Minimog_Login_Register {

		/**
		 * Minimum Social Login, Social Sharing by miniOrange plugin version required.
		 *
		 * @var string
		 */
		const MINIMUM_MO_SOCIAL_LOGIN_VERSION = '7.4.7';

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_ajax_nopriv_minimog_user_login', [ $this, 'user_login' ] );

			add_action( 'wp_ajax_nopriv_minimog_user_register', [ $this, 'user_register' ] );

			add_action( 'wp_ajax_nopriv_minimog_user_reset_password', [ $this, 'reset_password' ] );

			add_action( 'wp_enqueue_scripts', [ $this, 'login_scripts' ], 11 );

			add_action( 'wp_footer', [ $this, 'user_form_modals' ] );

			if ( defined( 'MO_OPENID_SOCIAL_LOGIN_VERSION' ) ) {
				add_action( 'minimog/modal_user_login/after_form', [ $this, 'add_social_login_buttons' ], 10 );

				add_action( 'minimog/user_register_form/after', [ $this, 'add_social_login_buttons' ], 10 );

				if ( version_compare( MO_OPENID_SOCIAL_LOGIN_VERSION, self::MINIMUM_MO_SOCIAL_LOGIN_VERSION, '<' ) ) {
					add_action( 'admin_notices', [ $this, 'admin_notice_minimum_mo_social_login_version' ] );
				}

				// Woocommerce Login Form.
				minimog_remove_filters_for_anonymous_class( 'woocommerce_login_form_start', 'miniorange_openid_sso_settings', 'mo_openid_add_social_login' );
				minimog_remove_filters_for_anonymous_class( 'woocommerce_login_form', 'miniorange_openid_sso_settings', 'mo_openid_add_social_login' );
				add_action( 'woocommerce_login_form_end', [ $this, 'add_social_login_buttons' ] );
			}
		}

		public function admin_notice_minimum_mo_social_login_version() {
			minimog_notice_required_plugin_version( 'Social Login, Social Sharing by miniOrange', self::MINIMUM_MO_SOCIAL_LOGIN_VERSION );
		}

		public function is_modal_on() {
			$modal_login_on = Minimog::setting( 'login_popup_enable' );

			if ( ! is_user_logged_in() && $modal_login_on ) {
				return true;
			}

			return false;
		}

		public function login_scripts() {
			$min = Minimog_Enqueue::instance()->get_min_suffix();

			/**
			 * Disable unused scripts & styles from frontend of social login plugin.
			 */
			wp_dequeue_style( 'mo_openid_admin_settings_style' );
			wp_dequeue_style( 'mo_openid_admin_settings_phone_style' );
			wp_dequeue_style( 'mo-wp-bootstrap-social' );
			wp_dequeue_style( 'mo-wp-bootstrap-main' );
			wp_dequeue_style( 'mo-openid-sl-wp-font-awesome' );
			wp_dequeue_style( 'bootstrap_style_ass' );

			wp_dequeue_script( 'js-cookie-script' );
			// Don't need cache scroll position feature => don't enqueue again.
			wp_dequeue_script( 'mo-social-login-script' );

			wp_register_script( 'minimog-login', MINIMOG_THEME_URI . "/assets/js/login{$min}.js", [
				'validate',
				'minimog-modal',
				'minimog-script',
			], '1.17.0', true );

			if ( $this->is_modal_on() ) {
				wp_enqueue_script( 'minimog-login' );

				/*
				 * Enqueue custom variable JS
				 */
				$js_variables = array(
					'validatorMessages' => $this->get_form_validate_messages(),
				);
				wp_localize_script( 'minimog-login', '$minimogLogin', $js_variables );
			}
		}

		public function get_form_validate_messages() {
			return [
				'simple' => [
					'required'   => esc_html__( 'This field is required', 'minimog' ),
					'remote'     => esc_html__( 'Please fix this field', 'minimog' ),
					'email'      => esc_html__( 'A valid email address is required', 'minimog' ),
					'url'        => esc_html__( 'Please enter a valid URL', 'minimog' ),
					'date'       => esc_html__( 'Please enter a valid date', 'minimog' ),
					'dateISO'    => esc_html__( 'Please enter a valid date (ISO)', 'minimog' ),
					'number'     => esc_html__( 'Please enter a valid number.', 'minimog' ),
					'digits'     => esc_html__( 'Please enter only digits.', 'minimog' ),
					'creditcard' => esc_html__( 'Please enter a valid credit card number', 'minimog' ),
					'equalTo'    => esc_html__( 'Please enter the same value again', 'minimog' ),
					'accept'     => esc_html__( 'Please enter a value with a valid extension', 'minimog' ),
				],
				'format' => [
					'maxlength'   => esc_html__( 'Please enter no more than {0} characters', 'minimog' ),
					'minlength'   => esc_html__( 'Please enter at least {0} characters', 'minimog' ),
					'rangelength' => esc_html__( 'Please enter a value between {0} and {1} characters long', 'minimog' ),
					'range'       => esc_html__( 'Please enter a value between {0} and {1}', 'minimog' ),
					'max'         => esc_html__( 'Please enter a value less than or equal to {0}', 'minimog' ),
					'min'         => esc_html__( 'Please enter a value greater than or equal to {0}', 'minimog' ),
				],
			];
		}

		public function user_form_modals() {
			if ( $this->is_modal_on() ) {
				minimog_load_template( 'modal/modal-login' );
				minimog_load_template( 'modal/modal-lost-password' );
				minimog_load_template( 'modal/modal-register' );
			}
		}

		public function user_login() {
			if ( ! check_ajax_referer( 'user_login', 'user_login_nonce' ) ) {
				wp_die();
			}

			$errors = new WP_Error();
			$user   = false;

			$user_login = ! empty( $_POST['user_login'] ) ? sanitize_text_field( $_POST['user_login'] ) : '';
			$password   = ! empty( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';
			$remember   = isset( $_POST['rememberme'] ) && 'forever' === $_POST['rememberme'] ? true : false;

			if ( empty( $user_login ) || empty( $password ) ) {
				$errors->add( 'empty_input', esc_html__( 'Please input all required fields', 'minimog' ) );
			} else {
				if ( filter_var( $user_login, FILTER_VALIDATE_EMAIL ) ) {
					$user = get_user_by( 'email', $user_login );
				} else {
					$user = get_user_by( 'login', $user_login );
				}
			}

			/**
			 * Filters the errors encountered when a new user is being logged in.
			 *
			 * The filtered WP_Error object may, for example, contain errors for an invalid
			 * A WP_Error object should always be returned,
			 * but may or may not contain errors.
			 *
			 * If any errors are present in $errors, this will abort the user's login.
			 *
			 * @param WP_Error $errors A WP_Error object containing any errors encountered during log in.
			 *
			 * @var WP_Error   $errors
			 */
			$errors = apply_filters( 'minimog/user_login/errors', $errors );

			if ( is_wp_error( $errors ) && $errors->has_errors() ) {
				wp_send_json_error( [ 'messages' => $this->get_error_messages_html( $errors ) ] );
			}

			if ( $user && wp_check_password( $password, $user->data->user_pass, $user->ID ) ) {
				$credentials = [
					'user_login'    => $user->data->user_login,
					'user_password' => $password,
					'remember'      => $remember,
				];

				// Remove captcha verify by plugin.
				minimog_remove_filters_for_anonymous_class( 'wp_authenticate_user', 'HCaptcha\WP\Login', 'verify' );

				$user = wp_signon( $credentials );

				if ( ! is_wp_error( $user ) ) {
					$redirect_type = Minimog::setting( 'login_redirect' );
					$redirect_url  = '';

					switch ( $redirect_type ) {
						case 'home' :
							$redirect_url = home_url();
							break;

						case 'dashboard' :
							$redirect_url = home_url();
							if ( function_exists( 'wc_get_account_endpoint_url' ) ) {
								$redirect_url = wc_get_account_endpoint_url( 'dashboard' );
							}
							break;

						case 'custom' :
							$redirect_url = Minimog::setting( 'custom_login_redirect' );
							break;
					}

					$redirect_url = apply_filters( 'minimog/login_redirect', $redirect_url );

					wp_send_json_success( [
						'messages'     => esc_html__( 'Login successful, Redirecting...', 'minimog' ),
						'redirect_url' => esc_url( $redirect_url ),
					] );
				} else {
					wp_send_json_error( [ 'messages' => $this->get_error_messages_html( $user ) ] );
				}
			}

			wp_send_json_error( [ 'messages' => esc_html__( 'Username or password is wrong. Please try again!', 'minimog' ) ] );
		}

		public function user_register() {
			if ( ! check_ajax_referer( 'user_register', 'user_register_nonce' ) ) {
				wp_die();
			}

			if ( empty ( $_POST['fullname'] ) || empty ( $_POST['username'] ) || empty ( $_POST['email'] ) || empty ( $_POST['password'] ) ) {
				wp_send_json_error( [ 'messages' => esc_html__( 'Please input all required fields', 'minimog' ) ] );
			}

			$errors = new WP_Error();

			$accept_terms_conditions = isset( $_POST['accept_account'] ) && '1' === $_POST['accept_account'] ? true : false;

			if ( ! $accept_terms_conditions ) {
				$errors->add( 'accept_privacy_policy', esc_html__( 'Please accept the Privacy Policy and Terms of Use', 'minimog' ) );
			}

			if ( ! is_email( $_POST['email'] ) ) {
				$errors->add( 'invalid_email', esc_html__( 'A valid email address is required', 'minimog' ) );
			}

			$email      = sanitize_email( $_POST['email'] );
			$fullname   = sanitize_text_field( $_POST['fullname'] );
			$password   = sanitize_text_field( $_POST['password'] );
			$user_login = sanitize_user( $_POST['username'] );

			/**
			 * Filters the errors encountered when a new user is being registered.
			 *
			 * The filtered WP_Error object may, for example, contain errors for an invalid
			 * or existing username or email address. A WP_Error object should always be returned,
			 * but may or may not contain errors.
			 *
			 * If any errors are present in $errors, this will abort the user's registration.
			 *
			 * @param WP_Error $errors               A WP_Error object containing any errors encountered
			 *                                       during registration.
			 * @param string   $sanitized_user_login User's username after it has been sanitized.
			 * @param string   $user_email           User's email.
			 *
			 * @var WP_Error   $errors
			 */
			$errors = apply_filters( 'minimog/user_register/errors', $errors, $user_login, $email );

			if ( is_wp_error( $errors ) && $errors->has_errors() ) {
				wp_send_json_error( [ 'messages' => $this->get_error_messages_html( $errors ) ] );
			}

			$userdata = [
				'display_name' => $fullname,
				'user_login'   => $user_login,
				'user_email'   => $email,
				'user_pass'    => $password,
			];

			$userdata = apply_filters( 'minimog/user_register/data', $userdata );

			$user_id = wp_insert_user( $userdata );

			if ( ! is_wp_error( $user_id ) ) {
				$creds                  = array();
				$creds['user_login']    = $user_login;
				$creds['user_email']    = $email;
				$creds['user_password'] = $password;
				$creds['remember']      = true;

				// Remove captcha verify by plugin.
				minimog_remove_filters_for_anonymous_class( 'wp_authenticate_user', 'HCaptcha\WP\Login', 'verify' );

				$user = wp_signon( $creds, false );

				do_action( 'minimog/user_register/save', $user_id );

				wp_send_json_success( [ 'messages' => esc_html__( 'Congratulations, register successful, Redirecting...', 'minimog' ) ] );
			} else {
				wp_send_json_error( [ 'messages' => $this->get_error_messages_html( $user_id ) ] );
			}

			wp_send_json_error( [ 'messages' => esc_html__( 'Sorry, there is some thing went wrong!', 'minimog' ) ] );
		}

		public function reset_password() {
			if ( ! check_ajax_referer( 'user_reset_password', 'user_reset_password_nonce' ) ) {
				wp_die();
			}

			$errors = new WP_Error();

			$user_login     = ! empty( $_POST['user_login'] ) ? trim( wp_strip_all_tags( $_POST['user_login'] ) ) : '';
			$user_data      = false;
			$reset_by_email = false;

			if ( empty( $_POST['user_login'] ) ) {
				$errors->add( 'empty_username', esc_html__( 'Enter a username or email address.', 'minimog' ) );
			} else {
				if ( filter_var( $user_login, FILTER_VALIDATE_EMAIL ) ) {
					$user_data      = get_user_by( 'email', $user_login );
					$reset_by_email = true;
				} else {
					$user_data = get_user_by( 'login', $user_login );
				}
			}

			if ( ! $user_data instanceof WP_User ) {
				if ( $reset_by_email ) {
					$errors->add( 'invalid_email', esc_html__( 'There is no user registered with that email address.', 'minimog' ) );
				} else {
					$errors->add( 'invalid_username', esc_html__( 'Invalid username', 'minimog' ) );
				}
			}

			/**
			 * Filters the errors encountered when a user is being reset password.
			 *
			 * The filtered WP_Error object may, for example, contain errors for an invalid
			 * A WP_Error object should always be returned,
			 * but may or may not contain errors.
			 *
			 * If any errors are present in $errors, this will abort the user's login.
			 *
			 * @param WP_Error $errors A WP_Error object containing any errors encountered during log in.
			 *
			 * @var WP_Error   $errors
			 */
			$errors = apply_filters( 'minimog/user_reset_password/errors', $errors );

			if ( is_wp_error( $errors ) && $errors->has_errors() ) {
				wp_send_json_error( [ 'messages' => $this->get_error_messages_html( $errors ) ] );
			}

			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;
			$key        = get_password_reset_key( $user_data );

			if ( is_wp_error( $key ) ) {
				wp_send_json_error( [ 'messages' => $this->get_error_messages_html( $key ) ] );
			}

			$message = esc_html__( 'Someone has requested a password reset for the following account:', 'minimog' ) . "\r\n\r\n";
			$message .= network_home_url( '/' ) . "\r\n\r\n";
			$message .= sprintf( esc_html__( 'Username: %s', 'minimog' ), $user_login ) . "\r\n\r\n";
			$message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.', 'minimog' ) . "\r\n\r\n";
			$message .= esc_html__( 'To reset your password, visit the following address:', 'minimog' ) . "\r\n\r\n";
			$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

			if ( is_multisite() ) {
				$blogname = $GLOBALS['current_site']->site_name;
			} else {
				$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			}

			$title   = sprintf( esc_html__( '[%s] Password Reset', 'minimog' ), $blogname );
			$title   = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );
			$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );
			if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
				wp_send_json_error( [ 'messages' => esc_html__( 'The email could not be sent.', 'minimog' ) . "<br />\n" . esc_html__( 'Possible reason: your host may have disabled the mail function.', 'minimog' ) ] );
			} else {
				wp_send_json_success( [ 'messages' => esc_html__( 'Please check your email to get new password', 'minimog' ) ] );
			}
		}

		public function add_social_login_buttons() {
			echo do_shortcode( '[miniorange_social_login shape="longbuttonwithtext" view="horizontal" appcnt="2" space="0" theme="default" height="48" color="000000" heading="' . esc_html__( 'or Log-in with', 'minimog' ) . '"]' );
		}

		/**
		 * @param WP_Error $errors
		 *
		 * @return string
		 */
		public function get_error_messages_html( $errors ) {
			$output         = '';
			$error_messages = $errors->get_error_messages();

			foreach ( $error_messages as $message ) {
				$output .= '<li>' . $message . '</li>';
			}

			return '<ul>' . $output . '</ul>';
		}
	}

	Minimog_Login_Register::instance()->initialize();
}
