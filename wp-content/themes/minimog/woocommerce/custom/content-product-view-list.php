<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="content-view-list">
	<div class="woocommerce-loop-product__desc post-title-2-rows">
		<?php Minimog_Templates::excerpt( array(
			'limit' => 25,
			'type'  => 'word',
		) ); ?>
	</div>

	<div class="product-list-view-actions">
		<?php
		$button_list_settings = [
			'show_tooltip'     => true,
			'tooltip_position' => 'top',
			'style'            => '01',
		];

		woocommerce_template_loop_add_to_cart();

		Minimog\Woo\Wishlist::output_button( $button_list_settings );
		Minimog\Woo\Compare::output_button( $button_list_settings );
		Minimog\Woo\Quick_View::output_button( $button_list_settings );
		?>
	</div>
</div>
