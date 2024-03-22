<?php

namespace Minimog\Woo\Variation_Gallery;

defined( 'ABSPATH' ) || exit;

class Backend {
	protected static $instance = null;

	const META_KEY = 'gallery_images';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'woocommerce_save_product_variation', [ $this, 'save_product_variation' ], 10, 2 );
		add_action( 'woocommerce_product_after_variable_attributes', [
			$this,
			'output_variation_gallery_html',
		], 10, 3 );

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
	}

	public function output_variation_gallery_html( $loop, $variation_data, $variation ) {
		$variation_id    = absint( $variation->ID );
		$gallery_ids     = get_post_meta( $variation_id, self::META_KEY, true );
		$gallery_images  = array();
		$has_images      = false;
		$wrapper_classes = [ 'form-row form-row-full minimog-variation-gallery-wrapper' ];

		if ( ! empty( $gallery_ids ) ) {
			$gallery_images    = explode( ',', $gallery_ids );
			$has_images        = true;
			$wrapper_classes[] = 'gallery-has-images';
		}
		?>
		<div data-product_variation_id="<?php echo esc_attr( $variation_id ) ?>" class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
			<label><?php esc_html_e( 'Variation gallery', 'minimog' ); ?></label>
			<input type="hidden" class="minimog-variation-gallery-ids" name="minimog_variation_gallery[<?php echo esc_attr( $loop ) ?>]" value="<?php echo esc_attr( $gallery_ids ); ?>">
			<div class="minimog-variation-gallery-content">
				<div class="minimog-variation-gallery-images">
					<?php
					if ( $has_images ) : ?>
						<?php foreach ( $gallery_images as $image_id ) : ?>
							<?php
							$image = wp_get_attachment_image_src( $image_id );
							?>
							<div class="minimog-variation-gallery-thumbnail" style="background-image: url(<?php echo esc_url( $image[0] ) ?>)"></div>
						<?php endforeach; ?>
					<?php endif; ?>
					<button class="minimog-add-variation-gallery-image" aria-label="<?php esc_attr_e( 'Add variation gallery images', 'minimog' ); ?>">
						<span class="dashicons dashicons-plus-alt2"></span></button>
				</div>
			</div>
			<div class="minimog-clear-variation-gallery-image">
				<a href="#"><?php esc_html_e( 'Clear gallery', 'minimog' ); ?></a>
			</div>
		</div>
		<?php
	}

	public function save_product_variation( $variation_id, $loop ) {
		if ( isset( $_POST['minimog_variation_gallery'] ) && $_POST['minimog_variation_gallery'][ $loop ] ) {
			update_post_meta( $variation_id, self::META_KEY, sanitize_text_field( $_POST['minimog_variation_gallery'][ $loop ] ) );
		} else {
			delete_post_meta( $variation_id, self::META_KEY );
		}
	}

	public function admin_scripts() {
		global $post, $pagenow;

		if ( $post && ( 'product' === get_post_type( $post->ID ) && ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'minimog-wc-variation-gallery', MINIMOG_THEME_ASSETS_URI . '/admin/js/variation-gallery.js', [ 'jquery' ], MINIMOG_THEME_VERSION, true );
		}
	}
}

Backend::instance()->initialize();
