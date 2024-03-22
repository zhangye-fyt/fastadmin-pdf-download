<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Core\Base\Document;

defined( 'ABSPATH' ) || exit;

class Widget_Tabs extends Base {
	public function get_name() {
		return 'tm-tabs';
	}

	public function get_title() {
		return esc_html__( 'Tabs', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-tabs';
	}

	public function get_keywords() {
		return [ 'modern', 'accordion', 'tabs', 'toggle' ];
	}

	public function get_script_depends() {
		return [ 'minimog-widget-tabs' ];
	}

	protected function register_controls() {
		$this->add_tabs_content_section();

		$this->add_tabs_style_section();

		$this->add_tab_content_style_section();
	}

	private function add_tabs_content_section() {
		$this->start_controls_section( 'tabs_section', [
			'label' => esc_html__( 'Tabs', 'minimog' ),
		] );

		$this->add_control( 'tabs_style', [
			'label'   => __( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				''         => esc_html__( 'Default', 'minimog' ),
				'dropdown' => esc_html__( 'Dropdown', 'minimog' ),
				'01'       => sprintf( esc_html__( 'Style %s', 'minimog' ), '01' ),
				'02'       => sprintf( esc_html__( 'Style %s', 'minimog' ), '02' ),
			],
			'default' => '',
		] );

		$this->add_control( 'tabs_layout', [
			'label'   => esc_html__( 'Layout', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'horizontal',
			'options' => [
				'horizontal'         => esc_html__( 'Horizontal', 'minimog' ),
				'horizontal-reverse' => esc_html__( 'Horizontal Reverse', 'minimog' ),
			],
		] );

		$this->add_control( 'intro_text', [
			'label'       => esc_html__( 'Intro Text', 'minimog' ),
			'description' => esc_html__( 'This text display before dropdown', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => esc_html__( 'You are in', 'minimog' ),
			'label_block' => true,
			'condition'   => [
				'tabs_style' => 'dropdown',
			],
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'tab_title_heading', [
			'label' => __( 'Tab Title', 'minimog' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$repeater->add_control( 'tab_title', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Title', 'minimog' ),
			'label_block' => true,
			'dynamic'     => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'tab_title_image', [
			'label'   => esc_html__( 'Image', 'minimog' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [],
		] );

		$repeater->add_control( 'tab_content_heading', [
			'label'     => __( 'Tab Content', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$repeater->add_control( 'tab_custom_content', [
			'label'        => esc_html__( 'Custom Content', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$repeater->add_control( 'tab_content', [
			'label'     => esc_html__( 'Content', 'minimog' ),
			'type'      => Controls_Manager::WYSIWYG,
			'default'   => esc_html__( 'Tab Content', 'minimog' ),
			'condition' => [
				'tab_custom_content!' => 'yes',
			],
		] );

		$document_types = Plugin::instance()->documents->get_document_types( [
			'show_in_library' => true,
		] );

		$repeater->add_control( 'template_id', [
			'label'        => esc_html__( 'Choose Template', 'minimog' ),
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'label_block'  => true,
			'autocomplete' => [
				'object' => Module_Query_Base::QUERY_OBJECT_LIBRARY_TEMPLATE,
				'query'  => [
					'meta_query' => [
						[
							'key'     => Document::TYPE_META_KEY,
							'value'   => array_keys( $document_types ),
							'compare' => 'IN',
						],
					],
				],
			],
			'condition'    => [
				'tab_custom_content' => 'yes',
			],
		] );

		$this->add_control( 'tabs', [
			'label'       => esc_html__( 'Items', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'tab_title'   => 'Tab title #1',
					'tab_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper.',
				],
				[
					'tab_title'   => 'Tab title #2',
					'tab_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper.',
				],
				[
					'tab_title'   => 'Tab title #3',
					'tab_content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper.',
				],
			],
			'title_field' => '{{{ tab_title }}}',
			'separator'   => 'before',
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'minimog' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		$this->end_controls_section();
	}

	private function add_tabs_style_section() {
		$this->start_controls_section( 'tabs_style_section', [
			'label' => esc_html__( 'Tabs', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'tab_title_horizontal_alignment', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'label_block'          => false,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => 'center',
			'toggle'               => false,
			'selectors'            => [
				'{{WRAPPER}} .minimog-tabs__header-wrap' => 'text-align: {{VALUE}}',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'tab_title_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'default'    => [],
			'selectors'  => [
				'{{WRAPPER}} .minimog-tabs' => '--tab-content-spacing: {{SIZE}}{{UNIT}};',
			],
		] );

		// Dropdown.
		$dropdown_condition = [ 'tabs_style' => 'dropdown' ];
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
				'{{WRAPPER}} .minimog-tab-header__dropdown-section .minimog-nice-select-wrap' => 'width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => $dropdown_condition,
		] );

		// Item.
		$no_dropdown_condition = [ 'tabs_style!' => 'dropdown' ];
		$this->add_control( 'item_style_heading', [
			'label'     => esc_html__( 'Item', 'minimog' ),
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
					'max' => 200,
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
				'{{WRAPPER}} .minimog-tabs .tab-title' => 'padding: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
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

		$this->add_control( 'image_style_heading', [
			'label'     => __( 'Image', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $no_dropdown_condition,
		] );

		$this->add_control( 'tab_title_graphic_position', [
			'label'     => esc_html__( 'Graphic Position', 'minimog' ),
			'type'      => Controls_Manager::CHOOSE,
			'default'   => 'middle',
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
			'condition' => $no_dropdown_condition,
		] );

		$this->add_control( 'tab_title_graphic_align', [
			'label'     => esc_html__( 'Graphic Align', 'minimog' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_text_align(),
			'default'   => 'center',
			'condition' => [
				'tab_title_graphic_position!' => [
					'left',
					'right',
				],
			],
			'condition' => $no_dropdown_condition,
		] );

		$this->add_control( 'image_hover_type', [
			'label'     => esc_html__( 'Hover Type', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				''          => esc_html__( 'None', 'minimog' ),
				'grayscale' => esc_html__( 'Grayscale to normal', 'minimog' ),
				'opacity'   => esc_html__( 'Opacity to normal', 'minimog' ),
				'faded'     => esc_html__( 'Normal to opacity', 'minimog' ),
			],
			'default'   => 'grayscale',
			'condition' => $no_dropdown_condition,
		] );

		$this->add_responsive_control( 'image_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [],
			'selectors'  => [
				'{{WRAPPER}} .minimog-tabs' => '--tab-title-graphic-spacing: {{SIZE}}{{UNIT}};',
			],
			'condition'  => $no_dropdown_condition,
		] );

		$this->end_controls_section();
	}

	private function add_tab_content_style_section() {
		$this->start_controls_section( 'tab_content_style_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'tab_content_text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => 'center',
			'selectors'            => [
				'{{WRAPPER}} .tab-content' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'content_Width',
			[
				'label'      => esc_html__( 'Max Width', 'minimog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [],
				'selectors'  => [
					'{{WRAPPER}} .tab-content-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control( 'content_height',
			[
				'label'      => esc_html__( 'Min Height', 'minimog' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default'    => [],
				'selectors'  => [
					'{{WRAPPER}} .tab-content' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'tabs_layout' => 'horizontal-reverse',
				],
			]
		);

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'tab_content_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .minimog-tabs .tab-content',
		] );

		$this->add_control( 'tab_content_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs .tab-content' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control(
			'tab_content_padding',
			[
				'label'      => esc_html__( 'Padding', 'minimog' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'body:not(.rtl) {{WRAPPER}} .minimog-tabs .tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .minimog-tabs .tab-content'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
				],
			]
		);

		$this->add_control( 'icon_tab_heading', [
			'label'     => esc_html__( 'Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'icon_tab_space', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs__icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'icon_tab_size', [
			'label'     => esc_html__( 'Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-tabs__icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon_tab',
			'selector' => '{{WRAPPER}} .minimog-tabs__icon',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Do nothing if there is not any items.
		if ( empty( $settings['tabs'] ) || count( $settings['tabs'] ) <= 0 ) {
			return;
		}

		$wrapper_classes = [
			'minimog-tabs',
			'minimog-tabs--' . $settings['tabs_layout'],
			'minimog-tabs--nav-style-' . $settings['tabs_style'],
		];

		if ( 'dropdown' === $settings['tabs_style'] ) {
			$wrapper_classes[] = 'minimog-tabs--nav-type-dropdown';
		}

		if ( $settings['image_hover_type'] ) {
			$wrapper_classes[] = 'minimog-tabs--image-hover-' . $settings['image_hover_type'];
		}

		if ( $settings['tab_title_graphic_position'] ) {
			$wrapper_classes[] = 'minimog-tabs--title-graphic-position-' . $settings['tab_title_graphic_position'];
		}

		$this->add_render_attribute( 'wrapper', 'class', $wrapper_classes );
		$this->add_render_attribute( 'tabs_header_wrapper', [
			'class' => 'minimog-tabs__header',
			'role'  => 'tablist',
		] );
		$this->add_render_attribute( 'tabs_content_wrapper', 'class', 'minimog-tabs__content' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<div class="minimog-tabs__header-wrap">
				<div class="minimog-tabs__header-inner">
					<?php if ( 'dropdown' === $settings['tabs_style'] ) : ?>
						<?php $this->print_dropdown_section( $settings ); ?>
					<?php else: ?>
						<div <?php $this->print_attributes_string( 'tabs_header_wrapper' ); ?>>
							<?php
							foreach ( $settings['tabs'] as $index => $item ) {
								$loop_count            = $index + 1;
								$tab_id                = $item['_id'];
								$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );

								$this->add_render_attribute( $tab_title_setting_key, [
									'class'         => [ 'tab-title' ],
									'data-tab'      => $loop_count,
									'id'            => "tab-title-{$tab_id}",
									'role'          => 'tab',
									'aria-controls' => "tab-content-{$tab_id}",
									'aria-selected' => 1 === $loop_count ? 'true' : 'false',
									'tabindex'      => 1 === $loop_count ? '0' : '-1',
								] );

								if ( empty( $item['tab_title'] ) ) {
									$this->add_render_attribute( $tab_title_setting_key, 'class', 'tab-title--no-text' );
								}

								if ( 1 === $loop_count ) {
									$this->add_render_attribute( $tab_title_setting_key, 'class', 'active' );
								}
								?>
								<div <?php $this->print_attributes_string( $tab_title_setting_key ); ?>>
									<?php if ( ! empty( $item['tab_title_image'] ) && $item['tab_title_image']['url'] ) : ?>
										<span class="tab-title__graphic tab-title__image">
										<?php echo \Minimog_Image::get_elementor_attachment( [
											'settings'  => $item,
											'image_key' => 'tab_title_image',
										] ); ?>
									</span>
									<?php endif; ?>
									<?php if ( $item['tab_title'] ) : ?>
										<span class="tab-title__text"><?php echo esc_html( $item['tab_title'] ); ?></span>
									<?php endif; ?>
								</div>
								<?php
							}
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div <?php $this->print_attributes_string( 'tabs_content_wrapper' ); ?>>
				<?php
				foreach ( $settings['tabs'] as $index => $item ) {
					$loop_count = $index + 1;
					$tab_id     = $item['_id'];

					$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

					$this->add_render_attribute( $tab_content_setting_key, [
						'class'           => [ 'tab-content' ],
						'data-tab'        => $loop_count,
						'id'              => "tab-content-{$tab_id}",
						'role'            => 'tabpanel',
						'tabindex'        => '0',
						'aria-expanded'   => 1 === $loop_count ? 'true' : 'false',
					] );

					if ( 'dropdown' !== $settings['tabs_style'] ) {
						$this->add_render_attribute( $tab_content_setting_key, 'aria-labelledby', "tab-title-{$tab_id}" );
					}

					if ( 1 === $loop_count ) {
						$this->add_render_attribute( $tab_content_setting_key, 'class', 'active' );
					}

					$custom_template = '';

					if ( ! empty( $template_id = $item['template_id'] ) ) {
						$custom_template = Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
					}

					?>
					<div <?php $this->print_attributes_string( $tab_content_setting_key ); ?><?php echo 1 === $loop_count ? '' : ' hidden'; ?>>
						<div class="tab-content-wrapper">
							<?php
							if ( 'yes' === $item['tab_custom_content'] ) {
								echo '' . $custom_template;
							} else {
								echo '' . $this->parse_text_editor( $item['tab_content'] );
							}
							?>
						</div>
					</div>
					<?php
				}
				?>
			</div>

		</div>
		<?php
	}

	private function print_dropdown_section( $settings ) {
		$tabs = $settings['tabs'];
		?>
		<div class="minimog-tab-header__dropdown-section">
			<?php
			if ( ! empty( $settings['intro_text'] ) ) {
				echo '<div class="intro-text">' . wp_kses( $settings['intro_text'], 'minimog-default' ) . '</div>';
			}
			?>
			<select class="tab-select">
				<?php $loop_count = 0; ?>
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<?php $loop_count++; ?>
					<option value="<?php echo esc_attr( $loop_count ) ?>">
						<?php echo esc_html( $tab['tab_title'] ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	}
}
