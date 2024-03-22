<?php
/**
 * Template part for displaying blog content in home.php, archive.php.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Minimog
 * @since   1.0
 */
$style   = Minimog::setting( 'blog_archive_style', 'grid' );
$classes = [
	'minimog-main-post',
	'minimog-grid-wrapper',
	'minimog-blog',
	'minimog-animation-zoom-in',
	'minimog-blog-' . $style,
];

$is_grid = false;

if ( in_array( $style, [ 'grid' ], true ) ) {
	$is_grid    = true;
	$is_masonry = Minimog::setting( 'blog_archive_masonry' );
	$grid_class = 'minimog-grid lazy-grid';
	$lg_columns = intval( Minimog::setting( 'blog_archive_lg_columns' ) );
	$md_columns = Minimog::setting( 'blog_archive_md_columns' );
	$sm_columns = Minimog::setting( 'blog_archive_sm_columns' );

	$lg_gutter = Minimog::setting( 'blog_archive_lg_gutter', 30 );
	$md_gutter = Minimog::setting( 'blog_archive_md_gutter' );
	$sm_gutter = Minimog::setting( 'blog_archive_sm_gutter' );

	if ( 'none' !== Minimog_Global::instance()->get_sidebar_status() ) {
		$lg_columns--;
	}

	$grid_options = [
		'type'               => ! empty( $is_masonry ) ? 'masonry' : 'grid',
		'columns'            => $lg_columns,
		'columnsTabletExtra' => $md_columns,
		'columnsMobileExtra' => $sm_columns,
		'gutter'             => $lg_gutter,
	];

	if ( '' !== $md_gutter ) {
		$grid_options['gutterTabletExtra'] = $md_gutter;
	}

	if ( '' !== $sm_gutter ) {
		$grid_options['gutterMobileExtra'] = $sm_gutter;
	}

	if ( $is_masonry ) {
		$classes[] = 'minimog-grid-masonry';
	}
} else {
	$grid_class = 'minimog-grid';
}

$template_part = $style;

if ( $is_grid ) {
	$caption_style     = Minimog::setting( 'blog_archive_grid_caption_style' );
	$caption_alignment = Minimog::setting( 'blog_archive_grid_caption_alignment' );
	$classes[]         = 'minimog-blog-caption-style-' . $caption_style;
	$classes[]         = 'minimog-blog-caption-alignment-' . $caption_alignment;

	$template_part = 'grid-' . $caption_style;
}

$query_vars = array_merge( [
	'style'         => $style,
	'caption_style' => $caption_style,
	'template_part' => $template_part,
], Minimog_Blog_Query::instance()->get_query_vars() );

if ( have_posts() ) : ?>
	<div id="minimog-main-post" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		<?php if ( $is_grid ) : ?>
			data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
			<?php echo Minimog_Helper::grid_args_to_html_attr( $grid_options ); ?>
		<?php endif; ?>
	>
		<input type="hidden"
		       id="minimog-main-post-query"
		       data-query="<?php echo esc_attr( wp_json_encode( $query_vars ) ); ?>"
		       name="query"/>
		<div class="<?php echo esc_attr( $grid_class ); ?>">
			<?php if ( $is_grid ) : ?>
				<div class="grid-sizer"></div>
			<?php endif; ?>

			<?php while ( have_posts() ) : the_post();
				$classes = array( 'grid-item', 'post-item' );
				?>
				<div <?php post_class( implode( ' ', $classes ) ); ?>>
					<?php minimog_load_template( 'blog/content-blog', $template_part ); ?>
				</div>
			<?php endwhile; ?>
		</div>

		<?php minimog_load_template( 'blog/loop/pagination' ); ?>
	</div>

<?php else : minimog_load_template( 'content', 'none' ); ?>
<?php endif; ?>
