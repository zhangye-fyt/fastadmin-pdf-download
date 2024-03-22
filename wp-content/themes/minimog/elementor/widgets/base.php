<?php

namespace Minimog_Elementor;

use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

defined( 'ABSPATH' ) || exit;

abstract class Base extends Widget_Base {

	protected function get_icon_part() {
		return 'eicon-elementor-square';
	}

	public function get_icon() {
		return 'minimog-badge ' . $this->get_icon_part();
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the button widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since  2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'minimog' ];
	}

	protected function print_attributes_string( $attr ) {
		echo '' . $this->get_render_attribute_string( $attr );
	}

	/**
	 * Get Render Icon
	 *
	 * Used to render Icon for \Elementor\Controls_Manager::ICONS
	 *
	 * @param array  $icon       Icon Type, Icon value
	 * @param array  $attributes Icon HTML Attributes
	 * @param string $tag        Icon HTML tag, defaults to <i>
	 *
	 * @return mixed|string
	 */
	protected function get_icons_html( $icon, $attributes = [], $tag = 'i' ) {
		ob_start();

		Icons_Manager::render_icon( $icon, $attributes, $tag );

		$template = ob_get_clean();

		return $template;
	}

	protected function render_icon( $settings, $icon, $attributes = [], $svg = false, $color_prefix = 'icon' ) {
		$template = $this->get_render_icon( $settings, $icon, $attributes, $svg, $color_prefix );

		echo '' . $template;
	}

	protected function get_render_icon( $settings, $icon, $attributes = [], $svg = false, $color_prefix = 'icon' ) {
		$tag = 'i';

		ob_start();
		Icons_Manager::render_icon( $icon, $attributes, $tag );
		$template = ob_get_clean();

		if ( $svg === true ) {
			$color_type = isset( $settings["{$color_prefix}_color_type"] ) ? $settings["{$color_prefix}_color_type"] : '';

			if ( 'gradient' === $color_type ) {
				$id = uniqid( 'svg-gradient' );

				$stroke_attr = 'stroke="' . "url(#{$id})" . '"';
				$fill_attr   = 'fill="' . "url(#{$id})" . '"';

				$template = preg_replace( '/stroke="#(.*?)"/', $stroke_attr, $template );
				$template = preg_replace( '/fill="#(.*?)"/', $fill_attr, $template );

				$svg_defs = $this->get_svg_gradient_defs( $settings, $color_prefix, $id );

				if ( ! empty( $svg_defs ) ) {
					$template = $svg_defs . $template;
				}
			}
		}

		return $template;
	}

	protected function get_svg_gradient_defs( array $settings, $name, $id ) {
		if ( 'gradient' !== $settings["{$name}_color_type"] ) {
			return false;
		}

		$color_a_stop = $settings["{$name}_color_a_stop"];
		$color_b_stop = $settings["{$name}_color_b_stop"];

		$color_a_stop_value = $color_a_stop['size'] . $color_a_stop['unit'];
		$color_b_stop_value = $color_b_stop['size'] . $color_a_stop['unit'];

		ob_start();
		?>
		<svg aria-hidden="true" focusable="false" class="svg-defs-gradient">
			<defs>
				<linearGradient id="<?php echo esc_attr( $id ); ?>" x1="0%" y1="0%" x2="0%" y2="100%">
					<stop class="stop-a" offset="<?php echo esc_attr( $color_a_stop_value ); ?>"/>
					<stop class="stop-b" offset="<?php echo esc_attr( $color_b_stop_value ); ?>"/>
				</linearGradient>
			</defs>
		</svg>
		<?php
		return ob_get_clean();
	}

	protected function render_common_button() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['button_text'] ) && empty( $settings['button_icon']['value'] ) ) {
			return;
		}

		$this->add_render_attribute( 'button', 'class', 'tm-button style-' . $settings['button_style'] );

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'tm-button-' . $settings['button_size'] );
		}

		$button_tag = 'a';

		if ( ! empty( $settings['button_link'] ) ) {
			$this->add_link_attributes( 'button', $settings['button_link'] );
		} else {
			$button_tag = 'div';

			if ( ! empty( $settings['link'] ) && ! empty( $settings['link_click'] ) && 'button' === $settings['link_click'] ) {
				$button_tag = 'a';
				$this->add_link_attributes( 'button', $settings['link'] );
			}
		}

		$has_icon = false;

		if ( ! empty( $settings['button_icon']['value'] ) ) {
			$has_icon = true;
			$is_svg   = isset( $settings['button_icon']['library'] ) && 'svg' === $settings['button_icon']['library'] ? true : false;

			$this->add_render_attribute( 'button', 'class', 'icon-' . $settings['button_icon_align'] );

			$this->add_render_attribute( 'button-icon', 'class', 'button-icon minimog-solid-icon' );

			if ( $is_svg ) {
				$this->add_render_attribute( 'button-icon', 'class', [
					'minimog-svg-icon svg-icon',
				] );
			}
		}
		?>
		<div class="tm-button-wrapper">
			<?php printf( '<%1$s %2$s>', $button_tag, $this->get_render_attribute_string( 'button' ) ); ?>

			<div class="button-content-wrapper">
				<?php if ( $has_icon && 'left' === $settings['button_icon_align'] ) : ?>
					<span <?php $this->print_attributes_string( 'button-icon' ); ?>>
							<?php Icons_Manager::render_icon( $settings['button_icon'] ); ?>
						</span>
				<?php endif; ?>

				<?php if ( ! empty( $settings['button_text'] ) ): ?>
					<span class="button-text"><?php echo esc_html( $settings['button_text'] ); ?></span>

					<?php if ( $settings['button_style'] === 'bottom-line-winding' ): ?>
						<span class="line-winding">
							<svg width="42" height="6" viewBox="0 0 42 6" fill="none"
							     xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd"
								      d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
								      fill="#E8C8B3"/>
							</svg>
						</span>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( $has_icon && 'right' === $settings['button_icon_align'] ) : ?>
					<span <?php $this->print_attributes_string( 'button-icon' ); ?>>
							<?php Icons_Manager::render_icon( $settings['button_icon'] ); ?>
						</span>
				<?php endif; ?>
			</div>
			<?php printf( '</%1$s>', $button_tag ); ?>
		</div>
		<?php
	}

	/**
	 * Register common button style controls.
	 */
	protected function register_common_button_style_section() {
		$this->start_controls_section( 'button_style_section', [
			'label' => esc_html__( 'Button', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$icon_condition = [
			'button_icon[value]!' => '',
		];

		$line_condition = [
			'button_style' => [ 'bottom-line', 'bottom-thick-line' ],
		];

		$this->add_responsive_control( 'button_min_width', [
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
				'{{WRAPPER}} .tm-button' => 'min-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_min_height', [
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
		] );

		$this->add_responsive_control( 'button_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl{{WRAPPER}} .tm-button-wrapper'        => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-text'                                        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-flat'                                        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-border'                                      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper'         => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper'   => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .tm-button.style-bottom-line-winding .button-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-text'                                              => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-flat'                                              => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-border'                                            => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper'               => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper'         => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button.style-bottom-line-winding .button-content-wrapper'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'button_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'button_style' => [ 'flat', 'border' ],
			],
		] );

		$this->add_control( 'button_border_width', [
			'label'      => esc_html__( 'Border Width', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'button_style' => [ 'border' ],
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'button_text',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
			'selector' => '{{WRAPPER}} .tm-button',
		] );

		$this->start_controls_tabs( 'button_skin_tabs', [
			'label' => esc_html__( 'Skin', 'minimog' ),
		] );

		$this->start_controls_tab( 'button_skin_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		/**
		 * Button wrapper style.
		 * Background working only with style: flat, border.
		 */

		$this->add_control( 'button_wrapper_color_normal_heading', [
			'label'   => esc_html__( 'Wrapper', 'minimog' ),
			'type'    => Controls_Manager::HEADING,
			'classes' => 'control-heading-in-tabs',
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'           => 'button_background',
			'types'          => [ 'classic', 'gradient' ],
			'selector'       => '{{WRAPPER}} .tm-button',
			'fields_options' => [
				'color' => [
					'selectors' => [
						'{{SELECTOR}}' => '--minimog-tm-button-hover-background: {{VALUE}}; background-color: {{VALUE}};',
					],
				],
			],
			'condition'      => [
				'button_style' => [ 'flat', 'border' ],
			],
		] );

		$this->add_control( 'button_border_color', [
			'label'     => esc_html__( 'Border', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'button_style!' => [ 'flat', 'bottom-line', 'bottom-thick-line' ],
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'button_box_shadow',
			'selector' => '{{WRAPPER}} .tm-button',
		] );

		/**
		 * Text Color
		 */
		$this->add_control( 'button_text_color_normal_heading', [
			'label'     => esc_html__( 'Text', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'button_text',
			'selector' => '{{WRAPPER}} .tm-button',
		] );

		/**
		 * Icon Color
		 */
		$this->add_control( 'button_icon_color_normal_heading', [
			'label'     => esc_html__( 'Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
			'condition' => $icon_condition,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => 'button_icon',
			'selector'  => '{{WRAPPER}} .tm-button .button-icon',
			'condition' => $icon_condition,
		] );

		/**
		 * Line Color
		 */
		$this->add_control( 'button_line_color_normal_heading', [
			'label'     => esc_html__( 'Line', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
			'condition' => $line_condition,
		] );

		$this->add_control( 'button_line_color', [
			'label'     => esc_html__( 'Line Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper:before'       => 'background: {{VALUE}};',
				'{{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper:before' => 'background: {{VALUE}};',
			],
			'condition' => $line_condition,
		] );

		$this->add_control( 'button_line_winding_color', [
			'label'     => esc_html__( 'Line Winding', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line-winding .button-content-wrapper .line-winding svg path' => 'fill: {{VALUE}};',
			],
			'condition' => [
				'button_style' => [ 'bottom-line-winding' ],
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_skin_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		/**
		 * Button wrapper style.
		 * Background working only with style: flat, border.
		 */

		$this->add_control( 'button_wrapper_color_hover_heading', [
			'label'   => esc_html__( 'Wrapper', 'minimog' ),
			'type'    => Controls_Manager::HEADING,
			'classes' => 'control-heading-in-tabs',
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'           => 'hover_button_background',
			'types'          => [ 'classic', 'gradient' ],
			'selector'       => '{{WRAPPER}} a.tm-button:hover, {{WRAPPER}} .minimog-box:hover div.tm-button',
			'fields_options' => [
				'color' => [
					'selectors' => [
						'{{SELECTOR}}' => '--minimog-tm-button-hover-background: {{VALUE}}; background-color: {{VALUE}};',
					],
				],
			],
			'condition'      => [
				'button_style' => [ 'flat', 'border' ],
			],
		] );

		$this->add_control( 'hover_button_border_color', [
			'label'     => esc_html__( 'Border', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} a.tm-button:hover, {{WRAPPER}} .minimog-box:hover div.tm-button' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'button_style!' => [ 'flat', 'bottom-line', 'bottom-thick-line' ],
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'hover_button_box_shadow',
			'selector' => '{{WRAPPER}} a.tm-button:hover, {{WRAPPER}} .minimog-box:hover div.tm-button',
		] );

		/**
		 * Text Color
		 */
		$this->add_control( 'button_text_color_hover_heading', [
			'label'     => esc_html__( 'Text', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_button_text',
			'selector' => '{{WRAPPER}} a.tm-button:hover, {{WRAPPER}} .minimog-box:hover div.tm-button',
		] );

		/**
		 * Icon Color
		 */
		$this->add_control( 'button_icon_color_hover_heading', [
			'label'     => esc_html__( 'Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
			'condition' => $icon_condition,
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => 'hover_button_icon',
			'selector'  => '{{WRAPPER}} .minimog-box:hover div.tm-button .button-icon, {{WRAPPER}} a.tm-button:hover .button-icon',
			'condition' => $icon_condition,
		] );

		/**
		 * Line Color
		 */
		$this->add_control( 'button_line_color_hover_heading', [
			'label'     => esc_html__( 'Line', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'classes'   => 'control-heading-in-tabs',
			'condition' => $line_condition,
		] );

		$this->add_control( 'hover_button_line_color', [
			'label'     => esc_html__( 'Line Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line .button-content-wrapper:after'       => 'background: {{VALUE}};',
				'{{WRAPPER}} .tm-button.style-bottom-thick-line .button-content-wrapper:after' => 'background: {{VALUE}};',
			],
			'condition' => $line_condition,
		] );

		$this->add_control( 'hover_button_line_winding_color', [
			'label'     => esc_html__( 'Line Winding', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-button.style-bottom-line-winding:hover .button-content-wrapper .line-winding svg path' => 'fill: {{VALUE}};',
			],
			'condition' => [
				'button_style' => [ 'bottom-line-winding' ],
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		/**
		 * Button icon style
		 */
		$this->add_control( 'button_icon_style_heading', [
			'label'     => esc_html__( 'Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $icon_condition,
		] );

		$this->add_responsive_control( 'button_icon_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-button .button-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-button .button-icon'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => $icon_condition,
		] );

		$this->add_responsive_control( 'button_icon_font_size', [
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
			'condition' => $icon_condition,
		] );

		$this->end_controls_section();
	}

	/**
	 * @param array  $settings Elementor widget settings
	 * @param string $name     Setting name
	 * @param string $attr_base_name
	 *
	 * @return array
	 */
	protected function parse_responsive_settings( $settings, $name, $attr_base_name ) {
		$breakpoints = [
			'widescreen'   => 'WideScreen',
			'desktop'      => '',
			'laptop'       => 'Laptop',
			'tablet_extra' => 'TabletExtra',
			'tablet'       => 'Tablet',
			'mobile_extra' => 'MobileExtra',
			'mobile'       => 'Mobile',
		];

		$results = [];

		foreach ( $breakpoints as $breakpoint => $suffix ) {
			$setting_name = 'desktop' === $breakpoint ? $name : $name . '_' . $breakpoint;
			$attr_name    = $attr_base_name . $suffix;

			if ( isset( $settings[ $setting_name ] ) && '' !== $settings[ $setting_name ] ) {
				$results[ $attr_name ] = $settings[ $setting_name ];
			}
		}

		return $results;
	}

	protected function has_lazy_loading() {
		$lazy_load_enable = \Minimog::setting( 'image_lazy_load_enable' );

		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() && $lazy_load_enable ) {
			return true;
		}

		return false;
	}

	protected function add_render_lazyload_attributes( $key, $url ) {
		if ( ! empty( $url ) && $this->has_lazy_loading() ) {
			$this->add_render_attribute( $key, [
				'class'    => 'll-background ll-background-unload',
				'data-src' => $url,
			] );
		}
	}

	/**
	 * @param array  $settings   Widget settings
	 * @param string $control_id Control id
	 *
	 * @return string
	 */
	protected function parse_background_image_url( $settings, $control_id ) {
		$background_image_url = '';

		if ( isset( $settings["{$control_id}_background"] ) && 'classic' === $settings["{$control_id}_background"] ) {
			if ( ! empty( $settings["{$control_id}_image"] ) ) {
				$image = $settings["{$control_id}_image"];

				if ( ! empty( $image['url'] ) ) {
					return $image['url'];
				} elseif ( ! empty( $image['id'] ) ) {
					return \Minimog_Image::get_attachment_url_by_id( [
						'id' => $image['id'],
					] );
				}
			}
		}

		return $background_image_url;
	}
}
