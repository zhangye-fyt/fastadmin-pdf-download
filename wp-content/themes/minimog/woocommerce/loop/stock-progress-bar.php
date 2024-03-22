<?php
/**
 * Stock Progress Bar
 */

defined( 'ABSPATH' ) || exit;

global $product, $post;

$stock_quantity = $product->get_stock_quantity();

if ( empty( $stock_quantity ) ) {
	return;
}

$sold        = intval( get_post_meta( $post->ID, 'total_sales', true ) );
$stock_first = $stock_quantity + $sold;
$available   = (float) ( 100 / $stock_first ) * ( $stock_quantity );
?>
<div class="loop-product-stock">
	<div class="status-bar">
		<div class="sold-bar" style="width: <?php echo esc_attr( $available ); ?>%"></div>
	</div>
	<div class="product-stock-status">
		<div class="product-stock-status-item sold">
			<span class="label"><?php esc_html_e( 'Sold: ', 'minimog' ); ?></span>
			<span class="value"><?php echo esc_html( $sold ); ?></span>
		</div>
		<div class="product-stock-status-item available">
			<span class="label"><?php esc_html_e( 'Available: ', 'minimog' ); ?></span>
			<span class="value"><?php echo esc_html( $stock_quantity ); ?></span>
		</div>
	</div>
</div>
