<?php

namespace Minimog_Elementor;

defined( 'ABSPATH' ) || exit;

class Modify_Widget_Image extends Modify_Base {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'elementor/element/image/section_image/before_section_end', [
			$this,
			'before_end_section_image',
		] );
	}

	/**
	 * @param \Elementor\Widget_Base $element The edited element.
	 */
	public function before_end_section_image( $element ) {
		$element->update_responsive_control( 'align', [
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );
	}
}

Modify_Widget_Image::instance()->initialize();
