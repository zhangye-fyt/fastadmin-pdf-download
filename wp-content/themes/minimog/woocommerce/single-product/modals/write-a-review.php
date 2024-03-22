<?php
/**
 * Modal Write a Review
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}
?>
<div class="minimog-modal modal-product-write-review" id="modal-product-write-review" aria-hidden="true" role="dialog" hidden>
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<div class="modal-content-header">
					<h4 class="modal-title"><?php esc_html_e( 'Write a Review', 'minimog' ); ?></h4>
				</div>
				<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
					<div id="review_form">
						<?php
						$comment_form_title = '<p class="comment-form-title"><label for="comment-title">' . esc_html__( 'Give your review a title', 'minimog' ) . '</label><input type="text" id="comment-title" name="comment_title" required/></p>';

						$commenter    = wp_get_current_commenter();
						$comment_form = array(
							'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'minimog' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'minimog' ), get_the_title() ),
							'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'minimog' ),
							'title_reply_before'   => '<span id="reply-title" class="comment-reply-title">',
							'title_reply_after'    => '</span>',
							'comment_notes_after'  => '',
							'comment_notes_before' => '',
							'label_submit'         => esc_html__( 'Submit Now', 'minimog' ),
							'logged_in_as'         => '',
							'comment_field'        => $comment_form_title . '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'minimog' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>',
						);

						$name_email_required = (bool) get_option( 'require_name_email', 1 );
						$fields              = array(
							'author' => array(
								'label'    => __( 'Name', 'minimog' ), // WPCS: XSS OK.
								'type'     => 'text',
								'value'    => $commenter['comment_author'],
								'required' => $name_email_required,
								'before'   => '<div class="row">',
							),
							'email'  => array(
								'label'    => __( 'Email', 'minimog' ),  // WPCS: XSS OK.
								'type'     => 'email',
								'value'    => $commenter['comment_author_email'],
								'required' => $name_email_required,
								'after'    => '</div>',
							),
						);

						$comment_form['fields'] = array();

						foreach ( $fields as $key => $field ) {
							$field_html = '';

							if ( ! empty( $field['before'] ) ) {
								$field_html .= $field['before'];
							}

							$field_html .= '<div class="col-sm-6">';
							$field_html .= '<p class="comment-form-' . esc_attr( $key ) . '">';
							$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . ( $field['required'] ? '&nbsp;<span class="required">*</span>' : '' ) . '</label>';
							$field_html .= '<input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';
							$field_html .= '</div>'; // End .col-sm-6

							if ( ! empty( $field['after'] ) ) {
								$field_html .= $field['after'];
							}

							$comment_form['fields'][ $key ] = $field_html;
						}

						$account_page_url = wc_get_page_permalink( 'myaccount' );
						if ( $account_page_url ) {
							/* translators: %s opening and closing link tags respectively */
							$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$s logged in %2$s to post a review.', 'minimog' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
						}

						if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
							$rating_template = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating:', 'minimog' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
										<option value="">' . esc_html__( 'Rate&hellip;', 'minimog' ) . '</option>
										<option value="5">' . esc_html__( 'Perfect', 'minimog' ) . '</option>
										<option value="4">' . esc_html__( 'Good', 'minimog' ) . '</option>
										<option value="3">' . esc_html__( 'Average', 'minimog' ) . '</option>
										<option value="2">' . esc_html__( 'Not that bad', 'minimog' ) . '</option>
										<option value="1">' . esc_html__( 'Very poor', 'minimog' ) . '</option>
									</select></div>';

							$comment_form['comment_field'] = $rating_template . $comment_form['comment_field'];
						}

						comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
						?>
					</div>
				<?php else : ?>
					<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'minimog' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
