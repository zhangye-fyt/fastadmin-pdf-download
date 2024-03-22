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

/**
 * @var WP_User $store_user
 */
$vendor       = dokan()->vendor->get( get_query_var( 'author' ) );
$vendor_info  = $vendor->get_shop_info();
$map_location = $vendor->get_location();
$store_user   = get_userdata( get_query_var( 'author' ) );
$store_info   = dokan_get_store_info( $store_user->get( 'ID' ) );
$layout       = get_theme_mod( 'store_layout', 'left' );
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
							<div id="store-toc-wrapper">
								<div id="store-toc">
									<?php
									if ( ! empty( $vendor->get_store_tnc() ) ):
										?>
										<h2 class="headline"><?php esc_html_e( 'Terms And Conditions', 'minimog' ); ?></h2>
										<div>
											<?php
											echo wp_kses_post( nl2br( $vendor->get_store_tnc() ) );
											?>
										</div>
									<?php
									endif;
									?>
								</div><!-- #store-toc -->
							</div><!-- #store-toc-wrap -->
						</div>

					</div><!-- .dokan-single-store -->

				</div><!-- .dokan-store-wrap -->
			</div>

			<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

		</div>
	</div>
</div>

<?php get_footer( 'shop' ); ?>
