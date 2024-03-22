<?php
/**
 * Modal Ask A Question
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="minimog-modal modal-product-question" id="modal-product-question"
     data-question-title="<?php esc_attr_e( 'Ask a Question', 'minimog' ); ?>"
     data-reply-title="<?php esc_attr_e( 'Reply to {comment_author_name}', 'minimog' ); ?>" aria-hidden="true" role="dialog" hidden>
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<div class="modal-content-header">
					<h4 class="modal-title"><?php esc_html_e( 'Ask a Question', 'minimog' ); ?></h4>
				</div>
				<div class="question-form-wrapper">
					<?php Minimog\Woo\Product_Question::instance()->question_form(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
