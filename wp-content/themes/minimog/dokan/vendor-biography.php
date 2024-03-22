<?php
/**
 * The Template for displaying all reviews.
 *
 * @package       dokan
 * @package       dokan - 2014 1.0
 *
 * @theme-version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$store_user      = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info      = $store_user->get_shop_info();
$map_location    = $store_user->get_location();
$layout          = get_theme_mod( 'store_layout', 'left' );
$container_class = Minimog_Site_Layout::instance()->get_container_class( Minimog::setting( 'single_store_site_layout' ) );

get_header( 'shop' );
?>
<div id="page-content" class="page-content">
	<div class="<?php echo esc_attr( $container_class ); ?>">

		<?php dokan_get_template_part( 'store-header' ); ?>

		<div class="row">

			<?php Minimog_Sidebar::instance()->render( 'left' ); ?>

			<div class="page-main-content">

				<?php dokan_get_template_part( 'custom/store-tabs' ); ?>

				<div class="dokan-store-wrap layout-<?php echo esc_attr( $layout ); ?>">
					<div id="dokan-primary" class="dokan-single-store">
						<div id="dokan-content" class="store-review-wrap woocommerce" role="main">
							<div id="vendor-biography">
								<div id="comments">
									<?php do_action( 'dokan_vendor_biography_tab_before', $store_user, $store_info ); ?>

									<h2 class="headline screen-reader-text"><?php echo esc_html( apply_filters( 'dokan_vendor_biography_title', __( 'Vendor Biography', 'minimog' ) ) ); ?></h2>

									<?php
									if ( ! empty( $store_info['vendor_biography'] ) ) {
										printf( '%s', apply_filters( 'the_content', $store_info['vendor_biography'] ) );
									}
									?>

									<?php do_action( 'dokan_vendor_biography_tab_after', $store_user, $store_info ); ?>
								</div>
							</div>
						</div><!-- #content .site-content -->
					</div><!-- .dokan-single-store -->
				</div><!-- .dokan-store-wrap -->
			</div>

			<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

		</div>
	</div>
</div>

<?php get_footer( 'shop' ); ?>
