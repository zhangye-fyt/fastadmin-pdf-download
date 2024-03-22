<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Minimog\Woo\Single_Product;

defined( 'ABSPATH' ) || exit;

class Widget_Single_Product_Live_Visitors extends Base {
	public function get_name() {
		return 'tm-single-product-live-visitors';
	}

	public function get_title() {
		return esc_html__( 'Product Live Visitors', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-preview-thin';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'live', 'visitors' ];
	}

	public function get_categories() {
		return [ 'minimog_wc_product' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_single_product_live_visitors', [
			'label' => esc_html__( 'Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'wc_style_warning', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => esc_html__( 'The visibility of this widget is affected by setting in Theme Options', 'minimog' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		global $product;

		if ( empty( $product ) && ! $product instanceof \WC_Product ) {
			return;
		}

		Single_Product::instance()->output_product_live_view_visitors();
	}

	public function get_group_name() {
		return 'woocommerce';
	}
}
