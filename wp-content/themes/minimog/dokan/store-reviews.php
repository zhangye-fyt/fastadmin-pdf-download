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

$store_user      = get_userdata( get_query_var( 'author' ) );
$store_info      = dokan_get_store_info( $store_user->ID );
$map_location    = isset( $store_info['location'] ) ? esc_attr( $store_info['location'] ) : '';
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
							<?php
							$dokan_template_reviews = dokan_pro()->review;
							$id                     = $store_user->ID;
							$post_type              = 'product';
							$limit                  = 20;
							$status                 = '1';
							$comments               = $dokan_template_reviews->comment_query( $id, $post_type, $limit, $status );
							?>

							<div id="reviews">
								<div id="comments">

									<?php do_action( 'dokan_review_tab_before_comments' ); ?>

									<h2 class="headline"><?php esc_html_e( 'Vendor Review', 'minimog' ); ?></h2>

									<ol class="commentlist">
										<?php echo $dokan_template_reviews->render_store_tab_comment_list( $comments, $store_user->ID ); ?>
									</ol>

								</div>
							</div>

							<?php
							if ( dokan_pro()->module->is_active( 'store_reviews' ) ) {
								echo $dokan_template_reviews->review_pagination( $store_user->ID, $post_type, $limit, $status );
							} else {
								$pagenum = isset( $_REQUEST['pagenum'] ) ? absint( $_REQUEST['pagenum'] ) : 1; // phpcs:ignore
								echo $dokan_template_reviews->review_pagination_with_query( $store_user->ID, $post_type, $limit, $status, $pagenum );
							}
							?>
						</div><!-- #content .site-content -->
					</div><!-- .dokan-single-store -->
				</div><!-- .dokan-store-wrap -->
			</div>

			<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

		</div>
	</div>
</div>

<?php get_footer( 'shop' ); ?>
