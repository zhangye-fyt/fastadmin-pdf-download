<?php
/**
 * Single Product stock.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/stock.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

$stock_amount     = $product->get_stock_quantity();
$low_stock_amount = wc_get_low_stock_amount( $product );
$stock_percent    = 0;
$wrap_class       = [
	'stock',
	$class,
	'entry-product-stock',
];

if ( '1' === \Minimog::setting( 'single_product_low_stock_enable' ) && $stock_amount > 0 && $low_stock_amount > 0 && $stock_amount <= $low_stock_amount ) {
	$stock_percent = \Minimog_Helper::calculate_percentage( $stock_amount, $low_stock_amount );
	$wrap_class[]  = ' low-stock-bar';
}
?>
<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
	<?php echo wp_kses_post( $availability ); ?>

	<?php if ( ! empty( $stock_percent ) ) : ?>
		<div class="minimog-progress">
			<div class="progress-bar-wrap">
				<div class="progress-bar"
				     role="progressbar"
				     aria-label="<?php esc_attr_e( 'Low stock bar', 'minimog' ); ?>"
				     style="<?php echo esc_attr( "width: {$stock_percent}%" ); ?>"
				     aria-valuenow="<?php echo esc_attr( $stock_percent ); ?>"
				     aria-valuemin="0"
				     aria-valuemax="100"></div>
			</div>
		</div>
	<?php endif; ?>
</div>
