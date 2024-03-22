<?php
/**
 * Fly cart modal coupon
 */

defined( 'ABSPATH' ) || exit;
?>
<form class="form-coupon" method="POST">
	<label class="fly-cart-modal-title"
	       for="fly-cart-coupon_code"><?php esc_html_e( 'Select or input Coupon', 'minimog' ); ?></label>

	<?php do_action( 'minimog/coupon_modal/before' ); ?>

	<p class="form-description"><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'minimog' ); ?></p>
	<div class="fly-cart-modal-content">
		<input type="text" name="coupon_code" class="input-text" id="fly-cart-coupon_code" value=""
		       required
		       placeholder="<?php esc_attr_e( 'Coupon code', 'minimog' ); ?>"/>
	</div>

	<div class="fly-cart-modal-actions">
		<button type="submit" class="button minimog-coupon-check" name="apply_coupon"
		        value="<?php esc_attr_e( 'Apply', 'minimog' ); ?>"><?php esc_html_e( 'Apply', 'minimog' ); ?></button>
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

	<?php do_action( 'minimog/coupon_modal/after' ); ?>
</form>
