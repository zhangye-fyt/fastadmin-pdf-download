<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use MABEL_SILITE\Code\Models\Shoppable_Image_VM;
use MABEL_SILITE\Code\Models\Tag;
use MABEL_SILITE\Code\Services\Woocommerce_Service;
use MABEL_SILITE\Core\Common\Managers\Settings_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Shoppable_Image extends Base {

	const POST_TYPE = 'mb_siwc_lite_image';

	public function get_script_depends() {
		return [ 'minimog-widget-shoppable-image' ];
	}

	public function get_name() {
		return 'tm-shoppable-image';
	}

	public function get_title() {
		return esc_html__( 'Shoppable Image', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-hotspot';
	}

	public function get_keywords() {
		return [ 'image', 'shoppable' ];
	}

	public function get_post_list() {
		$query_args = array(
			'post_type'      => self::POST_TYPE,
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		$results = array();

		$query = new \WP_Query( $query_args );

		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post();
				$post_id             = get_the_ID();
				$results[ $post_id ] = $post_id;
			endwhile;
			wp_reset_postdata();
		endif;

		return $results;
	}

	protected function register_controls() {
		$this->add_shoppable_image_section();

		$this->add_image_style_section();

		$this->add_tag_style_section();

		$this->add_popup_style_section();
	}

	private function add_shoppable_image_section() {
		$this->start_controls_section( 'shoppable_image_section', [
			'label' => esc_html__( 'Image', 'minimog' ),
		] );

		$this->add_control( 'shoppable_image_id', [
			'label'   => esc_html__( 'ID', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [ 0 => esc_html__( 'None', 'minimog' ) ] + $this->get_post_list(),
			'default' => 0,
		] );

		$this->end_controls_section();
	}

	private function add_image_style_section() {
		$this->start_controls_section( 'image_style_section', [
			'label' => esc_html__( 'Image Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_width', [
			'label'      => esc_html__( 'Image Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ '%', 'px' ],
			'range'      => [
				'%'  => [
					'min' => 5,
					'max' => 50,
				],
				'px' => [
					'min' => 50,
					'max' => 1000,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .mabel-siwc-img-wrapper' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_tag_style_section() {
		$this->start_controls_section( 'tag_style_section', [
			'label' => esc_html__( 'Tag Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'tag_type', [
			'label'   => esc_html__( 'Tag Type', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'numeric' => esc_html__( 'Numeric', 'minimog' ),
				'icon'    => esc_html__( 'Icon', 'minimog' ),
			],
			'default' => 'numeric',
		] );

		$this->add_control( 'tag_style', [
			'label'   => esc_html__( 'Tag Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => '01',
				'02' => '02',
			],
			'default' => '01',
		] );

		$this->add_control( 'tag_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-shoppable-image span.mb-siwc-tag' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'tag_bg_color', [
			'label'     => esc_html__( 'Background Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-shoppable-image span.mb-siwc-tag' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'tag_border_color', [
			'label'     => esc_html__( 'Border Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .minimog-shoppable-image span.mb-siwc-tag' => 'border-color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'tag_box_shadow',
			'selector' => '{{WRAPPER}} .minimog-shoppable-image span.mb-siwc-tag',
		] );

		$this->end_controls_section();
	}

	private function add_popup_style_section() {
		$this->start_controls_section( 'popup_style_section', [
			'label' => esc_html__( 'Popup Style', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'popup_width', [
			'label'      => esc_html__( 'Popup Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 100,
					'max' => 500,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-shoppable-image div.mb-siwc-popup' => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'popup_shadow',
			'selector' => '{{WRAPPER}} .minimog-shoppable-image div.mb-siwc-popup',
		] );

		$this->add_responsive_control( 'show_thumbnail', [
			'label'                => esc_html__( 'Show Thumbnail', 'minimog' ),
			'type'                 => Controls_Manager::SWITCHER,
			'label_on'             => esc_html__( 'Show', 'minimog' ),
			'label_off'            => esc_html__( 'Hide', 'minimog' ),
			'return_value'         => 'yes',
			'default'              => '',
			'selectors_dictionary' => [
				'yes' => 'display: block;',
				''    => 'display: none;',
			],
			'selectors'            => [
				'{{WRAPPER}} .minimog-shoppable-image div.mb-siwc-popup-inner .siwc-thumb-wrapper' => '{{VALUE}};',
			],
			'separator'            => 'before',
		] );

		$this->add_control( 'thumbnail_position', [
			'label'        => esc_html__( 'Thumbnail Position', 'minimog' ),
			'type'         => Controls_Manager::CHOOSE,
			'default'      => 'left',
			'options'      => [
				'left'  => [
					'title' => esc_html__( 'Left', 'minimog' ),
					'icon'  => 'eicon-h-align-left',
				],
				'top'   => [
					'title' => esc_html__( 'Top', 'minimog' ),
					'icon'  => 'eicon-v-align-top',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'minimog' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'prefix_class' => 'minimog-shoppable-thumbnail-',
			'toggle'       => false,
			'condition'    => [
				'show_thumbnail' => 'yes',
			],
		] );

		$this->add_control( 'thumbnail_width', [
			'label'      => esc_html__( 'Thumbnail Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'default'    => [
				'unit' => 'px',
			],
			'range'      => [
				'px' => [
					'min' => 30,
					'max' => 200,
				],
				'%'  => [
					'min' => 5,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-shoppable-image' => '--thumbnail-width: {{SIZE}}{{UNIT}}',
			],
			'condition'  => [
				'show_thumbnail' => 'yes',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'label'    => esc_html__( 'Product Name', 'minimog' ),
			'name'     => 'product_title_typography',
			'selector' => '{{WRAPPER}} .mb-siwc-popup .product-title',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'label'    => esc_html__( 'Product Price', 'minimog' ),
			'name'     => 'product_price_typography',
			'selector' => '{{WRAPPER}} .mb-siwc-popup .price',
		] );

		$this->start_controls_tabs( 'popup_content_color_tabs', [
			'label' => esc_html__( 'Colors', 'minimog' ),
		] );

		$this->start_controls_tab( 'popup_content_color_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'product_title_text_color', [
			'label'     => esc_html__( 'Product Name', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .mb-siwc-popup .product-title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'product_price_text_color', [
			'label'     => esc_html__( 'Product Price', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .mb-siwc-popup .price' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'popup_content_color_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'product_title_hover_text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .mb-siwc-popup .product-title a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['shoppable_image_id'] ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', [
			'minimog-shoppable-image',
			'minimog-shoppable-image--tag-style-' . $settings['tag_style'],
			'minimog-shoppable-image--tag-type-' . $settings['tag_type'],
		] );

		$this->add_render_attribute( 'wrapper', 'data-tag_type', $settings['tag_type'] );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php $this->print_shortcode_shoppable(); ?>
		</div>
		<?php
	}

	/**
	 * @see \MABEL_SILITE\Core\Common\Shortcode::render_shortcode()
	 */
	private function print_shortcode_shoppable() {
		$settings   = $this->get_settings_for_display();
		$attributes = [
			'id' => $settings['shoppable_image_id'],
		];

		$model = $this->create_shortcode_model( $attributes );

		$lazy_load_enable = $this->has_lazy_loading();

		$image_info   = \Minimog_Image::get_image_size( $model->image );
		$image_width  = $image_info[0];
		$image_height = $image_info[1];

		$image_attrs = [
			'src'    => $model->image,
			'alt'    => esc_attr__( 'Shoppable image', 'minimog' ),
			'width'  => $image_width,
			'height' => $image_height,
		];

		if ( $lazy_load_enable ) {
			$image_attrs['class']    = ' ll-image';
			$image_attrs['data-src'] = $image_attrs['src'];
			$image_attrs['src']      = \Minimog_Image::get_lazy_image_src( $image_width, $image_height );
		}

		$image_html = \Minimog_Image::build_img_tag( $image_attrs );

		if ( $lazy_load_enable ) {
			$image_html = \Minimog_Image::build_lazy_img_tag( $image_html, $image_width, $image_height );
		}
		?>
		<div
			class="mabel-siwc-img-wrapper"
			data-sw-text="<?php echo esc_attr( $model->button_text ); ?>"
			data-sw-tags='<?php echo json_encode( $model->tags, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>'
			data-sw-icon="<?php echo esc_attr( $model->icon ); ?>"
			data-sw-size="<?php echo esc_attr( $model->size ); ?>"
		>
			<?php echo '' . $image_html; ?>
		</div>
		<?php
	}

	/**
	 * @see \MABEL_SILITE\Code\Controllers\Shortcode_Controller::create_shortcode_model()
	 *
	 * @param $attributes
	 *
	 * @return Shoppable_Image_VM
	 */
	public function create_shortcode_model( $attributes ) {
		$model = new Shoppable_Image_VM();

		if ( ! isset( $attributes['id'] ) || get_post( $attributes['id'] ) == null || get_post( $attributes['id'] )->post_type !== 'mb_siwc_lite_image' ) {
			$model->show_error = true;

			return $model;
		}

		$model->button_text = Settings_Manager::get_setting( 'buttontext' );
		$model->size        = Settings_Manager::get_setting( 'tagsize' );
		$model->icon        = Settings_Manager::get_setting( 'tagicon' );
		$model->image       = json_decode( get_post_meta( $attributes['id'], 'image', true ) )->image;
		$taglist            = json_decode( get_post_meta( $attributes['id'], 'tags', true ) );
		foreach ( $taglist as $tag ) {
			$t = new Tag( round( doubleval( $tag->x ), 4 ), round( doubleval( $tag->y ), 4 ) );
			if ( $tag->id ) {
				$product = Woocommerce_Service::get_product( $tag->id );
				if ( $product === null ) {
					continue;
				}
				$t->link  = $product->get_permalink();
				$t->thumb = get_the_post_thumbnail_url( $product->get_id(), 'woocommerce_thumbnail' );
				$t->price = $this->format_price( wc_get_price_to_display( $product ) );
				$t->title = $product->get_title();
			} else {
				$t->price = $tag->price;
				$t->title = $tag->name;
				$t->link  = $tag->url;
			}

			array_push( $model->tags, $t );
		}

		return $model;
	}

	/**
	 * @see \MABEL_SILITE\Code\Controllers\Shortcode_Controller::format_price()
	 *
	 * @param $price
	 *
	 * @return string
	 */
	private function format_price( $price ) {
		if ( empty( $price ) ) {
			$price = 0;
		}

		return sprintf(
			get_woocommerce_price_format(),
			get_woocommerce_currency_symbol(),
			number_format( $price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() )
		);
	}
}
