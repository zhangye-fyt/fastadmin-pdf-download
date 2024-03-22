<?php
/**
 * The Template for displaying all single posts.
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
						<div id="dokan-content" class="store-page-wrap woocommerce" role="main">

							<?php do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info ); ?>

							<?php if ( have_posts() ) { ?>

								<div class="seller-items">

									<?php
									wc_get_template_part( 'custom/before-shop-loop' );

									$archive_grid_style = Minimog::setting( 'shop_archive_grid_style' );
									?>

									<?php while ( have_posts() ) : the_post(); ?>

										<?php
										/**
										 * Hook: woocommerce_shop_loop.
										 */
										do_action( 'woocommerce_shop_loop' );

										minimog_get_wc_template_part( 'content-product', $archive_grid_style, [
											'settings' => [
												'show_list_view' => true,
											],
										] );
										?>

									<?php endwhile; // end of the loop. ?>

									<?php wc_get_template_part( 'custom/after-shop-loop' ); ?>

								</div>

								<?php Minimog_Templates::paging_nav(); ?>

							<?php } else { ?>

								<p class="dokan-info"><?php esc_html_e( 'No products were found of this vendor!', 'minimog' ); ?></p>

							<?php } ?>
						</div>

					</div><!-- .dokan-single-store -->

				</div><!-- .dokan-store-wrap -->
			</div>

			<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

		</div>
	</div>
</div>

<?php get_footer( 'shop' ); ?>
