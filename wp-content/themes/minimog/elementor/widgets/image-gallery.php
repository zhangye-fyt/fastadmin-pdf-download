<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;

defined( 'ABSPATH' ) || exit;

class Widget_Image_Gallery extends Base {

	private $loop_count = 0;

	public function get_name() {
		return 'tm-image-gallery';
	}

	public function get_title() {
		return esc_html__( 'Image Gallery', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-gallery-grid';
	}

	public function get_keywords() {
		return [ 'image', 'photo', 'visual', 'gallery' ];
	}

	public function get_script_depends() {
		return [ 'lightgallery', 'minimog-group-widget-grid' ];
	}

	public function get_style_depends() {
		return [ 'lightgallery' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_layout_section();

		$this->add_grid_section();

		$this->add_image_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'gallery', [
			'label'      => esc_html__( 'Add Images', 'minimog' ),
			'type'       => Controls_Manager::GALLERY,
			'show_label' => false,
			'dynamic'    => [
				'active' => true,
			],
		] );

		$this->end_controls_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .minimog-image' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_control( 'layout', [
			'label'   => esc_html__( 'Layout', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'grid'    => esc_html__( 'Grid', 'minimog' ),
				'masonry' => esc_html__( 'Masonry', 'minimog' ),
				'metro'   => esc_html__( 'Metro', 'minimog' ),
			],
			'default' => 'grid',
		] );

		$this->add_responsive_control( 'zigzag_height', [
			'label'     => esc_html__( 'Zigzag Height', 'minimog' ),
			'type'      => Controls_Manager::NUMBER,
			'step'      => 1,
			'condition' => [
				'layout' => 'masonry',
			],
		] );

