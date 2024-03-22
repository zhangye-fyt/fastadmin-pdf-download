<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Instagram_Carousel extends Carousel_Base {
	public function get_name() {
		return 'tm-instagram-carousel';
	}

	public function get_title() {
		return __( 'Instagram Carousel', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-carousel';
	}

	public function get_keywords() {
		return [ 'instagram', 'gallery', 'image', 'carousel' ];
	}

	public function get_script_depends() {
		return [ 'minimog-group-widget-carousel' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_style_section();

		parent::register_controls();
	}

	protected function add_content_section() {
		$this->start_controls_section( 'instagram_content_section', [
			'label' => esc_html__( 'Instagram', 'minimog' ),
		] );

		$this->add_control( 'access_token', [
			'label'       => esc_html__( 'Access Token', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'placeholder' => esc_html__( 'Enter your Instagram Access Token', 'minimog' ),
			'description' => '<a href="' . \Minimog_Instagram::ACCESS_TOKEN_URL . '" target="_blank">' . esc_html__( 'Get my Access Token', 'minimog' ) . '</a>',
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'minimog' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'minimog' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'minimog' ),
				'move-up'  => esc_html__( 'Move Up', 'minimog' ),
			],
			'default'      => '',
			'prefix_class' => 'minimog-animation-',
			'separator'    => 'before',
		] );

		$this->add_control( 'image_shape', [
			'label'   => esc_html__( 'Image Shape', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'cropped',
			'options' => [
				'cropped'  => esc_html__( 'Square', 'minimog' ),
				'original' => esc_html__( 'Original', 'minimog' ),
			],
		] );

		$this->add_control( 'limit', [
			'label'          => esc_html__( 'Limit', 'minimog' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 100,
			'step'           => 1,
			'default'        => 5,
		] );

		$this->add_control( 'offset', [
			'label'          => esc_html__( 'Offset', 'minimog' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 100,
			'step'           => 1,
			'default'        => 0,
		] );

		$this->add_control( 'sortorder', [
			'label'   => esc_html__( 'Order', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'desc',
			'options' => [
				'asc'  => esc_html__( 'ASC', 'minimog' ),
				'desc' => esc_html__( 'DESC', 'minimog' ),
			],
		] );

		$this->add_control( 'orderby', [
			'label'   => esc_html__( 'Order By', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'id',
			'options' => [
				'id'   => esc_html__( 'ID', 'minimog' ),
				'date' => esc_html__( 'Date', 'minimog' ),
			],
		] );

		$this->end_controls_section();
	}

	protected function add_style_section() {
		$this->start_controls_section( 'instagram_style_section', [
			'label' => esc_html__( 'Instagram', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-instagram-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_style_hr', [
			'label'     => esc_html__( 'Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'      => esc_html__( 'Size', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-instagram-image .icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'icon_width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-instagram-image .icon i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'icon_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-instagram-image .icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-instagram-image .icon' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'icon_bg_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-instagram-image .icon i' => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$image_shape = $settings['image_shape'];

		$this->add_render_attribute( 'wrapper', 'class', [
			'tm-instagram-carousel',
			'minimog-instagram',
			'minimog-instagram--' . $image_shape,
		]);
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php $this->print_slider( $settings ); ?>
		</div>
		<?php
	}

	protected function print_slides( array $settings ) {
		$this->add_render_attribute( 'item_wrapper', 'class', [
			'swiper-slide',
			'minimog-box',
		] );

		$access_token = ! empty( $settings['access_token'] ) ? $settings['access_token'] : null;

		$images = \Minimog_Instagram::instance()->get_images( $settings['limit'], $access_token );

		if ( is_wp_error( $images ) ) {
			echo '' . $images->get_error_message();
		} elseif ( is_array( $images ) ) {
			$medias = array_slice( $images, $settings['offset'], $settings['limit'] );

			$orderby   = array_column( $medias, $settings['orderby'] );
			$sortorder = 'asc' === $settings['sortorder'] ? SORT_ASC : SORT_DESC;

			array_multisort( $orderby, $sortorder, $medias );

			?>
			<?php foreach ( $medias as $media ) : ?>
				<div <?php $this->print_render_attribute_string( 'item_wrapper' ); ?>>
					<?php echo \Minimog_Instagram::instance()->get_image( $media ); ?>
				</div>
			<?php endforeach; ?>
			<?php
		}
	}
}
