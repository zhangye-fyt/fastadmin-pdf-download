<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPCleverWoosb' ) ) {
	class Product_Bundle extends \WPCleverWoosb {
		protected static $instance = null;

		const MINIMUM_PLUGIN_VERSION   = '6.2.0';
		const RECOMMEND_PLUGIN_VERSION = '7.0.5';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Init self constructor to avoid auto call parent::__construct
		 * This make code run twice times.
		 */
		public function __construct() {
		}

		public function initialize() {
			if ( ! $this->is_activate() ) {
				return;
			}

			if ( defined( 'WOOSB_VERSION' ) ) {
				if ( version_compare( WOOSB_VERSION, self::MINIMUM_PLUGIN_VERSION, '<' ) ) {
					return;
				}

				if ( version_compare( WOOSB_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) ) {
					add_action( 'admin_notices', [ $this, 'admin_notice_recommend_plugin_version' ] );
				}
			}

			minimog_remove_filters_for_anonymous_class( 'woocommerce_woosb_add_to_cart', 'WPCleverWoosb', 'add_to_cart_form' );
			add_action( 'woocommerce_woosb_add_to_cart', [ $this, 'add_to_cart_form' ] );
		}

		public function is_activate() {
			return class_exists( 'WPCleverWoosb' );
		}

		public function admin_notice_recommend_plugin_version() {
			minimog_notice_required_plugin_version( 'WPC Product Bundles for WooCommerce', self::RECOMMEND_PLUGIN_VERSION );
		}

		public function get_types() {
			return self::$types;
		}

		public function add_to_cart_form() {
			/**
			 * @var \WC_Product
			 */
			global $product;

			if ( ! $product || ! $product->is_type( 'woosb' ) ) {
				return;
			}

			if ( $product->has_variables() ) {
				wp_enqueue_script( 'wc-add-to-cart-variation' );
			}

			$position = get_option( '_woosb_bundled_position', 'above' );
			$can_show = apply_filters( 'woosb_show_bundled', true, $product->get_id() );

			if ( 'above' === $position && $can_show ) {
				$this->minimog_show_bundled();
			}

			wc_get_template( 'single-product/add-to-cart/simple.php' );

			if ( 'below' === $position && $can_show ) {
				$this->minimog_show_bundled();
			}
		}

		/**
		 * @param \WC_Product_Woosb $product
		 */
		function minimog_show_bundled( $product = null ) {
			if ( ! $product ) {
				global $product;
			}

			if ( ! $product || ! is_a( $product, 'WC_Product_Woosb' ) ) {
				return;
			}

			$items = $product->get_items();

			if ( empty( $items ) ) {
				return;
			}

			$order               = 1;
			$product_id          = $product->get_id();
			$fixed_price         = $product->is_fixed_price();
			$optional            = $product->is_optional();
			$has_variables       = $product->has_variables();
			$discount_amount     = $product->get_discount_amount();
			$discount_percentage = $product->get_discount_percentage();
			$total_limit         = get_post_meta( $product_id, 'woosb_total_limits', true ) === 'on';
			$total_min           = get_post_meta( $product_id, 'woosb_total_limits_min', true );
			$total_max           = get_post_meta( $product_id, 'woosb_total_limits_max', true );
			$whole_min           = get_post_meta( $product_id, 'woosb_limit_whole_min', true ) ? : 1;
			$whole_max           = get_post_meta( $product_id, 'woosb_limit_whole_max', true ) ? : '-1';
			$each_min_default    = get_post_meta( $product_id, 'woosb_limit_each_min_default', true ) === 'on';
			$each_min            = get_post_meta( $product_id, 'woosb_limit_each_min', true ) ? : 0;
			$each_max            = get_post_meta( $product_id, 'woosb_limit_each_max', true ) ? : 10000;
			$quantity_input_html = '';

			do_action( 'woosb_before_wrap', $product );

			echo '<div class="woosb-wrap woosb-bundled" data-id="' . esc_attr( $product_id ) . '">';

			if ( $before_text = apply_filters( 'woosb_before_text', get_post_meta( $product_id, 'woosb_before_text', true ), $product_id ) ) {
				echo '<div class="woosb-before-text woosb-text">' . do_shortcode( stripslashes( $before_text ) ) . '</div>';
			}

			do_action( 'woosb_before_table', $product );
			?>
			<div class="woosb-products"
			     data-discount-amount="<?php echo esc_attr( $discount_amount ); ?>"
			     data-discount="<?php echo esc_attr( $discount_percentage ); ?>"
			     data-fixed-price="<?php echo esc_attr( $fixed_price ? 'yes' : 'no' ); ?>"
			     data-price="<?php echo esc_attr( wc_get_price_to_display( $product ) ); ?>"
			     data-price-suffix="<?php echo esc_attr( htmlentities( $product->get_price_suffix() ) ); ?>"
			     data-variables="<?php echo esc_attr( $has_variables ? 'yes' : 'no' ); ?>"
			     data-optional="<?php echo esc_attr( $optional ? 'yes' : 'no' ); ?>"
			     data-min="<?php echo esc_attr( $whole_min ); ?>"
			     data-max="<?php echo esc_attr( $whole_max ); ?>"
			     data-total-min="<?php echo esc_attr( $total_limit && $total_min ? $total_min : 0 ); ?>"
			     data-total-max="<?php echo esc_attr( $total_limit && $total_max ? $total_max : '-1' ); ?>"
			>
				<?php
				// store global $product.
				$global_product = $product;

				foreach ( $items as $item ) {
					if ( $item['id'] ) {
						/**
						 * @var \WC_Product_Variable $product
						 */
						$product = wc_get_product( $item['id'] );

						if ( ! $product || in_array( $product->get_type(), $this->get_types(), true ) ) {
							continue;
						}

						if ( ! apply_filters( 'woosb_item_exclude', true, $product, $global_product ) ) {
							continue;
						}

						$item_qty = $item['qty'];
						if ( $optional ) {
							if ( $each_min_default ) {
								$item_min = $item_qty;
							} else {
								$item_min = (float) $each_min;
							}

							$item_max = (float) $each_max;

							if ( ( $max_purchase = $global_product->get_max_purchase_quantity() ) && ( $max_purchase > 0 ) && ( $max_purchase < $item_max ) ) {
								// get_max_purchase_quantity can return -1
								$item_max = $max_purchase;
							}

							if ( $item_qty < $item_min ) {
								$item_qty = $item_min;
							}

							if ( ( $item_max > $item_min ) && ( $item_qty > $item_max ) ) {
								$item_qty = $item_max;
							}
						}

						$item_class = 'woosb-item-product woosb-product';

						if ( ! apply_filters( 'woosb_item_visible', true, $global_product, $global_product ) ) {
							$item_class .= ' woosb-product-hidden';
						}

						if ( ( ! $product->is_in_stock() || ! $product->has_enough_stock( $item_qty ) || ! $product->is_purchasable() ) && ( get_option( '_woosb_exclude_unpurchasable', 'no' ) === 'yes' ) ) {
							$item_qty   = 0;
							$item_class .= ' woosb-product-unpurchasable';
						}

						$quantity_input_html = '';
						if ( $optional ) {
							if ( get_post_meta( $product_id, 'woosb_optional_products', true ) === 'on' ) {
								if ( ( $product->get_backorders() === 'no' ) && ( $product->get_stock_status() !== 'onbackorder' ) && is_int( $product->get_stock_quantity() ) && ( $product->get_stock_quantity() < $item_max ) ) {
									$item_max = $product->get_stock_quantity();
								}

								if ( $product->is_sold_individually() ) {
									$item_max = 1;
								}

								ob_start();
								?>
								<div class="woosb-quantity">
									<?php
									if ( $product->is_in_stock() ) {
										woocommerce_quantity_input( array(
											'input_value' => $item_qty,
											'min_value'   => $item_min,
											'max_value'   => $item_max,
											'woosb_qty'   => array(
												'input_value' => $item_qty,
												'min_value'   => $item_min,
												'max_value'   => $item_max,
											),
											'classes'     => array( 'input-text', 'woosb-qty', 'qty', 'text' ),
											'input_name'  => 'woosb_qty_' . $order
											// compatible with WPC Product Quantity
										), $product );
									} else { ?>
										<input type="number" class="input-text qty text woosb-qty" value="0" disabled/>
									<?php } ?>
								</div>
								<?php
								$quantity_input_html = ob_get_clean();
							}
						}

						do_action( 'woosb_above_item', $product, $global_product, $order );
						?>
						<div class="<?php echo esc_attr( apply_filters( 'woosb_item_class', $item_class, $product, $global_product, $order ) ); ?>"
						     data-name="<?php echo esc_attr( $product->get_name() ); ?>"
						     data-id="<?php echo esc_attr( $product->is_type( 'variable' ) ? 0 : $item['id'] ); ?>"
						     data-price="<?php echo esc_attr( \WPCleverWoosb_Helper::get_price_to_display( $product ) ); ?>"
						     data-price-suffix="<?php echo esc_attr( htmlentities( $product->get_price_suffix() ) ); ?>"
						     data-qty="<?php echo esc_attr( $item_qty ); ?>"
						     data-order="<?php echo esc_attr( $order ); ?>"
						>
							<?php
							do_action( 'woosb_before_item', $product, $global_product, $order );

							$has_link = $product->is_visible() && get_option( '_woosb_bundled_link', 'yes' ) !== 'no';

							if ( get_option( '_woosb_bundled_thumb', 'yes' ) !== 'no' ) { ?>
								<div class="woosb-thumb">
									<?php if ( $has_link ) {
										echo '<a ' . ( \WPCleverWoosb_Helper::get_setting( 'bundled_link', 'yes' ) === 'yes_popup' ? 'class="woosq-link no-ajaxy" data-id="' . $item['id'] . '" data-context="woosb"' : '' ) . ' href="' . esc_url( $product->get_permalink() ) . '" ' . ( \WPCleverWoosb_Helper::get_setting( 'bundled_link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>';
									} ?>
									<?php
									/**
									 * Disabled variation image changed.
									 * Because it's not support properly image size.
									 * Move img out of div to make js disabled.
									 */
									?>
									<!--<div class="woosb-thumb-ori"></div>
									<div class="woosb-thumb-new"></div>-->
									<?php
									$product_image = \Minimog_Woo::instance()->get_product_image( $product, \Minimog_Woo::instance()->get_loop_product_image_size( 60 ) );
									echo apply_filters( 'woosb_item_thumbnail', $product_image, $product );
									?>

									<?php if ( $has_link ) {
										echo '</a>';
									} ?>
								</div><!-- /woosb-thumb -->
							<?php } ?>

							<div class="woosb-product-info">
								<div class="woosb-product-main-info">
									<div class="woosb-title-wrap">
										<?php
										do_action( 'woosb_before_item_name', $product );

										echo '<h3 class="woosb-title post-title-2-rows">';

										if ( ( get_option( '_woosb_bundled_qty', 'yes' ) === 'yes' ) && ! $optional ) {
											echo apply_filters( 'woosb_item_qty', $item['qty'] . ' × ', $item['qty'], $product );
										}

										$item_name    = '';
										$product_name = apply_filters( 'woosb_item_product_name', $product->get_name(), $product );

										if ( $has_link ) {
											$item_name .= '<a ' . ( \WPCleverWoosb_Helper::get_setting( 'bundled_link', 'yes' ) === 'yes_popup' ? 'class="woosq-link no-ajaxy" data-id="' . $item['id'] . '" data-context="woosb"' : '' ) . ' href="' . esc_url( $product->get_permalink() ) . '" ' . ( \WPCleverWoosb_Helper::get_setting( 'bundled_link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>';
										}

										if ( $product->is_in_stock() && $product->has_enough_stock( $item_qty ) ) {
											$item_name .= $product_name;
										} else {
											$item_name .= '<s>' . $product_name . '</s>';
										}

										if ( $has_link ) {
											$item_name .= '</a>';
										}

										echo apply_filters( 'woosb_item_name', $item_name, $product, $global_product, $order );
										echo '</h3>';

										do_action( 'woosb_after_item_name', $product );

										if ( \WPCleverWoosb_Helper::get_setting( 'bundled_description', 'no' ) === 'yes' ) {
											echo '<div class="woosb-description">' . apply_filters( 'woosb_item_description', $product->get_short_description(), $product ) . '</div>';
										}
										?>
									</div>

									<?php if ( ( $bundled_price = get_option( '_woosb_bundled_price', 'price' ) ) !== 'no' ) { ?>
										<div class="woosb-price">
											<div class="woosb-price-ori">
												<?php
												$ori_price = $product->get_price();
												$get_price = \WPCleverWoosb_Helper::get_price( $product );

												if ( ! $fixed_price && $discount_percentage ) {
													$new_price     = true;
													$product_price = $get_price * ( 100 - (float) $discount_percentage ) / 100;
													$product_price = round( $product_price, wc_get_price_decimals() );
													$product_price = apply_filters( 'woosb_item_price_add_to_cart', $product_price, $item );
												} else {
													$new_price     = false;
													$product_price = $get_price;
												}

												switch ( $bundled_price ) {
													case 'price':
														if ( $new_price ) {
															$item_price = wc_format_sale_price( wc_get_price_to_display( $product, array( 'price' => $get_price ) ), wc_get_price_to_display( $product, array( 'price' => $product_price ) ) );
														} else {
															if ( $get_price > $ori_price ) {
																$item_price = wc_price( \WPCleverWoosb_Helper::get_price_to_display( $product ) ) . $product->get_price_suffix();
															} else {
																$item_price = $product->get_price_html();
															}
														}

														break;
													case 'subtotal':
														if ( $new_price ) {
															$item_price = wc_format_sale_price( wc_get_price_to_display( $product, array(
																	'price' => $get_price,
																	'qty'   => $item['qty'],
																) ), wc_get_price_to_display( $product, array(
																	'price' => $product_price,
																	'qty'   => $item['qty'],
																) ) ) . $product->get_price_suffix();
														} else {
															$item_price = wc_price( \WPCleverWoosb_Helper::get_price_to_display( $product, $item['qty'] ) ) . $product->get_price_suffix();
														}

														break;
													default:
														$item_price = $product->get_price_html();
												}

												echo apply_filters( 'woosb_item_price', $item_price, $product );
												?>
											</div>
											<div class="woosb-price-new"></div>
											<?php do_action( 'woosb_after_item_price', $product ); ?>
										</div>
									<?php } ?>
								</div>
								<div class="woosb-product-cart">
									<?php if ( $product->is_type( 'variable' ) ) : ?>
										<div class="minimog-variation-select-wrap">
											<?php
											if ( \WPCleverWoosb_Helper::get_setting( 'variations_selector', 'default' ) === 'woovr' && class_exists( 'WPClever_Woovr' ) ) {
												\WPClever_Woovr::woovr_variations_form( $product );
											} else {
												\Minimog_Woo::instance()->get_product_variation_dropdown_html( $product, [
													'show_label' => false,
													'show_price' => false,
												] );

												$attributes           = $product->get_variation_attributes();
												$available_variations = $product->get_available_variations();
												$variations_json      = wp_json_encode( $available_variations );
												$variations_attr      = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

												if ( ! empty( $attributes ) ) {
													$total_attrs = count( $attributes );
													$loop_count  = 0;

													echo '<div class="variations_form" data-product_id="' . absint( $product->get_id() ) . '" data-product_variations="' . $variations_attr . '">';
													echo '<div class="variations">';

													foreach ( $attributes as $attribute_name => $options ) {
														$loop_count++;
														?>
														<div class="variation">
															<div class="label">
																<?php echo wc_attribute_label( $attribute_name ); ?>
															</div>
															<div class="select">
																<?php
																$attr     = 'attribute_' . sanitize_title( $attribute_name );
																$selected = isset( $_REQUEST[ $attr ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ $attr ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
																wc_dropdown_variation_attribute_options( array(
																	'options'          => $options,
																	'attribute'        => $attribute_name,
																	'product'          => $product,
																	'selected'         => $selected,
																	'show_option_none' => wc_attribute_label( $attribute_name ),
																) );
																?>
															</div>
															<?php if ( $loop_count === $total_attrs ): ?>
																<?php echo '<div class="reset">' . apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'minimog' ) . '</a>' ) . '</div>'; ?>
															<?php endif; ?>
														</div>
													<?php }

													echo '</div>';
													echo '</div>';

													if ( get_option( '_woosb_bundled_description', 'no' ) === 'yes' ) {
														echo '<div class="woosb-variation-description"></div>';
													}
												}
											}

											do_action( 'woosb_after_item_variations', $product );
											?>
										</div>
									<?php endif; ?>
									<?php echo '' . $quantity_input_html; ?>
									<?php echo '<div class="woosb-availability">' . wc_get_stock_html( $product ) . '</div>'; ?>
								</div>
							</div>
							<?php do_action( 'woosb_after_item', $product, $global_product, $order ); ?>
						</div>
						<?php
						do_action( 'woosb_under_item', $product, $global_product, $order );
					} elseif ( ! empty( $item['text'] ) ) {
						$item_class = 'woosb-item-text';

						if ( ! empty( $item['type'] ) ) {
							$item_class .= ' woosb-item-text-type-' . $item['type'];
						}

						echo '<div class="' . esc_attr( apply_filters( 'woosb_item_text_class', $item_class, $item, $global_product, $order ) ) . '">';

						if ( empty( $item['type'] ) || ( $item['type'] === 'none' ) ) {
							echo $item['text'];
						} else {
							echo '<' . $item['type'] . '>' . $item['text'] . '</' . $item['type'] . '>';
						}

						echo '</div>';
					}

					$order++;
				}

				// Restore global $product.
				$product = $global_product;
				?>
			</div>
			<?php
			if ( ! $fixed_price && ( $has_variables || $optional ) ) {
				echo '<div class="woosb-total woosb-text"></div>';
			}

			echo '<div class="woosb-alert woosb-text" style="display: none"></div>';

			do_action( 'woosb_after_table', $product );

			if ( $after_text = apply_filters( 'woosb_after_text', get_post_meta( $product_id, 'woosb_after_text', true ), $product_id ) ) {
				echo '<div class="woosb-after-text woosb-text">' . do_shortcode( stripslashes( $after_text ) ) . '</div>';
			}

			echo '</div>';

			do_action( 'woosb_after_wrap', $product );
		}
	}

	Product_Bundle::instance()->initialize();
}

