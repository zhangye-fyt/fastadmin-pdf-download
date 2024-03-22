<?php
/**
 * Loop Brand
 *
 * @package Minimog
 * @since   2.0.0
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

$brands = Minimog_Woo::instance()->get_the_product_brands();

if ( empty( $brands ) ) {
	return;
}

$term = array_shift( $brands );

$is_external = true;
$link        = get_term_meta( $term->term_id, 'url', true );

if ( empty( $link ) ) {
	$is_external = false;
	$link        = get_term_link( $term, 'product_brand' );
}

if ( is_wp_error( $link ) ) {
	return;
}
?>
<div class="loop-product-brand">
	<a href="<?php echo esc_url( $link ); ?>"
		<?php if ( $is_external ): ?>
			target="_blank"
		<?php endif; ?>
	>
		<?php echo esc_html( $term->name ); ?>
	</a>
</div>
