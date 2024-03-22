<?php
defined( 'ABSPATH' ) || exit;

/**
 * Custom functions, filters, actions for WooCommerce.
 */
if ( ! class_exists( 'Minimog_Woo' ) ) {
	class Minimog_Woo {

		protected static $instance = null;

		public static $product_loop_image_size = [];

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function define_constants() {
			define( 'MINIMOG_WOO_DIR', get_template_directory() . DIRECTORY_SEPARATOR . 'woocommerce' );
			define( 'MINIMOG_WOO_CORE_DIR', MINIMOG_FRAMEWORK_DIR . DIRECTORY_SEPARATOR . 'woocommerce' );
			define( 'MINIMOG_WOO_ASSETS_DIR', MINIMOG_THEME_ASSETS_DIR . DIRECTORY_SEPARATOR . 'woocommerce' );
		}

		public function initialize() {
			$this->define_constants();

			// Do nothing if Woo plugin not activated.
			if ( ! $this->is_activated() ) {
				return;
			}
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/back-compatible.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/admin-settings.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/shipping.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/meta-box.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/ajax-handlers.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/sidebar.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/shop-layout-switcher.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/quick-view.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/fly-cart.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/avatar-upload.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-query.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-thumb-media.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-quantity-select.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-attribute-swatches.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-variation.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-stock.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-bundle.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-bought-together.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-comment.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-comment-attachment.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-question.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/trust-badge.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/notification.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/sales-countdown-timer.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/advanced-discounts.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/shoppable-images.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/customer.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/comments.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/archive-product.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/single-product.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/my-account.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/cart.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/checkout.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/wishlist.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/compare.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/free-shipping-label.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/coupon.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/auction.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/size-guide/settings.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/size-guide/frontend.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/enqueue.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/3rd-party-compatible/woocommerce-extra-product-options.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/3rd-party-compatible/woongkir.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/3rd-party-compatible/lumise.php' );

			if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
				minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/3rd-party-compatible/elementor-pro.php' );
			}

			if ( class_exists( 'WOOMULTI_CURRENCY' ) ) {
				minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/3rd-party-compatible/curcy-multi-currency.php' );
			}

			if ( class_exists( 'WCML_Multi_Currency' ) ) {
				minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/3rd-party-compatible/wpml-multi-currency.php' );
			}

			if ( class_exists( '\Aelia\WC\CurrencySwitcher\WC_Aelia_CurrencySwitcher' ) ) {
				minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/3rd-party-compatible/aelia-multi-currency.php' );
			}

			if ( class_exists( '\WooCommerce_Germanized' ) ) {
				minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/3rd-party-compatible/woocommerce-germanized.php' );
			}

			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/3rd-party-compatible/wpc-tabs.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/gift-carts.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/buy-now.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/product-category.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/variation-gallery/backend.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/variation-gallery/frontend.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/admin/product-category-banner.php' );
			minimog_require_file_once( MINIMOG_WOO_CORE_DIR . '/cache-controller.php' );

			/**
			 * Price html edited.
			 */
			add_action( 'init', [ $this, 'trim_zeroes_from_price' ] );
			add_filter( 'woocommerce_get_price_html', [ $this, 'add_wrap_for_price_html' ], 999, 2 );
			add_filter( 'woocommerce_format_price_range', [ $this, 'format_price_range' ], 999, 3 );

			add_filter( 'woocommerce_product_get_image_id', [ $this, 'fix_product_image_id_type' ], 10, 2 );

			// Move nav count to link.
			add_filter( 'woocommerce_layered_nav_term_html', [ $this, 'move_layered_nav_count_inside_link' ], 10, 4 );

			/**
			 * Begin ajax requests.
			 */
			// Load more for widget Product.
			add_action( 'wp_ajax_product_infinite_load', [ $this, 'product_infinite_load' ] );
			add_action( 'wp_ajax_nopriv_product_infinite_load', [ $this, 'product_infinite_load' ] );
			/**
			 * End ajax requests.
			 */

			add_action( 'after_setup_theme', [ $this, 'modify_theme_support' ], 10 );

			add_filter( 'wp_get_attachment_metadata', [ $this, 'normalize_attachment_metadata' ], 10, 2 );

			// Disable update.
			add_filter( 'wpc_check_update', '__return_false' );

			add_filter( 'body_class', [ $this, 'body_classes' ] );
		}

		public function body_classes( $classes ) {
			if ( '1' === \Minimog::setting( 'hide_icon_badges_on_empty' ) ) {
				$classes[] = 'hide-icon-badge-on-empty';
			}

			return $classes;
		}

		/**
		 * Fix WC raising php warning
		 * Some how $data => [
		 *     0 => ''
		 * ]
		 *
		 * @param $data
		 * @param $attachment_id
		 *
		 * @return array
		 * @see WC_Regenerate_Images::get_full_size_image_dimensions()
		 *
		 */
		public function normalize_attachment_metadata( $data, $attachment_id ) {
			if ( is_array( $data ) && ( isset( $data[0] ) && '' === $data[0] ) ) {
				$data = array();
			}

			return $data;
		}

		/**
		 * Check woocommerce plugin active
		 *
		 * @return boolean true if plugin activated
		 */
		public function is_activated() {
			return class_exists( 'WooCommerce' );
		}

		public function get_product_setting( $name = '', $default = '' ) {
			if ( empty( $name ) ) {
				return $default;
			}

			$value = Minimog_Helper::get_post_meta( $name, '' );

			if ( isset( $value ) && '' === $value ) {
				$value = Minimog::setting( $name );
			}

			return $value;
		}

		public function get_the_product_categories() {
			global $product;

			$terms = get_the_terms( $product->get_id(), 'product_cat' );

			return is_wp_error( $terms ) || empty( $terms ) ? false : $terms;
		}

		public function get_the_product_brands() {
			global $product;

			return $this->get_product_brands( $product->get_id() );
		}

		public function get_product_brands( $product_id ) {
			$terms = get_the_terms( $product_id, 'product_brand' );

			return is_wp_error( $terms ) || empty( $terms ) ? false : $terms;
		}

		public function get_product_categories_dropdown_options( $args = array() ) {
			$defaults = [
				'taxonomy'   => 'product_cat',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => false, // Don't change this param because dropdown has option to do that.
			];

			$args = wp_parse_args( $args, $defaults );

			$categories = get_terms( $args );
			$options    = array();

			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
				foreach ( $categories as $key => $category ) {
					$ancestors = get_ancestors( $category->term_id, 'product_cat' );
					$prefix    = str_repeat( '-', count( $ancestors ) );

					$options[ $category->term_id ] = $prefix . $category->name;
				}
			}

			return $options;
		}

		/**
		 * Format a price range for display.
		 * Add wrap tag around price range separator
		 *
		 * @param string $from Price from.
		 * @param string $to   Price to.
		 *
		 * @return string
		 */
		public function format_price_range( $price, $from, $to ) {
			return sprintf( _x( '%1$s <span class="wc-price-separator">&ndash;</span> %2$s', 'Price range: from-to', 'minimog' ), is_numeric( $from ) ? wc_price( $from ) : $from, is_numeric( $to ) ? wc_price( $to ) : $to );
		}

		public function trim_zeroes_from_price() {
			if ( 'yes' === get_option( 'woocommerce_price_decimal_no_zeroes' ) ) {
				add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
			}
		}

		/**
		 * Add div wrap around price html
		 *
		 * @param            $price
		 * @param WC_Product $product
		 *
		 * @return string
		 *
		 * @since 2.5.0
		 */
		public function add_wrap_for_price_html( $price, $product ) {
			return '<div class="price">' . $price . '</div>';
		}

		/**
		 * @param $image_id
		 * @param $product
		 *
		 * @return int
		 */
		public function fix_product_image_id_type( $image_id, $product ) {
			return intval( $image_id );
		}

		/**
		 * Modify image width theme support.
		 */
		public function modify_theme_support() {
			add_theme_support( 'woocommerce' );
		}

		/**
		 * Returns true if on a page which uses WooCommerce templates exclude single product (cart and checkout are standard pages with shortcodes and which are also included)
		 *
		 * @access public
		 * @return bool
		 */
		public function is_woocommerce_page_without_product() {
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				return true;
			}

			if ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) {
				return true;
			}

			if ( is_post_type_archive( 'product' ) ) {
				return true;
			}

			$the_id = get_the_ID();

			if ( $the_id !== false ) {
				$woocommerce_keys = array(
					'woocommerce_shop_page_id',
					'woocommerce_terms_page_id',
					'woocommerce_cart_page_id',
					'woocommerce_checkout_page_id',
					'woocommerce_pay_page_id',
					'woocommerce_thanks_page_id',
					'woocommerce_myaccount_page_id',
					'woocommerce_edit_address_page_id',
					'woocommerce_view_order_page_id',
					'woocommerce_change_password_page_id',
					'woocommerce_logout_page_id',
					'woocommerce_lost_password_page_id',
				);

				foreach ( $woocommerce_keys as $wc_page_id ) {
					if ( $the_id == get_option( $wc_page_id, 0 ) ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
		 *
		 * @access public
		 * @return bool
		 */
		public function is_woocommerce_page() {
			if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
				return true;
			}

			$woocommerce_keys = array(
				"woocommerce_shop_page_id",
				"woocommerce_terms_page_id",
				"woocommerce_cart_page_id",
				"woocommerce_checkout_page_id",
				"woocommerce_pay_page_id",
				"woocommerce_thanks_page_id",
				"woocommerce_myaccount_page_id",
				"woocommerce_edit_address_page_id",
				"woocommerce_view_order_page_id",
				"woocommerce_change_password_page_id",
				"woocommerce_logout_page_id",
				"woocommerce_lost_password_page_id",
			);

			foreach ( $woocommerce_keys as $wc_page_id ) {
				if ( get_the_ID() == get_option( $wc_page_id, 0 ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Returns true if on a archive product pages.
		 *
		 * @access public
		 * @return bool
		 */
		public function is_product_archive() {
			return $this->is_shop() || $this->is_product_taxonomy() ? true : false;
		}

		public function is_shop() {
			return function_exists( 'is_shop' ) && is_shop() ? true : false;
		}

		public function is_product_taxonomy() {
			$taxonomies = get_object_taxonomies( 'product' );

			return empty( $taxonomies ) ? false : is_tax( $taxonomies );
		}

		public function get_shop_base_url() {
			if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
				$link = home_url();
			} elseif ( is_shop() ) {
				$link = get_permalink( wc_get_page_id( 'shop' ) );
			} elseif ( is_product_category() ) {
				$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
			} elseif ( is_product_tag() ) {
				$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
			} elseif ( is_tax() ) {
				$queried_object = get_queried_object();
				$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			} else {
				$link = get_permalink();
			}

			return $link;
		}

		public function get_shop_active_filters_url( $filters = array(), $link = '' ) {
			if ( empty( $link ) ) {
				$link = Minimog_Woo::instance()->get_shop_base_url();
			}

			if ( empty( $filters ) ) {
				$filters = $_GET;
			}

			// Min/Max.
			if ( isset( $filters['min_price'] ) ) {
				$link = add_query_arg( 'min_price', wc_clean( wp_unslash( $filters['min_price'] ) ), $link );
			}

			if ( isset( $filters['max_price'] ) ) {
				$link = add_query_arg( 'max_price', wc_clean( wp_unslash( $filters['max_price'] ) ), $link );
			}

			// Order by.
			if ( isset( $filters['orderby'] ) ) {
				$link = add_query_arg( 'orderby', wc_clean( wp_unslash( $filters['orderby'] ) ), $link );
			}

			/**
			 * Search Arg.
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( wp_specialchars_decode( get_search_query() ) ), $link );
			}

			// Post Type Arg.
			if ( isset( $filters['post_type'] ) ) {
				$link = add_query_arg( 'post_type', wc_clean( wp_unslash( $filters['post_type'] ) ), $link );

				// Prevent post type and page id when pretty permalinks are disabled.
				if ( is_shop() ) {
					$link = remove_query_arg( 'page_id', $link );
				}
			}

			// Min Rating Arg.
			if ( isset( $filters['rating_filter'] ) ) {
				$link = add_query_arg( 'rating_filter', wc_clean( wp_unslash( $filters['rating_filter'] ) ), $link );
			}

			if ( ! empty( $filters['highlight_filter'] ) ) {
				$link = add_query_arg( 'highlight_filter', wc_clean( wp_unslash( $filters['highlight_filter'] ) ), $link );
			}

			if ( ! empty( $filters['stock_status'] ) ) {
				$link = add_query_arg( 'stock_status', wc_clean( wp_unslash( $filters['stock_status'] ) ), $link );
			}

			if ( ! empty( $filters['filter_product_cat'] ) ) {
				$link = add_query_arg( 'filter_product_cat', wc_clean( wp_unslash( $filters['filter_product_cat'] ) ), $link );
			}

			if ( ! empty( $filters['filter_product_tag'] ) ) {
				$link = add_query_arg( 'filter_product_tag', wc_clean( wp_unslash( $filters['filter_product_tag'] ) ), $link );
			}

			if ( ! empty( $filters['filter_product_brand'] ) ) {
				$link = add_query_arg( 'filter_product_brand', wc_clean( wp_unslash( $filters['filter_product_brand'] ) ), $link );
			}

			// All current filters.
			if ( $_chosen_attributes = \Minimog\Woo\Product_Query::get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
				foreach ( $_chosen_attributes as $name => $data ) {
					$filter_name = wc_attribute_taxonomy_slug( $name );

					if ( ! empty( $data['terms'] ) ) {
						$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
					}
					if ( 'or' === $data['query_type'] ) {
						$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
					}
				}
			}

			return $link;
		}

		public static function get_remove_active_filter_links( $filters ) {
			$filter_link = \Minimog_Woo::instance()->get_shop_active_filters_url( $filters );

			$clear_links = [];

			foreach ( $filters as $filter_name => $value ) {
				$taxonomy_name = 0 === strpos( $filter_name, 'filter_' ) ? wc_sanitize_taxonomy_name( str_replace( 'filter_', '', $filter_name ) ) : '';

				$attribute_name = wc_attribute_taxonomy_name( $taxonomy_name );
				$attribute_id   = ! empty( $attribute_name ) ? wc_attribute_taxonomy_id_by_name( $attribute_name ) : 0;

				// This is taxonomy filter like category, tag, brand...
				if ( ! empty( $taxonomy_name ) && taxonomy_exists( $taxonomy_name ) ) {
					$taxonomy = get_taxonomy( $taxonomy_name );

					$filter_terms = ! empty( $value ) ? explode( ',', wc_clean( wp_unslash( $value ) ) ) : array();

					if ( empty( $filter_terms ) ) {
						continue;
					}

					foreach ( $filter_terms as $key => $term_id ) {
						$clear_link = $filter_link;
						$clear_link = remove_query_arg( $filter_name, $clear_link );

						$term = get_term_by( 'id', $term_id, $taxonomy_name );

						if ( empty( $term ) ) {
							continue;
						}

						$clone_terms = $filter_terms;
						unset( $clone_terms[ $key ] );

						if ( empty( $clone_terms ) ) {
							$clear_link = remove_query_arg( $filter_name, $clear_link );
						} else {
							// Re add.
							$clear_link = add_query_arg( $filter_name, implode( ',', $clone_terms ), $clear_link );
						}

						$tooltip_text = isset( $taxonomy->labels->singular_name ) ? $taxonomy->labels->singular_name : __( 'Filter', 'minimog' );

						$clear_links[] = [
							'url'     => $clear_link,
							'text'    => $term->name,
							'tooltip' => sprintf( __( 'Remove This %s', 'minimog' ), $tooltip_text ),
							'class'   => 'remove-filter-link',
						];
					}
				} elseif ( $attribute_id && taxonomy_exists( $attribute_name ) ) { // This is attribute filter like color, size...
					$filter_terms = ! empty( $value ) ? explode( ',', wc_clean( wp_unslash( $value ) ) ) : array();

					if ( empty( $filter_terms ) ) {
						continue;
					}

					$attribute_info = wc_get_attribute( $attribute_id );

					foreach ( $filter_terms as $key => $term_slug ) {
						$clear_link = $filter_link;
						$clear_link = remove_query_arg( $filter_name, $clear_link );

						$term = get_term_by( 'slug', $term_slug, $attribute_name );

						if ( empty( $term ) ) {
							continue;
						}

						$clone_terms = $filter_terms;
						unset( $clone_terms[ $key ] );

						if ( empty( $clone_terms ) ) {
							$clear_link = remove_query_arg( $filter_name, $clear_link );
						} else {
							// Re add.
							$clear_link = add_query_arg( $filter_name, implode( ',', $clone_terms ), $clear_link );
						}

						$clear_links[] = [
							'url'     => $clear_link,
							'text'    => $term->name,
							'tooltip' => sprintf( __( 'Remove This %s', 'minimog' ), $attribute_info->name ),
							'class'   => 'remove-filter-link',
						];
					}
				} elseif ( 'rating_filter' === $filter_name ) {
					$filter_values = ! empty( $value ) ? explode( ',', wc_clean( wp_unslash( $value ) ) ) : array();

					if ( empty( $filter_values ) ) {
						continue;
					}

					foreach ( $filter_values as $key => $filter_value ) {
						$clear_link = $filter_link;
						$clear_link = remove_query_arg( $filter_name, $clear_link );

						$clone_values = $filter_values;
						unset( $clone_values[ $key ] );

						if ( empty( $clone_values ) ) {
							$clear_link = remove_query_arg( $filter_name, $clear_link );
						} else {
							// Re add.
							$clear_link = add_query_arg( $filter_name, implode( ',', $clone_values ), $clear_link );
						}

						$clear_links[] = [
							'url'     => $clear_link,
							'text'    => sprintf( _n( '%s star', '%s stars', $filter_value, 'minimog' ), $filter_value ),
							'tooltip' => sprintf( __( 'Remove This %s', 'minimog' ), __( 'Rating', 'minimog' ) ),
							'class'   => 'remove-filter-link',
						];
					}
				} elseif ( 'highlight_filter' === $filter_name ) {
					$clear_link        = $filter_link;
					$clear_link        = remove_query_arg( $filter_name, $clear_link );
					$highlight_options = Minimog_Woo::instance()->get_product_highlight_filter_options();

					$clear_link_text = isset( $highlight_options[ $value ] ) ? $highlight_options[ $value ] : $value;

					$clear_links[] = [
						'url'     => $clear_link,
						'text'    => $clear_link_text,
						'tooltip' => sprintf( __( 'Remove This %s', 'minimog' ), __( 'Highlight', 'minimog' ) ),
						'class'   => 'remove-filter-link',
					];
				}
			}

			if ( isset( $filters['min_price'] ) && isset( $filters['max_price'] ) ) {
				$clear_link = $filter_link;
				$clear_link = remove_query_arg( 'min_price', $clear_link );
				$clear_link = remove_query_arg( 'max_price', $clear_link );

				$clear_links[] = [
					'url'     => $clear_link,
					'text'    => wc_price( $filters['min_price'] ) . ' - ' . wc_price( $filters['max_price'] ),
					'tooltip' => sprintf( __( 'Remove This %s', 'minimog' ), __( 'Price', 'minimog' ) ),
					'class'   => 'remove-filter-link',
				];
			}

			if ( ! empty( $filters['filtering'] ) || ! empty( $clear_links ) ) {
				$clear_links[] = [
					'url'   => \Minimog_Woo::instance()->get_shop_base_url(),
					'text'  => esc_html__( 'Clear All', 'minimog' ),
					'class' => 'remove-all-filters-link',
				];
			}

			$output = '<div class="active-filters-list">';

			foreach ( $clear_links as $clear_link ) {
				$base_link_class = 'js-product-filter-link';

				if ( ! empty( $clear_link['class'] ) ) {
					$base_link_class .= " {$clear_link['class']}";
				}

				if ( ! empty( $clear_link['tooltip'] ) ) {
					$base_link_class .= ' hint--bounce hint--top';
				}

				$tooltip_text = ! empty( $clear_link['tooltip'] ) ? $clear_link['tooltip'] : esc_html__( 'Remove This Filter', 'minimog' );

				$output .= sprintf( '<a href="%1$s" class="%2$s" aria-label="%3$s"><div class="filter-link-text">%4$s</div></a>', $clear_link['url'], $base_link_class, $tooltip_text, $clear_link['text'] );
			}

			$output .= '</div>';

			return $output;
		}

		/**
		 * @param WC_Product|WC_Product_Variable $product
		 *
		 * @return string
		 */
		public function get_product_price_saving_amount( $product = null ) {
			if ( ! $product ) {
				global $product;
			}

			$saving_amount = 0;

			if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) || $product->is_type( 'woosb' ) ) {
				$regular_price = $product->get_regular_price();
				$sale_price    = $product->get_sale_price();

				$saving_amount = $regular_price - $sale_price;
			} elseif ( $product->is_type( 'variable' ) ) {
				$max_amount           = 0;
				$available_variations = $product->get_available_variations( 'objects' );
				foreach ( $available_variations as $variation ) {
					/**
					 * @var $variation WC_Product_Variable
					 */
					$regular_price = $variation->get_regular_price();
					$sale_price    = $variation->get_sale_price();

					if ( empty( $sale_price ) ) {
						continue;
					}

					$saving_amount = $regular_price - $sale_price;

					if ( $saving_amount > $max_amount ) {
						$max_amount = $saving_amount;
					}
				}

				return $max_amount;
			}

			return $saving_amount;
		}

		/**
		 * @param \WC_Product|\WC_Product_Variable|\WC_Product_Variation $product
		 *
		 * @return string
		 */
		public function get_product_price_saving_percentage( $product ) {
			$percentage = 0;

			if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) || $product->is_type( 'woosb' ) ) {
				$regular_price = $product->get_regular_price();
				$sale_price    = $product->get_sale_price();

				if ( $regular_price > 0 ) {
					$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
				}
			} elseif ( $product->is_type( 'variable' ) ) { // Show -x% badge if all variations has the same percent discount amount.
				$sale_percent         = 0;
				$available_variations = $product->get_available_variations( 'objects' );

				foreach ( $available_variations as $variation ) {
					// @var $variation WC_Product_Variable
					if ( ! $variation->is_on_sale() ) {
						return 0;
					}

					$regular_price = $variation->get_regular_price();
					$sale_price    = $variation->get_sale_price();
					if ( empty( $sale_price ) || 0 >= $regular_price ) {
						return 0;
					}

					$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );

					if ( $sale_percent === 0 ) { // Assign first discount amount for check later.
						$sale_percent = $percentage;
					}

					if ( $sale_percent !== $percentage ) { // If discount amount not the same then do nothing.
						return 0;
					}
				}

				return $sale_percent;
			} elseif ( $product->is_type( 'variation' ) ) {
				$regular_price = $product->get_regular_price();
				$sale_price    = $product->get_sale_price();

				if ( $regular_price > 0 ) {
					$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
				}
			}

			return $percentage;
		}

		public function get_product_sale_badge_text( $product ) {
			$percentage = $this->get_product_price_saving_percentage( $product );

			if ( $percentage > 0 ) {
				return "-{$percentage}%";
			}

			return esc_html__( 'Sale !', 'minimog' );
		}

		public function custom_woo_reviews_summary() {
			global $product;
			$product_id       = $product->get_id();
			$get_rating_count = get_post_meta( $product_id, '_wc_rating_count', true );
			$get_review_count = get_post_meta( $product_id, '_wc_review_count', true );
			$get_rating_text  = array(
				'5' => '5 stars',
				'4' => '4 stars',
				'3' => '3 stars',
				'2' => '2 stars',
				'1' => '1 stars',
			); ?>

			<?php
			for ( $i = 5; $i > 0; $i -- ) {
				if ( ! isset( $get_rating_count[ $i ] ) ) {
					$get_rating_count[ $i ] = 0;
				}
				$percentage = 0;
				if ( $get_rating_count[ $i ] > 0 ) {
					$percentage = round( ( $get_rating_count[ $i ] / $get_review_count ) * 100 );
				}
				?>
				<div class="reviews-bar">
					<label class="stars-title"
					       title="<?php echo esc_attr( $get_rating_text[ $i ] ); ?>"><?php echo esc_html( $get_rating_text[ $i ] ); ?></label>

					<div class="rating-graph" title="<?php printf( '%s%%', $percentage ); ?>">
						<div class="percentage bar"
						     style="width: <?php echo esc_attr( $percentage ); ?>%"></div>
					</div>

					<div class="rating-percentage">
						<?php echo esc_html( sprintf( __( '%1$d%%', 'minimog' ), $percentage ) ); ?>
					</div>
				</div> <?php
			} ?>
			<?php
		}

		public function product_infinite_load() {
			$source     = isset( $_GET['source'] ) ? sanitize_text_field( $_GET['source'] ) : '';
			$settings   = ! empty( $_GET['settings'] ) ? $_GET['settings'] : [];
			$query_vars = $_GET['query_vars'];

			$style = ! empty( $settings['style'] ) ? sanitize_text_field( $settings['style'] ) : 'grid-01';

			if ( 'current_query' === $source ) {

				$query_vars['paged']       = intval( $_GET['paged'] );
				$query_vars['nopaging']    = false;
				$query_vars['post_status'] = 'publish';

				$minimog_query = new WP_Query( $query_vars );
			} else {
				$query_args = array(
					'post_type'      => $query_vars['post_type'],
					'posts_per_page' => $query_vars['posts_per_page'],
					'orderby'        => $query_vars['orderby'],
					'order'          => $query_vars['order'],
					'paged'          => $query_vars['paged'],
					'post_status'    => 'publish',
				);

				$tax_query = isset( $query_vars['tax_query'] ) ? $query_vars['tax_query'] : array();

				$product_visibility_terms  = wc_get_product_visibility_term_ids();
				$product_visibility_not_in = array( $product_visibility_terms['exclude-from-search'] );

				// Hide out of stock products.
				if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
					$product_visibility_not_in[] = $product_visibility_terms['outofstock'];
				}

				if ( ! empty( $product_visibility_not_in ) ) {
					$tax_query[] = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'term_taxonomy_id',
						'terms'    => $product_visibility_not_in,
						'operator' => 'NOT IN',
					);
				}

				if ( ! empty( $tax_query ) ) {
					$tax_query['relation']   = 'AND';
					$query_args['tax_query'] = $tax_query;
				}

				if ( ! empty( $_GET['extra_taxonomy'] ) ) {
					$query_args = Minimog_Helper::build_extra_terms_query( $query_args, $_GET['extra_taxonomy'] );
				}

				$minimog_query = new WP_Query( $query_args );
			}

			$response = array(
				'max_num_pages' => $minimog_query->max_num_pages,
				'found_posts'   => $minimog_query->found_posts,
				'count'         => $minimog_query->post_count,
			);

			ob_start();

			if ( $minimog_query->have_posts() ) :

				while ( $minimog_query->have_posts() ) : $minimog_query->the_post();
					minimog_get_wc_template_part( 'content-product', $style, [
						'settings' => $settings,
					] );
				endwhile;

				wp_reset_postdata();

			endif;

			$template = ob_get_contents();
			$template = preg_replace( '~>\s+<~', '><', $template );
			ob_clean();

			$response['template'] = $template;

			echo json_encode( $response );

			wp_die();
		}

		/**
		 * @param \WC_Product $product
		 * @param mixed       $size
		 * @param array       $args
		 *
		 * @return string
		 */
		public function get_product_image( $product, $size = 'full', $args = [] ) {
			$args = wp_parse_args( $args, [
				'class' => 'product-main-image-img',
			] );

			if ( $product_image_id = $product->get_image_id() ) {
				return Minimog_Image::get_attachment_by_id( array_merge( [
					'id'   => $product_image_id,
					'size' => $size,
					'alt'  => $product->get_name(),
				], $args ) );
			} elseif ( $product->get_parent_id() ) {
				$parent_product = wc_get_product( $product->get_parent_id() );
				if ( $parent_product && $product_parent_image_id = $parent_product->get_image_id() ) {
					return Minimog_Image::get_attachment_by_id( array_merge( [
						'id'   => $product_parent_image_id,
						'size' => $size,
						'alt'  => $parent_product->get_name(),
					], $args ) );
				}
			}

			$src               = WC()->plugin_url() . '/assets/images/placeholder.png';
			$placeholder_image = get_option( 'woocommerce_placeholder_image', 0 );

			if ( ! empty( $placeholder_image ) && is_numeric( $placeholder_image ) ) {
				$image = Minimog_Image::get_attachment_by_id( array_merge( [
					'id'   => $placeholder_image,
					'size' => $size,
					'alt'  => $product->get_name(),
				], $args ) );
			} else {
				$image = '<img src="' . $src . '" alt="' . esc_attr( $product->get_name() ) . '" class="product-main-image-img"/>';
			}

			return $image;
		}

		/**
		 * @param \WC_Product $product
		 * @param mixed       $size
		 *
		 * @return string
		 */
		public function get_product_image_url( $product, $size = 'full' ) {
			$image_src = '';
			if ( $product->get_image_id() ) {
				$image_src = Minimog_Image::get_attachment_url_by_id( [
					'id'   => $product->get_image_id(),
					'size' => $size,
				] );
			} elseif ( $product->get_parent_id() ) {
				$parent_product = wc_get_product( $product->get_parent_id() );
				if ( $parent_product ) {
					$image_src = Minimog_Image::get_attachment_url_by_id( [
						'id'   => $parent_product->get_image_id(),
						'size' => $size,
					] );
				}
			}

			return $image_src;
		}

		public function move_layered_nav_count_inside_link( $term_html, $term, $link, $count ) {
			if ( $count > 0 ) {
				$term_html = str_replace( '</a>', '', $term_html );

				$find    = '</span>';
				$replace = '</span></a>';
				$pos     = strrpos( $term_html, $find );

				if ( $pos !== false ) {
					$term_html = substr_replace( $term_html, $replace, $pos, strlen( $find ) );
				}
			}

			return $term_html;
		}

		public function get_single_product_site_layout() {
			return $this->get_product_setting( 'single_product_site_layout' );
		}

		public function get_single_product_images_style() {
			return $this->get_product_setting( 'single_product_images_style' );
		}

		public function get_single_product_summary_layout() {
			return $this->get_product_setting( 'single_product_summary_layout' );
		}

		public function get_single_product_images_wide() {
			return $this->get_product_setting( 'single_product_images_wide' );
		}

		/**
		 * Get base shop page link
		 *
		 * @param bool $keep_query
		 *
		 * @return string $link
		 */
		public function get_shop_page_link( $keep_query = false ) {

			// Base Link decided by current page
			if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
				$link = home_url();
			} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
				$link = get_post_type_archive_link( 'product' );
			} elseif ( is_product_category() ) {
				$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
			} elseif ( is_product_tag() ) {
				$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
			} else {
				$link = get_term_link( get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			}

			if ( $keep_query ) {
				// Keep query string vars intact
				foreach ( $_GET as $key => $val ) {

					if ( 'orderby' === $key || 'submit' === $key ) {
						continue;
					}

					$link = add_query_arg( $key, $val, $link );
				}
			}

			return $link;
		}

		/**
		 * empty value for only products.
		 *
		 * @return string $shop_page_display
		 */
		public function get_shop_display() {
			/**
			 * Force mode for demo purpose.
			 */
			if ( ! empty( $_GET['shop_setting_preset'] ) ) {
				switch ( $_GET['shop_setting_preset'] ) {
					case 'category_layout_1':
						return 'both';
				}
			}

			return woocommerce_get_loop_display_mode();
		}

		/**
		 * @return mixed
		 * @deprecated 2.0.0
		 *
		 */
		public function get_shop_categories_carousel_style() {
			$category_style = Minimog::setting( 'shop_sub_categories_style' );

			if ( is_product_category() ) {
				$category_style = Minimog::setting( 'product_category_sub_categories_style' );
			}

			return $category_style;
		}

		public function get_shop_categories_style_options() {
			return [
				'01' => '01',
				'02' => '02',
				'03' => '03',
				'04' => '04',
				'05' => '05',
				'06' => '06',
				'07' => '07',
				'08' => '08',
				'09' => '09',
				'10' => '10',
				'11' => '11',
				'12' => '12',
				'13' => '13',
			];
		}

		/**
		 * Get product categories setting for display catalog or category page.
		 *
		 * @param $setting_name
		 *
		 * @return mixed
		 * @since 2.0.0
		 *
		 */
		public function get_shop_categories_setting( $setting_name ) {
			$setting_prefix = is_product_category() ? 'product_category_sub_categories_' : 'shop_sub_categories_';

			return Minimog::setting( $setting_prefix . $setting_name );
		}

		/**
		 * Get coupon display HTML.
		 *
		 * @param string|WC_Coupon $coupon Coupon data or code.
		 *
		 * @see wc_cart_totals_coupon_html()
		 *
		 */
		public function cart_totals_coupon_html( $coupon ) {
			if ( is_string( $coupon ) ) {
				$coupon = new WC_Coupon( $coupon );
			}

			$amount               = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
			$discount_amount_html = ' -' . wc_price( $amount );

			if ( $coupon->get_free_shipping() && empty( $amount ) ) {
				$discount_amount_html = __( 'Free shipping coupon', 'minimog' );
			}

			$discount_amount_html = '<span class="coupon-value">' . $discount_amount_html . '</span>';

			$discount_amount_html = apply_filters( 'woocommerce_coupon_discount_amount_html', $discount_amount_html, $coupon );
			$coupon_html          = $discount_amount_html . sprintf( '<a href="%1$s" class="fly-cart-coupon remove-coupon-link" data-coupon="%2$s">%3$s</a>', esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), \Automattic\Jetpack\Constants::is_defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ), esc_attr( $coupon->get_code() ), '<span class="coupon-icon fal fa-trash-alt"></span>' . __( 'Remove', 'minimog' ) );

			echo wp_kses( apply_filters( 'woocommerce_cart_totals_coupon_html', $coupon_html, $coupon, $discount_amount_html ), array_replace_recursive( wp_kses_allowed_html( 'post' ), array( 'a' => array( 'data-coupon' => true ) ) ) ); // phpcs:ignore PHPCompatibility.PHP.NewFunctions.array_replace_recursiveFound
		}

		/**
		 * @param \WC_Product_Variable $product
		 * @param array                $args Dropdown settings
		 */
		public function get_product_variation_dropdown_html( $product = null, $args = array() ) {
			if ( null === $product ) {
				global $product;
			}

			if ( ! $product instanceof WC_Product || 'variable' !== $product->get_type() ) {
				return;
			}

			$defaults = [
				'show_label' => true,
				'show_price' => true,
			];

			$args = wp_parse_args( $args, $defaults );

			$available_variations = $product->get_available_variations( 'objects' );
			$selected_attributes  = $product->get_default_attributes();
			$variation_attributes = $product->get_variation_attributes();

			$has_default_variation = false;
			if ( ! empty( $selected_attributes && ! empty( $variation_attributes ) && count( $selected_attributes ) === count( $variation_attributes ) ) ) {
				$has_default_variation = true;
			}

			if ( empty( $available_variations ) && false !== $available_variations ) {
				return;
			}

			$select_options_html = '';

			foreach ( $available_variations as $variation ) {
				$all_attributes = $this->get_variation_attributes_for_dropdown( $product, $variation );
				$raw_price      = strip_tags( wc_price( $variation->get_price() ) );

				foreach ( $all_attributes as $attribute ) {
					$option_data_attributes = [];
					$option_label           = [];

					$is_selected = false;
					$is_ok       = true;

					foreach ( $attribute as $key => $value ) {
						if ( $args['show_label'] ) {
							$option_label[] = $value['label'] . ': ' . $value['name'];
						} else {
							$option_label[] = $value['name'];
						}

						$option_data_attributes[ $value['option_name'] ] = $value['value'];

						if ( $has_default_variation && $is_ok ) {
							foreach ( $selected_attributes as $selected_attribute_key => $selected_attribute_value ) {

								if ( $selected_attribute_key === $key ) {
									if ( $selected_attribute_value !== $value['value'] ) {
										$is_ok = false;
										break;
									}
								}
							}
						}
					}

					if ( $has_default_variation && $is_ok ) {
						$is_selected = true;
					}

					if ( $args['show_price'] ) {
						$option_label[] = $raw_price;
					}

					$select_options_html .= '<option ' . selected( $is_selected, true, false ) . ' data-variation-id="' . $variation->get_id() . '" value="' . esc_attr( json_encode( $option_data_attributes ) ) . '">' . implode( ' / ', $option_label ) . '</option>';
				}
			}
			?>
			<select name="product_variation_id" class="product-variation-select">
				<option
					value="" <?php selected( $has_default_variation, false ); ?>><?php esc_html_e( 'Select a option', 'minimog' ); ?></option>
				<?php echo '' . $select_options_html; ?>
			</select>
			<?php
		}

		/**
		 * @param WC_Product_Variable  $product
		 * @param WC_Product_Variation $variation
		 *
		 * @return array
		 */
		public function get_variation_attributes_for_dropdown( $product, $variation ) {
			$attributes = $variation->get_attributes();

			$fill = $empty = [];

			foreach ( $attributes as $attr_name => $attr_value ) {
				if ( empty( $attr_value ) ) {
					$empty[] = $attr_name;
				} else {
					$taxonomy_id   = wc_attribute_taxonomy_id_by_name( $attr_name );
					$taxonomy_info = wc_get_attribute( $taxonomy_id );

					if ( empty( $taxonomy_info ) ) {
						$taxonomy_name = ucwords( $attr_name );
						$term_name     = ucwords( $attr_value );
					} else {
						$taxonomy_name = $taxonomy_info->name;
						$term          = get_term_by( 'slug', $attr_value, $attr_name );
						$term_name     = $term->name;
					}

					$fill[ $attr_name ] = [
						'label'       => $taxonomy_name,
						'option_name' => 'attribute_' . $attr_name,
						'value'       => $attr_value,
						'name'        => $term_name,
					];
				}
			}

			$results = [
				$fill,
			];

			foreach ( $empty as $empty_attr ) {
				$reference_attr = wc_get_product_terms( $product->get_id(), $empty_attr, array( 'fields' => 'all' ) );
				$taxonomy_id    = wc_attribute_taxonomy_id_by_name( $empty_attr );
				$taxonomy_info  = wc_get_attribute( $taxonomy_id );
				$taxonomy_name  = empty( $taxonomy_info->name ) ? ucwords( $empty_attr ) : $taxonomy_info->name;
				$new_results    = [];

				// Handle for custom attribute separate by |
				if ( empty( $taxonomy_id ) ) {
					$attributes = $product->get_attributes();
					/**
					 * @var \WC_Product_Attribute $this_attribute
					 */
					$this_attribute      = $attributes[ $empty_attr ];
					$this_attribute_data = $this_attribute->get_data();
					foreach ( $this_attribute_data['options'] as $option_value ) {
						foreach ( $results as $key => $value ) {

							$new_val = $value;

							$new_val[ $empty_attr ] = [
								'label'       => $this_attribute_data['name'],
								'option_name' => 'attribute_' . $empty_attr,
								'value'       => $option_value,
								'name'        => $option_value,
							];

							$new_results[] = $new_val;
						}
					}
				} else {
					foreach ( $reference_attr as $reference_key => $term ) {
						foreach ( $results as $key => $value ) {
							$new_val = $value;

							$new_val[ $empty_attr ] = [
								'label'       => $taxonomy_name,
								'option_name' => 'attribute_' . $empty_attr,
								'value'       => $term->slug,
								'name'        => $term->name,
							];

							$new_results[] = $new_val;
						}
					}
				}

				$results = $new_results;
			}

			return $results;
		}

		/**
		 * Get product ids on best-selling list that based on total sales.
		 *
		 * @return array|mixed
		 */
		public function get_product_ids_best_selling() {
			$number              = intval( Minimog::setting( 'shop_best_selling_list_number' ) );
			$transient_name      = "minimog_product_ids_best_selling";
			$cached_best_selling = (array) get_transient( $transient_name );

			global $wpdb;
			$query_sql  = "SELECT product.ID FROM $wpdb->posts AS product
						INNER JOIN $wpdb->postmeta AS p_meta ON ( product.ID = p_meta.post_id )
						WHERE p_meta.meta_key = 'total_sales'
						  	AND p_meta.meta_value > '0'
							AND product.post_type = 'product'
							AND product.post_status = 'publish'
						GROUP BY product.ID ORDER BY p_meta.meta_value+0 DESC LIMIT 0, %d";
			$query_sql  = $wpdb->prepare( $query_sql, $number );
			$query_hash = md5( $query_sql );

			if ( ! isset( $cached_best_selling[ $query_hash ] ) ) {
				$products = [];
				$results  = $wpdb->get_col( $query_sql );

				if ( ! empty( $results ) ) {
					$products = $results;
				}

				$cached_best_selling[ $query_hash ] = $products;
				set_transient( $transient_name, $cached_best_selling, DAY_IN_SECONDS );
			}

			return $cached_best_selling[ $query_hash ];
		}

		/**
		 * Get ids of products that new arrivals.
		 *
		 * @return array|mixed
		 */
		public function get_product_ids_new_arrivals() {
			$transient_name = 'minimog_product_ids_new_arrivals';

			$products = get_transient( $transient_name );

			if ( false === $products ) {
				global $wpdb;
				$day        = Minimog::setting( 'shop_badge_new_range' );
				$date_range = strtotime( "-{$day} day" );
				$products   = [];

				$date_query = date( 'Y-m-d H:i:s', $date_range );

				$sql = "SELECT product.ID FROM {$wpdb->posts} AS product
						WHERE 1=1 AND ( product.post_date > %s )
						AND product.post_type = 'product' AND product.post_status = 'publish'
						ORDER BY product.post_date DESC";

				$sql     = $wpdb->prepare( $sql, $date_query );
				$results = $wpdb->get_col( $sql );

				if ( ! empty( $results ) ) {
					$products = $results;
				}

				set_transient( $transient_name, $products, DAY_IN_SECONDS );
			}

			return $products;
		}

		/**
		 * Check if product is in best-selling list.
		 *
		 * @param WC_Product $product
		 *
		 * @return bool
		 */
		public function is_product_in_best_selling() {
			global $product;
			$best_selling_ids = $this->get_product_ids_best_selling();

			return in_array( $product->get_id(), $best_selling_ids );
		}

		public function get_loop_product_image_size( $width = 0 ) {
			if ( 0 === $width ) {
				$width = intval( get_option( 'woocommerce_thumbnail_image_width', 450 ) );
			}

			if ( isset( self::$product_loop_image_size[ $width ] ) ) {
				return self::$product_loop_image_size[ $width ];
			}

			$image_size                              = $this->get_product_image_size_by_width( $width );
			self::$product_loop_image_size[ $width ] = $image_size;

			return $image_size;
		}

		public function get_single_product_image_size( $width = 540 ) {
			if ( isset( self::$product_loop_image_size[ $width ] ) ) {
				return self::$product_loop_image_size[ $width ];
			}

			$image_size                              = $this->get_product_image_size_by_width( $width );
			self::$product_loop_image_size[ $width ] = $image_size;

			return $image_size;
		}

		public function get_single_product_image_width() {
			$summary_layout = Minimog_Woo::instance()->get_single_product_summary_layout();
			$images_layout  = Minimog_Woo::instance()->get_single_product_images_wide();

			switch ( $summary_layout ) {
				case 'full':
				case 'full-gap-100':
				case 'full-gap-80':
				case 'full-gap-0':
					$summary_width = 1920;
					break;
				case 'wider':
					$summary_width = 1720;
					break;
				case 'wide':
					$summary_width = 1620;
					break;
				case 'large':
					$summary_width = 1410;
					break;
				case 'broad':
					$summary_width = 1340;
					break;
				default:
					$summary_width = 1170;
					break;
			}

			$summary_width -= 30; // Gutter.

			switch ( $images_layout ) {
				case 'wide':
					$percentage = 66.6;
					break;
				case 'extended':
					$percentage = 55.555556;
					break;
				case 'narrow':
					$percentage = 42;
					break;
				default:
					$percentage = 50;
					break;
			}

			$image_size_width = ceil( ( $percentage / 100 ) * $summary_width );

			return $image_size_width;
		}

		public function get_single_product_image_size_by_feature_style( $feature_style, $is_quick_view = false ) {
			switch ( $feature_style ) {
				case 'carousel':
					$image_width = 600;
					break;
				case 'grid':
					$image_width = 570;
					break;
				default: // Slider.
					$image_width = $is_quick_view ? 455 : Minimog_Woo::instance()->get_single_product_image_width();
					break;
			}

			return apply_filters( 'minimog/single_product/image_size', Minimog_Woo::instance()->get_single_product_image_size( $image_width ), $feature_style, $is_quick_view );
		}

		public function get_single_product_thumb_size() {
			$thumbnail_size = Minimog_Woo::instance()->get_single_product_image_size( 150 );

			return apply_filters( 'minimog/single_product/feature_slider/thumbnail_size', $thumbnail_size );
		}

		public function get_product_image_size_by_width( $width = 450 ) {
			$height = $this->get_product_image_height_by_width( $width );

			return $width . 'x' . $height;
		}

		public function get_product_image_height_by_width( $width = 450 ) {
			$cropping = get_option( 'woocommerce_thumbnail_cropping' );

			switch ( $cropping ) {
				case 'custom':
					$ratio_w = floatval( get_option( 'woocommerce_thumbnail_cropping_custom_width' ) );
					$ratio_h = floatval( get_option( 'woocommerce_thumbnail_cropping_custom_height' ) );

					// Normalize data to avoid division for 0.
					$ratio_h = $ratio_h > 0 ? $ratio_h : 1;
					$ratio_w = $ratio_w > 0 ? $ratio_w : $ratio_h;

					$height = ( $width * $ratio_h ) / $ratio_w;
					$height = (int) $height;

					break;
				case 'uncropped':
					$height = 9999;
					break;
				default:
					$height = $width;
					break;
			}

			return $height;
		}

		public function get_product_image_ratio_height_percent() {
			$cropping = get_option( 'woocommerce_thumbnail_cropping' );

			switch ( $cropping ) {
				case 'custom':
					$ratio_w = get_option( 'woocommerce_thumbnail_cropping_custom_width' );
					$ratio_h = get_option( 'woocommerce_thumbnail_cropping_custom_height' );
					$height  = ( $ratio_h / $ratio_w ) * 100;
					break;
				default: // un cropped & 1:1
					$height = 100;
					break;
			}

			return "{$height}%";
		}

		public function output_skeleton_loading_item( $loop_settings ) {
			?>
			<div class="minimog-skeleton-card style-01">
				<div class="minimog-skeleton-item minimog-skeleton-image"></div>
				<?php if ( ! empty( $loop_settings['show_category'] ) ) : ?>
					<div class="minimog-skeleton-item minimog-skeleton-category"></div>
				<?php endif; ?>
				<?php if ( ! empty( $loop_settings['show_brand'] ) ) : ?>
					<div class="minimog-skeleton-item minimog-skeleton-category"></div>
				<?php endif; ?>
				<?php if ( ! empty( $loop_settings['show_rating'] ) ) : ?>
					<div class="minimog-skeleton-item minimog-skeleton-rating">
						<?php Minimog_Templates::render_rating( 5 ); ?>
					</div>
				<?php endif; ?>
				<div class="minimog-skeleton-item minimog-skeleton-title"></div>
				<?php if ( ! empty( $loop_settings['show_price'] ) ) : ?>
					<div class="minimog-skeleton-item minimog-skeleton-price"></div>
				<?php endif; ?>
				<?php if ( ! empty( $loop_settings['show_variation'] ) ) : ?>
					<div class="minimog-skeleton-colors">
						<?php echo str_repeat( '<div class="minimog-skeleton-item minimog-skeleton-color"></div>', mt_rand( 2, 3 ) ); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php
		}

		public function get_product_quantity_type( $this_product = null ) {
			if ( is_null( $this_product ) ) {
				$this_product = $GLOBALS['product'];
			}

			if ( $this_product instanceof WC_Product ) {
				$type = get_post_meta( $this_product->get_id(), '_quantity_type', true );

				if ( empty( $type ) ) {
					$type = Minimog::setting( 'product_quantity_type', 'input' );
				}
			}

			return ! empty( $type ) ? $type : 'input';
		}

		public function get_product_quantity_select_ranges( $this_product = null ) {
			if ( is_null( $this_product ) ) {
				$this_product = $GLOBALS['product'];
			}

			if ( ! $this_product instanceof WC_Product ) {
				return '';
			}

			$ranges = get_post_meta( $this_product->get_id(), '_quantity_ranges', true );

			if ( empty( $ranges ) ) {
				$ranges = \Minimog::setting( 'product_quantity_ranges' );
			}

			return $ranges;
		}

		public function output_add_to_cart_quantity_html( $args = array(), $product = null ) {
			if ( is_null( $product ) ) {
				$product = $GLOBALS['product'];
			}

			$quantity_type = Minimog_Woo::instance()->get_product_quantity_type( $product );

			if ( 'select' === $quantity_type ) {
				Minimog_Woo::instance()->output_quantity_select( $args, $product );
			} else {
				woocommerce_quantity_input( $args, $product );
			}
		}

		public function output_quantity_select( $args = array(), $this_product = null, $echo = true ) {
			if ( is_null( $this_product ) ) {
				$this_product = $GLOBALS['product'];
			}

			$defaults = array(
				'input_id'     => uniqid( 'quantity_' ),
				'input_name'   => 'quantity',
				'input_value'  => '1',
				'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array(
					'input-text',
					'qty',
					'text',
				), $this_product ),
				'max_value'    => apply_filters( 'woocommerce_quantity_input_max', - 1, $this_product ),
				'min_value'    => apply_filters( 'woocommerce_quantity_input_min', 0, $this_product ),
				'step'         => apply_filters( 'woocommerce_quantity_input_step', 1, $this_product ),
				'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
				'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
				'product_name' => $this_product ? $this_product->get_title() : '',
				'placeholder'  => apply_filters( 'woocommerce_quantity_input_placeholder', '', $this_product ),
			);

			$args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $this_product );

			// Apply sanity to min/max args - min cannot be lower than 0.
			$args['min_value'] = max( $args['min_value'], 0 );
			$args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';

			// Max cannot be lower than min if defined.
			if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
				$args['max_value'] = $args['min_value'];
			}

			$args['values'] = Minimog_Woo::instance()->get_product_quantity_select_ranges( $this_product );

			ob_start();

			wc_get_template( 'global/quantity-select.php', $args );

			if ( $echo ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo ob_get_clean();
			} else {
				return ob_get_clean();
			}
		}

		/**
		 * Get availability text based on stock status.
		 *
		 * @return string
		 */
		public function get_product_availability_text() {
			global $product;

			if ( ! $product->is_in_stock() ) {
				$availability = __( 'Out of stock', 'minimog' );
			} elseif ( $product->managing_stock() && $product->is_on_backorder( 1 ) && $product->backorders_require_notification() ) {
				$availability = __( 'Available on backorder', 'minimog' );
			} elseif ( ! $product->managing_stock() && $product->is_on_backorder( 1 ) ) {
				$availability = __( 'Available on backorder', 'minimog' );
			} elseif ( $product->managing_stock() ) {
				$availability = $this->format_stock_for_display( $product );
			} else {
				$availability = __( 'In stock', 'minimog' );
			}

			return apply_filters( 'woocommerce_get_availability_text', $availability, $this );
		}

		/**
		 * Format the stock amount ready for display based on settings.
		 *
		 * @param WC_Product $product Product object for which the stock you need to format.
		 *
		 * @return string
		 * @see wc_format_stock_for_display()
		 *
		 */
		public function format_stock_for_display( $product ) {
			$display      = __( 'In stock', 'minimog' );
			$stock_amount = $product->get_stock_quantity();

			if ( $stock_amount > 0 ) {
				if ( $stock_amount <= wc_get_low_stock_amount( $product ) ) {
					/* translators: %s: stock amount */
					$display = sprintf( __( 'Only %s left in stock', 'minimog' ), wc_format_stock_quantity_for_display( $stock_amount, $product ) );
				} else {
					/* translators: %s: stock amount */
					$display = sprintf( __( '%s in stock', 'minimog' ), wc_format_stock_quantity_for_display( $stock_amount, $product ) );
				}
			}

			if ( $product->backorders_allowed() && $product->backorders_require_notification() ) {
				$display .= ' ' . __( '(can be backordered)', 'minimog' );
			}

			return $display;
		}

		public function get_upsells_products_heading() {
			$heading = \Minimog_Helper::get_post_meta( 'single_product_up_sells_heading', '' );

			if ( '' === $heading ) {
				$heading = __( 'You may also like', 'minimog' );
			}

			return apply_filters( 'woocommerce_product_upsells_products_heading', $heading );
		}

		public function get_recent_viewed_products_heading() {
			return apply_filters( 'woocommerce_product_recent_viewed_products_heading', __( 'Recently viewed products', 'minimog' ) );
		}

		/**
		 * @param array $attributes
		 *
		 * @return array
		 */
		public function get_variation_attributes_from_attributes( $attributes ) {
			return array_filter( $attributes, array(
				$this,
				'filter_variation_attributes',
			) );
		}

		/**
		 * This is a clone function
		 *
		 * @param \WC_Product_Attribute $attribute Product attribute.
		 *
		 * @return bool
		 * @see \WC_Meta_Box_Product_Data::filter_variation_attributes()
		 *
		 * Filter callback for finding variation attributes.
		 *
		 */
		public function filter_variation_attributes( $attribute ) {
			return true === $attribute->get_variation();
		}

		public function is_wishlist_page() {
			global $post;

			$wishlist_page_id = $this->get_wishlist_page_id();

			return isset( $post ) && $wishlist_page_id === $post->ID ? true : false;
		}

		public function get_wishlist_page_id() {
			if ( get_option( 'woosw_page_id' ) ) {
				return absint( get_option( 'woosw_page_id' ) );
			}

			return false;
		}

		public function get_shop_loop_style_options() {
			return array(
				'grid-01' => sprintf( esc_html__( 'Style %s', 'minimog' ), '01' ),
				'grid-02' => sprintf( esc_html__( 'Style %s', 'minimog' ), '02' ),
				'grid-03' => sprintf( esc_html__( 'Style %s', 'minimog' ), '03' ),
				'grid-04' => sprintf( esc_html__( 'Style %s', 'minimog' ), '04' ),
				'grid-05' => sprintf( esc_html__( 'Style %s', 'minimog' ), '05' ),
				'grid-06' => sprintf( esc_html__( 'Style %s', 'minimog' ), '06' ),
				'grid-07' => sprintf( esc_html__( 'Style %s', 'minimog' ), '07' ),
				'grid-08' => sprintf( esc_html__( 'Style %s', 'minimog' ), '08' ),
				'grid-09' => sprintf( esc_html__( 'Style %s', 'minimog' ), '09' ),
				'grid-10' => sprintf( esc_html__( 'Style %s', 'minimog' ), '10' ),
			);
		}

		public function get_shop_loop_carousel_style_options() {
			return array(
				'carousel-01' => sprintf( esc_html__( 'Style %s', 'minimog' ), '01' ),
				'carousel-02' => sprintf( esc_html__( 'Style %s', 'minimog' ), '02' ),
				'carousel-03' => sprintf( esc_html__( 'Style %s', 'minimog' ), '03' ),
				'carousel-04' => sprintf( esc_html__( 'Style %s', 'minimog' ), '04' ),
				'carousel-05' => sprintf( esc_html__( 'Style %s', 'minimog' ), '05' ),
				'carousel-06' => sprintf( esc_html__( 'Style %s', 'minimog' ), '06' ),
				'carousel-07' => sprintf( esc_html__( 'Style %s', 'minimog' ), '07' ),
				'carousel-08' => sprintf( esc_html__( 'Style %s', 'minimog' ), '08' ),
				'carousel-09' => sprintf( esc_html__( 'Style %s', 'minimog' ), '09' ),
				'carousel-10' => sprintf( esc_html__( 'Style %s', 'minimog' ), '10' ),
			);
		}

		public function get_shop_loop_caption_style_options() {
			return array(
				'01' => sprintf( esc_html__( 'Style %s', 'minimog' ), '01' ),
				'02' => sprintf( esc_html__( 'Style %s', 'minimog' ), '02' ),
				'03' => sprintf( esc_html__( 'Style %s', 'minimog' ), '03' ),
				'04' => sprintf( esc_html__( 'Style %s', 'minimog' ), '04' ),
				'05' => sprintf( esc_html__( 'Style %s', 'minimog' ), '05' ),
				'06' => sprintf( esc_html__( 'Style %s', 'minimog' ), '06' ),
			);
		}

		public function get_result_count_text( $current, $per_page, $total ) {
			$pagination_type = Minimog::setting( 'shop_archive_pagination_type' );

			if ( 1 === intval( $total ) ) {
				$text = esc_html__( 'Showing the single result', 'minimog' );
			} elseif ( $total <= $per_page || - 1 === $per_page ) {
				/* translators: %d: total results */
				$text = sprintf( _n( 'Showing all %d result', 'Showing all %d results', $total, 'minimog' ), $total );
			} else {
				if ( in_array( $pagination_type, [ 'load-more', 'infinite' ], true ) ) {
					$first = min( $total, $per_page * $current );

					$text = sprintf( _nx( 'Showing %1$d of %2$d result', 'Showing %1$d of %2$d results', $total, 'with first and last result', 'minimog' ), $first, $total );
				} else {
					$first = ( $per_page * $current ) - $per_page + 1;
					$last  = min( $total, $per_page * $current );
					/* translators: 1: first result 2: last result 3: total results */
					$text = sprintf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'minimog' ), $first, $last, $total );
				}
			}

			return '<p>' . $text . '</p>';
		}

		public function get_pagination( $args = array() ) {
			$defaults = array(
				'total'   => wc_get_loop_prop( 'total_pages' ),
				'current' => wc_get_loop_prop( 'current_page' ),
				'base'    => esc_url_raw( add_query_arg( 'product-page', '%#%', false ) ),
				'format'  => '?product-page=%#%',
			);

			$args = wp_parse_args( $args, $defaults );

			wc_get_template( 'loop/custom-pagination.php', $args );
		}

		public function get_product_highlight_filter_options() {
			return apply_filters( 'minimog/wp_widget/product_highlight_filter/options', [
				''             => __( 'All Products', 'minimog' ),
				'best_selling' => __( 'Best Seller', 'minimog' ),
				'new_arrivals' => __( 'New Arrivals', 'minimog' ),
				'on_sale'      => __( 'Sale', 'minimog' ),
				'featured'     => __( 'Hot Items', 'minimog' ),
			] );
		}

		public function get_product_delivery_range_begin() {
			global $product;

			$product_delivery_range_begin = $product->get_meta( '_shipping_delivery_time_range_begin' );
			$delivery_days                = '' !== $product_delivery_range_begin ? $product_delivery_range_begin : get_option( 'woocommerce_shipping_delivery_time_begin' );

			return intval( $delivery_days );
		}

		public function get_product_delivery_range() {
			global $product;

			$product_delivery_range = $product->get_meta( '_shipping_delivery_time_range' );
			$delivery_days          = '' !== $product_delivery_range ? $product_delivery_range : get_option( 'woocommerce_shipping_delivery_time' );

			return intval( $delivery_days );
		}

		public function get_product_delivery_time_type() {
			global $product;

			$time_type = $product->get_meta( '_shipping_delivery_time_type' );

			return '' !== $time_type ? $time_type : get_option( 'woocommerce_shipping_delivery_time_type', 'days' );
		}

		public function get_recent_viewed_products() {
			global $product;

			$viewed_product_ids = [];
			$posts_per_page     = Minimog::setting( 'recent_viewed_products_per_page' );

			if ( isset( $_COOKIE['recent_viewed_products'] ) ) {
				$viewed_product_ids = array_map( 'intval', explode( ',', $_COOKIE['recent_viewed_products'] ) );

				if ( $product instanceof WC_Product ) {
					$current_pid = $product->get_id();

					// Skip current product.
					$viewed_product_ids = array_values( array_diff( $viewed_product_ids, [ $current_pid ] ) );
				}
			}

			if ( empty( $viewed_product_ids ) ) {
				return false;
			}

			$viewed_ids = array_slice( $viewed_product_ids, 0, $posts_per_page );

			$products = wc_get_products( [
				'include' => $viewed_ids,
				'orderby' => 'include',
			] );

			return $products;
		}

		public function get_default_cat_thumbnail_size( $cat_style ) {
			switch ( $cat_style ) {
				case '02' :
					$thumbnail_size = '440x595';
					break;
				case '03' :
					/**
					 * Display size: 160x160
					 * Up size to fix blurry on zoom animation.
					 */ $thumbnail_size = '180x180';
					break;
				case '04' :
					$thumbnail_size = '383x510';
					break;
				case '05' :
					$thumbnail_size = '520x310';
					break;
				case '08' :
					$thumbnail_size = '291x295';
					break;
				case '07' :
				case '09' :
					/**
					 * Display size: 270x270
					 * Up size to fix blurry on zoom animation.
					 */

					$thumbnail_size = '300x300';
					break;
				case '10' :
					$thumbnail_size = '100x100';
					break;
				case '12' :
					$thumbnail_size = '85x85';
					break;
				default: // Style 01.
					$thumbnail_size = '370x500';
					break;
			}

			return $thumbnail_size;
		}

		public function get_product_image_slider_slide_html( $attachment_ids, $args = array() ) {
			/**
			 * @var bool   $open_gallery
			 * @var string $main_image_size
			 * @var string $thumb_image_size
			 * @var int    $thumbnail_id
			 */
			extract( $args );

			$image_lazy_load = isset( $args['image_lazy_load'] ) ? $args['image_lazy_load'] : true;

			$output = [
				'main_slides_html'  => '',
				'thumb_slides_html' => '',
			];

			global $product;

			if ( ! empty( $args['product'] ) && $args['product'] instanceof \WC_Product ) {
				$product = $args['product'];
			}

			foreach ( $attachment_ids as $attachment_id ) {
				$attachment_info = Minimog_Image::get_attachment_info( $attachment_id );

				if ( empty( $attachment_info ) || ! $attachment_info['src'] ) {
					continue;
				}

				$main_slide_classes          = array( 'swiper-slide' );
				$thumbnail_slide_classes     = array( 'swiper-slide' );
				$video_play_html             = '';
				$video_html                  = '';
				$attributes_string           = '';
				$main_slide_suffix_html      = '';
				$thumbnail_slide_suffix_html = '';

				$media_attach = get_post_meta( $attachment_id, 'minimog_product_attachment_type', true );
				switch ( $media_attach ) {
					case 'video':
						$video_url = get_post_meta( $attachment_id, 'minimog_product_video', true );
						if ( ! empty( $video_url ) ) {
							$main_slide_classes[]        = 'zoom has-video';
							$thumbnail_slide_classes[]   = 'has-video';
							$video_play_html             = '<div class="main-play-product-video"></div>';
							$svg_icon                    = Minimog_SVG_Manager::instance()->get( 'far-video' );
							$thumbnail_slide_suffix_html = '<div class="thumbnail-play-product-video">' . $svg_icon . '</div>';

							if ( strpos( $video_url, 'mp4' ) !== false ) {
								$html5_video_id = uniqid( 'product-video-' . $attachment_id );

								$attributes_string .= sprintf( ' data-html="%s"', '#' . $html5_video_id );

								$video_html .= '<div id="' . $html5_video_id . '" style="display:none;"><video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none" src="' . esc_url( $video_url ) . '"></video></div>';
							} else {
								$attributes_string .= sprintf( ' data-src="%s"', esc_url( $video_url ) );
							}

							$main_slide_suffix_html = $video_play_html . $video_html;
						}
						break;
					case '360':
						$sprite_image_id  = get_post_meta( $attachment_id, 'minimog_360_source_sprite', true );
						$sprite_image_url = Minimog_Image::get_attachment_url_by_id( [
							'id'   => $sprite_image_id,
							'size' => 'full',
						] );

						if ( ! empty( $sprite_image_url ) ) {
							$thumbnail_slide_suffix_html = '<div class="thumbnail-play-product-video">' . Minimog_SVG_Manager::instance()->get( 'cube' ) . '</div>';

							$product_360_settings = [
								'source'  => $sprite_image_url,
								'frames'  => absint( get_post_meta( $attachment_id, 'minimog_360_total_frames', true ) ),
								'framesX' => absint( get_post_meta( $attachment_id, 'minimog_360_total_frames_per_row', true ) ),
								'width'   => 540,
								'height'  => Minimog_Woo::instance()->get_product_image_height_by_width( 540 ),
							];

							$main_slide_classes[] = 'btn-open-product-360';
							$attributes_string    .= ' data-spritespin-settings="' . esc_attr( wp_json_encode( $product_360_settings ) ) . '"';
						}
						break;
					default:
						$main_slide_classes[] = 'zoom';
						$attributes_string    .= sprintf( ' data-src="%s"', esc_url( $attachment_info['src'] ) );
						break;
				}

				if ( isset( $thumbnail_id ) && $attachment_id == $thumbnail_id ) {
					$main_slide_classes[]      = 'product-main-image';
					$thumbnail_slide_classes[] = 'product-main-thumbnail';
				}

				if ( $open_gallery ) {
					$sub_html = '';

					if ( ! empty( $attachment_info['title'] ) ) {
						$sub_html .= "<h4>{$attachment_info['title']}</h4>";
					}

					if ( ! empty( $attachment_info['caption'] ) ) {
						$sub_html .= "<p>{$attachment_info['caption']}</p>";
					}

					if ( ! empty( $sub_html ) ) {
						$attributes_string .= ' data-sub-html="' . esc_attr( $sub_html ) . '"';
					}
				}

				$attributes_string .= ' data-image-id="' . $attachment_id . '"';
				$attributes_string .= ' class="' . esc_attr( implode( ' ', $main_slide_classes ) ) . '"';

				$main_image_html = Minimog_Image::get_attachment_by_id( array(
					'id'        => $attachment_id,
					'size'      => $main_image_size,
					'alt'       => $product->get_name(),
					'class'     => $attachment_id === $thumbnail_id ? 'wp-post-image' : '',
					'lazy_load' => $image_lazy_load,
				) );

				/**
				 * Required wrap div to make compatible with 3rd plugins.
				 * For eg: Woocommerce advanced product fields
				 */
				$main_image_html = '<div class="woocommerce-product-gallery__image">' . $main_image_html . '</div>';
				$main_image_html = apply_filters( 'woocommerce_single_product_image_thumbnail_html', $main_image_html, $attachment_id );

				$output['main_slides_html'] .= sprintf( '<div %1$s>%2$s%3$s</div>', $attributes_string, $main_image_html, $main_slide_suffix_html );

				$thumbs_image_html           = Minimog_Image::get_attachment_by_id( [
					'id'        => $attachment_id,
					'size'      => $thumb_image_size,
					'alt'       => $product->get_name(),
					'lazy_load' => $image_lazy_load,
				] );
				$output['thumb_slides_html'] .= sprintf( '<div class="%1$s"><div class="swiper-thumbnail-wrap">%2$s%3$s</div></div>', esc_attr( implode( ' ', $thumbnail_slide_classes ) ), $thumbs_image_html, $thumbnail_slide_suffix_html );
			}

			return $output;
		}
	}

	Minimog_Woo::instance()->initialize();
}
