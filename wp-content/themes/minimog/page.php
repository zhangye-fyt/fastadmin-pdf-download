<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Minimog
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Minimog_Sidebar::instance()->render( 'left' ); ?>

				<div id="page-main-content" class="page-main-content">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php minimog_load_template( 'content-rich-snippet' ); ?>

						<?php
						if ( ! minimog_has_elementor_template( 'single' ) ) {
							minimog_load_template( 'content-single-page' );
						}
						?>

						<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
						?>
					<?php endwhile; ?>
				</div>

				<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer();
