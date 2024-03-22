<?php

namespace Minimog_Elementor;

defined( 'ABSPATH' ) || exit;

class Widget_Button_Scroll extends Widget_Button {

	public function get_name() {
		return 'tm-button-scroll';
	}

	public function get_title() {
		return esc_html__( 'Button: Scroll', 'minimog' );
	}

	public function get_script_depends() {
		return [ 'jquery-smooth-scroll' ];
	}

	public function register_controls() {
		parent::register_controls();

		$this->update_control( 'link', [
			'description' => esc_html__( 'To make smooth scroll to a section, then input section\'s ID like this: #about-us-section.', 'minimog' ),
		] );
	}

	public function before_render_button() {
		$this->add_render_attribute( 'button', 'class', 'smooth-scroll-link' );
	}
}
