<?php


?>
<?php if ( $sellers['users'] ) : ?>

	<?php dokan_get_template( 'custom/loop/before-vendor-loop.php' ); ?>

	<?php foreach ( $sellers['users'] as $seller ) : ?>
		<?php
		dokan_get_template_part( 'custom/content', 'vendor', [
			'seller' => $seller,
		] );
		?>
	<?php endforeach; ?>

	<?php dokan_get_template( 'custom/loop/after-vendor-loop.php' ); ?>

	<?php
	$user_count   = $sellers['count'];
	$num_of_pages = ceil( $user_count / $limit );

	if ( $num_of_pages > 1 ) {
		echo '<div class="pagination-container clearfix">';

		$pagination_args = array(
			'current'   => $paged,
			'total'     => $num_of_pages,
			'base'      => $pagination_base,
			'type'      => 'array',
			'prev_text' => __( '&larr; Previous', 'minimog' ),
			'next_text' => __( 'Next &rarr;', 'minimog' ),
		);

		if ( ! empty( $search_query ) ) {
			$pagination_args['add_args'] = array(
				'dokan_seller_search' => $search_query,
			);
		}

		$page_links = paginate_links( $pagination_args );

		if ( $page_links ) {
			$pagination_links = '<div class="pagination-wrap">';
			$pagination_links .= '<ul class="pagination"><li>';
			$pagination_links .= join( "</li>\n\t<li>", $page_links );
			$pagination_links .= "</li>\n</ul>\n";
			$pagination_links .= '</div>';

			echo $pagination_links; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		echo '</div>';
	}
	?>

<?php else: ?>
	<p class="dokan-error"><?php esc_html_e( 'No vendor found!', 'minimog' ); ?></p>
<?php endif; ?>
