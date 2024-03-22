<?php
/**
 * Cart Table Content
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart-content">
	<?php if ( ! WC()->cart->is_empty() ) : ?>
		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
			<tr>
				<th class="col-product-info"><?php esc_html_e( 'Product', 'minimog' ); ?></th>
				<th class="product-price"><?php esc_html_e( 'Price', 'minimog' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'minimog' ); ?></th>
				<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'minimog' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<td class="col-product-info">
							<div class="product-wrap">
								<div class="product-thumbnail">
									<?php
									$thumbnail = Minimog_Woo::instance()->get_product_image( $_product, Minimog_Woo::instance()->get_loop_product_image_size( 110 ) );
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $thumbnail, $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo '' . $thumbnail;
									} else {
										printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
									}
									?>
								</div>
								<div class="product-info">
									<h6 class="product-title">
										<?php
										if ( ! $product_permalink ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
										}
										?>
									</h6>
									<?php
									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

									// Meta data.
									echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

									// Backorder notification.
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'minimog' ) . '</p>' ) );
									}
									?>
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">%s</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_attr( 'remove btn-remove-from-cart' ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() ),
											esc_attr( $cart_item_key ),
											esc_attr__( 'Remove', 'minimog' )
										),
										$cart_item_key
									);
									?>
								</div>
							</div>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'minimog' ); ?>">
							<label class="cart-item-label-mobile"><?php esc_html_e( 'Price :', 'minimog' ); ?></label>
							<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'minimog' ); ?>">
							<?php
							$readonly = false;
							if ( $_product->is_sold_individually() ) {
								$min_quantity = 0;
								$max_quantity = 1;
								// Make quantity as input text readonly for better UI.
								$readonly = true;
							} else {
								$min_quantity = 0;
								$max_quantity = $_product->get_max_purchase_quantity();
							}

							ob_start();
							Minimog_Woo::instance()->output_add_to_cart_quantity_html( [
								'input_name'   => $cart_item_key, // Use single cart key to work with Ajax.
								'input_value'  => $cart_item['quantity'],
								'max_value'    => $max_quantity,
								'min_value'    => $min_quantity,
								'product_name' => $_product->get_name(),
								'readonly'     => $readonly,
							], $_product );
							$product_quantity = ob_get_clean();

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
							?>
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'minimog' ); ?>">
							<label
								class="cart-item-label-mobile"><?php esc_html_e( 'Subtotal :', 'minimog' ); ?></label>
							<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>
					</tr>
					<?php
				}
			}

			do_action( 'woocommerce_cart_contents' );
			?>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>
	<?php else: ?>
		<?php wc_get_template( 'cart/cart-empty.php' ); ?>
	<?php endif; ?>
</div>
