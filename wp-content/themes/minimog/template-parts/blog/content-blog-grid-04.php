<?php
/**
 * The template for displaying content blog grid item.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Minimog
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;

$image_size = Minimog::setting( 'blog_archive_grid_image_size', '740x480' );

if ( 'custom' === $image_size ) {
	$width  = intval( Minimog::setting( 'blog_archive_grid_image_size_width' ) );
	$height = intval( Minimog::setting( 'blog_archive_grid_image_size_width' ) );

	if ( empty( $width ) ) {
		$width = 9999;
	}

	if ( empty( $height ) ) {
		$height = 9999;
	}

	$image_size = "{$width}x{$height}";
}
?>

<div class="post-wrapper minimog-box">
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="post-thumbnail-wrapper">
			<div class="post-feature post-thumbnail minimog-image">
				<a href="<?php the_permalink(); ?>">
					<?php Minimog_Image::the_post_thumbnail( array( 'size' => $image_size ) ); ?>
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
