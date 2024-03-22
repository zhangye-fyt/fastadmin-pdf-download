<?php
/**
 * The template for displaying loop read more.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Minimog
 * @since   1.0
 */

defined( 'ABSPATH' ) || exit;

$args = [
	'link'          => [
		'url' => get_the_permalink(),
	],
	'style'         => 'bottom-line',
	'text'          => esc_html__( 'Read more', 'minimog' ),
	'wrapper_class' => 'post-read-more',
];

Minimog_Templates::render_button( $args );
