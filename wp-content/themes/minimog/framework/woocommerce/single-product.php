<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Single_Product {
	protected static $instance = null;

	private $related_product_html       = '';
	private $upsells_product_html       = null;
	private $recent_viewed_product_html = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'body_class', [ $this, 'body_classes' ] );

		add_filter( 'minimog/page_sidebar/single_width', [ $this, 'set_sidebar_single_width' ] );
		add_filter( 'minimog/page_sidebar/single_offset', [ $this, 'set_sidebar_single_offset' ] );
		add_filter( 'minimog/page_sidebar/class', [ $this, 'add_sidebar_css_class' ] ); // Different style for sidebar.

		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 3 );

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_action( 'minimog/single_product/after_price', 'woocommerce_template_single_rating', 10 );
		add_action( 'minimog/single_product/after_price', [ $this, 'output_product_total_sales' ], 20 );

		add_action( 'woocommerce_single_product_summary', [ $this, 'output_product_live_view_visitors' ], 25 );

		// Priority 34 to compatible with PayPal plugin.
		add_action( 'woocommerce_single_product_summary', [ $this, 'add_product_popup_links' ], 34 );

		add_action( 'woocommerce_single_product_summary', [ $this, 'output_product_shipping_info' ], 35 );

		add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );

		add_action( 'wp', [ $this, 'wp_init' ], 99 );

		/**
		 * Priority 20 to run after Product Tabs plugin
		 */
		add_filter( 'woocommerce_product_tabs', [ $this, 'adjust_product_tabs' ], 20 );

		/**
		 * Remove Tab Description if the page built with Elementor Templates
		 * Because those templates have the_content tag.
		 */
		add_filter( 'woocommerce_product_tabs', [ $this, 'compatible_elementor_templates' ], 9999 );

		/**
		 * Linked Products Tabs
		 */
		add_filter( 'minimog/product/linked_products_tabs', [ $this, 'add_default_linked_products_tabs' ] );
		add_filter( 'minimog/product/linked_products_tabs', 'woocommerce_sort_product_tabs', 99 );

		/**
		 * Discussion Tabs
		 */
		add_filter( 'minimog/product/discussion_tabs', [ $this, 'add_default_discussion_tabs' ] );
		add_filter( 'minimog/product/discussion_tabs', 'woocommerce_sort_product_tabs', 99 );

		add_action( 'wp_footer', [ $this, 'product_modals' ] );
	}

	public function body_classes( $classes ) {
		if ( is_product() ) {
			$product_image_wide = \Minimog_Woo::instance()->get_single_product_images_wide();
			$classes[]          = "single-product-images-{$product_image_wide}";

			$product_images_style = \Minimog_Woo::instance()->get_single_product_images_style();
			$classes[]            = "single-product-{$product_images_style}";
			if ( 'slider' === $product_images_style ) {
				$classes[] = '1' === \Minimog_Woo::instance()->get_product_setting( 'single_product_slider_vertical' ) ? 'single-product-slider-thumbs-ver' : 'single-product-slider-thumbs-hoz';
			}

			$product_images_offset = \Minimog_Woo::instance()->get_product_setting( 'single_product_images_offset' );
			$classes[]             = "single-product-images-offset-{$product_images_offset}";

			$product_summary_offset = \Minimog_Woo::instance()->get_product_setting( 'single_product_summary_offset' );
			$classes[]              = "single-product-summary-offset-{$product_summary_offset}";

			$review_form_style = \Minimog::setting( 'single_product_review_style' );
			$classes[]         = "single-product-reviews-{$review_form_style}";

			$tabs_display = \Minimog_Woo::instance()->get_product_setting( 'single_product_tabs_style' );
			$classes[]    = "single-product-tabs-displays-as-{$tabs_display}";

			if ( '1' === \Minimog::setting( 'single_product_slider_thumbnails_mobile_disable' ) ) {
				$classes[] = "single-product-thumbnails-hide-mobile";
			}

			if ( '1' === \Minimog::setting( 'single_product_image_grid_to_slider_on_mobile' ) ) {
				$classes[] = "single-product-thumbnails-grid-to-slider-on-mobile";
			}
		}

		return $classes;
	}

	public function page_content_container_class() {
		$container = \Minimog_Woo::instance()->get_single_product_site_layout();

		return \Minimog_Site_Layout::instance()->get_container_class( $container );
	}

	public function set_sidebar_single_width( $width ) {
		if ( is_product() ) {
			$new_width = \Minimog::setting( 'product_page_single_sidebar_width' );

			if ( isset( $new_width['width'] ) && '' !== $new_width['width'] ) {
				return $new_width['width'];
			}
		}

		return $width;
	}

	public function set_sidebar_single_offset( $offset ) {
		if ( is_product() ) {
			$new_offset = \Minimog::setting( 'product_page_single_sidebar_offset' );

			if ( isset( $new_offset['width'] ) && '' !== $new_offset['width'] ) {
				/**
				 * Redux - Unit is included in dimensions type
				 * return $new_offset['width'] . 'px';
				 */
				return $new_offset['width'];
			}
		}

		return $offset;
	}

	public function add_sidebar_css_class( $classes ) {
		if ( is_product() ) {
			$sidebar_style = \Minimog::setting( 'product_page_sidebar_style' );

			if ( ! empty( $sidebar_style ) ) {
				$classes[] = 'style-' . $sidebar_style;
			}
		}

		return $classes;
	}

	public function related_products_args( $args ) {
		$number = \Minimog::setting( 'product_related_number' );

		$args['posts_per_page'] = $number;

		return $args;
	}

	public function add_entry_product_categories() {
		global $product;

		if ( '1' === \Minimog::setting( 'single_product_categories_enable' ) ) {
			echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="entry-product-categories">', '</div>' );
		}
	}

	public function wp_init() {
		if ( '1' !== \Minimog::setting( 'single_product_short_description_enable' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		}

		if ( '1' !== \Minimog::setting( 'single_product_meta_enable' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		}

		if ( 'below_summary' === \Minimog::setting( 'product_content_position' ) ) {
			add_action( 'woocommerce_after_single_product', [ $this, 'output_product_content_inline' ], 5 );
		}

		$tabs_display = \Minimog_Woo::instance()->get_product_setting( 'single_product_tabs_style' );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

		switch ( $tabs_display ) {
			case 'toggles':
				/**
				 * Moved Reviews + Questions to discussion tabs
				 * Other tabs will keep in toggles.
				 */
				add_action( 'woocommerce_single_product_summary', [
					$this,
					'output_product_data_tabs_as_toggles',
				], 100 );
				add_action( 'woocommerce_after_single_product', [ $this, 'output_discussion_tabs' ], 10 );
				break;

			case 'tabs':
				add_action( 'woocommerce_after_single_product', 'woocommerce_output_product_data_tabs', 10 );
				break;
		}

		/**
		 * Move Up-sell section below page content.
		 */
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		if ( '1' === \Minimog_Woo::instance()->get_product_setting( 'single_product_up_sells_enable' ) ) {
			$position = \Minimog_Woo::instance()->get_product_setting( 'single_product_up_sells_position' );
			switch ( $position ) {
				case 'below_product_images':
					add_action( 'minimog/single_product/product_images/after', [
						$this,
						'output_upsells_products_as_list',
					], 20 );
					break;
				case 'below_product_summary':
					add_action( 'woocommerce_single_product_summary', [
						$this,
						'output_upsells_products_as_list',
					], 120 );
					break;
				case 'below_product_details': // Above product main tabs.
					add_action( 'woocommerce_after_single_product', [
						$this,
						'output_upsells_products',
					], 1 );
					break;
				case 'below_product_tabs':
					add_action( 'woocommerce_after_single_product', [
						$this,
						'output_upsells_products',
					], 120 );
					break;
			}
		}

		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

		if ( '1' === \Minimog::setting( 'single_product_related_enable' )
		     && 'below_product_tabs' === \Minimog::setting( 'single_product_related_position' )
		) {
			add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20 );
		}

		if ( '1' === \Minimog::setting( 'single_product_recent_viewed_enable' ) ) {
			$position = \Minimog::setting( 'single_product_recent_viewed_position' );

			switch ( $position ) {
				case 'below_product_tabs':
					add_action( 'woocommerce_after_single_product', [
						$this,
						'output_recent_viewed_products',
					], 130 );
					break;
			}
		}

		add_action( 'woocommerce_after_single_product', [ $this, 'output_linked_products_tabs' ], 40 );
	}

	public function adjust_product_tabs( $tabs ) {
		global $product;
		$review_priority = 30;

		// Make better priority to easy hook other tabs.
		foreach ( $tabs as $key => $tab ) {
			$priority     = $tab['priority'];
			$new_priority = ( $priority + 1 ) * 10;

			if ( 'reviews' === $key ) {
				$review_priority = $new_priority;
			}

			$tabs[ $key ]['priority'] = $new_priority;
		}

		if ( '' !== \Minimog::setting( 'product_content_position' ) ) {
			unset( $tabs['description'] );
		}

		if ( '1' === \Minimog::setting( 'single_product_brands_tab_enable' ) && ! empty( \Minimog_Woo::instance()->get_product_brands( $product->get_id() ) ) ) {
			$tabs[] = [
				'title'    => apply_filters( 'woocommerce_product_brand_heading', __( 'About the brand', 'minimog' ) ),
				'priority' => $review_priority - 5,
				'callback' => [ $this, 'output_product_brand_tab' ],
			];
		}

		$tabs_display = \Minimog_Woo::instance()->get_product_setting( 'single_product_tabs_style' );

		if ( 'toggles' === $tabs_display ) {
			unset( $tabs['reviews'] );
		} else {
			if ( '1' === \Minimog::setting( 'single_product_question_enable' ) ) {
				$tabs['questions'] = array(
					'title'    => __( 'Questions', 'minimog' ),
					'priority' => $review_priority + 5,
					'callback' => [ $this, 'product_question' ],
				);
			}
		}

		return $tabs;
	}

	public function compatible_elementor_templates( $tabs ) {
		$page_template = get_page_template_slug();

		if ( '' !== $page_template ) {
			unset( $tabs['description'] );
		}

		return $tabs;
	}

	public function output_product_brand_tab() {
		wc_get_template( 'single-product/tabs/brand.php' );
	}

	public function output_product_data_tabs_as_toggles() {
		wc_get_template( 'single-product/tabs/toggles.php' );
	}

	public function output_discussion_tabs() {
		wc_get_template( 'single-product/tabs/discussion-tabs.php' );
	}

	public function output_linked_products_tabs() {
		wc_get_template( 'single-product/tabs/linked-products-tabs.php' );
	}

	public function add_default_discussion_tabs( $tabs = array() ) {
		global $product;

		if ( comments_open() ) {
			$tabs['reviews'] = array(
				'title'    => __( 'Reviews', 'minimog' ),
				'priority' => 10,
				'callback' => 'comments_template',
			);

			if ( '1' === \Minimog::setting( 'single_product_question_enable' ) ) {
				$tabs['questions'] = array(
					'title'    => __( 'Questions', 'minimog' ),
					'priority' => 20,
					'callback' => [ $this, 'product_question' ],
				);
			}
		}

		return $tabs;
	}

	public function add_default_linked_products_tabs( $tabs = array() ) {
		global $product;

		if ( '1' === \Minimog::setting( 'single_product_related_enable' )
		     && 'in_linked_product_tabs' === \Minimog::setting( 'single_product_related_position' ) ) {
			ob_start();
			woocommerce_output_related_products();
			$this->related_product_html = ob_get_clean();

			if ( ! empty( $this->related_product_html ) ) {
				$tabs['related'] = array(
					'title'    => apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'minimog' ) ),
					'priority' => 10,
					'callback' => [ $this, 'output_related_products' ],
				);
			}
		}

		if ( '1' === \Minimog_Woo::instance()->get_product_setting( 'single_product_up_sells_enable' )
		     && 'in_linked_product_tabs' === \Minimog_Woo::instance()->get_product_setting( 'single_product_up_sells_position' )
		) {
			$upsell_product_html = $this->get_upsell_products_html();

			if ( ! empty( $upsell_product_html ) ) {
				$tabs['upsells'] = array(
					'title'    => \Minimog_Woo::instance()->get_upsells_products_heading(),
					'priority' => 20,
					'callback' => [ $this, 'output_upsells_products' ],
				);
			}
		}

		if ( '1' === \Minimog::setting( 'single_product_recent_viewed_enable' )
		     && 'in_linked_product_tabs' === \Minimog::setting( 'single_product_recent_viewed_position' )
		) {
			$recent_viewed_html = $this->get_recent_viewed_products_html();

			if ( ! empty( $recent_viewed_html ) ) {
				$tabs['recent_viewed'] = array(
					'title'    => \Minimog_Woo::instance()->get_recent_viewed_products_heading(),
					'priority' => 30,
					'callback' => [ $this, 'output_recent_viewed_products' ],
				);
			}
		}

		return $tabs;
	}

	public function output_related_products() {
		echo '' . $this->related_product_html;
	}

	public function get_upsell_products_html() {
		if ( null === $this->upsells_product_html ) {
			ob_start();
			woocommerce_upsell_display();
			$this->upsells_product_html = ob_get_clean();
		}

		return $this->upsells_product_html;
	}

	public function output_upsells_products() {
		echo '' . $this->get_upsell_products_html();
	}

	public function get_recent_viewed_products_html() {
		if ( null === $this->recent_viewed_product_html ) {
			ob_start();
			$products = \Minimog_Woo::instance()->get_recent_viewed_products();

			wc_get_template( 'single-product/recent-viewed.php', [
				'products' => $products,
			] );
			$this->recent_viewed_product_html = ob_get_clean();
		}

		return $this->recent_viewed_product_html;
	}

	public function output_recent_viewed_products() {
		echo '' . $this->get_recent_viewed_products_html();
	}

	public function product_question() {
		wc_get_template( 'single-product-questions.php' );
	}

	public function output_product_live_view_visitors() {
		if ( '1' !== \Minimog::setting( 'single_product_live_view_visitors_enable' ) ) {
			return;
		}

		$range = \Minimog::setting( 'single_product_live_view_visitors_range' );

		if ( ! empty( $range ) ) {
			$min = $range[1];
			$max = $range[2];

			$total_visitors = mt_rand( $min, $max );

			wc_get_template( 'single-product/live-view-visitors.php', [
				'settings'       => [
					'min'      => $min,
					'max'      => $max,
					'duration' => 10000, // in ms.
					'labels'   => [
						'singular' => esc_html( _n( '%s person is viewing this right now', '%s people are viewing this right now', 1, 'minimog' ) ),
						'plural'   => esc_html( _n( '%s person is viewing this right now', '%s people are viewing this right now', 99, 'minimog' ) ),
					],
				],
				'total_visitors' => $total_visitors,
			] );
		}
	}

	public function output_product_shipping_info() {
		global $product;

		if ( $product->is_virtual() ) {
			return;
		}

		wc_get_template( 'single-product/shipping.php' );
	}

	public function output_product_content_inline() {
		$page_template = get_page_template_slug();

		if ( '' !== $page_template ) {
			return;
		}
		?>
		<div class="entry-product-section entry-product-content-section">
			<div class="<?php echo esc_attr( $this->page_content_container_class() ); ?>">
				<?php wc_get_template( 'single-product/tabs/description.php' ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Output product up sells.
	 *
	 * @param int|string $limit   (default: -1).
	 * @param int        $columns (default: 4).
	 * @param string     $orderby Supported values - rand, title, ID, date, modified, menu_order, price.
	 * @param string     $order   Sort direction.
	 */
	public function output_upsells_products_as_list( $limit = '-1', $columns = 4, $orderby = 'rand', $order = 'desc' ) {
		global $product;

		if ( ! $product ) {
			return;
		}

		// Handle the legacy filter which controlled posts per page etc.
		$args = apply_filters(
			'woocommerce_upsell_display_args',
			array(
				'posts_per_page' => $limit,
				'orderby'        => $orderby,
				'order'          => $order,
				'columns'        => $columns,
			)
		);
		wc_set_loop_prop( 'name', 'up-sells' );
		wc_set_loop_prop( 'columns', apply_filters( 'woocommerce_upsells_columns', isset( $args['columns'] ) ? $args['columns'] : $columns ) );

		$orderby = apply_filters( 'woocommerce_upsells_orderby', isset( $args['orderby'] ) ? $args['orderby'] : $orderby );
		$order   = apply_filters( 'woocommerce_upsells_order', isset( $args['order'] ) ? $args['order'] : $order );
		$limit   = apply_filters( 'woocommerce_upsells_total', isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $limit );

		// Get visible upsells then sort them at random, then limit result set.
		$upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
		$upsells = $limit > 0 ? array_slice( $upsells, 0, $limit ) : $upsells;

		wc_get_template(
			'single-product/up-sells-list.php',
			array(
				'upsells'        => $upsells,
				// Not used now, but used in previous version of up-sells.php.
				'posts_per_page' => $limit,
				'orderby'        => $orderby,
				'columns'        => $columns,
			)
		);
	}

	public function add_product_popup_links() {
		wc_get_template( 'single-product/popup-links.php' );
	}

	public function output_product_total_sales() {
		if ( '1' !== \Minimog::setting( 'single_product_total_sales_enable' ) ) {
			return;
		}

		global $product;

		$total_sales = intval( $product->get_total_sales() );
		$wrap_class  = 'entry-product-total-sales';

		$wrap_class .= $total_sales > 0 ? ' has-sale' : ' no-sale';
		?>
		<div class="<?php echo esc_attr( $wrap_class ); ?>">
			<?php echo sprintf( esc_html__( '%s sold', 'minimog' ), '<span class="count">' . $total_sales . '</span>' ); ?>
		</div>
		<?php
	}

	public function product_modals() {
		if ( ! is_product() ) {
			return;
		}

		if ( '1' === \Minimog::setting( 'single_product_sharing_enable' ) && ! empty( \Minimog::setting( 'social_sharing_item_enable' ) ) ) {
			wc_get_template( 'single-product/modals/share.php' );
		}

		if ( '1' === \Minimog::setting( 'single_product_question_enable' ) ) {
			wc_get_template( 'single-product/modals/ask-a-question.php' );
		}

		wc_get_template( 'single-product/modals/write-a-review.php' );
	}
}

Single_Product::instance()->initialize();
