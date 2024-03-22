<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.7.0
 */

defined( 'ABSPATH' ) || exit;

$category = isset( $args['category'] ) ? $args['category'] : $category;

$default_settings = [
	'style'           => Minimog_Woo::instance()->get_shop_categories_setting( 'style' ),
	'show_count_text' => true,
	'show_count'      => true,
	'show_min_price'  => false,
	'layout'          => 'slider',
];
$settings         = isset( $args['settings'] ) ? $args['settings'] : array();
$settings         = wp_parse_args( $settings, $default_settings );

$settings['thumbnail_size'] = isset( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : Minimog_Woo::instance()->get_default_cat_thumbnail_size( $settings['style'] );

$item_class = 'slider' === $settings['layout'] ? 'swiper-slide' : 'grid-item';

$link         = get_term_link( $category, 'product_cat' );
$thumbnail_id = ! empty( $settings['custom_thumbnail_id'] ) ? $settings['custom_thumbnail_id'] : get_term_meta( $category->term_id, 'thumbnail_id', true );

if ( $thumbnail_id ) {
	$image = \Minimog_Image::get_attachment_by_id( [
		'id'   => $thumbnail_id,
		'size' => $settings['thumbnail_size'],
	] );
} else {
	$image = '<img src="' . wc_placeholder_img_src() . '" alt="' . esc_attr__( 'Placeholder', 'minimog' ) . '" />';
}

if ( in_array( $settings['style'], [ '03', '04' ], true ) ) {
	$settings['show_count_text'] = false;
}

$item_image_wrap_class = 'minimog-image-wrapper';
if ( 'grid' === $settings['layout'] ) {
	$item_image_wrap_class .= ' grid-item-height';
}
?>
<div <?php wc_product_cat_class( $item_class, $category ); ?>>
	<div class="cat-wrap minimog-box">
		<div class="<?php echo esc_attr( $item_image_wrap_class ); ?>">
			<div class="minimog-image-inner">
				<div class="minimog-image image">
					<a href="<?php echo esc_url( $link ); ?>" class="cat-image minimog-box">
						<?php echo '' . $image; ?>
					</a>
				</div>
			</div>
		</div>

		<?php
		$caption_template = in_array( $settings['style'], [ '03', '04' ], true ) ? '03' : $settings['style'];
		minimog_get_wc_template_part( 'cat-loop/caption', $caption_template, [
			'category' => $category,
			'link'     => $link,
			'settings' => $settings,
		] );
		?>

		<?php
		// Button.
		if ( '09' === $settings['style'] ) {
			wc_get_template( 'cat-loop/button.php', [
				'category' => $category,
				'link'     => $link,
				'settings' => $settings,
			] );
		}
		?>
	</div>
</div>
