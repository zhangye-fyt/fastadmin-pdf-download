<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Currency_Switcher extends Base {

	public function get_name() {
		return 'tm-currency-switcher';
	}

	public function get_title() {
		return esc_html__( 'Currency Switcher', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-sync';
	}

	public function get_keywords() {
		return [ 'currency', 'woocommerce', 'switcher' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'currency_section', [
			'label' => esc_html__( 'Currency Switcher', 'minimog' ),
		] );

		$this->add_control( 'x_direction', [
			'label'   => esc_html__( 'Directions', 'minimog' ),
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'down' => [
					'title' => esc_html__( 'Down', 'minimog' ),
					'icon'  => 'eicon-arrow-down',
				],
				'up'   => [
					'title' => esc_html__( 'Up', 'minimog' ),
					'icon'  => 'eicon-arrow-up',
				],
			],
			'default' => 'down',
			'toggle'  => false,
		] );

		$this->add_control( 'y_direction', [
			'label'      => esc_html__( 'Direction', 'minimog' ),
			'type'       => Controls_Manager::CHOOSE,
			'options'    => [
				'left'  => [
					'title' => esc_html__( 'Left', 'minimog' ),
					'icon'  => 'eicon-arrow-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'minimog' ),
					'icon'  => 'eicon-arrow-right',
				],
			],
			'default'    => 'left',
			'toggle'     => false,
			'show_label' => false,
		] );

		$this->end_controls_section();
	}

	private function add_style_section() {
		$this->start_controls_section( 'currency_style_section', [
			'label' => esc_html__( 'Currency Switcher', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		// Active Item.
		$this->add_control( 'active_item_style_hr', [
			'label' => esc_html__( 'Active Item', 'minimog' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'active_item',
			'selector' => '{{WRAPPER}} .menu-item-has-children>a',
		] );

		$this->start_controls_tabs( 'active_item_style_tabs' );

		$this->start_controls_tab( 'active_item_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'active_item_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .menu-item-has-children>a' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'active_item_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'active_item_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .menu-item-has-children>a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// List Items.
		$this->add_control( 'list_items_style_hr', [
			'label'     => esc_html__( 'List Items', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'list_items_wrapper_min_width', [
			'label'      => esc_html__( 'Wrapper Min Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 170,
					'max' => 500,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .currency-switcher-menu .sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'list_items_wrapper_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .currency-switcher-menu .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .currency-switcher-menu .sub-menu'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'list_items_wrapper_bg_color', [
			'label'     => esc_html__( 'Wrapper Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .currency-switcher-menu .sub-menu' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'sub_item',
			'selector' => '{{WRAPPER}} .sub-menu a',
		] );

		$this->start_controls_tabs( 'sub_item_style_tabs' );

		$this->start_controls_tab( 'sub_item_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'sub_item_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sub-menu a' => 'color: {{VALUE}} !important;',
			],
		] );

		$this->add_control( 'sub_item_bg_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sub-menu a' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'sub_item_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'sub_item_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sub-menu a:hover' => 'color: {{VALUE}} !important;',
			],
		] );

		$this->add_control( 'sub_item_hover_bg_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .sub-menu a:hover' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'currency-switcher-menu-wrap',
			'currency-switcher-menu-wrap--elementor',
			'currency-switcher-menu-wrap--' . $settings['x_direction'],
			'currency-switcher-menu-wrap--' . $settings['y_direction'],
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		if ( ! class_exists( 'WOOMULTI_CURRENCY_Data' ) ) {
			return;
		}

		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php $this->currency_switcher_html(); ?>
		</div>
		<?php
	}

	private function currency_switcher_html() {
		$data = \WOOMULTI_CURRENCY_Data::get_ins();

		$currencies       = $data->get_list_currencies();
		$current_currency = $data->get_current_currency();
		$links            = $data->get_links();
		$currency_name    = get_woocommerce_currencies();

		?>
		<ul class="menu currency-switcher-menu woo-multi-currency-menu">
			<li class="menu-item-has-children">
				<a href="#">
					<span class="current-currency-text"><?php echo esc_html( $current_currency ); ?></span>
				</a>
				<ul class="sub-menu">
					<?php foreach ( $links as $code => $link ): ?>
						<?php
						if ( $code === $current_currency ) {
							continue;
						}

						if ( empty( $currency_name[ $code ] ) ) {
							continue;
						}

						$value   = esc_url( $link );
						$name    = $currency_name[ $code ];
						$current = '';
						?>
						<li>
							<a href="<?php echo esc_url( $value ) ?>"
							   class="<?php echo esc_attr( $current ); ?> currency-switcher-link">
								<span class="currency-text"><?php echo esc_html( $code ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</li>
		</ul>
		<?php
	}
}
