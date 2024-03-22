<?php
defined( 'ABSPATH' ) || exit;

$question_count = count( $questions );
?>
<ol id="question-list" class="comment-list product-comment-list question-list">
	<?php
	if ( $question_count ) {
		echo wp_list_comments( $list_comments_args, $questions );
	} else {
		echo '<li class="woocommerce-noreviews"><p>' . esc_html__( 'There are no question found.', 'minimog' ) . '</p></li>';
	}
	?>
</ol>
