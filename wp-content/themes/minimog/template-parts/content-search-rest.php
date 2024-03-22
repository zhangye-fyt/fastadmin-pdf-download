<?php
/**
 * Template part for displaying search result loop item for all other content post types.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Minimog
 * @since   1.0.0
 */

$classes = array( 'grid-item', 'post-item' );
?>
<div <?php post_class( implode( ' ', $classes ) ); ?>>
	<div class="post-wrapper minimog-box">
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="post-thumbnail-wrapper">
				<div class="post-feature post-thumbnail minimog-image">
					<a href="<?php the_permalink(); ?>">
						<?php Minimog_Image::the_post_thumbnail( array( 'size' => '740x480' ) ); ?>
					</a>
				</div>
			</div>
		<?php } ?>
		<div class="post-caption">
			<?php
			Minimog_Post::instance()->the_categories( array(
				'classes'   => 'post-categories',
				'separator' => ' ',
			) );

			$title = get_the_title();
			if ( ! empty( $title ) ) {
				minimog_load_template( 'blog/loop/title-collapsed' );
			} else {
				minimog_load_template( 'blog/loop/read-more' );
			}
			?>
		</div>
	</div>
</div>
