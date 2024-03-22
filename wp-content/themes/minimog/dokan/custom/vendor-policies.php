<?php
/**
 * The Template for displaying vendor policies.
 *
 * @theme-version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$store_user      = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info      = $store_user->get_shop_info();
$map_location    = $store_user->get_location();
$layout          = get_theme_mod( 'store_layout', 'left' );
$container_class = Minimog_Site_Layout::instance()->get_container_class( Minimog::setting( 'single_store_site_layout' ) );

$toggles = [
	'store_shipping_policy' => [
		'title'   => apply_filters( 'minimog/dokan/vendor_policies/shipping/title', __( 'Shipping Policy', 'minimog' ) ),
		'content' => $store_info['store_shipping_policy'],
	],
	'store_refund_policy'   => [
		'title'   => apply_filters( 'minimog/dokan/vendor_policies/shipping/title', __( 'Refund Policy', 'minimog' ) ),
		'content' => $store_info['store_refund_policy'],
	],
	'store_return_policy'   => [
		'title'   => apply_filters( 'minimog/dokan/vendor_policies/shipping/title', __( 'Cancellation / Return / Exchange Policy', 'minimog' ) ),
		'content' => $store_info['store_return_policy'],
	],
];

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
						<div id="dokan-content" class="vendor-policies" role="main">
							<div class="minimog-accordion-style-01">
								<div class="minimog-accordion" data-multi-open="1">
									<?php
									$toggle_active = Minimog::is_handheld() ? false : true;
									?>
									<?php foreach ( $toggles as $key => $toggle ): ?>
										<?php
										if ( empty( $toggle['title'] ) || empty( $toggle['title'] ) ) {
											continue;
										}

										$item_classes = 'accordion-section';

										if ( $toggle_active ) {
											$item_classes .= ' active';
										}
										?>
										<div class="<?php echo esc_attr( $item_classes ); ?>"
										     id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
										     aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
											<div class="accordion-header">
												<div class="accordion-title-wrapper">
													<?php printf( '<%1$s class="accordion-title">%2$s</%1$s>',
														'h4',
														esc_html( $toggle['title'] )
													); ?>
												</div>
												<div class="accordion-icons">
													<span class="accordion-icon opened-icon">
														<i class="far fa-angle-down"></i>
													</span>
													<span class="accordion-icon closed-icon">
														<i class="far fa-angle-up"></i>
													</span>
												</div>
											</div>
											<div class="accordion-content"
												<?php if ( $toggle_active ): ?>
													style="display:block;"
												<?php endif; ?>
											>
												<?php
												if ( ! empty( $toggle['content'] ) ) {
													printf( '%s', apply_filters( 'the_content', $toggle['content'] ) );
												}
												?>
											</div>
										</div>
									<?php endforeach; ?>
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
