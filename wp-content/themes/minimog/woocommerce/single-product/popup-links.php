<?php
/**
 * Popup Links
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="product-popup-links">

	<?php do_action( 'minimog/single_product/before_popup_links' ); ?>

	<?php if ( '1' === \Minimog::setting( 'single_product_question_enable' ) && \Minimog\Woo\Product_Question::instance()->current_user_can_post_question() ) : ?>
		<div class="product-popup-link">
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>"
			   id="open-product-question-popup-btn"
			   class="product-action open-product-question-popup-btn"
			   data-minimog-toggle="modal"
			   data-minimog-target="#modal-product-question"
			>
				<?php esc_html_e( 'Ask a Question', 'minimog' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<?php if ( '1' === \Minimog::setting( 'single_product_sharing_enable' ) && ! empty( \Minimog::setting( 'social_sharing_item_enable' ) ) ): ?>
		<div class="product-popup-link">
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>"
			   id="open-product-share-popup-btn"
			   class="product-action open-product-share-popup-btn"
			   data-minimog-toggle="modal"
			   data-minimog-target="#modal-product-share"
			>
				<?php esc_html_e( 'Share', 'minimog' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<?php do_action( 'minimog/single_product/after_popup_links' ); ?>

</div>
