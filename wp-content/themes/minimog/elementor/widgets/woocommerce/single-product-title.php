<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Heading;

defined( 'ABSPATH' ) || exit;

class Widget_Single_Product_Title extends Widget_Heading {
	public function get_name() {
		return 'tm-single-product-title';
	}

	public function get_title() {
		return esc_html__( 'Product Title', 'minimog' );
	}

	public function get_icon() {
		return 'minimog-badge ' . $this->get_icon_part();
	}

	public function get_icon_part() {
		return 'eicon-product-title';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'product title' ];
	}

	public function get_inline_css_depends() {
		return [
			[
				'name'               => 'heading',
				'is_core_dependency' => true,
			],
		];
	}

	public function get_categories() {
		return [ 'minimog_wc_product' ];
	}

	protected function register_controls() {
		parent::register_controls();

		$this->update_control( 'title', [
			'dynamic' => [
				'default' => Plugin::instance()->dynamic_tags->tag_data_to_tag_text( null, 'tm-product-title-tag' ),
			],
		], [
			'recursive' => true,
		] );

		$this->update_control(
			'header_size',
			[
				'default' => 'h1',
			]
		);
	}

	protected function render() {
		global $product;

		if ( empty( $product ) && ! $product instanceof \WC_Product ) {
			return;
		}

		$this->add_render_attribute( 'title', 'class', [ 'product_title', 'entry-title' ] );
		parent::render();
	}

	/**
	 * Render Woocommerce Product Title output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  2.9.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# view.addRenderAttribute( 'title', 'class', [ 'product_title', 'entry-title' ] ); #>
		<?php
		parent::content_template();
	}

	public function render_plain_content() {
	}

	public function get_group_name() {
		return 'woocommerce';
	}
}
