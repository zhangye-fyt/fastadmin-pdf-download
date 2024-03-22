<?php
/**
 * The template for displaying product question form
 *
 * @package Minimog/WooCommerce/Templates
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

$req      = get_option( 'require_name_email' );
$aria_req = '';
$required = '';
if ( $req ) {
	$aria_req = " aria-required='true'";
	$required = '<span class="required">*</span>';
}

$reply_to_id = isset( $_GET['reply_to_question'] ) ? (int) $_GET['reply_to_question'] : 0;
?>
<form method="post" id="question-form" class="question-form">
	<?php if ( ! is_user_logged_in() ) : ?>
		<div class="question-form-author">
			<label for="q-author"><?php echo esc_html__( 'Your Name', 'minimog' ) . $required; ?></label>
			<input type="text" name="author" value="" id="q-author" <?php echo '' . $aria_req ?>
			       placeholder="<?php echo esc_attr__( 'Your Name', 'minimog' ) . '*'; ?>">
		</div>
		<div class="question-form-email">
			<label for="q-email"><?php echo esc_html__( 'Your Email', 'minimog' ) . $required; ?></label>
			<input type="email" name="email" value="" id="q-email" <?php echo '' . $aria_req ?>
			       placeholder="<?php echo esc_attr__( 'Your Email', 'minimog' ) . '*'; ?>">
		</div>
	<?php endif; ?>

	<div class="question-form-question">
		<label for="question"><?php echo esc_html__( 'Your Message', 'minimog' ) . $required; ?></label>
		<textarea id="question" name="question" cols="45" rows="8" aria-required="true"
		          placeholder="<?php echo esc_attr__( 'Your Message', 'minimog' ) . '*'; ?>"></textarea>
	</div>
	<div class="question-form-submit form-submit">
		<button type="submit" name="submit"
		        class="submit"><span><?php esc_html_e( 'Submit Now', 'minimog' ); ?></span></button>
		<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
		<input type="hidden" name="question_parent_id" value="<?php echo esc_attr( $reply_to_id ); ?>">
		<input type="hidden" name="action" value="minimog_add_comment">
		<?php wp_nonce_field( 'product_question', 'product_question_nonce' ); ?>
	</div>

	<div class="question-form-message-box"></div>
</form>
