<?php
/**
 * Loop Product Actions
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<?php Minimog\Woo\Wishlist::output_button( [
	'show_tooltip'     => true,
	'tooltip_position' => 'left',
	'style'            => '04',
] ); ?>

<div class="product-actions">
	<?php
	$button_settings = [
		'show_tooltip'     => true,
		'tooltip_position' => 'top',
		'style'            => '01',
	];

	woocommerce_template_loop_add_to_cart();
	Minimog\Woo\Compare::output_button( $button_settings );
	Minimog\Woo\Quick_View::output_button( $button_settings );
	?>
</div>
