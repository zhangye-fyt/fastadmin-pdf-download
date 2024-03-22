<?php
/**
 * Shipping Calculator used on fly cart.
 * This file same with shipping-calculator.php file.
 * Minor html edited
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_shipping_calculator' );
?>
<form class="woocommerce-shipping-calculator fly-cart-shipping-calculator" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<div class="shipping-calculator-form">
		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_country', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_country_field">
				<select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state country_select"
				        rel="calc_shipping_state">
					<option value=""><?php esc_html_e( 'Select a country / region&hellip;', 'minimog' ); ?></option>
					<?php
					foreach ( WC()->countries->get_shipping_countries() as $key => $value ) {
						echo '<option value="' . esc_attr( $key ) . '"' . selected( WC()->customer->get_shipping_country(), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
					}
					?>
				</select>
			</p>
		<?php endif; ?>

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_state', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_state_field">
				<?php
				$current_cc = WC()->customer->get_shipping_country();
				$current_r  = WC()->customer->get_shipping_state();
				$states     = WC()->countries->get_states( $current_cc );

				if ( is_array( $states ) && empty( $states ) ) {
					?>
					<input type="hidden" name="calc_shipping_state" id="calc_shipping_state"
					       placeholder="<?php esc_attr_e( 'State / County', 'minimog' ); ?>"/>
					<?php
				} elseif ( is_array( $states ) ) {
					?>
					<span>
						<select name="calc_shipping_state" class="state_select" id="calc_shipping_state"
						        data-placeholder="<?php esc_attr_e( 'State / County', 'minimog' ); ?>">
							<option value=""><?php esc_html_e( 'Select an option&hellip;', 'minimog' ); ?></option>
							<?php
							foreach ( $states as $ckey => $cvalue ) {
								echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( $current_r, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
							}
							?>
						</select>
					</span>
					<?php
				} else {
					?>
					<input type="text" class="input-text" value="<?php echo esc_attr( $current_r ); ?>"
					       placeholder="<?php esc_attr_e( 'State / County', 'minimog' ); ?>" name="calc_shipping_state"
					       id="calc_shipping_state"/>
					<?php
				}
				?>
			</p>
		<?php endif; ?>

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_city', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_city_field">
				<input type="text" class="input-text"
				       value="<?php echo esc_attr( WC()->customer->get_shipping_city() ); ?>"
				       placeholder="<?php esc_attr_e( 'City', 'minimog' ); ?>" name="calc_shipping_city"
				       id="calc_shipping_city"/>
			</p>
		<?php endif; ?>

		<?php if ( apply_filters( 'woocommerce_shipping_calculator_enable_postcode', true ) ) : ?>
			<p class="form-row form-row-wide" id="calc_shipping_postcode_field">
				<input type="text" class="input-text"
				       value="<?php echo esc_attr( WC()->customer->get_shipping_postcode() ); ?>"
				       placeholder="<?php esc_attr_e( 'Postcode / ZIP', 'minimog' ); ?>" name="calc_shipping_postcode"
				       id="calc_shipping_postcode"/>
			</p>
		<?php endif; ?>

		<p class="form-row-submit form-submit-wrap">
			<button type="submit" name="calc_shipping" value="1"
			        class="button"><?php esc_html_e( 'Calculate shipping rates ', 'minimog' ); ?></button>
		</p>
		<?php wp_nonce_field( 'woocommerce-shipping-calculator', 'woocommerce-shipping-calculator-nonce' ); ?>
	</div>
</form>

<?php do_action( 'woocommerce_after_shipping_calculator' ); ?>
