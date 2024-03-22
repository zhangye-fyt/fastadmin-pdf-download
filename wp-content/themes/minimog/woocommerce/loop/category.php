<?php
/**
 * Loop Category
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.9.2
 */

defined( 'ABSPATH' ) || exit;

global $product;

$categories = Minimog_Woo::instance()->get_the_product_categories();

if ( empty( $categories ) ) {
	return;
}

$term = array_shift( $categories );
$term = Minimog_Helper::get_deepest_term( $term, $categories );

$link = get_term_link( $term, 'product_cat' );
if ( is_wp_error( $link ) ) {
	return;
}
?>
<div class="loop-product-category">
	<a href="<?php echo esc_url( $link ); ?>">
		<?php echo esc_html( $term->name ); ?>
	</a>
</div>
