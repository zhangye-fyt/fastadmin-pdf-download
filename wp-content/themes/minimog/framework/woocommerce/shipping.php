<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Shipping {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'woocommerce_product_options_shipping_product_data', [ $this, 'add_shipping_estimated' ] );
		add_action( 'woocommerce_process_product_meta', [ $this, 'save_product_data' ] );
	}

	public function add_shipping_estimated() {
		global $post, $thepostid, $product_object;

		$shipping_delivery_time_range_begin = $product_object->get_meta( '_shipping_delivery_time_range_begin' );
		woocommerce_wp_text_input(
			array(
				'id'                => '_shipping_delivery_time_range_begin',
				'value'             => $shipping_delivery_time_range_begin,
				'label'             => __( 'Delivery Time Range Begin', 'minimog' ),
				'desc_tip'          => true,
				'description'       => sprintf( '%s %s',
					__( 'Set up estimated average shipping delivery begin time.', 'minimog' ),
					__( 'Leave blank to use global setting.', 'minimog' ) ),
				'type'              => 'number',
				'custom_attributes' => array(
					'step' => '1',
					'min'  => '0',
				),
			)
		);

		$shipping_delivery_time_range = $product_object->get_meta( '_shipping_delivery_time_range' );
		woocommerce_wp_text_input(
			array(
				'id'                => '_shipping_delivery_time_range',
				'value'             => $shipping_delivery_time_range,
				'label'             => __( 'Delivery Time Range Complete', 'minimog' ),
				'desc_tip'          => true,
				'description'       => sprintf( '%s %s',
					__( 'Set up estimated average shipping delivery time.', 'minimog' ),
					__( 'Leave blank to use global setting.', 'minimog' ) ),
				'type'              => 'number',
				'custom_attributes' => array(
					'step' => '1',
					'min'  => '0',
				),
			)
		);

		$shipping_delivery_time_type = $product_object->get_meta( '_shipping_delivery_time_type' );
		woocommerce_wp_select(
			array(
				'id'       => '_shipping_delivery_time_type',
				'value'    => $shipping_delivery_time_type,
				'label'    => __( 'Delivery Time Type', 'minimog' ),
				'desc_tip' => true,
				'options'  => array(
					''      => __( 'Default', 'minimog' ),
					'days'  => __( 'Days', 'minimog' ),
					'hours' => __( 'Hours', 'minimog' ),
				),
			)
		);
	}

	public function save_product_data( $post_id ) {
		$product = wc_get_product( $post_id );

		$delivery_time_range_begin = isset( $_POST['_shipping_delivery_time_range_begin'] ) ? sanitize_text_field( $_POST['_shipping_delivery_time_range_begin'] ) : '';
		$product->update_meta_data( '_shipping_delivery_time_range_begin', $delivery_time_range_begin );

		$delivery_time_range = isset( $_POST['_shipping_delivery_time_range'] ) ? sanitize_text_field( $_POST['_shipping_delivery_time_range'] ) : '';
		$product->update_meta_data( '_shipping_delivery_time_range', $delivery_time_range );

		$delivery_time_type = isset( $_POST['_shipping_delivery_time_type'] ) ? sanitize_text_field( $_POST['_shipping_delivery_time_type'] ) : '';
		$product->update_meta_data( '_shipping_delivery_time_type', $delivery_time_type );

		$product->save();
	}
}

Shipping::instance()->initialize();
