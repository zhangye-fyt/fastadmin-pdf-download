<?php
/**
 * Cart goal to get free shipping
 */

defined( 'ABSPATH' ) || exit;

$cart_total          = \Minimog\Woo\Free_Shipping_Label::instance()->get_cart_total();
$amount_left         = 0;
$percent_amount_done = 100;

if ( $cart_total < $min_amount ) {
	$amount_left         = $min_amount - $cart_total;
	$percent_amount_done = \Minimog_Helper::calculate_percentage( $cart_total, $min_amount );
}
?>
<div class="cart-goal-text">
	<?php
	if ( $amount_left > 0 ) {
		printf( esc_html__( 'Buy %1$s more to enjoy %2$s', 'minimog' ),
			wc_price( $amount_left ),
			'<strong>' . esc_html__( 'FREE Shipping', 'minimog' ) . '</strong>' );
	} else {
		printf( esc_html__( 'Congrats! You are eligible for %s', 'minimog' ),
			'<strong>' . esc_html__( 'FREE Shipping', 'minimog' ) . '</strong>' );
	}
	?>
	<input type="hidden" name="cart-goal-percent" value="<?php echo esc_attr( $percent_amount_done ); ?>"
	       class="cart-goal-percent"/>
</div>


