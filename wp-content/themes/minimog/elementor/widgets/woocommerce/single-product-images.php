<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Single_Product_Images extends Base {
	public function get_name() {
		return 'tm-single-product-images';
	}

	public function get_title() {
		return esc_html__( 'Product Images', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-product-images';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'product', 'product image', 'product images' ];
	}

	public function get_categories() {
		return [ 'minimog_wc_product' ];
	}

	protected function render() {
		global $product;

		if ( empty( $product ) && ! $product instanceof \WC_Product ) {
			return;
		}
		?>
		<div class="woo-single-images">
			<?php do_action( 'minimog/single_product/images/before' ); ?>

			<?php
			/**
			 * woocommerce_before_single_product_summary hook.
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images - 20
			 */
			do_action( 'woocommerce_before_single_product_summary' );
			?>

			<?php do_action( 'minimog/single_product/images/after' ); ?>
		</div>
		<?php
	}

	public function get_group_name() {
		return 'woocommerce';
	}
}
