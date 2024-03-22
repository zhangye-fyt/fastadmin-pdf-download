<?php
/**
 * The template for displaying product widget entries.
 */

defined( 'ABSPATH' ) || exit;

/** @var WC_Product $product */
global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

switch ( $style ) {
	case 'big-thumbnail':
		$thumbnail_size = 450;
		break;
	case 'boxed':
		$thumbnail_size = 100;
		break;
	default:
		$thumbnail_size = 70;
		break;
}
?>
<li>
	<?php do_action( 'woocommerce_widget_product_item_start', $args ); ?>

	<div class="product-item">
		<div class="thumbnail">
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
				<?php echo Minimog_Woo::instance()->get_product_image( $product, Minimog_Woo::instance()->get_loop_product_image_size( $thumbnail_size ) ); ?>
			</a>
		</div>
		<div class="info">
			<h6 class="product-title post-title-2-rows">
				<a href="<?php the_permalink(); ?>">
					<?php echo wp_kses_post( $product->get_name() ); ?>
				</a>
			</h6>

			<?php if ( ! empty( $show_rating ) ) : ?>
				<?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating() ) ); ?>
			<?php endif; ?>

			<?php if ( 'variable' !== $product->get_type() || ( 'variable' === $product->get_type() && empty( $show_buttons ) ) ) : ?>
				<?php echo wp_kses( $product->get_price_html(), 'minimog-default' ); ?>
			<?php endif; ?>

			<?php if ( ! empty( $show_buttons ) ) : ?>
				<?php wc_get_template( 'custom/add-to-cart-variations-dropdown.php' ); ?>
			<?php endif; ?>
		</div>
	</div>

	<?php do_action( 'woocommerce_widget_product_item_end', $args ); ?>
</li>
