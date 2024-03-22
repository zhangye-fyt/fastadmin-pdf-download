<?php
/**
 * The template for displaying loop post meta.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Minimog
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;
?>

<?php Minimog_Post::instance()->the_post_meta( array( 'author', 'date', 'views' ) ); ?>
