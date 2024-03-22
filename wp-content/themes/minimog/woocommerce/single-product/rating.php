<?php
/**
 * Single Product Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();
?>
<div class="woocommerce-product-rating">
	<?php Minimog_Templates::render_rating( $average, [
		'wrapper_class' => 'entry-product-star-rating',
	] ); ?>
	<?php
	$is_comment_open = comments_open();

	$review_html = esc_html( sprintf( _n( '%s review', '%s reviews', $review_count, 'minimog' ), $review_count ) );

	if ( $is_comment_open ) :
		$review_html = '<a href="#reviews" class="woocommerce-review-link">(' . $review_html . ')</a>';
	endif;

	echo $review_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.
	?>
</div>
