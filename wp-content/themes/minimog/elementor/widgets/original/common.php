<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Modify_Widget_Common extends Modify_Base {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'elementor/element/common/_section_style/before_section_end', [ $this, 'add_order_control' ] );
	}

	/**
	 * Adding extra settings in Advanced tab for all widgets
	 *
	 * @see \Elementor\Widget_Common::register_controls()
	 *
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function add_order_control( $element ) {
		$element->start_injection( [
			'type' => 'control',
			'at'   => 'after',
			'of'   => '_z_index',
		] );

		// Some widgets have overlay content on hover. Then this settings made it display properly.
		$element->add_responsive_control( '_hover_z_index', [
			'label'     => esc_html__( 'Hover Z-Index', 'minimog' ),
			'type'      => Controls_Manager::NUMBER,
			'selectors' => [
				'{{WRAPPER}}:hover' => 'z-index: {{VALUE}};',
			],
		] );

		$element->end_injection();

		// Add order setting to control widgets's order in flex layout.
		$element->add_responsive_control( '_order', [
			'label'     => esc_html__( 'Order', 'minimog' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 1,
			'max'       => 99,
			'step'      => 1,
			'selectors' => [
				'{{WRAPPER}}' => 'order: {{VALUE}};',
			],
		] );
	}
}

Modify_Widget_Common::instance()->initialize();
