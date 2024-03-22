<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Widget_Carousel_Product_Tabs extends Carousel_Base {

	private $tab_key      = '';
	private $current_loop = 0;
	private $products     = '';

	private $loop_settings = [];

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_script( 'minimog-widget-product-tabs', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-product-tabs.js', array(
			'minimog-tab-panel',
			'minimog-nice-select',
			'elementor-frontend',
		), null, true );
	}

	public function get_name() {
		return 'tm-carousel-product-tabs';
	}

	public function get_title() {
		return esc_html__( 'Product Carousel Tabs', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-product-tabs';
	}

	public function get_keywords() {
		return [ 'product', 'tabs', 'product tabs', 'product carousel tabs', 'product slider tabs' ];
	}

	public function get_script_depends() {
		return [
			'minimog-swiper-wrapper',
			'minimog-widget-product-tabs',
		];
	}

	public function get_tab_key() {
		return $this->tab_key;
	}

	public function update_slider_settings( $settings, $slider_settings ) {
		/**
		 * Disable autoplay for skeleton.
		 */
		unset( $slider_settings['data-autoplay'] );

		return $slider_settings;
	}

	protected function get_post_type() {
		return 'product';
	}

	protected function register_controls() {
		$this->add_product_tabs_section();

		$this->add_layout_section();

		$this->add_tabs_style_section();

		$this->add_thumbnail_style_section();

		$this->add_caption_style_section();

		parent::register_controls();

		$this->update_controls();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Product Layout', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Product Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => \Minimog_Woo::instance()->get_shop_loop_carousel_style_options(),
			'default' => 'carousel-01',
		] );

		$this->add_control( 'caption_style', [
			'label'       => esc_html__( 'Caption Style', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => \Minimog_Woo::instance()->get_shop_loop_caption_style_options(),
			'default'     => '01',
			'render_type' => 'template',
		] );

		$this->add_control( 'show_price', [
			'label'        => esc_html__( 'Show Price', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'show_variation', [
			'label'        => esc_html__( 'Show Variation', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'show_category', [
			'label'        => esc_html__( 'Show Category', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_brand', [
			'label'        => esc_html__( 'Show Brand', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_rating', [
			'label'        => esc_html__( 'Show Rating', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_availability', [
			'label'        => esc_html__( 'Show Availability', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_stock_bar', [
			'label'        => esc_html__( 'Show Stock Bar', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'thumbnail_default_size', [
			'label'        => esc_html__( 'Use Default Thumbnail Size', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
			'separator'    => 'before',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'thumbnail',
			'default'   => 'full',
			'condition' => [
				'thumbnail_default_size!' => '1',
			],
		] );

		$this->add_control( 'product_title_collapse', [
			'label'        => esc_html__( 'Product Title Collapse', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
			'prefix_class' => 'product-title-collapse-',
			'separator'    => 'before',
		] );

		$this->end_controls_section();
	}

	protected function add_product_tabs_section() {
		$this->start_controls_section( 'product_tabs_section', [
			'label' => esc_html__( 'Heading', 'minimog' ),
		] );

		$this->add_responsive_control( 'heading_alignment', [
			'label'     => esc_html__( 'Alignment', 'minimog' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex'  => [
					'title' => esc_html__( 'Horizontal', 'minimog' ),
					'icon'  => 'eicon-ellipsis-h',
				],
				'block' => [
					'title' => esc_html__( 'Vertical', 'minimog' ),
					'icon'  => 'eicon-ellipsis-v',
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs__header-wrap' => '--minimog-tabs-heading-display: {{VALUE}} ',
			],
		] );

		$this->add_responsive_control( 'nav_tab_text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .minimog-tabs__header-wrap' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_control( 'heading_title_hr', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'product_tabs_title', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Enter your title', 'minimog' ),
			'default'     => '',
		] );

		$this->add_control( 'product_tabs_title_size', [
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
			'default' => 'h2',
		] );

		$this->add_control( 'product_tabs_hr', [
			'label'     => esc_html__( 'Product Tabs', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'tabs_nav_style', [
			'label'   => esc_html__( 'Tabs Nav Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''         => esc_html__( 'Default', 'minimog' ),
				'dropdown' => esc_html__( 'Dropdown', 'minimog' ),
				'01'       => sprintf( esc_html__( 'Preset %s', 'minimog' ), '01' ),
				'02'       => sprintf( esc_html__( 'Preset %s', 'minimog' ), '02' ),
			],
			'default' => '',
		] );

		$this->add_control( 'intro_text', [
			'label'       => esc_html__( 'Intro Text', 'minimog' ),
			'description' => esc_html__( 'This text display before dropdown', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => esc_html__( 'You are in', 'minimog' ),
			'label_block' => true,
			'condition'   => [
				'tabs_nav_style' => 'dropdown',
			],
		] );

		$this->add_control( 'number_posts', [
			'label'       => esc_html__( 'Number posts', 'minimog' ),
			'description' => esc_html__( 'Select number of products display on each of tab.', 'minimog' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => -1,
			'max'         => 100,
			'step'        => 1,
			'default'     => 8,
		] );

		$source_options = [
			'latest'       => esc_html__( 'All Products (Latest)', 'minimog' ),
			'on_sale'      => esc_html__( 'OnSale Products', 'minimog' ),
			'top_rated'    => esc_html__( 'Top Rated Products', 'minimog' ),
			'best_selling' => esc_html__( 'Best Selling Products', 'minimog' ),
			'featured'     => esc_html__( 'Featured Products', 'minimog' ),
		];

		/**
		 * WP-PostViews
		 */
		if ( function_exists( 'the_views' ) ) {
			$source_options['trending'] = esc_html__( 'Trending Products', 'minimog' );
		}

		$repeater = new Repeater();

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Tab Title', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'description' => esc_html__( 'Leave blank to use default.', 'minimog' ),
		] );

		$repeater->add_control( 'query_source', [
			'label'   => esc_html__( 'Source', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $source_options,
			'default' => 'latest',
		] );

		$repeater->add_control( 'query_include_term_ids', [
			'label'        => esc_html__( 'Include Terms', 'minimog' ),
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'options'      => [],
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => [
				'object'  => Module_Query_Base::QUERY_OBJECT_TAX,
				'display' => 'detailed',
				'query'   => [
					'post_type' => $this->get_post_type(),
				],
			],
		] );

		$this->add_control( 'tabs', [
			'label'       => esc_html__( 'Product Tabs', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => "{{{ title || MinimogElementor.helpers.getRepeaterTextForProductTabs('tm-carousel-product-tabs', 'tabs', 'query_source', query_source) }}}",
			'default'     => [
				[
					'title'        => '',
					'query_source' => 'latest',
				],
			],
		] );

		$this->end_controls_section();
	}

	private function add_tabs_style_section() {
		$this->start_controls_section( 'product_tabs_style_section', [
			'label' => esc_html__( 'Heading Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'nav_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max' => 100,
					'min' => 0,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-tabs' => '--tab-content-spacing: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_control( 'nav_tabs_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs__header-wrap:after' => 'background: {{VALUE}};',
			],
			'condition' => [
				'tabs_nav_style' => '',
			],
		] );

		$this->add_control( 'heading_title_style_hr', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'product_tabs_title!' => '',
			],
		] );

		$this->add_responsive_control( 'heading_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-tabs__title-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-tabs__title-wrap'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'product_tabs_title!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'title',
			'selector'  => '{{WRAPPER}} .minimog-tabs__title',
			'condition' => [
				'product_tabs_title!' => '',
			],
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'      => 'title',
			'selector'  => '{{WRAPPER}} .minimog-tabs__title',
			'condition' => [
				'product_tabs_title!' => '',
			],
		] );

		// Dropdown.
		$dropdown_condition = [ 'tabs_nav_style' => 'dropdown' ];
		$this->add_control( 'dropdown_style_heading', [
			'label'     => esc_html__( 'Dropdown Section', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $dropdown_condition,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'dropdown_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .minimog-tab-header__dropdown-section',
			'condition' => $dropdown_condition,
		] );

		$this->add_control( 'intro_text_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-tab-header__dropdown-section .intro-text' => 'color: {{VALUE}};',
			],
			'condition' => $dropdown_condition,
		] );

		$this->add_control( 'dropdown_color', [
			'label'     => esc_html__( 'Dropdown Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-tab-header__dropdown-section select'                       => 'color: {{VALUE}};',
				'{{WRAPPER}} .minimog-tab-header__dropdown-section .minimog-nice-select-current' => 'color: {{VALUE}};',
			],
			'condition' => $dropdown_condition,
		] );

		$this->add_control( 'dropdown_focus_color', [
			'label'     => esc_html__( 'Dropdown Focus Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-nice-select-wrap.focused .minimog-nice-select-current' => 'color: {{VALUE}};',
			],
			'condition' => $dropdown_condition,
		] );

		$this->add_responsive_control( 'dropdown_width', [
			'label'      => esc_html__( 'Dropdown Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max' => 500,
					'min' => 0,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-tab-header__dropdown-section .minimog-nice-select-wrap' => 'min-width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => $dropdown_condition,
		] );

		// Item.
		$no_dropdown_condition = [ 'tabs_nav_style!' => 'dropdown' ];
		$this->add_control( 'item_style_heading', [
			'label'     => esc_html__( 'Tab Item', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $no_dropdown_condition,
		] );

		$this->add_responsive_control( 'nav_item_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max' => 100,
					'min' => 0,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-tabs' => '--tab-title-spacing: {{SIZE}}{{UNIT}}',
			],
			'condition'  => $no_dropdown_condition,
		] );

		$this->add_responsive_control( 'nav_tab_item_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-tabs .tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-tabs .tab-title'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => $no_dropdown_condition,
		] );

		$this->add_control( 'item_border_width', [
			'label'      => esc_html__( 'Border Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max' => 100,
					'min' => 0,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-tabs .tab-title:after' => 'height: {{SIZE}}{{UNIT}}',
			],
			'condition'  => $no_dropdown_condition,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'nav_tab_item_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .minimog-tabs .tab-title .tab-title__text',
			'condition' => $no_dropdown_condition,
		] );

		$this->start_controls_tabs( 'nav_colors_tabs', [
			'condition' => $no_dropdown_condition,
		] );

		$this->start_controls_tab( 'nav_colors_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'nav_item_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs .tab-title .tab-title__text' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'nav_item_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs .tab-title:after' => 'background: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'nav_item_colors_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'hover_nav_item_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs .tab-title:hover .tab-title__text' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'hover_nav_item_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs .tab-title:hover:after' => 'background: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'nav_item_colors_active', [
			'label' => esc_html__( 'Active', 'minimog' ),
		] );

		$this->add_control( 'active_nav_item_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs .tab-title.active .tab-title__text' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'active_nav_item_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs .tab-title.active:after' => 'background: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_thumbnail_style_section() {
		$this->start_controls_section( 'thumbnail_style_section', [
			'label' => esc_html__( 'Thumbnail', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'thumbnail_border_radius', [
			'label'     => esc_html__( 'Border Radius', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .thumbnail' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->end_controls_section();
	}

	private function add_caption_style_section() {
		$this->start_controls_section( 'caption_style_section', [
			'label' => esc_html__( 'Product Caption', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'caption_text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .product-info' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'caption_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .product-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .product-info'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'caption_title_heading', [
			'label'     => esc_html__( 'Product Name', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_title_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .woocommerce-loop-product__title',
		] );

		$this->start_controls_tabs( 'caption_title_tabs' );

		$this->start_controls_tab( 'caption_title_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'caption_title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woocommerce-loop-product__title a' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_title_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'caption_title_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woocommerce-loop-product__title a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'caption_price_heading', [
			'label'     => esc_html__( 'Product Price', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'caption_price_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-product div.price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-product div.price'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_price_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .product-info .price, {{WRAPPER}} .product-info .amount',
		] );

		$this->start_controls_tabs( 'caption_price_tabs' );

		$this->start_controls_tab( 'caption_regular_price_tab', [
			'label' => esc_html__( 'Regular', 'minimog' ),
		] );

		$this->add_control( 'caption_regular_price_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .price'  => 'color: {{VALUE}};',
				'{{WRAPPER}} .product-info .amount' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_sale_price_tab', [
			'label' => esc_html__( 'Sale', 'minimog' ),
		] );

		$this->add_control( 'caption_sale_regular_price_color', [
			'label'     => esc_html__( 'Regular Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .price del'         => 'color: {{VALUE}};',
				'{{WRAPPER}} .price del .amount' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_sale_price_color', [
			'label'     => esc_html__( 'Sale Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product.sale ins'         => 'color: {{VALUE}};',
				'{{WRAPPER}} .product.sale ins .amount' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'caption_category_heading', [
			'label'     => esc_html__( 'Product Category', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_responsive_control( 'caption_category_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .loop-product-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .loop-product-category'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'show_category' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'caption_category_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .product-info .loop-product-category a',
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_control( 'caption_category_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .loop-product-category a' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_control( 'caption_category_hover_color', [
			'label'     => esc_html__( 'Hover Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .loop-product-category a:hover' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_control( 'caption_rating_heading', [
			'label'     => esc_html__( 'Product Rating', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_rating' => '1',
			],
		] );

		$this->add_control( 'caption_rating_star_fill_color', [
			'label'     => esc_html__( 'Star Fill Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating' => '--fill: {{VALUE}}; --half: {{VALUE}};',
			],
			'condition' => [
				'show_rating' => '1',
			],
		] );

		$this->add_control( 'caption_rating_star_empty_color', [
			'label'     => esc_html__( 'Star Empty Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating' => '--empty: {{VALUE}};',
			],
			'condition' => [
				'show_rating' => '1',
			],
		] );

		$this->add_control( 'caption_availability_heading', [
			'label'     => esc_html__( 'Product Availability', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_availability' => '1',
			],
		] );

		$this->add_responsive_control( 'caption_availability_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .loop-product-availability' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .loop-product-availability'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'show_availability' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'caption_availability_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .loop-product-availability',
			'condition' => [
				'show_availability' => '1',
			],
		] );

		$this->add_control( 'caption_availability_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .loop-product-availability' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_availability' => '1',
			],
		] );

		$this->end_controls_section();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'default'        => '4',
			'tablet_default' => '2',
			'mobile_default' => '1',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 30,
		] );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['tabs'] ) ) {
			return;
		}

		$layout       = 'carousel';
		$product_tabs = $settings['tabs'];
		$number_posts = $settings['number_posts'];
		$style        = ! empty( $settings['style'] ) ? $settings['style'] : 'carousel-01';
		$tabs_key     = 'product_tabs';

		$this->add_render_attribute( $tabs_key, 'class', [
			'minimog-tabs minimog-tabs--horizontal',
			'minimog-tabs--nav-style-' . $settings['tabs_nav_style'],
		] );

		if ( 'dropdown' === $settings['tabs_nav_style'] ) {
			$this->add_render_attribute( $tabs_key, 'class', 'minimog-tabs--nav-type-dropdown' );
		}

		$caption_style = ! empty( $settings['caption_style'] ) ? $settings['caption_style'] : '01';

		$this->loop_settings = [
			'style'             => $style,
			'caption_style'     => $caption_style,
			'layout'            => 'slider',
			'show_price'        => ! empty( $settings['show_price'] ) ? 1 : 0,
			'show_variation'    => ! empty( $settings['show_variation'] ) ? 1 : 0,
			'show_category'     => ! empty( $settings['show_category'] ) ? 1 : 0,
			'show_brand'        => ! empty( $settings['show_brand'] ) ? 1 : 0,
			'show_rating'       => ! empty( $settings['show_rating'] ) ? 1 : 0,
			'show_availability' => ! empty( $settings['show_availability'] ) ? 1 : 0,
			'show_stock_bar'    => ! empty( $settings['show_stock_bar'] ) ? 1 : 0,
		];

		if ( isset( $settings['thumbnail_default_size'] ) && '1' !== $settings['thumbnail_default_size'] ) {
			$this->loop_settings['thumbnail_size'] = \Minimog_Image::elementor_parse_image_size( $settings );
		}
		?>
		<div <?php $this->print_render_attribute_string( $tabs_key ); ?>>
			<div class="minimog-tabs__header-wrap">
				<?php $this->print_title( $settings ); ?>

				<div class="minimog-tabs__header-inner">
					<?php if ( 'dropdown' === $settings['tabs_nav_style'] ) : ?>
						<?php $this->print_dropdown_section( $settings ); ?>
					<?php else: ?>
						<div class="minimog-tabs__header" role="tablist">
							<?php $loop_count = 0; ?>
							<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
								<?php
								$tab_id        = $product_tab['_id'];
								$tab_key       = "tab_title_{$tab_id}";
								$this->tab_key = $tab_key;
								$loop_count++;

								$this->add_render_attribute( $tab_key, [
									'class'         => [ 'tab-title' ],
									'id'            => "tab-title-{$tab_id}",
									'data-tab'      => $loop_count,
									'role'          => 'tab',
									'aria-controls' => "tab-content-{$tab_id}",
									'aria-selected' => 1 === $loop_count ? 'true' : 'false',
									'tabindex'      => 1 === $loop_count ? '0' : '-1',
								] );

								if ( 1 === $loop_count ) {
									$this->add_render_attribute( $tab_key, 'class', 'active' );
								}
								?>
								<div <?php $this->print_render_attribute_string( $tab_key ); ?>>
							<span
								class="tab-title__text"><?php echo '' . $this->get_tab_title( $product_tab ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="minimog-tabs__content">
				<?php $this->current_loop = 0; ?>
				<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
					<?php
					$this->set_slider_key( 'slider-' . $key );
					$tab_id        = $product_tab['_id'];
					$tab_key       = "tab_content_{$tab_id}";
					$this->tab_key = $tab_key;
					$this->current_loop++;

					$this->add_render_attribute( $tab_key, [
						'class'           => [ 'tab-content' ],
						'data-tab'        => $this->current_loop,
						'id'              => "tab-content-{$tab_id}",
						'role'            => 'tabpanel',
						'tabindex'        => '0',
						'aria-expanded'   => 1 === $this->current_loop ? 'true' : 'false',
					] );

					if ( 'dropdown' !== $settings['tabs_nav_style'] ) {
						$this->add_render_attribute( $tab_key, 'aria-labelledby', "tab-title-{$tab_id}" );
					}

					$source = $product_tab['query_source'];

					$query_args = [
						'source'           => $source,
						'number'           => $number_posts,
						'layout'           => $layout,
						'style'            => $style,
						'include_term_ids' => ! empty( $product_tab['query_include_term_ids'] ) ? $product_tab['query_include_term_ids'] : array(),
						'loop_settings'    => $this->loop_settings,
					];

					/**
					 * Display first tab without ajax load for better UX.
					 */
					if ( 1 === $this->current_loop ) {
						$this->add_render_attribute( $tab_key, 'class', 'active ajax-loaded' );
						$this->products = \Minimog\Woo\Ajax_Handlers::instance()->get_product_tab_content( $query_args );
					} else {
						$this->add_render_attribute( $tab_key, 'data-query', wp_json_encode( $query_args ) );
					}

					$this->add_render_attribute( $tab_key, 'data-layout', $layout );
					$this->add_render_attribute( $tab_key, 'data-slider-autoplay', $settings['swiper_autoplay'] );
					?>
					<div <?php $this->print_render_attribute_string( $tab_key ); ?><?php echo 1 === $this->current_loop ? '' : ' hidden'; ?>>
						<div class="tab-content-wrapper">
							<?php
							$this->before_slider();

							$this->print_slider( $settings );

							$this->after_slider();
							?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	private function print_dropdown_section( $settings ) {
		$product_tabs = $settings['tabs'];
		?>
		<div class="minimog-tab-header__dropdown-section">
			<?php
			if ( ! empty( $settings['intro_text'] ) ) {
				echo '<div class="intro-text">' . wp_kses( $settings['intro_text'], 'minimog-default' ) . '</div>';
			}
			?>
			<select class="tab-select">
				<?php $loop_count = 0; ?>
				<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
					<?php $loop_count++; ?>
					<option value="<?php echo esc_attr( $loop_count ) ?>">
						<?php echo '' . $this->get_tab_title( $product_tab ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	}

	private function get_tab_title( $tab ) {
		if ( ! empty( $tab['title'] ) ) {
			return $tab['title'];
		}

		$text = '';

		switch ( $tab['query_source'] ) {
			case 'latest' :
				$text = esc_html__( 'All', 'minimog' );
				break;
			case 'trending' :
				$text = esc_html__( 'Trending', 'minimog' );
				break;
			case 'best_selling' :
				$text = esc_html__( 'Best Selling', 'minimog' );
				break;
			case 'top_rated' :
				$text = esc_html__( 'Top Rated', 'minimog' );
				break;
			case 'on_sale' :
				$text = esc_html__( 'On Sale', 'minimog' );
				break;
			case 'popular' :
				$text = esc_html__( 'Popularity', 'minimog' );
				break;
			case 'featured' :
				$text = esc_html__( 'Featured', 'minimog' );
				break;
			default :
				$text = $tab;
				break;
		}

		return $text;
	}

	public function before_slider() {
		$settings = $this->get_settings_for_display();

		$style         = ! empty( $settings['style'] ) ? $settings['style'] : 'carousel-01';
		$caption_style = ! empty( $settings['caption_style'] ) ? $settings['caption_style'] : '01';

		$this->add_render_attribute( $this->get_slider_key(), 'class', str_replace( 'carousel-', 'group-style-', $style ) );
		$this->add_render_attribute( $this->get_slider_key(), 'class', 'tm-tab-product-element minimog-product style-' . $style );
		$this->add_render_attribute( $this->get_slider_key(), 'class', 'caption-style-' . $caption_style );
	}

	public function after_slider() {
		echo '<div class="minimog-grid-response-messages"></div>';
	}

	protected function print_slides( array $settings ) {
		?>
		<?php if ( 1 === $this->current_loop ) : ?>
			<?php echo '' . $this->products['template']; ?>
		<?php else: ?>
			<?php
			$number_posts = $settings['number_posts'];
			if ( isset( $settings['thumbnail_default_size'] ) && '1' === $settings['thumbnail_default_size'] ) {
				$skeleton_image_height = \Minimog_Woo::instance()->get_product_image_ratio_height_percent();
			} else {
				$skeleton_image_height = \Minimog_Image::get_elementor_image_ratio_height_percent( $settings );
			}
			?>
			<?php for ( $i = $number_posts; $i > 0; $i-- ): ?>
				<div class="swiper-slide" style="<?php echo '--skeleton-image-height: ' . $skeleton_image_height; ?>">
					<?php \Minimog_Woo::instance()->output_skeleton_loading_item( $this->loop_settings ); ?>
				</div>
			<?php endfor; ?>
		<?php endif;
	}

	private function print_title( array $settings ) {
		if ( empty( $title = $settings['product_tabs_title'] ) ) {
			return;
		}

		// .elementor-heading-title -> Default color from section + column.
		$this->add_render_attribute( 'title', 'class', 'minimog-tabs__title elementor-heading-title' );

		?>
		<div class="minimog-tabs__title-wrap">
			<?php printf( '<%1$s %2$s>%3$s</%1$s>', $settings['product_tabs_title_size'], $this->get_render_attribute_string( 'title' ), $title ); ?>
		</div>
		<?php
	}
}
