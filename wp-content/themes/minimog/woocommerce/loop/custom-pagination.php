<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.1
 */

defined( 'ABSPATH' ) || exit;

$pagination_type = Minimog::setting( 'shop_archive_pagination_type' );

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) );
$format  = isset( $format ) ? $format : '';

/**
 * Override base to avoid page/xx from url on ajax.
 */
$shop_base_url = Minimog_Woo::instance()->get_shop_active_filters_url();
$base          = esc_url_raw( add_query_arg( 'product-page', '%#%', $shop_base_url ) );
?>
<nav class="woocommerce-pagination" data-type="<?php echo esc_attr( $pagination_type ); ?>">
	<?php if ( in_array( $pagination_type, [ 'load-more', 'infinite' ], true ) ) : ?>
		<?php if ( $total > $current ): ?>
			<?php
			$load_more_url = add_query_arg( 'product-page', $current + 1, $shop_base_url );
			?>
			<button data-url="<?php echo esc_url( $load_more_url ); ?>" class="shop-load-more-button">
				<span class="button-text"><?php esc_html_e( 'Load more', 'minimog' ); ?></span>
			</button>
		<?php endif; ?>
	<?php else: ?>
		<?php if ( $total > 1 ) : ?>
			<?php
			echo paginate_links(
				apply_filters(
					'woocommerce_pagination_args',
					array( // WPCS: XSS ok.
						'base'      => $base,
						'format'    => $format,
						'add_args'  => false,
						'current'   => max( 1, $current ),
						'total'     => $total,
						'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
						'next_text' => is_rtl() ? '&larr;' : '&rarr;',
						'type'      => 'list',
						'end_size'  => 3,
						'mid_size'  => 3,
					)
				)
			);
			?>
		<?php endif; ?>
	<?php endif; ?>
</nav>
