<?php

namespace Minimog\Dokan;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Admin_Settings' ) ) {
	class Admin_Settings {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'dokan_settings_fields', [ $this, 'add_custom_settings' ], 10, 2 );
		}

		public function add_custom_settings( $settings, $setting_instance ) {
			if ( isset( $settings['dokan_appearance'] ) ) {
				$new_fields = [
					'hide_vendor_rating' => [
						'name'    => 'hide_vendor_rating',
						'label'   => __( 'Hide Vendor Rating', 'minimog' ),
						'type'    => 'checkbox',
						'desc'    => __( 'Hide rating for Vendor store', 'minimog' ),
						'default' => 'off',
					],
				];

				$settings['dokan_appearance'] += $new_fields;
			}

			return $settings;
		}
	}

	Admin_Settings::instance()->initialize();
}
