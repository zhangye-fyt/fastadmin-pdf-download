<?php

namespace Minimog_Elementor;

use Elementor\Group_Control_Box_Shadow;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Modify_WPForms extends Modify_Base {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'elementor/element/wpforms/section_form/after_section_end', [
			$this,
			'section_form_summary_style',
		], 20 );

		add_action( 'elementor/element/wpforms/section_form/after_section_end', [
			$this,
			'section_form_style',
		], 30 );

		add_action( 'elementor/element/wpforms/section_form_style/after_section_end', [
			$this,
			'section_form_fields_style',
		] );

		add_action( 'elementor/element/wpforms/section_form_fields_style/after_section_end', [
			$this,
			'section_form_checkbox_style',
		] );

		add_action( 'elementor/element/wpforms/section_form_fields_style/after_section_end', [
			$this,
			'section_form_button_style',
		] );

		add_action( 'elementor/element/wpforms/section_form/before_section_end', [
			$this,
			'add_form_style_control',
		] );

		add_action( 'wpforms_display_fields_before', array( $this, 'render_newsletter_icon' ), 26 );
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function add_form_style_control( $element ) {
		$element->add_control( 'is_newsletter_form', [
			'label'        => esc_html__( 'Is Newsletter Form?', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'separator'    => 'before',
			'prefix_class' => 'minimog-wpforms-newsletter--',
		] );

		$element->add_control( 'form_style', [
			'label'        => esc_html__( 'Style', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'00' => esc_html__( 'None', 'minimog' ),
				'01' => '01',
				'02' => '02',
				'03' => '03',
				'04' => '04',
				'05' => '05',
				'06' => '06',
				'07' => '07',
				'08' => '08',
			],
			'default'      => '00',
			'prefix_class' => 'minimog-wpforms-style-',
		] );

		$element->add_control( 'form_skin', [
			'label'        => esc_html__( 'Skin', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'dark'  => esc_html__( 'Dark', 'minimog' ),
				'light' => esc_html__( 'Light', 'minimog' ),
			],
			'default'      => 'dark',
			'prefix_class' => 'minimog-wpforms-',
		] );
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function section_form_style( $element ) {
		$element->start_controls_section( 'section_form_style', [
			'label' => esc_html__( 'Form', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$element->add_responsive_control( 'column_gap', [
			'label'     => esc_html__( 'Columns Gap', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [],
			'range'     => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 2,
				],
			],
			'selectors' => [
				'body:not(.rtl) {{WRAPPER}} .wpforms-field-row-block' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
				'body:not(.rtl) {{WRAPPER}} .wpforms-field-row'       => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				'body.rtl {{WRAPPER}} .wpforms-field-row-block'       => 'padding-left: calc( {{SIZE}}{{UNIT}}/2 ); padding-right: calc( {{SIZE}}{{UNIT}}/2 );',
				'body.rtl {{WRAPPER}} .wpforms-field-row'             => 'margin-right: calc( -{{SIZE}}{{UNIT}}/2 ); margin-left: calc( -{{SIZE}}{{UNIT}}/2 );',
			],
		] );

		$element->add_responsive_control( 'row_gap', [
			'label'     => esc_html__( 'Rows Gap', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [],
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .wpforms-field' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
			],
		] );

		$element->add_responsive_control( 'form_width', [
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
				'{{WRAPPER}} .minimog-wpforms' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'form_alignment', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .elementor-widget-container' => 'justify-content: {{VALUE}}',
			],
		] );

		$element->add_responsive_control( 'form_text_align', [
			'label'     => esc_html__( 'Text Align', 'minimog' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_text_align(),
			'selectors' => [
				'{{WRAPPER}} .minimog-wpforms .wpforms-field-container .form-input'          => 'text-align: {{VALUE}}',
				'{{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="text"]'   => 'text-align: {{VALUE}}',
				'{{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="email"]'  => 'text-align: {{VALUE}}',
				'{{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="number"]' => 'text-align: {{VALUE}}',
				'{{WRAPPER}} .minimog-wpforms .wpforms-field-container select'               => 'text-align: {{VALUE}}',
				'{{WRAPPER}} .minimog-wpforms .wpforms-field-container textarea'             => 'text-align: {{VALUE}}',
			],
		] );

		$element->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'form_box_shadow',
			'selector' => '{{WRAPPER}} .minimog-wpforms',
		] );

		$element->end_controls_section();
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function section_form_fields_style( $element ) {
		$element->start_controls_section( 'section_form_fields_style', [
			'tab'   => Controls_Manager::TAB_STYLE,
			'label' => __( 'Fields', 'minimog' ),
		] );

		$field_selectors = '
			{{WRAPPER}} .minimog-wpforms .wpforms-field-container .form-input,
			{{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="text"],
			{{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="email"],
			{{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="number"],
			{{WRAPPER}} .minimog-wpforms .wpforms-field-container select
		';

		$field_selectors_rtl = '
			body.rtl {{WRAPPER}} .minimog-wpforms .wpforms-field-container .form-input,
			body.rtl {{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="text"],
			body.rtl {{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="email"],
			body.rtl {{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="number"],
			body.rtl {{WRAPPER}} .minimog-wpforms .wpforms-field-container select
		';

		$field_focus_selectors = '
			{{WRAPPER}} .minimog-wpforms .wpforms-field-container .form-input:focus,
			{{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="text"]:focus,
			{{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="email"]:focus,
			{{WRAPPER}} .minimog-wpforms .wpforms-field-container input[type="number"]:focus,
			{{WRAPPER}} .minimog-wpforms .wpforms-field-container select:focus
		';

		$element->add_responsive_control( 'min_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				$field_selectors                                                   => 'min-height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}}  .wpforms-container.minimog-wpforms .newsletter-icon' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'field_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				$field_selectors     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				$field_selectors_rtl => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'field_border_width', [
			'label'      => esc_html__( 'Border Width', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px' ],
			'selectors'  => [
				"$field_selectors, {{WRAPPER}} .wpforms-form textarea" => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'field_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				"{{WRAPPER}}" => '--minimog-form-input-normal-rounded: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; --minimog-form-textarea-rounded: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'field_typography',
			'label'    => esc_html__( 'Field Typography', 'minimog' ),
			'selector' => "$field_selectors, {{WRAPPER}} .wpforms-form textarea",
		] );

		$element->start_controls_tabs( 'field_colors_tabs' );

		$element->start_controls_tab( 'field_colors_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$element->add_control( 'field_text_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"$field_selectors, {{WRAPPER}} .wpforms-form textarea" => 'color: {{VALUE}};',
			],
		] );

		$element->add_control( 'field_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"$field_selectors, {{WRAPPER}} .wpforms-form textarea" => 'border-color: {{VALUE}};',
			],
		] );

		$element->add_control( 'field_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"$field_selectors, {{WRAPPER}} .wpforms-form textarea" => 'background-color: {{VALUE}};',
			],
		] );

		$element->end_controls_tab();

		$element->start_controls_tab( 'field_colors_focus_tab', [
			'label' => esc_html__( 'Focus', 'minimog' ),
		] );

		$element->add_control( 'field_text_focus_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"$field_focus_selectors, {{WRAPPER}} .wpforms-form textarea:focus" => 'color: {{VALUE}};',
			],
		] );

		$element->add_control( 'field_border_focus_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"$field_focus_selectors, {{WRAPPER}} .wpforms-form textarea:focus" => 'border-color: {{VALUE}};',
			],
		] );

		$element->add_control( 'field_background_focus_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				"$field_focus_selectors, {{WRAPPER}} .wpforms-form textarea:focus" => 'background-color: {{VALUE}};',
			],
		] );

		$element->end_controls_tab();

		$element->end_controls_tabs();

		// Textarea
		$element->add_control( 'textarea_heading', [
			'label'     => __( 'Textarea', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$element->add_responsive_control( 'textarea_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .wpforms-form textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .wpforms-form textarea'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'textarea_radius', [
			'label'      => esc_html__( 'Border Radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .wpforms-form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'textarea_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 1000,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .wpforms-form textarea' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		// Last Field.
		$last_field_selectors = '
			{{WRAPPER}} .wpforms-form .wpforms-field:last-child .form-input,
			{{WRAPPER}} .wpforms-form .wpforms-field:last-child input[type="text"],
			{{WRAPPER}} .wpforms-form .wpforms-field:last-child input[type="email"],
			{{WRAPPER}} .wpforms-form .wpforms-field:last-child input[type="number"],
			{{WRAPPER}} .wpforms-form .wpforms-field:last-child textarea,
			{{WRAPPER}} .wpforms-form .wpforms-field:last-child select
		';

		$element->add_control( 'last_field_heading', [
			'label'     => esc_html__( 'The Last Field', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$element->add_responsive_control( 'last_field_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				$last_field_selectors => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		// Placeholder.
		$element->add_control( 'placeholder_heading', [
			'label'     => __( 'Placeholder', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$element->add_control( 'field_placeholder_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-form input::placeholder'    => 'color: {{VALUE}};',
				'{{WRAPPER}} .wpforms-form textarea::placeholder' => 'color: {{VALUE}};',
			],
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'field_placeholder_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .wpforms-form input::placeholder, {{WRAPPER}} .wpforms-form textarea::placeholder',
		] );

		// Label.
		$element->add_control( 'label_heading', [
			'label'     => __( 'Label', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$element->add_control( 'field_label_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-field-label' => 'color: {{VALUE}};',
			],
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'field_label_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .wpforms-field-label',
		] );

		$element->add_responsive_control( 'field_label_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .wpforms-field-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		// Newsletter icon.
		$element->add_control( 'newsletter_icon_hr', [
			'label'     => __( 'Newsletter Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'form_style' => [ '01', '02' ],
			],
		] );

		$element->add_responsive_control( 'newsletter_icon_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .wpforms-container.minimog-wpforms .newsletter-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .wpforms-container.minimog-wpforms .newsletter-icon'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'form_style' => [ '01', '02' ],
			],
		] );

		$element->add_control( 'newsletter_icon_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-container.minimog-wpforms .newsletter-icon' => 'color: {{VALUE}};',
			],
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'newsletter_icon_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .wpforms-container.minimog-wpforms .newsletter-icon',
		] );

		$element->end_controls_section();
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function section_form_checkbox_style( $element ) {
		$element->start_controls_section( 'section_form_checkbox_style', [
			'tab'   => Controls_Manager::TAB_STYLE,
			'label' => esc_html__( 'Checkbox', 'minimog' ),
		] );

		$element->add_responsive_control( 'checkbox_align', [
			'label'     => esc_html__( 'Alignment', 'minimog' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_text_align(),
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .wpforms-field-checkbox' => 'text-align: {{VALUE}};',
			],
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'checkbox_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .wpforms-field-checkbox',
		] );

		$element->add_control( 'checkbox_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-field-checkbox'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .wpforms-field-checkbox a' => 'color: {{VALUE}};',
			],
		] );

		$element->end_controls_section();
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function section_form_button_style( $element ) {
		$element->start_controls_section( 'section_form_button_style', [
			'tab'   => Controls_Manager::TAB_STYLE,
			'label' => esc_html__( 'Button', 'minimog' ),
		] );

		$element->add_responsive_control( 'button_icon_size', [
			'label'     => esc_html__( 'Icon Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .wpforms-container.minimog-wpforms .wpforms-submit:before' => 'font-size: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'is_newsletter_form' => 'yes',
				'form_style'         => [ '01', '02', '04', '06', '08' ],
			],
		] );

		$element->add_control( 'button_icon_style_hr', [
			'type'      => Controls_Manager::DIVIDER,
			'condition' => [
				'is_newsletter_form' => 'yes',
				'form_style'         => [ '01', '02', '04', '06', '08' ],
			],
		] );

		$element->add_responsive_control( 'button_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .wpforms-submit-container' => 'justify-content: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'condition'            => [
				'form_style' => [ '00' ],
			],
		] );

		$element->add_responsive_control( 'button_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .wpforms-submit-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .wpforms-submit-container'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'button_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .wpforms-submit-container button, body:not(.rtl) {{WRAPPER}} .wpforms-submit-container input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .wpforms-submit-container button, body.rtl {{WRAPPER}} .wpforms-submit-container input'             => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'button_border_width', [
			'label'      => esc_html__( 'Border Width', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .wpforms-submit-container button, {{WRAPPER}} .wpforms-submit-container input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'button_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .wpforms-submit-container button, {{WRAPPER}} .wpforms-submit-container input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'button_height', [
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
				'{{WRAPPER}} .wpforms-submit-container button' => 'height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .wpforms-submit-container input'  => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$element->add_responsive_control( 'button_width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'max'  => 1600,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .wpforms-submit-container button' => 'min-width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .wpforms-submit-container input'  => 'min-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'button_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .wpforms-submit-container button, {{WRAPPER}} .wpforms-submit-container input',
		] );

		$element->start_controls_tabs( 'button_colors_tabs' );

		$element->start_controls_tab( 'button_colors_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$element->add_control( 'button_text_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-submit-container button, {{WRAPPER}} .wpforms-submit-container input' => '--btn-color: {{VALUE}}; color: {{VALUE}};',
			],
		] );

		$element->add_control( 'button_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-submit-container button, {{WRAPPER}} .wpforms-submit-container input' => '--btn-border-color: {{VALUE}}; border-color: {{VALUE}};',
			],
		] );

		$element->add_control( 'button_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-submit-container button, {{WRAPPER}} .wpforms-submit-container input' => '--btn-background-color: {{VALUE}}; background-color: {{VALUE}};',
			],
		] );

		$element->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'button_box_shadow',
			'selector' => '{{WRAPPER}} .wpforms-submit-container button, {{WRAPPER}} .wpforms-submit-container input',
		] );

		$element->end_controls_tab();

		$element->start_controls_tab( 'button_colors_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$element->add_control( 'button_text_hover_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-submit-container button:hover, {{WRAPPER}} .wpforms-submit-container input:hover' => '--btn-color: {{VALUE}}; color: {{VALUE}};',
			],
		] );

		$element->add_control( 'button_border_hover_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-submit-container button:hover, {{WRAPPER}} .wpforms-submit-container input:hover' => '--btn-border-color: {{VALUE}}; border-color: {{VALUE}};',
			],
		] );

		$element->add_control( 'button_background_hover_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-submit-container button:hover, {{WRAPPER}} .wpforms-submit-container input:hover' => '--btn-background-color: {{VALUE}}; background-color: {{VALUE}};',
			],
		] );

		$element->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'button_hover_box_shadow',
			'selector' => '{{WRAPPER}} .wpforms-submit-container button:hover, {{WRAPPER}} .wpforms-submit-container input:hover',
		] );

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function section_form_summary_style( $element ) {
		$element->start_controls_section( 'section_form_summary_style', [
			'tab'        => Controls_Manager::TAB_STYLE,
			'label'      => esc_html__( 'Summary', 'minimog' ),
			'conditions' => [
				'relation' => 'or',
				'terms'    => [
					[
						'name'     => 'display_form_name',
						'operator' => '==',
						'value'    => 'yes',
					],
					[
						'name'     => 'display_form_description',
						'operator' => '==',
						'value'    => 'yes',
					],
				],
			],
		] );

		$element->add_responsive_control( 'summary_text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors'            => [
				'{{WRAPPER}} .wpforms-head-container' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$element->add_control( 'summary_title_heading', [
			'label'     => __( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'display_form_name' => 'yes',
			],
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'title_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .wpforms-title',
			'condition' => [
				'display_form_name' => 'yes',
			],
		] );

		$element->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-title' => 'color: {{VALUE}};',
			],
			'condition' => [
				'display_form_name' => 'yes',
			],
		] );

		$element->add_responsive_control( 'form_title_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .wpforms-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$element->add_control( 'summary_description_heading', [
			'label'     => __( 'Description', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'display_form_description' => 'yes',
			],
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'description_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .wpforms-description',
			'condition' => [
				'display_form_description' => 'yes',
			],
		] );

		$element->add_control( 'description_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .wpforms-description' => 'color: {{VALUE}};',
			],
			'condition' => [
				'display_form_description' => 'yes',
			],
		] );

		$element->add_responsive_control( 'form_description_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .wpforms-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'display_form_description' => 'yes',
			],
		] );

		$element->end_controls_section();
	}

	public function render_newsletter_icon() {
		?>
		<div class="newsletter-icon"><i class="far fa-envelope"></i></div>
		<?php
	}
}

Modify_WPForms::instance()->initialize();
