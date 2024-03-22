<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

global $product;
// Get Available variations?
$get_variations       = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
$attributes           = $product->get_variation_attributes();
$available_variations = $get_variations ? $product->get_available_variations() : false;
$selected_attributes  = $product->get_default_attributes();

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
?>
<form class="variations_form cart"
      action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
      method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>"
      data-product_variations="<?php echo '' . $variations_attr; // WPCS: XSS ok. ?>">

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'minimog' ) ) ); ?></p>
	<?php else : ?>
		<div class="variations">
			<?php foreach ( $attributes as $attribute_name => $options ) : ?>
				<div class="variation">
					<?php
					$attr_id   = wc_attribute_taxonomy_id_by_name( $attribute_name );
					$attr_info = wc_get_attribute( $attr_id );

					$option_none_text = ! empty( $attr_info->name ) ? $attr_info->name : __( 'Choose an option', 'minimog' );

					wc_dropdown_variation_attribute_options(
						array(
							'options'          => $options,
							'attribute'        => $attribute_name,
							'product'          => $product,
							'show_option_none' => $option_none_text,
						)
					);
					echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'minimog' ) . '</a>' ) ) : '';
					?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<input type="hidden" name="variation_id" class="variation_id" value="0"/>
</form>
