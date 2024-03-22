<?php
/**
 * Discussion Tabs
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see \Minimog\Woo\Single_Product::add_default_discussion_tabs()
 */
$product_tabs = apply_filters( 'minimog/product/discussion_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>
	<div class="woocommerce-tabs woocommerce-discussion-tabs">
		<div class="<?php echo Minimog\Woo\Single_Product::instance()->page_content_container_class(); ?>">
			<div
				class="minimog-tabs minimog-tabs--horizontal minimog-tabs--nav-style-02 minimog-tabs--title-graphic-align-center">
				<div class="minimog-tabs__header-wrap">
					<div class="minimog-tabs__header-inner">
						<div class="minimog-tabs__header" role="tablist">
							<?php $loop_count = 0; ?>
							<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
								<?php
								$loop_count++;
								$tab_title_class = "tab-title {$key}_tab";

								if ( 1 === $loop_count ) {
									$tab_title_class .= ' active';
								}
								?>
								<div class="<?php echo esc_attr( $tab_title_class ); ?>"
								     data-tab="<?php echo esc_attr( $loop_count ); ?>"
								     id="tab-title-<?php echo esc_attr( $key ); ?>"
								     role="tab"
								     aria-selected="<?php echo 1 === $loop_count ? 'true' : 'false'; ?>"
								     tabindex="<?php echo 1 === $loop_count ? '0' : '-1'; ?>"
								     aria-controls="tab-content-<?php echo esc_attr( $key ); ?>"
								>
								<span class="tab-title__text">
									<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
								</span>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div class="minimog-tabs__content">
					<?php $loop_count = 0; ?>
					<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
						<?php
						$loop_count++;
						$tab_content_class = "tab-content tab-content-{$key}";

						if ( 1 === $loop_count ) {
							$tab_content_class .= ' active';
						}
						?>
						<div class="<?php echo esc_attr( $tab_content_class ); ?>"
						     data-tab="<?php echo esc_attr( $loop_count ); ?>"
						     id="tab-content-<?php echo esc_attr( $key ); ?>"
						     role="tabpanel"
						     tabindex="0"
						     aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>"
							<?php echo 1 === $loop_count ? '' : ' hidden'; ?>
						     aria-expanded="<?php echo 1 === $loop_count ? 'true' : 'false'; ?>"
						>
							<div class="tab-content-wrapper">
								<?php
								if ( isset( $product_tab['callback'] ) ) {
									call_user_func( $product_tab['callback'], $key, $product_tab );
								}
								?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php do_action( 'minimog/product/discussion_tabs/after' ); ?>
		</div>
	</div>
<?php endif; ?>
