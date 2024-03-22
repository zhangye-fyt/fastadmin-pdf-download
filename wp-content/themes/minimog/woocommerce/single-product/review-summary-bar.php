<?php
/**
 * The template to display the review bar info above review list & questions list.
 *
 * @package Minimog/WooCommerce/Templates
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var WC_Product $product
 */
global $product;

$rating_average = $product->get_average_rating();
$review_count   = $product->get_review_count();
?>
<div class="product-reviews-summary-bar">
	<h4 class="heading"><?php esc_html_e( 'Rating & Review', 'minimog' ); ?></h4>
	<div class="product-reviews-summary-bar-info">
		<div class="product-reviews-summary-bar-details">
			<?php Minimog_Templates::render_rating( $rating_average ); ?>
			<div class="reviews-count">
				<?php
				printf( esc_html( _n( 'Based on %1$s Review', 'Based on %1$s Reviews', $review_count, 'minimog' ) ), $review_count );
				?>
			</div>
		</div>
		<?php
		if ( comments_open() ) :
			Minimog_Templates::render_button( [
				'text'       => esc_html__( 'Write a review', 'minimog' ),
				'link'       => [
					'url' => '#',
				],
				'style'      => 'border',
				'attributes' => [
					'data-minimog-toggle' => 'modal',
					'data-minimog-target' => '#modal-product-write-review',
				],
			] );
		endif;
		?>
	</div>
</div>
