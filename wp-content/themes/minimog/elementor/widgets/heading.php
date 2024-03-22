<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Heading extends Base {

	public function get_name() {
		return 'tm-heading';
	}

	public function get_title() {
		return esc_html__( 'Modern Heading', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-heading';
	}

	public function get_keywords() {
		return [ 'heading', 'title', 'text' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_title_section();

		$this->add_sub_title_section();

		$this->add_wrapper_style_section();

		$this->add_title_style_section();

		$this->add_divider_style_section();

		$this->add_divider_wave_style_section();

		$this->add_description_style_section();

		$this->add_sub_title_style_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				''         => esc_html__( 'None', 'minimog' ),
				'style-01' => '01',
				'style-02' => '02',
				'style-03' => '03',
				'style-04' => '04',
				'style-05' => '05',
			),
			'default' => 'style-01',
		] );

		$this->add_control( 'title', [
			'label'       => esc_html__( 'Primary Heading', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Enter your title', 'minimog' ),
			'default'     => esc_html__( 'Add Your Heading Text Here', 'minimog' ),
			'description' => esc_html__( 'Wrap any words with &lt;mark&gt;&lt;/mark&gt; tag to make them highlight.', 'minimog' ),
			'separator'   => 'before',
		] );

		$this->add_control( 'highlight_style', [
			'label'   => esc_html__( 'Highlight Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'style-01' => '01',
				'style-02' => '02',
			),
			'default' => 'style-01',
		] );

		$this->add_control( 'description', [
			'label'     => esc_html__( 'Description', 'minimog' ),
			'type'      => Controls_Manager::WYSIWYG,
			'dynamic'   => [
				'active' => true,
			],
			'separator' => 'before',
		] );

		$this->add_control( 'sub_title_text', [
			'label'   => esc_html__( 'Secondary Heading', 'minimog' ),
			'type'    => Controls_Manager::TEXTAREA,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->end_controls_section();
	}

	private function add_title_section() {
		$this->start_controls_section( 'title_section', [
			'label' => esc_html__( 'Primary Heading', 'minimog' ),
		] );

		$this->add_control( 'title_link', [
			'label'     => esc_html__( 'Link', 'minimog' ),
			'type'      => Controls_Manager::URL,
			'dynamic'   => [
				'active' => true,
			],
			'default'   => [
				'url' => '',
			],
			'separator' => 'before',
		] );

		$this->add_control( 'title_link_animate', [
			'label'        => esc_html__( 'Link Animate', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''                  => esc_html__( 'None', 'minimog' ),
				'animate-border'    => esc_html__( 'Animate Border', 'minimog' ),
				'animate-border-02' => esc_html__( 'Animate Border 02', 'minimog' ),
			],
			'default'      => '',
			'prefix_class' => 'minimog-link-',
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
			'default' => 'h2',
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'minimog' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		// Divider.
		$this->add_control( 'divider_enable', [
			'label' => esc_html__( 'Display Divider', 'minimog' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'divider_style', [
			'label'     => esc_html__( 'Divider Style', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'line' => esc_html__( 'Line', 'minimog' ),
				'wave' => esc_html__( 'Wave', 'minimog' ),
			),
			'default'   => 'wave',
			'condition' => [
				'divider_enable' => 'yes',
			],
		] );

		$this->end_controls_section();
	}

	private function add_sub_title_section() {
		$this->start_controls_section( 'sub_title_section', [
			'label' => esc_html__( 'Secondary Heading', 'minimog' ),
		] );

		$this->add_control( 'sub_title_size', [
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
			'default' => 'h4',
		] );

		$this->end_controls_section();
	}

	private function add_wrapper_style_section() {
		$this->start_controls_section( 'wrapper_style_section', [
			'tab'   => Controls_Manager::TAB_STYLE,
			'label' => esc_html__( 'Wrapper', 'minimog' ),
		] );

		$this->add_responsive_control( 'max_width', [
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
				'{{WRAPPER}} .tm-modern-heading' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align_full(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}}' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'alignment', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors'            => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}}',
			],
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
		] );

		$this->end_controls_section();
	}

	private function add_title_style_section() {
		$this->start_controls_section( 'title_style_section', [
			'label'     => esc_html__( 'Primary Heading', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'title!' => '',
			],
		] );

		$this->add_responsive_control( 'heading_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .heading-primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .heading-primary'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
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
				'{{WRAPPER}} .heading-primary' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .heading-primary',
		] );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), [
			'name'     => 'text_shadow',
			'selector' => '{{WRAPPER}} .heading-primary',
		] );

		$this->add_control( 'blend_mode', [
			'label'     => esc_html__( 'Blend Mode', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				''            => esc_html__( 'Normal', 'minimog' ),
				'multiply'    => 'Multiply',
				'screen'      => 'Screen',
				'overlay'     => 'Overlay',
				'darken'      => 'Darken',
				'lighten'     => 'Lighten',
				'color-dodge' => 'Color Dodge',
				'saturation'  => 'Saturation',
				'color'       => 'Color',
				'difference'  => 'Difference',
				'exclusion'   => 'Exclusion',
				'hue'         => 'Hue',
				'luminosity'  => 'Luminosity',
			],
			'selectors' => [
				'{{WRAPPER}} .heading-primary' => 'mix-blend-mode: {{VALUE}}',
			],
			'separator' => 'none',
		] );

		$this->start_controls_tabs( 'title_style_tabs' );

		$this->start_controls_tab( 'title_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .heading-primary',
		] );

		$this->add_control( 'title_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .heading-primary' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_hover',
			'selector' => '{{WRAPPER}} .heading-primary:hover > a',
		] );

		$this->add_control( 'title_background_color_hover', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .heading-primary:hover' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'highlight_heading', [
			'label'     => esc_html__( 'Highlight Words', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_highlight',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .heading-primary mark',
		] );

		$this->add_group_control( Group_Control_Text_Stroke::get_type(), [
			'name'     => 'title_highlight_text_stroke',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .heading-primary mark',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_highlight',
			'selector' => '{{WRAPPER}} .heading-primary mark',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'title_highlight_border',
			'label'    => esc_html__( 'Border', 'minimog' ),
			'selector' => '{{WRAPPER}} .heading-primary mark',
		] );

		$this->add_control( 'title_highlight_line_color', [
			'label'     => esc_html__( 'Line Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-modern-heading--highlight-style-02 mark:after' => 'background-color: {{VALUE}};',
			],
			'condition' => [
				'highlight_style' => 'style-02',
			],
		] );

		$this->add_responsive_control( 'title_highlight_line_thickness', [
			'label'      => esc_html__( 'Line Thickness', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 50,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-modern-heading--highlight-style-02 mark:after' => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'highlight_style' => 'style-02',
			],
		] );

		$this->add_responsive_control( 'title_highlight_line_rounded', [
			'label'      => esc_html__( 'Line Rounded', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 50,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-modern-heading--highlight-style-02 mark:after' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'highlight_style' => 'style-02',
			],
		] );

		$this->add_responsive_control( 'title_highlight_line_offset', [
			'label'      => esc_html__( 'Line Offset X', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => -30,
					'max'  => 30,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-modern-heading--highlight-style-02 mark:after' => 'bottom: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'highlight_style' => 'style-02',
			],
		] );

		/**
		 * Title Line Animate
		 */
		$line_condition = [
			'title_link_animate' => [
				'animate-border',
				'animate-border-02',
			],
		];

		$this->add_control( 'title_animate_line_heading', [
			'label'     => esc_html__( 'Line', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $line_condition,
		] );

		$this->add_responsive_control( 'title_animate_line_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 1,
					'max'  => 5,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .heading-primary a mark:before, {{WRAPPER}} .heading-primary a mark:after' => 'height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $line_condition,
		] );

		$this->add_control( 'title_animate_line_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .heading-primary a mark:before, {{WRAPPER}} .heading-primary a mark:after' => 'background: {{VALUE}};',
			],
			'condition' => $line_condition,
		] );

		$this->add_control( 'hover_title_animate_line_color', [
			'label'     => esc_html__( 'Hover Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .heading-primary a mark:after' => 'background: {{VALUE}};',
			],
			'condition' => [
				'title_link_animate' => [
					'animate-border',
				],
			],
		] );

		$this->end_controls_section();
	}

	private function add_divider_style_section() {
		$this->start_controls_section( 'divider_style_section', [
			'label'     => esc_html__( 'Divider', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'divider_enable' => 'yes',
				'divider_style'  => 'line',
			],
		] );

		$this->add_responsive_control( 'divider_spacing', [
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
				'{{WRAPPER}} .heading-divider-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'divider_width', [
			'label'          => esc_html__( 'Width', 'minimog' ),
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
				'{{WRAPPER}} .heading-divider' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'divider_height', [
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
				'{{WRAPPER}} .heading-divider' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'divider',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .heading-divider',
		] );

		$this->end_controls_section();
	}

	private function add_divider_wave_style_section() {
		$this->start_controls_section( 'divider_wave_style_section', [
			'label'     => esc_html__( 'Divider', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'divider_enable' => 'yes',
				'divider_style'  => 'wave',
			],
		] );

		$this->add_responsive_control( 'divider_wave_spacing', [
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
				'{{WRAPPER}} .heading-divider-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'divider_wave_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .heading-divider-wrap svg path' => 'fill: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_description_style_section() {
		$this->start_controls_section( 'description_style_section', [
			'label'     => esc_html__( 'Description', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'description!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .heading-description',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'description',
			'selector' => '{{WRAPPER}} .heading-description',
		] );

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
				'{{WRAPPER}} .heading-description-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'description_max_width', [
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
				'{{WRAPPER}} .heading-description' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_sub_title_style_section() {
		$this->start_controls_section( 'sub_title_style_section', [
			'label'     => esc_html__( 'Secondary Heading', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'sub_title_text!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'sub_title',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .heading-secondary',
		] );

		$this->start_controls_tabs( 'sub_title_style_tabs' );

		$this->start_controls_tab( 'sub_title_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'sub_title',
			'selector' => '{{WRAPPER}} .heading-secondary',
		] );

		$this->add_control( 'sub_title_bg_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .heading-secondary' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'sub_title_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'sub_title_hover',
			'selector' => '{{WRAPPER}} .heading-secondary:hover',
		] );

		$this->add_control( 'sub_title_hover_bg_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .heading-secondary:hover' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'sub_title_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .heading-secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->add_responsive_control( 'sub_title_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .heading-secondary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],

		] );

		$this->add_responsive_control( 'sub_title_spacing', [
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
				'{{WRAPPER}} .heading-secondary-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'sub_title_max_width', [
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
				'{{WRAPPER}} .heading-secondary' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'opacity', [
			'label' => esc_html__( 'Opacity', 'minimog' ),
			'type'  => Controls_Manager::TEXT,

			'selectors' => [
				'{{WRAPPER}} .heading-secondary' => 'opacity: {{VALUE}};',
			],
		] );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'tm-modern-heading',
		];

		if ( ! empty( $settings['style'] ) ) {
			$classes[] = 'tm-modern-heading--' . $settings['style'];
		}

		$classes[] = 'tm-modern-heading--highlight-' . $settings['highlight_style'];

		$this->add_render_attribute( 'wrapper', 'class', $classes );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php $this->print_sub_title( $settings ); ?>

			<?php $this->print_title( $settings ); ?>

			<?php $this->print_divider( $settings ); ?>

			<?php $this->print_description( $settings ); ?>
		</div>
		<?php
	}

	private function print_sub_title( array $settings ) {
		if ( empty( $settings['sub_title_text'] ) ) {
			return;
		}

		// .elementor-heading-title -> Default color from section + column.
		$this->add_render_attribute( 'sub_title', 'class', 'heading-secondary elementor-heading-title' );
		?>
		<div class="heading-secondary-wrap">
			<?php printf( '<%1$s %2$s>%3$s</%1$s>', $settings['sub_title_size'], $this->get_render_attribute_string( 'sub_title' ), $settings['sub_title_text'] ); ?>
		</div>
		<?php
	}

	private function print_title( array $settings ) {
		if ( empty( $settings['title'] ) ) {
			return;
		}

		// .elementor-heading-title -> Default color from section + column.
		$this->add_render_attribute( 'title', 'class', 'heading-primary elementor-heading-title' );

		$this->add_inline_editing_attributes( 'title' );

		$title = $settings['title'];

		if ( ! empty( $settings['title_link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['title_link'] );

			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}
		?>
		<div class="heading-primary-wrap">
			<?php printf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title' ), $title ); ?>
		</div>
		<?php
	}

	private function print_divider( array $settings ) {
		if ( empty( $settings['divider_enable'] ) || 'yes' !== $settings['divider_enable'] ) {
			return;
		}
		?>
		<div class="heading-divider-wrap heading-devider__<?php echo esc_attr( $settings['divider_style'] ); ?>">
			<?php if ( $settings['divider_style'] === 'wave' ) { ?>
				<svg width="42" height="6" viewBox="0 0 42 6" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" clip-rule="evenodd"
					      d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
					      fill="#F58448"/>
				</svg>
			<?php } elseif ( $settings['divider_style'] === 'line' ) { ?>
				<div class="heading-divider"></div>
			<?php } ?>
		</div>
		<?php
	}

	private function print_description( array $settings ) {
		if ( empty( $settings['description'] ) ) {
			return;
		}

		$this->add_render_attribute( 'description', 'class', 'heading-description' );
		?>
		<div class="heading-description-wrap">
			<div <?php $this->print_attributes_string( 'description' ); ?>>
				<?php echo wp_kses_post( $settings['description'] ); ?>
			</div>
		</div>
		<?php
	}

	protected function content_template() {
		// @formatter:off
		?>
		<#
		var title = settings.title;
		var title_html = '';

		view.addRenderAttribute( 'wrapper', 'class', 'tm-modern-heading tm-modern-heading--' + settings.style );
		view.addRenderAttribute( 'wrapper', 'class', 'tm-modern-heading--highlight-' + settings.highlight_style );

		if ( ''  !== title ) {
			if ( '' !== settings.title_link.url ) {
				title = '<a href="' + settings.title_link.url + '">' + title + '</a>';
			}

			view.addRenderAttribute( 'title', 'class', 'heading-primary elementor-heading-title' );

			view.addInlineEditingAttributes( 'title' );

			title_html = '<' + settings.title_size  + ' ' + view.getRenderAttributeString( 'title' ) + '>' + title + '</' + settings.title_size + '>';
			title_html = '<div class="heading-primary-wrap">' + title_html + '</div>';
		}

		var sub_title_html = '';

		if ( settings.sub_title_text ) {
			sub_title_html = settings.sub_title_text;

			view.addRenderAttribute( 'sub_title', 'class', 'heading-secondary elementor-heading-title' );

			sub_title_html = '<' + settings.sub_title_size  + ' ' + view.getRenderAttributeString( 'sub_title' ) + '>' + sub_title_html + '</' + settings.sub_title_size + '>';

			sub_title_html = '<div class="heading-secondary-wrap">' + sub_title_html + '</div>';
		}
		#>
		<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>

			<# if ( '' !== sub_title_html ) { #>
				<# print( sub_title_html ); #>
			<# } #>

			<# print( title_html ); #>

			<# if ( 'yes' === settings.divider_enable ) { #>
				<div class="heading-divider-wrap">
					<# if( settings.divider_style === 'wave') { #>
						<svg width="42" height="6" viewBox="0 0 42 6" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z" fill="#F58448"/>
						</svg>
					<#  } else if( settings.divider_style === 'line' ) { #>
						<div class="heading-divider"></div>
					<#  } #>
				</div>
			<# } #>

			<# if ( settings.description ) { #>
				<div class="heading-description-wrap">
					<div class="heading-description">{{{ settings.description }}}</div>
				</div>
			<# } #>
		</div>
		<?php
		// @formatter:off
	}
}
