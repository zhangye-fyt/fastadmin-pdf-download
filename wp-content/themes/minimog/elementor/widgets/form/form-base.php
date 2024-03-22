<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

abstract class Form_Base extends Base {

	public function get_icon_part() {
		return 'eicon-form-horizontal';
	}

	protected function add_field_style_section() {
		$this->start_controls_section( 'form_field_style_section', [
			'label' => esc_html__( 'Field', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'field_margin', [
			'label'       => esc_html__( 'Wrapper Margin', 'minimog' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'size_units'  => [ 'px', '%' ],
			'selectors'   => [
				'body:not(.rtl) {{WRAPPER}} .form-field' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .form-field'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'description' => esc_html__( 'The Margin just apply on fields that have class "form-field".', 'minimog' ),
		] );

		$this->add_control( 'form_field_style_alert', [
			'type'            => Controls_Manager::RAW_HTML,
			'content_classes' => 'elementor-control-field-description',
			'raw'             => wp_kses( __( 'These styles just apply on fields that have class "form-input"', 'minimog' ), 'minimog-default' ),
			'separator'       => 'before'
		] );

		$this->add_responsive_control( 'field_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .form-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .form-input'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'field_border_width', [
			'label'       => esc_html__( 'Border Width', 'minimog' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'placeholder' => '1',
			'size_units'  => [ 'px' ],
			'selectors'   => [
				'{{WRAPPER}} .form-input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'field_border_radius', [
			'label'       => esc_html__( 'Border Radius', 'minimog' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'placeholder' => '5',
			'size_units'  => [ 'px', '%' ],
			'selectors'   => [
				'{{WRAPPER}} .form-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'field_typography',
			'label'    => esc_html__( 'Field Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .form-input',
		] );

		$this->start_controls_tabs( 'field_colors_tabs' );

		$this->start_controls_tab( 'field_colors_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'field_text_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'field_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'field_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'field_colors_focus_tab', [
			'label' => esc_html__( 'Focus', 'minimog' ),
		] );

		$this->add_control( 'field_text_focus_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input:focus' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'field_border_focus_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input:focus' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'field_background_focus_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input:focus' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'placeholder_style_heading', [
			'label'     => esc_html__( 'Placeholder', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'field_placeholder_color', [
			'label'     => esc_html__( 'Placeholder Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-input::placeholder' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'field_placeholder_typography',
			'label'    => esc_html__( 'Placeholder Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .form-input::placeholder',
		] );

		$this->end_controls_section();
	}

	protected function add_button_style_section() {
		$this->start_controls_section( 'form_button_style_section', [
			'label' => esc_html__( 'Button', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'button_align', [
			'label'        => esc_html__( 'Alignment', 'minimog' ),
			'type'         => Controls_Manager::CHOOSE,
			'options'      => [
				'start'   => [
					'title' => esc_html__( 'Left', 'minimog' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center'  => [
					'title' => esc_html__( 'Center', 'minimog' ),
					'icon'  => 'eicon-text-align-center',
				],
				'end'     => [
					'title' => esc_html__( 'Right', 'minimog' ),
					'icon'  => 'eicon-text-align-right',
				],
				'stretch' => [
					'title' => esc_html__( 'Justified', 'minimog' ),
					'icon'  => 'eicon-text-align-justify',
				],
			],
			'default'      => 'stretch',
			'prefix_class' => 'minimog%s-button-align-',
		] );

		$this->add_responsive_control( 'button_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .form-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .form-submit'       => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .form-submit button, body:not(.rtl) {{WRAPPER}} .form-submit input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .form-submit button, body.rtl {{WRAPPER}} .form-submit input'             => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_border_width', [
			'label'       => esc_html__( 'Border Width', 'minimog' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'placeholder' => '1',
			'size_units'  => [ 'px' ],
			'selectors'   => [
				'{{WRAPPER}} .form-submit button, {{WRAPPER}} .form-submit input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_border_radius', [
			'label'       => esc_html__( 'Border Radius', 'minimog' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'placeholder' => '5',
			'size_units'  => [ 'px', '%' ],
			'selectors'   => [
				'{{WRAPPER}} .form-submit button, {{WRAPPER}} .form-submit input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_height', [
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
				'{{WRAPPER}} .form-submit button, {{WRAPPER}} .form-submit input' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
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
				'{{WRAPPER}} .form-submit button, {{WRAPPER}} .form-submit input' => 'min-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'button_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .form-submit button, {{WRAPPER}} .form-submit input',
		] );

		$this->start_controls_tabs( 'button_colors_tabs' );

		$this->start_controls_tab( 'button_colors_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'button_text_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-submit button, {{WRAPPER}} .form-submit input' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-submit button, {{WRAPPER}} .form-submit input' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-submit button, {{WRAPPER}} .form-submit input' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_colors_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'button_text_hover_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-submit button:hover, {{WRAPPER}} .form-submit input:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_border_hover_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-submit button:hover, {{WRAPPER}} .form-submit input:hover' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_background_hover_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .form-submit button:hover, {{WRAPPER}} .form-submit input:hover' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
}
