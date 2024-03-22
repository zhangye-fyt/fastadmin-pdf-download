<?php
/**
 * Loop Product Actions
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="product-actions">
	<div class="inner">
		<?php
		$button_settings = [
			'show_tooltip'     => true,
			'tooltip_position' => 'top',
			'style'            => '03',
		];

		woocommerce_template_loop_add_to_cart();
		Minimog\Woo\Wishlist::output_button( $button_settings );
		Minimog\Woo\Compare::output_button( $button_settings );
		Minimog\Woo\Quick_View::output_button( $button_settings );
		?>
	</div>
</div>
