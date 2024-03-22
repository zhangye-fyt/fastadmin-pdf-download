<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Single_Product_Recent_Viewed extends Base {
	public function get_name() {
		return 'tm-single-product-recent_viewed';
	}

	public function get_title() {
		return esc_html__( 'Product Recent Viewed', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-products';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'recent' ];
	}

	public function get_categories() {
		return [ 'minimog_wc_product' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_single_product_recent_viewed', [
			'label' => esc_html__( 'Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );


		$this->end_controls_section();
	}

	protected function render() {
		global $product;

		if ( empty( $product ) && ! $product instanceof \WC_Product ) {
			return;
		}

		\Minimog\Woo\Single_Product::instance()->output_recent_viewed_products();
	}

	public function get_group_name() {
		return 'woocommerce';
	}
}
