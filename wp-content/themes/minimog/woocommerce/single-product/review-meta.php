<?php
/**
 * The template to display the reviewers meta data (name, verified owner, review date)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review-meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $comment;
$verified = wc_review_is_from_verified_owner( $comment->comment_ID );
?>
<div class="meta woo-comment-author-meta">
	<?php
	if ( '0' === $comment->comment_approved ) { ?>
		<em class="woocommerce-review__awaiting-approval">
			<?php esc_attr_e( 'Your review is awaiting approval', 'minimog' ); ?>
		</em>
	<?php } else { ?>
		<h6 class="woocommerce-review__author"><?php comment_author(); ?></h6>
		<?php
		if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) {
			echo '<em class="woocommerce-review__verified verified">(' . esc_attr__( 'verified owner', 'minimog' ) . ')</em> ';
		}
		?>
		<time class="woocommerce-review__published-date comment-datetime"
		      datetime="<?php echo get_comment_date( 'c' ); ?>">
			<?php echo sprintf( __( 'on %s', 'minimog' ), get_comment_date() ); ?>
		</time>
	<?php } ?>
</div>
