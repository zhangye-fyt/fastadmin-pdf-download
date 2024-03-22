<?php
/**
 * The home latest posts template.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Minimog
 * @since   1.0
 */
get_header();

do_action( 'minimog/blog_archive/before_content' );

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
					<?php minimog_load_template( 'blog/archive-blog' ); ?>
				</div>

				<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer();
