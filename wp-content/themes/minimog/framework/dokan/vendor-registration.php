<?php

namespace Minimog\Dokan;

defined( 'ABSPATH' ) || exit;

class Vendor_Registration {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'minimog/modal_user_register/after_form_fields', [ $this, 'add_extra_fields_for_seller' ] );

		add_filter( 'minimog/user_register/errors', [ $this, 'validate_registration' ], 10, 3 );

		add_filter( 'minimog/user_register/data', [ $this, 'set_new_vendor_names' ] );

		add_action( 'minimog/user_register/save', [ $this, 'save_vendor_info' ] );
	}

	public function add_extra_fields_for_seller() {
		?>
		<div class="form-group form-user-role form-radio-inline">
			<label for="ip_reg_role_customer" class="form-label-radio">
				<input type="radio" name="role" value="customer" checked
				       id="ip_reg_role_customer"/><?php esc_html_e( 'I am a customer', 'minimog' ); ?>
			</label>
			<label for="ip_reg_role_seller" class="form-label-radio">
				<input type="radio" name="role" value="seller"
				       id="ip_reg_role_seller"/><?php esc_html_e( 'I am a vendor', 'minimog' ); ?>
			</label>
		</div>

		<div class="form-group show-if-seller display-none">
			<label for="ip_reg_company_name" class="form-label"><?php esc_html_e( 'Shop Name', 'minimog' ); ?></label>
			<input type="text" class="form-control form-input" name="shopname" id="ip_reg_company_name"
			       required="required"
			       placeholder="<?php esc_attr_e( 'Shop Name', 'minimog' ); ?>"/>
		</div>

		<div class="form-group show-if-seller display-none">
			<label for="ip_reg_seller_url" class="form-label"><?php esc_html_e( 'Shop URL', 'minimog' ); ?></label>
			<input type="text" class="form-control form-input" name="shopurl" id="ip_reg_seller_url"
			       placeholder="<?php esc_attr_e( 'Shop URL', 'minimog' ); ?>"
			       required="required"
			/>
			<small><?php echo esc_url( home_url() . '/' . dokan_get_option( 'custom_store_url', 'dokan_general', 'store' ) ) . '/<strong id="ip_reg-url-alart"></strong></small>'; ?>
		</div>

		<div class="form-group show-if-seller display-none">
			<label for="ip_reg_shop_phone" class="form-label"><?php esc_html_e( 'Phone Number', 'minimog' ); ?></label>
			<input type="text" class="form-control form-input" name="phone" id="ip_reg_shop_phone"
			       required="required"
			       placeholder="<?php esc_attr_e( 'Phone Number', 'minimog' ); ?>"/>
		</div>
		<?php
	}

	/**
	 * @param \WP_Error $errors
	 * @param           $user_login
	 * @param           $email
	 *
	 * @return  \WP_Error
	 */
	public function validate_registration( $errors, $user_login, $email ) {
		$allowed_roles = apply_filters( 'dokan_register_user_role', array( 'customer', 'seller' ) );

		// is the role name allowed or user is trying to manipulate?
		if ( ! empty( $_POST['role'] ) && ! in_array( $_POST['role'], $allowed_roles, true ) ) {
			$errors->add( 'role-error', __( 'Cheating, eh?', 'minimog' ) );
		}

		if ( 'seller' === $_POST['role'] ) {
			$required_fields = [
				'phone'    => esc_html__( 'Please enter your phone number.', 'minimog' ),
				'shopname' => esc_html__( 'Please provide a shop name.', 'minimog' ),
			];

			foreach ( $required_fields as $field => $msg ) {
				if ( empty( trim( $_POST[ $field ] ) ) ) {
					$errors->add( "$field-error", $msg );
				}
			}

			if ( empty( $_POST['shopurl'] ) ) {
				$errors->add( "shopurl-error", esc_html__( 'Please provide a shop url.', 'minimog' ) );
			} else {
				if ( ! $this->is_store_url_available( $_POST['shopurl'] ) ) {
					$errors->add( "shopurl-error", esc_html__( 'The shop url is not available', 'minimog' ) );
				}
			}
		}

		return $errors;
	}

	/**
	 * Check the availability of shop name.
	 *
	 * @param string $url_slug
	 *
	 * @return bool
	 */
	public function is_store_url_available( $url_slug ) {
		global $user_ID;

		$url_slug = sanitize_text_field( wp_unslash( $url_slug ) );
		$check    = true;
		$user     = get_user_by( 'slug', $url_slug );

		if ( false !== $user ) {
			$check = false;
		}

		// check if a customer wants to migrate, his username should be available
		if ( is_user_logged_in() && dokan_is_user_customer( $user_ID ) ) {
			$current_user = wp_get_current_user();

			if ( $user && $current_user->user_nicename === $user->user_nicename ) {
				$check = true;
			}
		}

		if ( is_admin() && isset( $_POST['vendor_id'] ) ) {
			$vendor = get_user_by( 'id', intval( $_POST['vendor_id'] ) );

			if ( $vendor && $user && $vendor->user_nicename === $user->user_nicename ) {
				$check = true;
			}
		}

		return $check;
	}

	public function set_new_vendor_names( $data ) {
		$post_data = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification

		$allowed_roles = apply_filters( 'dokan_register_user_role', array( 'customer', 'seller' ) );
		$role          = ( isset( $post_data['role'] ) && in_array( $post_data['role'], $allowed_roles, true ) ) ? $post_data['role'] : 'customer';

		$data['role'] = $role;

		if ( 'seller' !== $role ) {
			return $data;
		}

		$data['user_nicename'] = sanitize_user( $post_data['shopurl'] );

		return $data;
	}

	/**
	 * @see \WeDevs\Dokan\Registration::save_vendor_info()
	 *
	 * Adds default dokan store settings when a new vendor registers
	 *
	 * @param int $user_id
	 *
	 * @return void
	 */
	public function save_vendor_info( $user_id ) {
		$post_data = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification

		if ( ! isset( $post_data['role'] ) || $post_data['role'] !== 'seller' ) {
			return;
		}

		$social_profiles = array();

		foreach ( dokan_get_social_profile_fields() as $key => $item ) {
			$social_profiles[ $key ] = '';
		}

		$dokan_settings = array(
			'store_name'     => sanitize_text_field( wp_unslash( $post_data['shopname'] ) ),
			'social'         => $social_profiles,
			'payment'        => array(),
			'phone'          => sanitize_text_field( wp_unslash( $post_data['phone'] ) ),
			'show_email'     => 'no',
			'location'       => '',
			'find_address'   => '',
			'dokan_category' => '',
			'banner'         => 0,
		);

		// Intially add values on profile completion progress bar
		$dokan_settings['profile_completion']['store_name']    = 10;
		$dokan_settings['profile_completion']['phone']         = 10;
		$dokan_settings['profile_completion']['next_todo']     = 'banner_val';
		$dokan_settings['profile_completion']['progress']      = 20;
		$dokan_settings['profile_completion']['progress_vals'] = array(
			'banner_val'          => 15,
			'profile_picture_val' => 15,
			'store_name_val'      => 10,
			'address_val'         => 10,
			'phone_val'           => 10,
			'map_val'             => 15,
			'payment_method_val'  => 15,
			'social_val'          => array(
				'fb'       => 4,
				'twitter'  => 2,
				'youtube'  => 2,
				'linkedin' => 2,
			),
		);

		update_user_meta( $user_id, 'dokan_profile_settings', $dokan_settings );
		update_user_meta( $user_id, 'dokan_store_name', $dokan_settings['store_name'] );

		do_action( 'dokan_new_seller_created', $user_id, $dokan_settings );
	}
}

Vendor_Registration::instance()->initialize();
