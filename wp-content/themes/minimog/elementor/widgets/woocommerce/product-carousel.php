<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || exit;

class Widget_Product_Carousel extends Posts_Carousel_Base {

	private $loop_settings = array();

	public function get_name() {
		return 'tm-product-carousel';
	}

	public function get_title() {
		return esc_html__( 'Products Carousel', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-posts-carousel';
	}

	public function get_keywords() {
		return [ 'product', 'products', 'carousel', 'product carousel', 'carousel product' ];
	}

	public function before_slider() {
		$settings = $this->get_settings_for_display();

		$style         = ! empty( $settings['style'] ) ? $settings['style'] : 'carousel-01';
		$caption_style = ! empty( $settings['caption_style'] ) ? $settings['caption_style'] : '01';

		$this->add_render_attribute( $this->get_slider_key(), 'class', str_replace( 'carousel-', 'group-style-', $style ) );
		$this->add_render_attribute( $this->get_slider_key(), 'class', 'minimog-product style-' . $style );
		$this->add_render_attribute( $this->get_slider_key(), 'class', 'caption-style-' . $caption_style );

		$this->loop_settings = [
			'layout'            => 'slider',
			'style'             => $style,
			'caption_style'     => $caption_style,
			'show_price'        => ! empty( $settings['show_price'] ) ? 1 : 0,
			'show_variation'    => ! empty( $settings['show_variation'] ) ? 1 : 0,
			'show_category'     => ! empty( $settings['show_category'] ) ? 1 : 0,
			'show_brand'        => ! empty( $settings['show_brand'] ) ? 1 : 0,
			'show_rating'       => ! empty( $settings['show_rating'] ) ? 1 : 0,
			'show_availability' => ! empty( $settings['show_availability'] ) ? 1 : 0,
			'show_stock_bar'    => ! empty( $settings['show_stock_bar'] ) ? 1 : 0,
		];

		if ( isset( $settings['thumbnail_default_size'] ) && '1' !== $settings['thumbnail_default_size'] ) {
			$this->loop_settings['thumbnail_size'] = \Minimog_Image::elementor_parse_image_size( $settings );
		}
	}

	protected function get_post_type() {
		return 'product';
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_thumbnail_style_section();

		$this->add_caption_style_section();

		parent::register_controls();

		$this->update_controls();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'       => esc_html__( 'Style', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => \Minimog_Woo::instance()->get_shop_loop_carousel_style_options(),
			'default'     => 'carousel-01',
			'render_type' => 'template',
		] );

		$this->add_control( 'caption_style', [
			'label'       => esc_html__( 'Caption Style', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => \Minimog_Woo::instance()->get_shop_loop_caption_style_options(),
			'default'     => '01',
			'render_type' => 'template',
		] );

		$this->add_control( 'show_price', [
			'label'        => esc_html__( 'Show Price', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'show_variation', [
			'label'        => esc_html__( 'Show Variation', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'show_category', [
			'label'        => esc_html__( 'Show Category', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_brand', [
			'label'        => esc_html__( 'Show Brand', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_rating', [
			'label'        => esc_html__( 'Show Rating', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_availability', [
			'label'        => esc_html__( 'Show Availability', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_stock_bar', [
			'label'        => esc_html__( 'Show Stock Bar', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
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

	private function add_thumbnail_style_section() {
		$this->start_controls_section( 'thumbnail_style_section', [
			'label' => esc_html__( 'Thumbnail', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'thumbnail_border_radius', [
			'label'     => esc_html__( 'Border Radius', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .thumbnail' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'thumbnail_height', [
			'label'          => esc_html__( 'Height', 'minimog' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => 'px',
			],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units'     => [ 'px', '%', 'vw' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
				'vw' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .post-thumbnail img' => 'height: {{SIZE}}{{UNIT}};',
			],
			'render_type'    => 'template',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'thumbnail_css_filters',
			'selector' => '{{WRAPPER}} .post-thumbnail img',
		] );

		$this->end_controls_section();
	}

	private function add_caption_style_section() {
		$this->start_controls_section( 'caption_style_section', [
			'label' => esc_html__( 'Product Caption', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'caption_text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .product-info' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'caption_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .product-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .product-info'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'caption_title_heading', [
			'label'     => esc_html__( 'Product Name', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_title_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .woocommerce-loop-product__title',
		] );

		$this->start_controls_tabs( 'caption_title_tabs' );

		$this->start_controls_tab( 'caption_title_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'caption_title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woocommerce-loop-product__title a' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_title_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'caption_title_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woocommerce-loop-product__title a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'caption_price_heading', [
			'label'     => esc_html__( 'Product Price', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'caption_price_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-product div.price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-product div.price'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_price_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .product-info .price, {{WRAPPER}} .product-info .amount',
		] );

		$this->start_controls_tabs( 'caption_price_tabs' );

		$this->start_controls_tab( 'caption_regular_price_tab', [
			'label' => esc_html__( 'Regular', 'minimog' ),
		] );

		$this->add_control( 'caption_regular_price_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .price'  => 'color: {{VALUE}};',
				'{{WRAPPER}} .product-info .amount' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_sale_price_tab', [
			'label' => esc_html__( 'Sale', 'minimog' ),
		] );

		$this->add_control( 'caption_sale_regular_price_color', [
			'label'     => esc_html__( 'Regular Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .price del'         => 'color: {{VALUE}};',
				'{{WRAPPER}} .price del .amount' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_sale_price_color', [
			'label'     => esc_html__( 'Sale Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product.sale ins'         => 'color: {{VALUE}};',
				'{{WRAPPER}} .product.sale ins .amount' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'caption_category_heading', [
			'label'     => esc_html__( 'Product Category', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_responsive_control( 'caption_category_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .loop-product-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .loop-product-category'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'show_category' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'caption_category_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .product-info .loop-product-category a',
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_control( 'caption_category_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .loop-product-category a' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_control( 'caption_category_hover_color', [
			'label'     => esc_html__( 'Hover Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .loop-product-category a:hover' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_control( 'caption_rating_heading', [
			'label'     => esc_html__( 'Product Rating', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_rating' => '1',
			],
		] );

		$this->add_control( 'caption_rating_star_fill_color', [
			'label'     => esc_html__( 'Star Fill Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating' => '--fill: {{VALUE}}; --half: {{VALUE}};',
			],
			'condition' => [
				'show_rating' => '1',
			],
		] );

		$this->add_control( 'caption_rating_star_empty_color', [
			'label'     => esc_html__( 'Star Empty Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating' => '--empty: {{VALUE}};',
			],
			'condition' => [
				'show_rating' => '1',
			],
		] );

		$this->add_control( 'caption_availability_heading', [
			'label'     => esc_html__( 'Product Availability', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_availability' => '1',
			],
		] );

		$this->add_responsive_control( 'caption_availability_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .loop-product-availability' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .loop-product-availability'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'show_availability' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'caption_availability_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .loop-product-availability',
			'condition' => [
				'show_availability' => '1',
			],
		] );

		$this->add_control( 'caption_availability_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .loop-product-availability' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_availability' => '1',
			],
		] );

		$this->end_controls_section();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'default'        => '5',
			'tablet_default' => '3',
			'mobile_default' => '2',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 20,
		] );
	}

	protected function print_slide( array $settings ) {
		minimog_get_wc_template_part( 'content-product', $settings['style'], [
			'settings' => $this->loop_settings,
		] );
	}
}
