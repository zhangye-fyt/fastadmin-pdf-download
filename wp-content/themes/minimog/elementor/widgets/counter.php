<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Counter extends Base {

	public function get_name() {
		return 'tm-counter';
	}

	public function get_title() {
		return esc_html__( 'Modern Counter', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-counter';
	}

	public function get_keywords() {
		return [ 'counter' ];
	}

	public function get_script_depends() {
		return [ 'minimog-widget-counter' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_box_style_section();

		$this->add_number_style_section();

		$this->add_title_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'counter_section', [
			'label' => esc_html__( 'Counter', 'minimog' ),
		] );

		$this->add_control( 'reverse_content', [
			'label'     => esc_html__( 'Reverse Content', 'minimog' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => '',
			'label_on'  => esc_html__( 'Yes', 'minimog' ),
			'label_off' => esc_html__( 'No', 'minimog' ),
		] );

		$this->add_control( 'number_heading', [
			'label'     => esc_html__( 'Number', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'starting_number', [
			'label'   => esc_html__( 'Starting Number', 'minimog' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 0,
			'step'    => 0.01,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'ending_number', [
			'label'   => esc_html__( 'Ending Number', 'minimog' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 100,
			'step'    => 0.01,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'prefix', [
			'label'   => esc_html__( 'Number Prefix', 'minimog' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'suffix', [
			'label'   => esc_html__( 'Number Suffix', 'minimog' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'duration', [
			'label'   => esc_html__( 'Animation Duration', 'minimog' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 2000,
			'min'     => 100,
			'step'    => 100,
		] );

		$this->add_control( 'number_format_type', [
			'label'   => esc_html__( 'Format Type', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'full',
			'options' => [
				'full'  => esc_html__( 'Full', 'minimog' ),
				'short' => esc_html__( 'Short', 'minimog' ),
			],
		] );

		$this->add_control( 'thousand_separator', [
			'label'     => esc_html__( 'Thousand Separator', 'minimog' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_on'  => esc_html__( 'Show', 'minimog' ),
			'label_off' => esc_html__( 'Hide', 'minimog' ),
			'condition' => [
				'number_format_type' => 'full',
			],
		] );

		$this->add_control( 'thousand_separator_char', [
			'label'     => esc_html__( 'Separator', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'condition' => [
				'number_format_type' => 'full',
				'thousand_separator' => 'yes',
			],
			'options'   => [
				''  => 'Default',
				'.' => 'Dot',
				' ' => 'Space',
			],
		] );
		$this->add_control( 'digits', [
			'label'       => esc_html__( 'Digits', 'minimog' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => 1,
			'description' => esc_html__( 'The number of digits to appear after the decimal point', 'minimog' ),
			'min'         => 0,
			'max'         => 3,
			'condition'   => [
				'number_format_type' => 'short',
			],
		] );

		$this->add_control( 'decimal_number', [
			'label'       => esc_html__( 'Decimal', 'minimog' ),
			'description' => esc_html__( 'The number of decimal places to show', 'minimog' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => 0,
			'min'         => 0,
			'max'         => 2,
			'condition'   => [
				'number_format_type' => 'full',
			],
		] );

		$this->add_control( 'title_text', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Cool Number', 'minimog' ),
			'separator'   => 'before',
		] );

		$this->end_controls_section();
	}

	private function add_box_style_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align_full(),
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
			'selectors'            => [
				'{{WRAPPER}} .tm-counter'                 => 'text-align: {{VALUE}};',
				'{{WRAPPER}} .tm-counter__number-wrapper' => 'justify-content: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-counter'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_max_width', [
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
				'{{WRAPPER}} .tm-counter' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'              => 'center',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}}',
			],
		] );

		$this->start_controls_tabs( 'box_colors' );

		$this->start_controls_tab( 'box_colors_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .tm-counter',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .tm-counter',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .tm-counter',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'box_colors_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .tm-counter:hover',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_hover_border',
			'selector' => '{{WRAPPER}} .tm-counter:hover',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_hover_shadow',
			'selector' => '{{WRAPPER}} .tm-counter:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_number_style_section() {
		$this->start_controls_section( 'number_style_section', [
			'label' => esc_html__( 'Number', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'number_vertical_alignment', [
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
				'{{WRAPPER}} .tm-counter__number-wrapper' => 'align-items: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'number',
			'selector' => '{{WRAPPER}} .tm-counter__number-wrapper',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
		] );

		$this->start_controls_tabs( 'number_colors' );

		$this->start_controls_tab( 'number_color_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'number',
			'selector' => '{{WRAPPER}} .tm-counter__number-wrapper',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'number_color_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'number_hover',
			'selector' => '{{WRAPPER}} .tm-counter:hover .tm-counter__number-wrapper',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$prefix_condition = [ 'prefix!' => '' ];

		$this->add_control( 'number_prefix_style_hr', [
			'label'     => esc_html__( 'Prefix', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $prefix_condition,
		] );

		$this->add_responsive_control( 'number_prefix_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-counter__number-prefix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-counter__number-prefix'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => $prefix_condition,
		] );

		$this->add_responsive_control( 'number_prefix_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-counter__number-prefix' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-counter__number-prefix'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => $prefix_condition,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'number_prefix',
			'selector'  => '{{WRAPPER}} .tm-counter__number-prefix',
			'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
			'condition' => $prefix_condition,
		] );

		$this->add_control( 'number_prefix_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-counter__number-prefix' => 'color: {{VALUE}};',
			],
			'condition' => $prefix_condition,
		] );

		$this->add_control( 'number_prefix_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-counter__number-prefix' => 'background-color: {{VALUE}};',
			],
			'condition' => $prefix_condition,
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'      => 'number_prefix_border',
			'selector'  => '{{WRAPPER}} .tm-counter__number-prefix',
			'condition' => $prefix_condition,
		] );

		// Suffix
		$suffix_condition = [ 'suffix!' => '' ];

		$this->add_control( 'number_suffix_style_hr', [
			'label'     => esc_html__( 'Suffix', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $suffix_condition,
		] );

		$this->add_responsive_control( 'number_suffix_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-counter__number-suffix' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-counter__number-suffix'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => $suffix_condition,
		] );

		$this->add_responsive_control( 'number_suffix_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-counter__number-suffix' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-counter__number-suffix'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => $suffix_condition,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'number_suffix',
			'selector'  => '{{WRAPPER}} .tm-counter__number-suffix',
			'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
			'condition' => $suffix_condition,
		] );

		$this->add_control( 'number_suffix_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-counter__number-suffix' => 'color: {{VALUE}};',
			],
			'condition' => $suffix_condition,
		] );

		$this->add_control( 'number_suffix_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-counter__number-suffix' => 'background-color: {{VALUE}};',
			],
			'condition' => $suffix_condition,
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'      => 'number_suffix_border',
			'selector'  => '{{WRAPPER}} .tm-counter__number-suffix',
			'condition' => $suffix_condition,
		] );

		$this->end_controls_section();
	}

	private function add_title_style_section() {
		$this->start_controls_section( 'title_style_section', [
			'label' => esc_html__( 'Title', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'title_width', [
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
					'max' => 800,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-counter__heading' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'title_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'              => '',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .tm-counter__heading-wrap' => 'display: flex; justify-content: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'heading_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-counter__heading-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-counter__heading-wrap'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .tm-counter__heading',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
		] );

		$this->start_controls_tabs( 'title_colors' );

		$this->start_controls_tab( 'title_color_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .tm-counter__heading',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_color_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_hover',
			'selector' => '{{WRAPPER}} .tm-counter:hover .tm-counter__heading',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'box', 'class', 'tm-counter' );

		if ( ! isset( $settings['ending_number'] ) ) {
			return;
		}

		$starting_number = isset( $settings['starting_number'] ) ? floatval( $settings['starting_number'] ) : 0;
		$ending_number   = floatval( $settings['ending_number'] );
		$decimal         = isset( $settings['decimal_number'] ) ? intval( $settings['decimal_number'] ) : 0;
		$duration        = isset( $settings['duration'] ) ? intval( $settings['duration'] ) : 2000;
		$separator_char  = '';

		if ( 'yes' === $settings['thousand_separator'] ) {
			$separator_char = ! empty( $settings['thousand_separator_char'] ) ? $settings['thousand_separator_char'] : ',';
		}

		$counter_options = [
			'from'       => $starting_number,
			'to'         => $ending_number,
			'decimal'    => $decimal,
			'duration'   => $duration,
			'separator'  => $separator_char,
			'formatType' => $settings['number_format_type'],
			'digits'     => $settings['digits'],
		];

		$this->add_render_attribute( 'box', 'data-counter', wp_json_encode( $counter_options ) );
		?>
		<div <?php $this->print_render_attribute_string( 'box' ); ?>>
			<?php 
			if ( 'yes' == $settings['reverse_content'] ) {
				$this->print_title();
			}
			?>

			<div class="tm-counter__number-wrapper">
				<span class="tm-counter__number-prefix"><?php echo esc_html( $settings['prefix'] ); ?></span>
				<span class="tm-counter__number"><?php echo esc_html( $starting_number ); ?></span>
				<span class="tm-counter__number-suffix"><?php echo esc_html( $settings['suffix'] ); ?></span>
			</div>

			<?php 
			if ( 'yes' != $settings['reverse_content'] ) {
				$this->print_title();
			}
			?>
		</div>
		<?php
	}

	private function print_title() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['title_text'] ) ) {
			return;
		}

		$title_text = wp_kses( $settings['title_text'], [
			'br'   => [],
			'span' => [
				'class' => [],
			],
			'mark' => [
				'class' => [],
			],
		] );

		$this->add_render_attribute( 'title', 'class', 'tm-counter__heading' );
		?>
		<div class="tm-counter__heading-wrap">
			<?php printf( '<%1$s %2$s>%3$s</%1$s>', 'h3', $this->get_render_attribute_string( 'title' ), $title_text ); ?>
		</div>
		<?php
	}
}
