<?php
/**
 * Login button on mobile menu
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$button_classes = '';

global $wp;
$redirect = '';
if ( ! empty( $wp->query_vars ) ) {
	$redirect = add_query_arg( $wp->query_vars, home_url() );
}

$redirect    = apply_filters( 'minimog/user_logout/redirect_url', $redirect );
$button_text = apply_filters( 'minimog/user_logout/text', __( 'Log out', 'minimog' ) );
$button_url  = apply_filters( 'minimog/user_logout/url', wp_logout_url( $redirect ) );

if ( empty( $button_text ) || empty( $button_url ) ) {
	return;
}

Minimog_Templates::render_button( [
	'link'          => [
		'url' => $button_url,
	],
	'text'          => $button_text,
	'full_wide'     => true,
	'extra_class'   => $button_classes,
	'size'          => 'sm',
	'wrapper_class' => 'mobile-menu-logout-btn',
] );
