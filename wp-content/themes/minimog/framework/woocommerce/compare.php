<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Compare {

	protected static $instance = null;

	const RECOMMEND_PLUGIN_VERSION = '5.3.7';

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

		if ( defined( 'WOOSCP_VERSION' ) // Constant in old version
		     || ( defined( 'WOOSC_VERSION' ) && version_compare( WOOSC_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_plugin_version' ] );
		}

		add_filter( 'woosc_button_position_archive', '__return_false' );
		add_filter( 'woosc_button_position_single', '__return_false' );

		add_action( 'minimog/single_product/before_popup_links', [ $this, 'add_compare_button_to_popup_links' ] );

		add_action( 'minimog/sticky_product_bar/after_add_to_cart_button', [
			$this,
			'add_compare_button_to_sticky_bar',
		] );

		// Change compare button color on popup.
		add_filter( 'woosc_bar_btn_color_default', [ $this, 'change_compare_button_color' ] );

		add_filter( 'woosc_get_table_minimum_columns', [ $this, 'change_minimum_columns' ] );

		// Custom Rows.
		add_filter( 'woosc_fields', [ $this, 'add_custom_fields' ] );
		add_filter( 'woosc_field_value', [ $this, 'custom_field_value' ], 99, 3 );
		// Update Row Html.
		add_filter( 'woosc_product_price', [ $this, 'update_row_price_html' ], 99, 2 );
		add_filter( 'woosc_product_rating', [ $this, 'update_row_rating_html' ], 99, 2 );

		/**
		 * Quick compare table in single product.
		 * Update to property position.
		 */
		// First remove all position.
		minimog_remove_filters_for_anonymous_class( 'woocommerce_after_single_product_summary', 'WPCleverWoosc', 'show_quick_table', 19 );
		minimog_remove_filters_for_anonymous_class( 'woocommerce_after_single_product_summary', 'WPCleverWoosc', 'show_quick_table', 21 );
		minimog_remove_filters_for_anonymous_class( 'woocommerce_after_single_product_summary', 'WPCleverWoosc', 'show_quick_table', 20 );
		// Then re-add it back.
		add_action( 'wp', [ $this, 'wp_init' ], 99 );
	}

	public function is_activate() {
		return class_exists( 'WPCleverWoosc' );
	}

	public function admin_notice_minimum_plugin_version() {
		minimog_notice_required_plugin_version( 'WPC Smart Compare for WooCommerce', self::RECOMMEND_PLUGIN_VERSION );
	}

	public function wp_init() {
		if ( 'yes' === \WPCleverWoosc::get_setting( 'quick_table_enable' ) ) {
			$quick_table_position = \WPCleverWoosc::get_setting( 'quick_table_position', 'above_related' );
			$related_position     = \Minimog::setting( 'single_product_related_position' );

			$hook     = 'woocommerce_after_single_product';
			$priority = 19;

			switch ( $quick_table_position ) {
				case 'above_related':
					if ( 'below_product_tabs' === $related_position ) {
						$priority = 19;
					} elseif ( 'in_linked_product_tabs' === $related_position ) {
						$priority = 39;
					}
					break;
				case 'under_related':
				case 'replace_related':
					if ( 'below_product_tabs' === $related_position ) {
						$priority = 21;
					} elseif ( 'in_linked_product_tabs' === $related_position ) {
						$priority = 41;
					}
					break;
			}

			add_action( $hook, [ $this, 'show_quick_table' ], $priority );
		}
	}

	public function show_quick_table() {
		?>
		<div class="minimog-quick-table-wrap entry-product-section">
			<div class="<?php echo Single_Product::instance()->page_content_container_class(); ?>">
				<div class="entry-product-block">
					<?php echo do_shortcode( '[woosc_quick_table]' ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	public static function output_button( $args = array() ) {
		if ( '1' !== \Minimog::setting( 'shop_archive_compare' )
		     || \Minimog::is_mobile()
		     || ! self::instance()->is_activate()
		) {
			return;
		}

		global $product;

		$product_id = $product->get_id();

		$defaults = array(
			'show_tooltip'     => false,
			'tooltip_position' => 'top',
			'tooltip_skin'     => '',
			'style'            => '02',
		);
		$args     = wp_parse_args( $args, $defaults );

		$_wrapper_classes = "product-action compare-btn style-{$args['style']}";

		if ( $args['show_tooltip'] === true ) {
			$_wrapper_classes .= ' hint--bounce';
			$_wrapper_classes .= " hint--{$args['tooltip_position']}";

			if ( ! empty( $args['tooltip_skin'] ) ) {
				$_wrapper_classes .= " hint--{$args['tooltip_skin']}";
			}
		}
		?>
		<div class="<?php echo esc_attr( $_wrapper_classes ); ?>"
		     data-hint="<?php esc_attr_e( 'Compare', 'minimog' ) ?>">
			<?php echo do_shortcode( '[woosc id="' . $product_id . '" type="link"]' ); ?>
		</div>
		<?php
	}

	public function add_compare_button_to_popup_links() {
		global $product;

		if ( '1' !== \Minimog::setting( 'single_product_compare_enable' ) ) {
			return;
		}
		?>
		<div class="product-popup-link compare-btn">
			<?php echo do_shortcode( '[woosc id="' . $product->get_id() . '" type="link"]' ); ?>
		</div>
		<?php
	}

	public function add_compare_button_to_sticky_bar() {
		self::output_button( [
			'style'        => '01',
			'show_tooltip' => false,
		] );
	}

	public function change_compare_button_color() {
		$primary_color = \Minimog::setting( 'primary_color' );

		return $primary_color;
	}

	public function change_minimum_columns() {
		return 5;
	}

	public function add_custom_fields( $fields ) {
		/**
		 * Required re-save settings in WP Admin
		 */
		$fields['minimog_sold']     = esc_html__( 'Sold', 'minimog' );
		$fields['minimog_shipping'] = esc_html__( 'Shipping', 'minimog' );

		return $fields;
	}

	public function custom_field_value( $field_value, $field, $product_id ) {
		if ( in_array( $field, [
			'minimog_sold',
			'minimog_shipping',
		] ) ) {
			$product = wc_get_product( $product_id );

			if ( $product instanceof \WC_Product ) {
				switch ( $field ) {
					case 'minimog_sold' :
						return $product->get_total_sales();
					case 'minimog_shipping':
						$shipping_class_id = $product->get_shipping_class_id();
						if ( $shipping_class_id ) {
							$term = get_term_by( 'id', $shipping_class_id, 'product_shipping_class' );

							if ( $term && ! is_wp_error( $term ) ) {
								return $term->name;
							}
						}

						return '';
				}
			}
		}

		return $field_value;
	}

	/**
	 * @param string      $price_html
	 * @param \WC_Product $product
	 *
	 * @return string
	 */
	public function update_row_price_html( $price_html, $product ) {
		$price_html      = $product->get_price_html();
		$sale_badge_html = '';
		if ( $product->is_on_sale() ) {
			$saved_amount = \Minimog_Woo::instance()->get_product_price_saving_percentage( $product );

			if ( $saved_amount > 0 ) {
				$saved_amount = sprintf( esc_html__( 'Save %s', 'minimog' ), $saved_amount . '%' );
			} else {
				$saved_amount = esc_html__( 'Sale !', 'minimog' );
			}

			$sale_badge_html = '<div class="compare-price-saved">' . $saved_amount . '</div>';
		}

		return sprintf( '<div class="compare-price-wrap">%1$s %2$s</div>', $price_html, $sale_badge_html );
	}

	/**
	 * @param string      $rating_html
	 * @param \WC_Product $product
	 *
	 * @return string
	 */
	public function update_row_rating_html( $rating_html, $product ) {
		$average_rating = $product->get_average_rating();
		$rating_html    = '';

		if ( $average_rating > 0 ) {
			$rating_average_html = '<div class="compare-rating-average">' . $average_rating . '</div>';
			$rating_star_html    = wc_get_rating_html( $average_rating ); // WordPress.XSS.EscapeOutput.OutputNotEscaped.

			$review_count = $product->get_review_count();

			$rating_count_html = _n( '%s review', '%s reviews', $review_count, 'minimog' );
			$rating_count_html = sprintf( $rating_count_html, '<span class="count">' . esc_html( $review_count ) . '</span>' );
			$rating_count_html = '<div class="compare-review-count">' . $rating_count_html . '</div>';

			$rating_html = '<div class="compare-reviews-wrap">' . $rating_average_html . $rating_star_html . $rating_count_html . '</div>';
		}

		return $rating_html;
	}
}

Compare::instance()->initialize();
