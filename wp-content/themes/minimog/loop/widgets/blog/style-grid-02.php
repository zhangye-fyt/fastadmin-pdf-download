<?php
$classes = array( 'grid-item', 'post-item' );
?>
<div <?php post_class( implode( ' ', $classes ) ); ?>>
	<div class="post-wrapper minimog-box">

		<?php if ( has_post_thumbnail() ) { ?>
			<div class="post-thumbnail-wrapper">
				<div class="post-feature post-thumbnail minimog-image">
					<a href="<?php the_permalink(); ?>">
						<?php
						$size = Minimog_Image::elementor_parse_image_size( $settings, '840x544' );
						Minimog_Image::the_post_thumbnail( array( 'size' => $size ) );
						?>
					</a>
				</div>
			</div>
		<?php } ?>

		<?php if ( 'yes' === $settings['show_caption'] ) : ?>
			<div class="post-caption">
				<div class="entry-post-meta post-meta">
					<div class="entry-post-meta__inner inner">
						<?php Minimog_Post::instance()->meta_date_template(); ?>
					</div>
				</div>

				<?php 
					minimog_load_template( 'blog/loop/title-collapsed' ); 
					if ( 'yes' === $settings['show_excerpt'] ) {
						minimog_load_template( 'blog/loop/excerpt' );
					}
				?>

				<?php if ( 'yes' === $settings['show_button'] ) : ?>
					<div class="post-footer">
						<?php minimog_load_template( 'blog/loop/read-more' ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
