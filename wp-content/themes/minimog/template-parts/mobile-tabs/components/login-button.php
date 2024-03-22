<?php
/**
 * Login button on mobile tabs
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.9.1
 */

defined( 'ABSPATH' ) || exit;

$link_classes = 'mobile-tab-link login-link';

if ( is_user_logged_in() ) {
	$button_text = apply_filters( 'minimog/user_profile/text', __( 'Log out', 'minimog' ) );
	$button_url  = minimog_get_user_profile_url();
} else {
	$button_text = apply_filters( 'minimog/user_login/text', __( 'Log in', 'minimog' ) );
	$button_url  = minimog_get_login_url();

	/**
	 * Force remove link.
	 */
	if ( Minimog::setting( 'login_popup_enable' ) ) {
		$button_url   = '#';
		$link_classes .= ' open-modal-login';
	}
}

if ( empty( $button_url ) ) {
	return;
}
?>
<a href="<?php echo esc_url( $button_url ); ?>" class="<?php echo esc_attr( $link_classes ); ?>" aria-label="<?php echo esc_attr( $button_text ); ?>">
	<span class="icon">
		<?php echo Minimog_SVG_Manager::instance()->get( 'user' ); ?>
	</span>
</a>
