<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || exit;

class Widget_Button extends Base {

	public function get_name() {
		return 'tm-button';
	}

	public function get_title() {
		return esc_html__( 'Advanced Button', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-button';
	}

	protected function register_controls() {
		$this->add_button_settings_section();

		$this->add_button_badge_section();

		$this->add_wrapper_section();

		$this->add_button_style_section();

		$this->add_icon_style_section();

		$this->add_badge_style_section();
	}

	private function add_button_settings_section() {
		$this->start_controls_section( 'button_settings_section', [
			'label' => esc_html__( 'Button', 'minimog' ),
		] );

		$this->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'minimog' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_attr__( 'https://your-link.com', 'minimog' ),
			'default'     => [
				'url' => '#',
			],
		] );

		$this->add_control( 'style', [
			'label'     => esc_html__( 'Style', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'flat',
			'options'   => \Minimog_Helper::get_button_style_options(),
			'separator' => 'before',
		] );

		$this->add_control( 'text', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'Click here', 'minimog' ),
			'placeholder' => esc_html__( 'Click here', 'minimog' ),
		] );

		$this->add_control( 'icon', [
			'label'       => esc_html__( 'Icon', 'minimog' ),
			'type'        => Controls_Manager::ICONS,
			'label_block' => true,
		] );

		$this->add_control( 'icon_align', [
			'label'       => esc_html__( 'Position', 'minimog' ),
			'type'        => Controls_Manager::CHOOSE,
			'options'     => [
				'left'  => [
					'title' => esc_html__( 'Left', 'minimog' ),
					'icon'  => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'minimog' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default'     => 'left',
			'toggle'      => false,
			'label_block' => false,
			'render_type' => 'template',
			'condition'   => [
				'icon[value]!' => '',
			],
		] );

		$this->add_control( 'icon_hover', [
			'label'        => esc_html__( 'Icon Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => '',
			'options'      => [
				''                 => esc_html__( 'None', 'minimog' ),
				'fade'             => esc_html__( 'Fade', 'minimog' ),
				'slide-from-left'  => esc_html__( 'Slide From Left', 'minimog' ),
				'slide-from-right' => esc_html__( 'Slide From Right', 'minimog' ),
			],
			'prefix_class' => 'minimog-button-icon-animation--',
			'condition'    => [
				'icon[value]!' => '',
			],
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'minimog' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		$this->add_control( 'button_css_id', [
			'label'       => esc_html__( 'Button ID', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'title'       => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'minimog' ),
			'label_block' => false,
			'description' => wp_kses( __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'minimog' ), 'minimog-default' ),
			'separator'   => 'before',
		] );

		$this->add_control( 'button_css_class', [
			'label'       => esc_html__( 'Button Class', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'title'       => esc_html__( 'Add your custom classes WITHOUT the dot key. e.g: my-class', 'minimog' ),
			'label_block' => false,
		] );

		$this->end_controls_section();
	}

	private function add_button_badge_section() {
		$this->start_controls_section( 'button_badge_section', [
			'label' => esc_html__( 'Badge', 'minimog' ),
		] );

		$this->add_control( 'badge_text', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => '$59',
		] );

		$this->end_controls_section();
	}

	private function add_wrapper_section() {
		$this->start_controls_section( 'button_wrapper_section', [
			'label' => esc_html__( 'Wrapper', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'alignment', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'prefix_class'         => 'elementor%s-align-',
			'default'              => '',
		] );

		$this->add_control( 'size', [
			'label'          => esc_html__( 'Size', 'minimog' ),
			'type'           => Controls_Manager::SELECT,
			'default'        => 'nm',
			'options'        => [
				'xs' => esc_html__( 'Extra Small', 'minimog' ),
				'sm' => esc_html__( 'Small', 'minimog' ),
				'nm' => esc_html__( 'Normal', 'minimog' ),
				'lg' => esc_html__( 'Large', 'minimog' ),
			],
			'style_transfer' => true,
		] );

		$this->add_responsive_control( 'min_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 300,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-button' => 'min-height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ 'flat', 'border', '3d' ],
			],
		] );

		$this->add_responsive_control( 'width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'max'  => 1000,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-button' => 'min-width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => [
				'style' => [ 'flat', 'border', '3d' ],
			],
		] );

		$this->add_responsive_control( 'padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'style!' => [ 'bottom-line', 'bottom-thick-line' ],
			],
		] );

		$this->add_responsive_control( 'button_line_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper'         => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-bottom-line-winding .button-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper'               => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper'         => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-bottom-line-winding .button-content-wrapper'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ 'bottom-line', 'bottom-thick-line', 'bottom-line-winding' ],
			],
		] );

		$this->add_responsive_control( 'rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ 'flat', 'border', '3d' ],
			],
		] );

		$this->add_responsive_control( 'border_width', [
			'label'      => esc_html__( 'Border Width', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'style' => [ 'border' ],
			],
		] );

		$this->end_controls_section();
	}

	private function add_button_style_section() {
		$this->start_controls_section( 'skin_section', [
			'label' => esc_html__( 'Button', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'text',
			'selector' => '{{WRAPPER}} .tm-button',
		] );

		$this->start_controls_tabs( 'button_skin_tabs' );

		$this->start_controls_tab( 'button_skin_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );
		// Color
		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'text',
			'selector' => '{{WRAPPER}} .tm-button',
		] );

		$this->add_control( 'button_line_color', [
			'label'     => esc_html__( 'Line', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper:before'       => 'background: {{VALUE}};',
				'{{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper:before' => 'background: {{VALUE}};',
			],
			'condition' => [
				'style' => [ 'bottom-line', 'bottom-thick-line' ],
			],
		] );

		$this->add_control( 'button_line_winding_color', [
			'label'     => esc_html__( 'Line Winding', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line-winding .button-content-wrapper .line-winding svg path' => 'fill: {{VALUE}};',
			],
			'condition' => [
				'style' => [ 'bottom-line-winding' ],
			],
		] );

		// Background
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'           => 'button_background',
			'types'          => [ 'classic', 'gradient' ],
			'selector'       => '{{WRAPPER}} .tm-button',
			'separator'      => 'before',
			'condition'      => [
				'style' => [ 'flat', 'border', '3d' ],
			],
			'fields_options' => [
				'color' => [
					'selectors' => [
						'{{SELECTOR}}' => '--minimog-tm-button-hover-background: {{VALUE}}; background-color: {{VALUE}};',
					],
				],
			],
		] );

		$this->add_control( 'button_border_color', [
			'label'     => esc_html__( 'Border', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'style' => [ 'border' ],
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'      => 'button_box_shadow',
			'selector'  => '{{WRAPPER}} .tm-button',
			'condition' => [
				'style' => [ 'flat', 'border', '3d' ],
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		// Color
		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_text',
			'selector' => '{{WRAPPER}} .tm-button:hover',
		] );

		$this->add_control( 'hover_button_line_color', [
			'label'     => esc_html__( 'Line', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper:after'       => 'background: {{VALUE}};',
				'{{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper:after' => 'background: {{VALUE}};',
			],
			'condition' => [
				'style' => [ 'bottom-line', 'bottom-thick-line' ],
			],
		] );

		$this->add_control( 'hover_button_line_winding_color', [
			'label'     => esc_html__( 'Line Winding', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line-winding:hover .button-content-wrapper .line-winding svg path' => 'fill: {{VALUE}};',
			],
			'condition' => [
				'style' => [ 'bottom-line-winding' ],
			],
		] );

		// Background
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'           => 'hover_button_background',
			'types'          => [ 'classic', 'gradient' ],
			'selector'       => '{{WRAPPER}} .tm-button:hover',
			'condition'      => [
				'style' => [ 'flat', 'border', '3d' ],
			],
			'fields_options' => [
				'color' => [
					'selectors' => [
						'{{SELECTOR}}' => '--minimog-tm-button-hover-background: {{VALUE}}; background-color: {{VALUE}};',
					],
				],
			],
			'separator'      => 'before',
		] );

		$this->add_control( 'button_hover_border_color', [
			'label'     => esc_html__( 'Border', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button:hover' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'style' => [ 'border' ],
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'      => 'button_hover_box_shadow',
			'selector'  => '{{WRAPPER}} .tm-button:hover',
			'condition' => [
				'style' => [ 'flat', 'border', '3d' ],
			],
		] );

		$this->add_control( 'hover_animation', [
			'label' => esc_html__( 'Hover Animation', 'minimog' ),
			'type'  => Controls_Manager::HOVER_ANIMATION,
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_icon_style_section() {
		$this->start_controls_section( 'icon_section', [
			'label'     => esc_html__( 'Icon', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'icon[value]!' => '',
			],
		] );

		$this->add_responsive_control( 'icon_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-button .button-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button .button-icon'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'icon_font_size', [
			'label'     => esc_html__( 'Font Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 8,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-button .button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_effect', [
			'label'   => esc_html__( 'Effect', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				''         => esc_html__( 'None', 'minimog' ),
				'scale-up' => esc_html__( 'Scale up', 'minimog' ),
			],
		] );

		$this->start_controls_tabs( 'icon_skin_tabs' );

		$this->start_controls_tab( 'icon_skin_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon',
			'selector' => '{{WRAPPER}} .tm-button .button-icon',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon_hover',
			'selector' => '{{WRAPPER}} .tm-button:hover .button-icon',
		] );


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_badge_style_section() {
		$this->start_controls_section( 'badge_style_section', [
			'label'     => esc_html__( 'Badge', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'badge_text!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'badge',
			'selector' => '{{WRAPPER}} .button-badge',
		] );

		$this->add_control( 'badge_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .button-badge' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'badge_background_color', [
			'label'     => esc_html__( 'Background', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .button-badge' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'badge_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-button .button-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button .button-badge'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-button .button-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_pos_x', [
			'label'      => esc_html__( 'Position X', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max' => 200,
					'min' => -200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-button .button-badge' => 'right: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_pos_y', [
			'label'      => esc_html__( 'Position Y', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max' => 100,
					'min' => -100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-button .button-badge' => 'top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tm-button-wrapper' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['link'] );
			$this->add_render_attribute( 'button', 'class', 'tm-button-link' );
		}

		$this->add_render_attribute( 'button', 'class', 'tm-button' );
		$this->add_render_attribute( 'button', 'role', 'button' );

		if ( ! empty( $settings['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		if ( ! empty( $settings['button_css_class'] ) ) {
			$this->add_render_attribute( 'button', 'class', $settings['button_css_class'] );
		}

		if ( ! empty( $settings['style'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'style-' . $settings['style'] );
		}

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'tm-button-' . $settings['size'] );
		}

		if ( ! empty( $settings['icon_align'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'icon-' . $settings['icon_align'] );
		}

		if ( ! empty( $settings['icon_effect'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'icon-effect-' . $settings['icon_effect'] );
		}

		if ( $settings['hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		$this->before_render_button();
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<a <?php $this->print_attributes_string( 'button' ); ?>>

				<?php $this->print_button_content(); ?>

				<?php $this->print_badge(); ?>
			</a>
		</div>
		<?php
		$this->after_render_button();
	}

	public function print_button_content() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'button-content-wrapper',
			],
			'icon'            => [
				'class' => [
					'button-icon',
				],
			],
			'text'            => [
				'class' => 'button-text',
			],
		] );

		$this->add_inline_editing_attributes( 'text', 'none' );
		?>
		<div <?php $this->print_attributes_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['icon'] ) && $settings['icon_align'] === 'left' ) : ?>
				<div <?php $this->print_attributes_string( 'icon' ); ?>>
					<?php $this->print_icon( $settings ); ?>
				</div>
			<?php endif; ?>

			<?php $this->print_button_text(); ?>

			<?php if ( ! empty( $settings['icon'] ) && $settings['icon_align'] === 'right' ) : ?>
				<div <?php $this->print_attributes_string( 'icon' ); ?>>
					<?php $this->print_icon( $settings ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	private function print_icon( array $settings ) {
		$this->add_render_attribute( 'icon_wrap', 'class', [
			'minimog-icon',
			'icon',
		] );

		$is_svg = isset( $settings['icon']['library'] ) && 'svg' === $settings['icon']['library'] ? true : false;

		if ( $is_svg ) {
			$this->add_render_attribute( 'icon_wrap', 'class', [
				'minimog-svg-icon',
			] );
		}

		if ( 'gradient' === $settings['icon_color_type'] ) {
			$this->add_render_attribute( 'icon_wrap', 'class', [
				'minimog-gradient-icon',
			] );
		} else {
			$this->add_render_attribute( 'icon_wrap', 'class', [
				'minimog-solid-icon',
			] );
		}
		?>
		<div <?php $this->print_attributes_string( 'icon_wrap' ); ?>>
			<?php $this->render_icon( $settings, $settings['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?>
		</div>
		<?php
	}

	public function get_button_text() {
		$settings = $this->get_settings_for_display();

		return isset( $settings['text'] ) ? $settings['text'] : '';
	}

	public function print_button_text() {
		$settings = $this->get_settings_for_display();
		$text     = $this->get_button_text();

		if ( empty( $text ) ) {
			return;
		}
		?>
		<div <?php $this->print_attributes_string( 'text' ); ?>>
			<?php $this->print_button_text_before(); ?>

			<?php echo esc_html( $text ); ?>

			<?php if ( $settings['style'] === 'bottom-line-winding' ): ?>
				<span class="line-winding">
					<svg width="42" height="6" viewBox="0 0 42 6" fill="none"
					     xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd"
						      d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
						      fill="#E8C8B3"/>
					</svg>
				</span>
			<?php endif; ?>

			<?php $this->print_button_text_after(); ?>
		</div>
		<?php
	}

	public function print_button_text_before() {
	}

	public function print_button_text_after() {
	}

	public function print_badge() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['badge_text'] ) ) {
			return;
		}
		?>
		<div class="button-badge">
			<div class="badge-text"><?php echo esc_html( $settings['badge_text'] ); ?></div>
		</div>
		<?php
	}

	protected function content_template() {
		$icon_id = uniqid( 'svg-gradient' );
		// @formatter:off
		?>

		<# var svg_id = '<?php echo esc_html( $icon_id ); ?>'; #>

		<#
		view.addRenderAttribute( 'text', 'class', 'button-text' );

		view.addInlineEditingAttributes( 'text', 'none' );

		var buttonUrl = '';
		if( settings.link && settings.link.url ) {
			buttonUrl = settings.link.url;
		}

		view.addRenderAttribute( 'icon', 'class', 'minimog-icon icon' );

		if ( 'svg' === settings.icon.library ) {
			view.addRenderAttribute( 'icon', 'class', 'minimog-svg-icon' );
		}

		if ( 'gradient' === settings.icon_color_type ) {
			view.addRenderAttribute( 'icon', 'class', 'minimog-gradient-icon' );
		} else {
			view.addRenderAttribute( 'icon', 'class', 'minimog-solid-icon' );
		}

		view.addRenderAttribute( 'wrapper', {
			id: settings.button_css_id,
			class: [
				'tm-button',
				'style-' + settings.style,
				'tm-button-' + settings.size,
				'elementor-animation-' + settings.hover_animation,
				'icon-' + settings.icon_align,
				settings.button_css_class
			],
			href: buttonUrl,
			role: 'button'
		} );

		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
		#>
		<div class="tm-button-wrapper">
			<a {{{ view.getRenderAttributeString( 'wrapper' ) }}}>

				<div class="button-content-wrapper">

					<# if ( iconHTML.rendered && settings.icon_align == 'left' ) { #>
					<div class="button-icon">
						<div class="minimog-icon-wrap">
							<div {{{ view.getRenderAttributeString( 'icon' ) }}}>
								<# if ( iconHTML.rendered ) { #>
									<#
									var stop_a = settings.icon_color_a_stop.size + settings.icon_color_a_stop.unit;
									var stop_b = settings.icon_color_b_stop.size + settings.icon_color_b_stop.unit;

									var iconValue = iconHTML.value;
									if ( typeof iconValue === 'string' ) {
										var strokeAttr = 'stroke="' + 'url(#' + svg_id + ')"';
										var fillAttr = 'fill="' + 'url(#' + svg_id + ')"';

										iconValue = iconValue.replace(new RegExp(/stroke="#(.*?)"/, 'g'), strokeAttr);
										iconValue = iconValue.replace(new RegExp(/fill="#(.*?)"/, 'g'), fillAttr);
									}
									#>
									<svg aria-hidden="true" focusable="false" class="svg-defs-gradient">
										<defs>
											<linearGradient id="{{{ svg_id }}}" x1="0%" y1="0%" x2="0%" y2="100%">
												<stop class="stop-a" offset="{{{ stop_a }}}"/>
												<stop class="stop-b" offset="{{{ stop_b }}}"/>
											</linearGradient>
										</defs>
									</svg>

									{{{ iconValue }}}
								<# } #>
							</div>
						</div>
					</div>
					<# } #>

					<# if ( settings.text ) { #>
						<div {{{ view.getRenderAttributeString( 'text' ) }}}>
							{{{ settings.text }}}

							<# if ( settings.style == 'bottom-line-winding' ) { #>
								<span class="line-winding">
									<svg width="42" height="6" viewBox="0 0 42 6" fill="none"
										xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd"
											d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
											fill="#E8C8B3"/>
									</svg>
								</span>
							<# } #>
						</div>
					<# } #>

					<# if ( iconHTML.rendered && settings.icon_align == 'right' ) { #>
					<div class="button-icon">
						<div class="minimog-icon-wrap">
							<div {{{ view.getRenderAttributeString( 'icon' ) }}}>
								<# if ( iconHTML.rendered ) { #>
									<#
									var stop_a = settings.icon_color_a_stop.size + settings.icon_color_a_stop.unit;
									var stop_b = settings.icon_color_b_stop.size + settings.icon_color_b_stop.unit;

									var iconValue = iconHTML.value;
									if ( typeof iconValue === 'string' ) {
										var strokeAttr = 'stroke="' + 'url(#' + svg_id + ')"';
										var fillAttr = 'fill="' + 'url(#' + svg_id + ')"';

										iconValue = iconValue.replace(new RegExp(/stroke="#(.*?)"/, 'g'), strokeAttr);
										iconValue = iconValue.replace(new RegExp(/fill="#(.*?)"/, 'g'), fillAttr);
									}
									#>
									<svg aria-hidden="true" focusable="false" class="svg-defs-gradient">
										<defs>
											<linearGradient id="{{{ svg_id }}}" x1="0%" y1="0%" x2="0%" y2="100%">
												<stop class="stop-a" offset="{{{ stop_a }}}"/>
												<stop class="stop-b" offset="{{{ stop_b }}}"/>
											</linearGradient>
										</defs>
									</svg>

									{{{ iconValue }}}
								<# } #>
							</div>
						</div>
					</div>
					<# } #>

				</div>

				<# if( settings.badge_text ) { #>
					<div class="button-badge">
						<div class="badge-text">{{{ settings.badge_text }}}</div>
					</div>
				<# } #>
			</a>
		</div>
		<?php
		// @formatter:off
	}

	protected function before_render_button(){}

	protected function after_render_button(){}
}
