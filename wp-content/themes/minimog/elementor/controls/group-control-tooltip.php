<?php

namespace Minimog_Elementor;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Elementor tooltip control.
 *
 * A base control for creating tooltip control.
 *
 * @since 1.0.0
 */
class Group_Control_Tooltip extends Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'tooltip';
	}

	protected function init_fields() {
		$fields = [];

		$fields['skin'] = [
			'label'   => esc_html__( 'Tooltip Skin', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''        => esc_html__( 'Black', 'minimog' ),
				'white'   => esc_html__( 'White', 'minimog' ),
				'primary' => esc_html__( 'Primary', 'minimog' ),
			],
			'default' => '',
		];

		$fields['position'] = [
			'label'   => esc_html__( 'Tooltip Position', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'top'          => esc_html__( 'Top', 'minimog' ),
				'right'        => esc_html__( 'Right', 'minimog' ),
				'bottom'       => esc_html__( 'Bottom', 'minimog' ),
				'left'         => esc_html__( 'Left', 'minimog' ),
				'top-left'     => esc_html__( 'Top Left', 'minimog' ),
				'top-right'    => esc_html__( 'Top Right', 'minimog' ),
				'bottom-left'  => esc_html__( 'Bottom Left', 'minimog' ),
				'bottom-right' => esc_html__( 'Bottom Right', 'minimog' ),
			],
			'default' => 'top',
		];

		return $fields;
	}

	protected function get_default_options() {
		return [
			'popover' => [
				'starter_title' => _x( 'Tooltip', 'Tooltip Control', 'minimog' ),
				'starter_name'  => 'enable',
				'starter_value' => 'yes',
				'settings'      => [
					'render_type' => 'template',
				],
			],
		];
	}
}
