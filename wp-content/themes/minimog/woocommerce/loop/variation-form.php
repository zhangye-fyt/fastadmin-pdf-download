<?php
/**
 * Swatches selection
 *
 * @since   1.0.0
 * @version 2.4.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * @var \WC_Product_Variable $product
 */
global $product;

if ( 'variable' !== $product->get_type() ) {
	return;
}

if ( ! class_exists( 'Insight_Swatches' ) ) {
	return;
}

$selected_attribute = get_post_meta( $product->get_id(), 'variation_attributes_show_on_loop', true );
if ( empty( $selected_attribute ) ) {
	return;
}

$taxonomy_id   = wc_attribute_taxonomy_id_by_name( $selected_attribute );
$taxonomy_info = wc_get_attribute( $taxonomy_id );

if ( is_wp_error( $taxonomy_info ) || empty( $taxonomy_info ) ) {
	return;
}

$available_variations = $product->get_available_variations();

if ( empty( $available_variations ) ) {
	return;
}

$terms = wp_get_post_terms( $product->get_id(), $taxonomy_info->slug, array( 'hide_empty' => false ) );

if ( is_wp_error( $terms ) || empty( $terms ) ) {
	return;
}
/**
 * @since 1.2.1
 * Test up if terms used as variation
 * Skip render terms without used.
 */
$variation_attributes = $product->get_variation_attributes();

if ( empty( $variation_attributes[ $taxonomy_info->slug ] ) ) {
	return;
}
$terms_in_variation       = [];
$loop_variation_attribute = $variation_attributes[ $taxonomy_info->slug ];
foreach ( $terms as $term ) {
	$encode_slug = wc_get_text_attributes( $term->slug )[0];

	if ( in_array( $encode_slug, $loop_variation_attribute, true ) ) {
		$terms_in_variation[] = $term;
	}
}

$variations_images = [];

foreach ( $available_variations as $variation ) {
	$variation_image_src = Minimog_Image::get_attachment_url_by_id( [
		'id'   => $variation['image_id'],
		'size' => $args['thumbnail_size'],
	] );

	$variations_images[ $variation['variation_id'] ] = $variation_image_src;
}

$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

$term_link_classes = 'js-product-variation-term term-link hint--top';
?>
<div class="loop-product-variation-selector variation-selector-type-<?php echo esc_attr( $taxonomy_info->type ); ?>"
     data-product_attribute="attribute_<?php echo esc_attr( $taxonomy_info->slug ); ?>"
     data-product_variations="<?php echo '' . $variations_attr; // WPCS: XSS ok. ?>"
     data-product_variations_images="<?php echo esc_attr( wp_json_encode( $variations_images ) ); ?>"
>
	<div class="inner">
		<?php
		$total_terms   = count( $terms_in_variation );
		$items_to_show = 3;
		$loop_count    = 0;

		switch ( $taxonomy_info->type ) :
			case 'color':
				foreach ( $terms_in_variation as $term ) :
					$loop_count++;
					if ( $loop_count > $items_to_show ) {
						break 2;
					}

					$val     = get_term_meta( $term->term_id, 'sw_color', true ) ? : '#fff';
					$tooltip = get_term_meta( $term->term_id, 'sw_tooltip', true ) ? : $term->name;
					?>
					<a href="#" aria-label="<?php echo esc_attr( $tooltip ); ?>"
					   class="<?php echo esc_attr( $term_link_classes ); ?>"
					   data-term="<?php echo esc_attr( $term->slug ); ?>">
						<div class="term-shape">
							<span style="background: <?php echo esc_attr( $val ); ?>" class="term-shape-bg"></span>
							<span class="term-shape-border"></span>
						</div>
						<div class="term-name"><?php echo esc_html( $term->name ); ?></div>
					</a>
				<?php
				endforeach;
				break;
			case 'image':
				foreach ( $terms_in_variation as $term ) :
					$loop_count++;
					if ( $loop_count > $items_to_show ) {
						break 2;
					}

					$val     = get_term_meta( $term->term_id, 'sw_image', true );
					$tooltip = get_term_meta( $term->term_id, 'sw_tooltip', true ) ? : $term->name;

					if ( ! empty( $val ) ) {
						$image_url = wp_get_attachment_thumb_url( $val );
					} else {
						$image_url = wc_placeholder_img_src();
					}
					?>
					<a href="#" aria-label="<?php echo esc_attr( $tooltip ); ?>"
					   class="<?php echo esc_attr( $term_link_classes ); ?>"
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
				foreach ( $terms_in_variation as $term ) :
					$loop_count++;
					if ( $loop_count > $items_to_show ) {
						break 2;
					}

					$tooltip = get_term_meta( $term->term_id, 'sw_tooltip', true ) ? : $term->name;
					?>
					<a href="#" aria-label="<?php echo esc_attr( $tooltip ); ?>"
					   class="<?php echo esc_attr( $term_link_classes ); ?>"
					   data-term="<?php echo esc_attr( $term->slug ); ?>">
						<div class="term-name"><?php echo esc_html( $term->name ); ?></div>
					</a>
				<?php
				endforeach;
				break;
		endswitch;
		?>
		<?php if ( $total_terms > $items_to_show ) : ?>
			<?php
			$quick_view_enable = Minimog\Woo\Quick_View::can_show();
			$rest_link_class   = 'term-link-rest js-term-link-rest';
			$rest_link_class   .= $quick_view_enable ? ' quick-view-btn' : '';
			$rest_link_url     = $quick_view_enable ? '#' : $product->get_permalink();
			?>
			<a href="<?php echo esc_url( $rest_link_url ); ?>" class="<?php echo esc_attr( $rest_link_class ); ?>" data-pid="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo '+' . ( $total_terms - $items_to_show ) ?></a>
		<?php endif; ?>
	</div>
</div>
