<?php
/**
 * Checkout login form
 *
 * This template can be overridden by copying it to minimog-child/woocommerce/checkout/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}

$popup_login_on      = Minimog::setting( 'login_popup_enable' );
$toggle_link_classes = 'link-transition-02';

if ( $popup_login_on ) {
	$toggle_link_classes .= ' open-modal-login';
} else {
	$toggle_link_classes .= ' showlogin';
}
?>
<div class="checkout-login-link">
	<?php echo apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'minimog' ) ) . ' <a href="#" class="' . esc_attr( $toggle_link_classes ) . '">' . esc_html__( 'Click here to login', 'minimog' ) . '</a>' ?>
</div>
<?php if ( ! $popup_login_on ) : ?>
	<?php
	woocommerce_login_form(
		array(
			'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'minimog' ),
			'redirect' => wc_get_checkout_url(),
			'hidden'   => true,
		)
	);
	?>
<?php endif; ?>
