<?php
/**
 * Modal Shipping Calculator
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="minimog-modal modal-cart modal-cart-shipping-calculator" id="modal-cart-shipping-calculator" aria-hidden="true" role="dialog" hidden>
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<div class="modal-content-header">
					<h4 class="modal-title"><?php esc_html_e( 'Estimate shipping rates', 'minimog' ); ?></h4>
				</div>

				<?php wc_get_template( 'cart/fly-cart-shipping-calculator-form.php' ); ?>

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
						'data-minimog-target'  => '#modal-cart-shipping-calculator',
					],
				] );
				?>
			</div>
		</div>
	</div>
</div>
