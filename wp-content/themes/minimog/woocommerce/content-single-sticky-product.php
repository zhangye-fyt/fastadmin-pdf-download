<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

if ( '1' !== Minimog::setting( 'single_product_sticky_bar_enable' ) ) {
	return;
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

$thumbnail_size = Minimog_Woo::instance()->get_loop_product_image_size( 60 );
?>
<div id="sticky-product-bar" <?php wc_product_class( 'sticky-product', $product ); ?>>
	<div class="sticky-product-bar-close">
		<span class="fal fa-times"></span>
	</div>
	<div class="container-fluid">
		<div class="row row-xs-center">
			<div class="col-md-6">
				<div class="sticky-product-info">
					<div class="sticky-product-thumbnail">
						<?php echo Minimog_Woo::instance()->get_product_image( $product, $thumbnail_size ); ?>
					</div>
					<div class="sticky-product-summary">
						<h3 class="sticky-product-name post-title-1-row"><span><?php the_title(); ?></span></h3>
						<div class="sticky-product-price">
							<?php echo '' . $product->get_price_html(); ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="cart woocommerce-add-to-cart sticky-cart-form">
					<?php do_action( 'minimog/sticky_product_bar/before_add_to_cart_button' ); ?>

					<?php
					Minimog_Templates::render_button( [
						'text'        => esc_html__( 'Add to Cart', 'minimog' ),
						'link'        => [
							'url' => '#',
						],
						'extra_class' => 'sticky-product-add_to_cart_button',
					] );
					?>

					<?php do_action( 'minimog/sticky_product_bar/after_add_to_cart_button' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
