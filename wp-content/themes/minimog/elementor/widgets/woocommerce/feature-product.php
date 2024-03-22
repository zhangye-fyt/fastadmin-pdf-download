<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || exit;

class Widget_Feature_Product extends Base {

	public function get_name() {
		return 'tm-feature-product';
	}

	public function get_title() {
		return esc_html__( 'Feature Product', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-image-rollover';
	}

	public function get_keywords() {
		return [ 'feature', 'product' ];
	}

	public function get_script_depends() {
		return [ 'minimog-featured-product' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_product_info_styling_section();

		$this->add_product_form_styling_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'       => esc_html__( 'Style', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'01' => '01',
				'02' => '02',
			],
			'default'     => '01',
			'render_type' => 'template',
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
				],
			],
		] );

		$this->add_control( 'show_thumbnail', [
			'label'        => esc_html__( 'Show Thumbnail', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
			'separator'    => 'before',
		] );

		$this->add_control( 'show_gallery', [
			'label'        => esc_html__( 'Show Gallery', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'condition'    => [
				'show_thumbnail' => '1',
			],
		] );

		$this->add_control( 'show_category', [
			'label'        => esc_html__( 'Show Category', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_rating', [
			'label'        => esc_html__( 'Show Rating', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'show_price', [
			'label'        => esc_html__( 'Show Price', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'show_excerpt', [
			'label'        => esc_html__( 'Show Short Description', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'show_low_stock', [
			'label'        => esc_html__( 'Show Stock Bar', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_form', [
			'label'        => esc_html__( 'Show Form', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'show_quantity', [
			'label'        => esc_html__( 'Show Quantity', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
			'condition'    => [
				'show_form' => '1',
			],
		] );

		$this->add_control( 'show_quantity_label', [
			'label'                => esc_html__( 'Show Quantity Label', 'minimog' ),
			'type'                 => Controls_Manager::SWITCHER,
			'return_value'         => '1',
			'default'              => '1',
			'selectors'            => [
				'{{WRAPPER}} .minimog-feature-product' => '--quantity-label-display: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'1' => 'block',
				''  => 'none',
			],
			'condition'            => [
				'show_form'     => '1',
				'show_quantity' => '1',
			],
		] );

		$this->add_control( 'show_add_to_cart_button', [
			'label'        => esc_html__( 'Show Add To Cart Button', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
			'condition'    => [
				'show_form' => '1',
			],
		] );

		$this->add_control( 'show_buy_now_button', [
			'label'        => esc_html__( 'Show Buy Now Button', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'condition'    => [
				'show_form' => '1',
			],
		] );

		$this->add_control( 'buy_now_text', [
			'label'       => esc_html__( 'Button Buy Now Text', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'placeholder' => esc_html__( 'Buy Now', 'minimog' ),
			'dynamic'     => [
				'active' => true,
			],
			'condition'   => [
				'show_form'           => '1',
				'show_buy_now_button' => '1',
			],
		] );

		$this->end_controls_section();
	}

	private function add_product_info_styling_section() {
		$this->start_controls_section( 'product_styling_section', [
			'label' => esc_html__( 'Product', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'content_alignment', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .minimog-feature-product' => '--content-alignment: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-feature-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-feature-product'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .minimog-feature-product',
		] );

		$this->add_control( 'box_bg_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-feature-product' => 'background-color: {{VALUE}};',
			],
		] );

		// Thumbnail
		$this->add_control( 'thumbnail_heading', [
			'label'     => esc_html__( 'Image', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_thumbnail' => '1',
			],
		] );

		$this->add_responsive_control( 'thumbnail_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .feature-product-thumbnail-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_thumbnail' => '1',
			],
		] );

		$this->add_control( 'gallery_heading', [
			'label'     => esc_html__( 'Gallery', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_gallery'   => '1',
				'show_thumbnail' => '1',
			],
		] );

		$this->add_responsive_control( 'gallery_width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'%' => [
					'min' => 40,
					'max' => 150,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-feature-product' => '--gallery-width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_gallery'   => '1',
				'show_thumbnail' => '1',
			],
		] );

		$this->add_responsive_control( 'gallery_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'%' => [
					'min' => 10,
					'max' => 60,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-feature-product' => '--gallery-spacing: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_gallery'   => '1',
				'show_thumbnail' => '1',
			],
		] );

		$this->add_responsive_control( 'gallery_radius', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => 'px',
			'selectors'  => [
				'{{WRAPPER}} .woo-single-gallery .minimog-thumbs-swiper .swiper-slide .swiper-thumbnail-wrap'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .woo-single-gallery .minimog-thumbs-swiper .swiper-slide .swiper-thumbnail-wrap img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'show_gallery'   => '1',
				'show_thumbnail' => '1',
			],
		] );

		$this->start_controls_tabs( 'gallery_border_color_tabs', [
			'condition' => [
				'show_gallery'   => '1',
				'show_thumbnail' => '1',
			],
		] );

		$this->start_controls_tab( 'gallery_border_color_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'gallery_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woo-single-gallery .minimog-thumbs-swiper .swiper-slide .swiper-thumbnail-wrap' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'gallery_border_color_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'gallery_border_color_hover', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woo-single-gallery .minimog-thumbs-swiper .swiper-slide.swiper-slide-thumb-active .swiper-thumbnail-wrap' => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .woo-single-gallery .minimog-thumbs-swiper .swiper-slide:hover .swiper-thumbnail-wrap'                     => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Title
		$this->add_control( 'title_heading', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .product-title',
		] );

		$this->start_controls_tabs( 'title_style_tabs' );

		$this->start_controls_tab( 'title_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-title a' => 'color: {{VALUE}} !important;',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'title_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-title:hover a' => 'color: {{VALUE}} !important;',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Cat
		$this->add_control( 'category_heading', [
			'label'     => esc_html__( 'Category', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_responsive_control( 'cat_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .product-category' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_category' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'category_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .product-category a',
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->start_controls_tabs( 'cat_style_tabs', [
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->start_controls_tab( 'cat_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'cat_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-category a' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'cat_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'cat_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-category a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Star Rating
		$this->add_control( 'star_rating_heading', [
			'label'     => esc_html__( 'Rating', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_rating' => '1',
			],
		] );

		// $this->add_responsive_control( 'star_size', [
		// 	'label'     => esc_html__( 'Size', 'minimog' ),
		// 	'type'      => Controls_Manager::SLIDER,
		// 	'range'     => [
		// 		'px' => [
		// 			'min' => 0,
		// 			'max' => 100,
		// 		],
		// 	],
		// 	'selectors' => [
		// 		'{{WRAPPER}} .tm-star-rating' => '--size: {{SIZE}}{{UNIT}};',
		// 	],
		// 	'condition' => [
		// 		'show_rating' => '1',
		// 	],
		// ] );

		$this->add_control( 'star_full_color', [
			'label'     => esc_html__( 'Fill', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating' => '--fill: {{VALUE}}; --half: {{VALUE}};',
			],
			'condition' => [
				'show_rating' => '1',
			],
		] );

		$this->add_control( 'star_empty_color', [
			'label'     => esc_html__( 'Empty', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating' => '--empty: {{VALUE}};',
			],
			'condition' => [
				'show_rating' => '1',
			],
		] );

		$this->add_responsive_control( 'star_spacing', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .product-rating' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'show_rating' => '1',
			],
		] );

		// Price
		$this->add_control( 'price_heading', [
			'label'     => esc_html__( 'Price', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_price' => '1',
			],
		] );

		$this->add_responsive_control( 'price_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .entry-price-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_price' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'price',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .entry-price-wrap .price, {{WRAPPER}} .entry-price-wrap .price .amount',
			'condition' => [
				'show_price' => '1',
			],
		] );

		$this->add_control( 'regular_price_color', [
			'label'     => esc_html__( 'Regular Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .price del'         => 'color: {{VALUE}};',
				'{{WRAPPER}} .price del .amount' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_price' => '1',
			],
		] );

		$this->add_control( 'sale_price_color', [
			'label'     => esc_html__( 'Sale Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .price'         => 'color: {{VALUE}};',
				'{{WRAPPER}} .price .amount' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_price' => '1',
			],
		] );

		// Excerpt
		$this->add_control( 'excerpt_heading', [
			'label'     => esc_html__( 'Excerpt', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_excerpt' => '1',
			],
		] );

		$this->add_responsive_control( 'excerpt_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .woocommerce-product-details__short-description' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_excerpt' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'excerpt_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .woocommerce-product-details__short-description',
			'condition' => [
				'show_excerpt' => '1',
			],
		] );

		$this->add_control( 'excerpt_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_excerpt' => '1',
			],
		] );

		// Low Stock
		$this->add_control( 'low_stock_heading', [
			'label'     => esc_html__( 'Stock bar', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_low_stock' => '1',
			],
		] );

		$this->add_responsive_control( 'low_stock_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .entry-product-low-stock' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .entry-product-low-stock'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'show_low_stock' => '1',
			],
		] );

		$this->add_responsive_control( 'low_stock_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'%' => [
					'min' => 1,
					'max' => 20,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-progress' => '--bar-height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_low_stock' => '1',
			],
		] );

		$this->add_control( 'low_stock_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'%' => [
					'min' => 1,
					'max' => 20,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-progress' => '--bar-rounded: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_low_stock' => '1',
			],
		] );

		$this->add_control( 'low_stock_primary_color', [
			'label'     => esc_html__( 'Bar Primary Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-progress' => '--bar-color: {{VALUE}};',
			],
			'condition' => [
				'show_low_stock' => '1',
			],
		] );

		$this->add_control( 'low_stock_secondary_color', [
			'label'     => esc_html__( 'Bar Secondary Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-progress' => '--fill-color: {{VALUE}};',
			],
			'condition' => [
				'show_low_stock' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'low_stock_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .product-availability',
			'condition' => [
				'show_low_stock' => '1',
			],
		] );

		$this->add_control( 'low_stock_text_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .product-availability' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_low_stock' => '1',
			],
		] );

		$this->add_responsive_control( 'low_stock_text_spacing', [
			'label'      => esc_html__( 'Text Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'%' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .entry-product-low-stock .text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_low_stock' => '1',
			],
		] );

		$this->end_controls_section();
	}

	private function add_product_form_styling_section() {
		$this->start_controls_section( 'product_form_styling_section', [
			'label' => esc_html__( 'Product Form', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'form_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .form-wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		// Button
		$this->add_control( 'form_button_heading', [
			'label'     => esc_html__( 'Add to cart', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'add_to_cart_button_min_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 300,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .single_add_to_cart_button' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'add_to_cart_button',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .single_add_to_cart_button',
		] );

		$this->start_controls_tabs( 'form_button_skin_tabs' );

		$this->start_controls_tab( 'form_button_skin_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'form_button_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button' => '--minimog-color-button-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'form_button_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button' => '--minimog-color-button-background: {{VALUE}};',
			],
		] );

		$this->add_control( 'form_button_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button' => '--minimog-color-button-border: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'form_button_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'form_button_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button:hover' => '--minimog-color-button-hover-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'form_button_hover_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button:hover' => '--minimog-color-button-hover-background: {{VALUE}};',
			],
		] );

		$this->add_control( 'form_button_hover_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .single_add_to_cart_button:hover' => '--minimog-color-button-hover-border: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Quantity
		$this->add_control( 'quantity_heading', [
			'label'     => esc_html__( 'Quantity', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_quantity' => '1',
			],
		] );

		$this->add_responsive_control( 'quantity_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} div.quantity' => '--size: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_quantity' => '1',
			],
		] );

		$this->add_responsive_control( 'quantity_border_width', [
			'label'      => esc_html__( 'Border Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 20,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} div.quantity input.qty' => 'border-width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_quantity' => '1',
			],
		] );

		$this->add_control( 'quantity_label_color', [
			'label'     => esc_html__( 'Label Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .quantity-button-wrapper label' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_quantity' => '1',
			],
		] );

		$this->add_control( 'quantity_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} div.quantity input.qty'     => 'color: {{VALUE}};',
				'{{WRAPPER}} div.quantity button:before' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_quantity' => '1',
			],
		] );

		$this->add_control( 'quantity_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} div.quantity input.qty' => 'background-color: {{VALUE}};',
			],
			'condition' => [
				'show_quantity' => '1',
			],
		] );

		$this->add_control( 'quantity_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} div.quantity input.qty' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'show_quantity' => '1',
			],
		] );

		// Buy Now.
		$this->add_control( 'buy_now_button_heading', [
			'label'     => esc_html__( 'Buy Now', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'buy_now_button_min_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 300,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm_buy_now_button' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'buy_now_button',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm_buy_now_button',
		] );

		$this->start_controls_tabs( 'buy_now_button_skin_tabs' );

		$this->start_controls_tab( 'buy_now_button_skin_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'buy_now_button_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm_buy_now_button' => '--minimog-color-button-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'buy_now_button_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm_buy_now_button' => '--minimog-color-button-background: {{VALUE}};',
			],
		] );

		$this->add_control( 'buy_now_button_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm_buy_now_button' => '--minimog-color-button-border: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'buy_now_button_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'buy_now_button_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm_buy_now_button:hover' => '--minimog-color-button-hover-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'buy_now_button_hover_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm_buy_now_button:hover' => '--minimog-color-button-hover-background: {{VALUE}};',
			],
		] );

		$this->add_control( 'buy_now_button_hover_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm_buy_now_button:hover' => '--minimog-color-button-hover-border: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Cart form inputs.
		$this->add_control( 'cart_form_styling_heading', [
			'label'     => esc_html__( 'Form Styling', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'cart_form_min_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 70,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .variations_form' => '--minimog-form-input-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'cart_form',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .variations_form select',
		] );

		$this->start_controls_tabs( 'cart_form_colors_tabs' );

		$this->start_controls_tab( 'cart_form_colors_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'cart_form_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .variations_form' => '--minimog-color-form-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'cart_form_background', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .variations_form' => '--minimog-color-form-background: {{VALUE}};',
			],
		] );

		$this->add_control( 'cart_form_border', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .variations_form' => '--minimog-color-form-border: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'cart_form_colors_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'cart_form_focus_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .variations_form' => '--minimog-color-form-focus-text: {{VALUE}};',
			],
		] );

		$this->add_control( 'cart_form_focus_background', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .variations_form' => '--minimog-color-form-focus-background: {{VALUE}};',
			],
		] );

		$this->add_control( 'cart_form_focus_border', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .variations_form' => '--minimog-color-form-focus-border: {{VALUE}};',
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
		 * @var \WC_Product $product
		 */
		$this_product = wc_get_product( $settings['product_id'] );

		if ( empty( $this_product ) ) {
			return;
		}

		global $product;

		$clone_product = $product;
		$product       = $this_product;

		$classes = [
			'minimog-feature-product',
			'single-product',
			'minimog-box',
			'minimog-feature-product--style-' . $settings['style'],
		];

		if ( '1' !== $settings['show_gallery'] ) {
			$classes[] = 'no-gallery';
		}

		$this->add_render_attribute( 'wrapper', 'class', $classes );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php $this->print_product_thumbnail( $product ); ?>

			<div class="feature-product--content">
				<div class="feature-product--info">
					<?php $this->print_product_category( $product ); ?>

					<h3 class="product-title">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>"
						   class="link-in-title"><?php echo esc_html( $product->get_name() ); ?></a>
					</h3>

					<?php
					$this->print_product_rating( $product );
					$this->print_product_price( $product );
					$this->print_product_excerpt( $product );
					$this->print_low_stock( $product );
					?>
				</div>

				<?php $this->print_form( $product ); ?>
			</div>
		</div>
		<?php
		// Reset global product.
		$product = $clone_product;
	}

	protected function print_product_thumbnail( \WC_Product $product ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['show_thumbnail'] ) ) {
			return;
		}

		$product_id    = $product->get_id();
		$feature_style = 'slider';

		$wrapper_classes = [
			'woo-single-gallery',
			'feature-style-' . $feature_style,
		];

		$attachment_ids = $product->get_gallery_image_ids();
		$thumbnail_id   = 0;

		if ( has_post_thumbnail( $product_id ) ) {
			$thumbnail_id = (int) get_post_thumbnail_id( $product_id );
			array_unshift( $attachment_ids, $thumbnail_id );
		}

		if ( empty( $attachment_ids ) ) {
			return;
		}

		$thumb_image_size = \Minimog_Woo::instance()->get_single_product_image_size( 100 );
		$main_image_size  = \Minimog_Woo::instance()->get_single_product_image_size( 570 );

		$open_gallery = $is_quick_view = false;

		echo '<div class="feature-product-thumbnail-wrap">';
		echo '<div class="woo-single-images">';

		wc_get_template( "single-product/product-image-{$feature_style}.php", [
			'thumbnail_id'     => $thumbnail_id,
			'attachment_ids'   => $attachment_ids,
			'is_quick_view'    => $is_quick_view,
			'wrapper_classes'  => implode( ' ', $wrapper_classes ),
			'main_image_size'  => $main_image_size,
			'thumb_image_size' => $thumb_image_size,
			'open_gallery'     => $open_gallery,
			'show_gallery'     => $settings['show_gallery'],
			'vertical_slider'  => '1',
		] );

		echo '</div>';
		echo '</div>';
	}

	protected function print_product_category( \WC_Product $product ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['show_category'] ) ) {
			return;
		}

		$cats = $product->get_category_ids();
		if ( empty( $cats ) ) {
			return;
		}

		$first_cat = $cats[0];
		$cat       = get_term_by( 'id', $first_cat, 'product_cat' );

		if ( ! $cat instanceof \WP_Term ) {
			return;
		}

		$link = get_term_link( $cat );
		?>
		<div class="product-category">
			<a href="<?php echo esc_url( $link ) ?>"><?php echo esc_html( $cat->name ); ?></a>
		</div>
		<?php
	}

	protected function print_product_rating( \WC_Product $product ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['show_rating'] ) ) {
			return;
		}

		if ( ! wc_review_ratings_enabled() ) {
			return;
		}

		$average_rating = $product->get_average_rating();

		if ( 0 >= $average_rating ) {
			return;
		}

		$review_count = $product->get_review_count();

		$rating_text = sprintf( esc_html( _n( '%s review', '%s reviews', $review_count, 'minimog' ) ), '<span class="count">' . esc_html( $review_count ) . '</span>' );
		?>
		<div class="product-rating">
			<?php \Minimog_Templates::render_rating( $average_rating ); ?>
			<div class="review-count"><?php echo '' . $rating_text; ?></div>
		</div>
		<?php
	}

	protected function print_product_excerpt( \WC_Product $product ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['show_excerpt'] ) ) {
			return;
		}
		?>
		<div class="woocommerce-product-details__short-description">
			<?php echo '' . $product->get_short_description(); ?>
		</div>
		<?php
	}

	protected function print_product_price( \WC_Product $product ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['show_price'] ) ) {
			return;
		}

		?>
		<div class="entry-price-wrap">
			<?php echo '' . $product->get_price_html(); ?>
		</div>
		<?php
	}

	protected function print_form( \WC_Product $product ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['show_form'] ) ) {
			return;
		}

		$product_type = $product->get_type();

		if ( ! $product->is_in_stock() && 'simple' === $product_type ) {
			return;
		}

		$this->add_render_attribute( 'form_wrapper', [
			'class' => 'form-wrapper',
		] );

		$this->add_render_attribute( 'form', [
			'class'   => 'cart',
			'action'  => $product->get_permalink(),
			'method'  => 'post',
			'enctype' => 'multipart/form-data',
		] );

		$this->add_render_attribute( 'button_wrapper', [
			'class' => 'entry-product-quantity-wrapper',
		] );

		$this->add_render_attribute( 'button', [
			'type'  => 'submit',
			'value' => $product->get_id(),
			'class' => 'single_add_to_cart_button ajax_add_to_cart button alt',
		] );

		if ( 'variable' === $product_type ) {
			$available_variations = $product->get_available_variations();
			$variations_attr      = htmlspecialchars( wp_json_encode( $available_variations ) );

			$this->add_render_attribute( 'form_wrapper', [
				'class' => 'minimog-variation-select-wrap',
			] );

			$this->add_render_attribute( 'form', [
				'class'                   => 'variations_form',
				'data-product_id'         => absint( $product->get_id() ),
				'data-product_variations' => $variations_attr,
			] );

			$this->add_render_attribute( 'button_wrapper', [
				'class' => 'woocommerce-variation-add-to-cart variations_button',
			] );
		} else {
			$this->add_render_attribute( 'button', [
				'name' => 'add-to-cart',
			] );
		}

		?>
		<div <?php $this->print_render_attribute_string( 'form_wrapper' ); ?>>
			<form <?php $this->print_render_attribute_string( 'form' ); ?>>

				<?php $this->print_product_variations( $product ); ?>

				<?php if ( 'variable' === $product_type ) : ?>
					<div class="woocommerce-variation single_variation"></div>
				<?php endif; ?>

				<div <?php $this->print_render_attribute_string( 'button_wrapper' ); ?>>
					<?php
					if ( '1' === $settings['show_quantity'] ) {
						do_action( 'woocommerce_before_add_to_cart_quantity' );

						\Minimog_Woo::instance()->output_add_to_cart_quantity_html( array(
							'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
							'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
							'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
						) );

						do_action( 'woocommerce_after_add_to_cart_quantity' );
					} else {
						echo '<input type="hidden" class="input-text qty text" id="' . esc_attr( uniqid( 'quantity_' ) ) . '" name="quantity" value="1" />';
					}

					?>
					<?php if ( '1' === $settings['show_add_to_cart_button'] ) : ?>
						<button <?php $this->print_render_attribute_string( 'button' ); ?>>
							<span><?php echo esc_html( $product->single_add_to_cart_text() ); ?></span>
						</button>
					<?php endif; ?>

					<?php $this->print_buy_now_button( $product ) ?>
				</div>
				<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>"/>
				<?php if ( 'variable' === $product_type ) : ?>
					<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>"/>
					<input type="hidden" name="variation_id" class="variation_id" value="0"/>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}

	protected function print_product_variations( \WC_Product $product ) {
		if ( 'variable' !== $product->get_type() ) {
			return;
		}

		\Minimog_Woo::instance()->get_product_variation_dropdown_html( $product, [
			'show_label' => false,
			'show_price' => true,
		] );

		$attributes = $product->get_variation_attributes();

		if ( ! empty( $attributes ) ) {
			$total_attrs = count( $attributes );
			$loop_count  = 0;

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
						$selected = isset( $_REQUEST[ $attr ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ $attr ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
						wc_dropdown_variation_attribute_options( array(
							'options'          => $options,
							'attribute'        => $attribute_name,
							'product'          => $product,
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
		}
	}

	protected function print_buy_now_button( \WC_Product $product ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['show_buy_now_button'] ) || ! in_array( $product->get_type(), [
				'simple',
				'variable',
			], true ) ) {
			return;
		}

		$button_text = ! empty( $settings['buy_now_text'] ) ? $settings['buy_now_text'] : __( 'Buy Now', 'minimog' );

		$button_class = 'single_add_to_cart_button ajax_add_to_cart button alt tm_buy_now_button';

		if ( '1' === $settings['show_add_to_cart_button'] ) {
			$button_class .= ' w-100';
		}
		?>
		<button type="submit" name="minimog-buy-now" value="<?php echo esc_attr( $product->get_id() ); ?>"
		        class="<?php echo esc_attr( $button_class ); ?>"
		        data-redirect="<?php echo esc_url( wc_get_checkout_url() ); ?>">
			<span><?php echo esc_html( $button_text ); ?></span></button>
		<?php
	}

	protected function print_low_stock( $product ) {
		$settings = $this->get_settings_for_display();

		if ( '1' !== $settings['show_low_stock'] ) {
			return;
		}

		if ( $product->managing_stock() && $product->is_in_stock() ) {
			$stock_amount     = $product->get_stock_quantity();
			$low_stock_amount = wc_get_low_stock_amount( $product );
			$stock_percent    = \Minimog_Helper::calculate_percentage( $stock_amount, $low_stock_amount );

			if ( $stock_amount > 0 && $stock_amount <= $low_stock_amount ) {
				?>
				<div class="entry-product-low-stock">
					<div class="text product-availability">
						<?php printf( esc_html( _n( 'Only %s item left in stock!', 'Only %s items left in stock!', $stock_amount, 'minimog' ) ), '<span class="value">' . $stock_amount . '</span>' ); ?>
					</div>
					<div class="minimog-progress">
						<div class="progress-bar-wrap">
							<div class="progress-bar"
							     role="progressbar"
							     aria-label="<?php esc_attr_e( 'Low stock bar', 'minimog' ); ?>"
							     style="<?php echo esc_attr( "width: {$stock_percent}%" ); ?>"
							     aria-valuenow="<?php echo esc_attr( $stock_percent ); ?>" aria-valuemin="0"
							     aria-valuemax="100"></div>
						</div>
					</div>
				</div>
				<?php
			}
		} elseif ( ! $product->is_in_stock() ) {
			?>
			<div class="entry-product-low-stock">
				<div
					class="text product-availability out-of-stock"><?php esc_html_e( 'Out of stock', 'minimog' ); ?></div>
			</div>
			<?php
		}
	}
}
