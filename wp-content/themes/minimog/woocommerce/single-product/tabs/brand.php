<?php
/**
 * Brand tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$terms = Minimog_Woo::instance()->get_product_brands( $product->get_id() );

if ( empty( $terms ) ) {
	return;
}
?>
<div class="product-brand-tab-list">
	<?php foreach ( $terms as $term ): ?>
		<?php
		$logo         = get_term_meta( $term->term_id, 'thumbnail_id', true );
		$url          = get_term_meta( $term->term_id, 'url', true );
		$archive_link = get_term_link( $term );
		?>
		<div class="product-brand-item">
			<?php if ( ! empty( $logo ) ): ?>
				<div class="product-brand-logo-wrap">
					<div class="product-brand-logo">
						<?php echo Minimog_Image::get_attachment_by_id( [
							'id'   => $logo,
							'size' => '350x9999',
						] ) ?>
					</div>
					<a class="product-brand-archive-link" href="<?php echo esc_url( $archive_link ); ?>">
						<?php esc_html_e( 'More Products', 'minimog' ); ?>
					</a>
				</div>
			<?php endif; ?>
			<div class="product-brand-info">
				<h4 class="product-brand-name"><?php echo esc_html( $term->name ); ?></h4>
				<?php if ( ! empty( $term->description ) ) : ?>
					<div class="product-brand-description"><?php echo wp_kses_post( $term->description ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
