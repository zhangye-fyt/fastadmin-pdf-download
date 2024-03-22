<?php

namespace Minimog\Dokan;

defined( 'ABSPATH' ) || exit;

class Vendor_Policies {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'dokan_settings_form_bottom', [ $this, 'render_store_policies_form' ], 10, 2 );
		add_action( 'dokan_store_profile_saved', [ $this, 'save_store_policies_data' ] );

		add_action( 'dokan_rewrite_rules_loaded', [ $this, 'load_policies_rewrite_rules' ] );
		add_action( 'dokan_query_var_filter', [ $this, 'load_policies_query_var' ], 10, 2 );
		add_filter( 'dokan_store_tabs', [ $this, 'store_custom_tabs' ], 100, 2 );
		add_filter( 'template_include', [ $this, 'load_vendor_policies_template' ], 100 );
	}

	public function save_store_policies_data( $vendor_id ) {
		$post_data = $_POST;

		$data = [
			'enable_policy'         => isset( $post_data['dokan_store_policy_enable'] ) && 'on' === $post_data['dokan_store_policy_enable'] ? 'on' : 'off',
			'store_shipping_policy' => isset( $post_data['dokan_store_shipping_policy'] ) ? wp_kses_post( $post_data['dokan_store_shipping_policy'] ) : '',
			'store_refund_policy'   => isset( $post_data['dokan_store_refund_policy'] ) ? wp_kses_post( $post_data['dokan_store_refund_policy'] ) : '',
			'store_return_policy'   => isset( $post_data['dokan_store_return_policy'] ) ? wp_kses_post( $post_data['dokan_store_return_policy'] ) : '',
		];

		$store_info         = dokan_get_store_info( $vendor_id );
		$updated_store_info = wp_parse_args( $data, $store_info );

		update_user_meta( $vendor_id, 'dokan_profile_settings', $updated_store_info );
	}

	public function render_store_policies_form( $vendor_id, $store_info ) {
		$enable_policy         = isset( $store_info['enable_policy'] ) ? $store_info['enable_policy'] : '';
		$store_shipping_policy = isset( $store_info['store_shipping_policy'] ) ? $store_info['store_shipping_policy'] : '';
		$store_refund_policy   = isset( $store_info['store_refund_policy'] ) ? $store_info['store_refund_policy'] : '';
		$store_return_policy   = isset( $store_info['store_return_policy'] ) ? $store_info['store_return_policy'] : '';

		$editor_settings = [
			'editor_height' => 100,
			'media_buttons' => false,
			'quicktags'     => false,
		];
		?>
		<fieldset id="dokan-seller-policies-settings">
			<div class="dokan-form-group">
				<label
					class="dokan-w3 dokan-control-label"><?php esc_html_e( 'Policies', 'minimog' ); ?></label>
				<div class="dokan-w5 dokan-text-left dokan_tock_check">
					<div class="checkbox">
						<label>
							<input type="checkbox" id="dokan_store_tnc_enable"
							       value="on" <?php echo $enable_policy == 'on' ? 'checked' : ''; ?>
							       name="dokan_store_policy_enable"> <?php esc_html_e( 'Show policies in store page', 'minimog' ); ?>
						</label>
					</div>
				</div>
			</div>
			<div class="dokan-form-group" id="dokan_shipping_policy_text">
				<label class="dokan-w3 dokan-control-label"
				       for="dokan_store_shipping_policy"><?php esc_html_e( 'Shipping Policy', 'minimog' ); ?></label>
				<div class="dokan-w8 dokan-text-left">
					<?php wp_editor( $store_shipping_policy, 'dokan_store_shipping_policy', $editor_settings ); ?>
				</div>
			</div>
			<div class="dokan-form-group" id="dokan_refund_policy_text">
				<label class="dokan-w3 dokan-control-label"
				       for="dokan_store_refund_policy"><?php esc_html_e( 'Refund Policy', 'minimog' ); ?></label>
				<div class="dokan-w8 dokan-text-left">
					<?php wp_editor( $store_refund_policy, 'dokan_store_refund_policy', $editor_settings ); ?>
				</div>
			</div>
			<div class="dokan-form-group" id="dokan_return_policy_text">
				<label class="dokan-w3 dokan-control-label"
				       for="dokan_store_return_policy"><?php esc_html_e( 'Return Policy', 'minimog' ); ?></label>
				<div class="dokan-w8 dokan-text-left">
					<?php wp_editor( $store_return_policy, 'dokan_store_return_policy', $editor_settings ); ?>
				</div>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * Load policies rewrite rules
	 *
	 * @param string $store_url
	 *
	 * @return void
	 */
	public function load_policies_rewrite_rules( $store_url ) {
		add_rewrite_rule( $store_url . '/([^/]+)/policies?$', 'index.php?' . $store_url . '=$matches[1]&policies=true', 'top' );
	}

	/**
	 * Load policies query var
	 *
	 * @param array $query_vars
	 *
	 * @return array
	 */
	public function load_policies_query_var( $query_vars ) {
		$query_vars[] = 'policies';

		return $query_vars;
	}

	public function store_custom_tabs( $tabs, $store_id ) {
		/**
		 * @var \WeDevs\Dokan\Vendor\Vendor $vendor
		 */
		$vendor     = dokan()->vendor;
		$store_user = $vendor->get( $store_id );
		$store_info = $store_user->get_shop_info();

		$store_policy_enable = ! empty( $store_info['enable_policy'] ) && 'on' === $store_info['enable_policy'] ? true : false;
		$store_has_content   = ( ! empty( $store_info['store_shipping_policy'] ) || ! empty( $store_info['store_refund_policy'] ) || ! empty( $store_info['store_re_policy'] ) ) ? true : false;

		if ( ! isset( $tabs['vendor_policies'] ) && $store_policy_enable && $store_has_content ) {
			$tabs['vendor_policies'] = [
				'title' => apply_filters( 'minimog\dokan\store_tabs\vendor_policies\title', __( 'Policies', 'minimog' ) ),
				'url'   => dokan_get_store_url( $store_id ) . 'policies',
			];
		}

		return $tabs;
	}

	public function load_vendor_policies_template( $template ) {
		if ( ! get_query_var( 'policies' ) ) {
			return $template;
		}

		return dokan_locate_template( 'custom/vendor-policies.php' );
	}
}

Vendor_Policies::instance()->initialize();
