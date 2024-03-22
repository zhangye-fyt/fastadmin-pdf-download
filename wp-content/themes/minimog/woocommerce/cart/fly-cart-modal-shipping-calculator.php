<?php
/**
 * Fly cart modal shipping calculator
 */

defined( 'ABSPATH' ) || exit;
?>
<label class="fly-cart-modal-title"><?php esc_html_e( 'Estimate shipping rates', 'minimog' ); ?></label>
<div class="fly-cart-modal-content">
	<?php wc_get_template( 'cart/fly-cart-shipping-calculator-form.php' ); ?>
</div>

<div class="fly-cart-modal-actions">
	<?php
	\Minimog_Templates::render_button( [
		'text'        => esc_html__( 'Cancel', 'minimog' ),
		'link'        => [
			'url' => '#',
		],
		'extra_class' => 'btn-close-fly-cart-modal',
		'full_wide'   => true,
		'wrapper'     => false,
		'style'       => 'text',
	] );
	?>
</div>
