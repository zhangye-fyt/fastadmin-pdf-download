<?php
/**
 * Add to cart button
 * Support variable product
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="loop-add-to-cart-form">
	<?php if ( $product->is_type( 'variable' ) && class_exists( 'Insight_Swatches' ) ) : ?>
		<?php
		$available_variations = $product->get_available_variations();
		$attributes           = $product->get_attributes();
		$selected_attribute   = get_post_meta( $product->get_id(), 'variation_attributes_show_on_loop', true );
		$variation_attributes = $product->get_variation_attributes();
		$selected_attributes  = $product->get_default_attributes();

		$variations_json = wp_json_encode( $available_variations );
		$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
		?>
		<form class="isw-swatches isw-swatches--in-loop variations_form cart"
		      method="post"
		      enctype="multipart/form-data"
		      data-product_id="<?php echo absint( get_the_ID() ); ?>"
		      data-product_variations="<?php echo '' . $variations_attr; // WPCS: XSS ok. ?>">
			<div class="variations">
				<?php foreach ( $attributes as $attribute_name => $attribute ) : ?>
					<?php
					/**
					 * @var WC_Product_Attribute $attribute
					 */

					// Skip render field if variation disabled.
					if ( true !== $attribute->get_variation() ) {
						continue;
					}

					$attr_id        = wc_attribute_taxonomy_id_by_name( $attribute_name );
					$attr_info      = wc_get_attribute( $attr_id );
					$term_sanitized = Insight_Swatches_Utils::utf8_urldecode( $attribute_name );
					$curr['type']   = isset( $attr_info->type ) ? $attr_info->type : 'select';
					$curr['slug']   = isset( $attr_info->slug ) ? $attr_info->slug : '';
					$curr['name']   = isset( $attr_info->name ) ? $attr_info->name : '';

					if ( ! taxonomy_exists( $term_sanitized ) ) {
						continue;
					}

					$terms = wp_get_post_terms( $product->get_id(), $term_sanitized, array( 'hide_empty' => false ) );
					?>
					<div class="row-isw-swatch row-isw-swatch--isw_<?php echo esc_attr( $curr['type'] ); ?>">
						<?php if ( $selected_attribute !== $attribute_name ) : ?>
							<div class="isw-swatch isw-swatch--isw_<?php echo esc_attr( $curr['type'] ); ?>"
							     data-attribute="<?php echo esc_attr( $attribute_name ); ?>">
								<?php
								switch ( $curr['type'] ) {
									case 'color':
										foreach ( $terms as $term ) :
											$term_class = 'isw-term term-link hint--top';

											$val     = get_term_meta( $term->term_id, 'sw_color', true ) ? : '#fff';
											$tooltip = get_term_meta( $term->term_id, 'sw_tooltip', true ) ? : $term->name;
											?>
											<a href="#"
											   aria-label="<?php echo esc_attr( $tooltip ); ?>"
											   class="<?php echo esc_attr( $term_class ); ?>"
											   data-term="<?php echo esc_attr( $term->slug ); ?>">
												<div class="term-shape">
										<span style="background: <?php echo esc_attr( $val ); ?>"
										      class="term-shape-bg"></span>
													<span class="term-shape-border"></span>
												</div>
												<div class="term-name"><?php echo esc_html( $term->name ); ?></div>
											</a>
										<?php
										endforeach;
										break;
									case 'image':
										foreach ( $terms as $term ) :
											$term_class = 'isw-term term-link hint--top';

											$val       = get_term_meta( $term->term_id, 'sw_image', true );
											$tooltip   = get_term_meta( $term->term_id, 'sw_tooltip', true ) ? : $term->name;
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
											   data-term="<?php echo esc_attr( $term->slug ); ?>">
												<div class="term-shape">
										<span style="background-image: url(<?php echo esc_attr( $image_url ); ?>);"
										      class="term-shape-bg"></span>
													<span class="term-shape-border"></span>
												</div>
												<div class="term-name"><?php echo esc_html( $term->name ); ?></div>
											</a>
										<?php
										endforeach;
										break;
									default:
										foreach ( $terms as $term ) :
											$term_class = 'isw-term term-link hint--top';

											$tooltip = get_term_meta( $term->term_id, 'sw_tooltip', true ) ? : $term->name;

											?>
											<a href="#"
											   aria-label="<?php echo esc_attr( $tooltip ); ?>"
											   class="<?php echo esc_attr( $term_class ); ?>"
											   data-term="<?php echo esc_attr( $term->slug ); ?>">
												<div class="term-name"><?php echo esc_html( $term->name ); ?></div>
											</a>
										<?php
										endforeach;
										break;
								}
								?>
							</div>
						<?php endif; ?>
						<?php
						if ( isset( $selected_attributes[ sanitize_title( $attribute_name ) ] ) ) {
							$selected = $selected_attributes[ sanitize_title( $attribute_name ) ];
						} else {
							$selected = '';
						}

						if ( ! $attr_info ) {
							$attr_data      = $attribute->get_data();
							$attribute_name = $attr_data['name'];
						}

						$dropdown_args = array(
							'options'   => $variation_attributes[ $attribute_name ],
							'attribute' => $attribute_name,
							'product'   => $product,
							'selected'  => $selected,
							'class'     => 'isw-dropdown-' . $curr['type'],
						);

						wc_dropdown_variation_attribute_options( $dropdown_args );
						?>
					</div>
				<?php endforeach; ?>
			</div>

			<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>"/>
			<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>"/>
			<input type="hidden" name="variation_id" class="variation_id" value="0"/>
		</form>
	<?php endif; ?>
	<?php
	if ( $product->is_type( 'variable' ) && class_exists( 'Insight_Swatches' ) ) {
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
	?>
</div>
