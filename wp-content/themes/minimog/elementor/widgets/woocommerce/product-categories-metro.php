<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || exit;

class Widget_Product_Categories_Metro extends Base {

	const PRODUCT_CATEGORY = 'product_cat';
	private $term_ids = [];

	public function get_name() {
		return 'tm-product-categories-metro';
	}

	public function get_title() {
		return esc_html__( 'Product Categories Metro', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-gallery-grid';
	}

	public function get_keywords() {
		return [ 'product', 'product category', 'product categories', 'grid', 'metro' ];
	}

	public function get_script_depends() {
		return [ 'minimog-group-widget-grid' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_grid_section();

		$this->add_cat_style_section();

		$this->add_cat_min_price_style_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		/**
		 * Metro Style 01 is Normal Style 05
		 * Metro Style 02 is Normal Style 02
		 * Metro Style 03 is Normal Style 06
		 */
		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => '01',
				'02' => '02',
				'03' => '03',
			],
			'default' => '01',
		] );

		$this->add_control( 'show_count', [
			'label'                => esc_html__( 'Show Count', 'minimog' ),
			'type'                 => Controls_Manager::SWITCHER,
			'label_on'             => esc_html__( 'Show', 'minimog' ),
			'label_off'            => esc_html__( 'Hide', 'minimog' ),
			'return_value'         => 'yes',
			'default'              => 'yes',
			'selectors_dictionary' => [
				'yes' => 'display: block;',
				''    => 'display: none;',
			],
			'selectors'            => [
				'{{WRAPPER}} .category-count' => '{{VALUE}};',
			],
		] );

