<?php
/**
 * Variable product add to cart
 *
 * @theme-since   1.0.0
 * @theme-version 1.9.8
 *
 * @var $attributes
 * @var $variation_attributes
 * @var $selected_attributes
 * @var $available_variations
 */

defined( 'ABSPATH' ) || exit;

global $product;

$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' );
?>
<form class="isw-swatches isw-swatches--in-single variations_form cart"
      method="post"
      enctype="multipart/form-data"
      data-product_id="<?php echo absint( get_the_ID() ); ?>"
      data-product_variations="<?php echo '' . $variations_attr; // WPCS: XSS ok. ?>"
>
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) { ?>
		<p class="stock out-of-stock"><?php esc_html__( 'This product is currently out of stock and unavailable.', 'minimog' ) ?></p>
	<?php } else { ?>
		<div class="variations">
			<?php
			$total_attrs = count( $variation_attributes );
			$loop_count  = 0;
			$is_last_row = false;

			foreach ( $attributes as $attribute_name => $product_attribute ) :
				$loop_count ++;

				if ( $loop_count === $total_attrs ) {
					$is_last_row = true;
				}

				/**
				 * @var WC_Product_Attribute $product_attribute
				 */
				$attribute_data = $product_attribute->get_data();

				// Skip render field if variation disabled.
				if ( $attribute_data['variation'] === false ) {
					continue;
				}

				$attr_id                 = wc_attribute_taxonomy_id_by_name( $attribute_name );
				$attr_info               = wc_get_attribute( $attr_id );
				$attribute_name_original = $attribute_data['name'];

				$curr['type'] = isset( $attr_info->type ) ? $attr_info->type : 'select';
				$curr['slug'] = isset( $attr_info->slug ) ? $attr_info->slug : '';
				$curr['name'] = isset( $attr_info->name ) ? $attr_info->name : '';
				if ( taxonomy_exists( $attribute_name_original ) ) {
					$curr['terms'] = wp_get_post_terms( $product->get_id(), $attribute_name_original, array( 'hide_empty' => false ) );

					if ( empty( $curr['terms'] ) || is_wp_error( $curr['terms'] ) ) {
						continue;
					}
				} else { // It's custom attribute.
					// Force type to select because custom attribute has no type.
					$curr['type'] = 'select';
				}
				?>
				<div class="row-isw-swatch row-isw-swatch--isw_<?php echo esc_attr( $curr['type'] ); ?>">
					<?php if ( ( $curr['type'] !== '' ) ) : ?>
						<?php
						$attribute_request = 'attribute_' . sanitize_title( $attribute_name );
						if ( isset( $_REQUEST[ $attribute_request ] ) ) {
							$selected = $_REQUEST[ $attribute_request ];
						} else if ( isset( $selected_attributes[ sanitize_title( $attribute_name ) ] ) ) {
							$selected = $selected_attributes[ sanitize_title( $attribute_name ) ];
						} else {
							$selected = '';
						}
						if ( ! $attr_info ) {
							$attr_data      = $product_attribute->get_data();
							$attribute_name = $attr_data['name'];
						}

						$dropdown_args      = array(
							'options'   => $variation_attributes[ $attribute_name_original ],
							'attribute' => $attribute_name_original,
							'product'   => $product,
							'selected'  => $selected,
							'class'     => 'isw-dropdown-' . $curr['type'],
						);
						?>
						<div class="label">
							<label for="<?php echo esc_attr( $curr['slug'] ); ?>">
								<?php printf( '%s: ', wc_attribute_label( $attribute_name_original ) ); ?>
							</label>
							<?php if ( 'select' !== $curr['type'] ) : ?>
								<div class="selected-term-name"></div>
							<?php endif; ?>
							<?php do_action( 'minimog/product_variation_attribute/label/after', $attribute_name ); ?>
						</div>
						<?php wc_dropdown_variation_attribute_options( $dropdown_args ); ?>
						<?php
						switch ( $curr['type'] ) :
							case 'text' :
								$text_style = get_option( "wc_attribute_type_text_style-{$attr_id}" );
								$wrap_class = "isw-swatch isw-swatch--isw_{$curr['type']} style-{$text_style}";
								?>
								<div class="<?php echo esc_attr( $wrap_class ); ?>"
								     data-attribute="<?php echo esc_attr( $attribute_name ); ?>">
									<?php
									foreach ( $curr['terms'] as $term ) {
										$term_class = 'isw-term term-link';

										$val     = get_term_meta( $term->term_id, 'sw_text', true ) ?: $term->name;
										$tooltip = get_term_meta( $term->term_id, 'sw_tooltip', true ) ?: $val;
										?>
										<span
											class="<?php echo esc_attr( $term_class ); ?>"
											aria-label="<?php echo esc_attr( $tooltip ); ?>"
											title="<?php echo esc_attr( $tooltip ); ?>"
											data-term="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $val ); ?></span>
										<?php
									}
									?>
								</div>
								<?php
								break;
							case 'color':
								?>
								<div class="isw-swatch isw-swatch--isw_<?php echo esc_attr( $curr['type'] ); ?>"
								     data-attribute="<?php echo esc_attr( $attribute_name ); ?>">
									<?php
									foreach ( $curr['terms'] as $term ) {
										$term_class = 'isw-term term-link hint--top';

										$val     = get_term_meta( $term->term_id, 'sw_color', true ) ?: '#fff';
										$tooltip = get_term_meta( $term->term_id, 'sw_tooltip', true ) ?: $term->name;
										?>
										<a href="#"
										   aria-label="<?php echo esc_attr( $tooltip ); ?>"
										   title="<?php echo esc_attr( $tooltip ); ?>"
										   class="<?php echo esc_attr( $term_class ); ?>"
										   data-term="<?php echo esc_attr( $term->slug ); ?>">
											<div class="term-shape"
											     style="background: <?php echo esc_attr( $val ); ?>"></div>
										</a>
										<?php
									}
									?>
								</div>
								<?php
								break;
							case 'image':
								?>
								<div class="isw-swatch isw-swatch--isw_<?php echo esc_attr( $curr['type'] ); ?>"
								     data-attribute="<?php echo esc_attr( $attribute_name ); ?>">
									<?php
									foreach ( $curr['terms'] as $term ) {
										$term_class = 'isw-term term-link hint--top';

										$val       = get_term_meta( $term->term_id, 'sw_image', true );
										$tooltip   = get_term_meta( $term->term_id, 'sw_tooltip', true ) ?: $term->name;
										$image_url = '';

										if ( ! empty( $val ) ) {
											$image_url = wp_get_attachment_thumb_url( $val );
										}

										if ( empty( $image_url ) ) {
											$image_url = wc_placeholder_img_src();
										}
										?>
										<a href="#"
										   aria-label="<?php echo esc_attr( $tooltip ); ?>"
										   class="<?php echo esc_attr( $term_class ); ?>"
										   title="<?php echo esc_attr( $tooltip ); ?>"
										   data-term="<?php echo esc_attr( $term->slug ); ?>">
											<div class="term-shape">
												<img src="<?php echo esc_url( $image_url ); ?>"
												     alt="<?php echo esc_attr( $tooltip ); ?>" width="64" height="64">
											</div>
										</a>
										<?php
									}
									?>
								</div>
								<?php
								break;
						endswitch;
						?>
					<?php endif; ?>

					<?php if ( $is_last_row ): ?>
						<div class="reset_variations-wrap">
							<a class="reset_variations reset_variations--single"
							   href="#"><?php esc_html_e( 'Clear', 'minimog' ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="single_variation_wrap">
			<?php
			/**
			 * woocommerce_before_single_variation Hook
			 */
			do_action( 'woocommerce_before_single_variation' );

			/**
			 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
			 *
			 * @since  2.4.0
			 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
			 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
			 */
			do_action( 'woocommerce_single_variation' );

			/**
			 * woocommerce_after_single_variation Hook
			 */
			do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php } ?>
	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
