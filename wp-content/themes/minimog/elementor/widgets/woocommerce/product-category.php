<?php

namespace Minimog_Elementor;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || exit;

class Widget_Product_Category extends Base {

	/**
	 * @var \WP_Term $category
	 */
	private $category = null;

	public function get_name() {
		return 'tm-product-category-banner';
	}

	public function get_title() {
		return esc_html__( 'Product Category Banner', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-image-rollover';
	}

	public function get_keywords() {
		return [ 'banner', 'product-category' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_cat_style_section();

		$this->add_cat_image_style_section();

		$this->add_cat_info_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => '01',
				'02' => '02',
				'03' => '03',
				'04' => '04',
				'05' => '05',
				'06' => '06',
				'07' => '07',
				'08' => '08',
				'09' => '09',
			],
			'default' => '01',
		] );

		$all_categories = \Minimog_Woo::instance()->get_product_categories_dropdown_options();
		$all_categories = [ 0 => esc_html__( 'Select a category', 'minimog' ) ] + $all_categories;

		$this->add_control( 'cat_id', [
			'label'   => __( 'Category', 'minimog' ),
			'type'    => Controls_Manager::SELECT2,
			'default' => 0,
			'options' => $all_categories,
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''                    => esc_html__( 'None', 'minimog' ),
				'zoom-in'             => esc_html__( 'Zoom In', 'minimog' ),
				'zoom-out'            => esc_html__( 'Zoom Out', 'minimog' ),
				'move-up'             => esc_html__( 'Move Up', 'minimog' ),
				'scaling-up'          => esc_html__( 'Scale Up', 'minimog' ),
				'scaling-up-style-02' => esc_html__( 'Scale Up Bigger', 'minimog' ),
			],
			'default'      => '',
			'prefix_class' => 'minimog-animation-',
		] );

		$this->add_control( 'thumbnail_default_size', [
			'label'        => esc_html__( 'Use Default Thumbnail', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
			'separator'    => 'before',
		] );

		$this->add_control( 'image', [
			'label'     => esc_html__( 'Choose Image', 'minimog' ),
			'type'      => Controls_Manager::MEDIA,
			'dynamic'   => [
				'active' => true,
			],
			'default'   => [
				'url' => Utils::get_placeholder_image_src(),
			],
			'condition' => [
				'thumbnail_default_size!' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'image',
			// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
			'default'   => 'full',
			'separator' => 'none',
			'condition' => [
				'thumbnail_default_size!' => '1',
			],
		] );

		$this->add_control( 'custom_category_text', [
			'label'       => esc_html__( 'Custom Category Text', 'minimog' ),
			'description' => esc_html__( 'Leave blank to use category name.', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'separator'   => 'before',
		] );

		$this->add_control( 'button_text', [
			'label'       => esc_html__( 'Button Text', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Shop Now', 'minimog' ),
			'label_block' => true,
			'separator'   => 'before',
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'minimog' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		$this->end_controls_section();
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
				'body.rtl {{WRAPPER}} .cat-wrap'       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_radius', [
			'label'      => esc_html__( 'Border box radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => 'px',
			'selectors'  => [
				'{{WRAPPER}} .cat-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->end_controls_section();
	}

	private function add_cat_image_style_section() {
		$this->start_controls_section( 'cat_image_style_section', [
			'label' => esc_html__( 'Image', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'cat_image_size', [
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
				'style' => [ '03', '07', '09' ],
			],
		] );

		$this->add_responsive_control( 'image_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-image-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-image-wrapper'       => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		// Image Effect
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
				'{{WRAPPER}} .category-info' => 'display: flex; justify-content: {{VALUE}}',
			],
			'condition'            => [
				'style' => [ '06' ],
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
				'{{WRAPPER}} .category-info' => 'display: flex; align-items: {{VALUE}}',
			],
			'condition'            => [
				'style' => [ '06' ],
			],
		] );

		$this->add_responsive_control( 'info_box_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .category-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .category-info'       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

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
				'{{WRAPPER}} .minimog-box:hover .category-name' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'count_style_hr', [
			'label'     => esc_html__( 'Count', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
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
				'style'      => [ '01', '02', '06' ],
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

		$this->add_control( 'button_style_hr', [
			'label'     => esc_html__( 'Button', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'style' => [ '02', '05', '09' ],
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
				'{{WRAPPER}} .minimog-product-categories .tm-button' => 'min-height: {{SIZE}}{{UNIT}};',
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
				'{{WRAPPER}} .minimog-product-categories .tm-button' => 'min-width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => [
				'style' => [ '02', '05' ],
			],
		] );

		$this->add_control( 'button_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-product-categories .tm-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ '02', '05' ],
			],
		] );

		$this->add_control( 'button_border_width', [
			'label'      => esc_html__( 'Border Width', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-product-categories .tm-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ '02', '05' ],
			],
		] );

		$this->add_responsive_control( 'button_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-product-categories .tm-button.style-text'                                        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .minimog-product-categories .tm-button.style-flat'                                        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .minimog-product-categories .tm-button.style-border'                                      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .minimog-product-categories .tm-button.style-bottom-line .button-content-wrapper'         => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .minimog-product-categories .tm-button.style-bottom-thick-line .button-content-wrapper'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .minimog-product-categories .tm-button.style-bottom-line-winding .button-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-product-categories .tm-button.style-text'                                              => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-product-categories .tm-button.style-flat'                                              => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-product-categories .tm-button.style-border'                                            => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-product-categories .tm-button.style-bottom-line .button-content-wrapper'               => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-product-categories .tm-button.style-bottom-thick-line .button-content-wrapper'         => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-product-categories .tm-button.style-bottom-line-winding .button-content-wrapper'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ '02', '05', '09' ],
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'button_typography',
			'selector'  => '{{WRAPPER}} .minimog-product-categories .tm-button',
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

		$this->add_control( 'button_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-product-categories .tm-button' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_line_color', [
			'label'     => esc_html__( 'Line Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-product-categories .tm-button.style-bottom-line .button-content-wrapper:before'       => 'background: {{VALUE}};',
				'{{WRAPPER}} .minimog-product-categories .tm-button.style-bottom-thick-line .button-content-wrapper:before' => 'background: {{VALUE}};',
			],
			'condition' => [
				'style' => [ '09' ],
			],
		] );

		$this->add_control( 'button_background', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-product-categories .tm-button' => '--minimog-tm-button-hover-background: {{VALUE}}; background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'button_box_shadow',
			'selector' => '{{WRAPPER}} .minimog-product-categories .tm-button',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'hover_button_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-product-categories .minimog-box:hover .tm-button' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'hover_button_line_color', [
			'label'     => esc_html__( 'Line Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-product-categories .tm-button.style-bottom-line .button-content-wrapper:after'       => 'background: {{VALUE}};',
				'{{WRAPPER}} .minimog-product-categories .tm-button.style-bottom-thick-line .button-content-wrapper:after' => 'background: {{VALUE}};',
			],
			'condition' => [
				'style' => [ '09' ],
			],
		] );

		$this->add_control( 'hover_button_background', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-product-categories .minimog-box:hover .tm-button' => '--minimog-tm-button-hover-background: {{VALUE}}; background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'button_hover_box_shadow',
			'selector' => '{{WRAPPER}} .minimog-product-categories .minimog-box:hover .tm-button',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['cat_id'] ) ) {
			return;
		}

		$terms = get_terms( [
			'taxonomy' => 'product_cat',
			'include'  => $settings['cat_id'],
		] );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		$this->category = $terms[0];

		$this->add_render_attribute( 'wrapper', 'class', [
			'minimog-banner-category',
			'minimog-product-categories',
			'style-' . $settings['style'],
		] );

		$cat_wrap_classes = [
			'cat-wrap',
			'minimog-box',
		];

		$this->add_render_attribute( 'cat_wrap', [
			'class' => $cat_wrap_classes,
		] );

		$thumbnail_id = get_term_meta( $this->category->term_id, 'thumbnail_id', true );

		switch ( $settings['style'] ) {
			case '02' :
				$thumbnail_size = '440x595';
				break;
			case '03' :
				/**
				 * Display size: 160x160
				 * Up size to fix blurry on zoom animation.
				 */
				$thumbnail_size = '180x180';
				break;
			case '04' :
				$thumbnail_size = '383x510';
				break;
			case '05' :
				$thumbnail_size = '520x310';
				break;
			case '08' :
				$thumbnail_size = '291x295';
				break;
			case '09' :
				/**
				 * Display size: 270x270
				 * Up size to fix blurry on zoom animation.
				 */
				$thumbnail_size = '300x300';
				break;
			default: // Style 01.
				$thumbnail_size = '370x500';
				break;
		}

		$image_size = \Minimog_Image::elementor_parse_image_size( $settings, $thumbnail_size );
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ) ?>>
			<a href="<?php echo esc_url( get_term_link( $this->category, 'product_cat' ) ) ?>" class="link-secret">
				<div <?php $this->print_render_attribute_string( 'cat_wrap' ); ?>>
					<div class="minimog-image-wrapper">
						<div class="minimog-image-inner">
							<div class="minimog-image image">
								<div class="cat-image"><?php $this->print_image( $thumbnail_id, $image_size ); ?></div>
							</div>
						</div>
					</div>

					<?php
					switch ( $settings['style'] ) {
						case '02' :
							$this->print_category_info_with_button();
							break;
						case '03' :
						case '04' :
							$this->print_category_info_small_count();
							break;
						case '05' :
							$this->print_category_info_button_only();
							break;
						case '08' :
							$this->print_category_info_split();
							break;
						default: // Style 01/06/07/09.
							$this->print_category_info();
							break;
					}
					?>

					<?php
					if ( '09' === $settings['style'] ) {
						$this->print_button( $settings );
					}
					?>
				</div>
			</a>
		</div>
		<?php
	}

	private function print_image( $thumbnail_id, $image_size ) {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['image']['url'] ) ) {
			echo \Minimog_Image::get_elementor_attachment( [
				'settings' => $settings,
			] );
		} elseif ( ! empty( $thumbnail_id ) ) {
			$size = \Minimog_Image::elementor_parse_image_size( $settings, $image_size, 'image' );
			\Minimog_Image::the_attachment_by_id( [
				'id'   => $thumbnail_id,
				'size' => $size,
			] );
		} else {
			\Minimog_Templates::image_placeholder( 370, 500 );
		}
	}

	private function print_button( array $settings ) {
		if ( empty( $settings['button_text'] ) ) {
			return;
		}
		?>
		<div class="tm-button-wrapper">
			<div class="tm-button style-bottom-line">
				<div class="button-content-wrapper">
					<span class="button-text"><?php echo esc_html( $settings['button_text'] ); ?></span>
				</div>
			</div>
		</div>
		<?php
	}

	private function print_category_count( $text = true ) {
		?>
		<div class="category-count">
			<span class="cat-count-number"><?php echo intval( $this->category->count ); ?></span>

			<?php if ( $text ) : ?>
				<span
					class="cat-count-text"><?php echo esc_html( _n( 'item', 'items', $this->category->count, 'minimog' ) ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	// Style 01.
	private function print_category_info() {
		?>
		<div class="category-info">
			<div class="category-info-wrapper">
				<h5 class="category-name"><?php $this->print_category_text(); ?></h5>
				<?php $this->print_category_count(); ?>
			</div>
		</div>
		<?php
	}

	// Style 02.
	private function print_category_info_with_button() {
		?>
		<div class="category-info">
			<div class="category-info-wrapper">
				<h5 class="category-name"><?php $this->print_category_text(); ?></h5>
				<?php $this->print_category_count(); ?>
			</div>
			<div class="tm-button style-flat">
				<i class="far fa-arrow-right"></i>
			</div>
		</div>
		<?php
	}

	// Style 03, 04.
	private function print_category_info_small_count() {
		?>
		<div class="category-info">
			<h5 class="category-name">
				<span>
					<?php $this->print_category_text(); ?>
					<?php $this->print_category_count( false ); ?>
				</span>
			</h5>
		</div>
		<?php
	}

	// Style 05.
	private function print_category_info_button_only() {
		?>
		<div class="category-info">
			<div class="tm-button style-flat"><?php $this->print_category_text(); ?></div>
		</div>
		<?php
	}

	// Style 08.
	private function print_category_info_split() {
		?>
		<h5 class="category-name"><span><?php $this->print_category_text(); ?></span></h5>
		<?php $this->print_category_count(); ?>
		<?php
	}

	private function print_category_text( $echo = true ) {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['custom_category_text'] ) ) {
			$text = $settings['custom_category_text'];
		} else {
			$text = $this->category->name;
		}

		if ( $echo ) {
			echo esc_html( $text );
		} else {
			return $text;
		}
	}
}
