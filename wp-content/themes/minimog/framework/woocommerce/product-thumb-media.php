<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Product_Thumb_Media {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'attachment_fields_to_edit', [ $this, 'attachment_fields_to_edit' ], 10, 2 );
		add_filter( 'attachment_fields_to_save', [ $this, 'attachment_fields_to_save' ], 10, 2 );

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );

		// Disable down size on upload. Product 360 sprite is big image.
		add_filter( 'big_image_size_threshold', '__return_false' );
	}

	public function admin_scripts() {
		global $post, $pagenow;

		$get_page = sanitize_text_field( filter_input( INPUT_GET, 'page' ) );

		if (
			( $post && ( 'product' === get_post_type( $post->ID ) && ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) ) ) ||
			( 'admin.php' === $pagenow && $get_page && $get_page === 'minimog-woothumbs-bulk-edit' ) ||
			( $get_page && ( 'minimog-woothumbs-settings-account' === $get_page || 'minimog-woothumbs-settings' === $get_page ) ) ||
			'upload.php' === $pagenow ||
			( 'post.php' === $pagenow && 'attachment' === get_post_type( $post->ID ) )
		) {
			wp_enqueue_media();
			wp_enqueue_script( 'minimog-product-media-attach', MINIMOG_THEME_ASSETS_URI . '/admin/js/video-attachments.js', [ 'jquery' ], MINIMOG_THEME_VERSION, true );
		}
	}

	/**
	 * Add fields to the $form_fields array.
	 *
	 * @param array  $form_fields
	 * @param object $post
	 *
	 * @return array
	 */
	public function attachment_fields_to_edit( $form_fields, $post ) {
		if ( strpos( $post->post_mime_type, 'image/' ) !== 0 ) {
			return $form_fields;
		}

		$screen = get_current_screen();

		$form_fields['minimog_media_attach_title'] = array(
			'tr' => '<tr><td colspan="2">' . sprintf( '<strong style="font-size: 1.1em; line-height: 30px;">%s</strong>',
					esc_html__( 'Minimog Media Attach', 'minimog' )
				) . '</td></tr>',
		);

		$form_fields['minimog_media_attach_help'] = array(
			'tr' => '<tr><td colspan="2">' . esc_html__( 'Any changes are saved automatically.', 'minimog' ) . '</td></tr>',
		);

		if ( $screen instanceof \WP_Screen && 'post' === $screen->base && 'attachment' === $screen->post_type ) {
			unset( $form_fields['minimog_media_attach_help'] );
		}

		$attach_type      = get_post_meta( $post->ID, 'minimog_product_attachment_type', true );
		$attachment_types = [
			''      => esc_html__( 'None', 'minimog' ),
			'video' => esc_html__( 'Product Video', 'minimog' ),
			'360'   => esc_html__( 'Product 360', 'minimog' ),
		];

		ob_start(); ?>
		<select id="<?php echo 'attachments-' . $post->ID . '-minimog_product_attachment_type' ?>"
		        name="<?php echo 'attachments[' . $post->ID . '][minimog_product_attachment_type]'; ?>">
			<?php foreach ( $attachment_types as $attachment_type => $label ) : ?>
				<option
					value="<?php echo esc_attr( $attachment_type ) ?>" <?php selected( $attach_type, $attachment_type ); ?>><?php echo '' . $label; ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		$attachment_type_html = ob_get_clean();

		$form_fields['minimog_product_attachment_type'] = array(
			'label' => __( 'Media Attach', 'minimog' ),
			'input' => 'html',
			'html'  => $attachment_type_html,
			'value' => $attach_type,
		);

		$video_url = get_post_meta( $post->ID, 'minimog_product_video', true );
		ob_start();
		?>
		<input type="text" class="text"
		       id="<?php echo 'attachments-' . $post->ID . '-minimog_product_video' ?>"
		       name="<?php echo 'attachments[' . $post->ID . '][minimog_product_video]'; ?>"
		       value="<?php echo esc_attr( $video_url ); ?>"/>
		<a href="#" class="minimog-video-upload button-secondary"
		   data-image-id="<?php echo esc_attr( $post->ID ); ?>"><?php esc_html_e( 'Attach MP4', 'minimog' ); ?></a>
		<p class="description" style="width: 100%; padding-top: 4px;">
			<?php echo sprintf( __( 'Enter a <a href="%s" target="_blank">valid media URL</a>, or click "Attach MP4" to upload your own MP4 video into the WordPress media library.', 'minimog' ), esc_url( 'https://wordpress.org/support/article/embeds/#okay-so-what-sites-can-i-embed-from' ) ); ?>
		</p>
		<?php
		$video_html = ob_get_clean();

		$form_fields['minimog_product_video'] = array(
			'label'         => __( 'Video URL', 'minimog' ),
			'input'         => 'html',
			'html'          => $video_html,
			'value'         => $video_url,
			'show_in_edit'  => 'video' === $attach_type,
			'show_in_modal' => 'video' === $attach_type,
		);

		// Product 360 settings.
		$source_sprite    = get_post_meta( $post->ID, 'minimog_360_source_sprite', true );
		$sprite_image_url = \Minimog_Image::get_attachment_url_by_id( [
			'id'   => $source_sprite,
			'size' => '150x150',
		] );

		ob_start();

		$test_class   = 'minimog-360-sprite-image';
		$sprite_style = '';

		if ( ! empty( $sprite_image_url ) ) {
			$sprite_style = ' style="background-image: url(' . $sprite_image_url . ')"';
			$test_class   .= ' minimog-360-sprite-has-image';
		}

		echo '<input type="hidden" class="text" id="attachments-' . $post->ID . '-minimog_360_source_sprite" name="attachments[' . $post->ID . '][minimog_360_source_sprite]" value="' . esc_attr( $source_sprite ) . '" />';
		echo '<div class="' . $test_class . '" ' . $sprite_style . '></div>
			<a href="#" class="minimog-product-360-sprite-upload button-secondary" data-image-id="' . esc_attr( $post->ID ) . '">' . esc_html__( 'Upload Image', 'minimog' ) . '</a>';

		if ( ! empty( $sprite_image_url ) ) {
			echo '<a href="#" class="minimog-product-360-sprite-clear button-secondary button-link-delete" data-image-id="' . esc_attr( $post->ID ) . '">' . esc_html__( 'Delete', 'minimog' ) . '</a>';
		}

		$sprite_image_field_html = ob_get_clean();

		$form_fields['minimog_360_source_sprite'] = array(
			'label'         => __( 'Sprite Image', 'minimog' ),
			'input'         => 'html',
			'html'          => $sprite_image_field_html,
			'value'         => $source_sprite,
			'show_in_edit'  => '360' === $attach_type,
			'show_in_modal' => '360' === $attach_type,
		);

		$form_fields['minimog_360_total_frames'] = array(
			'label'         => __( 'Total Frames', 'minimog' ),
			'input'         => 'text',
			'value'         => get_post_meta( $post->ID, 'minimog_360_total_frames', true ),
			'helps'         => 'Set the total number of frames to show. The 6x6 sprite might contain 36 images, but it only has 34 frames, hence we set it to 34 here.',
			'show_in_edit'  => '360' === $attach_type,
			'show_in_modal' => '360' === $attach_type,
		);

		$form_fields['minimog_360_total_frames_per_row'] = array(
			'label'         => __( 'Frames per row', 'minimog' ),
			'input'         => 'text',
			'value'         => get_post_meta( $post->ID, 'minimog_360_total_frames_per_row', true ),
			'helps'         => 'The 6x6 sprite sheet contains 6 frames in one row.',
			'show_in_edit'  => '360' === $attach_type,
			'show_in_modal' => '360' === $attach_type,
		);

		return $form_fields;
	}

	/**
	 * Save attachment fields.
	 *
	 * @param array $post
	 * @param array $attachment
	 *
	 * @return array
	 */
	public function attachment_fields_to_save( $post, $attachment ) {
		if ( isset( $attachment['minimog_product_attachment_type'] ) ) {
			update_post_meta( $post['ID'], 'minimog_product_attachment_type', $attachment['minimog_product_attachment_type'] );
		}

		// Product Video.
		if ( isset( $attachment['minimog_product_video'] ) ) {
			update_post_meta( $post['ID'], 'minimog_product_video', $attachment['minimog_product_video'] );
		}

		// Product 360.
		if ( isset( $attachment['minimog_360_source_sprite'] ) ) {
			update_post_meta( $post['ID'], 'minimog_360_source_sprite', $attachment['minimog_360_source_sprite'] );
		}

		if ( isset( $attachment['minimog_360_total_frames'] ) ) {
			update_post_meta( $post['ID'], 'minimog_360_total_frames', $attachment['minimog_360_total_frames'] );
		}

		if ( isset( $attachment['minimog_360_total_frames_per_row'] ) ) {
			update_post_meta( $post['ID'], 'minimog_360_total_frames_per_row', $attachment['minimog_360_total_frames_per_row'] );
		}

		return $post;
	}
}

Product_Thumb_Media::instance()->initialize();
