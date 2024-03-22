<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_WP_Widget_Product_Badge' ) ) {
	class Minimog_WP_Widget_Product_Badge extends Minimog_WC_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'minimog-wp-widget-product-badge';
			$this->widget_cssclass    = 'minimog-wp-widget-product-badge';
			$this->widget_name        = sprintf( '%1$s %2$s', '[Minimog]', esc_html__( 'Product Badge', 'minimog' ) );
			$this->widget_description = esc_html__( 'Get List Badge', 'minimog' );
			$this->settings           = array(
				'title'              => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Title', 'minimog' ),
				),
				'show_badge_hot'     => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Featured product', 'minimog' ),
				),
				'badge_hot_text'     => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Hot items', 'minimog' ),
					'label' => esc_html__( 'Hot Badge Text', 'minimog' ),
				),
				'show_badge_on_sale' => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'On sale product', 'minimog' ),
				),
				'badge_sale_text'    => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Sale', 'minimog' ),
					'label' => esc_html__( 'On sale Badge Text', 'minimog' ),
				),
				'show_badge_new'     => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'New product', 'minimog' ),
				),
				'badge_new_text'     => array(
					'type'  => 'text',
					'std'   => esc_html__( 'New Arrivals', 'minimog' ),
					'label' => esc_html__( 'New Badge Text', 'minimog' ),
				),
			);

			parent::__construct();
		}

		public function widget( $args, $instance ) {
			if ( ! is_singular( 'product' ) ) {
				return;
			}

			$badge_hot       = $this->get_value( $instance, 'show_badge_hot' );
			$badge_on_sale   = $this->get_value( $instance, 'show_badge_on_sale' );
			$badge_new       = $this->get_value( $instance, 'show_badge_new' );
			$badge_hot_text  = $this->get_value( $instance, 'badge_hot_text' );
			$badge_new_text  = $this->get_value( $instance, 'badge_new_text' );
			$badge_sale_text = $this->get_value( $instance, 'badge_sale_text' );

			/**
			 * @var WP_Post    $post
			 * @var WC_Product $product
			 */
			global $post;
			$product = wc_get_product( $post->ID );

			if ( ! $product ) {
				return;
			}

			$is_featured = $is_sale = $is_new = false;

			if ( $product->is_featured() ) {
				$is_featured = true;
			}

			if ( $product->is_on_sale() ) {
				$is_sale = true;
			}

			$new_days = Minimog::setting( 'shop_badge_new' );

			if ( '0' !== $new_days ) {
				$postdate        = get_the_time( 'Y-m-d', $product->get_id() );
				$post_date_stamp = strtotime( $postdate );

				if ( ( time() - ( 60 * 60 * 24 * $new_days ) ) < $post_date_stamp ) {
					$is_new = true;
				}
			}

			$this->widget_start( $args, $instance );
			?>
			<?php if ( $badge_hot || $badge_new || $badge_on_sale ) : ?>
				<ul class="minimog-wp-widget-product-badge-list">
					<?php if ( $badge_new ): ?>
						<li>
							<input type="checkbox" disabled <?php checked( $is_new ); ?>/>
							<span><?php echo esc_html( $badge_new_text ); ?></span>
						</li>
					<?php endif; ?>

					<?php if ( $badge_on_sale ): ?>
						<li>
							<input type="checkbox" disabled <?php checked( $is_sale ); ?>/>
							<span><?php echo esc_html( $badge_sale_text ); ?></span>
						</li>
					<?php endif; ?>

					<?php if ( $badge_hot ): ?>
						<li>
							<input type="checkbox" disabled <?php checked( $is_featured ); ?>/>
							<span><?php echo esc_html( $badge_hot_text ); ?></span>
						</li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
			<?php
			$this->widget_end( $args, $instance );
		}
	}
}
