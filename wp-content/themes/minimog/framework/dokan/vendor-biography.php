<?php

namespace Minimog\Dokan;

defined( 'ABSPATH' ) || exit;

class Vendor_Biography {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		if ( Utils::instance()->is_pro_activated() ) {
			return;
		}

		add_action( 'dokan_rewrite_rules_loaded', [ $this, 'load_biography_rewrite_rules' ] );
		add_action( 'dokan_query_var_filter', [ $this, 'load_biography_query_var' ], 10, 2 );
		add_filter( 'template_include', [ $this, 'load_vendor_biography_template' ], 100 );
		add_filter( 'dokan_store_tabs', [ $this, 'add_store_biography_tabs' ], 100, 2 );

		// Add vendor biography form.
		add_action( 'dokan_settings_form_bottom', [ $this, 'render_biography_form' ], 10, 2 );
		add_action( 'dokan_store_profile_saved', [ $this, 'save_biography_data' ] );
	}

	public function add_store_biography_tabs( $tabs, $store_id ) {
		/**
		 * @var \WeDevs\Dokan\Vendor\Vendor $vendor
		 */
		$vendor     = dokan()->vendor;
		$store_user = $vendor->get( $store_id );
		$store_info = $store_user->get_shop_info();

		if ( ! isset( $tabs['vendor_biography'] ) && ! empty( $store_info['vendor_biography'] ) ) {
			$tabs['vendor_biography'] = [
				'title' => apply_filters( 'dokan_vendor_biography_title', __( 'Vendor Biography', 'minimog' ) ),
				'url'   => dokan_get_store_url( $store_id ) . 'biography',
			];
		}

		return $tabs;
	}

	/**
	 * Load biography rewrite rules
	 *
	 * @param string $store_url
	 *
	 * @return void
	 */
	public function load_biography_rewrite_rules( $store_url ) {
		add_rewrite_rule( $store_url . '/([^/]+)/biography?$', 'index.php?' . $store_url . '=$matches[1]&biography=true', 'top' );
	}

	/**
	 * Load biography query var
	 *
	 * @param array $query_vars
	 *
	 * @return array
	 */
	public function load_biography_query_var( $query_vars ) {
		$query_vars[] = 'biography';

		return $query_vars;
	}

	/**
	 * Load vendor biography template
	 *
	 * @param string $template
	 *
	 * @return string
	 */
	public function load_vendor_biography_template( $template ) {
		if ( ! get_query_var( 'biography' ) ) {
			return $template;
		}

		return dokan_locate_template( 'vendor-biography.php' );
	}

	/**
	 * Render biography form
	 *
	 * @return void
	 */
	public function render_biography_form( $vendor_id, $store_info ) {
		$biography = ! empty( $store_info['vendor_biography'] ) ? $store_info['vendor_biography'] : '';
		?>
		<div class="dokan-form-group">
			<label class="dokan-w3 dokan-control-label"><?php esc_html_e( 'Biography', 'minimog' ); ?></label>
			<div class="dokan-w7 dokan-text-left">
				<?php
				wp_editor( $biography, 'vendor_biography', [
					'quicktags' => false,
				] );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Save biography data
	 *
	 * @return void
	 */
	public function save_biography_data( $vendor_id ) {
		if ( ! isset( $_POST['vendor_biography'] ) ) {
			return;
		}

		$data = [
			'vendor_biography' => wp_kses_post( $_POST['vendor_biography'] ),
		];

		$store_info         = dokan_get_store_info( $vendor_id );
		$updated_store_info = wp_parse_args( $data, $store_info );

		update_user_meta( $vendor_id, 'dokan_profile_settings', $updated_store_info );
	}
}

Vendor_Biography::instance()->initialize();