		$this->add_control( 'show_min_price', [
			'label'        => esc_html__( 'Show Min Price', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Show', 'minimog' ),
			'label_off'    => esc_html__( 'Hide', 'minimog' ),
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'minimog' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'minimog' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'minimog' ),
			],
			'default'      => '',
			'prefix_class' => 'minimog-animation-',
		] );

		$this->add_control( 'metro_image_size_width', [
			'label'   => esc_html__( 'Image Size', 'minimog' ),
			'type'    => Controls_Manager::NUMBER,
			'step'    => 1,
			'default' => 300,
		] );

		$this->add_control( 'metro_image_ratio', [
			'label'   => esc_html__( 'Image Ratio', 'minimog' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [
				'px' => [
					'max'  => 2,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'default' => [
				'size' => 1,
			],
		] );

		$this->end_controls_section();
	}

	private function add_grid_section() {
		$this->start_controls_section( 'grid_options_section', [
			'label' => esc_html__( 'Grid Options', 'minimog' ),
		] );

		$this->add_responsive_control( 'grid_columns', [
			'label'          => esc_html__( 'Columns', 'minimog' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 12,
			'step'           => 1,
			'default'        => 4,
			'tablet_default' => 2,
			'mobile_default' => 1,
		] );

		$this->add_responsive_control( 'grid_gutter', [
			'label'   => esc_html__( 'Gutter', 'minimog' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 200,
			'step'    => 1,
			'default' => 30,
		] );

		$terms_arr = $this->get_term_options();

		$metro_layout_repeater = new Repeater();

		$metro_layout_repeater->start_controls_tabs( 'metro_layout_repeater' ); // Start Repeater Tabs

		// Repeater Content Tab
		$metro_layout_repeater->start_controls_tab( 'metro_layout_content_tab', [
			'label' => esc_html__( 'Content', 'minimog' ),
		] );
		$metro_layout_repeater->add_control( 'term_id', [
			'label'   => esc_html__( 'Category', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => ! empty( $this->term_ids ) ? $this->term_ids[0] : 0,
			'options' => $terms_arr,
		] );

		$metro_layout_repeater->add_control( 'size', [
			'label'   => esc_html__( 'Item Size', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1:1',
			'options' => Widget_Utils::get_grid_metro_size(),
		] );

		$metro_layout_repeater->add_control( 'image', [
			'label'   => esc_html__( 'Choose Image', 'minimog' ),
			'type'    => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [],
		] );
		$metro_layout_repeater->end_controls_tab(); // End Repeater Content Tab

		// Repeater Style Tab
		$metro_layout_repeater->start_controls_tab( 'metro_layout_style_tab', [
			'label' => esc_html__( 'Style', 'minimog' ),
		] );

		$metro_layout_repeater->add_control( 'item_custom_style', [
			'label'       => esc_html__( 'Custom', 'minimog' ),
			'type'        => Controls_Manager::SWITCHER,
			'description' => esc_html__( 'Set custom style that will only affect this specific item.', 'minimog' ),
		] );

		$metro_layout_repeater->add_responsive_control( 'item_text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors'            => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .category-info' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'conditions'           => [
				'terms' => [
					[
						'name'  => 'item_custom_style',
						'value' => 'yes',
					],
				],
			],
		] );

		$metro_layout_repeater->add_responsive_control( 'item_width', [
			'label'       => esc_html__( 'Width', 'minimog' ),
			'type'        => Controls_Manager::SLIDER,
			'description' => esc_html__( 'This option just works if Style is 03', 'minimog' ),
			'default'     => [
				'unit' => 'px',
			],
			'size_units'  => [ 'px', '%' ],
			'range'       => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
			],
			'selectors'   => [
				'{{WRAPPER}} .minimog-product-categories.style-06 {{CURRENT_ITEM}} .category-info-wrapper' => 'width: {{SIZE}}{{UNIT}};',
			],
			'conditions'  => [
				'terms' => [
					[
						'name'  => 'item_custom_style',
						'value' => 'yes',
					],
				],
			],
		] );

		$metro_layout_repeater->add_responsive_control( 'cat_info_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'description'          => esc_html__( 'This option just works if Style is 03', 'minimog' ),
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .minimog-product-categories.style-06 {{CURRENT_ITEM}} .category-info-wrapper' => 'display: flex; align-items: {{VALUE}}',
			],
			'conditions'           => [
				'terms' => [
					[
						'name'  => 'item_custom_style',
						'value' => 'yes',
					],
				],
			],
		] );

		$metro_layout_repeater->add_responsive_control( 'cat_info_vertical_alignment', [
			'label'                => esc_html__( 'Vertical Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'description'          => esc_html__( 'This option just works if Style is 03', 'minimog' ),
			'options'              => Widget_Utils::get_control_options_vertical_alignment(),
			'toggle'               => false,
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .minimog-product-categories.style-06 {{CURRENT_ITEM}} .category-info-wrapper' => 'display: flex; justify-content: {{VALUE}}',
			],
			'conditions'           => [
				'terms' => [
					[
						'name'  => 'item_custom_style',
						'value' => 'yes',
					],
				],
			],
		] );

		$metro_layout_repeater->add_responsive_control( 'item_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .category-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'conditions' => [
				'terms' => [
					[
						'name'  => 'item_custom_style',
						'value' => 'yes',
					],
				],
			],
		] );

		$metro_layout_repeater->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Category Name', 'minimog' ),
			'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .category-name',
		] );

		$metro_layout_repeater->end_controls_tab(); // End Repeater Style Tab.

		$metro_layout_repeater->end_controls_tabs(); // End Repeater Tabs.

		$this->add_control( 'grid_metro_layout', [
			'label'       => esc_html__( 'Metro Layout', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $metro_layout_repeater->get_controls(),
			'default'     => [
				[
					'size'    => '1:2',
					'term_id' => ! empty( $this->term_ids[0] ) ? $this->term_ids[0] : 0,
					'image'   => [
						'url' => $this->get_default_image_src( 270, 570 ),
					],
				],
				[
					'size'    => '1:1',
					'term_id' => ! empty( $this->term_ids[1] ) ? $this->term_ids[1] : 0,
					'image'   => [
						'url' => $this->get_default_image_src( 270, 270 ),
					],
				],
				[
					'size'    => '2:1',
					'term_id' => ! empty( $this->term_ids[2] ) ? $this->term_ids[2] : 0,
					'image'   => [
						'url' => $this->get_default_image_src( 570, 270 ),
					],
				],
				[
					'size'    => '2:1',
					'term_id' => ! empty( $this->term_ids[3] ) ? $this->term_ids[3] : 0,
					'image'   => [
						'url' => $this->get_default_image_src( 570, 270 ),
					],
				],
				[
					'size'    => '1:1',
					'term_id' => ! empty( $this->term_ids[4] ) ? $this->term_ids[4] : 0,
					'image'   => [
						'url' => $this->get_default_image_src( 270, 270 ),
					],
				],
			],
			'title_field' => '{{{ size }}}',
		] );

		$this->end_controls_section();
	}

	private function add_button_section() {
		$this->start_controls_section( 'button_section', [
			'label' => esc_html__( 'Button', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Button::get_type(), [
			'name'           => 'button',
			// Use box link instead of.
			'exclude'        => [
				'link',
			],
			// Change button style text as default.
			'fields_options' => [
				'heading' => [
					'type'      => Controls_Manager::HIDDEN,
					'separator' => '',
				],
				'style'   => [
					'default' => 'text',
				],
				'text'    => [
					'default' => esc_html__( 'Shop Now', 'minimog' ),
				],
			],
		] );

		$this->end_controls_section();
	}

	private function add_cat_style_section() {
		$this->start_controls_section( 'cat_style_section', [
			'label' => esc_html__( 'Categories', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'cat_text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors'            => [
				'{{WRAPPER}} .category-info' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'cat_width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
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
					'max' => 1000,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .category-info-wrapper' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ '03' ],
			],
		] );

		$this->add_responsive_control( 'cat_info_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .category-info-wrapper' => 'display: flex;align-items: {{VALUE}}',
			],
			'condition'            => [
				'style' => [ '03' ],
			],
		] );

		$this->add_responsive_control( 'cat_info_vertical_alignment', [
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
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .category-info-wrapper' => 'display: flex; justify-content: {{VALUE}}',
			],
			'condition'            => [
				'style' => [ '03' ],
			],
		] );

		$this->add_responsive_control( 'cat_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .category-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .category-info'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		// Image.
		$this->add_control( 'cat_image_heading', [
			'label'     => esc_html__( 'Image', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );
		$this->add_control( 'image_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		// Name.
		$this->add_control( 'cat_name_heading', [
			'label'     => esc_html__( 'Name', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'selector' => '{{WRAPPER}} .category-name',
		] );

		$this->start_controls_tabs( 'cat_name_color_tabs' );

		$this->start_controls_tab( 'cat_name_color_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'cat_name_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .category-name' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'cat_name_color_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'cat_name_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-box:hover .category-name' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Count.
		$this->add_control( 'cat_count_heading', [
			'label'     => esc_html__( 'Count', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_count' => 'yes',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'cat_count_typography',
			'selector'  => '{{WRAPPER}} .category-count',
			'condition' => [
				'show_count' => 'yes',
			],
		] );

		$this->add_control( 'cat_count_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .category-count' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_count' => 'yes',
			],
		] );

		$this->add_responsive_control( 'cat_count_margin_top', [
			'label'      => esc_html__( 'Margin Top', 'minimog' ),
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
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .category-count' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_count' => 'yes',
			],
		] );

		// Button.
		$this->add_control( 'button_style_hr', [
			'label'     => esc_html__( 'Button', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'style' => [ '01', '02' ],
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
				'{{WRAPPER}} .category-info .tm-button' => 'min-height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ '02', '01' ],
			],
		] );

		$this->add_responsive_control( 'button_min_width', [
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
				'{{WRAPPER}} .category-info .tm-button' => 'min-width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => [
				'style' => [ '02', '01' ],
			],
		] );

		$this->add_control( 'button_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .category-info .tm-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ '02', '01' ],
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
				'style' => [ '02', '01' ],
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'button_typography',
			'selector'  => '{{WRAPPER}} .category-info .tm-button',
			'condition' => [
				'style' => [ '02', '01' ],
			],
		] );

		$this->start_controls_tabs( 'button_skin_tabs', [
			'condition' => [
				'style' => [ '02', '01' ],
			],
		] );

		$this->start_controls_tab( 'button_skin_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'button_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .category-info .tm-button' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_background', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .category-info .tm-button' => '--minimog-tm-button-hover-background: {{VALUE}}; background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'button_box_shadow',
			'selector' => '{{WRAPPER}} .category-info .tm-button',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'hover_button_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-box:hover .category-info .tm-button' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'hover_button_background', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-box:hover .category-info .tm-button' => '--minimog-tm-button-hover-background: {{VALUE}}; background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'button_hover_box_shadow',
			'selector' => '{{WRAPPER}} .minimog-box:hover .category-info .tm-button',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_cat_min_price_style_section() {
		$this->start_controls_section( 'cat_min_price_style_section', [
			'label'     => esc_html__( 'Min Price', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'show_min_price' => 'yes',
			],
		] );

		$this->add_responsive_control( 'cat_min_price_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .category-min-price' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'cat_min_price_typography',
			'selector' => '{{WRAPPER}} .category-min-price',
		] );

		$this->add_control( 'cat_min_price_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .category-min-price' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'cat_min_price_amount_style_hr', [
			'label'     => esc_html__( 'Amount', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'cat_min_price_amount_typography',
			'selector' => '{{WRAPPER}} .category-min-price .amount',
		] );

		$this->add_control( 'cat_min_price_amount_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .category-min-price .amount' => 'color: {{VALUE}} !important;',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['grid_metro_layout'] ) ) {
			return;
		}

		switch ( $settings['style'] ) {
			case '02':
				$style = '02';
				break;
			case '03':
				$style = '06';
				break;
			default:
				$style = '05';
				break;
		}

		$this->add_render_attribute( 'grid-wrapper', 'class', [
			'minimog-grid-wrapper',
			'minimog-product-categories',
			'minimog-grid-metro',
			'minimog-product-categories-metro',
			'style-' . $style,
		] );

		$this->add_render_attribute( 'content-wrapper', 'class', 'minimog-grid lazy-grid has-animation fade-in' );

		$grid_options = $this->get_grid_options( $settings );
		$this->add_render_attribute( 'grid-wrapper', 'data-grid', wp_json_encode( $grid_options ) );
		$grid_args_style = \Minimog_Helper::grid_args_to_html_style( $grid_options );
		if ( ! empty( $grid_args_style ) ) {
			$this->add_render_attribute( 'grid-wrapper', 'style', $grid_args_style );
		}
		?>
		<div <?php $this->print_attributes_string( 'grid-wrapper' ); ?>>
			<div <?php $this->print_attributes_string( 'content-wrapper' ); ?>>
				<div class="grid-sizer"></div>
				<?php $this->print_metro_grid(); ?>
			</div>
		</div>
		<?php
	}

	protected function get_grid_options( array $settings ) {
		$grid_options = [
			'type' => 'metro',
		];

		if ( isset( $settings['metro_image_ratio'], $settings['metro_image_ratio']['size'] ) ) {
			$grid_options['ratio'] = $settings['metro_image_ratio']['size'];
		}

		$columns_settings = $this->parse_responsive_settings( $settings, 'grid_columns', 'columns' );
		$gutter_settings  = $this->parse_responsive_settings( $settings, 'grid_gutter', 'gutter' );

		$grid_options += $columns_settings + $gutter_settings;

		return $grid_options;
	}

	private function print_metro_grid() {
		$settings = $this->get_settings_for_display();

		foreach ( $settings['grid_metro_layout'] as $index => $layout ) {
			$term_id = intval( $layout['term_id'] );
			$term    = get_term( $term_id, self::PRODUCT_CATEGORY );

			if ( empty ( $term ) || is_wp_error( $term ) ) {
				continue;
			}

			$link = get_term_link( $term, self::PRODUCT_CATEGORY );
			if ( is_wp_error( $link ) ) {
				continue;
			}

			$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
			$item_key     = 'image_key_' . $index;

			$size   = $layout['size'];
			$ratio  = explode( ':', $size );
			$ratioW = $ratio[0];
			$ratioH = $ratio[1];

			$this->add_render_attribute( $item_key, [
				'class'       => [
					'grid-item',
					'minimog-box',
					'elementor-repeater-item-' . $layout['_id'],
				],
				'data-width'  => $ratioW,
				'data-height' => $ratioH,
			] );

			$ratio = ! empty( $settings['metro_image_ratio']['size'] ) ? $settings['metro_image_ratio']['size'] : 1;

			$_image_width  = $settings['metro_image_size_width'];
			$_image_height = $_image_width * $ratio;

			if ( in_array( $ratioW, array( '2' ) ) ) {
				$_image_width *= 2;
			}

			if ( in_array( $ratioH, array( '1.3', '2' ) ) ) {
				$_image_height *= 2;
			}

			$_image_size = "{$_image_width}x{$_image_height}";

			$background_key = 'background_key_' . $index;
			$url            = $this->get_default_image_src( $_image_width, $_image_height );

			if ( ! empty( $layout['image']['url'] ) ) {
				$layout['image_size']             = 'custom';
				$layout['image_custom_dimension'] = [
					'width'  => $_image_width,
					'height' => $_image_height,
				];

				$image_url = \Minimog_Image::get_image_cropped_url( $layout['image']['url'], [
					'size' => $_image_size,
					'crop' => true,
				] );

				$url = $image_url[0];

			} elseif ( ! empty( $thumbnail_id ) ) {
				$url = \Minimog_Image::get_attachment_url_by_id( [
					'id'   => $thumbnail_id,
					'size' => $_image_size,
				] );
			}

			$this->add_render_attribute( $background_key, [
				'class' => 'minimog-image__inner image',
				'style' => 'background-image: url(' . $url . ')',
			] );

			$this->add_render_lazyload_attributes( $background_key, $url );
			?>
			<div <?php $this->print_render_attribute_string( $item_key ); ?>>
				<a href="<?php echo esc_url( $link ); ?>" class="cat-wrap minimog-box cat-link link-secret">
					<div class="grid-item-height minimog-image">
						<div <?php $this->print_render_attribute_string( $background_key ); ?>></div>
					</div>

					<?php
					switch ( $settings['style'] ) {
						case '02' :
							$this->print_category_info_with_button( $term );
							break;
						case '03' :
							$this->print_category_info( $term );
							break;
						default: // Style 01.
							$this->print_category_info_button_only( $term );
							break;
					}
					?>
				</a>
			</div>
			<?php
		}
	}

	private function print_button() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['button_text'] ) && empty( $settings['button_icon']['value'] ) ) {
			return;
		}

		$this->add_render_attribute( 'button', 'class', 'tm-button style-' . $settings['button_style'] );

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'tm-button-' . $settings['button_size'] );
		}

		$has_icon = false;

		if ( ! empty( $settings['button_icon']['value'] ) ) {
			$has_icon = true;
			$is_svg   = isset( $settings['button_icon']['library'] ) && 'svg' === $settings['button_icon']['library'] ? true : false;

			$this->add_render_attribute( 'button', 'class', 'icon-' . $settings['button_icon_align'] );

			$this->add_render_attribute( 'button-icon', 'class', 'button-icon minimog-solid-icon' );

			if ( $is_svg ) {
				$this->add_render_attribute( 'button-icon', 'class', [
					'minimog-svg-icon svg-icon',
				] );
			}
		}

		?>
		<div class="tm-button-wrapper">
			<div <?php $this->print_render_attribute_string( 'button' ) ?>>

				<div class="button-content-wrapper">
					<?php if ( $has_icon && 'left' === $settings['button_icon_align'] ) : ?>
						<span <?php $this->print_attributes_string( 'button-icon' ); ?>>
							<?php Icons_Manager::render_icon( $settings['button_icon'] ); ?>
						</span>
					<?php endif; ?>

					<?php if ( ! empty( $settings['button_text'] ) ): ?>
						<span class="button-text"><?php echo esc_html( $settings['button_text'] ); ?></span>

						<?php if ( $settings['button_style'] === 'bottom-line-winding' ): ?>
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

					<?php if ( $has_icon && 'right' === $settings['button_icon_align'] ) : ?>
						<span <?php $this->print_attributes_string( 'button-icon' ); ?>>
							<?php Icons_Manager::render_icon( $settings['button_icon'] ); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	private function get_term_options() {
		$options = [];
		$terms   = get_terms( [ 'taxonomy' => self::PRODUCT_CATEGORY ] );

		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
			$this->term_ids[]          = $term->term_id;
		}

		return $options;
	}

	private function get_default_image_src( $width, $height ) {
		$src = 'https://via.placeholder.com/' . $width . 'x' . $height . '?text=' . esc_attr__( 'No+Image', 'minimog' );

		return $src;
	}

	private function print_category_count( $category, $text = true ) {
		?>
		<div class="category-count">
			<span class="cat-count-number"><?php echo intval( $category->count ); ?></span>

			<?php if ( $text ) : ?>
				<span class="cat-count-text"><?php echo esc_html( _n( 'item', 'items', $category->count, 'minimog' ) ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	private function print_category_min_price( $category ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['show_min_price'] ) ) {
			return;
		}

		$min_price = \Minimog\Woo\Product_Category::instance()->get_min_price( $category->term_id );
		?>
		<div class="category-min-price">
			<?php echo sprintf( __( 'from %s', 'minimog' ), wc_price( $min_price ) ); ?>
		</div>
		<?php
	}

	// Style 03.
	private function print_category_info( $category ) {
		?>
		<div class="category-info">
			<div class="category-info-wrapper">
				<h5 class="category-name"><?php echo esc_html( $category->name ); ?></h5>
				<?php $this->print_category_count( $category ); ?>
				<?php $this->print_category_min_price( $category ); ?>
			</div>
		</div>
		<?php
	}

	// Style 02.
	private function print_category_info_with_button( $category ) {
		?>
		<div class="category-info">
			<div class="category-info-wrapper">
				<h5 class="category-name"><?php echo esc_html( $category->name ); ?></h5>
				<?php $this->print_category_count( $category ); ?>
				<?php $this->print_category_min_price( $category ); ?>
			</div>
			<div class="tm-button style-flat">
				<i class="far fa-arrow-right"></i>
			</div>
		</div>
		<?php
	}

	// Style 01.
	private function print_category_info_button_only( $category ) {
		?>
		<div class="category-info">
			<div class="tm-button style-flat"><?php echo esc_html( $category->name ); ?></div>
		</div>
		<?php
	}
}
