<?php
/**
 * The template for displaying content blog list item.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Minimog
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;

?>

<div class="post-wrapper minimog-box">
	<?php if ( has_post_thumbnail() || has_category() ) { ?>
		<div class="post-thumbnail-wrapper">
			<div class="post-feature post-thumbnail minimog-image">
				<a href="<?php the_permalink(); ?>">
					<?php Minimog_Image::the_post_thumbnail( array( 'size' => '840x544' ) ); ?>
				</a>
			</div>
		</div>
	<?php } ?>

	<div class="post-caption">
		<div class="entry-post-meta post-meta">
			<div class="entry-post-meta__inner inner">
				<?php
				Minimog_Post::instance()->meta_date_template();
				?>
			</div>
		</div>
		
		<?php 
		minimog_load_template( 'blog/loop/title' ); 
		minimog_load_template( 'blog/loop/excerpt' );
		?>

		<div class="post-footer">
			<?php minimog_load_template( 'blog/loop/read-more' ); ?>
		</div>
	</div>

</div>
