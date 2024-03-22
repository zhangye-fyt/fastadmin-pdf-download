<?php

namespace Minimog_Elementor;

use Elementor\Plugin;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Modify_Widget_Section extends Modify_Base {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'elementor/element/section/section_layout/after_section_end', [
			$this,
			'section_layout',
		] );

		add_action( 'elementor/frontend/section/before_render', [ $this, 'lazy_load_background' ] );
	}

	/**
	 * Update section gap control in layout section.
	 *
	 * @param \Elementor\Controls_Stack $element
	 */
	public function section_layout( $element ) {
		// Update content width strong selector to make it working properly with Boxed template.
		$element->update_control( 'content_width', [
			'selectors' => [
				'{{WRAPPER}} > .elementor-container.elementor-container' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		// Get section args.
		$section_layout = Plugin::instance()->controls_manager->get_control_from_stack( $element->get_unique_name(), 'layout' );
		$layout_options = $section_layout['options'];

		// Add new layout option 'extended'.
		if ( ! isset( $layout_options['extended'] ) ) {
			$layout_options['extended'] = esc_html__( 'Extended', 'minimog' );
		}

		// Add new layout option 'broad'.
		if ( ! isset( $layout_options['broad'] ) ) {
			$layout_options['broad'] = esc_html__( 'Broad', 'minimog' );
		}

		// Add new layout option 'large'.
		if ( ! isset( $layout_options['large'] ) ) {
			$layout_options['large'] = esc_html__( 'Large', 'minimog' );
		}

		// Add new layout option 'wide'.
		if ( ! isset( $layout_options['wide'] ) ) {
			$layout_options['wide'] = esc_html__( 'Wide', 'minimog' );
		}

		// Add new layout option 'wider'.
		if ( ! isset( $layout_options['wider'] ) ) {
			$layout_options['wider'] = esc_html__( 'Wider', 'minimog' );
		}

		// Add new layout option 'full-wide'.
		if ( ! isset( $layout_options['full-wide'] ) ) {
			$layout_options['full-wide'] = esc_html__( 'Full Wide', 'minimog' );
		}

		// Set new options.
		$layout_options['options'] = $layout_options;
		// Apply gap changed.
		$element->update_control( 'layout', $layout_options );

		// Get section args.
		$section_gap = Plugin::instance()->controls_manager->get_control_from_stack( $element->get_unique_name(), 'gap' );
		$gap_options = $section_gap['options'];

		// Change 'Default' => 'Normal' text.
		if ( isset( $gap_options['default'] ) ) {
			$gap_options['default'] = esc_html__( 'Normal', 'minimog' );
		}

		// Add new gap option 'custom'.
		if ( ! isset( $gap_options['custom'] ) ) {
			$gap_options['custom'] = esc_html__( 'Custom', 'minimog' );
		}

		// Set new options.
		$section_gap['options'] = $gap_options;

		// Change default gap setting from 'default' => 'extended'.
		$section_gap['default'] = 'extended';

		// Apply gap changed.
		$element->update_control( 'gap', $section_gap );

		/**
		 * Elementor also added custom gap width in 3.1.0
		 * We need remove it.
		 */
		$element->remove_responsive_control( 'gap_columns_custom' );

		$element->start_injection( [
			'type' => 'control',
			'at'   => 'after',
			'of'   => 'gap',
		] );

		$element->add_control( 'gap_beside', [
			'label'        => esc_html__( 'Gap Beside', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => 'yes',
			'options'      => [
				'no'  => esc_html__( 'No', 'minimog' ),
				'yes' => esc_html__( 'Yes', 'minimog' ),
			],
			'prefix_class' => 'elementor-section-gap-beside-',
			'condition'    => [
				'gap!' => 'no',
			],
		] );

		$element->add_responsive_control( 'gap_width', [
			'label'     => esc_html__( 'Gap Width', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 2,
				],
			],
			'selectors' => [
				'body:not(.rtl) {{WRAPPER}} > .elementor-column-gap-custom > .elementor-row > .elementor-column > .elementor-element-populated' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2); padding-right: calc( {{SIZE}}{{UNIT}} / 2);',
				'body:not(.rtl) {{WRAPPER}}.elementor-section-gap-beside-no > .elementor-column-gap-custom > .elementor-row'                    => 'margin-left: calc( -{{SIZE}}{{UNIT}} / 2); margin-right: calc( -{{SIZE}}{{UNIT}} / 2);',
				'body.rtl {{WRAPPER}} > .elementor-column-gap-custom > .elementor-row > .elementor-column > .elementor-element-populated'       => 'padding-right: calc( {{SIZE}}{{UNIT}} / 2); padding-left: calc( {{SIZE}}{{UNIT}} / 2);',
				'body.rtl {{WRAPPER}}.elementor-section-gap-beside-no > .elementor-column-gap-custom > .elementor-row'                          => 'margin-right: calc( -{{SIZE}}{{UNIT}} / 2); margin-left: calc( -{{SIZE}}{{UNIT}} / 2);',
			],
			'condition' => [
				'gap' => 'custom',
			],
		] );

		$element->end_injection();

		// Add control custom alignment of content.
		$element->start_injection( [
			'type' => 'control',
			'at'   => 'before',
			'of'   => 'content_position',
		] );

		$element->add_control( 'content_alignment', [
			'label'        => esc_html__( 'Content Alignment', 'minimog' ),
			'type'         => Controls_Manager::CHOOSE,
			'options'      => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'      => 'center',
			'toggle'       => false,
			'prefix_class' => 'elementor-section-content-align-',
			'condition'    => [
				'content_width!' => '',
			],
		] );

		$element->add_control( 'column_vertical_alignment', [
			'label'                => esc_html__( 'Column Vertical Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_vertical_alignment_full(),
			'default'              => 'stretch',
			'toggle'               => false,
			'prefix_class'         => 'elementor-section-column-vertical-align-',
			'selectors'            => [
				'{{WRAPPER}} > .elementor-container > .elementor-row' => 'align-items: {{VALUE}}',
			],
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
		] );

		$element->update_control( 'content_position', [
			'condition' => [
				'column_vertical_alignment' => 'stretch',
			],
		] );

		$element->end_injection();
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

		$element->add_render_attribute( '_wrapper', [
			'class'    => 'll-background ll-background-unload',
			'data-src' => $settings['background_image']['url'],
		] );
	}
}

Modify_Widget_Section::instance()->initialize();
