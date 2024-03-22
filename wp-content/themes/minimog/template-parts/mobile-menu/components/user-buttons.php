<?php
/**
 * Login button on mobile menu
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.9.1
 */

defined( 'ABSPATH' ) || exit;

$is_logged_in = is_user_logged_in();
?>
<?php if ( ! $is_logged_in ) : ?>
	<div class="mobile-menu-my-account">
		<span class="button-icon"><?php echo Minimog_SVG_Manager::instance()->get( 'user' ); ?></span>
		<span class="button-text"><?php esc_html_e( 'My Account', 'minimog' ); ?></span>
	</div>
	<?php
	minimog_load_template( 'mobile-menu/components/login-button' );

	minimog_load_template( 'mobile-menu/components/register-button' );
	?>
<?php else: ?>
	<?php
	$current_user = wp_get_current_user();
	if ( $current_user instanceof WP_User ) {
		$profile_url = minimog_get_user_profile_url();
		?>
		<a class="mobile-menu-my-profile" href="<?php echo esc_url( $profile_url ); ?>">
			<div class="avatar"><?php echo get_avatar( $current_user->ID, 32 ); ?></div>
			<h6 class="display-name fn"><?php echo esc_html( $current_user->display_name ); ?></h6>
		</a>
	<?php } ?>
	<?php
	minimog_load_template( 'mobile-menu/components/logout-button' );
	?>
<?php endif;
