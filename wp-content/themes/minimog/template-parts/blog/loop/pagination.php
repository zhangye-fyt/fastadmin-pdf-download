<?php
/**
 * Pagination - Show pagination for blog archive
 */

defined( 'ABSPATH' ) || exit;

$pagination_type = Minimog::setting( 'blog_archive_pagination_type' );

global $wp_query;

$total = $wp_query->max_num_pages;

if ( get_query_var( 'paged' ) ) {
	$paged = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
	$paged = get_query_var( 'page' );
} else {
	$paged = 1;
}

$current = max( 1, $paged );
$base    = esc_url_raw( add_query_arg( 'blog-page', '%#%', false ) );
$format  = '?blog-page=%#%';
?>
<nav class="minimog-grid-pagination" data-type="<?php echo esc_attr( $pagination_type ); ?>">
	<?php if ( in_array( $pagination_type, [ 'load-more', 'infinite' ], true ) ) : ?>
		<?php if ( $total > $current ): ?>
			<?php
			$load_more_url = Minimog_Post::instance()->get_blog_active_filters_url();
			$load_more_url = add_query_arg( 'blog-page', $current + 1, $load_more_url );
			?>
			<button data-url="<?php echo esc_url( $load_more_url ); ?>" class="archive-load-more-button">
				<span class="button-text"><?php esc_html_e( 'Load more', 'minimog' ); ?></span>
			</button>
		<?php endif; ?>
	<?php else: ?>
		<?php if ( $total > 1 ) : ?>
			<?php
			echo paginate_links( [ // WPCS: XSS ok.
				'base'      => $base,
				'format'    => $format,
				'add_args'  => false,
				'current'   => max( 1, $current ),
				'total'     => $total,
				'prev_text' => Minimog_Templates::get_pagination_prev_text(),
				'next_text' => Minimog_Templates::get_pagination_next_text(),
				'type'      => 'list',
				'end_size'  => 3,
				'mid_size'  => 3,
			] );
			?>
		<?php endif; ?>
	<?php endif; ?>
</nav>
