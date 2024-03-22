<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Coupon {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'woocommerce_coupon_options', [ $this, 'add_coupon_settings' ], 10, 2 );

		add_action( 'woocommerce_coupon_options_save', [ $this, 'save_coupon_settings' ], 10, 2 );

		add_action( 'minimog/coupon_modal/before', [ $this, 'output_available_coupons' ] );
	}

	/**
	 * @param  int       $coupon_id
	 * @param \WC_Coupon $coupon
	 */
	public function add_coupon_settings( $coupon_id, $coupon ) {
		$value = get_post_meta( $coupon_id, 'is_public', true );

		// Public coupon.
		woocommerce_wp_checkbox(
			array(
				'id'          => 'is_public',
				'label'       => __( 'Public coupon', 'minimog' ),
				'description' => __( 'Check this box if the coupon is public and it will be shown in fly cart', 'minimog' ),
				'value'       => wc_bool_to_string( $value ),
			)
		);
	}

	/**
	 * @param  int       $coupon_id
	 * @param \WC_Coupon $coupon
	 */
	public function save_coupon_settings( $coupon_id, $coupon ) {
		$is_public = isset( $_POST['is_public'] );

		$coupon->update_meta_data( 'is_public', $is_public );
		$coupon->save();
	}

	public function get_available_coupons() {
		$query_args = [
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
			'meta_query'     => [
				array(
					'key'     => 'is_public',
					'value'   => '1',
					'compare' => '==',
				),
			],
		];

		$coupons = new \WP_Query( $query_args );

		$coupon_ids = [];

		if ( $coupons->have_posts() ) {
			while ( $coupons->have_posts() ) {
				$coupons->the_post();

				$coupon_ids[] = get_the_ID();
			}

			wp_reset_postdata();
		}

		$available_coupons = [];

		if ( ! empty( $coupon_ids ) ) {
			$current_user  = wp_get_current_user();
			$billing_email = isset( $_POST['billing_email'] ) ? $_POST['billing_email'] : '';
			$check_emails  = array_unique(
				array_filter(
					array_map(
						'strtolower',
						array_map(
							'sanitize_email',
							array(
								$billing_email,
								$current_user->user_email,
							)
						)
					)
				)
			);

			$cart_applied_coupons = \WC()->cart->get_applied_coupons() ? : array();
			$cart_subtotal        = \WC()->cart->get_subtotal();
			$cart_item            = \WC()->cart->get_cart();
			$now                  = current_time( 'timestamp' );

			$products = array();
			if ( ! empty( $cart_item ) ) {
				foreach ( $cart_item as $item ) {
					$product_id = $item['variation_id'] ? : $item['product_id'];
					$product    = wc_get_product( $product_id );
					$products[] = $product;
				}
			}

			foreach ( $coupon_ids as $coupon_id ) {
				$coupon          = new \WC_Coupon( $coupon_id );
				$date_expire     = ! empty( $coupon->get_date_expires() ) ? strtotime( $coupon->get_date_expires( 'edit' )->date( 'Y-m-d' ) ) : '';
				$coupon_enable   = true;
				$coupon_active   = false;
				$coupon_messages = '';

				// Skip coupon if it has expired.
				if ( '' !== $date_expire && $now > $date_expire ) {
					continue;
				}

				// Limit to defined email addresses.
				$restrictions = $coupon->get_email_restrictions();
				if ( is_array( $restrictions ) && 0 < count( $restrictions ) && ! \WC()->cart->is_coupon_emails_allowed( $check_emails, $restrictions ) ) {
					continue;
				}

				// Skip coupon if products in cart not fit with usage restriction.
				if ( ! empty( $products ) ) {
					$continue = false;
					if ( ! $coupon->is_type( wc_get_product_coupon_types() ) ) {
						if ( $coupon->get_exclude_sale_items() ) {
							foreach ( $products as $product ) {
								if ( $product->is_on_sale() ) {
									$continue = true;
									break;
								}
							}
							if ( $continue ) {
								continue;
							}
						}
						if ( count( $coupon->get_excluded_product_ids() ) > 0 ) {
							foreach ( $products as $product ) {
								if ( in_array( $product->get_id(), $coupon->get_excluded_product_ids(), true ) || in_array( $product->get_parent_id(), $coupon->get_excluded_product_ids(), true ) ) {
									$continue = true;
									break;
								}
							}
							if ( $continue ) {
								continue;
							}
						}
						if ( count( $coupon->get_excluded_product_categories() ) > 0 ) {
							foreach ( $products as $product ) {
								$product_cats = wc_get_product_cat_ids( $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id() );
								if ( ! count( array_intersect( $product_cats, $coupon->get_excluded_product_categories() ) ) ) {
									$continue = true;
									break;
								}
							}
							if ( $continue ) {
								continue;
							}
						}

					} else {
						foreach ( $products as $product ) {
							$continue = $coupon->is_valid_for_product( $product );
							if ( $continue ) {
								break;
							}
						}
						if ( ! $continue ) {
							continue;
						}
					}
				}

				// Skip coupon if it applied in cart.
				if ( in_array( $coupon->get_code(), $cart_applied_coupons ) ) {
					$coupon_active = true;
				}

				$minimum_amount = $coupon->get_minimum_amount();
				$maximum_amount = $coupon->get_maximum_amount();

				// Disable coupon if cart subtotal spent lest than minimum amount required.
				if ( $minimum_amount > 0 && apply_filters( 'woocommerce_coupon_validate_minimum_amount', $minimum_amount > $cart_subtotal, $coupon, $cart_subtotal ) ) {
					$coupon_enable   = false;
					$coupon_messages = sprintf( __( 'The minimum spend for this coupon is %s.', 'minimog' ), wc_price( $minimum_amount ) );
				}

				// Disable coupon if cart subtotal spent more than maximum amount required.
				if ( $maximum_amount > 0 && apply_filters( 'woocommerce_coupon_validate_maximum_amount', $maximum_amount < $cart_subtotal, $coupon ) ) {
					$coupon_enable   = false;
					$coupon_messages = sprintf( __( 'The maximum spend for this coupon is %s.', 'minimog' ), wc_price( $maximum_amount ) );
				}

				$available_coupons[ $coupon_id ] = [
					'enable'   => $coupon_enable,
					'active'   => $coupon_active,
					'messages' => $coupon_messages,
				];
			}
		}

		return $available_coupons;
	}

	public function output_available_coupons() {
		$coupons = $this->get_available_coupons();

		if ( empty( $coupons ) ) {
			return;
		}

		wc_get_template( 'cart/cart-coupon-list.php', [
			'coupons' => $coupons,
		] );
	}
}

Coupon::instance()->initialize();
