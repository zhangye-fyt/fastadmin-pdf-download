<?php
/**
 * Template part for display lost password form on modal.
 *
 * @link      https://codex.wordpress.org/Template_Hierarchy
 *
 * @package   Minimog
 * @since     1.0.0
 * @version   1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="minimog-modal modal-lost-password" id="modal-user-lost-password"
     data-template="template-parts/modal/modal-content-lost-password" aria-hidden="true" role="dialog" hidden>
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<div class="modal-content-header">
					<h3 class="modal-title"><?php esc_html_e( 'Lost your password?', 'minimog' ); ?></h3>
					<p class="modal-description">
						<?php esc_html_e( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'minimog' ); ?>
						<?php printf( esc_html__( 'Remember now? %1$sBack to login%2$s', 'minimog' ), '<a href="#" class="open-modal-login link-transition-01">', '</a>' ); ?>
					</p>
				</div>

				<div class="modal-content-body">
					<form id="minimog-lost-password-form" class="minimog-lost-password-form" method="post">

						<?php do_action( 'minimog/modal_user_lost_password/before_form_fields' ); ?>

						<div class="form-group">
							<label for="lost_password_user_login"
							       class="form-label"><?php esc_html_e( 'Username or email', 'minimog' ); ?></label>
							<input type="text" id="lost_password_user_login" class="form-control form-input"
							       name="user_login"
							       placeholder="<?php esc_attr_e( 'Your username or email', 'minimog' ); ?>" required/>
						</div>

						<?php do_action( 'minimog/modal_user_lost_password/after_form_fields' ); ?>

						<div class="form-response-messages"></div>

						<div class="form-group form-submit-wrap">
							<?php wp_nonce_field( 'user_reset_password', 'user_reset_password_nonce' ); ?>
							<input type="hidden" name="action" value="minimog_user_reset_password">
							<button type="submit"
							        class="button form-submit"><span><?php esc_html_e( 'Reset password', 'minimog' ); ?></span></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
