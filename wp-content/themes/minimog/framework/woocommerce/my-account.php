<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class My_Account {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'minimog/title_bar/type', [ $this, 'title_bar_type' ] );

		add_filter( 'woocommerce_address_to_edit', [ $this, 'override_fields' ] );
	}

	public function title_bar_type( $type ) {
		if ( is_account_page() ) {
			return \Minimog_Title_Bar::DEFAULT_MINIMAL_TYPE;
		}

		return $type;
	}

	/**
	 * Add placeholder for all fields.
	 *
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function override_fields( $fields ) {
		// Add placeholder for shipping form.
		foreach ( $fields as $field_name => $field_option ) {
			/**
			 * Add custom class for some fields
			 */
			switch ( $field_name ) {
				case 'billing_first_name':
				case 'billing_last_name':
				case 'billing_phone':
				case 'billing_email':
				case 'shipping_first_name':
				case 'shipping_last_name':
				case 'shipping_phone':
				case 'shipping_email':
					$fields[ $field_name ]['class'][] = 'col-sm-6';
					break;

				case 'billing_address_1':
				case 'shipping_address_1':
					$fields[ $field_name ]['class'][] = 'col-sm-8';
					break;

				case 'billing_address_2':
				case 'shipping_address_2':
					$fields[ $field_name ]['class'][] = 'col-sm-4 address-field--address-2';
					$fields[ $field_name ]['label']   = esc_html__( 'Apt/Suite', 'minimog' );
					break;

				case 'billing_city':
				case 'billing_state':
				case 'billing_postcode':
				case 'shipping_city':
				case 'shipping_state':
				case 'shipping_postcode':
					$fields[ $field_name ]['class'][] = 'col-sm-4';
					break;

				default :
					$fields[ $field_name ]['class'][] = 'col-sm-12';
					break;
			}
		}

		return $fields;
	}
}

My_Account::instance()->initialize();
