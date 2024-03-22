<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

$count = $product->get_review_count();

$wrapper_class = 'review-rating-template-wrapper';

if ( ! $count ) {
	$wrapper_class .= ' no-reviews';
}
?>
<div id="reviews" class="woocommerce-Reviews">
	<div id="comments">
		<div class="<?php echo esc_attr( $wrapper_class ) ?>">

			<?php wc_get_template( 'single-product/review-summary-bar.php' ); ?>

			<div class="reviews-content">
				<div id="review_form_wrapper">

				</div>

				<?php if ( have_comments() ) : ?>

					<ol class="commentlist comment-list product-comment-list">
						<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
					</ol>
					<?php
					if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
						echo '<nav class="woocommerce-pagination">';
						paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
							'prev_text' => \Minimog_Templates::get_pagination_prev_text(),
							'next_text' => \Minimog_Templates::get_pagination_next_text(),
							'type'      => 'list',
						) ) );
						echo '</nav>';
					}
					?>
				<?php else : ?>
					<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'minimog' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
