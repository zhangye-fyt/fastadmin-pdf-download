<?php

namespace Minimog\Dokan;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Single_Product' ) ) {
	class Single_Product {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			/**
			 * Move this tab from Main Tabs to Linked Product Tabs.
			 */
			remove_action( 'woocommerce_product_tabs', 'dokan_set_more_from_seller_tab', 10 );
			add_action( 'minimog/product/linked_products_tabs', [ $this, 'add_more_from_seller_tab' ], 10 );

			add_action( 'wp', [ $this, 'wp_init' ], 99 );

			/**
			 * Change hook position
			 *
			 * @see \WeDevs\DokanPro\Modules\ReportAbuse\SingleProduct::add_report_button()
			 */
			minimog_remove_filters_for_anonymous_class( 'woocommerce_single_product_summary', 'WeDevs\DokanPro\Modules\ReportAbuse\SingleProduct', 'add_report_button', 100 );
		}

		public function add_more_from_seller_tab( $tabs ) {
			global $product;

			if ( function_exists( 'check_more_seller_product_tab' ) && check_more_seller_product_tab()
			     && 'in_linked_product_tabs' === \Minimog::setting( 'single_product_related_position' )
			) {
				$tabs['more_seller_product'] = [
					'title'    => __( 'More products', 'minimog' ),
					'priority' => 99,
					'callback' => [ $this, 'output_more_products_from_seller' ],
				];
			}

			return $tabs;
		}

		public function wp_init() {
			if ( '1' === \Minimog::setting( 'single_product_related_enable' )
			     && 'below_product_tabs' === \Minimog::setting( 'single_product_related_position' )
			) {
				add_action( 'woocommerce_after_single_product', [ $this, 'output_more_products_from_seller' ], 15 );
			}
		}

		/**
		 *  Show more products from current seller
		 *
		 * @see   dokan_get_more_products_from_seller()
		 *
		 * @param int     $seller_id
		 * @param int     $posts_per_page
		 *
		 * @global object $product
		 * @global object $post
		 */
		public function output_more_products_from_seller( $seller_id = 0, $posts_per_page = 6 ) {
			global $product, $post;

			if ( $seller_id === 0 || 'more_seller_product' === $seller_id ) {
				$seller_id = $post->post_author;
			}

			if ( ! is_int( $posts_per_page ) ) {
				$posts_per_page = apply_filters( 'dokan_get_more_products_per_page', 6 );
			}

			$args = [
				'posts_per_page' => $posts_per_page,
				'orderby'        => 'rand',
				'post__not_in'   => [ $post->ID ],
				'author'         => $seller_id,
			];

			$seller_products = wc_get_products( $args );

			dokan_get_template( 'custom/single-product/seller-products.php', [
				'seller_products' => $seller_products,
			] );
		}
	}

	Single_Product::instance()->initialize();
}
