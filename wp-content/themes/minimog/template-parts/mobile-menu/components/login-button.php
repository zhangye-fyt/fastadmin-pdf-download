<?php
/**
 * Login button on mobile menu
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.9.1
 */

defined( 'ABSPATH' ) || exit;

$button_classes = '';

$button_text = apply_filters( 'minimog/user_login/text', __( 'Log in', 'minimog' ) );
$button_url  = minimog_get_login_url();

/**
 * Force remove link.
 */
if ( Minimog::setting( 'login_popup_enable' ) ) {
	$button_url     = '#';
	$button_classes .= ' open-modal-login';
}

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
	'wrapper_class' => 'mobile-menu-login-btn',
] );
