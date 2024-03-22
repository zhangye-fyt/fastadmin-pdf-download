<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || exit;

class Widget_Parallax_Sliders extends Carousel_Base {

	public function get_name() {
		return 'tm-parallax-sliders';
	}

	public function get_title() {
		return esc_html__( 'Parallax Sliders', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-slider-push';
	}

	public function get_keywords() {
		return [ 'image', 'photo', 'visual', 'carousel', 'slider' ];
	}

	protected function register_controls() {
		$this->add_slides_section();

		$this->add_primary_slider_style_section();

		$this->add_secondary_slider_style_section();

		$this->update_controls();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'type'                 => Controls_Manager::HIDDEN,
			'default'              => 1,
			'tablet_default'       => 1,
			'tablet_extra_default' => 1,
			'mobile_extra_default' => 1,
			'mobile_default'       => 1,
		] );

		$this->update_responsive_control( 'swiper_items_per_group', [
			'type'    => Controls_Manager::HIDDEN,
			'default' => 1,
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 0,
		] );

		$this->update_responsive_control( 'swiper_effect', [
			'type' => Controls_Manager::HIDDEN,
		] );

		$this->remove_control( 'swiper_centered' );
		$this->remove_control( 'swiper_centered_highlight' );
		$this->remove_control( 'swiper_free_mode' );
	}

	protected function add_slides_section() {
		$this->start_controls_section( 'slides_section', [
			'label' => esc_html__( 'Slides', 'minimog' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'primary_image', [
			'label' => esc_html__( 'Primary Image', 'minimog' ),
			'type'  => Controls_Manager::MEDIA,
		] );

		$repeater->add_control( 'secondary_image', [
			'label' => esc_html__( 'Secondary Image', 'minimog' ),
			'type'  => Controls_Manager::MEDIA,
		] );

		$placeholder_image_src = Utils::get_placeholder_image_src();

		$this->add_control( 'slides', [
			'label'     => esc_html__( 'Slides', 'minimog' ),
			'type'      => Controls_Manager::REPEATER,
			'fields'    => $repeater->get_controls(),
			'default'   => [
				[
					'primary_image'   => [ 'url' => $placeholder_image_src ],
					'secondary_image' => [ 'url' => $placeholder_image_src ],
				],
				[
					'primary_image'   => [ 'url' => $placeholder_image_src ],
					'secondary_image' => [ 'url' => $placeholder_image_src ],
				],
			],
			'separator' => 'after',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'    => 'image_size',
			'default' => 'full',
		] );

		$this->end_controls_section();

		parent::register_controls();
	}

	protected function print_slides( array $settings ) {
		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$image_size = \Minimog_Image::elementor_parse_image_size( $settings, '578x430' );

		foreach ( $settings['slides'] as $slide ) {
			?>
			<div class="swiper-slide">
				<div class="image">
					<?php echo \Minimog_Image::get_elementor_attachment( [
						'settings'      => $slide,
						'image_key'     => 'primary_image',
						'size_settings' => $settings,
					] ); ?>
				</div>
			</div>
			<?php
		}
	}

	protected function print_secondary_slides( array $settings ) {
		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$image_size = \Minimog_Image::elementor_parse_image_size( $settings, '578x430' );

		foreach ( $settings['slides'] as $slide ) {
			?>
			<div class="swiper-slide">
				<div class="image">
					<?php echo \Minimog_Image::get_elementor_attachment( [
						'settings'      => $slide,
						'image_key'     => 'secondary_image',
						'size_settings' => $settings,
					] ); ?>
				</div>
			</div>
			<?php
		}
	}

	protected function render() {
		$this->add_render_attribute( '_wrapper', 'class', 'minimog-swiper-linked-yes' );
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( $this->get_slider_key(), 'class', 'minimog-main-swiper' );

		$secondary_slider_key      = 'secondary_slider';
		$secondary_slider_settings = $this->get_slider_settings( $settings );

		unset( $secondary_slider_settings['data-nav'] );
		unset( $secondary_slider_settings['data-pagination'] );

		$this->add_render_attribute( $secondary_slider_key, 'class', 'minimog-thumbs-swiper' );
		$this->add_render_attribute( $secondary_slider_key, $secondary_slider_settings );
		?>
		<div class="parallax-sliders">
			<?php
			$this->before_slider();

			$this->print_slider( $settings );

			$this->after_slider();
			?>
			<div <?php $this->print_attributes_string( $secondary_slider_key ); ?>>
				<div class="swiper-inner">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<?php $this->print_secondary_slides( $settings ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	private function add_primary_slider_style_section() {
		$this->start_controls_section( 'primary_slider_style_section', [
			'label' => esc_html__( 'Primary Slider', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'primary_slider_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-main-swiper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-main-swiper'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_secondary_slider_style_section() {
		$this->start_controls_section( 'secondary_slider_style_section', [
			'label' => esc_html__( 'Secondary Slider', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'secondary_slider_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-thumbs-swiper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-thumbs-swiper'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}
}
