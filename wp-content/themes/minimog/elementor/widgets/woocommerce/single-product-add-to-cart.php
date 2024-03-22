<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Single_Product_Add_To_Cart extends Base {
	public function get_name() {
		return 'tm-single-product-add-to-cart';
	}

	public function get_title() {
		return esc_html__( 'Add To Cart', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-product-add-to-cart';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'add to cart' ];
	}

	public function get_categories() {
		return [ 'minimog_wc_product' ];
	}

	/*protected function register_controls() {
		$this->start_controls_section( 'section_single_product_related', [
			'label' => esc_html__( 'Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );


		$this->end_controls_section();
	}*/

	protected function render() {
		global $product;

		if ( empty( $product ) && ! $product instanceof \WC_Product ) {
			return;
		}

		woocommerce_template_single_add_to_cart();
	}

	public function get_group_name() {
		return 'woocommerce';
	}
}
