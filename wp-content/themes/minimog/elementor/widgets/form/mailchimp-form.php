<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Mailchimp_Form extends Form_Base {

	public function get_name() {
		return 'tm-mailchimp-form';
	}

	public function get_title() {
		return esc_html__( 'Mailchimp Form', 'minimog' );
	}

	public function get_keywords() {
		return [ 'mailchimp', 'form', 'subscribe' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_field_style_section();

		$this->add_button_style_section();

		$this->update_controls();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'form_id', [
			'label'       => esc_html__( 'Form Id', 'minimog' ),
			'description' => esc_html__( 'Input the id of form. Leave blank to show default form.', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'01' => '01',
				'02' => '02',
				'03' => '03',
				'04' => '04',
				'05' => '05',
				'06' => '06',
			],
			'default'      => '01',
			'prefix_class' => 'minimog-mailchimp-form-style-',
		] );

		$this->add_responsive_control( 'max_width', [
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
				'{{WRAPPER}} .minimog-mailchimp-form__wrapper' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'alignment', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .minimog-mailchimp-form' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors'            => [
				'{{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="email"], {{WRAPPER}} .form-submit' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->end_controls_section();
	}

	private function update_controls() {
		$this->remove_control( 'button_align' );
		$this->update_control( 'button_height', [
			'condition' => [
				'style' => [ '03', '05', '06' ],
			],
		] );
		$this->update_control( 'button_width', [
			'condition' => [
				'style' => [ '03', '05', '06' ],
			],
		] );

		$this->start_injection(
			[
				'type' => 'control',
				'at'   => 'before',
				'of'   => 'field_padding',
			]
		);

		$this->add_responsive_control( 'min_height', [
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
				'{{WRAPPER}} input[type="text"], {{WRAPPER}} input[type="email"]' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_injection();

		// Inject
		$this->start_injection(
			[
				'type' => 'control',
				'at'   => 'before',
				'of'   => 'button_margin',
			]
		);

		$this->add_control( 'arrow_icon', [
			'label'     => esc_html__( 'Arrow Icon', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'long-arrow'  => esc_html__( 'Long Arrow', 'minimog' ),
				'short-arrow' => esc_html__( 'Short Arrow', 'minimog' ),
			],
			'default'   => 'long-arrow',
			'condition' => [
				'style' => [ '01', '02', '04' ],
			],
		] );

		$this->add_responsive_control( 'button_icon_size', [
			'label'     => esc_html__( 'Icon Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'style' => [ '01', '02', '04' ],
			],
		] );

		$this->add_responsive_control( 'button_icon_spacing', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .button-icon' => 'right: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'style' => [ '01', '02', '04' ],
			],
		] );

		$this->add_control( 'icon_divider', [
			'type'      => Controls_Manager::DIVIDER,
			'condition' => [
				'style' => [ '01', '02', '04' ],
			],
		] );

		$this->end_injection();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$form_id  = ! empty( $settings['form_id'] ) ? $settings['form_id'] : '';


		if ( '' === $form_id && function_exists( 'mc4wp_get_forms' ) ) {
			$mc_forms = mc4wp_get_forms();
			if ( count( $mc_forms ) > 0 ) {
				$form_id = $mc_forms[0]->ID;
			}
		}

		$this->add_render_attribute( 'box', 'class', 'minimog-mailchimp-form' );
		$this->add_render_attribute( 'box', 'class', 'minimog-mailchimp-form--' . $settings['arrow_icon'] );
		?>
		<?php if ( function_exists( 'mc4wp_show_form' ) && $form_id !== '' ) : ?>
			<div <?php $this->print_render_attribute_string( 'box' ) ?>>
				<div class="minimog-mailchimp-form__wrapper"><?php mc4wp_show_form( $form_id ); ?></div>
			</div>
		<?php endif; ?>
		<?php
	}
}
