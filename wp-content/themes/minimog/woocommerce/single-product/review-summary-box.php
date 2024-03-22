<?php
/**
 * The template to display the review box info beside review list & questions list.
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

$show_question = '1' === \Minimog::setting( 'single_product_question_enable' ) ? true : false;
?>
<div class="reviews-summary">
	<div
		class="rating-average secondary-font primary-color"><?php echo esc_html( $rating_average ); ?></div>

	<?php Minimog_Templates::render_rating( $rating_average, [
		'wrapper_class' => 'entry-product-star-rating',
	] ); ?>

	<div class="reviews-count">
		<?php
		printf( esc_html( _n( '%1$s Review', '%1$s Reviews', $review_count, 'minimog' ) ), $review_count );

		if ( $show_question ) {
			$questions = get_comments( array(
				'type'    => 'question',
				'post_id' => $product->get_id(),
				'status'  => 'approve',
			) );

			$question_count = count( $questions );

			printf( ', %s', esc_html( _n( 'Q&#38;A', 'Q&#38;As', $question_count, 'minimog' ) ) );
		}
		?>
	</div>

	<?php Minimog_Woo::instance()->custom_woo_reviews_summary(); ?>

	<div class="reviews-summary-buttons">
		<?php
		if ( comments_open() ) :
			Minimog_Templates::render_button( [
				'extra_class' => 'js-btn-toggle-reviews-questions-tab',
				'text'        => esc_html__( 'Write a review', 'minimog' ),
				'link'        => [
					'url' => '#tab-reviews',
				],
				'size'        => 'sm',
			] );

			if ( $show_question
			     && \Minimog\Woo\Product_Question::instance()->current_user_can_post_question()
			) {
				Minimog_Templates::render_button( [
					'extra_class' => 'js-btn-toggle-reviews-questions-tab',
					'text'        => esc_html__( 'Ask a question', 'minimog' ),
					'link'        => [
						'url' => '#tab-questions',
					],
					'size'        => 'sm',
				] );
			}
		endif;
		?>
	</div>
</div>
