<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Single_Product_Related extends Base {
	public function get_name() {
		return 'tm-single-product-related';
	}

	public function get_title() {
		return esc_html__( 'Product Related', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-product-related';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'related' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_single_product_related', [
			'label' => esc_html__( 'Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );


		$this->end_controls_section();
	}

	public function get_categories() {
		return [ 'minimog_wc_product' ];
	}

	protected function render() {
		global $product;

		if ( empty( $product ) && ! $product instanceof \WC_Product ) {
			return;
		}

		$product_args = array(
			'posts_per_page' => 4,
			'columns'        => 4,
			'orderby'        => 'rand', // @codingStandardsIgnoreLine.
		);

		$product_args = apply_filters( 'woocommerce_output_related_products_args', $product_args );

		woocommerce_related_products( $product_args );

		wc_get_template( 'single-product/related.php' );
	}

	public function get_group_name() {
		return 'woocommerce';
	}
}
