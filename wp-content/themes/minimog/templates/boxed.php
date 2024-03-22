<?php
/**
 * Template Name: Boxed
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Minimog
 * @since   1.0
 */

get_header();
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Minimog_Sidebar::instance()->render( 'left' ); ?>

				<div id="page-main-content" class="page-main-content">
					<?php
					while ( have_posts() ) : the_post();

						minimog_load_template( 'content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile;
					?>
				</div>

				<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php get_footer();
