<?php
/**
 * Quick view popup template.
 *
 * @package   Minimog
 * @since     1.0.0
 * @version   1.0.0
 */
defined( 'ABSPATH' ) || exit;

global $post, $product;

$product_class = 'product-quick-view-content product product-container entry-product';

$quick_view_settings = [
	'imageCover' => true,
	'spacing'    => 0,
];
?>
	<div class="minimog-modal modal-quick-view-popup"
	     id="modal-quick-view-product-<?php echo esc_attr( $product->get_id() ); ?>" aria-hidden="true" role="dialog" hidden>
		<div class="modal-overlay"></div>
		<div class="modal-content">
			<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
			<div class="modal-content-wrap">
				<div class="modal-content-inner woocommerce single-product">
					<div <?php wc_product_class( $product_class, $product ); ?>
						data-quick-view="<?php echo esc_attr( wp_json_encode( $quick_view_settings ) ); ?>">
						<div class="woo-single-info">
							<div class="quick-view-col-images">
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
							</div>
							<div class="quick-view-col-summary">
								<div class="inner-content scroll-y">
									<div class="inner">
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
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
