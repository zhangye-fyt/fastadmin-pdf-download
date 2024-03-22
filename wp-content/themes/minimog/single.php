<?php
/**
 * The template for displaying all single posts.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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

				<div class="page-main-content">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php minimog_load_template( 'content-rich-snippet' ); ?>

						<?php
						if ( ! minimog_has_elementor_template( 'single' ) ) {
							if ( is_singular( 'post' ) ):
								minimog_load_template( 'blog/content-single-post' );
							else:
								minimog_load_template( 'content', 'single' );
							endif;
						}
						?>
					<?php endwhile; ?>
				</div>

				<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer();
