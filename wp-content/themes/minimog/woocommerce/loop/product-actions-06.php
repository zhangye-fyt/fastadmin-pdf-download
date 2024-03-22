<?php
/**
 * Loop Product Actions
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="product-actions">
	<?php
	$button_settings = [
		'show_tooltip'     => true,
		'tooltip_position' => 'left',
		'style'            => '01',
	];

	Minimog\Woo\Wishlist::output_button( $button_settings );
	Minimog\Woo\Compare::output_button( $button_settings );
	Minimog\Woo\Quick_View::output_button( $button_settings );
	?>
</div>
