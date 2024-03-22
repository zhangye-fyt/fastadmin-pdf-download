<?php
/**
 * Modal Order Notes
 */

defined( 'ABSPATH' ) || exit;

$notes = WC()->session->get( 'minimog_order_notes', '' );
?>
<div class="minimog-modal modal-cart modal-cart-order-notes" id="modal-cart-order-notes" aria-hidden="true" role="dialog" hidden>
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<div class="modal-content-header">
					<h4 class="modal-title"><?php esc_html_e( 'Add note for seller', 'minimog' ); ?></h4>
				</div>

				<form class="form-fly-cart-order-notes" method="POST">
					<div class="form-row">
							<textarea name="order_comments" id="cart-order-notes"
							          placeholder="<?php esc_attr_e( 'Notes about your order, e.g. special notes for delivery.', 'minimog' ); ?>"><?php echo '' . $notes; ?></textarea>
					</div>

					<div class="form-submit">
						<button type="submit" class="button"><span><?php esc_html_e( 'Save', 'minimog' ); ?></span></button>
						<?php
						\Minimog_Templates::render_button( [
							'text'       => esc_html__( 'Cancel', 'minimog' ),
							'link'       => [
								'url' => '#',
							],
							'full_wide'  => true,
							'wrapper'     => false,
							'style'      => 'text',
							'attributes' => [
								'data-minimog-dismiss' => '1',
								'data-minimog-toggle'  => 'modal',
								'data-minimog-target'  => '#modal-cart-order-notes',
							],
						] );
						?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
