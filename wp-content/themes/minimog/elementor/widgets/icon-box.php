<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Icon_Box extends Base {

	public function get_name() {
		return 'tm-icon-box';
	}

	public function get_title() {
		return esc_html__( 'Modern Icon Box', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return [ 'icon box', 'box icon', 'icon', 'box' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_icon_section();

		$this->add_content_section();

		$this->add_button_section();

		// Style
		$this->add_box_style_section();

		$this->add_icon_style_section();

		$this->add_badge_style_section();

		$this->add_title_style_section();

		$this->add_title_divider_style();

		$this->add_description_style_section();

		$this->register_common_button_style_section();
	}

	protected function add_layout_section() {
		$this->start_controls_section( 'icon_box_section', [
			'label' => esc_html__( 'Icon Box', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''   => esc_html__( 'None', 'minimog' ),
				'01' => '01',
			],
			'default'      => '',
			'prefix_class' => 'minimog-icon-box--style-',
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

		$this->end_controls_section();
	}

	protected function add_icon_section() {
		$this->start_controls_section( 'icon_section', [
			'label' => esc_html__( 'Icon', 'minimog' ),
		] );

		$this->add_control( 'icon_type', [
			'label'   => esc_html__( 'Icon Type', 'minimog' ),
			'type'    => Controls_Manager::CHOOSE,
			'default' => 'icon',
			'options' => [
				'icon'  => [
					'title' => esc_html__( 'Icon', 'minimog' ),
					'icon'  => 'eicon-favorite',
				],
				'image' => [
					'title' => esc_html__( 'Image', 'minimog' ),
					'icon'  => 'eicon-image',
				],
			],
			'toggle'  => false,
		] );

		$this->add_control( 'icon', [
			'label'      => esc_html__( 'Icon', 'minimog' ),
			'show_label' => false,
			'type'       => Controls_Manager::ICONS,
			'default'    => [
				'value'   => 'fas fa-star',
				'library' => 'fa-solid',
			],
			'condition'  => [
				'icon_type' => 'icon',
			],
		] );

		$this->add_control( 'image', [
			'label'      => esc_html__( 'Choose Image', 'minimog' ),
			'show_label' => false,
			'type'       => Controls_Manager::MEDIA,
			'condition'  => [
				'icon_type' => 'image',
			],
			'classes'    => 'minimog-control-media-auto',
		] );

		$this->add_control( 'view', [
			'label'        => esc_html__( 'View', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'default' => esc_html__( 'Default', 'minimog' ),
				'stacked' => esc_html__( 'Stacked', 'minimog' ),
				'bubble'  => esc_html__( 'Bubble', 'minimog' ),
			],
			'default'      => 'default',
			'prefix_class' => 'minimog-view-',
		] );

		$this->add_control( 'shape', [
			'label'        => esc_html__( 'Shape', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'circle' => esc_html__( 'Circle', 'minimog' ),
				'square' => esc_html__( 'Square', 'minimog' ),
			],
			'default'      => 'circle',
			'prefix_class' => 'minimog-shape-',
		] );

		$this->add_control( 'position', [
			'label'        => esc_html__( 'Position', 'minimog' ),
			'type'         => Controls_Manager::CHOOSE,
			'default'      => 'top',
			'options'      => [
				'left'  => [
					'title' => esc_html__( 'Left', 'minimog' ),
					'icon'  => 'eicon-h-align-left',
				],
				'top'   => [
					'title' => esc_html__( 'Top', 'minimog' ),
					'icon'  => 'eicon-v-align-top',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'minimog' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'prefix_class' => 'minimog-icon-box--icon-',
			'toggle'       => false,
		] );

		$this->add_control( 'content_vertical_alignment', [
			'label'        => esc_html__( 'Vertical Alignment', 'minimog' ),
			'type'         => Controls_Manager::CHOOSE,
			'options'      => Widget_Utils::get_control_options_vertical_alignment(),
			'default'      => 'top',
			'prefix_class' => 'minimog-icon-box--vertical-align-',
			'condition'    => [
				'position!' => 'top',
			],
		] );

		$this->add_control( 'icon_top_mobile', [
			'label'        => esc_html__( 'Icon Top', 'minimog' ),
			'description'  => esc_html__( 'Make icon on top on mobile.', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'minimog' ),
			'label_off'    => esc_html__( 'No', 'minimog' ),
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => [
				'position' => [ 'left', 'right' ],
			],
		] );

		$this->add_control( 'icon_badge', [
			'label'       => esc_html__( 'Badge', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'placeholder' => '3',
			'label_block' => false,
			'separator'   => 'before',
		] );

		$this->end_controls_section();
	}

	protected function add_content_section() {
		$this->start_controls_section( 'icon_title_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
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
		] );

		$this->add_control( 'title_size', [
			'label'   => esc_html__( 'HTML Tag', 'minimog' ),
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

		// Divider.
		$this->add_control( 'title_divider_enable', [
			'label' => esc_html__( 'Display Divider', 'minimog' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'description_heading', [
			'label'     => esc_html__( 'Description', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'description_text', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'show_label'  => false,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'placeholder' => esc_html__( 'Enter your description', 'minimog' ),
		] );

		$this->end_controls_section();
	}

	protected function add_button_section() {
		$this->start_controls_section( 'icon_button_section', [
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
				'style' => [
					'default' => 'text',
				],
			],
		] );
		$this->end_controls_section();
	}

	protected function add_icon_svg_animate_section() {
		$this->start_controls_section( 'icon_svg_animate_section', [
			'label'     => esc_html__( 'Icon SVG Animate', 'minimog' ),
			'condition' => [
				'icon[library]' => 'svg',
			],
		] );

		$this->add_control( 'icon_svg_animate_alert', [
			'type'            => Controls_Manager::RAW_HTML,
			'content_classes' => 'elementor-control-field-description',
			'raw'             => esc_html__( 'Note: Animate works only with Stroke SVG Icon.', 'minimog' ),
			'separator'       => 'after',
		] );

		$this->add_control( 'icon_svg_animate', [
			'label' => esc_html__( 'SVG Animate', 'minimog' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'icon_svg_animate_play_on_hover', [
			'label' => esc_html__( 'Play on hover', 'minimog' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'icon_svg_animate_type', [
			'label'   => esc_html__( 'Type', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'delayed'  => esc_html__( 'Delayed', 'minimog' ),
				'sync'     => esc_html__( 'Sync', 'minimog' ),
				'oneByOne' => esc_html__( 'One By One', 'minimog' ),
			],
			'default' => 'delayed',
		] );

		$this->add_control( 'icon_svg_animate_duration', [
			'label'   => esc_html__( 'Transition Duration', 'minimog' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 120,
		] );

		$this->end_controls_section();
	}

	protected function add_box_style_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align_full(),
			'selectors'            => [
				'{{WRAPPER}} .icon-box-wrapper' => 'text-align: {{VALUE}};',
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
				'body:not(.rtl) {{WRAPPER}} .tm-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-icon-box'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
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
				'{{WRAPPER}} .tm-icon-box' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_min_height', [
			'label'      => esc_html__( 'Min Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 800,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-icon-box' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'              => 'center',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}}',
			],
		] );

		$this->start_controls_tabs( 'box_colors' );

		$this->start_controls_tab( 'box_colors_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .tm-icon-box',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .tm-icon-box',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .tm-icon-box',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'box_colors_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box:before',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_hover_border',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'box_line_heading', [
			'label'     => esc_html__( 'Special Line', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'style' => [ '02' ],
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'box_line',
			'selector'  => '{{WRAPPER}}.minimog-icon-box-style-02 .tm-icon-box:after',
			'condition' => [
				'style' => [ '02' ],
			],
		] );

		$this->end_controls_section();
	}

	protected function add_icon_style_section() {
		$this->start_controls_section( 'icon_style_section', [
			'label' => esc_html__( 'Icon', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'icon_wrap_height', [
			'label'     => esc_html__( 'Wrap Height', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-icon-wrap' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab( 'icon_colors_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon',
			'selector' => '{{WRAPPER}} .icon',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_colors_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_icon',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover .icon',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'icon_space', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}}.minimog-icon-box--icon-right .minimog-icon-wrap'          => 'margin-left: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.minimog-icon-box--icon-left .minimog-icon-wrap'           => 'margin-right: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}.minimog-icon-box--icon-top .minimog-icon-wrap'            => 'margin-bottom: {{SIZE}}{{UNIT}};',
				'(mobile){{WRAPPER}} .tm-icon-box--icon-top-mobile .minimog-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'     => esc_html__( 'Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-icon-view, {{WRAPPER}} .minimog-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_rotate', [
			'label'     => esc_html__( 'Rotate', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [
				'unit' => 'deg',
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
			],
		] );

		// Icon View Settings.
		$this->add_control( 'icon_view_heading', [
			'label'     => esc_html__( 'Icon View', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'view' => [ 'stacked', 'bubble' ],
			],
		] );

		$this->add_control( 'icon_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'selectors'  => [
				'{{WRAPPER}} .minimog-icon-view' => 'padding: {{SIZE}}{{UNIT}};',
			],
			'range'      => [
				'em' => [
					'min' => 0,
					'max' => 5,
				],
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'size_units' => [ 'em', 'px' ],
			'condition'  => [
				'view' => [ 'stacked' ],
			],
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'      => 'icon_view_border',
			'selector'  => '{{WRAPPER}} .minimog-icon-view',
			'condition' => [
				'view' => [ 'stacked' ],
			],
		] );

		$this->start_controls_tabs( 'icon_view_colors', [
			'condition' => [
				'view' => [ 'stacked', 'bubble' ],
			],
		] );

		$this->start_controls_tab( 'icon_view_colors_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'icon_view',
			'selector' => '{{WRAPPER}} .minimog-icon-view',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'icon_view',
			'selector' => '{{WRAPPER}} .minimog-icon-view',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_view_colors_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'icon_view_hover_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-icon-box:hover .minimog-icon-view' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'hover_icon_view',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover .minimog-icon-view',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'hover_icon_view',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover .minimog-icon-view',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function add_badge_style_section() {
		$this->start_controls_section( 'badge_style_section', [
			'label' => esc_html__( 'Icon Badge', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'badge',
			'selector' => '{{WRAPPER}} .minimog-icon-badge',
		] );

		$this->add_control( 'badge_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-icon-badge' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'badge_background_color', [
			'label'     => esc_html__( 'Background', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-icon-badge' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'badge_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-icon-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-icon-badge'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'badge_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-icon-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function add_title_style_section() {
		$this->start_controls_section( 'title_style_section', [
			'label' => esc_html__( 'Title', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'heading_max_width', [
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
					'max' => 1600,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .heading' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );


		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .heading',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
		] );

		$this->start_controls_tabs( 'title_colors' );

		$this->start_controls_tab( 'title_color_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .heading',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_color_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover .heading',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function add_title_divider_style() {
		$this->start_controls_section( 'title_divider_style_section', [
			'label'     => esc_html__( 'Title Divider', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'title_divider_enable' => 'yes',
			],
		] );

		$this->start_controls_tabs( 'title_divider_colors' );

		$this->start_controls_tab( 'title_divider_colors_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'title_divider',
			'selector' => '{{WRAPPER}} .heading-divider:before',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_divider_colors_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'hover_title_divider',
			'selector' => '{{WRAPPER}} .heading-divider:after',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function add_description_style_section() {
		$this->start_controls_section( 'description_style_section', [
			'label'     => esc_html__( 'Description', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'description_text!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description',
			'selector' => '{{WRAPPER}} .description',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
		] );

		$this->start_controls_tabs( 'description_colors' );

		$this->start_controls_tab( 'description_color_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'description',
			'selector' => '{{WRAPPER}} .description',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'description_color_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'description_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box:hover .description',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'description_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .description-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->add_responsive_control( 'description_max_width', [
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
				'{{WRAPPER}} .description' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'box', 'class', 'tm-icon-box minimog-box' );

		if ( 'yes' === $settings['icon_top_mobile'] ) {
			$this->add_render_attribute( 'box', 'class', 'tm-icon-box--icon-top-mobile' );
		}

		if ( ! empty( $settings['icon_svg_animate'] ) && 'yes' === $settings['icon_svg_animate'] ) {
			$vivus_settings = [
				'enable'        => $settings['icon_svg_animate'],
				'type'          => $settings['icon_svg_animate_type'],
				'duration'      => $settings['icon_svg_animate_duration'],
				'play_on_hover' => $settings['icon_svg_animate_play_on_hover'],
			];
			$this->add_render_attribute( 'box', 'data-vivus', wp_json_encode( $vivus_settings ) );
		}

		$box_tag = 'div';

		if ( ! empty( $settings['link']['url'] ) && 'box' === $settings['link_click'] ) {
			$box_tag = 'a';

			$this->add_render_attribute( 'box', 'class', 'link-secret' );
			$this->add_link_attributes( 'box', $settings['link'] );
		}
		?>
		<?php printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( 'box' ) ); ?>

		<div class="icon-box-wrapper">
			<?php $this->print_icon( $settings ); ?>

			<div class="icon-box-content">
				<?php $this->print_title( $settings ); ?>

				<?php $this->print_description( $settings ); ?>

				<?php $this->render_common_button(); ?>
			</div>
		</div>

		<?php printf( '</%1$s>', $box_tag ); ?>
		<?php
	}

	protected function content_template() {
		$id = uniqid( 'svg-gradient' );
		// @formatter:off
		?>
		<# var svg_id = '<?php echo esc_html( $id ); ?>'; #>

		<#
		view.addRenderAttribute( 'box', 'class', 'tm-icon-box minimog-box' );
		var box_tag = 'div';

		if ( '' !== settings.link.url && 'box' === settings.link_click ) {
			box_tag = 'a';

			view.addRenderAttribute( 'box', 'class', 'link-secret' );
			view.addRenderAttribute( 'box', 'href', '#' );
		}

		if ( 'yes' === settings.icon_top_mobile ) {
			view.addRenderAttribute( 'box', 'class', 'tm-icon-box--icon-top-mobile' );
		}

		view.addRenderAttribute( 'icon', 'class', 'minimog-icon icon');

		if ( 'svg' === settings.icon.library ) {
			view.addRenderAttribute( 'icon', 'class', 'minimog-svg-icon' );
		}

		if ( 'gradient' === settings.icon_color_type ) {
			view.addRenderAttribute( 'icon', 'class', 'minimog-gradient-icon' );
		} else {
			view.addRenderAttribute( 'icon', 'class', 'minimog-solid-icon' );
		}

		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
		#>
		<{{{ box_tag }}} {{{ view.getRenderAttributeString( 'box' ) }}}>
			<div class="icon-box-wrapper">
				<div class="minimog-icon-wrap">
					<div class="minimog-icon-view first">
						<div class="minimog-icon-view-inner">
							<div {{{ view.getRenderAttributeString( 'icon' ) }}}>
								<# if ( 'image' === settings.icon_type && settings.image.url) { #>
									<#
									var image = {
										id: settings.image.id,
										url: settings.image.url,
										size: 'full',
										model: view.getEditModel()
									};

									var image_url = elementor.imagesManager.getImageUrl( image );
									#>
									<img src="{{{ image_url }}}" alt="Image" />
								<# } else if ( 'icon' === settings.icon_type ) { #>
									<# if ( iconHTML.rendered ) { #>
										<#
										var stop_a = settings.icon_color_a_stop.size + settings.icon_color_a_stop.unit;
										var stop_b = settings.icon_color_b_stop.size + settings.icon_color_b_stop.unit;

										var iconValue = iconHTML.value;
										if ( typeof iconValue === 'string' ) {
											var strokeAttr = 'stroke="' + 'url(#' + svg_id + ')"';
											var fillAttr = 'fill="' + 'url(#' + svg_id + ')"';

											iconValue = iconValue.replace(new RegExp(/stroke="#(.*?)"/, 'g'), strokeAttr);
											iconValue = iconValue.replace(new RegExp(/fill="#(.*?)"/, 'g'), fillAttr);
										}
										#>
										<svg aria-hidden="true" focusable="false" class="svg-defs-gradient">
											<defs>
												<linearGradient id="{{{ svg_id }}}" x1="0%" y1="0%" x2="0%" y2="100%">
													<stop class="stop-a" offset="{{{ stop_a }}}"/>
													<stop class="stop-b" offset="{{{ stop_b }}}"/>
												</linearGradient>
											</defs>
										</svg>

										{{{ iconValue }}}
									<# } #>
								<# } #>

								<# if ( settings.icon_badge ) { #>
									<span class="minimog-icon-badge">{{{ settings.icon_badge }}}</span>
								<# } #>
							</div>
						</div>
					</div>

					<# if ( 'bubble' == settings.view ) { #>
						<div class="minimog-icon-view second"></div>
					<# } #>
				</div>
				<div class="icon-box-content">
					<# if ( settings.title_text ) { #>
						<#
						view.addRenderAttribute( 'title', 'class', 'heading' );
						#>
						<div class="heading-wrap">
							<{{{ settings.title_size }}} {{{ view.getRenderAttributeString( 'title' ) }}}>
								{{{ settings.title_text }}}
							</{{{ settings.title_size }}}>

							<# if ( 'yes' === settings.title_divider_enable ) { #>
								<div class="heading-divider-wrap">
									<div class="heading-divider"></div>
								</div>
							<# } #>
						</div>
					<# } #>

					<# if ( settings.description_text ) { #>
						<#
						view.addRenderAttribute( 'description', 'class', 'description' );
						#>
						<div class="description-wrap">
							<div {{{ view.getRenderAttributeString( 'description' ) }}}>
								{{{ settings.description_text }}}
							</div>
						</div>
					<# } #>

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
										<span class="button-text">{{{ settings.button_text }}}</span>
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
			</div>
		</{{{ box_tag }}}>
		<?php
		// @formatter:off
	}

	protected function print_icon( array $settings ) {
		$icon_type = ! empty( $settings['icon_type']) ?  $settings['icon_type'] : 'icon';

		$classes = [
			'minimog-icon',
			'icon',
		];

		$is_svg = isset( $settings['icon']['library'] ) && 'svg' === $settings['icon']['library'] ? true : false;

		if ( $is_svg ) {
			$classes[] = 'minimog-svg-icon';
		}

		if ( 'gradient' === $settings['icon_color_type'] ) {
			$classes[] = 'minimog-gradient-icon';
		} else {
			$classes[] = 'minimog-solid-icon';
		}

		$this->add_render_attribute( 'icon', 'class', $classes );
		?>
		<div class="minimog-icon-wrap">
			<div class="minimog-icon-view first">
				<div class="minimog-icon-view-inner">
					<div <?php $this->print_attributes_string( 'icon' ); ?>>
						<?php if ( 'icon' === $icon_type ) : ?>
							<?php $this->render_icon( $settings, $settings['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?>
						<?php elseif ( 'image' === $icon_type &&  ! empty( $settings['image']['url'] ) ) : ?>
							<?php echo \Minimog_Image::get_elementor_attachment( [
								'settings' => $settings,
							] ); ?>
						<?php endif; ?>

						<?php if ( ! empty( $settings['icon_badge'] ) ) : ?>
							<span class="minimog-icon-badge"><?php echo esc_html( $settings['icon_badge'] ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php if( 'bubble' === $settings['view'] ) { ?>
				<div class="minimog-icon-view second"></div>
			<?php } ?>
		</div>
		<?php
	}

	protected function print_title( array $settings ) {
		if ( empty( $settings['title_text'] ) ) {
			return;
		}

		$this->add_render_attribute( 'title', 'class', 'heading' );
		?>
		<div class="heading-wrap">
			<?php printf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title' ), $settings['title_text'] ); ?>

			<?php $this->print_title_divider( $settings ); ?>
		</div>
		<?php
	}

	protected function print_title_divider( array $settings ) {
		if ( empty( $settings['title_divider_enable'] ) || 'yes' !== $settings['title_divider_enable'] ) {
			return;
		}
		?>
		<div class="heading-divider-wrap">
			<div class="heading-divider"></div>
		</div>
		<?php
	}

	protected function print_description( array $settings ) {
		if ( empty( $settings['description_text'] ) ) {
			return;
		}

		$this->add_render_attribute( 'description', 'class', 'description' );
		?>
		<div class="description-wrap">
			<div <?php $this->print_attributes_string( 'description' ); ?>>
				<?php echo wp_kses_post($settings['description_text']); ?>
			</div>
		</div>
		<?php
	}
}
