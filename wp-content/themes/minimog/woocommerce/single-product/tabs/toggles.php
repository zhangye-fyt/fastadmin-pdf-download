<?php
/**
 * Single Product toggles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package Minimog
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

$active_first_item = apply_filters( 'minimog/product_toggles/active_first_item', false );

$loop_count = 0;
if ( ! empty( $product_tabs ) ) : ?>
	<div class="single-product-accordion minimog-accordion-style-01">
		<div class="minimog-accordion" data-multi-open="0">
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<?php
				$loop_count++;

				$item_classes = 'accordion-section';

				if ( ! empty( $active_first_item ) && 1 === $loop_count ) {
					$item_classes .= ' active';
				}
				?>
				<div class="<?php echo esc_attr( $item_classes ); ?>"
				     id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
				     aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">

					<div class="accordion-header">
						<div class="accordion-title-wrapper">
							<?php printf( '<%1$s class="accordion-title">%2$s</%1$s>', 'h4', wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ) ); ?>
						</div>
						<div class="accordion-icons">
							<span class="accordion-icon opened-icon"><i class="far fa-angle-down"></i></span>
							<span class="accordion-icon closed-icon"><i class="far fa-angle-up"></i></span>
						</div>
					</div>
					<div class="accordion-content">
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
<?php endif; ?>
