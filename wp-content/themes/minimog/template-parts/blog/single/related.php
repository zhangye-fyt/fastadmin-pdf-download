<?php
$number_post = Minimog::setting( 'single_post_related_number' );
$results     = Minimog_Post::instance()->get_related_posts( array(
	'post_id'      => get_the_ID(),
	'number_posts' => $number_post,
) );

$classes = [
	'related-posts',
	'minimog-blog',
	'minimog-blog-grid',
	'minimog-animation-zoom-in',
	'minimog-blog-caption-style-01',
];

$slider_args = [
	'data-items-desktop'  => '3',
	'data-gutter-desktop' => '30',
	'data-items-tablet'   => '2',
	'data-items-mobile'   => '1',
	'data-nav'            => '1',
	'data-auto-height'    => '1',
];

if ( $results !== false && $results->have_posts() ) : ?>
	<div
		class="<?php echo esc_attr( implode( ' ', $classes ) ) ?>">
		<h3 class="related-title">
			<?php esc_html_e( 'Related Articles', 'minimog' ); ?>
		</h3>
		<div class="tm-swiper tm-slider" <?php echo Minimog_Helper::slider_args_to_html_attr( $slider_args ); ?>>
			<div class="swiper-inner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php while ( $results->have_posts() ) : $results->the_post(); ?>

							<div class="swiper-slide">
								<div <?php post_class( 'related-post-item post-item' ); ?>>
									<div class="post-wrapper minimog-box">

										<?php if ( has_post_thumbnail() ) { ?>
											<div class="post-thumbnail-wrapper">
												<div class="post-feature post-thumbnail minimog-image">
													<a href="<?php the_permalink(); ?>">
														<?php Minimog_Image::the_post_thumbnail( array( 'size' => '540x350' ) ); ?>
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
											?>

											<?php minimog_load_template( 'blog/loop/title-collapsed' ); ?>
										</div>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif;
