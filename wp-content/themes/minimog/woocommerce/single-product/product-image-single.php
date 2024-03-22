<?php
/**
 * @package Minimog
 * @since   1.0.0
 * @version 2.5.1
 */
defined( 'ABSPATH' ) || exit;

global $post, $product;

$output = '';

foreach ( $attachment_ids as $attachment_id ) {
	$attachment_info = Minimog_Image::get_attachment_info( $attachment_id );

	if ( ! $attachment_info['src'] ) {
		continue;
	}

	$output = Minimog_Image::get_attachment_by_id( array(
		'id'   => $attachment_id,
		'size' => $main_image_size,
		'alt'  => $product->get_name(),
	) );
	$output = '<div class="woocommerce-product-gallery__image">' . $output . '</div>';
	$output = apply_filters( 'woocommerce_single_product_image_thumbnail_html', $output, intval( $attachment_id ) );
	break;
}

?>
<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
	<?php echo '' . $output; ?>
</div>
