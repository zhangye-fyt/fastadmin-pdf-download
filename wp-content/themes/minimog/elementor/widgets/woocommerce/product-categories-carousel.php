<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Widget_Product_Category_Carousel extends Carousel_Base {

	public function get_name() {
		return 'tm-product-category-carousel';
	}

	public function get_title() {
		return esc_html__( 'Product Categories Carousel', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-posts-carousel';
	}

	public function get_keywords() {
		return [ 'product', 'product category', 'product categories', 'carousel' ];
	}

	public function before_slider() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( $this->get_slider_key(), 'class', 'minimog-product-categories style-' . $settings['style'] );
	}

	protected function get_taxonomy_name() {
		return 'product_cat';
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_query_section();

		$this->add_cat_style_section();

		$this->add_cat_image_style_section();

		$this->add_cat_info_style_section();

		$this->add_cat_min_price_style_section();

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
			'options'     => \Minimog_Woo::instance()->get_shop_categories_style_options(),
			'default'     => '01',
			'render_type' => 'template',
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''                    => esc_html__( 'None', 'minimog' ),
				'zoom-in'             => esc_html__( 'Zoom In', 'minimog' ),
				'zoom-out'            => esc_html__( 'Zoom Out', 'minimog' ),
				'scaling-up'          => esc_html__( 'Scale Up', 'minimog' ),
				'scaling-up-style-02' => esc_html__( 'Scale Up Bigger', 'minimog' ),
			],
			'default'      => '',
			'prefix_class' => 'minimog-animation-',
		] );

		$this->add_control( 'show_count', [
			'label'                => esc_html__( 'Show Count', 'minimog' ),
			'type'                 => Controls_Manager::SWITCHER,
			'label_on'             => esc_html__( 'Show', 'minimog' ),
			'label_off'            => esc_html__( 'Hide', 'minimog' ),
			'return_value'         => 'yes',
			'default'              => 'yes',
			'selectors_dictionary' => [
				'yes' => 'display: inline-block;',
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

		$this->add_control( 'thumbnail_default_size', [
			'label'        => esc_html__( 'Use Default Thumbnail Size', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'thumbnail',
			'default'   => 'full',
			'condition' => [
				'thumbnail_default_size!' => '1',
			],
		] );

		$this->add_control( 'button_text', [
			'label'       => esc_html__( 'Button Text', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Shop Now', 'minimog' ),
			'label_block' => true,
			'separator'   => 'before',
			'condition'   => [
				'style' => '09',
			],
		] );

		$this->end_controls_section();
	}

	private function add_query_section() {
		$this->start_controls_section( 'query_section', [
			'label' => esc_html__( 'Query', 'minimog' ),
		] );

		$this->add_control( 'custom_query', [
			'label'     => esc_html__( 'Custom', 'minimog' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Yes', 'minimog' ),
			'label_off' => esc_html__( 'No', 'minimog' ),
			'default'   => '',
		] );

		$this->add_control( 'source', [
			'label'       => esc_html__( 'Source', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				''                      => esc_html__( 'Show All', 'minimog' ),
				'by_parent'             => esc_html__( 'By Parent', 'minimog' ),
				'current_subcategories' => esc_html__( 'Current Subcategories', 'minimog' ),
			],
			'label_block' => true,
			'condition'   => [
				'custom_query!' => 'yes',
			],
		] );

		$options        = \Minimog_Woo::instance()->get_product_categories_dropdown_options();
		$parent_options = [ '0' => esc_html__( 'Only Top Level', 'minimog' ) ] + $options;

		$this->add_control( 'parent', [
			'label'     => esc_html__( 'Parent', 'minimog' ),
			'type'      => Controls_Manager::SELECT2,
			'multiple'  => false,
			'default'   => '0',
			'options'   => $parent_options,
			'condition' => [
				'custom_query!' => 'yes',
				'source'        => 'by_parent',
			],
		] );

		$this->add_control( 'hide_empty', [
			'label'     => esc_html__( 'Hide Empty', 'minimog' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_on'  => esc_html__( 'Hide', 'minimog' ),
			'label_off' => esc_html__( 'Show', 'minimog' ),
		] );

		$this->add_control( 'number', [
			'label'     => esc_html__( 'Categories Count', 'minimog' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => '10',
			'condition' => [
				'custom_query!' => 'yes',
			],
		] );

		$this->add_control( 'orderby', [
			'label'     => esc_html__( 'Order By', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'name',
			'options'   => [
				'name'        => esc_html__( 'Name', 'minimog' ),
				'slug'        => esc_html__( 'Slug', 'minimog' ),
				'description' => esc_html__( 'Description', 'minimog' ),
				'count'       => esc_html__( 'Count', 'minimog' ),
				'order'       => esc_html__( 'Category order', 'minimog' ),
			],
			'condition' => [
				'custom_query!' => 'yes',
			],
		] );

		$this->add_control( 'order', [
			'label'     => esc_html__( 'Order', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'desc',
			'options'   => [
				'asc'  => esc_html__( 'ASC', 'minimog' ),
				'desc' => esc_html__( 'DESC', 'minimog' ),
			],
			'condition' => [
				'custom_query!' => 'yes',
			],
		] );

		// Custom term.
		$repeater = new Repeater();

		$repeater->add_control( 'cat_id', [
			'label'    => __( 'Select Categories', 'minimog' ),
			'type'     => Controls_Manager::SELECT2,
			'multiple' => false,
			'default'  => '',
			'options'  => $options,
		] );

		$repeater->add_control( 'image', [
			'label'   => esc_html__( 'Custom Image', 'minimog' ),
			'type'    => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [],
		] );

		$repeater->add_control( 'custom_text', [
			'label'       => esc_html__( 'Custom Text', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'label_block' => true,
		] );

		$this->add_control( 'custom_categories', [
			'label'       => esc_html__( 'Categories', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [],
			'title_field' => "{{{ MinimogElementor.helpers.getRepeaterSelectOptionText('tm-product-category-carousel', 'custom_categories', 'cat_id', cat_id ) }}}",
			'condition'   => [
				'custom_query' => 'yes',
			],
		] );

		$this->end_controls_section();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'default'        => '3',
			'tablet_default' => '2',
			'mobile_default' => '1',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 30,
		] );
	}

	private function add_cat_style_section() {
		$this->start_controls_section( 'cat_style_section', [
			'label' => esc_html__( 'Categories', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'background_box',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .cat-wrap',
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .cat-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .cat-wrap'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_radius', [
			'label'      => esc_html__( 'Border box radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => 'px',
			'selectors'  => [
				'{{WRAPPER}} .cat-wrap'       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .cat-wrap:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors_dictionary' => [
				'left'   => 'text-align: start; justify-content: flex-start;',
				'center' => 'text-align: center; justify-content: center;',
				'right'  => 'text-align: end; justify-content: flex-end;',
			],
			'selectors'            => [
				'{{WRAPPER}} .cat-wrap'              => '{{VALUE}};',
				'{{WRAPPER}} .minimog-image-wrapper' => '{{VALUE}};',
			],
		] );

		$this->add_control( 'grid_border_color', [
			'label'     => esc_html__( 'Grid Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-product-categories' => '--grid-border-color: {{VALUE}};',
			],
			'condition' => [
				'style' => '12',
			],
		] );

		$this->end_controls_section();
	}

	private function add_cat_image_style_section() {
		$this->start_controls_section( 'cat_image_style_section', [
			'label' => esc_html__( 'Image', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_size', [
			'label'      => esc_html__( 'Size', 'minimog' ),
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
				'{{WRAPPER}} .minimog-product-categories .minimog-image' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ '03', '07', '09', '12' ],
			],
		] );

		$this->add_responsive_control( 'image_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-image-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-image-wrapper'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'image_radius', [
			'label'      => esc_html__( 'Radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-product-categories .minimog-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		// Image Effect.
		$this->start_controls_tabs( 'images_effects', [ 'separator' => 'before', ] );

		$this->start_controls_tab( 'images_effects_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters',
			'selector' => '{{WRAPPER}} .image',
		] );

		$this->add_control( 'images_opacity', [
			'label'     => esc_html__( 'Opacity', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .image' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'images_effects_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters_hover',
			'selector' => '{{WRAPPER}} .minimog-box:hover .image',
		] );

		$this->add_control( 'images_opacity_hover', [
			'label'     => esc_html__( 'Opacity', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-box:hover .image' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_cat_info_style_section() {
		$this->start_controls_section( 'cat_info_style_section', [
			'label' => esc_html__( 'Category Info', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
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
				'{{WRAPPER}} .category-info-wrapper' => 'align-items: {{VALUE}}',
			],
			'condition'            => [
				'style' => '06',
			],
		] );

		$this->add_responsive_control( 'cat_info_vertical_alignment', [
			'label'                => esc_html__( 'Vertical Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_vertical_alignment_full(),
			'toggle'               => false,
			'selectors_dictionary' => [
				'top'     => 'flex-start',
				'middle'  => 'center',
				'bottom'  => 'flex-end',
				'stretch' => 'space-between',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .category-info-wrapper' => 'justify-content: {{VALUE}}',
			],
			'condition'            => [
				'style' => '06',
			],
		] );

		$this->add_responsive_control( 'info_box_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .category-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .category-info'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'info_box_max_width', [
			'label'          => esc_html__( 'Max Width', 'minimog' ),
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
			'size_units'     => [ 'px', '%' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .category-info-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		// Name.
		$this->add_control( 'name_style_hr', [
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
				'{{WRAPPER}} .category-name:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Count.
		$this->add_control( 'count_style_hr', [
			'label'     => esc_html__( 'Count', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_count' => 'yes',
			],
		] );

		$this->add_responsive_control( 'count_spacing', [
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
				'{{WRAPPER}} .category-count' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
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

		// Button.
		$this->add_control( 'button_style_hr', [
			'label'     => esc_html__( 'Button', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'style' => [ '02', '05', '09' ],
			],
		] );

		/**
		 * Should be style with background
		 */
		$this->add_control( 'caption_overlay_button_style', [
			'label'       => esc_html__( 'Button Style', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'flat' => esc_html__( 'Flat', 'minimog' ),
				'3d'   => '3D',
			],
			'default'     => 'flat',
			'render_type' => 'template',
			'condition'   => [
				'style' => [ '05' ],
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
				'style' => [ '02', '05' ],
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
				'style' => [ '02', '05' ],
			],
		] );

		$this->add_responsive_control( 'button_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .category-info .tm-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ '02', '05' ],
			],
		] );

		$this->add_responsive_control( 'button_border_width', [
			'label'      => esc_html__( 'Border Width', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ '02', '05' ],
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'button_typography',
			'selector'  => '{{WRAPPER}} .category-info .tm-button',
			'condition' => [
				'style' => [ '02', '05', '09' ],
			],
		] );

		$this->start_controls_tabs( 'button_skin_tabs', [
			'condition' => [
				'style' => [ '02', '05', '09' ],
			],
		] );

		$this->start_controls_tab( 'button_skin_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );
		// Color
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
			'condition' => [
				'style' => [ '02', '05' ],
			],
		] );

		$this->add_control( 'button_line_color', [
			'label'     => esc_html__( 'Line', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper:before' => 'background: {{VALUE}};',
			],
			'condition' => [
				'style' => [ '09' ],
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'      => 'button_box_shadow',
			'selector'  => '{{WRAPPER}} .category-info .tm-button',
			'condition' => [
				'style' => [ '02', '05' ],
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'hover_button_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .category-info .tm-button:hover' => 'color: {{VALUE}};',
			],
		] );


		$this->add_control( 'hover_button_background', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .category-info .tm-button:hover' => '--minimog-tm-button-hover-background: {{VALUE}}; background-color: {{VALUE}};',
			],
			'condition' => [
				'style' => [ '02', '05' ],
			],
		] );

		$this->add_control( 'button_line_hover_color', [
			'label'     => esc_html__( 'Line', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper:after' => 'background: {{VALUE}};',
			],
			'condition' => [
				'style' => [ '09' ],
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'      => 'button_hover_box_shadow',
			'selector'  => '{{WRAPPER}} .category-info .tm-button:hover',
			'condition' => [
				'style' => [ '02', '05' ],
			],
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

	protected function print_slides( array $settings ) {
		$settings = $this->get_settings_for_display();

		$loop_settings = [
			'style'                => $settings['style'],
			'show_count'           => ! empty( $settings['show_count'] ) && 'yes' === $settings['show_count'] ? 1 : 0,
			'show_min_price'       => ! empty( $settings['show_min_price'] ) && 'yes' === $settings['show_min_price'] ? 1 : 0,
			'button_text'          => $settings['button_text'],
			'overlay_button_style' => ! empty( $settings['caption_overlay_button_style'] ) ? $settings['caption_overlay_button_style'] : '',
			'layout'               => 'slider',
		];

		if ( isset( $settings['thumbnail_default_size'] ) && '1' !== $settings['thumbnail_default_size'] ) {
			$loop_settings['thumbnail_size'] = \Minimog_Image::elementor_parse_image_size( $settings );
		}

		if ( 'yes' === $settings['custom_query'] && ! empty( $settings['custom_categories'] ) ) {
			foreach ( $settings['custom_categories'] as $slide ) {
				if ( empty( $slide['cat_id'] ) ) {
					return;
				}

				$custom_settings = $loop_settings;

				if ( ! empty( $slide['image']['id'] ) ) {
					$custom_settings['custom_thumbnail_id'] = $slide['image']['id'];
				}

				$terms = get_terms( [
					'taxonomy'   => 'product_cat',
					'include'    => $slide['cat_id'],
					'hide_empty' => ( 'yes' === $settings['hide_empty'] ) ? true : false,
				] );

				if ( empty( $terms ) || is_wp_error( $terms ) ) {
					continue;
				}

				$category = $terms[0];

				if ( ! empty( $slide['custom_text'] ) ) {
					$category->name = $slide['custom_text'];
				}

				minimog_get_wc_template_part( 'content-product-cat', '', [
					'settings' => $custom_settings,
					'category' => $category,
				] );
			}
		} else {
			$categories = $this->get_terms();

			foreach ( $categories as $category ) {
				minimog_get_wc_template_part( 'content-product-cat', '', [
					'settings' => $loop_settings,
					'category' => $category,
				] );
			}
		}
	}

	/**
	 * Query Term
	 */
	protected function get_terms() {
		$settings = $this->get_settings_for_display();

		$term_args = [
			'taxonomy'   => $this->get_taxonomy_name(),
			'number'     => $settings['number'],
			'hide_empty' => 'yes' === $settings['hide_empty'],
		];

		// Setup order.
		switch ( $settings['source'] ) {
			case 'by_id':
				$term_args['orderby'] = 'include';
				break;
			default:
				if ( 'order' === $settings['orderby'] ) {
					$term_args['orderby']  = 'meta_value_num';
					$term_args['meta_key'] = 'order';
				} else {
					$term_args['orderby'] = $settings['orderby'];
					$term_args['order']   = $settings['order'];
				}
				break;
		}

		// Setup source.
		switch ( $settings['source'] ) {
			case 'by_id':
				$term_args['include'] = $settings['categories'];
				break;
			case 'by_parent' :
				$term_args['parent'] = $settings['parent'];
				break;
			case 'current_subcategories':
				$term_args['parent'] = get_queried_object_id();
				break;
		}

		$terms = get_terms( $term_args );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			$terms = [];
		}

		return $terms;
	}
}
