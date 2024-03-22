<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Widget_Feature_Product_Carousel extends Static_Carousel {

	public function get_name() {
		return 'tm-feature-product-carousel';
	}

	public function get_title() {
		return esc_html__( 'Feature Product Carousel', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-posts-carousel';
	}

	public function get_keywords() {
		return [ 'feature', 'product', 'products', 'carousel' ];
	}

	public function before_slider() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( $this->get_slider_key(), 'class', 'minimog-feature-product-carousel style-' . $settings['style'] );
	}

	protected function register_controls() {
		$this->add_layout_section();

		/*$this->add_thumbnail_style_section();

		$this->add_caption_style_section();*/

		parent::register_controls();

		$this->update_controls();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'       => esc_html__( 'Style', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => array(
				'01' => sprintf( esc_html__( 'Style %s', 'minimog' ), '01' ),
			),
			'default'     => '01',
			'render_type' => 'template',
		] );

		$this->add_control( 'hover_effect', [
			'label'        => esc_html__( 'Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'options'      => [
				''         => esc_html__( 'None', 'minimog' ),
				'zoom-in'  => esc_html__( 'Zoom In', 'minimog' ),
				'zoom-out' => esc_html__( 'Zoom Out', 'minimog' ),
			],
			'default'      => '',
			'prefix_class' => 'minimog-animation-',
		] );

		$this->add_control( 'show_category', [
			'label'        => esc_html__( 'Show Category', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'button_text', [
			'label' => esc_html__( 'Button Text', 'minimog' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$this->end_controls_section();
	}

	private function update_controls() {
		$this->update_responsive_control( 'swiper_items', [
			'default'        => '3',
			'tablet_default' => '2',
			'mobile_default' => '1',
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'default' => 30,
		] );
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control( 'product_id', [
			'label'        => esc_html__( 'Choose Product', 'minimog' ),
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'label_block'  => true,
			'multiple'     => false,
			'autocomplete' => [
				'object' => Module_Query_Base::QUERY_OBJECT_POST,
				'query'  => [
					'post_type' => 'product',
				],
			],
		] );

		$repeater->add_control( 'image', [
			'label' => esc_html__( 'Custom Thumbnail', 'minimog' ),
			'type'  => Controls_Manager::MEDIA,
		] );
	}

	protected function get_repeater_defaults() {
		return [];
	}

	protected function print_slide() {
		$settings = $this->get_settings_for_display();
		$slide    = $this->get_current_slide();

		if ( empty( $slide['product_id'] ) ) {
			return;
		}

		/**
		 * @var \WC_Product $product
		 */
		$product = wc_get_product( $slide['product_id'] );

		if ( empty( $product ) ) {
			return;
		}

		$add_to_cart_text = ! empty( $settings['button_text'] ) ? $settings['button_text'] : $product->add_to_cart_text();
		?>
		<div class="feature-product minimog-box">
			<div class="image minimog-image">
				<?php if ( $slide['image']['url'] ) : ?>
					<?php echo \Minimog_Image::get_elementor_attachment( [
						'settings'       => $slide,
						'image_size_key' => 'image_size',
						'size_settings'  => $settings,
					] ); ?>
				<?php else: ?>
					<?php
					$size = \Minimog_Image::elementor_parse_image_size( $settings, 'full', 'image_size' );
					echo \Minimog_Woo::instance()->get_product_image( $product, $size );
					?>
				<?php endif; ?>
			</div>

			<div class="feature-product--content">
				<div class="feature-product--info">
					<?php $this->print_product_category( $product ); ?>

					<div class="product-title">
						<?php echo esc_html( $product->get_name() ); ?>
					</div>
				</div>

				<?php
				$button_class = implode(
					' ',
					array_filter(
						array(
							'cart-button',
							'product_type_' . $product->get_type(),
							$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
							$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
						)
					)
				);

				echo sprintf(
					'<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s%s</a>',
					esc_url( $product->add_to_cart_url() ),
					1,
					esc_attr( $product->get_id() ),
					esc_attr( $product->get_sku() ),
					$button_class,
					esc_html( $add_to_cart_text ),
					$product->get_price_html()
				);
				?>
			</div>
		</div>
		<?php
	}

	protected function print_product_category( \WC_Product $product ) {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['show_category'] ) ) {
			return;
		}

		$cats = $product->get_category_ids();
		if ( empty( $cats ) ) {
			return;
		}

		$first_cat = $cats[0];
		$cat       = get_term_by( 'id', $first_cat, 'product_cat' );

		if ( ! $cat instanceof \WP_Term ) {
			return;
		}

		$link = get_term_link( $cat );
		?>
		<div class="product-category">
			<a href="<?php echo esc_url( $link ) ?>"><?php echo esc_html( $cat->name ); ?></a>
		</div>
		<?php
	}
}
