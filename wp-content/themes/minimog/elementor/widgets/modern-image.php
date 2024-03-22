<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;
use Elementor\Plugin;

defined( 'ABSPATH' ) || exit;

class Widget_Modern_Image extends Base {

	public function get_name() {
		return 'tm-modern-image';
	}

	public function get_title() {
		return esc_html__( 'Modern Image', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-image';
	}

	public function get_keywords() {
		return [ 'image', 'photo' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_image_style_section();

		$this->add_caption_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'section_image', [
			'label' => esc_html__( 'Image', 'minimog' ),
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''                    => esc_html__( 'None', 'minimog' ),
				'zoom-in'             => esc_html__( 'Zoom In', 'minimog' ),
				'zoom-out'            => esc_html__( 'Zoom Out', 'minimog' ),
				'move-up'             => esc_html__( 'Move Up', 'minimog' ),
				'scaling-up'          => esc_html__( 'Scale Up', 'minimog' ),
				'scaling-up-style-02' => esc_html__( 'Scale Up Bigger', 'minimog' ),
			],
			'default'      => '',
			'prefix_class' => 'minimog-animation-',
		] );

		$this->add_control( 'image', [
			'label'   => esc_html__( 'Choose Image', 'minimog' ),
			'type'    => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'image',
			// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
			'default'   => 'large',
			'separator' => 'none',
		] );

		$this->add_responsive_control( 'align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors'            => [
				'{{WRAPPER}} .tm-modern-image .minimog-box' => 'justify-content: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
		] );

		$this->add_control( 'caption_source', [
			'label'   => esc_html__( 'Caption', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'none'       => esc_html__( 'None', 'minimog' ),
				'attachment' => esc_html__( 'Attachment Caption', 'minimog' ),
				'custom'     => esc_html__( 'Custom Caption', 'minimog' ),
			],
			'default' => 'none',
		] );

		$this->add_control( 'caption', [
			'label'       => esc_html__( 'Custom Caption', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => '',
			'placeholder' => esc_html__( 'Enter your image caption', 'minimog' ),
			'condition'   => [
				'caption_source' => 'custom',
			],
			'dynamic'     => [
				'active' => true,
			],
		] );

		$this->add_control( 'link_to', [
			'label'   => esc_html__( 'Link', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none'   => esc_html__( 'None', 'minimog' ),
				'file'   => esc_html__( 'Media File', 'minimog' ),
				'custom' => esc_html__( 'Custom URL', 'minimog' ),
			],
		] );

		$this->add_control( 'link', [
			'label'       => esc_html__( 'Link', 'minimog' ),
			'type'        => Controls_Manager::URL,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://your-link.com', 'minimog' ),
			'condition'   => [
				'link_to' => 'custom',
			],
			'show_label'  => false,
		] );

		$this->add_control( 'open_lightbox', [
			'label'     => esc_html__( 'Lightbox', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'default',
			'options'   => [
				'default' => esc_html__( 'Default', 'minimog' ),
				'yes'     => esc_html__( 'Yes', 'minimog' ),
				'no'      => esc_html__( 'No', 'minimog' ),
			],
			'condition' => [
				'link_to' => 'file',
			],
		] );

		$this->add_control( 'view', [
			'label'   => esc_html__( 'View', 'minimog' ),
			'type'    => Controls_Manager::HIDDEN,
			'default' => 'traditional',
		] );

		$this->end_controls_section();
	}

	private function add_image_style_section() {
		$this->start_controls_section( 'section_style_image', [
			'label' => esc_html__( 'Image', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'width', [
			'label'          => esc_html__( 'Width', 'minimog' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => '%',
			],
			'tablet_default' => [
				'unit' => '%',
			],
			'mobile_default' => [
				'unit' => '%',
			],
			'size_units'     => [ '%', 'px', 'vw' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
				'vw' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} .minimog-image' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'max_width', [
			'label'          => esc_html__( 'Max Width', 'minimog' ),
			'type'           => Controls_Manager::SLIDER,
			'default'        => [
				'unit' => '%',
			],
			'tablet_default' => [
				'unit' => '%',
			],
			'mobile_default' => [
				'unit' => '%',
			],
			'size_units'     => [ '%', 'px', 'vw' ],
			'range'          => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
				'vw' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} img' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'height', [
			'label'          => esc_html__( 'Height', 'minimog' ),
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
			'size_units'     => [ 'px', 'vh' ],
			'range'          => [
				'px' => [
					'min' => 1,
					'max' => 500,
				],
				'vh' => [
					'min' => 1,
					'max' => 100,
				],
			],
			'selectors'      => [
				'{{WRAPPER}} img' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'object-fit', [
			'label'     => esc_html__( 'Object Fit', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				''        => esc_html__( 'Default', 'minimog' ),
				'fill'    => esc_html__( 'Fill', 'minimog' ),
				'cover'   => esc_html__( 'Cover', 'minimog' ),
				'contain' => esc_html__( 'Contain', 'minimog' ),
			],
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} img' => 'object-fit: {{VALUE}};',
			],
			'condition' => [
				'height[size]!' => '',
			],
		] );

		$this->add_control( 'separator_panel_style', [
			'type'  => Controls_Manager::DIVIDER,
			'style' => 'thick',
		] );

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'opacity', [
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
				'{{WRAPPER}} img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters',
			'selector' => '{{WRAPPER}} img',
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'opacity_hover', [
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
				'{{WRAPPER}} .minimog-box:hover img' => 'opacity: {{SIZE}};',
			],
		] );

		$this->add_group_control( Group_Control_Css_Filter::get_type(), [
			'name'     => 'css_filters_hover',
			'selector' => '{{WRAPPER}} .minimog-box:hover img',
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => 'image_border',
			'selector'  => '{{WRAPPER}} .minimog-image',
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'image_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .minimog-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'image_box_shadow',
			'exclude'  => [
				'box_shadow_position',
			],
			'selector' => '{{WRAPPER}} img',
		] );

		$this->end_controls_section();
	}

	private function add_caption_style_section() {
		$this->start_controls_section( 'section_style_caption', [
			'label'     => esc_html__( 'Caption', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'caption_source!' => 'none',
			],
		] );

		$this->add_responsive_control( 'caption_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .widget-image-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .widget-image-caption'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'caption_align', [
			'label'     => esc_html__( 'Alignment', 'minimog' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_text_align_full(),
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .widget-image-caption' => 'text-align: {{VALUE}};',
			],
		] );

		$this->add_control( 'text_color', [
			'label'     => esc_html__( 'Text Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_background_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .widget-image-caption' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_typography',
			'selector' => '{{WRAPPER}} .widget-image-caption',
		] );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), [
			'name'     => 'caption_text_shadow',
			'selector' => '{{WRAPPER}} .widget-image-caption',
		] );

		$this->add_responsive_control( 'caption_space', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => -10,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .widget-image-caption' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * Check if the current widget has caption
	 *
	 * @access private
	 * @since  2.3.0
	 *
	 * @param array $settings
	 *
	 * @return boolean
	 */
	private function has_caption( $settings ) {
		return ( ! empty( $settings['caption_source'] ) && 'none' !== $settings['caption_source'] );
	}

	/**
	 * Get the caption for current widget.
	 *
	 * @access private
	 * @since  2.3.0
	 *
	 * @param $settings
	 *
	 * @return string
	 */
	private function get_caption( $settings ) {
		$caption = '';
		if ( ! empty( $settings['caption_source'] ) ) {
			switch ( $settings['caption_source'] ) {
				case 'attachment':
					$caption = wp_get_attachment_caption( $settings['image']['id'] );
					break;
				case 'custom':
					$caption = ! Utils::is_empty( $settings['caption'] ) ? $settings['caption'] : '';
			}
		}

		return $caption;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', [
			'tm-modern-image',
		] );

		// Caption handle.
		$has_caption = $this->has_caption( $settings );

		// Link handle.
		$link = $this->get_link_url( $settings );

		$this->add_render_attribute( 'link', [
			'class' => 'minimog-image',
		] );

		$box_tag = 'div';

		if ( $link ) {
			$box_tag = 'a';

			$this->add_link_attributes( 'link', $link );

			if ( Plugin::$instance->editor->is_edit_mode() ) {
				$this->add_render_attribute( 'link', [
					'class' => 'elementor-clickable',
				] );
			}

			if ( 'custom' !== $settings['link_to'] ) {
				$this->add_lightbox_data_attributes( 'link', $settings['image']['id'], $settings['open_lightbox'] );
			}
		}
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php if ( $has_caption ) : ?>
			<figure class="wp-caption">
				<?php endif; ?>
				<div class="minimog-box">
					<?php printf( '<%1$s %2$s>', $box_tag, $this->get_render_attribute_string( 'link' ) ); ?>
					<?php echo \Minimog_Image::get_elementor_attachment( [
						'settings' => $settings,
					] ); ?>
					<?php printf( '</%1$s>', $box_tag ); ?>
				</div>
				<?php if ( $has_caption && ! empty( $this->get_caption( $settings ) ) ) : ?>
					<figcaption
						class="widget-image-caption wp-caption-text"> <?php echo wp_kses_post( $this->get_caption( $settings ) ); ?></figcaption>
				<?php endif; ?>
				<?php if ( $has_caption ) : ?>
			</figure>
		<?php endif; ?>
		</div>
		<?php
	}

	protected function content_template() {
		// @formatter:off
		?>
		<# if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			if ( ! image_url ) {
				return;
			}

			var hasCaption = function() {
				if( ! settings.caption_source || 'none' === settings.caption_source ) {
					return false;
				}
				return true;
			}

			var ensureAttachmentData = function( id ) {
				if ( 'undefined' === typeof wp.media.attachment( id ).get( 'caption' ) ) {
					wp.media.attachment( id ).fetch().then( function( data ) {
						view.render();
					} );
				}
			}

			var getAttachmentCaption = function( id ) {
				if ( ! id ) {
					return '';
				}
				ensureAttachmentData( id );
				return wp.media.attachment( id ).get( 'caption' );
			}

			var getCaption = function() {
				if ( ! hasCaption() ) {
					return '';
				}
				return 'custom' === settings.caption_source ? settings.caption : getAttachmentCaption( settings.image.id );
			}

			var link_url;

			if ( 'custom' === settings.link_to ) {
				link_url = settings.link.url;
			}

			if ( 'file' === settings.link_to ) {
				link_url = settings.image.url;
			}

			#>
			<div class="tm-modern-image">
				<#

				var boxTag = 'div';

				view.addRenderAttribute( 'box', 'class', [
					'minimog-image',
					'elementor-clickable'
				] );

				view.addRenderAttribute( 'box', 'data-elementor-open-lightbox', settings.open_lightbox );

				if ( link_url ) {
					boxTag = 'a';

					view.addRenderAttribute( 'box', 'href', link_url );
				}

				if ( hasCaption() ) { #>
					<figure class="wp-caption">
				<# } #>

				<div class="minimog-box">
					<{{{ boxTag }}} {{{ view.getRenderAttributeString( 'box' ) }}}>
						<img src="{{ image_url }}"/>
					</{{{ boxTag }}}>
				</div>

				<# if ( hasCaption() && getCaption() ) { #>
					<figcaption class="widget-image-caption wp-caption-text">{{{ getCaption() }}}</figcaption>
				<# } #>

				<# if ( hasCaption() ) { #>
					</figure>
				<# } #>
			</div>
		<# } #>
		<?php
		// @formatter:off
	}

	/**
	 * Retrieve image widget link URL.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $settings
	 *
	 * @return array|string|false An array/string containing the link URL, or false if no link.
	 */
	private function get_link_url( $settings ) {
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}

			return $settings['link'];
		}

		return [
			'url' => $settings['image']['url'],
		];
	}
}
