<?php
/**
 * The template for displaying archive pages.
 *
 * @link     https://codex.wordpress.org/Template_Hierarchy
 *
 * @package  Minimog
 * @since    1.0
 */
get_header();

$archive_container_class = 'container';
if ( Minimog_Post::instance()->is_archive() ) {
	$archive_container_class = Minimog_Site_Layout::instance()->get_container_class( Minimog::setting( 'blog_archive_site_layout' ) );
}
?>
	<div id="page-content" class="page-content">
		<div class="<?php echo esc_attr( $archive_container_class ); ?>">
			<div class="row">

				<?php Minimog_Sidebar::instance()->render( 'left' ); ?>

				<div class="page-main-content">

					<?php
					if ( ! minimog_has_elementor_template( 'archive' ) ) {
						minimog_load_template( 'blog/archive-blog' );
					}
					?>

				</div>

				<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php get_footer();
