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

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.

	return;
}

$images_wide = Minimog_Woo::instance()->get_single_product_images_wide();

switch ( $images_wide ) {
	case 'wide':
		$product_image_classes   = 'col-md-8';
		$product_summary_classes = 'col-md-4';
		break;
	case 'narrow':
		$product_image_classes   = 'col-md-5';
		$product_summary_classes = 'col-md-7';
		break;
	case 'extended':
		$product_image_classes   = 'col-md-7';
		$product_summary_classes = 'col-md-5';
		break;
	default:
		$product_image_classes   = 'col-md-6';
		$product_summary_classes = 'col-md-6';
		break;
}
?>
	<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'entry-product', $product ); ?>>

		<div id="woo-single-info" class="tm-sticky-parent woo-single-info" data-sticky-group="product-images-summary">
			<div class="row">
				<div class="col-woo-single-images <?php echo esc_attr( $product_image_classes ); ?>">
					<div class="tm-sticky-column" data-sticky-group="product-images-summary">
						<?php do_action( 'minimog/single_product/product_images/before' ); ?>

						<div class="woo-single-images">
							<?php do_action( 'minimog/single_product/images/before' ); ?>

							<?php
							/**
							 * woocommerce_before_single_product_summary hook.
							 *
							 * @hooked woocommerce_show_product_sale_flash - 10
							 * @hooked woocommerce_show_product_images - 20
							 */
							do_action( 'woocommerce_before_single_product_summary' );
							?>

							<?php do_action( 'minimog/single_product/images/after' ); ?>
						</div>

						<?php do_action( 'minimog/single_product/product_images/after' ); ?>
					</div>
				</div>

				<div class="col-woo-single-summary <?php echo esc_attr( $product_summary_classes ); ?>">
					<div class="tm-sticky-column" data-sticky-group="product-images-summary">
						<div class="summary entry-summary">
							<?php
							/**
							 * Hook: woocommerce_single_product_summary.
							 *
							 * @hooked woocommerce_template_single_title - 5
							 * @hooked woocommerce_template_single_rating - 10
							 * @hooked woocommerce_template_single_price - 10
							 * @hooked woocommerce_template_single_excerpt - 20
							 * @hooked woocommerce_template_single_add_to_cart - 30
							 * @hooked woocommerce_template_single_meta - 40
							 * @hooked woocommerce_template_single_sharing - 50
							 * @hooked WC_Structured_Data::generate_product_data() - 60
							 */
							do_action( 'woocommerce_single_product_summary' );
							?>
						</div>

						<?php do_action( 'minimog/single_product/product_summary/after' ); ?>
					</div>
				</div>
			</div>
		</div>

		<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
		?>

	</div>

<?php wc_get_template_part( 'content', 'single-sticky-product' );
