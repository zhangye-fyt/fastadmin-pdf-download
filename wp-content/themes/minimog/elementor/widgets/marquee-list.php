<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Marquee_List extends Carousel_Base {

	public function get_name() {
		return 'tm-marquee-list';
	}

	public function get_title() {
		return esc_html__( 'Marquee List', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-bullet-list';
	}

	public function get_keywords() {
		return [ 'marquee', 'icon list', 'icon', 'list' ];
	}

	public function get_script_depends() {
		return [ 'minimog-group-widget-carousel' ];
	}

	protected function register_controls() {
		$this->add_list_section();

		$this->add_styling_section();

		$this->add_text_style_section();

		$this->add_image_style_section();

		$this->add_icon_style_section();
	}

	private function add_list_section() {
		$this->start_controls_section( 'marquee_list_section', [
			'label' => esc_html__( 'Marquee List', 'minimog' ),
		] );

		$this->add_responsive_control( 'swiper_items', [
			'label'          => esc_html__( 'Slides Per View', 'minimog' ),
			'type'           => Controls_Manager::HIDDEN,
			'default'        => 'auto',
			'tablet_default' => 'auto',
			'mobile_default' => 'auto',
		] );

		$this->add_responsive_control( 'swiper_gutter', [
			'label'   => esc_html__( 'Space Between', 'minimog' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 200,
			'step'    => 1,
			'default' => 50,
		] );

		$this->add_control( 'swiper_autoplay_reverse_direction', [
			'label'     => esc_html__( 'Reverse Direction?', 'minimog' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Yes', 'minimog' ),
			'label_off' => esc_html__( 'No', 'minimog' ),
		] );

		$this->add_control( 'swiper_speed', [
			'label'   => esc_html__( 'Duration', 'minimog' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 4000,
		] );

		$this->add_control( 'show_separator', [
			'label'     => esc_html__( 'Separator', 'minimog' ),
			'type'      => Controls_Manager::SWITCHER,
			'label_on'  => esc_html__( 'Show', 'minimog' ),
			'label_off' => esc_html__( 'Hide', 'minimog' ),
			'default'   => '',
			'separator' => 'before',
		] );

		$this->add_control( 'icon', [
			'label'       => esc_html__( 'Default Icon', 'minimog' ),
			'description' => esc_html__( 'Choose default icon for all items.', 'minimog' ),
			'type'        => Controls_Manager::ICONS,
			'separator'   => 'before',
		] );

		$this->add_control( 'icon_vertical_alignment', [
			'label'                => esc_html__( 'Icon Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_vertical_alignment(),
			'default'              => 'middle',
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .list-header' => 'align-items: {{VALUE}}',
			],
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'highlight', [
			'label'        => esc_html__( 'Highlight', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$repeater->add_control( 'text', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => esc_html__( 'Text', 'minimog' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'image', [
			'label'   => esc_html__( 'Choose Image', 'minimog' ),
			'type'    => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [],
		] );

		$repeater->add_control( 'icon', [
			'label' => esc_html__( 'Icon', 'minimog' ),
			'type'  => Controls_Manager::ICONS,
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
			'title_field' => '{{{ elementor.helpers.renderIcon( this, icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ text }}}',
		] );

		$this->end_controls_section();
	}

	private function add_styling_section() {
		$this->start_controls_section( 'marquee_list_styling_section', [
			'label' => esc_html__( 'Marquee List', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'width', [
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
				'{{WRAPPER}} .minimog-marquee-list' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$separator_condition = [ 'show_separator' => 'yes' ];

		$this->add_control( 'separator_heading', [
			'label'     => esc_html__( 'Separator', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => $separator_condition,
			'separator' => 'before',
		] );

		$this->add_control( 'separator_background_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-marquee-list .separator' => 'background-color: {{VALUE}}',
			],
			'condition' => $separator_condition,
		] );

		$this->add_responsive_control( 'separator_width', [
			'label'     => esc_html__( 'Width', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'default'   => [],
			'selectors' => [
				'{{WRAPPER}} .minimog-marquee-list .separator' => 'width: {{SIZE}}{{UNIT}}',
			],
			'condition' => $separator_condition,
		] );

		$this->add_responsive_control( 'separator_height', [
			'label'     => esc_html__( 'Height', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 50,
				],
			],
			'default'   => [],
			'selectors' => [
				'{{WRAPPER}} .minimog-marquee-list .separator' => 'height: {{SIZE}}{{UNIT}}',
			],
			'condition' => $separator_condition,
		] );

		$this->add_responsive_control( 'separator_spacing', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'default'   => [],
			'selectors' => [
				'body:not(.rtl) {{WRAPPER}} .minimog-marquee-list .separator' => 'margin-right: {{SIZE}}{{UNIT}}',
				'body.rtl {{WRAPPER}} .minimog-marquee-list .separator'       => 'margin-left: {{SIZE}}{{UNIT}}',
			],
			'condition' => $separator_condition,
		] );

		$this->end_controls_section();
	}

	private function add_text_style_section() {
		$this->start_controls_section( 'text_style_section', [
			'label' => esc_html__( 'Text', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'text_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .text',
		] );

		$this->start_controls_tabs( 'text_style_tabs' );

		$this->start_controls_tab( 'text_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'text',
			'selector' => '{{WRAPPER}} .text',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'text_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_text',
			'selector' => '{{WRAPPER}} .link:hover .text',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Highlight.
		$this->add_control( 'highlight_style_heading', [
			'label'     => esc_html__( 'Highlight Text', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'text_highlight',
			'selector' => '{{WRAPPER}} .item--highlight .text',
		] );

		$this->add_group_control( Group_Control_Text_Stroke::get_type(), [
			'name'     => 'text_highlight_stroke',
			'selector' => '{{WRAPPER}} .item--highlight .text',
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'text_highlight',
			'selector' => '{{WRAPPER}} .item--highlight .text',
		] );

		$this->end_controls_section();
	}

	private function add_image_style_section() {
		$this->start_controls_section( 'image_style_section', [
			'label' => esc_html__( 'Image', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-image'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
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

		$this->add_responsive_control( 'image_max_height', [
			'label'          => esc_html__( 'Max Height', 'minimog' ),
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
			'size_units'     => [ 'vh', 'px' ],
			'range'          => [
				'vh' => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .swiper-slide .minimog-image img' => 'max-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_icon_style_section() {
		$this->start_controls_section( 'icon_style_section', [
			'label' => esc_html__( 'Icon', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'icon_space', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'body:not(.rtl) {{WRAPPER}} .icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .icon'       => 'margin-left: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'     => esc_html__( 'Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 3,
					'max' => 20,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'min_width', [
			'label'          => esc_html__( 'Min Width', 'minimog' ),
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
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .icon' => 'min-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'icon_style_tabs' );

		$this->start_controls_tab( 'icon_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'icon',
			'selector' => '{{WRAPPER}} .icon',
		] );

		$this->add_control( 'icon_marker_color', [
			'label'     => esc_html__( 'Icon Marker', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}.minimog-list-style-icon-border .icon' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'style' => [
					'icon-border',
				],
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_icon',
			'selector' => '{{WRAPPER}} .link:hover .icon',
		] );

		$this->add_control( 'hover_icon_marker_color', [
			'label'     => esc_html__( 'Icon Marker', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}.minimog-list-style-icon-border .link:hover .icon' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'style' => [
					'icon-border',
				],
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function update_slider_settings( $settings, $slider_settings ) {
		$slider_settings['class']                  = [ 'tm-swiper tm-slider-widget use-elementor-breakpoints' ];
		$slider_settings['data-items-desktop']     = 'auto';
		$slider_settings['data-nav']               = '0';
		$slider_settings['data-pagination']        = '0';
		$slider_settings['data-centered']          = '1';
		$slider_settings['data-loop']              = '1';
		$slider_settings['data-simulate-touch']    = '0';
		$slider_settings['data-allow-touch-move '] = '0';

		if ( ! empty( $settings['swiper_speed'] ) ) {
			$slider_settings['data-speed'] = $settings['swiper_speed'];
		}

		$slider_settings['data-autoplay'] = '1';

		return $slider_settings;
	}

	public function before_slider() {
		$this->add_render_attribute( $this->get_slider_key(), 'class', 'minimog-marquee-list' );
	}

	protected function print_slides( array $settings ) {
		$global_icon_html = '';

		if ( ! empty ( $settings['icon']['value'] ) ) {
			$global_icon_html = '<div class="minimog-icon icon">' . $this->get_render_icon( $settings, $settings['icon'], [ 'aria-hidden' => 'true' ], false, 'icon' ) . '</div>';
		}

		foreach ( $settings['items'] as $index => $item ) {
			$item_key = 'item_' . $item['_id'];
			$this->add_render_attribute( $item_key, 'class', 'item swiper-slide' );
			$link_tag      = 'div';
			$item_link_key = 'item_link_' . $item['_id'];
			$this->add_render_attribute( $item_link_key, 'class', 'link' );
			if ( ! empty( $item['link']['url'] ) ) {
				$link_tag = 'a';
				$this->add_link_attributes( $item_link_key, $item['link'] );
			}

			if ( 'yes' === $item['highlight'] ) {
				$this->add_render_attribute( $item_key, 'class', 'item--highlight' );
			}

			?>
			<div <?php $this->print_attributes_string( $item_key ); ?>>
				<?php
				if ( 'yes' === $settings['show_separator'] ) {
					echo '<span class="separator"></span>';
				}
				?>

				<?php printf( '<%1$s %2$s>', $link_tag, $this->get_render_attribute_string( $item_link_key ) ); ?>
				<div class="list-header">
					<?php if ( ! empty( $item['icon']['value'] ) ) { ?>
						<div class="minimog-icon icon">
							<?php $this->render_icon( $settings, $item['icon'], [ 'aria-hidden' => 'true' ], false, 'icon' ); ?>
						</div>
					<?php } else { ?>
						<?php echo '' . $global_icon_html; ?>
					<?php } ?>
					<div class="text-wrap">
						<?php if ( ! empty( $item['text'] ) ) : ?>
							<div class="text">
								<?php echo wp_kses_post( $item['text'] ); ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $item['image']['url'] ) ) : ?>
							<div class="image minimog-image">
								<?php echo \Minimog_Image::get_elementor_attachment( [
									'settings' => $item,
								] ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php printf( '</%1$s>', $link_tag ); ?>
			</div>
			<?php
		}
	}
}
