<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

defined( 'ABSPATH' ) || exit;

class Modify_Widget_Column extends Modify_Base {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'elementor/frontend/column/before_render', [ $this, 'lazy_load_background' ] );

		add_action( 'elementor/frontend/column/before_render', [ $this, 'before_render_options' ], 10 );
		add_action( 'elementor/element/column/layout/before_section_end', [ $this, 'add_column_order_control' ] );
		add_action( 'elementor/element/column/section_advanced/after_section_end', [
			$this,
			'update_padding_option_selectors',
		] );
		add_action( 'elementor/element/column/layout/after_section_end', [ $this, 'add_column_collapsible' ] );
		add_action( 'elementor/element/column/section_typo/after_section_end', [
			$this,
			'add_column_collapsible_style',
		] );
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function add_column_collapsible( $element ) {
		$element->start_controls_section( 'collapsible_section', [
			'tab'   => Controls_Manager::TAB_LAYOUT,
			'label' => esc_html__( 'Collapsible', 'minimog' ),
		] );

		$device_options = [];

		$active_devices     = Plugin::$instance->breakpoints->get_active_devices_list();
		$active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

		foreach ( $active_devices as $breakpoint_key ) {
			$label = 'desktop' === $breakpoint_key ? esc_html__( 'Desktop', 'minimog' ) : $active_breakpoints[ $breakpoint_key ]->get_label();

			$device_options[ $breakpoint_key ] = $label;
		}

		$element->add_control( 'tm_collapsible', [
			'label'              => esc_html__( 'Enable', 'minimog' ),
			'type'               => Controls_Manager::SWITCHER,
			'label_off'          => esc_html__( 'Yes', 'minimog' ),
			'label_on'           => esc_html__( 'No', 'minimog' ),
			'default'            => '',
			'frontend_available' => true,
		] );

		$element->add_control( 'tm_collapsible_on', [
			'label'              => esc_html__( 'Collapsible On', 'minimog' ),
			'type'               => Controls_Manager::SELECT2,
			'multiple'           => true,
			'label_block'        => true,
			'default'            => [
				Breakpoints_Manager::BREAKPOINT_KEY_MOBILE,
				Breakpoints_Manager::BREAKPOINT_KEY_MOBILE_EXTRA,
			],
			'options'            => $device_options,
			'frontend_available' => true,
			'condition'          => [
				'tm_collapsible' => 'yes',
			],
		] );

		$element->add_control( 'tm_collapsible_status', [
			'label'              => esc_html__( 'Status', 'minimog' ),
			'type'               => Controls_Manager::SWITCHER,
			'label_off'          => esc_html__( 'Close', 'minimog' ),
			'label_on'           => esc_html__( 'Open', 'minimog' ),
			'return_value'       => 'open',
			'default'            => 'open',
			'frontend_available' => true,
			'condition'          => [
				'tm_collapsible' => 'yes',
			],
		] );

		$element->add_control( 'tm_collapsible_title_hr', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'tm_collapsible' => 'yes',
			],
		] );

		$element->add_control( 'tm_collapsible_title', [
			'label'              => esc_html__( 'Title', 'minimog' ),
			'type'               => Controls_Manager::TEXTAREA,
			'dynamic'            => [
				'active' => true,
			],
			'show_label'         => false,
			'placeholder'        => esc_html__( 'Enter your title', 'minimog' ),
			'default'            => esc_html__( 'Add Your Heading Text Here', 'minimog' ),
			'frontend_available' => true,
			'condition'          => [
				'tm_collapsible' => 'yes',
			],
		] );

		$element->add_control( 'tm_collapsible_title_size', [
			'label'              => esc_html__( 'HTML Tag', 'minimog' ),
			'type'               => Controls_Manager::SELECT,
			'options'            => [
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
			'default'            => 'h4',
			'frontend_available' => true,
			'condition'          => [
				'tm_collapsible' => 'yes',
			],
		] );

		$element->end_controls_section();
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function add_column_collapsible_style( $element ) {
		$element->start_controls_section(
			'collapsible_style_section',
			[
				'tab'   => Controls_Manager::TAB_STYLE,
				'type'  => Controls_Manager::SECTION,
				'label' => esc_html__( 'Collapsible', 'minimog' ),
			]
		);

		$element->add_control( 'heading_style_hr', [
			'label'     => esc_html__( 'Heading', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$element->add_responsive_control( 'heading_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .tm-collapsible__title' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$element->add_responsive_control( 'title_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-collapsible__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-collapsible__title'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$element->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'selector' => '{{WRAPPER}} .tm-collapsible__title',
		] );

		$element->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-collapsible__title' => 'color: {{VALUE}};',
			],
		] );

		$element->end_controls_section();
	}

	/**
	 * Adding column order control to layout section.
	 *
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function add_column_order_control( $element ) {
		$element->add_responsive_control( 'order', [
			'label'     => esc_html__( 'Column Order', 'minimog' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 1,
			'max'       => 12,
			'step'      => 1,
			'selectors' => [
				'{{WRAPPER}}' => 'order: {{VALUE}};',
			],
		] );
	}

	/**
	 * Update padding option selectors.
	 *
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function update_padding_option_selectors( $element ) {
		$element->update_responsive_control( 'padding', [
			'selectors' => [
				// Make stronger selector for compatible with theme.
				'body:not(.rtl) {{WRAPPER}} > .elementor-element-populated.elementor-element-populated' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} > .elementor-element-populated.elementor-element-populated'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );
	}

	public function enqueue_scripts() {
		wp_register_script( 'minimog-column-collapsible', MINIMOG_ELEMENTOR_URI . '/assets/js/column.js', array(
			'jquery',
			'elementor-frontend',
		), null, true );
	}

	/**
	 * @param \Elementor\Element_Base $element
	 */
	public function before_render_options( $element ) {
		$settings = $element->get_settings_for_display();

		wp_enqueue_script( 'minimog-column-collapsible' );

		if ( isset( $settings['tm_collapsible'] ) && 'yes' === $settings['tm_collapsible'] ) {
			$element->add_render_attribute( '_wrapper', 'class', 'elementor-column__tm-collapsible' );
		}
	}

	/**
	 * @param \Elementor\Element_Base $element
	 */
	public function lazy_load_background( $element ) {
		$lazy_load_enable = \Minimog::setting( 'image_lazy_load_enable' );
		if ( Plugin::$instance->editor->is_edit_mode() || ! $lazy_load_enable ) {
			return;
		}

		$settings = $element->get_settings_for_display();
		if ( 'classic' !== $settings['background_background'] || empty( $settings['background_image']['url'] ) ) {
			return;
		}

		/**
		 * @see \Elementor\Element_Column::before_render()
		 */
		$element->add_render_attribute( '_inner_wrapper', [
			'class'    => 'll-background ll-background-unload',
			'data-src' => $settings['background_image']['url'],
		] );
	}
}

Modify_Widget_Column::instance()->initialize();
