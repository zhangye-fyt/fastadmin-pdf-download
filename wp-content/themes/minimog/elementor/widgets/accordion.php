<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Accordion extends Base {

	public function get_name() {
		return 'tm-accordion';
	}

	public function get_title() {
		return esc_html__( 'Modern Accordion/Toggle', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-accordion';
	}

	public function get_keywords() {
		return [ 'modern', 'accordion', 'tabs', 'toggle' ];
	}

	public function get_script_depends() {
		return [ 'minimog-accordion' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_styling_section();

		$this->add_title_style_section();

		$this->add_icon_style_section();

		$this->add_content_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Items', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => '01',
			'options'      => [
				'01' => '01',
				'02' => '02',
				'03' => '03',
			],
			'prefix_class' => 'minimog-accordion-style-',
		] );

		$this->add_control( 'type', [
			'label'   => esc_html__( 'Type', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'accordion' => esc_html__( 'Accordion', 'minimog' ),
				'toggle'    => esc_html__( 'Toggle', 'minimog' ),
			],
			'default' => 'accordion',
		] );

		$this->add_control( 'active_first_item', [
			'label'              => esc_html__( 'Active First Item', 'minimog' ),
			'type'               => Controls_Manager::SWITCHER,
			'return_value'       => '1',
			'frontend_available' => true,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Accordion Title', 'minimog' ),
			'label_block' => true,
			'dynamic'     => [
				'active' => true,
			],
		] );

		$repeater->add_control( 'content', [
			'label'   => esc_html__( 'Content', 'minimog' ),
			'type'    => Controls_Manager::WYSIWYG,
			'default' => esc_html__( 'Accordion Content', 'minimog' ),
		] );

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title'   => 'Accordion #1',
					'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
				],
				[
					'title'   => 'Accordion #2',
					'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
				],
				[
					'title'   => 'Accordion #3',
					'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
				],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'minimog' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		$this->add_control( 'icon', [
			'label'       => esc_html__( 'Icon', 'minimog' ),
			'type'        => Controls_Manager::ICONS,
			'separator'   => 'before',
			'default'     => [
				'value'   => 'far fa-angle-down',
				'library' => 'fa-regular',
			],
			'recommended' => [
				'fa-solid'   => [
					'plus',
					'plus-square',
					'folder-plus',
					'cart-plus',
					'calendar-plus',
					'search-plus',
					'angle-down',
				],
				'fa-regular' => [
					'plus-square',
					'plus-circle',
					'calendar-plus',
					'angle-down',
				],
			],
			'skin'        => 'inline',
			'label_block' => false,
		] );

		$this->add_control( 'active_icon', [
			'label'       => esc_html__( 'Active Icon', 'minimog' ),
			'type'        => Controls_Manager::ICONS,
			'default'     => [
				'value'   => 'far fa-angle-up',
				'library' => 'fa-regular',
			],
			'recommended' => [
				'fa-solid'   => [
					'minus',
					'minus-circle',
					'minus-square',
					'folder-minus',
					'calendar-minus',
					'search-minus',
					'angle-up',
				],
				'fa-regular' => [
					'minus-square',
					'calendar-minus',
					'angle-up',
				],
			],
			'skin'        => 'inline',
			'label_block' => false,
			'condition'   => [
				'icon[value]!' => '',
			],
		] );

		$this->add_control( 'title_html_tag', [
			'label'     => esc_html__( 'Title HTML Tag', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'h1'  => 'H1',
				'h2'  => 'H2',
				'h3'  => 'H3',
				'h4'  => 'H4',
				'h5'  => 'H5',
				'h6'  => 'H6',
				'div' => 'div',
			],
			'default'   => 'h6',
			'separator' => 'before',
		] );

		$this->end_controls_section();
	}

	private function add_styling_section() {
		$this->start_controls_section( 'style_section', [
			'label' => esc_html__( 'Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .accordion-section, {{WRAPPER}} .accordion-header, {{WRAPPER}} .accordion-content' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_title_style_section() {
		$this->start_controls_section( 'title_style_section', [
			'label' => esc_html__( 'Title', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .accordion-title',
		] );

		$this->start_controls_tabs( 'title_style_tabs' );

		$this->start_controls_tab( 'title_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'text',
			'selector' => '{{WRAPPER}} .accordion-title',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'title_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Text_Gradient::get_type(), [
			'name'     => 'hover_text',
			'selector' => '{{WRAPPER}} .accordion-section.active .accordion-title, {{WRAPPER}} .accordion-header:hover .accordion-title',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_icon_style_section() {
		$this->start_controls_section( 'icon_style_section', [
			'label'     => esc_html__( 'Icon', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'icon[value]!' => '',
			],
		] );

		$this->add_control( 'icon_align', [
			'label'   => esc_html__( 'Alignment', 'minimog' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'left'  => [
					'title' => esc_html__( 'Start', 'minimog' ),
					'icon'  => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'End', 'minimog' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default' => 'right',
			'toggle'  => false,
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'     => esc_html__( 'Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 3,
					'max' => 40,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-accordion .accordion-icons' => 'font-size: {{SIZE}}{{UNIT}};',
			],
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
				'body:not(.rtl) {{WRAPPER}} .minimog-accordion.minimog-accordion-icon-right .accordion-icons' => 'margin: 0 0 0 {{SIZE}}{{UNIT}};',
				'body:not(.rtl) {{WRAPPER}} .minimog-accordion.minimog-accordion-icon-left .accordion-icons'  => 'margin: 0 {{SIZE}}{{UNIT}} 0 0;',
				'body.rtl {{WRAPPER}} .minimog-accordion.minimog-accordion-icon-right .accordion-icons'       => 'margin: 0 {{SIZE}}{{UNIT}} 0 0;',
				'body.rtl {{WRAPPER}} .minimog-accordion.minimog-accordion-icon-left .accordion-icons'        => 'margin: 0 0 0 {{SIZE}}{{UNIT}};',
			],
		] );

		$this->start_controls_tabs( 'icon_color_tabs' );

		$this->start_controls_tab( 'icon_color_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'icon_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .opened-icon' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'icon_active_color_tab', [
			'label' => esc_html__( 'Active', 'minimog' ),
		] );

		$this->add_control( 'icon_active_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .accordion-section.active .accordion-icon, {{WRAPPER}} .accordion-section:hover .accordion-icon' => 'color: {{VALUE}};',
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
				'body:not(.rtl) {{WRAPPER}} .accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .accordion-content'       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'content_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .accordion-content',
		] );

		$this->add_control( 'content_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .accordion-content' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'content_link_color', [
			'label'     => esc_html__( 'Link Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .accordion-content a' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'content_link_hover_color', [
			'label'     => esc_html__( 'Link Hover Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .accordion-content a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Do nothing if there is not any items.
		if ( empty( $settings['items'] ) || count( $settings['items'] ) <= 0 ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'minimog-accordion' );

		if ( 'toggle' === $settings['type'] ) {
			$this->add_render_attribute( 'wrapper', 'data-multi-open', '1' );
		}

		$has_icon = ! empty( $settings['icon']['value'] ) ? true : false;

		if ( $has_icon ) {
			$this->add_render_attribute( 'wrapper', 'class', 'minimog-accordion-icon-' . $settings['icon_align'] );
		}
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php
			$loop_count = 0;
			foreach ( $settings['items'] as $key => $item ) {
				if ( empty( $item['title'] ) || empty( $item['content'] ) ) {
					continue;
				}

				$loop_count++;
				$item_key = 'item_' . $item['_id'];
				$this->add_render_attribute( $item_key, 'class', 'accordion-section' );

				if ( ! empty( $settings['active_first_item'] ) && 1 === $loop_count ) {
					$this->add_render_attribute( $item_key, 'class', 'active' );
				}
				?>
				<div <?php $this->print_attributes_string( $item_key ); ?>>
					<div class="accordion-header">
						<div class="accordion-title-wrapper">
							<?php printf( '<%1$s class="accordion-title">%2$s</%1$s>', $settings['title_html_tag'], esc_html( $item['title'] ) ); ?>
						</div>
						<?php if ( $has_icon ) : ?>
							<div class="accordion-icons">
								<span
									class="accordion-icon opened-icon"><?php Icons_Manager::render_icon( $settings['icon'] ); ?></span>
								<span
									class="accordion-icon closed-icon"><?php Icons_Manager::render_icon( $settings['active_icon'] ); ?></span>
							</div>
						<?php endif; ?>
					</div>
					<div class="accordion-content">
						<?php echo '' . $this->parse_text_editor( $item['content'] ); ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	protected function content_template() {
		// @formatter:off
		?>
		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );
		var iconActiveHTML = elementor.helpers.renderIcon( view, settings.active_icon, { 'aria-hidden': true }, 'i' , 'object' );

		view.addRenderAttribute( 'wrapper', 'class', 'minimog-accordion' );

		if ( 'toggle' === settings.type ) {
			view.addRenderAttribute( 'wrapper', 'data-multi-open', '1' );
		}

		if ( iconHTML.rendered ) {
			view.addRenderAttribute( 'wrapper', 'class', 'minimog-accordion-icon-' + settings.icon_align);
		}

		var loopCount = 0;
		#>
		<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
			<# _.each( settings.items, function( item, index ) { #>
				<#
				if ( '' === item.title || '' === item.content ) {
					return;
				}

				loopCount++;
				var itemKey = 'item_' + item._id;
				view.addRenderAttribute( itemKey, 'class', 'accordion-section' );
				#>
				<div {{{ view.getRenderAttributeString( itemKey ) }}}>

					<div class="accordion-header">
						<div class="accordion-title-wrapper">
							<{{{ settings.title_html_tag }}} class="accordion-title">
								{{{ item.title }}}
							</{{{ settings.title_html_tag }}}>
						</div>
						<# if ( iconHTML.rendered ) { #>
							<div class="accordion-icons">
								<span class="accordion-icon opened-icon">
									{{{ iconHTML.value }}}
								</span>
								<# if ( iconActiveHTML.rendered ) { #>
								<span class="accordion-icon closed-icon">
									{{{ iconActiveHTML.value }}}
								</span>
								<# } #>
							</div>
						<# } #>
					</div>
					<div class="accordion-content">{{{ item.content }}}</div>
				</div>
			<# }); #>
		</div>
		<?php
		// @formatter:off
	}
}
