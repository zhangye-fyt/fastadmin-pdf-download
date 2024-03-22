<?php
/**
 * The template for displaying all single elementor_library posts.
 *
 * @package Minimog
 * @since   1.0.0
 * @version 2.0.0
 */

get_header();
?>
	<div id="page-content" class="page-content primary-menu-sub-visual">
		<div class="page-main-content">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	</div>
<?php
get_footer();
