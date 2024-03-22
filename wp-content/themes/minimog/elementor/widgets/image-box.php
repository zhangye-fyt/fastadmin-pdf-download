<?php

namespace Minimog_Elementor;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Core\Schemes\Color as Scheme_Color;

defined( 'ABSPATH' ) || exit;

class Widget_Image_Box extends Base {

	public function get_name() {
		return 'tm-image-box';
	}

	public function get_title() {
		return esc_html__( 'Image Box', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-image-box';
	}

	public function get_keywords() {
		return [ 'image', 'photo', 'visual', 'box' ];
	}

	protected function register_controls() {
		$this->add_image_box_section();

		$this->add_box_style_section();

		$this->add_image_style_section();

		$this->add_content_style_section();

		$this->add_badge_style_section();

		$this->register_common_button_style_section();
	}

	private function add_image_box_section() {
		$this->start_controls_section( 'image_section', [
			'label' => esc_html__( 'Image Box', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'1' => sprintf( esc_html__( 'Style %s', 'minimog' ), '01' ),
				'2' => sprintf( esc_html__( 'Style %s', 'minimog' ), '02' ),
			],
			'default' => '1',
		] );

		$this->add_control( 'box_hover_effect', [
			'label'        => esc_html__( 'Box Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''        => esc_html__( 'None', 'minimog' ),
				'move-up' => esc_html__( 'Move Up', 'minimog' ),
			],
			'default'      => '',
			'prefix_class' => 'minimog-box-animation-',
		] );

		$this->add_control( 'image_hover_effect', [
			'label'        => esc_html__( 'Image Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'minimog' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'minimog' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'minimog' ),
				'move-up'  => esc_html__( 'Move Up', 'minimog' ),
			],
			'default'      => '',
			'prefix_class' => 'minimog-animation-',
		] );

		$this->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'minimog' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'minimog' ),
			'separator'   => 'before',
		] );

		$this->add_control( 'link_click', [
			'label'     => esc_html__( 'Apply Link On', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'box'    => esc_html__( 'Whole Box', 'minimog' ),
				'button' => esc_html__( 'Button Only', 'minimog' ),
			],
			'default'   => 'box',
			'condition' => [
				'link[url]!' => '',
			],
		] );

		$this->add_control( 'image_heading', [
			'label'     => esc_html__( 'Image', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'image', [
			'label'   => esc_html__( 'Choose Image', 'minimog' ),
			'type'    => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$this->add_control( 'badge_text', [
			'label'   => esc_html__( 'Badge Text', 'minimog' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'image',
			// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
			'default'   => 'full',
			'separator' => 'none',
			'condition' => [
				'image[url]!' => '',
			],
		] );

		$this->add_control( 'image_position', [
			'label'     => esc_html__( 'Image Position', 'minimog' ),
			'type'      => Controls_Manager::CHOOSE,
			'default'   => 'top',
			'options'   => [
				'left'   => [
					'title' => esc_html__( 'Left', 'minimog' ),
					'icon'  => 'eicon-h-align-left',
				],
				'top'    => [
					'title' => esc_html__( 'Top', 'minimog' ),
					'icon'  => 'eicon-v-align-top',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'minimog' ),
					'icon'  => 'eicon-h-align-right',
				],
				'bottom' => [
					'title' => esc_html__( 'Bottom', 'minimog' ),
					'icon'  => 'eicon-v-align-bottom',
				],
			],
			'toggle'    => false,
			'condition' => [
				'image[url]!' => '',
			],
		] );

		$this->add_control( 'content_vertical_alignment', [
			'label'     => esc_html__( 'Vertical Alignment', 'minimog' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_vertical_alignment(),
			'default'   => 'top',
			'condition' => [
				'image[url]!'     => '',
				'image_position!' => [ 'top', 'bottom' ],
			],
		] );

		$this->add_control( 'image_top_mobile', [
			'label'        => esc_html__( 'Image Top', 'minimog' ),
			'description'  => esc_html__( 'Place image on top on mobile.', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'minimog' ),
			'label_off'    => esc_html__( 'No', 'minimog' ),
			'return_value' => 'yes',
			'default'      => 'yes',
			'condition'    => [
				'image[url]!'    => '',
				'image_position' => [ 'left', 'right' ],
			],
		] );

		$this->add_control( 'title_heading', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'title_text', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'This is the heading', 'minimog' ),
			'placeholder' => esc_html__( 'Enter your title', 'minimog' ),
			'description' => esc_html__( 'Wrap any words with &lt;mark&gt;&lt;/mark&gt; tag to make them highlight.', 'minimog' ),
			'label_block' => true,
			'show_label'  => false,
		] );

		$this->add_control( 'title_size', [
			'label'   => esc_html__( 'HTML Tag', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p',
			],
			'default' => 'h3',
		] );

		$this->add_control( 'title_icon', [
			'label'   => esc_html__( 'Title Icon', 'minimog' ),
			'type'    => Controls_Manager::ICONS,
			'default' => [],
		] );

		$this->add_control( 'description_heading', [
			'label'     => esc_html__( 'Description', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'description_text', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'minimog' ),
			'placeholder' => esc_html__( 'Enter your description', 'minimog' ),
			'separator'   => 'none',
			'rows'        => 10,
			'label_block' => true,
			'show_label'  => false,
		] );

		$this->add_group_control( Group_Control_Button::get_type(), [
			'name'           => 'button',
			// Use box link instead of.
			'exclude'        => [
				'link',
			],
			// Change button style text as default.
			'fields_options' => [
				'style' => [
					'default' => 'bottom-line',
				],
				'text'  => [
					'default' => '',
				],
			],
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'minimog' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
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
			'selectors'            => [
				'{{WRAPPER}} .minimog-box' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-box'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_radius', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_min_height', [
			'label'     => esc_html__( 'Height', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-box' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_max_width', [
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
				'{{WRAPPER}} .minimog-box' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'              => 'left',
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
			'selector' => '{{WRAPPER}} .minimog-box',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .minimog-box',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .minimog-box',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'box_colors_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .minimog-box:hover',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_hover_border',
			'selector' => '{{WRAPPER}} .minimog-box:hover',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .minimog-box:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_image_style_section() {
		$this->start_controls_section( 'image_style_section', [
			'label' => esc_html__( 'Image', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_wrap_height', [
			'label'     => esc_html__( 'Wrap Height', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-image-box__image' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'image_vertical_alignment', [
			'label'                => esc_html__( 'Vertical Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_vertical_alignment(),
			'default'              => 'top',
			'condition'            => [
				'image[url]!'    => '',
				'image_position' => [ 'top', 'bottom' ],
			],
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .tm-image-box__image' => 'display: inline-flex; align-items: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'image_width', [
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
			'size_units'     => [ '%', 'px' ],
			'range'          => [
				'%'  => [
					'min' => 5,
					'max' => 50,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .tm-image-box__image' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'image_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-image-box__image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-image-box__image'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'image_radius', [
			'label'      => esc_html__( 'Radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'image_border',
			'selector' => '{{WRAPPER}} .minimog-image',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'image_shadow',
			'selector' => '{{WRAPPER}} .minimog-image',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters',
			'selector' => '{{WRAPPER}} .tm-image-box__image img',
		] );

		$this->add_control( 'image_opacity', [
			'label'     => esc_html__( 'Opacity', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-image-box__image img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'hover_image_border',
			'selector' => '{{WRAPPER}} .minimog-box:hover .minimog-image',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'image_shadow_hover',
			'selector' => '{{WRAPPER}} .minimog-box:hover .minimog-image',
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters_hover',
			'selector' => '{{WRAPPER}} .minimog-box:hover .tm-image-box__image img',
		] );

		$this->add_control( 'image_opacity_hover', [
			'label'     => esc_html__( 'Opacity', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 1,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-box:hover .tm-image-box__image img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_content_style_section() {
		$this->start_controls_section( 'content_style_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'content_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-image-box__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-image-box__content'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'heading_title', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
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
				'{{WRAPPER}} .tm-image-box__title' => 'width: {{SIZE}}{{UNIT}};',
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
				'{{WRAPPER}} .tm-image-box__title-wrapper' => 'display: flex; justify-content: {{VALUE}}',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'selector' => '{{WRAPPER}} .tm-image-box__title',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
		] );

		$this->start_controls_tabs( 'title_color_tabs' );

		$this->start_controls_tab( 'title_normal_color_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .tm-image-box__title' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_hover_color_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'hover_title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-box:hover .tm-image-box__title' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'heading_title_icon', [
			'label'     => esc_html__( 'Title Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'title_icon[value]!' => '',
			],
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'     => esc_html__( 'Icon Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-image-box__title-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'title_icon[value]!' => '',
			],
		] );

		$this->add_responsive_control( 'icon_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-image-box__title-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-image-box__title-icon'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'title_icon[value]!' => '',
			],
		] );

		$this->add_control( 'highlight_heading', [
			'label'     => esc_html__( 'Title Highlight Words', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_highlight',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .tm-image-box__title mark',
		] );

		$this->add_group_control( Group_Control_Text_Stroke::get_type(), [
			'name'     => 'title_highlight_text_stroke',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .tm-image-box__title mark',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_highlight',
			'selector' => '{{WRAPPER}} .tm-image-box__title mark',
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'title_highlight_border',
			'label'    => esc_html__( 'Border', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm-image-box__title mark',
		] );

		$this->add_control( 'heading_description', [
			'label'     => esc_html__( 'Description', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'description_top_space', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-image-box__description' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'description_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .tm-image-box__description' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'hover_description_color', [
			'label'     => esc_html__( 'Hover Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-box:hover .tm-image-box__description' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'selector' => '{{WRAPPER}} .tm-image-box__description',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
		] );

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

		$this->add_responsive_control( 'badge_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .tm-image-box__badge-wrap' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'badge_vertical_alignment', [
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
				'{{WRAPPER}} .tm-image-box__badge-wrap' => 'align-items: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'badge_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-image-box__badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-image-box__badge'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-image-box__badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-image-box__badge'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'badge_radius', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-image-box__badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'badge_typography',
			'selector' => '{{WRAPPER}} .tm-image-box__badge',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
		] );

		$this->add_control( 'badge_text_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .tm-image-box__badge' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'badge_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .tm-image-box__badge' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$wrapper_classes = [
			'tm-image-box',
			'minimog-box',
			'tm-image-box--image-' . $settings['image_position'],
		];

		if ( $settings['style'] ) {
			$wrapper_classes[] = 'tm-image-box--style-' . $settings['style'];
		}

		if ( in_array( $settings['image_position'], [ 'left', 'right' ] ) ) {
			$wrapper_classes[] = 'tm-image-box--content-alignment-' . $settings['content_vertical_alignment'];
		}

		if ( 'yes' === $settings['image_top_mobile'] ) {
			$wrapper_classes[] = 'tm-image-box--image-top-mobile';
		}

		$this->add_render_attribute( 'wrapper', 'class', $wrapper_classes );

		$box_tag = 'div';
		if ( ! empty( $settings['link']['url'] ) && 'box' === $settings['link_click'] ) {
			$box_tag = 'a';
			$this->add_render_attribute( 'wrapper', 'class', 'link-secret' );
			$this->add_link_attributes( 'wrapper', $settings['link'] );
		}
		?>
		<?php printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( 'wrapper' ) ); ?>
		<div class="tm-image-box__wrap">

			<?php if ( ! empty( $settings['image']['url'] ) ) : ?>
				<div class="tm-image-box__image">
					<div class="minimog-image">
						<?php echo \Minimog_Image::get_elementor_attachment( [
							'settings' => $settings,
						] ); ?>
					</div>
					<?php if ( ! empty( $settings['badge_text'] ) ) : ?>
						<div class="tm-image-box__badge-wrap">
							<div class="tm-image-box__badge"><?php echo esc_html( $settings['badge_text'] ); ?></div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="tm-image-box__content">
				<?php $this->print_title( $settings ); ?>

				<?php $this->print_description( $settings ); ?>

				<?php $this->render_common_button(); ?>
			</div>

		</div>
		<?php printf( '</%1$s>', $box_tag ); ?>
		<?php
	}

	protected function content_template() {
		// @formatter:off
		?>
		<#
		view.addRenderAttribute( 'wrapper', 'class', [
			'tm-image-box',
			'minimog-box',
			'tm-image-box--image-' + settings.image_position,
			'tm-image-box--style-' + settings.style,
			'tm-image-box--content-alignment-' + settings.content_vertical_alignment
		]);

		if ( 'yes' === settings.image_top_mobile ) {
			view.addRenderAttribute( 'wrapper', 'class', 'tm-image-box--image-top-mobile' );
		}

		var boxTag = 'div';

		if( '' !== settings.link.url && 'box' === settings.link_click ) {
			boxTag = 'a';

			view.addRenderAttribute( 'wrapper', 'href', '#' );
			view.addRenderAttribute( 'wrapper', 'class', 'link-secret' );
		}

		var imageHTML = '';

		if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );
			view.addRenderAttribute( 'image', 'src', image_url );

			imageHTML = '<div class="minimog-image"><img ' + view.getRenderAttributeString( 'image' ) + ' /></div>';

			if( '' !== settings.badge_text ) {
				imageHTML += '<div class="tm-image-box__badge-wrap"><div class="tm-image-box__badge">' + settings.badge_text + '</div></div>';
			}

			imageHTML = '<div class="tm-image-box__image">' + imageHTML + '</div>';
		}
		#>
		<{{{ boxTag }}} {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
			<div class="tm-image-box__wrap">
				{{{ imageHTML }}}

				<div class="tm-image-box__content">

					<# if ( settings.title_text ) { #>
						<#
						view.addRenderAttribute( 'title_text', 'class', 'tm-image-box__title-text' );
						view.addInlineEditingAttributes( 'title_text', 'none' );

						view.addRenderAttribute( 'title_icon', 'class', 'tm-image-box__title-icon minimog-solid-icon');

						if ( 'svg' === settings.title_icon.library ) {
							view.addRenderAttribute( 'title_icon', 'class', 'svg-icon' );
						}
						var iconHTML = elementor.helpers.renderIcon( view, settings.title_icon, { 'aria-hidden': true }, 'i' , 'object' );
						#>
						<div class="tm-image-box__title-wrapper">
							<{{{ settings.title_size }}} class="tm-image-box__title">
								<span {{{ view.getRenderAttributeString( 'title_text' ) }}}>{{{ settings.title_text }}}</span>
								<# if ( iconHTML && iconHTML.rendered ) { #>
									<span {{{ view.getRenderAttributeString( 'title_icon' ) }}}>{{{ iconHTML.value }}}</span>
								<# } #>
							</{{{ settings.title_size }}}>
						</div>
					<# } #>

					<# if ( settings.description_text ) { #>
						<#
						view.addRenderAttribute( 'description_text', 'class', 'tm-image-box__description' );
						view.addInlineEditingAttributes( 'description_text' );
						#>
						<div {{{ view.getRenderAttributeString( 'description_text' ) }}}>{{{ settings.description_text }}}</div>
					<# } #>

					<# if ( settings.button_text || settings.button_icon.value ) { #>
						<#
						var buttonIconHTML = elementor.helpers.renderIcon( view, settings.button_icon, { 'aria-hidden': true }, 'i' , 'object' );
						var buttonTag = 'div';

						view.addRenderAttribute( 'button', 'class', 'tm-button style-' + settings.button_style );
						view.addRenderAttribute( 'button', 'class', 'tm-button-' + settings.button_size );

						if ( '' !== settings.link.url && 'button' === settings.link_click ) {
							buttonTag = 'a';
							view.addRenderAttribute( 'button', 'href', '#' );
						}

						if ( settings.button_icon.value ) {
							view.addRenderAttribute( 'button', 'class', 'icon-' + settings.button_icon_align );
						}

						view.addRenderAttribute( 'button-icon', 'class', 'button-icon' );
						#>
						<div class="tm-button-wrapper">
							<{{{ buttonTag }}} {{{ view.getRenderAttributeString( 'button' ) }}}>

								<div class="button-content-wrapper">
									<# if ( buttonIconHTML.rendered && 'left' === settings.button_icon_align ) { #>
										<span {{{ view.getRenderAttributeString( 'button-icon' ) }}}>
											{{{ buttonIconHTML.value }}}
										</span>
									<# } #>

									<# if ( settings.button_text ) { #>
										<span class="button-text">{{{ settings.button_text }}}</span>

										<# if ( settings.button_style == 'bottom-line-winding' ) { #>
											<span class="line-winding">
												<svg width="42" height="6" viewBox="0 0 42 6" fill="none"
												     xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd"
													      d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
													      fill="#E8C8B3"/>
												</svg>
											</span>
										<# } #>
									<# } #>

									<# if ( buttonIconHTML.rendered && 'right' === settings.button_icon_align ) { #>
										<span {{{ view.getRenderAttributeString( 'button-icon' ) }}}>
											{{{ buttonIconHTML.value }}}
										</span>
									<# } #>
								</div>
							</{{{ buttonTag }}}>
						</div>
					<# } #>

				</div>
			</div>
		</{{{ boxTag }}}>
		<?php
		// @formatter:off
	}

	private function print_title( array $settings ) {
		if( empty( $settings['title_text'] ) ) {
			return;
		}

		$this->add_render_attribute( 'title_text', 'class', 'tm-image-box__title-text' );

		$this->add_inline_editing_attributes( 'title_text', 'none' );

		printf(
			'<div class="tm-image-box__title-wrapper"><%1$s class="tm-image-box__title"><span %2$s>%3$s</span>%4$s</%1$s></div>',
			$settings['title_size'],
			$this->get_render_attribute_string( 'title_text' ),
			$settings['title_text'],
			$this->get_title_icon( $settings )
		);
	}

	private function print_description( array $settings ) {
		if ( empty( $settings['description_text'] ) ) {
			return;
		}

		$this->add_render_attribute( 'description_text', 'class', 'tm-image-box__description' );
		$this->add_inline_editing_attributes( 'description_text' );
		?>
		<div <?php $this->print_render_attribute_string('description_text'); ?>>
			<?php echo wp_kses( $settings['description_text'], 'minimog-default' ); ?>
		</div>
		<?php
	}

	private function get_title_icon( array $settings ) {
		if ( empty( $settings['title_icon']['value'] ) ) {
			return;
		}

		$this->add_render_attribute( 'title_icon', 'class', [
			'tm-image-box__title-icon',
			'minimog-icon',
			'minimog-solid-icon',
		] );

		$is_svg = isset( $settings['title_icon']['library'] ) && 'svg' === $settings['title_icon']['library'] ? true : false;

		if ( $is_svg ) {
			$this->add_render_attribute( 'title_icon', 'class', [
				'svg-icon',
			] );
		}

		return sprintf(
			'<span %1$s>%2$s</span>',
			$this->get_render_attribute_string( 'title_icon' ),
			$this->get_render_icon( $settings, $settings['title_icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'title-icon' )
		);
	}
}
