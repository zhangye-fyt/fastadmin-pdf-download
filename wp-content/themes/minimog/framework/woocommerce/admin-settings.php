<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Admin_Settings {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'woocommerce_general_settings', [ $this, 'add_general_settings' ] );

		add_filter( 'woocommerce_shipping_settings', [ $this, 'add_shipping_settings' ] );

		add_filter( 'insight_core_export_woocommerce_options', [ $this, 'add_export_options' ] );
	}

	public function add_general_settings( $settings ) {
		$count = count( $settings );

		$new_settings = array(
			[
				'title'           => __( 'Decimal no zeroes', 'minimog' ),
				// WPCS: XSS ok.
				'desc'            => __( 'Trim all zeros at the last from decimal', 'minimog' ),
				// WPCS: XSS ok.
				'id'              => 'woocommerce_price_decimal_no_zeroes',
				'type'            => 'checkbox',
				'default'         => 'no',
				'show_if_checked' => 'yes',
				'autoload'        => false,

			],
		);

		\Minimog_Helper::array_insert( $settings, $count - 1, $new_settings );

		return $settings;
	}

	public function add_shipping_settings( $settings ) {
		$count = count( $settings );

		$delivery_begin = strtotime( '+5 day' );
		$delivery_end   = strtotime( '+7 day' );

		$delivery_start_format_1 = $delivery_end_format_1 = 'd M, Y';
		$delivery_start_format_2 = $delivery_end_format_2 = 'M d, Y';

		// Simplify date if same year.
		if ( date( 'Y', $delivery_begin ) === date( 'Y', $delivery_end ) ) {
			$delivery_start_format_1 = 'd M';
			$delivery_start_format_2 = 'M d';

			// Simplify date if same month.
			if ( date( 'n', $delivery_begin ) === date( 'n', $delivery_end ) ) {
				$delivery_start_format_1 = 'd';
				$delivery_end_format_2   = 'd, Y';
			}
		}

		$delivery_date_option1 = wp_date( $delivery_start_format_1, $delivery_begin ) . ' - ' . wp_date( $delivery_end_format_1, $delivery_end );
		$delivery_date_option2 = wp_date( $delivery_start_format_2, $delivery_begin ) . ' - ' . wp_date( $delivery_end_format_2, $delivery_end );

		$new_settings = array(
			[
				'title'             => __( 'Delivery Time Range Begin', 'minimog' ),
				// WPCS: XSS ok.
				'desc'              => __( 'Set up estimated average shipping delivery begin time. Leave blank to set range begin today.', 'minimog' ),
				// WPCS: XSS ok.
				'id'                => 'woocommerce_shipping_delivery_time_begin',
				'type'              => 'number',
				'placeholder'       => '5',
				'autoload'          => false,
				'custom_attributes' => [
					'min' => 0,
				],
			],
			[
				'title'             => __( 'Delivery Time Range Complete', 'minimog' ),
				// WPCS: XSS ok.
				'desc'              => __( 'Set up estimated average shipping delivery completed time', 'minimog' ),
				// WPCS: XSS ok.
				'id'                => 'woocommerce_shipping_delivery_time',
				'type'              => 'number',
				'placeholder'       => '7',
				'autoload'          => false,
				'custom_attributes' => [
					'min' => 0,
				],
			],
			[
				'title'   => __( 'Delivery Time Type', 'minimog' ),
				// WPCS: XSS ok.
				'id'      => 'woocommerce_shipping_delivery_time_type',
				'type'    => 'select',
				'options' => [
					'days'  => esc_html__( 'Days', 'minimog' ),
					'hours' => esc_html__( 'Hours', 'minimog' ),
				],
				'default' => 'days',
			],
			[
				'title'           => __( 'Delivery Date Format', 'minimog' ),
				'id'              => 'woocommerce_shipping_delivery_time_format',
				'default'         => 'd M, Y',
				'type'            => 'radio',
				'options'         => array(
					'd M, Y' => $delivery_date_option1,
					'M d, Y' => $delivery_date_option2,
				),
				'autoload'        => true,
				'desc_tip'        => true,
				'show_if_checked' => 'option',
			],
		);

		\Minimog_Helper::array_insert( $settings, $count - 1, $new_settings );

		return $settings;
	}

	public function add_export_options( $options ) {
		$new_options = [
			'woocommerce_price_decimal_no_zeroes',
			'woocommerce_shipping_delivery_time',
		];

		return array_merge( $options, $new_options );
	}
}

Admin_Settings::instance()->initialize();
