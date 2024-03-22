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
						$size = Minimog_Image::elementor_parse_image_size( $settings, '740x480' );
						Minimog_Image::the_post_thumbnail( array( 'size' => $size ) );
						?>
					</a>
				</div>
			</div>
		<?php } ?>

		<?php if ( 'yes' === $settings['show_caption'] ) : ?>
			<div class="post-caption">
				<?php
				Minimog_Post::instance()->the_categories( array(
					'classes'   => 'post-categories',
					'separator' => ' ',
				) );

				minimog_load_template( 'blog/loop/title-collapsed' );
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
