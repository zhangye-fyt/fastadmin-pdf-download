<?php
/**
 * Trust badge
 *
 * @since   1.0.0
 * @version 2.1.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$trust_badge_image = Minimog_Helper::get_redux_image( [
	'setting_name'   => 'single_product_trust_badge_image',
	'img_attributes' => [
		'width'  => 317,
		'height' => 25,
		'alt'    => __( 'Trust Badge', 'minimog' ),
	],
] );

if ( empty( $trust_badge_image ) ) {
	return;
}

$text = \Minimog::setting( 'single_product_trust_badge_text' );

if ( empty( $text ) ) {
	$text = __( 'Guaranteed safe & secure checkout', 'minimog' );
}
?>
<div class="product-trust-badge">
	<?php echo '<div class="trust-badge-image">' . $trust_badge_image . '</div>'; ?>
	<div class="trust-badge-text"><?php echo esc_html( $text ); ?></div>
</div>
