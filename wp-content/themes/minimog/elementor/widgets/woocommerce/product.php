<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || exit;

class Widget_Product extends Posts_Base {

	public function get_name() {
		return 'tm-product';
	}

	public function get_title() {
		return esc_html__( 'Products', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-products';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product', 'products', 'shop', 'catalog' ];
	}

	public function get_script_depends() {
		return [
			'minimog-grid-query',
			'minimog-widget-grid-post',
		];
	}

	protected function get_post_category() {
		return 'product_cat';
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_grid_options_section();

		$this->add_thumbnail_style_section();

		$this->add_caption_style_section();

		$this->add_pagination_section();

		$this->add_pagination_style_section();

		parent::register_controls();

		$this->add_banner_section();
		$this->add_banner_style_section();
		$this->register_common_button_style_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'       => esc_html__( 'Style', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => \Minimog_Woo::instance()->get_shop_loop_style_options(),
			'default'     => 'grid-01',
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

	private function add_grid_options_section() {
		$this->start_controls_section( 'grid_options_section', [
			'label' => esc_html__( 'Grid Options', 'minimog' ),
		] );

		$this->add_responsive_control( 'grid_columns', [
			'label'          => esc_html__( 'Columns', 'minimog' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 12,
			'step'           => 1,
			'default'        => 3,
			'tablet_default' => 2,
			'mobile_default' => 1,
			'selectors'      => [
				'{{WRAPPER}} .modern-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
			],
		] );

		$this->add_responsive_control( 'grid_column_gutter', [
			'label'     => esc_html__( 'Column Gutter', 'minimog' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0,
			'max'       => 200,
			'step'      => 1,
			'default'   => 30,
			'selectors' => [
				'{{WRAPPER}} .modern-grid' => 'grid-column-gap: {{VALUE}}px;',
			],
		] );

		$this->add_responsive_control( 'grid_row_gutter', [
			'label'     => esc_html__( 'Row Gutter', 'minimog' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0,
			'max'       => 200,
			'step'      => 1,
			'default'   => 30,
			'selectors' => [
				'{{WRAPPER}} .modern-grid' => 'grid-row-gap: {{VALUE}}px;',
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

	private function add_banner_section() {
		$this->start_controls_section( 'banner_section', [
			'label' => esc_html__( 'Banner', 'minimog' ),

		] );

		$this->add_control( 'prepend_banner', [
			'label'       => __( 'Banner', 'minimog' ),
			'description' => esc_html__( 'Add a banner as the the first item', 'minimog' ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => '',
		] );

		$condition = [
			'prepend_banner' => 'yes',
		];

		$this->add_responsive_control( 'product_banner_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
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
				'{{WRAPPER}} .minimog-banner' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'banner_columns', [
			'label'          => esc_html__( 'Columns', 'minimog' ),
			'description'    => esc_html__( 'How many columns that you want to be covered by banner? It must not be greater than Grid columns', 'minimog' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 11,
			'step'           => 1,
			'default'        => 3,
			'tablet_default' => 2,
			'mobile_default' => 1,
			'selectors'      => [
				'{{WRAPPER}} .minimog-banner' => 'grid-column-end: calc( {{VALUE}} + 1 );',
			],
			'condition'      => $condition,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'background_box',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} .minimog-image .image',
			'condition' => $condition,
		] );

		$this->add_control( 'banner_link', [
			'label'       => esc_html__( 'Link', 'minimog' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'minimog' ),
			'separator'   => 'before',
			'condition'   => $condition,
		] );

		$this->add_control( 'banner_link_click', [
			'label'     => esc_html__( 'Apply Link On', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'box'    => esc_html__( 'Whole Box', 'minimog' ),
				'button' => esc_html__( 'Button Only', 'minimog' ),
			],
			'default'   => 'box',
			'condition' => [
				'prepend_banner'    => 'yes',
				'banner_link[url]!' => '',
			],
		] );

		$this->add_control( 'sub_title_text', [
			'label'       => esc_html__( 'Sub Title', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'placeholder' => esc_html__( 'Enter your sub-title', 'minimog' ),
			'label_block' => true,
			'condition'   => $condition,
		] );

		$this->add_control( 'title_text', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'This is the heading', 'minimog' ),
			'placeholder' => esc_html__( 'Enter your title', 'minimog' ),
			'label_block' => true,
			'condition'   => $condition,
		] );

		$this->add_control( 'title_size', [
			'label'     => esc_html__( 'Title HTML Tag', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p',
			],
			'default'   => 'h3',
			'condition' => $condition,
		] );

		$this->add_control( 'sub_title_size', [
			'label'     => esc_html__( 'Sub Title HTML Tag', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p',
			],
			'default'   => 'h4',
			'condition' => $condition,
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'minimog' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		$this->add_group_control( Group_Control_Button::get_type(), [
			'name'           => 'banner_button',
			// Use box link instead of.
			'exclude'        => [
				'link',
			],
			'fields_options' => [
				'style' => [
					'default' => 'flat',
				],
				'text'  => [
					'default' => esc_html__( 'Shop Now', 'minimog' ),
				],
			],
			'condition'      => $condition,
		] );

		$this->end_controls_section();
	}

	private function add_banner_style_section() {
		$this->start_controls_section( 'banner_style_section', [
			'label'     => esc_html__( 'Banner', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'prepend_banner' => 'yes',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-box'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_radius', [
			'label'      => esc_html__( 'Border box radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => 'px',
			'selectors'  => [
				'{{WRAPPER}} .minimog-box .minimog-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align_full(),
			'selectors'            => [
				'{{WRAPPER}} .minimog-box' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'box_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .minimog-box' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'box_vertical_alignment', [
			'label'                => esc_html__( 'Vertical Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_vertical_alignment(),
			'toggle'               => false,
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .minimog-box' => 'align-items: {{VALUE}}',
			],
		] );

		// Banner content
		$this->add_control( 'heading_content', [
			'label'     => esc_html__( 'Content', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'box_content_max_width', [
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
				'{{WRAPPER}} .content-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'heading_sub_title', [
			'label'     => esc_html__( 'Sub Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'sub_title_typography',
			'selector' => '{{WRAPPER}} .banner-sub-title',
		] );

		$this->add_control( 'sub_title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .banner-sub-title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'sub_title_margin_bottom', [
			'label'      => esc_html__( 'Margin Bottom', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 500,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .banner-sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'heading_title', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'selector' => '{{WRAPPER}} .banner-title',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .banner-title' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * Register common button style controls.
	 */
	protected function register_common_button_style_section() {
		$this->start_controls_section( 'button_style_section', [
			'label'     => esc_html__( 'Button Banner', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'prepend_banner' => 'yes',
			],
		] );

		$icon_condition = [
			'banner_button_icon[value]!' => '',
		];

		$line_condition = [
			'banner_button_style' => [ 'bottom-line', 'bottom-thick-line' ],
		];

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
				'{{WRAPPER}} .tm-button' => 'min-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_min_height', [
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
				'{{WRAPPER}} .tm-button' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button-wrapper'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-text'                                      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-flat'                                      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-border'                                    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper'       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-text'                                            => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-flat'                                            => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-border'                                          => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper'             => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'button_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'banner_button_style' => [ 'flat', 'border' ],
			],
		] );

		$this->add_control( 'button_border_width', [
			'label'      => esc_html__( 'Border Width', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'banner_button_style' => [ 'flat', 'border' ],
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'button_text',
			'selector' => '{{WRAPPER}} .tm-button',
		] );

		$this->start_controls_tabs( 'button_skin_tabs', [
			'label' => esc_html__( 'Skin', 'minimog' ),
		] );

		$this->start_controls_tab( 'button_skin_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		/**
		 * Button wrapper style.
		 * Background working only with style: flat, border.
		 */

		$this->add_control( 'button_wrapper_color_normal_heading', [
			'label'   => esc_html__( 'Wrapper', 'minimog' ),
			'type'    => Controls_Manager::HEADING,
			'classes' => 'control-heading-in-tabs',
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'button_background',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} .tm-button',
			'condition' => [
				'banner_button_style' => [ 'flat', 'border' ],
			],
		] );

		$this->add_control( 'button_border_color', [
			'label'     => esc_html__( 'Border', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'banner_button_style!' => [ 'flat', 'bottom-line', 'bottom-thick-line' ],
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'button_box_shadow',
			'selector' => '{{WRAPPER}} .tm-button',
		] );

		/**
		 * Text Color
		 */
		$this->add_control( 'button_text_color_normal_heading', [
			'label'     => esc_html__( 'Text', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'button_text',
			'selector' => '{{WRAPPER}} .tm-button',
		] );

		/**
		 * Icon Color
		 */
		$this->add_control( 'button_icon_color_normal_heading', [
			'label'     => esc_html__( 'Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
			'condition' => $icon_condition,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => 'button_icon',
			'selector'  => '{{WRAPPER}} .tm-button .button-icon',
			'condition' => $icon_condition,
		] );

		/**
		 * Line Color
		 */
		$this->add_control( 'button_line_color_normal_heading', [
			'label'     => esc_html__( 'Line', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
			'condition' => $line_condition,
		] );

		$this->add_control( 'button_line_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper:before'       => 'background: {{VALUE}};',
				'{{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper:before' => 'background: {{VALUE}};',
			],
			'condition' => $line_condition,
		] );

		$this->add_control( 'button_line_winding_color', [
			'label'     => esc_html__( 'Line Winding', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line-winding .button-content-wrapper .line-winding svg path' => 'fill: {{VALUE}};',
			],
			'condition' => [
				'banner_button_style' => [ 'bottom-line-winding' ],
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		/**
		 * Button wrapper style.
		 * Background working only with style: flat, border.
		 */

		$this->add_control( 'button_wrapper_color_hover_heading', [
			'label'   => esc_html__( 'Wrapper', 'minimog' ),
			'type'    => Controls_Manager::HEADING,
			'classes' => 'control-heading-in-tabs',
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'hover_button_background',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} .tm-button:hover',
			'condition' => [
				'banner_button_style' => [ 'flat', 'border' ],
			],
		] );

		$this->add_control( 'hover_button_border_color', [
			'label'     => esc_html__( 'Border', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button:hover' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'banner_button_style!' => [ 'flat', 'bottom-line', 'bottom-thick-line' ],
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'hover_button_box_shadow',
			'selector' => '{{WRAPPER}} .minimog-box:hover div.tm-button, {{WRAPPER}} a.tm-button:hover',
		] );

		/**
		 * Text Color
		 */
		$this->add_control( 'button_text_color_hover_heading', [
			'label'     => esc_html__( 'Text', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_button_text',
			'selector' => '{{WRAPPER}} .minimog-box:hover div.tm-button , {{WRAPPER}} a.tm-button:hover',
		] );

		/**
		 * Icon Color
		 */
		$this->add_control( 'button_icon_color_hover_heading', [
			'label'     => esc_html__( 'Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
			'condition' => $icon_condition,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => 'hover_button_icon',
			'selector'  => '{{WRAPPER}} .minimog-box:hover div.tm-button .button-icon, {{WRAPPER}} a.tm-button:hover .button-icon',
			'condition' => $icon_condition,
		] );

		/**
		 * Line Color
		 */
		$this->add_control( 'button_line_color_hover_heading', [
			'label'     => esc_html__( 'Line', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
			'condition' => $line_condition,
		] );

		$this->add_control( 'hover_button_line_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper:after'       => 'background: {{VALUE}};',
				'{{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper:after' => 'background: {{VALUE}};',
			],
			'condition' => $line_condition,
		] );

		$this->add_control( 'hover_button_line_winding_color', [
			'label'     => esc_html__( 'Line Winding', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line-winding:hover .button-content-wrapper .line-winding svg path' => 'fill: {{VALUE}};',
			],
			'condition' => [
				'banner_button_style' => [ 'bottom-line-winding' ],
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		/**
		 * Button icon style
		 */
		$this->add_control( 'button_icon_style_heading', [
			'label'     => esc_html__( 'Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $icon_condition,
		] );

		$this->add_responsive_control( 'button_icon_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-button .button-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button .button-icon'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => $icon_condition,
		] );

		$this->add_responsive_control( 'button_icon_font_size', [
			'label'     => esc_html__( 'Font Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 8,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-button .button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
			'condition' => $icon_condition,
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->query_posts();
		/**
		 * @var $query \WP_Query
		 */
		$query     = $this->get_query();
		$post_type = $this->get_post_type();

		$style = ! empty( $settings['style'] ) ? $settings['style'] : 'grid-01';

		$this->add_render_attribute( 'wrapper', 'class', str_replace( 'grid-', 'group-style-', $style ) );
		$this->add_render_attribute( 'wrapper', 'class', 'minimog-grid-wrapper minimog-product style-' . $style );

		if ( $settings['pagination_type'] !== '' && $query->found_posts > $settings['query_number'] ) {
			$this->add_render_attribute( 'wrapper', 'data-pagination', $settings['pagination_type'] );
		}

		if ( $settings['pagination_custom_button_id'] !== '' ) {
			$this->add_render_attribute( 'wrapper', 'data-pagination-custom-button-id', $settings['pagination_custom_button_id'] );
		}

		$caption_style = ! empty( $settings['caption_style'] ) ? $settings['caption_style'] : '01';
		$this->add_render_attribute( 'wrapper', 'class', 'caption-style-' . $caption_style );

		$loop_settings = [
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
			$loop_settings['thumbnail_size'] = \Minimog_Image::elementor_parse_image_size( $settings );
		}
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php if ( $query->have_posts() ) : ?>

				<?php
				$minimog_grid_query['source']        = $settings['query_source'];
				$minimog_grid_query['action']        = "{$post_type}_infinite_load";
				$minimog_grid_query['max_num_pages'] = $query->max_num_pages;
				$minimog_grid_query['found_posts']   = $query->found_posts;
				$minimog_grid_query['count']         = $query->post_count;
				$minimog_grid_query['query_vars']    = $this->get_query_args();
				$minimog_grid_query['settings']      = $loop_settings;
				$minimog_grid_query                  = htmlspecialchars( wp_json_encode( $minimog_grid_query ) );
				?>
				<input type="hidden"
				       class="minimog-query-input" <?php echo 'value="' . $minimog_grid_query . '"'; ?>/>

				<div class="minimog-grid modern-grid">
					<?php $this->print_banner(); ?>

					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php
						minimog_get_wc_template_part( 'content-product', $style, [
							'settings' => $loop_settings,
						] );
						?>
					<?php endwhile; ?>
				</div>

				<?php $this->print_pagination( $query, $settings ); ?>

				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function get_post_type() {
		return 'product';
	}

	protected function print_banner() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' != $settings['prepend_banner'] ) {
			return;
		}

		$classes = [
			'minimog-banner',
			'minimog-box',
			'grid-item',
			'product',
			'minimog-product-grid-banner',
		];

		$this->add_render_attribute( 'banner', 'class', $classes );

		$box_tag = 'div';
		if ( ! empty( $settings['banner_link']['url'] ) && 'box' === $settings['banner_link_click'] ) {
			$box_tag = 'a';

			$this->add_render_attribute( 'banner', 'class', 'link-secret' );
			$this->add_link_attributes( 'banner', $settings['banner_link'] );
		}

		printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( 'banner' ) ); ?>
		<div class="minimog-image">
			<div class="image"></div>
		</div>
		<div class="content-wrap">
			<!-- Sub Title -->
			<?php
			if ( ! empty( $settings['sub_title_text'] ) ) {
				printf( '<%1$s class="sub-title banner-sub-title">%2$s</%1$s>', $settings['sub_title_size'], $settings['sub_title_text'] );
			}
			?>

			<!-- Title -->
			<?php
			if ( ! empty( $settings['title_text'] ) ) {
				printf( '<%1$s class="title banner-title">%2$s</%1$s>', $settings['title_size'], $settings['title_text'] );
			}
			?>

			<!-- Button -->
			<?php $this->print_banner_button(); ?>
		</div>
		<?php printf( '</%1$s>', $box_tag ); ?>
		<?php
	}

	private function print_banner_button() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['banner_button_text'] ) && empty( $settings['banner_button_icon']['value'] ) ) {
			return;
		}

		$this->add_render_attribute( 'banner_button', 'class', 'tm-button style-' . $settings['banner_button_style'] );

		if ( ! empty( $settings['banner_button_size'] ) ) {
			$this->add_render_attribute( 'banner_button', 'class', 'tm-button-' . $settings['banner_button_size'] );
		}

		$button_tag = 'a';

		if ( ! empty( $settings['banner_button_link'] ) ) {
			$this->add_link_attributes( 'banner_button', $settings['banner_button_link'] );
		} else {
			$button_tag = 'div';

			if ( ! empty( $settings['banner_link'] ) && ! empty( $settings['link_click'] ) && 'button' === $settings['banner_link_click'] ) {
				$button_tag = 'a';
				$this->add_link_attributes( 'banner_button', $settings['link'] );
			}
		}

		$has_icon = false;

		if ( ! empty( $settings['banner_button_icon']['value'] ) ) {
			$has_icon = true;
			$is_svg   = isset( $settings['banner_button_icon']['library'] ) && 'svg' === $settings['banner_button_icon']['library'] ? true : false;

			$this->add_render_attribute( 'banner_button', 'class', 'icon-' . $settings['banner_button_icon_align'] );

			$this->add_render_attribute( 'banner-button-icon', 'class', 'button-icon minimog-solid-icon' );

			if ( $is_svg ) {
				$this->add_render_attribute( 'banner-button-icon', 'class', [
					'minimog-svg-icon svg-icon',
				] );
			}
		}
		?>
		<div class="tm-button-wrapper">
			<?php printf( '<%1$s %2$s>', $button_tag, $this->get_render_attribute_string( 'banner_button' ) ); ?>
			<div class="button-content-wrapper">
				<?php if ( $has_icon && 'left' === $settings['banner_button_icon_align'] ) : ?>
					<span <?php $this->print_attributes_string( 'banner-button-icon' ); ?>>
							<?php Icons_Manager::render_icon( $settings['banner_button_icon'] ); ?>
						</span>
				<?php endif; ?>

				<?php if ( ! empty( $settings['banner_button_text'] ) ): ?>
					<span class="button-text"><?php echo esc_html( $settings['banner_button_text'] ); ?></span>

					<?php if ( $settings['banner_button_style'] === 'bottom-line-winding' ): ?>
						<span class="line-winding">
							<svg width="42" height="6" viewBox="0 0 42 6" fill="none"
							     xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd"
								      d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
								      fill="#E8C8B3"/>
							</svg>
						</span>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( $has_icon && 'right' === $settings['banner_button_icon_align'] ) : ?>
					<span <?php $this->print_attributes_string( 'banner-button-icon' ); ?>>
							<?php Icons_Manager::render_icon( $settings['banner_button_icon'] ); ?>
						</span>
				<?php endif; ?>
			</div>
			<?php printf( '</%1$s>', $button_tag ); ?>
		</div>
		<?php
	}

	private function banner_additional_classes( $settings ) {
		$devices = [
			'widescreen',
			'desktop',
			'laptop',
			'tablet_extra',
			'tablet',
			'mobile_extra',
			'mobile',
		];

		$classes = [];

		foreach ( $devices as $device ) {
			$grid_columns_setting_name   = 'desktop' === $device ? 'grid_columns' : 'grid_columns' . '_' . $device;
			$banner_columns_setting_name = 'desktop' === $device ? 'banner_columns' : 'banner_columns' . '_' . $device;

			if (
				! isset( $settings[ $grid_columns_setting_name ] ) || '' === $settings[ $grid_columns_setting_name ] ||
				! isset( $settings[ $banner_columns_setting_name ] ) || '' === $settings[ $banner_columns_setting_name ]
			) {
				continue;
			}

			$grid_columns   = intval( $settings[ $grid_columns_setting_name ] );
			$banner_columns = intval( $settings[ $banner_columns_setting_name ] );

			if ( $banner_columns >= $grid_columns ) {
				$classes[] = 'reset-banner-columns-' . $device;
			}
		}

		return $classes;
	}
}
