<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Trust_Badge {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		// Product data tabs.
		add_filter( 'woocommerce_product_data_tabs', [ $this, 'add_product_data_tab' ], 10, 1 );
		add_action( 'woocommerce_product_data_panels', [ $this, 'add_product_data_panel' ] );
		add_action( 'woocommerce_process_product_meta', [ $this, 'save_product_meta' ] );

		add_action( 'woocommerce_single_product_summary', [ $this, 'show_trust_badge_image' ], 45 );
	}

	public function add_product_data_tab( $tabs ) {
		$tabs['minimog_trust_badge'] = array(
			'label'  => __( 'Trust Badge', 'minimog' ),
			'target' => 'minimog_trust_badge_settings',
		);

		return $tabs;
	}

	public function add_product_data_panel() {
		?>
		<div id="minimog_trust_badge_settings" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php
				woocommerce_wp_select( array(
					'id'       => '_show_trust_badge',
					'label'    => __( 'Trust Badge', 'minimog' ),
					'desc_tip' => __( 'Show trust badge image below Add To Cart button.', 'minimog' ),
					'options'  => [
						''  => __( 'Default', 'minimog' ),
						'0' => __( 'Hide', 'minimog' ),
						'1' => __( 'Show', 'minimog' ),
					],
				) );
				?>
			</div>
		</div>
		<?php
	}

	public function save_product_meta( $post_id ) {
		if ( isset( $_POST['_show_trust_badge'] ) ) {
			update_post_meta( $post_id, '_show_trust_badge', sanitize_text_field( $_POST['_show_trust_badge'] ) );
		}
	}

	public function show_trust_badge_image() {
		global $product;

		$enable = get_post_meta( $product->get_id(), '_show_trust_badge', true );

		if ( '' === $enable ) {
			$enable = \Minimog::setting( 'single_product_trust_badge_enable' );
		}

		if ( '0' === $enable ) {
			return;
		}

		wc_get_template( 'single-product/trust-badge.php' );
	}
}

Trust_Badge::instance()->initialize();
