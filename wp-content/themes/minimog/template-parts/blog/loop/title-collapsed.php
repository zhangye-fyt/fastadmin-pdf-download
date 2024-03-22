<?php
/**
 * The template for displaying loop post title.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Minimog
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<h3 class="post-title post-title-2-rows">
	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h3>
