<?php
/**
 * Add to cart button
 * Support variable product
 */

global $product;

if ( $product->is_type( 'variable' ) ) {
	wc_get_template( 'custom/variable.php' );

	$button_text = $product->is_purchasable() && $product->is_in_stock() ? __( 'Add to cart', 'minimog' ) : __( 'Read more', 'minimog' );
} else {
	$button_text = $product->add_to_cart_text();
}

$defaults = array(
	'quantity'   => 1,
	'class'      => implode(
		' ',
		array_filter(
			array(
				'button',
				'product_type_' . $product->get_type(),
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
				$product->is_type( 'variable' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_variation_to_cart' : '',
				// Custom Classes
			)
		)
	),
	'attributes' => array(
		'data-product_id'  => $product->get_id(),
		'data-product_sku' => $product->get_sku(),
		'aria-label'       => $product->add_to_cart_description(),
		'rel'              => 'nofollow',
	),
);

$args = apply_filters( 'woocommerce_loop_add_to_cart_args', $defaults, $product );

if ( isset( $args['attributes']['aria-label'] ) ) {
	$args['attributes']['aria-label'] = wp_strip_all_tags( $args['attributes']['aria-label'] );
}

echo apply_filters(
	'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
	sprintf(
		'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		esc_html( $button_text )
	),
	$product,
	$args
);
