<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || exit;

class Widget_Slider_Slideshow extends Carousel_Base {

	public function get_name() {
		return 'tm-slider-slideshow';
	}

	public function get_title() {
		return esc_html__( 'Slider Slideshow', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-post-slider';
	}

	public function get_keywords() {
		return [ 'slideshow', 'slider' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_style_section();

		parent::register_controls();

		$this->update_controls();
	}

	private function update_controls() {
		$this->remove_control( 'swiper_centered' );
		$this->remove_control( 'swiper_centered_highlight' );
		$this->remove_control( 'swiper_free_mode' );

		$this->update_responsive_control( 'swiper_items', [
			'type'           => Controls_Manager::HIDDEN,
			'default'        => '1',
			'tablet_default' => '1',
			'mobile_default' => '1',
		] );

		$this->update_responsive_control( 'swiper_items_per_group', [
			'type'           => Controls_Manager::HIDDEN,
			'default'        => '1',
			'tablet_default' => '1',
			'mobile_default' => '1',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'type'           => Controls_Manager::HIDDEN,
			'default'        => 0,
			'tablet_default' => 0,
			'mobile_default' => 0,
		] );

		$this->update_control( 'swiper_effect', [
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'fade',
		] );

		$this->update_control( 'swiper_autoplay', [
			'default' => 5000,
		] );
	}

	protected function update_slider_settings( $settings, $slider_settings ) {
		// Enable layer transition.

		// if ( 'yes' === $settings['layers_animation'] ) {
		// 	$slider_settings['class'][]               = 'slide-layer-transition';
		// 	$slider_settings['data-layer-transition'] = '1';
		// 	$slider_settings['data-fade-effect']      = 'custom';
		// }

		// Make ken burn transition same speed with autoplay by default.
		$ken_burn_speed             = isset( $settings['swiper_autoplay'] ) ? $settings['swiper_autoplay'] : 5000;
		$slider_settings['style']   = ! empty( $slider_settings['style'] ) && is_array( $slider_settings['style'] ) ? $slider_settings['style'] : [];
		$slider_settings['style'][] = "--ken-burn-speed: {$ken_burn_speed}ms";

		return $slider_settings;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'tm-slider-slideshow',
			'tm-slider-slideshow--style-' . $settings['style'],
		];

		if ( 'yes' === $settings['content_reverse'] ) {
			$classes[] = 'tm-slider-slideshow--content-reverse';
		}

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php $this->print_slider( $settings ); ?>
		</div>
		<?php
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1',
			'options' => [
				'1' => '1',
			],
		] );

