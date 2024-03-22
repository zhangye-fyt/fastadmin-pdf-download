<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Banner extends Base {

	public function get_name() {
		return 'tm-banner';
	}

	public function get_title() {
		return esc_html__( 'Banner', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-image-rollover';
	}

	public function get_keywords() {
		return [ 'banner' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_banner_badge_section();

		$this->add_box_style_section();

		$this->add_content_style_section();

		$this->register_common_button_style_section();

		$this->add_banner_badge_style_section();

		$this->update_controls();
	}

	private function update_controls() {
		$this->start_injection(
			[
				'type' => 'control',
				'at'   => 'before',
				'of'   => 'button_min_width',
			]
		);

		$this->add_responsive_control( 'button_wrapper_text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors'            => [
				'{{WRAPPER}} .tm-button-wrapper' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->end_injection();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
		] );

		$this->add_control( 'full_height', [
			'label'        => esc_html__( 'Full Column Height', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'prefix_class' => 'minimog-banner-h-100-',
		] );

		$this->add_responsive_control( 'box_content_min_height', [
			'label'      => esc_html__( 'Min Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-banner' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'minimog' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'minimog' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'minimog' ),
				'move-up'  => esc_html__( 'Move Up', 'minimog' ),
			],
			'default'      => '',
			'prefix_class' => 'minimog-animation-',
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'background_box',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .minimog-image .image',
		] );

		$this->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'minimog' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'minimog' ),
			'separator'   => 'before',
		] );

		$this->add_control( 'link_click', [
			'label'     => esc_html__( 'Apply Link On', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'box'    => esc_html__( 'Whole Box', 'minimog' ),
				'button' => esc_html__( 'Button Only', 'minimog' ),
			],
			'default'   => 'box',
			'condition' => [
				'link[url]!' => '',
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
		] );

		$this->add_control( 'title_text', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'This is the heading', 'minimog' ),
			'placeholder' => esc_html__( 'Enter your title', 'minimog' ),
			'description' => esc_html__( 'Wrap any words with &lt;mark&gt;&lt;/mark&gt; tag to make them highlight.', 'minimog' ),
			'label_block' => true,
		] );

		$this->add_control( 'title_size', [
			'label'   => esc_html__( 'Title HTML Tag', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
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
			'default' => 'h3',
		] );

		$this->add_control( 'sub_title_size', [
			'label'   => esc_html__( 'Sub Title HTML Tag', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
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
			'default' => 'h4',
		] );

		$this->add_control( 'sub_title_position', [
			'label'        => esc_html__( 'Sub Title Position', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'above_title' => esc_html__( 'Above title', 'minimog' ),
				'below_title' => esc_html__( 'Below title', 'minimog' ),
			],
			'default'      => 'above_title',
			'prefix_class' => 'sub-title-position-',
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'minimog' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		$this->add_group_control( Group_Control_Button::get_type(), [
			'name'           => 'button',
			// Use box link instead of.
			'exclude'        => [
				'link',
			],
			'fields_options' => [
				'style' => [
					'default' => 'bottom-line',
				],
				'text'  => [
					'default' => esc_html__( 'Shop Now', 'minimog' ),
				],
			],
		] );

		$this->end_controls_section();
	}

	private function add_banner_badge_section() {
		$this->start_controls_section( 'banner_badge_section', [
			'label' => esc_html__( 'Badge', 'minimog' ),
		] );

		$this->add_control( 'badge_sub_text', [
			'label'       => esc_html__( 'Sub Text', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'placeholder' => esc_html__( 'Enter your sub-text', 'minimog' ),
			'label_block' => true,
		] );

		$this->add_control( 'badge_main_text', [
			'label'       => esc_html__( 'Main Text', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'placeholder' => esc_html__( 'Enter your main text', 'minimog' ),
			'label_block' => true,
		] );

		$this->add_control( 'badge_image', [
			'label'   => esc_html__( 'Choose Image', 'minimog' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [],
		] );

		$this->end_controls_section();
	}

	private function add_box_style_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
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
				'{{WRAPPER}} .minimog-box'                => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .minimog-box .minimog-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_content_max_width', [
			'label'      => esc_html__( 'Wrapper Width', 'minimog' ),
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
			'options'              => Widget_Utils::get_control_options_vertical_alignment_full(),
			'selectors_dictionary' => [
				'top'     => 'flex-start',
				'middle'  => 'center',
				'bottom'  => 'flex-end',
				'stretch' => 'space-between',
			],
			'selectors'            => [
				'{{WRAPPER}} .content-wrap' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->start_controls_tabs( 'box_style_tabs' );

		$this->start_controls_tab( 'box_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .minimog-box',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'box_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'hover_box_shadow',
			'selector' => '{{WRAPPER}} .minimog-box:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_content_style_section() {
		$this->start_controls_section( 'content_style_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'content_inner_width', [
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
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .content-wrap__inner' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'heading_sub_title', [
			'label'     => esc_html__( 'Sub Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'sub_title_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => 'px',
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .sub-title'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'sub_title_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => 'px',
			'selectors'  => [
				'{{WRAPPER}} .sub-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'sub_title_typography',
			'selector' => '{{WRAPPER}} .sub-title',
		] );

		$this->add_control( 'sub_title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .sub-title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'sub_title_bg_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .sub-title' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'sub_title_margin_bottom', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
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
				'{{WRAPPER}} .minimog-banner' => '--sub-title-spacing: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'heading_title', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'selector' => '{{WRAPPER}} .title',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'highlight_heading', [
			'label'     => esc_html__( 'Highlight Words', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_highlight',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .title mark',
		] );

		$this->add_group_control( Group_Control_Text_Stroke::get_type(), [
			'name'     => 'title_highlight_text_stroke',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .title mark',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_highlight',
			'selector' => '{{WRAPPER}} .title mark',
		] );

		$this->end_controls_section();
	}

	private function add_banner_badge_style_section() {
		$this->start_controls_section( 'banner_badge_style_section', [
			'label' => esc_html__( 'Badge', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'background_badge',
			'selector' => '{{WRAPPER}} .minimog-banner__badge',
		] );

		$this->add_responsive_control( 'badge_padding', [
			'label'       => esc_html__( 'Padding', 'minimog' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'size_units'  => [ 'px', '%', 'em' ],
			'placeholder' => [
				'top'    => '10',
				'bottom' => '10',
				'left'   => '10',
				'right'  => '10',
			],
			'selectors'   => [
				'body:not(.rtl) {{WRAPPER}} .minimog-banner__badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-banner__badge'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_margin', [
			'label'       => esc_html__( 'Margin', 'minimog' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'size_units'  => [ 'px', '%', 'em' ],
			'placeholder' => [
				'top'    => '34',
				'bottom' => '34',
				'left'   => '39',
				'right'  => '39',
			],
			'selectors'   => [
				'body:not(.rtl) {{WRAPPER}} .minimog-banner__badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-banner__badge'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_radius', [
			'label'      => esc_html__( 'Radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-banner__badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_width', [
			'label'     => esc_html__( 'Width', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-banner .minimog-banner__badge' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_pos_x', [
			'label'      => esc_html__( 'Position X', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => -100,
					'max' => 100,
				],
				'px' => [
					'min' => -1500,
					'max' => 1500,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-banner__badge' => 'right: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_pos_y', [
			'label'      => esc_html__( 'Position Y', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => -100,
					'max' => 100,
				],
				'px' => [
					'min' => -1500,
					'max' => 1500,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-banner__badge' => 'top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'heading_badge_sub_text', [
			'label'     => esc_html__( 'Sub Text', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'badge_sub_text_typography',
			'selector' => '{{WRAPPER}} .minimog-banner__badge .sub-text',
		] );

		$this->add_control( 'badge_sub_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-banner__badge .sub-text' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'badge_sub_text_spacing', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-banner__badge .sub-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'heading_badge_main_text', [
			'label'     => esc_html__( 'Main Text', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'badge_main_text_typography',
			'selector' => '{{WRAPPER}} .minimog-banner__badge .main-text',
		] );

		$this->add_control( 'badge_main_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-banner__badge .main-text' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'heading_badge_image', [
			'label'     => esc_html__( 'Image', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'badge_image_width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-banner .badge-image img' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_image_spacing', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-banner .badge-image' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'box', 'class', 'minimog-banner minimog-box' );

		$box_tag = 'div';

		if ( ! empty( $settings['link']['url'] ) && 'box' === $settings['link_click'] ) {
			$box_tag = 'a';

			$this->add_render_attribute( 'box', 'class', 'link-secret' );
			$this->add_link_attributes( 'box', $settings['link'] );
		}

		$this->add_render_attribute( 'box_background', 'class', 'image' );

		$background_image_url = $this->parse_background_image_url( $settings, 'background_box' );

		$this->add_render_lazyload_attributes( 'box_background', $background_image_url );
		?>
		<?php printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( 'box' ) ); ?>
		<div class="minimog-image">
			<div <?php $this->print_render_attribute_string( 'box_background' ); ?>></div>
		</div>
		<div class="content-wrap">
			<div class="content-wrap__inner">
				<?php $this->print_sub_title( $settings, 'above_title' ); ?>
				<?php $this->print_title( $settings ); ?>
				<?php $this->print_sub_title( $settings, 'below_title' ); ?>
			</div>
			<?php $this->render_common_button(); ?>
		</div>
		<?php $this->print_badge( $settings ); ?>
		<?php printf( '</%1$s>', $box_tag ); ?>
		<?php
	}

	private function print_sub_title( array $settings, $template_position = 'above_title' ) {
		if ( empty( $settings['sub_title_text'] ) ) {
			return;
		}

		if ( empty( $settings['sub_title_position'] ) || $settings['sub_title_position'] !== $template_position ) {
			return;
		}

		$this->add_render_attribute( 'sub_title_text', 'class', 'sub-title' );

		$this->add_inline_editing_attributes( 'sub_title_text', 'none' );

		$sub_title_html = $settings['sub_title_text'];

		printf( '<%1$s %2$s>%3$s</%1$s>', $settings['sub_title_size'], $this->get_render_attribute_string( 'sub_title_text' ), $sub_title_html );
	}

	private function print_title( array $settings ) {
		if ( empty( $settings['title_text'] ) ) {
			return;
		}

		$this->add_render_attribute( 'title_text', 'class', 'title' );

		$this->add_inline_editing_attributes( 'title_text', 'none' );

		$title_html = $settings['title_text'];

		printf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title_text' ), $title_html );
	}

	private function print_badge( array $settings ) {
		if (
			empty( $settings['badge_sub_text'] )
			&& empty( $settings['badge_main_text'] )
			&& empty( $settings['badge_image']['url'] )
		) {
			return;
		}

		$this->add_render_attribute( 'badge_sub_text', 'class', [ 'sub-text' ] );
		$this->add_render_attribute( 'badge_main_text', 'class', [ 'main-text' ] );

		$this->add_inline_editing_attributes( 'badge_sub_text', 'none' );
		$this->add_inline_editing_attributes( 'badge_main_text', 'none' );

		?>
		<div class="minimog-banner__badge">
			<?php if ( ! empty( $settings['badge_sub_text'] ) ) : ?>
				<span <?php $this->print_render_attribute_string( 'badge_sub_text' ) ?>>
					<?php echo esc_html( $settings['badge_sub_text'] ); ?>
				</span>
			<?php endif; ?>

			<?php if ( ! empty( $settings['badge_main_text'] ) ) : ?>
				<span <?php $this->print_render_attribute_string( 'badge_main_text' ) ?>>
					<?php echo esc_html( $settings['badge_main_text'] ); ?>
				</span>
			<?php endif; ?>
			<?php if ( ! empty( $settings['badge_image']['url'] ) ) : ?>
				<div class="badge-image">
					<?php echo \Minimog_Image::get_elementor_attachment( [
						'settings'  => $settings,
						'image_key' => 'badge_image',
					] ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function content_template() {
		// @formatter:off
		?>
		<#
		var tag = 'div';

		view.addRenderAttribute( 'box', 'class', 'minimog-banner minimog-box' );

		if ( settings.link.url && 'box' === settings.link_click ) {
			tag = 'a';

			view.addRenderAttribute( 'box', 'class', 'link-secret' );
			view.addRenderAttribute( 'box', 'href', settings.link.url );
		}

		#>
		<{{{tag}}} {{{ view.getRenderAttributeString( 'box' ) }}}>
			<div class="minimog-image">
				<div class="image"></div>
			</div>
			<div class="content-wrap">
				<div class="content-wrap__inner">
				<# if ( '' !== settings.sub_title_text && 'above_title' === settings.sub_title_position ) { #>
					<#
					view.addRenderAttribute( 'sub_title_text', 'class', ['sub-title'] );
					view.addInlineEditingAttributes( 'sub_title_text', 'none' );
					#>

					<{{{ settings.sub_title_size }}} {{{ view.getRenderAttributeString( 'sub_title_text' ) }}}>{{{ settings.sub_title_text }}}</{{{ settings.sub_title_size }}}>
				<# } #>

				<# if ( '' !== settings.title_text ) { #>
					<#
					view.addRenderAttribute( 'title_text', 'class', ['title'] );
					view.addInlineEditingAttributes( 'title_text', 'none' );
					#>

					<{{{ settings.title_size }}} {{{ view.getRenderAttributeString( 'title_text' ) }}}>{{{ settings.title_text }}}</{{{ settings.title_size }}}>
				<# } #>

				<# if ( '' !== settings.sub_title_text && 'below_title' === settings.sub_title_position ) { #>
					<#
					view.addRenderAttribute( 'sub_title_text', 'class', ['sub-title'] );
					view.addInlineEditingAttributes( 'sub_title_text', 'none' );
					#>

					<{{{ settings.sub_title_size }}} {{{ view.getRenderAttributeString( 'sub_title_text' ) }}}>{{{ settings.sub_title_text }}}</{{{ settings.sub_title_size }}}>
				<# } #>
				</div>

				<# if ( settings.button_text || settings.button_icon.value ) { #>
					<#

					var buttonIconHTML = elementor.helpers.renderIcon( view, settings.button_icon, { 'aria-hidden': true }, 'i' , 'object' );
					var buttonTag = 'div';

					view.addRenderAttribute( 'button', 'class', 'tm-button style-' + settings.button_style );
					view.addRenderAttribute( 'button', 'class', 'tm-button-' + settings.button_size );

					if ( '' !== settings.link.url && 'button' === settings.link_click ) {
						buttonTag = 'a';
						view.addRenderAttribute( 'button', 'href', '#' );
					}

					if ( settings.button_icon.value ) {
						view.addRenderAttribute( 'button', 'class', 'icon-' + settings.button_icon_align );
					}

					view.addRenderAttribute( 'button-icon', 'class', 'button-icon' );

					view.addRenderAttribute( 'button-icon', 'class', 'minimog-icon icon' );

					if ( 'svg' === settings.button_icon.library ) {
						view.addRenderAttribute( 'button-icon', 'class', 'minimog-svg-icon svg-icon' );
					}

					view.addRenderAttribute( 'button-icon', 'class', 'minimog-solid-icon' );
					#>
					<div class="tm-button-wrapper">
						<{{{ buttonTag }}} {{{ view.getRenderAttributeString( 'button' ) }}}>

							<div class="button-content-wrapper">
								<# if ( buttonIconHTML.rendered && 'left' === settings.button_icon_align ) { #>
									<span {{{ view.getRenderAttributeString( 'button-icon' ) }}}>
										{{{ buttonIconHTML.value }}}
									</span>
								<# } #>

								<# if ( settings.button_text ) { #>
									<span class="button-text">
										{{{ settings.button_text }}}

										<# if ( settings.button_style == 'bottom-line-winding' ) { #>
											<span class="line-winding">
												<svg width="42" height="6" viewBox="0 0 42 6" fill="none"
													xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd"
														d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
														fill="#E8C8B3"/>
												</svg>
											</span>
										<# } #>
									</span>
								<# } #>

								<# if ( buttonIconHTML.rendered && 'right' === settings.button_icon_align ) { #>
									<span {{{ view.getRenderAttributeString( 'button-icon' ) }}}>
										{{{ buttonIconHTML.value }}}
									</span>
								<# } #>
							</div>
						</{{{ buttonTag }}}>
					</div>
				<# } #>
			</div>
			<# if ( '' !== settings.badge_sub_text || '' !== settings.badge_main_text || '' !== settings.badge_image.url ) { #>
				<#
				view.addRenderAttribute( 'badge_sub_text', 'class', ['sub-text'] );
				view.addRenderAttribute( 'badge_main_text', 'class', ['main-text'] );
				view.addInlineEditingAttributes( 'badge_sub_text', 'none' );
				view.addInlineEditingAttributes( 'badge_main_text', 'none' );
				#>
				<div class="minimog-banner__badge">
					<# if ( '' !== settings.badge_sub_text ) { #>
						<span {{{ view.getRenderAttributeString( 'badge_sub_text' ) }}}>{{{ settings.badge_sub_text }}}</span>
					<#}#>

					<# if ( '' !== settings.badge_main_text ) { #>
						<span {{{ view.getRenderAttributeString( 'badge_main_text' ) }}}>{{{ settings.badge_main_text }}}</span>
					<# } #>

					<# if ( '' !== settings.badge_image.url ) { #>
						<div class="badge-image"><img src="{{{ settings.badge_image.url }}}"></div>
					<# } #>
				</div>
			<# } #>
		</{{{tag}}}>
		<?php
		// @formatter:off
	}
}
