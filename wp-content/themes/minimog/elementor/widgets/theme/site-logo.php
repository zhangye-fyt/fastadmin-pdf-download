<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Site_Logo extends Base {

	public function get_name() {
		return 'tm-site-logo';
	}

	public function get_title() {
		return esc_html__( 'Site Logo', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-site-logo';
	}

	public function get_keywords() {
		return [ 'image', 'photo' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_image_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'section_branding', [
			'label' => esc_html__( 'Image', 'minimog' ),
		] );

		$this->add_control( 'logo_version', [
			'label'   => esc_html__( 'Logo Skin', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'dark'  => esc_html__( 'Dark', 'minimog' ),
				'light' => esc_html__( 'Light', 'minimog' ),
			],
			'default' => 'dark',
		] );

		$this->add_responsive_control( 'align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors'            => [
				'{{WRAPPER}} .elementor-widget-container' => 'justify-content: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
		] );

		$this->end_controls_section();
	}

	private function add_image_style_section() {
		$this->start_controls_section( 'section_style_branding', [
			'label' => esc_html__( 'Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'width', [
			'label'          => esc_html__( 'Width', 'minimog' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => '%',
			],
			'tablet_default' => [
				'unit' => '%',
			],
			'mobile_default' => [
				'unit' => '%',
			],
			'size_units'     => [ '%', 'px', 'vw' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
				'vw' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .site-logo' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$skin = ! empty( $settings['logo_version'] ) ? $settings['logo_version'] : 'dark';
		?>
		<div class="site-logo">
			<?php
			\Minimog_Logo::instance()->render( [
				'skin' => $skin,
			] );
			?>
		</div>
		<?php
	}
}
