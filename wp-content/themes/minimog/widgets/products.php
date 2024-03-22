<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_WP_Widget_Linked_Products' ) ) {
	class Minimog_WP_Widget_Products extends Minimog_Widget {

		public function __construct() {
			$this->widget_id          = 'minimog-wp-widget-products';
			$this->widget_cssclass    = 'minimog-wp-widget-products';
			$this->widget_name        = sprintf( '%1$s %2$s', '[Minimog]', esc_html__( 'Products', 'minimog' ) );
			$this->widget_description = esc_html__( 'A list of your store\'s products.', 'minimog' );
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
						'latest'       => esc_html__( 'Latest Products', 'minimog' ),
						'featured'     => esc_html__( 'Featured Products', 'minimog' ),
						'on_sale'      => esc_html__( 'On-sale Products', 'minimog' ),
						'best_selling' => esc_html__( 'Best Selling Products', 'minimog' ),
						'top_rated'    => esc_html__( 'Top Rated Products', 'minimog' ),
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
			/**
			 * @var WP_Query $products
			 */
			$products = $this->get_products( $args, $instance );

			if ( $products->have_posts() ) {
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

				while ( $products->have_posts() ) : $products->the_post();
					wc_get_template( 'custom/content-widget-product.php', $template_args );
				endwhile;
				wp_reset_postdata();

				echo wp_kses_post( apply_filters( 'woocommerce_after_widget_product_list', '</ul>' ) );

				$this->widget_end( $args, $instance );
			}
		}

		public function get_products( $args, $instance ) {
			global $product;

			$source = $this->get_value( $instance, 'source' );
			$limit  = $this->get_value( $instance, 'number' );

			$query_args = [
				'post_type'      => 'product',
				'posts_per_page' => $limit,
				'post_status'    => 'publish',
			];

			switch ( $source ) {
				case 'best_selling': // Woocommerce best selling items.
					$query_args['meta_key'] = 'total_sales';
					$query_args['orderby']  = 'meta_value_num';
					$query_args['order']    = 'DESC';
					break;
				case 'featured': // Woocommerce featured items.
					$query_args['tax_query'][] = [
						'taxonomy'         => 'product_visibility',
						'terms'            => 'featured',
						'field'            => 'name',
						'operator'         => 'IN',
						'include_children' => false,
					];
					break;
				case 'on_sale': // Woocommerce best selling items.
					$query_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
					break;
				case 'top_rated': // Woocommerce top rated items.
					$query_args['meta_key'] = '_wc_average_rating';
					$query_args['orderby']  = 'meta_value_num';
					$query_args['order']    = 'DESC';
					break;
				default:
					$query_args['orderby'] = 'date';
					$query_args['order']   = 'DESC';
					break;
			}

			$products = new WP_Query( $query_args );

			return $products;
		}
	}
}
