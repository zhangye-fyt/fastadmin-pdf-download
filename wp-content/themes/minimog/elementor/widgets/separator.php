<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color as Scheme_Color;

defined( 'ABSPATH' ) || exit;

class Widget_Separator extends Base {

	public function get_name() {
		return 'tm-separator';
	}

	public function get_title() {
		return esc_html__( 'Separator', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-divider';
	}

	public function get_keywords() {
		return [ 'divider', 'hr', 'line', 'border', 'separator' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'separator_section', [
			'label' => esc_html__( 'Separator', 'minimog' ),
		] );

		$this->add_control( 'type', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'line'        => esc_html__( 'Line', 'minimog' ),
				'double-line' => esc_html__( 'Double Line', 'minimog' ),
			],
			'default' => 'line',
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Layout', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'vertical-line'   => esc_html__( 'Vertical', 'minimog' ),
				'horizontal-line' => esc_html__( 'Horizontal', 'minimog' ),
			],
			'default' => 'vertical-line',
		] );

		$this->add_control( 'play_animate', [
			'label'        => esc_html__( 'Play Animate', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'condition'    => [
				'style' => array( 'vertical-line' ),
			],
		] );

		$this->add_control( 'color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'scheme'    => [
				'type'  => Scheme_Color::get_type(),
				'value' => Scheme_Color::COLOR_1,
			],
			'selectors' => [
				'{{WRAPPER}} .tm-separator .inner' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'align', [
			'label'        => esc_html__( 'Alignment', 'minimog' ),
			'type'         => Controls_Manager::CHOOSE,
			'options'      => Widget_Utils::get_control_options_horizontal_alignment(),
			'prefix_class' => 'elementor%s-align-',
			'default'      => 'center',
		] );

		$this->add_responsive_control( 'width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'px' => [
					'max' => 1000,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-separator .inner' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'px' => [
					'max' => 1000,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-separator .inner' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tm-separator' );
		$this->add_render_attribute( 'wrapper', 'class', $settings['style'] );

		if ( $settings['play_animate'] === '1' ) {
			$this->add_render_attribute( 'wrapper', 'class', 'play-animate' );
		}
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php if ( 'double-line' === $settings['type'] ): ?>
				<div class="inner"></div>
			<?php endif; ?>
			<div class="inner"></div>
		</div>
		<?php
	}

	protected function content_template() {
		// @formatter:off
		?>
		<#
		view.addRenderAttribute( 'wrapper', 'class', 'tm-separator' );

		view.addRenderAttribute( 'wrapper', 'class', settings.style );

		if( settings.play_animate === '1' ) {
			view.addRenderAttribute( 'wrapper', 'class', 'play-animate' );
		}
		#>
		<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
			<# if ( 'double-line' === settings.type ) { #>
				<div class="inner"></div>
			<# } #>
			<div class="inner"></div>
		</div>
		<?php
		// @formatter:off
	}
}
