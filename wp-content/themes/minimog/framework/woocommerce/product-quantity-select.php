<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Product_Quantity_Select {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_data_tabs' ] );
		add_action( 'woocommerce_product_data_panels', [ $this, 'product_data_panels' ] );
		add_action( 'woocommerce_process_product_meta', [ $this, 'process_product_meta' ] );
	}

	public function product_data_tabs( $tabs ) {
		$tabs['minimog_quantity_select'] = array(
			'label'  => __( 'Quantity', 'minimog' ),
			'target' => 'minimog_quantity_settings',
		);

		return $tabs;
	}

	public function product_data_panels() {
		?>
		<div id="minimog_quantity_settings" class="panel woocommerce_options_panel">
			<div class="options_group">
				<?php
				woocommerce_wp_select( [
					'id'      => '_quantity_type',
					'label'   => __( 'Type', 'minimog' ),
					'options' => [
						''       => __( 'Default', 'minimog' ),
						'input'  => __( 'Input', 'minimog' ),
						'select' => __( 'Select', 'minimog' ),
					],
				] );

				woocommerce_wp_textarea_input( [
					'id'          => '_quantity_ranges',
					'label'       => __( 'Values', 'minimog' ),
					'cols'        => 50,
					'rows'        => 5,
					'style'       => 'height: 100px;',
					'description' => __( 'These values will be used for select type. Enter each value in one line and can use the range e.g "1-5".', 'minimog' ),
				] );
				?>
			</div>
		</div>
		<?php
	}

	public function process_product_meta( $post_id ) {
		if ( isset( $_POST['_quantity_type'] ) ) {
			update_post_meta( $post_id, '_quantity_type', sanitize_text_field( $_POST['_quantity_type'] ) );
		} else {
			delete_post_meta( $post_id, '_quantity_type' );
		}

		if ( isset( $_POST['_quantity_ranges'] ) ) {
			update_post_meta( $post_id, '_quantity_ranges', sanitize_textarea_field( $_POST['_quantity_ranges'] ) );
		} else {
			delete_post_meta( $post_id, '_quantity_ranges' );
		}
	}
}

Product_Quantity_Select::instance()->initialize();
