<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Product_Attribute_Swatches {
	protected static $instance = null;

	const MINIMUM_PLUGIN_VERSION   = '1.3.0';
	const RECOMMEND_PLUGIN_VERSION = '1.4.0';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		if ( ! $this->is_activate() ) {
			return;
		}

		if ( version_compare( INSIGHT_SWATCHES_VERSION, self::MINIMUM_PLUGIN_VERSION, '<' ) ) {
			return;
		}

		if ( version_compare( INSIGHT_SWATCHES_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_recommend_plugin_version' ] );
		}

		add_action( 'woocommerce_variable_product_before_variations', [
			$this,
			'add_setting_product_variation_attributes_on_loop',
		] );
		add_action( 'woocommerce_process_product_meta', [ $this, 'save_product_variation_attributes_on_loop' ] );

		add_filter( 'woocommerce_available_variation', [ $this, 'change_variation_price_html' ], 99, 3 );

		// Add extra settings for attribute swatches terms.
		add_action( 'woocommerce_after_add_attribute_fields', [ $this, 'add_custom_fields_for_product_attribute' ] );
		add_action( 'woocommerce_after_edit_attribute_fields', [ $this, 'edit_custom_fields_for_product_attribute' ] );
		add_action( 'woocommerce_attribute_added', [ $this, 'save_custom_fields_for_product_attribute' ] );
		add_action( 'woocommerce_attribute_updated', [ $this, 'save_custom_fields_for_product_attribute' ] );
		add_action( 'woocommerce_attribute_deleted', [ $this, 'delete_custom_fields_on_product_attribute_deleted' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'product_attributes_scripts' ], 20 );
	}

	public function is_activate() {
		return defined( 'INSIGHT_SWATCHES_VERSION' );
	}

	public function admin_notice_recommend_plugin_version() {
		minimog_notice_required_plugin_version( 'Insight Swatches', self::RECOMMEND_PLUGIN_VERSION );
	}

	public function product_attributes_scripts() {
		$screen = get_current_screen();

		if ( 'product_page_product_attributes' === $screen->id ) {
			wp_enqueue_script( 'minimog-admin-product-attributes', MINIMOG_THEME_ASSETS_URI . '/admin/js/product-attributes.js', [ 'jquery' ], null, true );
		}
	}

	public function get_attribute_type_text_styles() {
		return [
			'square' => __( 'Rectangle', 'minimog' ),
			'circle' => __( 'Circle', 'minimog' ),
		];
	}

	public function add_custom_fields_for_product_attribute() {
		$att_type            = 'select';
		$att_type_text_style = '';

		$text_styles = $this->get_attribute_type_text_styles();
		?>
		<div class="form-field hide-on-types show-on-type--text"
			<?php if ( 'text' !== $att_type ): ?>
				style="display: none"
			<?php endif; ?>
		>
			<label for="wc_attribute_type_text_style"><?php esc_html_e( 'Text Style', 'minimog' ); ?></label>
			<select id="wc_attribute_type_text_style" name="wc_attribute_type_text_style">
				<?php foreach ( $text_styles as $value => $label ): ?>
					<option
						value="<?php echo esc_attr( $value ); ?>" <?php selected( $att_type_text_style, $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	}

	public function edit_custom_fields_for_product_attribute() {
		global $wpdb;

		$edit = isset( $_GET['edit'] ) ? absint( $_GET['edit'] ) : 0;

		$attribute_to_edit = $wpdb->get_row( $wpdb->prepare(
			"
				SELECT attribute_id, attribute_type
				FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_id = %d
				",
			$edit
		) );

		$att_type            = $attribute_to_edit->attribute_type;
		$att_type_text_style = get_option( "wc_attribute_type_text_style-{$attribute_to_edit->attribute_id}" );

		$text_styles = $this->get_attribute_type_text_styles();
		?>
		<tr class="form-field hide-on-types show-on-type--text"
			<?php if ( 'text' !== $att_type ): ?>
				style="display: none"
			<?php endif; ?>
		>
			<th valign="top" scope="row">
				<label for="wc_attribute_type_text_style"><?php esc_html_e( 'Text Style', 'minimog' ); ?></label>
			</th>
			<td>
				<select id="wc_attribute_type_text_style" name="wc_attribute_type_text_style">
					<?php foreach ( $text_styles as $value => $label ): ?>
						<option
							value="<?php echo esc_attr( $value ); ?>" <?php selected( $att_type_text_style, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<?php
	}

	public function save_custom_fields_for_product_attribute( $id ) {
		if ( is_admin() && isset( $_POST['wc_attribute_type_text_style'] ) ) {
			$option_name = "wc_attribute_type_text_style-$id";
			update_option( $option_name, sanitize_text_field( $_POST['wc_attribute_type_text_style'] ) );
		}
	}

	public function delete_custom_fields_on_product_attribute_deleted( $id ) {
		delete_option( "wc_attribute_type_text_style-$id" );
	}

	public function add_setting_product_variation_attributes_on_loop() {
		global $post, $product_object;

		$variation_attributes = \Minimog_Woo::instance()->get_variation_attributes_from_attributes( $product_object->get_attributes() );

		$post_id           = $post->ID;
		$product_attribute = get_post_meta( $post_id, 'variation_attributes_show_on_loop', true );

		if ( ! count( $variation_attributes ) ) {
			return;
		}
		?>
		<div class="toolbar toolbar-variations-attributes-show-on-catalog">
			<strong><?php esc_html_e( 'Show attribute on shop catalog', 'minimog' ); ?>
				: <?php echo wc_help_tip( __( 'Select attribute that you want to show to shop catalog. Selected attribute should has variation image.', 'minimog' ) ); ?></strong>
			<select name="variation_attributes_show_on_loop">
				<option value=""><?php esc_html_e( 'Select an attribute', 'minimog' ); ?></option>
				<?php foreach ( $variation_attributes as $attribute_name => $attribute ) : ?>
					<?php
					/**
					 * @var \WC_Product_Attribute $attribute
					 */
					$taxonomy_info = wc_get_attribute( $attribute->get_id() );

					if ( empty( $taxonomy_info ) ) {
						continue;
					}
					?>
					<option
						value="<?php echo esc_attr( $attribute_name ); ?>" <?php selected( $product_attribute, $attribute_name ); ?>><?php echo esc_html( $taxonomy_info->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	}

	public function save_product_variation_attributes_on_loop( $post_id ) {
		if ( isset( $_POST['variation_attributes_show_on_loop'] ) ) {
			// Do not sanitize value. It caused save failed with Arabic language.
			$attribute = $_POST['variation_attributes_show_on_loop']; // XSS valid.

			update_post_meta( $post_id, 'variation_attributes_show_on_loop', $attribute );
		}
	}

	/**
	 * @param                       $settings
	 * @param \WC_Product_Variable  $product
	 * @param \WC_Product_Variation $variation
	 *
	 * @return mixed
	 */
	public function change_variation_price_html( $settings, $product, $variation ) {
		// See if prices should be shown for each variation after selection.
		$show_variation_price = apply_filters( 'woocommerce_show_variation_price', $variation->get_price() === '' || $product->get_variation_sale_price( 'min' ) !== $product->get_variation_sale_price( 'max' ) || $product->get_variation_regular_price( 'min' ) !== $product->get_variation_regular_price( 'max' ), $product, $variation );

		if ( isset( $settings['price_html'] ) ) {
			// Remove span.price tag wrap div.price
			$settings['price_html'] = $show_variation_price ? $variation->get_price_html() : '';
		}

		return $settings;
	}
}

Product_Attribute_Swatches::instance()->initialize();
