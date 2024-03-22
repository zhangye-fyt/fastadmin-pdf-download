<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Icon_Box_List extends Base {

	public function get_name() {
		return 'tm-icon-box-list';
	}

	public function get_title() {
		return esc_html__( 'Modern Icon Box List', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return [ 'icon box', 'box icon', 'icon', 'box', 'icon list', 'list' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_icon_section();

		$this->add_content_section();

		// Style
		$this->add_box_style_section();

		$this->add_list_style_section();

		$this->add_icon_style_section();

		$this->add_title_style_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'icon_box_section', [
			'label' => esc_html__( 'Icon Box', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'' => esc_html__( 'None', 'minimog' ),
			],
			'default' => '',
		] );

		$this->end_controls_section();
	}

	private function add_icon_section() {
		$this->start_controls_section( 'icon_section', [
			'label' => esc_html__( 'Icon', 'minimog' ),
		] );

		$this->add_control( 'icon', [
			'label'      => esc_html__( 'Icon', 'minimog' ),
			'show_label' => false,
			'type'       => Controls_Manager::ICONS,
			'default'    => [
				'value'   => 'fas fa-star',
				'library' => 'fa-solid',
			],
		] );

		$this->add_control( 'view', [
			'label'        => esc_html__( 'View', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'default' => esc_html__( 'Default', 'minimog' ),
				'stacked' => esc_html__( 'Stacked', 'minimog' ),
				'bubble'  => esc_html__( 'Bubble', 'minimog' ),
			],
			'default'      => 'default',
			'prefix_class' => 'minimog-view-',
		] );

		$this->add_control( 'shape', [
			'label'        => esc_html__( 'Shape', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				'circle' => esc_html__( 'Circle', 'minimog' ),
				'square' => esc_html__( 'Square', 'minimog' ),
			],
			'default'      => 'circle',
			'prefix_class' => 'minimog-shape-',
		] );

		$this->add_control( 'position', [
			'label'        => esc_html__( 'Position', 'minimog' ),
			'type'         => Controls_Manager::CHOOSE,
			'default'      => 'left',
			'options'      => [
				'left'  => [
					'title' => esc_html__( 'Left', 'minimog' ),
					'icon'  => 'eicon-h-align-left',
				],
				'top'   => [
					'title' => esc_html__( 'Top', 'minimog' ),
					'icon'  => 'eicon-v-align-top',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'minimog' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'prefix_class' => 'elementor-position-',
			'toggle'       => false,
		] );

		$this->add_control( 'content_vertical_alignment', [
			'label'        => esc_html__( 'Vertical Alignment', 'minimog' ),
			'type'         => Controls_Manager::CHOOSE,
			'options'      => Widget_Utils::get_control_options_vertical_alignment(),
			'default'      => 'top',
			'prefix_class' => 'elementor-vertical-align-',
			'condition'    => [
				'position!' => 'top',
			],
		] );

		$this->end_controls_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'icon_title_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
		] );

		$this->add_control( 'title_text', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'This is the heading', 'minimog' ),
			'placeholder' => esc_html__( 'Enter your title', 'minimog' ),
			'label_block' => true,
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

		$this->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'minimog' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'minimog' ),
		] );

		$this->add_control( 'list_heading', [
			'label'     => esc_html__( 'Items', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'text', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => esc_html__( 'Text', 'minimog' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'minimog' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'minimog' ),
		] );

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'show_label'  => false,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'text' => 'List Item #1',
				],
				[
					'text' => 'List Item #2',
				],
				[
					'text' => 'List Item #3',
				],
			],
			'title_field' => '{{{ text }}}',
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
				'{{WRAPPER}} .tm-icon-box-list__wrapper' => 'text-align: {{VALUE}};',
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
				'body:not(.rtl) {{WRAPPER}} .tm-icon-box-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-icon-box-list'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
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
				'{{WRAPPER}} .tm-icon-box-list' => 'max-width: {{SIZE}}{{UNIT}};',
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
			'selector' => '{{WRAPPER}} .tm-icon-box-list',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .tm-icon-box-list',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .tm-icon-box-list',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'box_colors_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box-list:hover',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_hover_border',
			'selector' => '{{WRAPPER}} .tm-icon-box-list:hover',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box-list:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_icon_style_section() {
		$this->start_controls_section( 'icon_style_section', [
			'label' => esc_html__( 'Icon', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'icon_wrap_height', [
			'label'     => esc_html__( 'Wrap Height', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-icon-wrap' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab( 'icon_colors_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon',
			'selector' => '{{WRAPPER}} .icon',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_colors_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_icon',
			'selector' => '{{WRAPPER}} .tm-icon-box-list:hover .icon',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'icon_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-icon-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				'body.rtl {{WRAPPER}} .minimog-icon-wrap'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}}',
			],
			'separator'  => 'before',
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'     => esc_html__( 'Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 6,
					'max' => 300,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-icon-view, {{WRAPPER}} .minimog-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_rotate', [
			'label'     => esc_html__( 'Rotate', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [
				'unit' => 'deg',
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
			],
		] );

		// Icon View Settings.
		$this->add_control( 'icon_view_heading', [
			'label'     => esc_html__( 'Icon View', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'view' => [ 'stacked', 'bubble' ],
			],
		] );

		$this->add_control( 'icon_padding', [
			'label'     => esc_html__( 'Padding', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'selectors' => [
				'{{WRAPPER}} .minimog-icon-view' => 'padding: {{SIZE}}{{UNIT}};',
			],
			'range'     => [
				'em' => [
					'min' => 0,
					'max' => 5,
				],
			],
			'condition' => [
				'view' => [ 'stacked' ],
			],
		] );

		$this->add_control( 'icon_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-icon-view' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'view' => [ 'stacked' ],
			],
		] );

		$this->start_controls_tabs( 'icon_view_colors', [
			'condition' => [
				'view' => [ 'stacked', 'bubble' ],
			],
		] );

		$this->start_controls_tab( 'icon_view_colors_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'icon_view',
			'selector' => '{{WRAPPER}} .minimog-icon-view',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'icon_view',
			'selector' => '{{WRAPPER}} .minimog-icon-view',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_view_colors_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'hover_icon_view',
			'selector' => '{{WRAPPER}} .tm-icon-box-list:hover .minimog-icon-view',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'hover_icon_view',
			'selector' => '{{WRAPPER}} .tm-icon-box-list:hover .minimog-icon-view',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_title_style_section() {
		$this->start_controls_section( 'title_style_section', [
			'label' => esc_html__( 'Title', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'heading_max_width', [
			'label'          => esc_html__( 'Max Width', 'minimog' ),
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
				'{{WRAPPER}} .tm-icon-box-list__heading' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );


		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .tm-icon-box-list__heading',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
		] );

		$this->start_controls_tabs( 'title_colors' );

		$this->start_controls_tab( 'title_color_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title',
			'selector' => '{{WRAPPER}} .tm-icon-box-list__heading',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_color_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'title_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box-list:hover .tm-icon-box-list__heading',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_list_style_section() {
		$this->start_controls_section( 'list_style_section', [
			'label' => esc_html__( 'List', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'list_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .box-list' => 'margin-top: {{SIZE}}{{UNIT}};',
			],

		] );

		$this->add_responsive_control( 'list_max_width', [
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
				'{{WRAPPER}} .box-list' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'list_style_heading', [
			'label'     => esc_html__( 'Item', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'item_spacing', [
			'label'      => esc_html__( 'Item Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .box-list__item + .box-list__item' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'list_typography',
			'selector' => '{{WRAPPER}} .box-list__item',
		] );

		$this->start_controls_tabs( 'list_colors' );

		$this->start_controls_tab( 'list_color_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'list',
			'selector' => '{{WRAPPER}} .box-list__text',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'list_color_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'list_hover',
			'selector' => '{{WRAPPER}} .tm-icon-box-list .box-list__text:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'box', 'class', 'tm-icon-box-list minimog-box' );

		?>
		<div <?php $this->print_attributes_string( 'box' ); ?>>
			<div class="tm-icon-box-list__wrapper">
				<?php $this->print_icon( $settings ); ?>

				<div class="tm-icon-box-list__content">
					<?php $this->print_title( $settings ); ?>
					<?php $this->print_list( $settings ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function content_template() {
		$id = uniqid( 'svg-gradient' );
		// @formatter:off
		?>
		<# var svg_id = '<?php echo esc_html( $id ); ?>'; #>

		<#
		view.addRenderAttribute( 'box', 'class', 'tm-icon-box-list minimog-box' );

		view.addRenderAttribute( 'icon', 'class', 'minimog-icon icon');

		if ( 'svg' === settings.icon.library ) {
			view.addRenderAttribute( 'icon', 'class', 'minimog-svg-icon' );
		}

		if ( 'gradient' === settings.icon_color_type ) {
			view.addRenderAttribute( 'icon', 'class', 'minimog-gradient-icon' );
		} else {
			view.addRenderAttribute( 'icon', 'class', 'minimog-solid-icon' );
		}

		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
		#>
		<div {{{ view.getRenderAttributeString( 'box' ) }}}>
			<div class="tm-icon-box-list__wrapper">

				<# if ( settings.icon.value ) { #>
					<div class="minimog-icon-wrap">
						<div class="minimog-icon-view">
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
				<div class="tm-icon-box-list__content">
					<# if ( settings.title_text ) { #>
						<#
						view.addRenderAttribute( 'title', 'class', 'tm-icon-box-list__heading' );
						var link_tag = 'span';

						if ( settings.link.url ) {
							link_tag = 'a';
							view.addRenderAttribute( 'link', 'href', settings.link.url );
						}

						#>
							<{{{ settings.title_size }}} {{{ view.getRenderAttributeString( 'title' ) }}}>
								<{{{ link_tag }}} {{{ view.getRenderAttributeString( 'link' ) }}}>
									{{{ settings.title_text }}}
								</{{{ link_tag }}}>
							</{{{ settings.title_size }}}>
					<# } #>

					<# if ( settings.items ) { #>
						<ul class="box-list">
							<#
							_.each( settings.items, function( item, index ) {
								var key = view.getRepeaterSettingKey( 'item', 'items', index ),
									linkTag = 'span';

								view.addRenderAttribute( key, 'class', 'box-list__text' );
								if ( item.link.url ) {
									linkTag = 'a';
									view.addRenderAttribute( key, 'href', item.link.url );
								}
								#>
								<li class="box-list__item">
									<{{{ linkTag }}}>{{{ item.text }}}</{{{ linkTag }}}>
								</li>
								<#
							} );
							#>
						</ul>
					<# } #>
				</div>

			</div>
		</div>
		<?php
		// @formatter:off
	}

	private function print_icon( array $settings ) {
		if ( empty( $settings['icon']['value'] ) ) {
			return;
		}

		$classes = [
			'minimog-icon',
			'icon',
		];

		$is_svg = isset( $settings['icon']['library'] ) && 'svg' === $settings['icon']['library'] ? true : false;

		if ( $is_svg ) {
			$classes[] = 'minimog-svg-icon';
		}

		if ( 'gradient' === $settings['icon_color_type'] ) {
			$classes[] = 'minimog-gradient-icon';
		} else {
			$classes[] = 'minimog-solid-icon';
		}

		$this->add_render_attribute( 'icon', 'class', $classes );
		?>
		<div class="minimog-icon-wrap">
			<div class="minimog-icon-view">
				<div <?php $this->print_attributes_string( 'icon' ); ?>>
					<?php $this->render_icon( $settings, $settings['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	private function print_title( array $settings ) {
		if ( empty( $settings['title_text'] ) ) {
			return;
		}

		$link_tag = 'span';

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
			$link_tag = 'a';
		}

		$this->add_render_attribute( 'title', 'class', 'tm-icon-box-list__heading' );
		printf(
			'<%1$s %2$s><%3$s %4$s>%5$s</%3$s></%1$s>',
			$settings['title_size'],
			$this->get_render_attribute_string( 'title' ),
			$link_tag,
			$this->get_render_attribute_string( 'link' ),
			wp_kses_post( $settings['title_text'] )
		);
	}

	private function print_list( array $settings ) {
		if ( empty( $settings['items'] ) ) {
			return;
		}

		$this->add_render_attribute( 'list', 'class', 'box-list' );
		?>
		<ul <?php $this->print_attributes_string( 'list' ); ?>>
			<?php
			foreach( $settings['items'] as $index => $item ) {
				$key = $this->get_repeater_setting_key( 'item', 'items', $index );

				$link_tag = 'span';
				$this->add_render_attribute( $key, 'class', 'box-list__text' );

				if ( ! empty( $item['link']['url'] ) ) {
					$this->add_link_attributes( $key, $item['link'] );
					$link_tag = 'a';
				}
				printf(
					'<li class="box-list__item"><%1$s %2$s>%3$s</%1$s></li>',
					$link_tag,
					$this->get_render_attribute_string( $key ),
					wp_kses_post( $item['text'] )
				);
			}
			?>
		</ul>
		<?php
	}
}
