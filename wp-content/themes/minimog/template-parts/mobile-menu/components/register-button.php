<?php
/**
 * Register button on mobile menu
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$button_classes = '';

$button_text = apply_filters( 'minimog/user_register/text', __( 'Register', 'minimog' ) );
$button_url  = apply_filters( 'minimog/user_register/url', wp_login_url() );

/**
 * Force remove link.
 */
if ( Minimog::setting( 'login_popup_enable' ) ) {
	$button_url     = '#';
	$button_classes .= ' open-modal-register';
}

if ( empty( $button_text ) || empty( $button_url ) ) {
	return;
}

Minimog_Templates::render_button( [
	'link'        => [
		'url' => $button_url,
	],
	'text'        => $button_text,
	'full_wide'   => true,
	'extra_class' => $button_classes,
	'style'       => 'border',
	'size'        => 'sm',
] );
