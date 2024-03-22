<?php
defined( 'ABSPATH' ) || exit;

$form_id = Minimog::setting( 'promo_popup_form_id' );

$type          = Minimog::setting( 'promo_popup_type' );
$style         = Minimog::setting( 'promo_popup_style' );
$heading       = Minimog::setting( 'promo_popup_heading' );
$desc          = Minimog::setting( 'promo_popup_description' );
$content       = Minimog::setting( 'promo_popup_content' );
$image_url     = Minimog_Helper::get_redux_image_url( 'promo_popup_image' );
$button_text   = Minimog::setting( 'promo_popup_button_text' );
$button_url    = Minimog::setting( 'promo_popup_button_url' );
$discount_code = Minimog::setting( 'promo_popup_discount_code' );

$class = 'minimog-modal modal-promo-popup style-' . $style;
?>
<div class="<?php echo esc_attr( $class ); ?>" id="modal-promo-popup" aria-hidden="true" role="dialog" hidden>
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<div class="modal-content-body">
					<?php if ( ! empty( $image_url ) ): ?>
						<div class="promo-popup-image">
							<img src="<?php echo esc_url( $image_url ); ?>"
							     alt="<?php esc_attr_e( 'Popup Image', 'minimog' ); ?>"
							/>
						</div>
					<?php endif; ?>

					<div class="promo-popup-content">
						<?php if ( $heading !== '' ): ?>
							<div class="promo-popup-heading-wrap">
								<h3 class="promo-popup-heading"><?php echo esc_html( $heading ); ?></h3>
							</div>
						<?php endif; ?>

						<?php if ( $desc !== '' ): ?>
							<div class="promo-popup-description-wrap">
								<div class="promo-popup-description"><?php echo esc_html( $desc ); ?></div>
							</div>
						<?php endif; ?>

						<?php if ( $content !== '' ): ?>
							<div class="promo-popup-content-wrap">
								<?php echo do_shortcode( $content ); ?>
							</div>
						<?php endif; ?>

						<?php if ( 'subscribe' === $type && ! empty( $form_id ) ) : ?>
							<div class="promo-popup-component-wrap">
								<div class="promo-popup-form">
									<?php echo do_shortcode( '[wpforms id="' . $form_id . '"]' ); ?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( 'discount_code' === $type && ! empty( $discount_code ) ): ?>
							<div class="promo-popup-component-wrap">
								<div class="promo-popup-discount-code">
									<label class="screen-reader-text" for="promo-popup-discount-code"><?php esc_html_e( 'Discount code', 'minimog' ); ?></label>
									<input id="promo-popup-discount-code" type="text" class="discount-code" readonly name="discount_code"
									       value="<?php echo esc_attr( $discount_code ); ?>"/>
									<button class="btn-copy"
									        data-message-success="<?php esc_attr_e( 'Copied', 'minimog' ); ?>"
									        value="<?php esc_attr_e( 'Copy', 'minimog' ); ?>"><?php esc_html_e( 'Copy', 'minimog' ); ?></button>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! in_array( $type, [
								'discount_code',
								'subscribe',
							], true ) && $button_url !== '' && $button_text !== '' ): ?>
							<div class="promo-popup-component-wrap">
								<?php
								$style = Minimog::setting( 'promo_popup_button_style', 'thick-bottom-line' );

								Minimog_Templates::render_button( [
									'text'        => $button_text,
									'link'        => [
										'url' => $button_url,
									],
									'style'       => $style,
									'extra_class' => 'promo-popup-button',
								] );
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
