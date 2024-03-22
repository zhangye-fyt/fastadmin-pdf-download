<?php
/**
 * Category item min price
 */

defined( 'ABSPATH' ) || exit;
extract( $args );

if ( empty( $settings['show_min_price'] ) ) {
	return;
}

$min_price = Minimog\Woo\Product_Category::instance()->get_min_price( $category->term_id );
?>
<div class="category-min-price">
	<?php echo sprintf( __( 'from %s', 'minimog' ), wc_price( $min_price ) ); ?>
</div>
