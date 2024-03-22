<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Carousel_Nav_Buttons extends Base {

	public function get_name() {
		return 'tm-carousel-nav-buttons';
	}

	public function get_title() {
		return esc_html__( 'Carousel Nav Buttons', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-posts-carousel';
	}

	public function get_keywords() {
		return [ 'carousel', 'slider', 'arrows', 'arrow' ];
	}


	protected function register_controls() {
		$this->add_layout_section();
		$this->add_style_button_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => '01',
				'02' => '02',
				'03' => '03',
				'04' => '04',
				'05' => '05',
			],
			'default' => '01',
		] );

		$this->add_responsive_control( 'horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment_full(),
			'default'              => 'left',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'    => 'flex-start',
				'right'   => 'flex-end',
				'stretch' => 'space-between',
			],
			'selectors'            => [
				'{{WRAPPER}} .button-wrap' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_control( 'button_id', [
			'label'       => esc_html__( 'Button ID', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'title'       => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'minimog' ),
			'label_block' => false,
			'description' => wp_kses( __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'minimog' ), 'minimog-default' ),
			'separator'   => 'before',
		] );

		$this->add_control( 'icon', [
			'label'       => esc_html__( 'Right Arrow', 'minimog' ),
			'type'        => Controls_Manager::ICONS,
			'description' => esc_html__( 'The Left Arrow is the inverse of The Right Arrow.', 'minimog' ),
			'default'     => [],
		] );

		$this->end_controls_section();
	}

	private function add_style_button_section() {
		$this->start_controls_section( 'style_button_section', [
			'label' => esc_html__( 'Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'swiper_arrows_size', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .slider-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_height', [
			'label'      => esc_html__( 'Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .slider-btn' => 'height: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_icon_size', [
			'label'      => esc_html__( 'Icon Size', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 8,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .slider-btn' => 'font-size: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .button-wrap' => 'margin: 0 calc( -{{SIZE}}{{UNIT}} / 2)',
				'{{WRAPPER}} .slider-btn'  => 'margin: 0 calc( {{SIZE}}{{UNIT}} / 2)',
			],
			'condition' => [ 
				'style' => [ '01', '05' ] 
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_border_width', [
			'label'     => esc_html__( 'Border Width', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'selectors' => [
				'{{WRAPPER}} .slider-btn' => 'border-width: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_responsive_control( 'swiper_arrows_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'max'  => 100,
					'step' => 1,
				],
				'px' => [
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .slider-btn' => 'border-radius: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->start_controls_tabs( 'swiper_arrows_style_tabs' );

		$this->start_controls_tab( 'swiper_arrows_style_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'swiper_arrows_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slider-btn' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slider-btn' => 'background: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slider-btn' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'swiper_arrows_box_shadow',
			'selector' => '{{WRAPPER}} .slider-btn',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'swiper_arrows_style_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'swiper_arrows_hover_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slider-btn:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_hover_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slider-btn:hover' => '--minimog-swiper-nav-button-hover-background: {{VALUE}}; background: {{VALUE}};',
			],
		] );

		$this->add_control( 'swiper_arrows_hover_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .slider-btn:hover' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'swiper_arrows_hover_box_shadow',
			'selector' => '{{WRAPPER}} .slider-btn:hover',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$condition_style2     = [ 'style' => '02' ];
		$condition_bullets    = [ 'style' => [ '03', '04' ] ];
		$condition_pagination = [ 'style' => [ '02', '03', '04' ] ];

		$this->add_control( 'pagination_style_hr', [
			'label'     => esc_html__( 'Pagination', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => $condition_pagination,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'fraction_typography',
			'label'     => esc_html__( 'Fraction Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .fraction',
			'condition' => $condition_style2,
		] );

		$this->add_control( 'fraction_color', [
			'label'     => esc_html__( 'Fraction Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .fraction' => 'color: {{VALUE}};',
			],
			'condition' => $condition_style2,
		] );

		$this->add_responsive_control( 'fraction_width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 300,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .pagination-wrapper' => 'min-width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => $condition_pagination,
		] );

		$this->add_responsive_control( 'fraction_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .pagination-wrapper' => 'margin: 0 {{SIZE}}{{UNIT}}',
			],
			'condition'  => $condition_pagination,
		] );

		$this->add_control( 'bullets_primary_color', [
			'label'     => esc_html__( 'Normal Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-pagination-bullet' => 'color: {{VALUE}};',
			],
			'condition'  => $condition_bullets,
		] );

		$this->add_control( 'bullets_secondary_color', [
			'label'     => esc_html__( 'Active Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-pagination-bullet:hover'                           => 'color: {{VALUE}};',
				'{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'color: {{VALUE}};',
			],
			'condition'  => $condition_bullets,
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'slider-button', [
			'class'     => 'minimog-slider-buttons style-' . $settings['style'],
			'id'        => $settings['button_id'],
			'data-text' => esc_html__( 'Show', 'minimog' ) . '&nbsp;',
		] );

		?>
		<div <?php $this->print_render_attribute_string( 'slider-button' ); ?>>
			<div class="button-wrap">
				<div class="slider-btn slider-prev-btn">
					<?php if ( ! empty( $settings['icon']['value'] ) ) : ?>
						<?php $this->print_nav_icon( $settings, true ); ?>
					<?php else : ?>
						<?php $this->print_default_icon_left( $settings ); ?>
					<?php endif ?>
				</div>
				<div class="slider-btn slider-next-btn">
					<?php if ( ! empty( $settings['icon']['value'] ) ) : ?>
						<?php $this->print_nav_icon( $settings ); ?>
					<?php else : ?>
						<?php $this->print_default_icon_right( $settings ); ?>
					<?php endif ?>
				</div>
				<?php if ( in_array( $settings['style'], [ '02', '03', '04' ] ) ) : ?>
					<div class="pagination-wrapper"></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	private function print_default_icon_left( array $settings ) {
		?>
		<span class="far fa-arrow-left"></span>
		<?php
	}

	private function print_default_icon_right( array $settings ) {
		?>
		<span class="far fa-arrow-right"></span>
		<?php
	}

	private function print_nav_icon( array $settings, $reverse = false ) {
		$classes = [
			'minimog-icon',
			'icon',
			'minimog-solid-icon',
		];

		$key = 'icon_' . $this->get_id_number( __FUNCTION__ );

		$is_svg = isset( $settings['icon']['library'] ) && 'svg' === $settings['icon']['library'] ? true : false;

		if ( $is_svg ) {
			$classes[] = 'minimog-svg-icon';
		}

		if ( $reverse ) {
			$classes[] = 'reverse';
		}

		$this->add_render_attribute( $key, 'class', $classes );
		?>
		<div <?php $this->print_render_attribute_string( $key ); ?>>
			<?php $this->render_icon( $settings, $settings['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?>
		</div>
		<?php
	}

	private function get_id_number( $shortcode ) {
		if ( isset( $this->ids[ $shortcode ] ) ) {
			$this->ids[ $shortcode ]++;
		} else {
			$this->ids[ $shortcode ] = 1;
		}

		return $this->ids[ $shortcode ];
	}
}