		$this->add_control( 'zigzag_reversed', [
			'label'     => esc_html__( 'Zigzag Reversed?', 'minimog' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [
				'layout' => 'masonry',
			],
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
		] );

		$this->add_control( 'lightbox_enable', [
			'label'        => esc_html__( 'Lightbox', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'metro_image_size_width', [
			'label'     => esc_html__( 'Image Size', 'minimog' ),
			'type'      => Controls_Manager::NUMBER,
			'step'      => 1,
			'default'   => 480,
			'condition' => [
				'layout' => 'metro',
			],
		] );

		$this->add_control( 'metro_image_ratio', [
			'label'     => esc_html__( 'Image Ratio', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'max'  => 2,
					'min'  => 0.10,
					'step' => 0.01,
				],
			],
			'default'   => [
				'size' => 1,
			],
			'condition' => [
				'layout' => 'metro',
			],
		] );

		$this->add_control( 'thumbnail_default_size', [
			'label'        => esc_html__( 'Use Default Thumbnail Size', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
			'separator'    => 'before',
			'condition'    => [
				'layout!' => 'metro',
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'thumbnail',
			'default'   => 'full',
			'condition' => [
				'layout!'                 => 'metro',
				'thumbnail_default_size!' => '1',
			],
		] );

		$this->end_controls_section();
	}

	private function add_grid_section() {
		$this->start_controls_section( 'grid_options_section', [
			'label' => esc_html__( 'Grid Options', 'minimog' ),
		] );

		$this->add_responsive_control( 'grid_columns', [
			'label'          => esc_html__( 'Columns', 'minimog' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 12,
			'step'           => 1,
			'default'        => 3,
			'tablet_default' => 2,
			'mobile_default' => 1,
		] );

		$this->add_responsive_control( 'grid_gutter', [
			'label'   => esc_html__( 'Gutter', 'minimog' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 200,
			'step'    => 1,
			'default' => 30,
		] );

		$metro_layout_repeater = new Repeater();

		$metro_layout_repeater->add_control( 'size', [
			'label'   => esc_html__( 'Item Size', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '1:1',
			'options' => Widget_Utils::get_grid_metro_size(),
		] );

		$this->add_control( 'grid_metro_layout', [
			'label'       => esc_html__( 'Metro Layout', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $metro_layout_repeater->get_controls(),
			'default'     => [
				[ 'size' => '2:2' ],
				[ 'size' => '1:1' ],
				[ 'size' => '1:1' ],
				[ 'size' => '1:1' ],
				[ 'size' => '2:2' ],
				[ 'size' => '1:1' ],
			],
			'title_field' => '{{{ size }}}',
			'condition'   => [
				'layout' => 'metro',
			],
		] );

		$this->end_controls_section();
	}

	private function add_image_style_section() {
		$this->start_controls_section( 'images_style_section', [
			'label' => esc_html__( 'Images', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->start_controls_tabs( 'images_effects' );

		$this->start_controls_tab( 'images_effects_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters',
			'selector' => '{{WRAPPER}} .image',
		] );

		$this->add_control( 'images_opacity', [
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
				'{{WRAPPER}} .image' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'images_effects_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters_hover',
			'selector' => '{{WRAPPER}} .grid-item:hover .image',
		] );

		$this->add_control( 'images_opacity_hover', [
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
				'{{WRAPPER}} .grid-item:hover .image' => 'opacity: {{SIZE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['gallery'] ) ) {
			return;
		}

		$this->add_render_attribute( 'grid-wrapper', 'class', 'minimog-grid-wrapper' );

		$this->add_render_attribute( 'content-wrapper', 'class', 'minimog-grid lazy-grid' );

		if ( 'metro' === $settings['layout'] ) {
			$this->add_render_attribute( 'grid-wrapper', 'class', 'minimog-grid-metro' );
		}

		$grid_options = $this->get_grid_options( $settings );

		$this->add_render_attribute( 'grid-wrapper', 'data-grid', wp_json_encode( $grid_options ) );

		if ( ! empty( $settings['lightbox_enable'] ) ) {
			$this->add_render_attribute( 'grid-wrapper', 'class', 'minimog-light-gallery' );
		}
		?>
		<div <?php $this->print_attributes_string( 'grid-wrapper' ); ?>>
			<div <?php $this->print_attributes_string( 'content-wrapper' ); ?>>
				<div class="grid-sizer"></div>

				<?php if ( 'metro' === $settings['layout'] ) : ?>
					<?php $this->print_metro_grid( $settings ); ?>
				<?php else: ?>
					<?php $this->print_grid( $settings ); ?>
				<?php endif; ?>

			</div>
		</div>
		<?php
	}

	protected function get_grid_options( array $settings ) {
		$grid_options = [];

		$grid_options['type'] = isset( $settings['layout'] ) ? $settings['layout'] : 'grid';

		if ( isset( $settings['metro_image_ratio'], $settings['metro_image_ratio']['size'] ) ) {
			$grid_options['ratio'] = $settings['metro_image_ratio']['size'];
		}

		if ( ! empty( $settings['zigzag_reversed'] ) && 'yes' === $settings['zigzag_reversed'] ) {
			$grid_options['zigzagReversed'] = 1;
		}

		$columns_settings       = $this->parse_responsive_settings( $settings, 'grid_columns', 'columns' );
		$gutter_settings        = $this->parse_responsive_settings( $settings, 'grid_gutter', 'gutter' );
		$zigzag_height_settings = $this->parse_responsive_settings( $settings, 'zigzag_height', 'zigzagHeight' );

		$grid_options += $columns_settings + $gutter_settings + $zigzag_height_settings;

		return $grid_options;
	}

	private function print_grid( array $settings ) {
		$image_size = \Minimog_Image::elementor_parse_image_size( $settings, '600x600' );

		foreach ( $settings['gallery'] as $image ) {
			$this->loop_count++;
			?>
			<div class="grid-item">
				<?php $this->print_image( $image, $image_size ); ?>
			</div>
			<?php
		}
	}

	private function print_metro_grid( array $settings ) {
		$metro_layout = [];

		if ( isset( $settings['grid_metro_layout'] ) ) {
			foreach ( $settings['grid_metro_layout'] as $key => $value ) {
				$metro_layout[] = $value['size'];
			}
		}

		if ( count( $metro_layout ) < 1 ) {
			return;
		}

		$metro_layout_count = count( $metro_layout );
		$metro_item_count   = 0;
		$count              = count( $settings['gallery'] );

		foreach ( $settings['gallery'] as $image ) {
			$this->loop_count++;
			$item_key = 'image_key_' . $this->loop_count;

			$size   = $metro_layout[ $metro_item_count ];
			$ratio  = explode( ':', $size );
			$ratioW = $ratio[0];
			$ratioH = $ratio[1];

			$this->add_render_attribute( $item_key, [
				'class'       => 'grid-item grid-item-height',
				'data-width'  => $ratioW,
				'data-height' => $ratioH,
			] );

			$_image_width  = $settings['metro_image_size_width'];
			$_image_height = $_image_width * $settings['metro_image_ratio']['size'];
			if ( in_array( $ratioW, array( '2' ) ) ) {
				$_image_width *= 2;
			}

			if ( in_array( $ratioH, array( '1.3', '2' ) ) ) {
				$_image_height *= 2;
			}

			$_image_size = "{$_image_width}x{$_image_height}";
			?>
			<div <?php $this->print_render_attribute_string( $item_key ); ?>>
				<?php $this->print_image( $image, $_image_size ); ?>
			</div>
			<?php
			$metro_item_count++;
			if ( $metro_item_count == $count || $metro_layout_count == $metro_item_count ) {
				$metro_item_count = 0;
			}
			?>
			<?php
		}
	}

	private function print_image( $image, $image_size ) {
		$settings = $this->get_settings_for_display();
		$box_key  = 'box_' . $this->loop_count;
		$box_tag  = 'div';
		$this->add_render_attribute( $box_key, 'class', 'minimog-box' );

		if ( ! empty( $settings['lightbox_enable'] ) ) {
			$box_tag = 'a';

			$image_full = \Minimog_Image::get_attachment_info( $image['id'] );

			$sub_html = '';
			if ( $image_full['title'] !== '' ) {
				$sub_html .= "<h4>{$image_full['title']}</h4>";
			}

			if ( $image_full['caption'] !== '' ) {
				$sub_html .= "<p>{$image_full['caption']}</p>";
			}

			$this->add_render_attribute( $box_key, [
				'class'         => 'zoom',
				'href'          => $image_full['src'],
				'data-sub-html' => $sub_html,
			] );
		}

		?>
		<?php printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( $box_key ) ); ?>
		<div class="minimog-image image">
			<?php \Minimog_Image::the_attachment_by_id( array(
				'id'   => $image['id'],
				'size' => $image_size,
			) ); ?>
		</div>
		<?php if ( ! empty( $settings['lightbox_enable'] ) ) { ?>
			<div class="minimog-overlay">
				<div><span class="fal fa-plus"></span></div>
			</div>
		<?php } ?>
		<?php printf( '</%1$s>', $box_tag ); ?>
		<?php
	}
}
