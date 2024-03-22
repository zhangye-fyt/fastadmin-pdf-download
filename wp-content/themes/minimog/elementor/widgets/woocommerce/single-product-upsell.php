<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Single_Product_Upsell extends Base {
	public function get_name() {
		return 'tm-single-product-upsell';
	}

	public function get_title() {
		return esc_html__( 'Product Upsell', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-product-upsell';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'upsell' ];
	}

	public function get_categories() {
		return [ 'minimog_wc_product' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_single_product_upsell', [
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

		woocommerce_upsell_display();
	}

	public function get_group_name() {
		return 'woocommerce';
	}
}
