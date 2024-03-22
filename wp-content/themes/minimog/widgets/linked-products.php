<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_WP_Widget_Linked_Products' ) ) {
	class Minimog_WP_Widget_Linked_Products extends Minimog_Widget {

		public function __construct() {
			$this->widget_id          = 'minimog-wp-widget-linked-products';
			$this->widget_cssclass    = 'minimog-wp-widget-linked-products';
			$this->widget_name        = sprintf( '%1$s %2$s', '[Minimog]', esc_html__( 'Linked Products', 'minimog' ) );
			$this->widget_description = esc_html__( 'Display linked products of current product. Used on single product page.', 'minimog' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Products', 'minimog' ),
					'label' => esc_html__( 'Title', 'minimog' ),
				),
				'number'       => array(
					'type'  => 'number',
					'step'  => 1,
					'min'   => 1,
					'max'   => '',
					'std'   => 5,
					'label' => esc_html__( 'Number of products to show', 'minimog' ),
				),
				'source'       => array(
					'type'    => 'select',
					'std'     => '',
					'label'   => esc_html__( 'Show', 'minimog' ),
					'options' => [
						'upsells' => esc_html__( 'Upsells Products', 'minimog' ),
						//'cross-sells' => esc_html__( 'Cross Sells Products', 'minimog' ),
						'related' => esc_html__( 'Related Products', 'minimog' ),
					],
				),
				'style'        => array(
					'type'    => 'select',
					'std'     => '',
					'label'   => esc_html__( 'Style', 'minimog' ),
					'options' => [
						''              => esc_html__( 'Default', 'minimog' ),
						'boxed'         => esc_html__( 'Boxed', 'minimog' ),
						'big-thumbnail' => esc_html__( 'Big Thumbnail', 'minimog' ),
					],
				),
				'show_rating'  => array(
					'type'  => 'checkbox',
					'label' => esc_html__( 'Show Rating ?', 'minimog' ),
					'std'   => 1,
				),
				'show_buttons' => array(
					'type'  => 'checkbox',
					'label' => esc_html__( 'Show Add to cart?', 'minimog' ),
					'std'   => 1,
				),
			);

			parent::__construct();
		}

		/**
		 * Output widget.
		 *
		 * @param array $args     Arguments.
		 * @param array $instance Widget instance.
		 *
		 * @see WP_Widget
		 */
		public function widget( $args, $instance ) {
			if ( ! is_product() ) {
				return;
			}

			global $product;

			if ( ! $product instanceof WC_Product ) {
				return;
			}

			$products = $this->get_products( $args, $instance );

			if ( empty( $products ) ) {
				return;
			}

			$this->widget_start( $args, $instance );

			$show_rating  = $this->get_value( $instance, 'show_rating' );
			$show_buttons = $this->get_value( $instance, 'show_buttons' );
			$style        = $this->get_value( $instance, 'style' );

			$list_classes = 'product_list_widget';

			if ( ! empty( $style ) ) {
				$list_classes .= ' style-' . $style;
			}

			echo wp_kses_post( apply_filters( 'woocommerce_before_widget_product_list', '<ul class="' . esc_attr( $list_classes ) . '">' ) );

			$template_args = array(
				'widget_id'    => isset( $args['widget_id'] ) ? $args['widget_id'] : $this->widget_id,
				'show_rating'  => $show_rating,
				'show_buttons' => $show_buttons,
				'style'        => $style,
			);

			foreach ( $products as $product ) {
				$post_object = get_post( $product->get_id() );
				setup_postdata( $GLOBALS['post'] =& $post_object );

				wc_get_template( 'custom/content-widget-product.php', $template_args );
			}
			wp_reset_postdata();

			echo wp_kses_post( apply_filters( 'woocommerce_after_widget_product_list', '</ul>' ) );

			$this->widget_end( $args, $instance );
		}

		public function get_products( $args, $instance ) {
			global $product;

			$source  = $this->get_value( $instance, 'source' );
			$limit   = $this->get_value( $instance, 'number' );
			$orderby = 'rand';
			$order   = 'desc';

			switch ( $source ) {
				case 'upsells':
					// Handle the legacy filter which controlled posts per page etc.


					// Get visible upsells then sort them at random, then limit result set.
					$upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
					$upsells = $limit > 0 ? array_slice( $upsells, 0, $limit ) : $upsells;

					return $upsells;
					break;

				case 'related':
					$related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $limit, $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );

					// Handle orderby.
					$related_products = wc_products_array_orderby( $related_products, $orderby, $order );

					return $related_products;
					break;
			}

			return false;
		}
	}
}
