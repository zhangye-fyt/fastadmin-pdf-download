<?php
/**
 * Product Questions Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

global $comment;
?>
<li <?php comment_class( 'comment' ); ?> id="li-comment-<?php comment_ID(); ?>">
	<div id="comment-<?php comment_ID(); ?>" class="comment-wrap comment_container">
		<div class="woo-comment-author">
			<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
		</div>
		<div class="woo-comment-content">
			<div class="meta woo-comment-author-meta">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-messages"><?php esc_html_e( 'Your question is awaiting moderation.', 'minimog' ) ?></em>
				<?php else : ?>
					<?php printf( '<h6 class="fn woocommerce-review__author">%s</h6>', get_comment_author_link() ); ?>
					<time class="comment-datetime">
						<?php echo sprintf( __( 'on %s', 'minimog' ), get_comment_date() ); ?>
					</time>
				<?php endif; ?>
			</div>

			<div class="comment-text"><?php comment_text(); ?></div>

			<?php if ( \Minimog\Woo\Product_Question::instance()->current_user_can_reply_question() ) : ?>
				<div class="comment-footer woo-comment-footer">
					<?php
					\Minimog\Woo\Product_Question::instance()->question_reply_and_cancel_link( [
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
					], $comment );
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
