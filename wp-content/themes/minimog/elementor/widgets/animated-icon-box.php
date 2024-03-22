<?php

namespace Minimog_Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * Moved SVG Animated Vivus as new widget to improvement Performance.
 *
 * Class Widget_Animated_Icon_Box
 *
 * @package Minimog_Elementor
 */
class Widget_Animated_Icon_Box extends Widget_Icon_Box {

	public function get_name() {
		return 'tm-animated-icon-box';
	}

	public function get_title() {
		return esc_html__( 'Animated Icon Box', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return [ 'icon box', 'icon', 'box', 'box icon' ];
	}

	public function get_script_depends() {
		return [ 'minimog-widget-icon-box' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_icon_section();

		$this->add_content_section();

		$this->add_button_section();

		$this->add_icon_svg_animate_section();

		// Style
		$this->add_box_style_section();

		$this->add_icon_style_section();

		$this->add_badge_style_section();

		$this->add_title_style_section();

		$this->add_title_divider_style();

		$this->add_description_style_section();

		$this->register_common_button_style_section();

		$this->update_controls();
	}

	public function update_controls() {
		$this->update_control( 'icon', [
			'default'                => [
				'value'   => '',
				'library' => 'svg',
			],
			'skin'                   => 'inline',
			'exclude_inline_options' => [ 'icon' ],
		] );
	}
}
