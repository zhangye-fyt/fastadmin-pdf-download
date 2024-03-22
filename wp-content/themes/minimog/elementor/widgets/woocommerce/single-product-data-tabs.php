<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Single_Product_Data_Tabs extends Base {
	public function get_name() {
		return 'tm-single-product-data-tabs';
	}

	public function get_title() {
		return esc_html__( 'Product Data Tabs', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-product-tabs';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'tabs' ];
	}

	public function get_categories() {
		return [ 'minimog_wc_product' ];
	}

	protected function render() {
		global $product;

		if ( empty( $product ) && ! $product instanceof \WC_Product ) {
			return;
		}

		wc_get_template( 'single-product/tabs/tabs.php' );
	}

	public function get_group_name() {
		return 'woocommerce';
	}
}
