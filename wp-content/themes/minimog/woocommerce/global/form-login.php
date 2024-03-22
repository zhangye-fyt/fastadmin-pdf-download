<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     7.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( is_user_logged_in() ) {
	return;
}

?>
<form class="woocommerce-form woocommerce-form-login login" method="post" <?php echo ( $hidden ) ? 'style="display:none;"' : ''; ?>>

	<?php do_action( 'woocommerce_login_form_start' ); ?>

	<?php echo ( $message ) ? wpautop( wptexturize( $message ) ) : ''; // @codingStandardsIgnoreLine ?>

	<p class="form-row form-row-first">
		<label for="username"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="input-text" name="username" id="username" autocomplete="username" />
	</p>
	<p class="form-row form-row-last">
		<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input class="input-text" type="password" name="password" id="password" autocomplete="current-password" />
	</p>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form' ); ?>

	<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide row-flex row-middle">
		<div class="col-grow">
			<label
				class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
				<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme"
				       type="checkbox" id="rememberme" value="forever"/>
				<span><?php esc_html_e( 'Stay signed in', 'minimog' ); ?></span>
			</label>
		</div>
		<div class="col-shrink woocommerce-LostPassword lost_password">
			<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"
			   class="link-transition-02"><?php esc_html_e( 'Forgot your password?', 'minimog' ); ?></a>
		</div>
	</div>

	<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
	<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
	<button type="submit" class="woocommerce-button button woocommerce-form-login__submit <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'minimog' ); ?>"><?php esc_html_e( 'Login', 'minimog' ); ?></button>

	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>
