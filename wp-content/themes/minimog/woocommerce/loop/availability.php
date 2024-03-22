<?php
/**
 * Availability
 */

defined( 'ABSPATH' ) || exit;

global $product;

$wrap_class = 'loop-product-availability';

if ( ! $product->is_in_stock() ) {
	$availability = __( 'Out of stock', 'minimog' );

	$wrap_class .= ' out-of-stock';
} elseif ( $product->managing_stock() && $product->is_on_backorder( 1 ) ) {
	$availability = $product->backorders_require_notification() ? __( 'Available on backorder', 'minimog' ) : '';
} elseif ( ! $product->managing_stock() && $product->is_on_backorder( 1 ) ) {
	$availability = __( 'Available on backorder', 'minimog' );
} elseif ( $product->managing_stock() ) {
	$availability   = __( 'In stock', 'minimog' );
	$stock_quantity = $product->get_stock_quantity();

	$availability = $availability . ', ' . sprintf( _n( '%s unit', '%s units', $stock_quantity, 'minimog' ), $stock_quantity );

	$wrap_class .= ' in-stock';
} else {
	$availability = __( 'In stock', 'minimog' );
}
?>
<div class="<?php echo esc_attr( $wrap_class ); ?>">
	<?php echo esc_html( $availability ); ?>
</div>