		$this->add_responsive_control( 'height', [
			'label'          => esc_html__( 'Height', 'minimog' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [],
			'tablet_default' => [
				'unit' => 'px',
			],
			'mobile_default' => [
				'unit' => 'px',
			],
			'size_units'     => [ 'px', '%', 'vh' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
				'vh' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .slide-bg-wrap' => 'height: {{SIZE}}{{UNIT}};',
			],
			'render_type'    => 'template',
		] );

		$this->add_responsive_control( 'width', [
			'label'     => esc_html__( 'Background Section Width', 'minimog' ) . ' (%)',
			'type'      => Controls_Manager::NUMBER,
			'min'       => 30,
			'max'       => 70,
			'step'      => 0.1,
			'selectors' => [
				'{{WRAPPER}} .slide-bg-wrap' => 'flex:0 0 {{VALUE}}%; max-width: {{VALUE}}%',
			],
			'devices'   => [
				'widescreen',
				'desktop',
				'laptop',
				'tablet_extra',
			],
		] );

		$this->add_control( 'content_reverse', [
			'label'   => esc_html__( 'Reverse', 'minimog' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => '',
		] );

		/**
		 * Don't need this item if set swiper_effect to be fade
		 */
		// $this->add_control( 'layers_animation', [
		// 	'label'   => esc_html__( 'Layers Animation', 'minimog' ),
		// 	'type'    => Controls_Manager::SWITCHER,
		// 	'default' => '',
		// ] );

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'slide_tabs' );

		$repeater->start_controls_tab( 'slide_content_tab', [
			'label' => esc_html__( 'Content', 'minimog' ),
		] );

		$repeater->add_control( 'title_heading', [
			'label' => esc_html__( 'Title', 'minimog' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Enter your title', 'minimog' ),
			'default'     => esc_html__( 'Add Your Heading Text Here', 'minimog' ),
			'description' => esc_html__( 'Wrap any words with &lt;mark&gt;&lt;/mark&gt; tag to make them highlight.', 'minimog' ),
		] );

		$repeater->add_control( 'sub_title_heading', [
			'label'     => esc_html__( 'Sub Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$repeater->add_control( 'sub_title', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Enter your sub title', 'minimog' ),
			'default'     => '',
		] );

		$repeater->add_control( 'description_heading', [
			'label'     => esc_html__( 'Description', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$repeater->add_control( 'description', [
			'label'      => esc_html__( 'Description', 'minimog' ),
			'show_label' => false,
			'type'       => Controls_Manager::TEXTAREA,
		] );

		$repeater->add_control( 'button_heading', [
			'label'     => esc_html__( 'Button', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$repeater->add_control( 'button_text', [
			'label' => esc_html__( 'Text', 'minimog' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$repeater->add_control( 'button_link', [
			'label'         => esc_html__( 'Link', 'minimog' ),
			'type'          => Controls_Manager::URL,
			'dynamic'       => [
				'active' => true,
			],
			'placeholder'   => esc_html__( 'https://your-link.com', 'minimog' ),
			'show_external' => true,
			'default'       => [
				'url'         => '',
				'is_external' => false,
				'nofollow'    => false,
			],
		] );

		$repeater->add_control( 'footer_heading', [
			'label'     => esc_html__( 'Footer', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$repeater->add_control( 'enable_footer', [
			'label'     => esc_html__( 'Enable Footer', 'minimog' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'minimog' ),
			'label_off' => esc_html__( 'Hide', 'minimog' ),
			'default'   => '',
		] );

		$repeater->add_control( 'footer_text', [
			'label'     => esc_html__( 'Text', 'minimog' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [
				'enable_footer' => 'yes',
			],
		] );

		$repeater->add_control( 'footer_separator', [
			'label'     => esc_html__( 'Separator', 'minimog' ),
			'default'   => '|',
			'type'      => Controls_Manager::TEXT,
			'condition' => [
				'enable_footer' => 'yes',
			],
		] );

		$repeater->add_control( 'footer_link', [
			'label'         => esc_html__( 'Link', 'minimog' ),
			'type'          => Controls_Manager::URL,
			'show_label'    => false,
			'dynamic'       => [
				'active' => true,
			],
			'placeholder'   => esc_html__( 'https://your-link.com', 'minimog' ),
			'show_external' => true,
			'default'       => [
				'url'         => '',
				'is_external' => false,
				'nofollow'    => false,
			],
			'condition'     => [
				'enable_footer' => 'yes',
			],
		] );

		$repeater->add_control( 'footer_link_text', [
			'label'     => esc_html__( 'Link Text', 'minimog' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [
				'enable_footer' => 'yes',
			],
		] );

		$repeater->add_control( 'wrapper_heading', [
			'label'     => esc_html__( 'Wrapper', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$repeater->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'wrapper_background',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .slide-content',
		] );

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'slide_background_tab', [
			'label' => esc_html__( 'Background', 'minimog' ),
		] );

		$repeater->add_control( 'background_animation', [
			'label'       => esc_html__( 'Background Animation', 'minimog' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT,
			'default'     => '',
			'options'     => [
				''          => esc_html__( 'None', 'minimog' ),
				'ken-burns' => esc_html__( 'Ken Burns', 'minimog' ),
			],
		] );

		$repeater->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'background',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} {{CURRENT_ITEM}} .slide-bg',
			'separator' => 'before',
		] );

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control( 'slides', [
			'label'       => esc_html__( 'Slides', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title'       => 'Minimog Studio #1',
					'description' => 'So. Morning. Seas shall he darkness moving without. Kind, living, great were whose from behold you’ll sea.',
				],
				[
					'title'       => 'Minimog Studio #2',
					'description' => 'So. Morning. Seas shall he darkness moving without. Kind, living, great were whose from behold you’ll sea.',
				],
			],
			'title_field' => '{{{ title }}}',
			'separator'   => 'before',
		] );

		$this->add_control( 'button_style', [
			'label'     => esc_html__( 'Button Style', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'flat',
			'options' => \Minimog_Helper::get_button_style_options(),
			'separator' => 'before',
		] );

		$this->end_controls_section();
	}

	private function add_style_section() {
		$this->start_controls_section( 'style_section', [
			'label' => esc_html__( 'Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'slide_wrapper_heading', [
			'label' => esc_html__( 'Wrapper', 'minimog' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( 'content_horizontal_align', [
			'label'                => esc_html__( 'Horizontal Align', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .slide-content' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'content_vertical_alignment', [
			'label'                => esc_html__( 'Vertical Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_vertical_alignment(),
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .slide-content' => 'align-items: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .slide-content' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'slide_wrapper_max_width', [
			'label'      => esc_html__( 'Max Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .slide-layers' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'slide_wrapper_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .slide-layers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .slide-layers'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		// Title Style
		$this->add_control( 'title_style_heading', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .title-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .title-wrap'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .title',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_mark_typography',
			'label'    => esc_html__( 'Highlight Words Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .title mark',
		] );

		$this->add_control( 'title_mark_color', [
			'label'     => esc_html__( 'Highlight Words Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .title mark' => 'color: {{VALUE}};',
			],
		] );

		// Sub Title Style
		$this->add_control( 'sub_title_style_heading', [
			'label'     => esc_html__( 'Sub Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'sub_title_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .sub-title-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .sub-title-wrap'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );


		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'sub_title_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .sub-title',
		] );

		$this->add_control( 'sub_title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sub-title' => 'color: {{VALUE}};',
			],
		] );

		// Description Style
		$this->add_control( 'description_style_heading', [
			'label'     => esc_html__( 'Description', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'description_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .description-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .description-wrap'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .description',
		] );

		$this->add_control( 'description_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .description' => 'color: {{VALUE}};',
			],
		] );

		// Button Style
		$this->add_control( 'button_style_heading', [
			'label'     => esc_html__( 'Button', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'button_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .button-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .button-wrap'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .slide-layer-wrap .tm-button.style-text'                                      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .slide-layer-wrap .tm-button.style-flat'                                      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .slide-layer-wrap .tm-button.style-border'                                    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .slide-layer-wrap .tm-button.style-bottom-line .button-content-wrapper'       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .slide-layer-wrap .tm-button.style-bottom-thick-line .button-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .slide-layer-wrap .tm-button.style-text'                                            => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .slide-layer-wrap .tm-button.style-flat'                                            => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .slide-layer-wrap .tm-button.style-border'                                          => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .slide-layer-wrap .tm-button.style-bottom-line .button-content-wrapper'             => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .slide-layer-wrap .tm-button.style-bottom-thick-line .button-content-wrapper'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .slide-layer-wrap .tm-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'button_border_width', [
			'label'      => esc_html__( 'Border Width', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .slide-layer-wrap .tm-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_width', [
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
				'{{WRAPPER}} .slide-layer-wrap .tm-button' => 'min-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'button_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max' => 100,
					'min' => 0,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .slide-layer-wrap .tm-button.style-flat'   => 'min-height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .slide-layer-wrap .tm-button.style-border' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'button_typography',
			'selector' => '{{WRAPPER}} .tm-button',
		] );

		$this->start_controls_tabs( 'button_style_tab' );

		$this->start_controls_tab( 'button_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'button_text_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-layer-wrap  .tm-button' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'           => 'button_background',
			'types'          => [ 'classic', 'gradient' ],
			'selector'       => '{{WRAPPER}} .slide-layer-wrap .tm-button',
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
				'{{WRAPPER}} .slide-layer-wrap .tm-button' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_line_color', [
			'label'     => esc_html__( 'Line Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-layer-wrap .tm-button.style-bottom-line .button-content-wrapper:before'       => 'background: {{VALUE}};',
				'{{WRAPPER}} .slide-layer-wrap .tm-button.style-bottom-thick-line .button-content-wrapper:before' => 'background: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_line_winding_color', [
			'label'     => esc_html__( 'Line Winding', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-layer-wrap .tm-button.style-bottom-line-winding .button-content-wrapper .line-winding svg path' => 'fill: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'button_hover_text_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-layer-wrap a.tm-button:hover'                => 'color: {{VALUE}};',
				'{{WRAPPER}} .slide-layer-wrap .minimog-box:hover div.tm-button' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'           => 'button_hover_background',
			'types'          => [ 'classic', 'gradient' ],
			'selector'       => '{{WRAPPER}} .slide-layer-wrap a.tm-button:hover, {{WRAPPER}} .slide-layer-wrap .minimog-box:hover div.tm-button',
			'fields_options' => [
				'color' => [
					'selectors' => [
						'{{SELECTOR}}' => '--minimog-tm-button-hover-background: {{VALUE}}; background-color: {{VALUE}};',
					],
				],
			],
		] );

		$this->add_control( 'button_hover_border_color', [
			'label'     => esc_html__( 'Border', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-layer-wrap a.tm-button:hover'                => 'border-color: {{VALUE}};',
				'{{WRAPPER}} .slide-layer-wrap .minimog-box:hover div.tm-button' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_hover_line_color', [
			'label'     => esc_html__( 'Line Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-layer-wrap .tm-button.style-bottom-line .button-content-wrapper:after'       => 'background: {{VALUE}};',
				'{{WRAPPER}} .slide-layer-wrap .tm-button.style-bottom-thick-line .button-content-wrapper:after' => 'background: {{VALUE}};',
			],
		] );

		$this->add_control( 'hover_button_line_winding_color', [
			'label'     => esc_html__( 'Line Winding', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-layer-wrap .tm-button.style-bottom-line-winding:hover .button-content-wrapper .line-winding svg path' => 'fill: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Footer
		$this->add_control( 'footer_style_heading', [
			'label'     => esc_html__( 'Footer', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'footer_width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .slide-footer__container' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'footer_horizontal_align', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .slide-footer__container' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'footer_offset_x', [
			'label'          => esc_html__( 'Offset X', 'minimog' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [],
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
					'max' => 200,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .slide-footer' => 'bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'label'    => esc_html__( 'Text Typography', 'minimog' ),
			'name'     => 'footer_typography',
			'selector' => '{{WRAPPER}} .slide-footer',
		] );

		$this->add_control( 'footer_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-footer' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'label'    => esc_html__( 'Link Typography', 'minimog' ),
			'name'     => 'footer_link_typography',
			'selector' => '{{WRAPPER}} .slide-footer__link',
		] );

		$this->start_controls_tabs( 'footer_link_style_tabs' );

		$this->start_controls_tab( 'footer_link_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'footer_link_color', [
			'label'     => esc_html__( 'Link Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-footer__link' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'footer_link_line_color', [
			'label'     => esc_html__( 'Line Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-footer__link .button-content-wrapper:before' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'footer_link_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'footer_link_hover_color', [
			'label'     => esc_html__( 'Link Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-footer__link:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'footer_link_line_hover_color', [
			'label'     => esc_html__( 'Line Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slide-footer__link .button-content-wrapper:after' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function print_slides( array $settings ) {
		foreach ( $settings['slides'] as $slide ) :
			$slide_id = $slide['_id'];
			$item_key = 'item_' . $slide_id;
			$item_button_key = 'button_' . $slide_id;

			$this->add_render_attribute( $item_key, 'class', [
				'swiper-slide',
				'elementor-repeater-item-' . $slide_id,
				'tm-slider-slideshow-slide-bg-animation-' . $slide['background_animation'],
			] );

			$this->add_render_attribute( $item_button_key, 'class', [
				'tm-button',
				'style-' . $settings['button_style'],
			] );

			if ( ! empty( $slide['button_link']['url'] ) ) {
				$this->add_link_attributes( $item_button_key, $slide['button_link'] );
			}

			?>
			<div <?php $this->print_attributes_string( $item_key ); ?>>
				<div class="slide-wrapper">
					<div class="slide-bg-wrap">
						<div class="slide-bg"></div>
					</div>
					<div class="slide-content">
						<div class="slide-layers">
							<?php if ( '' !== $slide['sub_title'] ) : ?>

								<div class="slide-layer-wrap sub-title-wrap">
									<div class="slide-layer">
										<h4 class="sub-title"><?php echo wp_kses( $slide['sub_title'], 'minimog-default' ); ?></h4>
									</div>
								</div>

							<?php endif; ?>

							<?php if ( '' !== $slide['title'] ) : ?>

								<div class="slide-layer-wrap title-wrap">
									<div class="slide-layer">
										<h3 class="title"><?php echo wp_kses( $slide['title'], 'minimog-default' ); ?></h3>
									</div>
								</div>

							<?php endif; ?>

							<?php if ( ! empty( $slide['description'] ) ) : ?>

								<div class="slide-layer-wrap description-wrap">
									<div class="slide-layer">
										<div class="description"><?php echo esc_html( $slide['description'] ); ?></div>
									</div>
								</div>

							<?php endif; ?>

							<?php if ( ! empty( $slide['button_text'] ) && ! empty( $slide['button_link']['url'] ) ) : ?>
								<div class="slide-layer-wrap button-wrap">
									<div class="slide-layer">
										<div class="tm-button-wrapper">
											<a <?php $this->print_attributes_string( $item_button_key ); ?>>

												<div class="button-content-wrapper">
													<span class="button-text">
														<?php echo esc_html( $slide['button_text'] ); ?>

														<?php if ( $settings['button_style'] === 'bottom-line-winding' ): ?>
															<span class="line-winding">
																<svg width="42" height="6" viewBox="0 0 42 6"
																     fill="none"
																     xmlns="http://www.w3.org/2000/svg">
																	<path fill-rule="evenodd" clip-rule="evenodd"
																	      d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
																	      fill="#E8C8B3"/>
																</svg>
															</span>
														<?php endif; ?>
													</span>
												</div>
											</a>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<!--Footer-->
						<?php if ( 'yes' === $slide['enable_footer'] ) : ?>
							<div class="slide-footer">
								<div class="slide-footer__container container-wide">
									<?php if ( $slide['footer_text'] ) : ?>
										<span class="slide-footer__text"><?php echo wp_kses( $slide['footer_text'], 'minimog-default' ) ?></span>

										<?php if ( $slide['footer_separator'] ) : ?>
											<span
												class="slide-footer__separator"><?php echo wp_kses( $slide['footer_separator'], 'minimog-default' ); ?></span>
										<?php endif; ?>
									<?php endif; ?>

									<?php
									$footer_link_key = 'footer_link_' . $slide_id;
									$this->add_render_attribute( $footer_link_key, 'class', [
										'slide-footer__link',
										'tm-button',
										'style-bottom-thick-line',
									] );

									$footer_link_tag = 'span';

									if ( ! empty( $slide['footer_link']['url'] ) ) {
										$footer_link_tag = 'a';
										$this->add_link_attributes( $footer_link_key, $slide['footer_link'] );
									}

									?>

									<?php if ( ! empty( $slide['footer_link_text'] ) ) : ?>
										<?php printf( '<%1$s %2$s>', $footer_link_tag, $this->get_render_attribute_string( $footer_link_key ) ); ?>
										<div class="button-content-wrapper">
											<span
												class="button-text"><?php echo esc_html( $slide['footer_link_text'] ); ?></span>
										</div>
										<?php printf( '</%1$s>', $footer_link_tag ); ?>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach;
	}
}
