<?php
/**
 * Modal Coupon
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="minimog-modal modal-cart modal-cart-coupon" id="modal-cart-coupon" aria-hidden="true" role="dialog" hidden>
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<div class="modal-content-header">
					<h4 class="modal-title"><?php esc_html_e( 'Select or input Coupon', 'minimog' ); ?></h4>
				</div>

				<?php do_action( 'minimog/coupon_modal/before' ); ?>

				<form class="form-coupon" method="POST">
					<p class="form-description"><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'minimog' ); ?></p>

					<div class="form-row">
						<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon code', 'minimog' ); ?></label>
						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" required
						       placeholder="<?php esc_attr_e( 'Coupon code', 'minimog' ); ?>"/>
					</div>

					<div class="form-submit">
						<button type="submit" class="button" name="apply_coupon"
						        value="<?php esc_attr_e( 'Apply coupon', 'minimog' ); ?>"><?php esc_html_e( 'Apply coupon', 'minimog' ); ?></button>
					</div>

					<?php
					\Minimog_Templates::render_button( [
						'text'       => esc_html__( 'Cancel', 'minimog' ),
						'link'       => [
							'url' => '#',
						],
						'full_wide'  => true,
						'wrapper'    => false,
						'style'      => 'text',
						'attributes' => [
							'data-minimog-dismiss' => '1',
							'data-minimog-toggle'  => 'modal',
							'data-minimog-target'  => '#modal-cart-coupon',
						],
					] );
					?>

					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</form>

				<?php do_action( 'minimog/coupon_modal/after' ); ?>
			</div>
		</div>
	</div>
</div>
