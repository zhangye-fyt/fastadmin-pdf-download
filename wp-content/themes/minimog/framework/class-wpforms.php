<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_WPForms' ) ) {
	class Minimog_WPForms {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			if ( ! $this->is_activate() ) {
				return;
			}

			add_action( 'wpforms_wp_footer', [ $this, 'fix_duplicate_assets' ], 9999 );
			add_action( 'admin_init', [ $this, 'update_option' ] );
			add_filter( 'wpforms_frontend_container_class', [ $this, 'update_frontend_container_class' ] );

			add_filter( 'wpforms_frontend_strings', [ $this, 'fix_override_validate_string' ] );
		}

		public function is_activate() {
			return defined( 'WPFORMS_VERSION' );
		}

		public function fix_duplicate_assets() {
			/**
			 * The plugin had prefixed validate asset
			 * Then dequeue to enqueue standard asset.
			 */
			if ( wp_script_is( 'wpforms-validation' ) ) {
				wp_deregister_script( 'wpforms-validation' );
				wp_dequeue_script( 'wpforms-validation' );

				wp_enqueue_script( 'validate' );
			}
		}

		/**
		 * Fix WP Form override some form validate messages.
		 *
		 * @see \WPForms_Frontend::get_strings()
		 *
		 * @param array $strings
		 *
		 * @return array $string
		 */
		public function fix_override_validate_string( $strings ) {
			$validate_messages = Minimog_Login_Register::instance()->get_form_validate_messages()['simple'];

			if ( isset( $strings['val_required'] ) ) {
				$strings['val_required'] = $validate_messages['required'];
			}

			if ( isset( $strings['val_email'] ) ) {
				$strings['val_email'] = $validate_messages['email'];
			}

			if ( isset( $strings['val_number'] ) ) {
				$strings['val_number'] = $validate_messages['number'];
			}

			return $strings;
		}

		public function update_option() {
			$settings = get_option( 'wpforms_settings' );

			if ( false === $settings ) {
				$settings['disable-css'] = 2;
				update_option( 'wpforms_settings', $settings );
			}
		}

		public function update_frontend_container_class( $classes ) {
			$settings = get_option( 'wpforms_settings' );

			if ( $settings['disable-css'] == 2 ) {
				$classes[] = 'minimog-wpforms';
			}

			return $classes;
		}

		public static function get_forms() {
			static $forms_list = [];

			$forms_list[0] = esc_html__( 'Select a form', 'minimog' );

			if ( ! function_exists( 'wpforms' ) ) {
				return $forms_list;
			}

			$forms = wpforms()->form->get();

			if ( ! empty( $forms ) ) {
				foreach ( $forms as $form ) {
					$forms_list[ $form->ID ] = mb_strlen( $form->post_title ) > 100 ? mb_substr( $form->post_title, 0, 97 ) . '...' : $form->post_title;
				}
			}

			return $forms_list;
		}
	}

	Minimog_WPForms::instance()->initialize();
}
