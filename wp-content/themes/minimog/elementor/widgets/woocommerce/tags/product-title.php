<?php
namespace Minimog_Elementor\Modules\Woocommerce\Tags;

defined( 'ABSPATH' ) || exit;

class Product_Title extends Base_Tag {
	public function get_name() {
		return 'tm-product-title-tag';
	}

	public function get_title() {
		return esc_html__( 'Product Title', 'minimog' );
	}

	protected function register_controls() {
		$this->add_product_id_control();
	}

	public function render() {
		$product = wc_get_product( $this->get_settings( 'product_id' ) );
		if ( ! $product ) {
			return;
		}

		echo wp_kses_post( $product->get_title() );
	}
}
