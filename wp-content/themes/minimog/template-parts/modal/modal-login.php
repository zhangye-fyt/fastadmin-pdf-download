<?php
/**
 * Template part for display login form on modal.
 *
 * @link      https://codex.wordpress.org/Template_Hierarchy
 *
 * @package   Minimog
 * @since     1.0.0
 * @version   1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="minimog-modal modal-user-login" id="modal-user-login" aria-hidden="true" role="dialog" hidden>
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">

				<div class="modal-content-header">
					<h3 class="modal-title"><?php esc_html_e( 'Sign In', 'minimog' ); ?></h3>
					<p class="modal-description">
						<?php printf( esc_html__( 'Don\'t have an account yet? %1$s Sign up %2$s for free', 'minimog' ), '<a href="#" class="open-modal-register link-transition-01">', '</a>' ); ?>
					</p>
				</div>

				<div class="modal-content-body">

					<?php do_action( 'minimog/modal_user_login/before_form' ); ?>

					<form id="minimog-login-form" class="minimog-login-form" method="post">

						<?php do_action( 'minimog/modal_user_login/before_form_fields' ); ?>

						<div class="form-group">
							<label for="ip_user_login"
							       class="form-label"><?php esc_html_e( 'Username or email', 'minimog' ); ?></label>
							<input type="text" id="ip_user_login" class="form-control form-input" name="user_login"
							       placeholder="<?php esc_attr_e( 'Your username or email', 'minimog' ); ?>">
						</div>

						<div class="form-group">
							<label for="ip_password"
							       class="form-label"><?php esc_html_e( 'Password', 'minimog' ); ?></label>
							<div class="form-input-group form-input-password">
								<input type="password" id="ip_password" class="form-control form-input" name="password"
								       placeholder="<?php esc_attr_e( 'Password', 'minimog' ); ?>" autocomplete="off">
								<button type="button" class="btn-pw-toggle" data-toggle="0"
								        aria-label="<?php esc_attr_e( 'Show password', 'minimog' ); ?>">
								</button>
							</div>
						</div>

						<div class="form-group row-flex row-middle">
							<div class="col-grow">
								<label
									class="form-label form-label-checkbox" for="ip_rememberme">
									<input class="form-checkbox" name="rememberme"
									       type="checkbox" id="ip_rememberme" value="forever"/>
									<span><?php esc_html_e( 'Stay signed in', 'minimog' ); ?></span>
								</label>
							</div>
							<div class="col-shrink">
								<div class="forgot-password">
									<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"
									   class="open-modal-lost-password forgot-password-link link-transition-01"><?php esc_html_e( 'Forgot your password?', 'minimog' ); ?></a>
								</div>
							</div>
						</div>

						<?php do_action( 'minimog/modal_user_login/after_form_fields' ); ?>

						<div class="form-response-messages"></div>

						<?php do_action( 'minimog/modal_user_login/before_form_submit' ); ?>

						<div class="form-group form-submit-wrap">
							<?php wp_nonce_field( 'user_login', 'user_login_nonce' ); ?>
							<input type="hidden" name="action" value="minimog_user_login">
							<button type="submit"
							        class="button form-submit"><span><?php esc_html_e( 'Log In', 'minimog' ); ?></span></button>
						</div>

						<?php do_action( 'minimog/modal_user_login/after_form_submit' ); ?>
					</form>

					<?php do_action( 'minimog/modal_user_login/after_form' ); ?>

				</div>
			</div>
		</div>
	</div>
</div>
