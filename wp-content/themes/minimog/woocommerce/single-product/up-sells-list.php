<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $upsells ) ) {
	return;
}
?>
<div class="upsells-products-list">
	<?php
	$heading = Minimog_Woo::instance()->get_upsells_products_heading();

	if ( $heading ) :
		?>
		<h2><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>

	<div class="minimog-product style-list-02">
		<?php foreach ( $upsells as $upsell ) : ?>

			<?php
			$post_object = get_post( $upsell->get_id() );
			setup_postdata( $GLOBALS['post'] =& $post_object );
			?>
			<?php wc_get_template_part( 'content', 'product-list-02' ); ?>

		<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</div>
