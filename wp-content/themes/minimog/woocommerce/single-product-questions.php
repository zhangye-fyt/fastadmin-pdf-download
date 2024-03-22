<?php
/**
 * Display single product questions (comments)
 *
 * @package Minimog/WooCommerce/Templates
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

$questions = \Minimog\Woo\Product_Question::instance()->get_top_questions([
	'post_id' => $product->get_id(),
] );

// Parent Comment Count.
$question_count = count( $questions );

$questions_children = array();

foreach ( $questions as $question ) {
	$_questions = get_comments( array(
		'parent'       => $question->comment_ID,
		'hierarchical' => true,
		'status'       => 'approve',
	) );

	foreach ( $_questions as $_question ) {
		$questions_children[] = $_question;
	}
}

$all_questions = array_merge( $questions, $questions_children );

$container_class = 'question-list-container';

if ( ! $question_count ) {
	$container_class .= ' no-question';
}

$max_depth    = get_option( 'thread_comments' ) ? get_option( 'thread_comments_depth' ) : -1;
$threaded     = ( -1 != $max_depth );
$per_page     = \Minimog\Woo\Product_Question::instance()->get_comment_per_page();
$current_page = ( 'newest' === get_option( 'default_comments_page' ) ) ? get_comment_pages_count( $questions, $per_page, $threaded ) : 1;
$total_pages  = $per_page > 0 ? ceil( $question_count / $per_page ) : 1;

if ( $current_page > $total_pages ) {
	$current_page = $total_pages;
}
?>
<div id="minimog-wc-question" class="minimog-wc-question woocommerce-question">
	<div class="minimog-wc-question__wrapper">
		<div class="product-reviews-summary-bar">
			<h4 class="heading"><?php esc_html_e( 'Question & Answer', 'minimog' ); ?></h4>
			<div class="product-reviews-summary-bar-info">
				<div class="product-reviews-summary-bar-details">
					<div class="reviews-count">
						<?php
						printf( esc_html( _n( '%1$s Question', '%1$s Questions', $question_count, 'minimog' ) ), '<span class="question-count">' . $question_count . '</span>' );
						?>
					</div>
				</div>
				<?php
				if ( comments_open()
				     && '1' === \Minimog::setting( 'single_product_question_enable' )
				     && \Minimog\Woo\Product_Question::instance()->current_user_can_post_question()
				) :
					Minimog_Templates::render_button( [
						'text'       => esc_html__( 'Ask a Question', 'minimog' ),
						'link'       => [
							'url' => '#',
						],
						'style'      => 'border',
						'attributes' => [
							'data-minimog-toggle' => 'modal',
							'data-minimog-target' => '#modal-product-question',
						],
					] );
				endif;
				?>
			</div>
		</div>

		<div class="<?php echo esc_attr( $container_class ); ?>">
			<div class="question-toolbar">
				<?php if ( $question_count > 1 ) : ?>
					<div class="question-filter">
						<form class="question-search-form" method="GET">
							<label for="question-search-form__filter-content"
							       class="hidden"><?php esc_html_e( 'Search', 'minimog' ); ?></label>
							<input
								id="question-search-form__filter-content"
								type="text"
								name="keyword"
								placeholder="<?php echo esc_attr( 'Search', 'minimog' ); ?>">
							<input type="hidden" name="action" value="minimog_get_questions">
							<input type="hidden" name="current_page" value="1">
							<input type="hidden" name="post_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
						</form>
					</div>
				<?php endif; ?>
			</div>

			<div class="question-list-wrapper">

				<?php wc_get_template( 'single-product/product-question/questions.php', [
					'list_comments_args' => Minimog\Woo\Product_Question::instance()->get_comment_list_args( [
						'page' => $current_page,
					] ),
					'questions'          => $all_questions,
				] ); ?>

				<?php
				if ( get_comment_pages_count( $questions ) > 1 && get_option( 'page_comments' ) ) {
					?>
					<nav class="navigation question-navigation comment-navigation">
						<h2 class="screen-reader-text"><?php esc_html_e( 'Question navigation', 'minimog' ); ?></h2>

						<div class="comment-nav-links">
							<?php
							\Minimog_Templates::render_paginate_links( [
								'format'  => '?current_page=%#%',
								'current' => max( 1, $current_page ),
								'total'   => $total_pages,
							] );
							?>
						</div>
					</nav>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
