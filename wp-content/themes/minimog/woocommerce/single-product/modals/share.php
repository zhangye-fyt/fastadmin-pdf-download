<?php
/**
 * Modal Share
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="minimog-modal modal-product-share" id="modal-product-share" aria-hidden="true" role="dialog" hidden>
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<?php
				$social_sharing = \Minimog::setting( 'social_sharing_item_enable' );
				if ( ! empty( $social_sharing ) ) :
					?>
					<div class="product-share">
						<div class="form-item">
							<label for="product-share-url">
								<?php esc_html_e( 'Copy link', 'minimog' ); ?>
							</label>
							<div class="form-control hint--top" style="width: 100%"
							     aria-label="<?php esc_attr_e( 'Click to copy', 'minimog' ); ?>"
							     data-copy="<?php esc_attr_e( 'Click to copy', 'minimog' ); ?>"
							     data-copied="<?php esc_attr_e( 'Copied', 'minimog' ); ?>"
							>
								<input id="product-share-url" type="text" readonly
								       value="<?php echo esc_url( $product->get_permalink() ); ?>">
							</div>
						</div>
						<div class="product-share-list">
							<label>
								<?php esc_html_e( 'Share', 'minimog' ); ?>
							</label>
							<div class="share-list">
								<?php \Minimog_Templates::get_sharing_list( [
									'tooltip_position' => 'top-right',
								] ); ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
