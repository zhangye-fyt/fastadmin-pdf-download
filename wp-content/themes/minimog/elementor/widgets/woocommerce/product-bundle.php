<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Product_Bundle extends Base {
	/**
	 * @var \WC_Product $product
	 */
	private $product = null;

	const PRODUCT_TYPE = 'woosb';

	public function get_name() {
		return 'tm-product-bundle';
	}

	public function get_title() {
		return esc_html__( 'Product Bundle', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-products';
	}

	public function get_keywords() {
		return [ 'bundle', 'product' ];
	}

	public function get_script_depends() {
		return [ 'minimog-widget-product-bundle' ];
	}

	public function register_controls() {
		$this->add_content_section();

		$this->add_style_section();

		$this->add_product_price_style_section();

		$this->add_order_number_style_section();

		$this->add_button_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Styling', 'minimog' ),
		] );

		$this->add_control( 'product_id', [
			'label'        => esc_html__( 'Choose Product', 'minimog' ),
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'label_block'  => true,
			'multiple'     => false,
			'autocomplete' => [
				'object' => Module_Query_Base::QUERY_OBJECT_POST,
				'query'  => [
					'post_type' => 'product',
					'tax_query' => [
						[
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => self::PRODUCT_TYPE,
						],
					],
				],
			],
			'render_type'  => 'template',
		] );

		$this->add_control( 'bundle_price', [
			'label'                => esc_html__( 'Bundle Price', 'minimog' ),
			'type'                 => Controls_Manager::SWITCHER,
			'label_on'             => esc_html__( 'Show', 'minimog' ),
			'label_off'            => esc_html__( 'Hide', 'minimog' ),
			'return_value'         => 'yes',
			'default'              => '',
			'selectors_dictionary' => [
				'yes' => 'display: block; !important',
				''    => 'display: none !important;',
			],
			'selectors'            => [
				'{{WRAPPER}} .woosb-total' => '{{VALUE}};',
			],
			'separator'            => 'before',
		] );

		$this->add_control( 'show_product_stock', [
			'label'        => esc_html__( 'Show Stock', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Show', 'minimog' ),
			'label_off'    => esc_html__( 'Hide', 'minimog' ),
			'return_value' => 'yes',
			'separator'    => 'before',
		] );

		$this->add_control( 'show_order_number', [
			'label'        => esc_html__( 'Show Order Number', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Show', 'minimog' ),
			'label_off'    => esc_html__( 'Hide', 'minimog' ),
			'return_value' => 'yes',
			'separator'    => 'before',
		] );

		$this->add_control( 'thumbnail_default_size', [
			'label'        => esc_html__( 'Use Default Thumbnail Size', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
			'separator'    => 'before',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'thumbnail',
			'default'   => 'full',
			'condition' => [
				'thumbnail_default_size!' => '1',
			],
		] );

		$this->end_controls_section();
	}

	private function add_style_section() {
		$this->start_controls_section( 'style_section', [
			'label' => esc_html__( 'Product Bundle', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'box_max_width', [
			'label'      => esc_html__( 'Max Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-product-bundle__product' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'              => 'left',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .tm-product-bundle' => 'display: flex; justify-content: {{VALUE}}',
			],
		] );

		$this->add_control( 'item_heading', [
			'label'     => esc_html__( 'Item', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [],
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .woosb-product + .woosb-product' => 'margin-top: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'price_wrapper_width', [
			'label'      => esc_html__( 'Price Wrapper Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [],
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 80,
					'max' => 300,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .woosb-price' => 'width: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'product_image_width', [
			'label'      => esc_html__( 'Image Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'min' => 5,
					'max' => 50,
				],
				'px' => [
					'min' => 50,
					'max' => 500,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-product-bundle .woosb-products .woosb-thumb' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'product_title_typography',
			'label'    => esc_html__( 'Product Title Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm-product-bundle .woosb-products .woosb-title',
		] );

		$this->start_controls_tabs( 'product_title_color_tabs', [
			'label' => esc_html__( 'Colors', 'minimog' ),
		] );

		$this->start_controls_tab( 'product_title_color_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'product_title_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-product-bundle .woosb-products .woosb-title' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'product_title_color_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'product_title_hover_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-product-bundle .woosb-products .woosb-title:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_product_price_style_section() {
		$this->start_controls_section( 'product_price_style_section', [
			'label' => esc_html__( 'Product Price', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'product_price_typography',
			'label'    => esc_html__( 'Product Price Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm-product-bundle .woosb-price > div > .amount',
		] );

		$this->add_control( 'product_price_text_color', [
			'label'     => esc_html__( 'Original Price', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-product-bundle .woosb-products .woosb-price-ori > .amount' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'product_saved_price_typography',
			'label'    => esc_html__( 'Saved Price Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm-product-bundle .woosb-products .woosb-price-saved',
		] );

		$this->add_control( 'product_price_saved_text_color', [
			'label'     => esc_html__( 'Saved Price', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-product-bundle .woosb-products .woosb-price-saved' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_order_number_style_section() {
		$this->start_controls_section( 'order_number_style_section', [
			'label'     => esc_html__( 'Order Number', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'show_order_number' => 'yes',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'order_number',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm-product-bundle .item-order-count',
		] );

		$this->add_control( 'order_number_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-product-bundle .item-order-count' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'order_number_background_color', [
			'label'     => esc_html__( 'Background', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-product-bundle .item-order-count' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'order_number_border_color', [
			'label'     => esc_html__( 'Border', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-product-bundle .item-order-count' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_button_style_section() {
		$this->start_controls_section( 'add_to_cart_button_style_section', [
			'label' => esc_html__( 'Add To Cart Button', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'button_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} form.cart' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'max'  => 1000,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .single_add_to_cart_button' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 200,
					'min'  => 20,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .single_add_to_cart_button' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'text',
			'selector' => '{{WRAPPER}} .single_add_to_cart_button',
		] );

		$this->start_controls_tabs( 'button_skin_tabs' );

		$this->start_controls_tab( 'button_skin_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'button_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button' => '--minimog-color-button-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button' => '--minimog-color-button-background: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button' => '--minimog-color-button-border: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'button_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button:hover' => '--minimog-color-button-hover-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_hover_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button:hover' => '--minimog-color-button-hover-background: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_hover_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button:hover' => '--minimog-color-button-hover-border: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['product_id'] ) ) {
			return;
		}

		/**
		 * @var \WC_Product_Woosb $product
		 */
		$product = wc_get_product( $settings['product_id'] );

		if ( empty( $product ) || ! $product instanceof \WC_Product || self::PRODUCT_TYPE !== $product->get_type() ) {
			return;
		}

		$product_id = $product->get_id();

		$ids = get_post_meta( $product_id, 'woosb_ids', true );

		$this->product = $product;

		$this->add_render_attribute( 'wrapper', [
			'class'                         => [
				'tm-product-bundle',
				'woocommerce',
			],
			'data-price_format'             => get_woocommerce_price_format(),
			'data-price_decimals'           => wc_get_price_decimals(),
			'data-price_thousand_separator' => wc_get_price_thousand_separator(),
			'data-price_decimal_separator'  => wc_get_price_decimal_separator(),
			'data-currency_symbol'          => get_woocommerce_currency_symbol(),
			'data-bundled_price'            => get_option( '_woosb_bundled_price', 'price' ),
			'data-bundled_price_from'       => get_option( '_woosb_bundled_price_from', 'sale_price' ),
			'data-save_text'                => esc_html__( 'Save', 'minimog' ),
			'data-discount_amount'          => $product->get_discount_amount(),
		] );

		$this->add_render_attribute( 'form', [
			'class'   => 'cart',
			'action'  => apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ),
			'method'  => 'post',
			'enctype' => 'multipart/form-data',
		] );

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ) ?>>
			<div <?php wc_product_class( 'tm-product-bundle__product', $product ); ?>>

				<?php $this->show_bundled( $product ); ?>

				<form <?php $this->print_render_attribute_string( 'form' ) ?>>
					<input name="woosb_ids" class="woosb_ids woosb-ids" type="hidden"
					       value="<?php echo esc_attr( $ids ) ?>"/>
					<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>"
					        class="single_add_to_cart_button button alt">
						<span><?php echo esc_html( $product->single_add_to_cart_text() ); ?></span>
						<span class="price"></span>
					</button>
				</form>
			</div>
		</div>

		<?php
	}

	/**
	 * @param \WC_Product_Woosb $product
	 */
	protected function show_bundled( $product ) {
		$settings      = $this->get_settings_for_display();
		$product_types = \Minimog\Woo\Product_Bundle::instance()->get_types();

		if ( isset( $settings['thumbnail_default_size'] ) && '1' !== $settings['thumbnail_default_size'] ) {
			$thumbnail_size = \Minimog_Image::elementor_parse_image_size( $settings );
		} else {
			$thumbnail_size = \Minimog_Woo::instance()->get_loop_product_image_size( 100 );
		}

		$product_id          = $product->get_id();
		$fixed_price         = $product->is_fixed_price();
		$discount_amount     = $product->get_discount_amount();
		$discount_percentage = $product->get_discount_percentage();
		$count               = 1;
		$quantity_input_html = '';

		if ( $items = $product->get_items() ) {
			do_action( 'woosb_before_wrap', $product );

			echo '<div class="woosb-wrap woosb-wrap-' . esc_attr( $product_id ) . ' woosb-bundled" data-id="' . esc_attr( $product_id ) . '">';

			if ( $before_text = apply_filters( 'woosb_before_text', get_post_meta( $product_id, 'woosb_before_text', true ), $product_id ) ) {
				echo '<div class="woosb-before-text woosb-text">' . do_shortcode( stripslashes( $before_text ) ) . '</div>';
			}

			do_action( 'woosb_before_table', $product );

			$this->add_render_attribute( 'woosb_products', [
				'class'                => 'woosb-products',
				'data-product-sku'     => $product->get_sku(),
				'data-discount-amount' => $discount_amount,
				'data-discount'        => $discount_percentage,
				'data-fixed-price'     => $fixed_price ? 'yes' : 'no',
				'data-price'           => wc_get_price_to_display( $product ),
				'data-price-suffix'    => htmlentities( $product->get_price_suffix() ),
				'data-variables'       => $product->has_variables() ? 'yes' : 'no',
				'data-optional'        => $product->is_optional() ? 'yes' : 'no',
				'data-min'             => get_post_meta( $product_id, 'woosb_limit_whole_min', true ) ? : 1,
				'data-max'             => get_post_meta( $product_id, 'woosb_limit_whole_max', true ) ? : '',
			] );
			?>
			<div <?php $this->print_render_attribute_string( 'woosb_products' ) ?>>

				<?php
				$product_items_count = 0;
				foreach ( $items as $item ) {
					/**
					 * @var \WC_Product_Variable $_product
					 */
					$_product = wc_get_product( $item['id'] );
					$product_items_count++;

					if ( ! $_product || in_array( $_product->get_type(), $product_types, true ) ) {
						continue;
					}

					if ( ! apply_filters( 'woosb_item_exclude', true, $_product, $product ) ) {
						continue;
					}

					$_qty = $item['qty'];
					$_min = 0;
					$_max = 1000;

					if ( $product->is_optional() ) {
						if ( get_post_meta( $product_id, 'woosb_limit_each_min_default', true ) === 'on' ) {
							$_min = $_qty;
						} else {
							$_min = absint( get_post_meta( $product_id, 'woosb_limit_each_min', true ) ? : 0 );
						}

						$_max = absint( get_post_meta( $product_id, 'woosb_limit_each_max', true ) ? : 1000 );

						if ( $_qty < $_min ) {
							$_qty = $_min;
						}

						if ( ( $_max > $_min ) && ( $_qty > $_max ) ) {
							$_qty = $_max;
						}
					}

					if ( ( ! $_product->is_in_stock() || ! $_product->has_enough_stock( $_qty ) ) && ( get_option( '_woosb_exclude_unpurchasable', 'no' ) === 'yes' ) ) {
						$_qty = 0;
					}
					?>

					<?php if ( get_post_meta( $product_id, 'woosb_optional_products', true ) === 'on' ) {
						if ( ( $_product->get_backorders() === 'no' ) && ( $_product->get_stock_status() !== 'onbackorder' ) && is_int( $_product->get_stock_quantity() ) && ( $_product->get_stock_quantity() < $_max ) ) {
							$_max = $_product->get_stock_quantity();
						}

						if ( $_product->is_sold_individually() ) {
							$_max = 1;
						}

						ob_start();
						?>
						<div class="woosb-quantity">
							<?php
							if ( $_product->is_in_stock() ) {
								woocommerce_quantity_input( array(
									'input_value' => $_qty,
									'min_value'   => $_min,
									'max_value'   => $_max,
								), $_product );
							} else { ?>
								<input type="number" class="input-text qty text woosb-qty" value="0" disabled/>
							<?php } ?>
						</div>
						<?php
						$quantity_input_html = ob_get_clean();
					} ?>

					<div class="product woosb-product"
					     data-name="<?php echo esc_attr( $_product->get_name() ); ?>"
					     data-id="<?php echo esc_attr( $_product->is_type( 'variable' ) ? 0 : $item['id'] ); ?>"
					     data-price="<?php echo esc_attr( \WPCleverWoosb_Helper::get_price_to_display( $_product, 1, 'min' ) ); ?>"
					     data-price-suffix="<?php echo esc_attr( htmlentities( $_product->get_price_suffix() ) ); ?>"
					     data-qty="<?php echo esc_attr( $_qty ); ?>"
					     data-order="<?php echo esc_attr( $count ); ?>">

						<?php do_action( 'woosb_before_item', $_product, $product, $count ); ?>

						<div class="woosb-thumb-wrap">
							<?php if ( ! empty( $settings['show_order_number'] ) && 'yes' === $settings['show_order_number'] ): ?>
								<div class="item-order-count"><span><?php echo '' . $product_items_count; ?></span>
								</div>
							<?php endif; ?>

							<?php if ( get_option( '_woosb_bundled_thumb', 'yes' ) !== 'no' ) : ?>
								<div class="woosb-thumb">
									<?php if ( $_product->is_visible() && ( get_option( '_woosb_bundled_link', 'yes' ) !== 'no' ) ) {
										echo '<a ' . ( get_option( '_woosb_bundled_link', 'yes' ) === 'yes_popup' ? 'class="woosq-btn no-ajaxy" data-id="' . $item['id'] . '"' : '' ) . ' href="' . esc_url( $_product->get_permalink() ) . '" ' . ( get_option( '_woosb_bundled_link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>';
									} ?>
									<?php
									/**
									 * Disabled variation image changed.
									 * Because it not support properly image size.
									 * Move img out of div to make js disabled.
									 */
									?>
									<!--<div class="woosb-thumb-ori"></div>
									<div class="woosb-thumb-new"></div>-->
									<?php
									$product_image = \Minimog_Woo::instance()->get_product_image( $_product, $thumbnail_size );
									echo apply_filters( 'woosb_item_thumbnail', $product_image, $_product );
									?>

									<?php if ( $_product->is_visible() && ( get_option( '_woosb_bundled_link', 'yes' ) !== 'no' ) ) {
										echo '</a>';
									} ?>
								</div><!-- /woosb-thumb -->
							<?php endif; ?>
						</div>
						<div class="woosb-product-info">
							<div class="woosb-product-main-info">
								<div class="woosb-title-wrap">
									<?php
									do_action( 'woosb_before_item_name', $_product );

									echo '<h3 class="woosb-title post-title-2-rows">';

									if ( ( get_option( '_woosb_bundled_qty', 'yes' ) === 'yes' ) && ( get_post_meta( $product_id, 'woosb_optional_products', true ) !== 'on' ) ) {
										echo apply_filters( 'woosb_item_qty', $item['qty'] . ' × ', $item['qty'], $_product );
									}

									$_name         = '';
									$_product_name = apply_filters( 'woosb_item_product_name', $_product->get_name(), $_product );

									if ( $_product->is_visible() && ( get_option( '_woosb_bundled_link', 'yes' ) !== 'no' ) ) {
										$_name .= '<a ' . ( get_option( '_woosb_bundled_link', 'yes' ) === 'yes_popup' ? 'class="woosq-btn no-ajaxy" data-id="' . $item['id'] . '"' : '' ) . ' href="' . esc_url( $_product->get_permalink() ) . '" ' . ( get_option( '_woosb_bundled_link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>';
									}

									if ( $_product->is_in_stock() && $_product->has_enough_stock( $_qty ) ) {
										$_name .= $_product_name;
									} else {
										$_name .= '<s>' . $_product_name . '</s>';
									}

									if ( $_product->is_visible() && ( get_option( '_woosb_bundled_link', 'yes' ) !== 'no' ) ) {
										$_name .= '</a>';
									}

									echo apply_filters( 'woosb_item_name', $_name, $_product, $product, $count );
									echo '</h3>';

									?>

									<?php if ( ( $bundled_price = get_option( '_woosb_bundled_price', 'price' ) ) !== 'no' ) { ?>
										<div class="woosb-price">
											<div class="woosb-price-ori">
												<?php
												$_ori_price = $_product->get_price();
												$_get_price = \WPCleverWoosb_Helper::get_price( $_product );

												if ( ! $product->is_fixed_price() && $discount_percentage > 0 ) {
													$_new_price     = true;
													$_product_price = $_get_price * ( 100 - $discount_percentage ) / 100;
												} else {
													$_new_price     = false;
													$_product_price = $_get_price;
												}

												switch ( $bundled_price ) {
													case 'price':
														if ( $_new_price ) {
															$regular_price = wc_get_price_to_display( $_product, array( 'price' => $_get_price ) );
															$sale_price    = wc_get_price_to_display( $_product, array( 'price' => $_product_price ) );
															$_amount_saved = $regular_price - $sale_price;

															$_price = wc_price( $sale_price );

														} else {
															if ( $_get_price > $_ori_price ) {
																$_price = wc_price( \WPCleverWoosb_Helper::get_price_to_display( $_product ) ) . $_product->get_price_suffix();
															} else {
																$_price = $_product->get_price_html();
															}
														}

														break;
													case 'subtotal':
														if ( $_new_price ) {

															$subtotal_regular_price = wc_get_price_to_display( $_product, array(
																'price' => $_get_price,
																'qty'   => $item['qty'],
															) );

															$subtotal_sale_price = wc_get_price_to_display( $_product, array(
																'price' => $_product_price,
																'qty'   => $item['qty'],
															) );

															$_amount_saved = $subtotal_regular_price - $subtotal_sale_price;

															$_price = wc_price( $subtotal_sale_price ) . $_product->get_price_suffix();

														} else {
															$_price = wc_price( \WPCleverWoosb_Helper::get_price_to_display( $_product, $item['qty'] ) ) . $_product->get_price_suffix();
														}

														break;
													default:
														$_amount_saved = 0;
														$_price        = $_product->get_price_html();
												}

												echo apply_filters( 'woosb_item_price', $_price, $_product );

												if ( $_new_price && intval( $_amount_saved ) > 0 ) {
													echo '<div class="woosb-price-saved">' . esc_html__( 'Save', 'minimog' ) . '&nbsp;' . wc_price( $_amount_saved ) . '</div>';
												}
												?>
											</div>
											<div class="woosb-price-new"></div>
											<?php do_action( 'woosb_after_item_price', $_product ); ?>
										</div>
									<?php } ?>

									<?php
									echo '<div class="woosb-price-wrapper"></div>';

									do_action( 'woosb_after_item_name', $_product );

									if ( get_option( '_woosb_bundled_description', 'no' ) === 'yes' ) {
										echo '<div class="woosb-description">' . apply_filters( 'woosb_item_description', $_product->get_short_description(), $_product ) . '</div>';
									}
									?>
								</div>

								<div class="woosb-product-cart">
									<?php if ( $_product->is_type( 'variable' ) ) { ?>
										<div class="minimog-variation-select-wrap">
											<?php
											if ( ( get_option( '_woosb_variations_selector', 'default' ) === 'wpc_radio' ) && class_exists( 'WPClever_Woovr' ) ) {
												\WPClever_Woovr::woovr_variations_form( $_product );
											} else {
												echo '<div class="minimog-variation-select-wrap">';
												\Minimog_Woo::instance()->get_product_variation_dropdown_html( $_product, [
													'show_label' => false,
													'show_price' => false,
												] );
												echo '</div>';

												$attributes           = $_product->get_variation_attributes();
												$available_variations = $_product->get_available_variations();
												$variations_json      = wp_json_encode( $available_variations );
												$variations_attr      = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

												if ( ! empty( $attributes ) ) {
													$total_attrs = count( $attributes );
													$loop_count  = 0;

													echo '<div class="variations_form" data-product_id="' . absint( $_product->get_id() ) . '" data-product_variations="' . $variations_attr . '">';
													echo '<div class="variations">';

													foreach ( $attributes as $attribute_name => $options ) {
														$loop_count++;
														?>
														<div class="variation">
															<div class="label">
																<?php echo wc_attribute_label( $attribute_name ); ?>
															</div>
															<div class="select">
																<?php
																$attr     = 'attribute_' . sanitize_title( $attribute_name );
																$selected = isset( $_REQUEST[ $attr ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ $attr ] ) ) ) : $_product->get_variation_default_attribute( $attribute_name );
																wc_dropdown_variation_attribute_options( array(
																	'options'          => $options,
																	'attribute'        => $attribute_name,
																	'product'          => $_product,
																	'selected'         => $selected,
																	'show_option_none' => wc_attribute_label( $attribute_name ),
																) );
																?>
															</div>
															<?php if ( $loop_count === $total_attrs ): ?>
																<?php echo '<div class="reset">' . apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'minimog' ) . '</a>' ) . '</div>'; ?>
															<?php endif; ?>
														</div>
													<?php }
													echo '</div>';
													echo '</div>';

													if ( get_option( '_woosb_bundled_description', 'no' ) === 'yes' ) {
														echo '<div class="woosb-variation-description"></div>';
													}
												}
											}

											do_action( 'woosb_after_item_variations', $_product );
											?>
										</div>
										<?php
									}
									?>
									<?php echo '' . $quantity_input_html; ?>
									<?php if ( ! empty( $settings['show_product_stock'] ) && 'yes' === $settings['show_product_stock'] ) : ?>
										<?php echo '<div class="woosb-availability">' . wc_get_stock_html( $_product ) . '</div>'; ?>
									<?php endif; ?>
								</div>
							</div>
							<?php if ( ( $bundled_price = get_option( '_woosb_bundled_price', 'price' ) ) !== 'no' ) { ?>
								<div class="woosb-price">
									<div class="woosb-price-ori">
										<?php
										$_ori_price = $_product->get_price();
										$_get_price = \WPCleverWoosb_Helper::get_price( $_product );

										if ( ! $product->is_fixed_price() && $discount_percentage > 0 ) {
											$_new_price     = true;
											$_product_price = $_get_price * ( 100 - $discount_percentage ) / 100;
										} else {
											$_new_price     = false;
											$_product_price = $_get_price;
										}

										switch ( $bundled_price ) {
											case 'price':
												if ( $_new_price ) {
													$regular_price = wc_get_price_to_display( $_product, array( 'price' => $_get_price ) );
													$sale_price    = wc_get_price_to_display( $_product, array( 'price' => $_product_price ) );
													$_amount_saved = $regular_price - $sale_price;

													$_price = wc_price( $sale_price );

												} else {
													if ( $_get_price > $_ori_price ) {
														$_price = wc_price( \WPCleverWoosb_Helper::get_price_to_display( $_product ) ) . $_product->get_price_suffix();
													} else {
														$_price = $_product->get_price_html();
													}
												}
												break;
											case 'subtotal':
												if ( $_new_price ) {

													$subtotal_regular_price = wc_get_price_to_display( $_product, array(
														'price' => $_get_price,
														'qty'   => $item['qty'],
													) );

													$subtotal_sale_price = wc_get_price_to_display( $_product, array(
														'price' => $_product_price,
														'qty'   => $item['qty'],
													) );

													$_amount_saved = $subtotal_regular_price - $subtotal_sale_price;

													$_price = wc_price( $subtotal_sale_price ) . $_product->get_price_suffix();

												} else {
													$_price = wc_price( \WPCleverWoosb_Helper::get_price_to_display( $_product, $item['qty'] ) ) . $_product->get_price_suffix();
												}

												break;
											default:
												$_amount_saved = 0;
												$_price        = $_product->get_price_html();
										}

										echo apply_filters( 'woosb_item_price', $_price, $_product );

										if ( $_new_price && floatval( $_amount_saved ) > 0 ) {
											echo '<div class="woosb-price-saved">' . esc_html__( 'Save', 'minimog' ) . '&nbsp;' . wc_price( $_amount_saved ) . '</div>';
										}
										?>
									</div>
									<div class="woosb-price-new"></div>
									<?php do_action( 'woosb_after_item_price', $_product ); ?>
								</div>
							<?php } ?>
						</div>
						<?php do_action( 'woosb_after_item', $_product, $product, $count ); ?>
					</div>
					<?php
					$count++;
				}
				?>

			</div>
			<?php
			if ( ! $product->is_fixed_price() && ( $product->has_variables() || $product->is_optional() ) ) {
				echo '<div class="woosb-total woosb-text"></div>';
			}

			echo '<div class="woosb-alert woosb-text" style="display: none"></div>';

			do_action( 'woosb_after_table', $product );

			if ( $after_text = apply_filters( 'woosb_after_text', get_post_meta( $product_id, 'woosb_after_text', true ), $product_id ) ) {
				echo '<div class="woosb-after-text woosb-text">' . do_shortcode( stripslashes( $after_text ) ) . '</div>';
			}

			echo '</div>';
		}
	}

	protected function save_amount( $product ) {
		if ( ! $product->is_on_sale() || 'grouped' == $product->get_type() ) {
			return;
		}

		$saved_amount = 0;

		if ( $product->get_type() == 'variable' ) {
			$available_variations = $product->get_available_variations();
			$max_saved_amount     = 0;
			$total_variations     = count( $available_variations );

			for ( $i = 0; $i < $total_variations; $i++ ) {
				$variation_id          = $available_variations[ $i ]['variation_id'];
				$variable_product      = new \WC_Product_Variation( $variation_id );
				$regular_price         = $variable_product->get_regular_price();
				$sales_price           = $variable_product->get_sale_price();
				$variable_saved_amount = $regular_price && $sales_price ? ( $regular_price - $sales_price ) : 0;

				if ( $variable_saved_amount > $max_saved_amount ) {
					$max_saved_amount = $variable_saved_amount;
				}
			}

			$saved_amount = $max_saved_amount ? $max_saved_amount : $saved_amount;
		} elseif ( $product->get_regular_price() != 0 ) {
			$saved_amount = $product->get_regular_price() - $product->get_sale_price();
		}

		if ( intval( $saved_amount ) > 0 ) {
			echo wc_price( $saved_amount );
		}
	}
}
