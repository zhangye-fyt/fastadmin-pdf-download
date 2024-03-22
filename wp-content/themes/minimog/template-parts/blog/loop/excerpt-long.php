<?php
/**
 * The template for displaying loop excerpt.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Minimog
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;

$post_title = get_the_title();
?>
<div class="post-excerpt">
	<?php if ( empty( $post_title ) ) : ?>
		<a href="<?php the_permalink(); ?>">
			<?php Minimog_Templates::excerpt( array(
				'limit' => 42,
				'type'  => 'word',
			) ); ?>
		</a>
	<?php else: ?>
		<?php Minimog_Templates::excerpt( array(
			'limit' => 42,
			'type'  => 'word',
		) ); ?>
	<?php endif; ?>
</div>
