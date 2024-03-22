<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Advanced_Discounts {

	protected static $instance = null;

	const RECOMMEND_PLUGIN_VERSION = '2.28.2';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		if ( ! $this->is_activate() ) {
			return;
		}

		// Check old version installed.
		if ( defined( 'WAD_VERSION' ) && version_compare( WAD_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_plugin_version' ] );
		}

		/**
		 * Fix quantity discount not applied on ajax request.
		 */
		add_filter( 'woocommerce_cart_subtotal', [ $this, 'get_cart_subtotal' ], 100, 1 );

		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

		/**
		 * Remove quantity based pricing table from plugin then add custom HTML
		 *
		 * @see WAD_Discount::get_quantity_pricing_tables()
		 * @see add_quantity_pricing_tables_html()
		 */
		add_filter( 'wad_get_quantity_pricing_tables', '__return_empty_string', 99, 3 );
		add_action( 'woocommerce_after_add_to_cart_button', [ $this, 'add_quantity_pricing_tables_html' ], 15 );
	}

	public function is_activate() {
		return defined( 'WAD_VERSION' );
	}

	public function get_version() {
		return WAD_VERSION;
	}

	public function admin_notice_minimum_plugin_version() {
		minimog_notice_required_plugin_version( 'Conditional Discounts for WooCommerce', self::RECOMMEND_PLUGIN_VERSION );
	}

	public function frontend_scripts() {
		wp_dequeue_style( 'woo-advanced-discounts' );
		wp_dequeue_style( 'o-tooltip' );

		wp_dequeue_script( 'woo-advanced-discounts' );
		wp_dequeue_script( 'o-tooltip' );
	}

	/**
	 * Remove is_cart & is_checkout conditions to working properly in AJAX
	 *
	 * @param $subtotal
	 *
	 * @return string
	 * @see \WAD_Discount::get_cart_subtotal()
	 *
	 */
	public function get_cart_subtotal( $subtotal ) {
		$new_subtotal = 0;
		$items        = WC()->cart->get_cart_contents();

		foreach ( $items as $item => $values ) {
			/**
			 * @var \WC_Product $product_obj
			 */
			$product_obj  = $values['data'];
			$price        = $product_obj->get_price();
			$quantity     = $values['quantity'];
			$new_subtotal += $price * $quantity;
		}
		$subtotal = wc_price( $new_subtotal );

		return $subtotal;
	}

	public function add_quantity_pricing_tables_html() {
		/**
		 * @var \WC_Product|\WC_Product_Variable $product
		 */ global $product;

		if ( $product->is_sold_individually() ) {
			return;
		}

		$product_id       = $product->get_id();
		$quantity_pricing = get_post_meta( $product_id, 'o-discount', true );

		if ( version_compare( $this->get_version(), '2.28.2', '>' ) ) { // After ver 2.28.2
			$rules_type = \O_Utils::get_proper_value( $quantity_pricing, 'rules-type', 'intervals' );
		} else {
			$rules_type = get_proper_value( $quantity_pricing, 'rules-type', 'intervals' );
		}

		if ( ! isset( $quantity_pricing['enable'] ) ) {
			return;
		}

		if ( ! isset( $quantity_pricing['rules'] ) && ! isset( $quantity_pricing['rules-by-step'] ) ) {
			return;
		}
		?>
		<div class="quantity-discount-table-wrap">
			<h4 class="quantity-discount-heading">
				<?php esc_html_e( 'Buy more save more!', 'minimog' ); ?>
			</h4>
			<?php
			if ( 'intervals' === $rules_type ) {
				if ( $product->get_type() === 'variable' ) {
					$available_variations = $product->get_available_variations();
					foreach ( $available_variations as $variation ) {
						$product_price = $variation['display_price'];
						$this->get_quantity_pricing_table( $variation['variation_id'], $quantity_pricing, $product_price );
					}
				} else {
					$product_price = $product->get_price();
					$this->get_quantity_pricing_table( $product_id, $quantity_pricing, $product_price, true );
				}
			} else if ( 'steps' === $rules_type ) {

				if ( $product->get_type() === 'variable' ) {
					$available_variations = $product->get_available_variations();
					foreach ( $available_variations as $variation ) {
						$product_price = $variation['display_price'];
						$this->get_steps_quantity_pricing_table( $variation['variation_id'], $quantity_pricing, $product_price );
					}
				} else {
					$product_price = $product->get_price();
					$this->get_steps_quantity_pricing_table( $product_id, $quantity_pricing, $product_price, true );
				}
			} ?>
		</div>
		<?php
	}

	private function get_steps_quantity_pricing_table( $product_id, $quantity_pricing, $product_price, $display = false ) {
		global $product;
		$classes = 'quantity-discount-table wad-qty-pricing-table';
		?>
		<div class="<?php echo esc_attr( $classes ) ?>" data-id="<?php echo esc_attr( $product_id ); ?>"
			<?php if ( ! $display ) : ?>
				style="display: none;"
			<?php endif; ?>
		>
			<?php
			$discount_price = $product_price;
			$discount_type  = $quantity_pricing['type'];
			foreach ( $quantity_pricing['rules-by-step'] as $rule ) :
				if ( '' === $rule['every'] ) {
					continue;
				}

				$input_disable = false;
				$min_quantity  = apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product );
				$max_quantity  = apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product );
				$quantity      = intval( $rule['every'] );

				if ( $quantity < $min_quantity || ( $quantity > $max_quantity && $max_quantity !== - 1 ) ) {
					$input_disable = true;
				}

				$discount_value_text = $rule['discount'];

				switch ( $discount_type ) {
					case 'fixed':
						$discount_price = $product_price - $rule['discount'];

						$discount_percentage = round( ( $product_price - $discount_price ) / $product_price * 100 );

						$discount_value_text = $discount_percentage . '%';
						break;
					case 'percentage':
						$discount_price = $product_price - ( $product_price * $rule['discount'] ) / 100;

						$discount_value_text = $rule['discount'] . '%';
						break;
				}

				$row_classes = 'quantity-discount-item';

				if ( $input_disable ) {
					$row_classes .= ' disabled';
				}
				?>
				<div class="<?php echo esc_attr( $row_classes ); ?>">
					<div class="quantity-discount-info">
						<h5 class="quantity-discount-name">
							<?php echo sprintf( esc_html( _n( 'Buy %s item get %s OFF', 'Buy %s items get %s OFF', $quantity, 'minimog' ) ), $quantity, $discount_value_text ); ?>
						</h5>
						<p class="quantity-discount-suggest"><?php esc_html_e( 'on each product', 'minimog' ); ?></p>
					</div>
					<div class="quantity-discount-add">
						<button
							class="single_add_to_cart_button ajax_add_to_cart button button-2 quantity-discount-add-button"
							data-qty="<?php echo esc_attr( $quantity ); ?>">
							<span><?php esc_html_e( 'Add', 'minimog' ); ?></span></button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	private function get_quantity_pricing_table( $product_id, $quantity_pricing, $product_price, $display = false ) {
		global $product;
		$classes = 'quantity-discount-table wad-qty-pricing-table';
		?>
		<div class="<?php echo esc_attr( $classes ) ?>" data-id="<?php echo esc_attr( $product_id ); ?>"
			<?php if ( ! $display ) : ?>
				style="display: none;"
			<?php endif; ?>
		>
			<?php
			$discount_price    = $product_price;
			$discount_type     = $quantity_pricing['type'];
			foreach ( $quantity_pricing['rules'] as $rule ) :
				$discount_label = '';
				$min_quantity  = apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product );
				$max_quantity  = apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product );
				$input_disable = false;

				if ( '' !== $rule['min'] ) {
					$quantity = $rule['min'];
				} elseif ( '' !== $rule['max'] ) {
					$quantity = $rule['max'];
				}

				if ( empty( $quantity ) ) {
					continue;
				}

				if ( $quantity < $min_quantity || ( $quantity > $max_quantity && $max_quantity !== - 1 ) ) {
					$input_disable = true;
				}

				if ( '' !== $rule['min'] && '' !== $rule['max'] ) {
					if ( $rule['min'] !== $rule['max'] ) {
						$discount_label = sprintf( esc_html__( 'from %1$s to %2$s', 'minimog' ), $rule['min'], $rule['max'] );
					} else {
						$discount_label = $quantity;
					}
				} elseif ( '' !== $rule['min'] ) {
					$discount_label = sprintf( esc_html__( 'more than %s', 'minimog' ), $quantity );
				} elseif ( '' !== $rule['max'] ) {
					$discount_label = $quantity;
				}

				$discount_value_text = $rule['discount'];

				switch ( $discount_type ) {
					case 'fixed':
						$discount_price = $product_price - $rule['discount'];

						$discount_percentage = round( ( $product_price - $discount_price ) / $product_price * 100 );
						$discount_value_text = $discount_percentage . '%';
						break;
					case 'percentage':
						$discount_price = $product_price - ( $product_price * $rule['discount'] ) / 100;

						$discount_value_text = $rule['discount'] . '%';
						break;
				}

				$quantity = intval( $quantity );

				$row_classes = 'quantity-discount-item';

				if ( $input_disable ) {
					$row_classes .= ' disabled';
				}
				?>
				<div class="<?php echo esc_attr( $row_classes ); ?>">
					<div class="quantity-discount-info">
						<h5 class="quantity-discount-name">
							<?php echo sprintf( esc_html__( 'Buy %s items get %s OFF', 'minimog' ), $discount_label, $discount_value_text ); ?>
						</h5>
						<p class="quantity-discount-suggest"><?php esc_html_e( 'on each product', 'minimog' ); ?></p>
					</div>
					<div class="quantity-discount-add">
						<button
							class="single_add_to_cart_button ajax_add_to_cart button button-2 quantity-discount-add-button"
							data-qty="<?php echo esc_attr( $quantity ); ?>">
							<span><?php esc_html_e( 'Add', 'minimog' ); ?></span></button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

Advanced_Discounts::instance()->initialize();
