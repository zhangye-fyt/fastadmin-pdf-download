<?php
/**
 * User links on top bar
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Default WP login.
$login_url = wp_login_url();

// Use Woocommerce login page.
if ( Minimog_Woo::instance()->is_activated() ) {
	$login_url = wc_get_page_permalink( 'myaccount' );
}
?>
<?php if ( ! is_user_logged_in() ) { ?>
	<a href="<?php echo esc_url( $login_url ); ?>"
	   title="<?php esc_attr_e( 'Log In / Sign Up', 'minimog' ); ?>"><?php esc_html_e( 'Log In / Sign Up', 'minimog' ); ?></a>
<?php } else { ?>
	<?php if ( Minimog_Woo::instance()->is_activated() ) : ?>
		<a href="<?php echo esc_url( $login_url ); ?>"
		   title="<?php esc_attr_e( 'My Account', 'minimog' ); ?>"><?php esc_html_e( 'My Account', 'minimog' ); ?></a>
	<?php endif; ?>
<?php } ?>
