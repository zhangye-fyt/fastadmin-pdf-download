<?php

namespace Minimog\Woo\Admin;

defined( 'ABSPATH' ) || exit;

class Product_Category_Banner {
	protected static $instance = null;

	const TAXONOMY_NAME = 'product_cat';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		// Add meta data fields.
		add_action( self::TAXONOMY_NAME . '_add_form_fields', [ $this, 'add_category_fields' ], 20 );
		add_action( self::TAXONOMY_NAME . '_edit_form_fields', [ $this, 'edit_category_fields' ], 20 );

		// Save meta data.
		add_action( 'created_term', [ $this, 'save_form_fields' ], 10, 3 );
		add_action( 'edit_term', [ $this, 'save_form_fields' ], 10, 3 );
	}

	/**
	 * Category banner field.
	 */
	public function add_category_fields() {
		?>
		<div class="form-field term-banner-wrap">
			<label><?php esc_html_e( 'Banner', 'minimog' ); ?></label>
			<div id="product_cat_banner" style="float: left; margin-right: 10px;">
				<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px"/></div>
			<div style="line-height: 60px;">
				<input type="hidden" id="product_cat_banner_id" name="product_cat_banner_id"/>
				<button type="button" class="upload_banner_button button"><?php esc_html_e( 'Upload/Add image', 'minimog' ); ?></button>
				<button type="button" class="remove_banner_button button"><?php esc_html_e( 'Remove image', 'minimog' ); ?></button>
			</div>
			<script type="text/javascript">

				// Only show the "remove image" button when needed.
				if ( ! jQuery( '#product_cat_banner_id' ).val() ) {
					jQuery( '.remove_banner_button' ).hide();
				}

				// Uploading files.
				var banner_frame;

				jQuery( document ).on( 'click', '.upload_banner_button', function( event ) {

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( banner_frame ) {
						banner_frame.open();
						return;
					}

					// Create the media frame.
					banner_frame = wp.media.frames.downloadable_file = wp.media( {
						title: '<?php esc_html_e( 'Choose an image', 'minimog' ); ?>',
						button: {
							text: '<?php esc_html_e( 'Use image', 'minimog' ); ?>'
						},
						multiple: false
					} );

					// When an image is selected, run a callback.
					banner_frame.on( 'select', function() {
						var attachment = banner_frame.state().get( 'selection' ).first().toJSON();
						var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

						jQuery( '#product_cat_banner_id' ).val( attachment.id );
						jQuery( '#product_cat_banner' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
						jQuery( '.remove_banner_button' ).show();
					} );

					// Finally, open the modal.
					banner_frame.open();
				} );

				jQuery( document ).on( 'click', '.remove_banner_button', function() {
					jQuery( '#product_cat_banner' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
					jQuery( '#product_cat_banner_id' ).val( '' );
					jQuery( '.remove_banner_button' ).hide();
					return false;
				} );

				jQuery( document ).ajaxComplete( function( event, request, options ) {
					if ( request && 4 === request.readyState && 200 === request.status
					     && options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

						var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
						if ( ! res || res.errors ) {
							return;
						}
						// Clear Thumbnail fields on submit.
						jQuery( '#product_cat_banner' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
						jQuery( '#product_cat_banner_id' ).val( '' );
						jQuery( '.remove_banner_button' ).hide();
						// Clear Display type field on submit.
						jQuery( '#display_type' ).val( '' );
						return;
					}
				} );

			</script>
			<div class="clear"></div>
			<p>Upload banner image that display as title bar background.</p>
		</div>
		<?php
	}

	/**
	 * Edit category banner field.
	 *
	 * @param mixed $term Term (category) being edited.
	 */
	public function edit_category_fields( $term ) {
		$banner_id = absint( get_term_meta( $term->term_id, 'banner_id', true ) );

		if ( $banner_id ) {
			$image = wp_get_attachment_thumb_url( $banner_id );
		} else {
			$image = wc_placeholder_img_src();
		}
		?>
		<tr class="form-field term-banner-wrap">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Banner', 'minimog' ); ?></label></th>
			<td>
				<div id="product_cat_banner" style="float: left; margin-right: 10px;">
					<img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px"/></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="product_cat_banner_id" name="product_cat_banner_id" value="<?php echo esc_attr( $banner_id ); ?>"/>
					<button type="button" class="upload_banner_button button"><?php esc_html_e( 'Upload/Add image', 'minimog' ); ?></button>
					<button type="button" class="remove_banner_button button"><?php esc_html_e( 'Remove image', 'minimog' ); ?></button>
				</div>
				<script type="text/javascript">

					// Only show the "remove image" button when needed.
					if ( '0' === jQuery( '#product_cat_banner_id' ).val() ) {
						jQuery( '.remove_banner_button' ).hide();
					}

					// Uploading files.
					var banner_frame;

					jQuery( document ).on( 'click', '.upload_banner_button', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( banner_frame ) {
							banner_frame.open();
							return;
						}

						// Create the media frame.
						banner_frame = wp.media.frames.downloadable_file = wp.media( {
							title: '<?php esc_html_e( 'Choose an image', 'minimog' ); ?>',
							button: {
								text: '<?php esc_html_e( 'Use image', 'minimog' ); ?>'
							},
							multiple: false
						} );

						// When an image is selected, run a callback.
						banner_frame.on( 'select', function() {
							var attachment = banner_frame.state().get( 'selection' ).first().toJSON();
							var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

							jQuery( '#product_cat_banner_id' ).val( attachment.id );
							jQuery( '#product_cat_banner' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
							jQuery( '.remove_banner_button' ).show();
						} );

						// Finally, open the modal.
						banner_frame.open();
					} );

					jQuery( document ).on( 'click', '.remove_banner_button', function() {
						jQuery( '#product_cat_banner' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
						jQuery( '#product_cat_banner_id' ).val( '' );
						jQuery( '.remove_banner_button' ).hide();
						return false;
					} );

				</script>
				<div class="clear"></div>
				<p>Upload banner image that display as title bar background.</p>
			</td>
		</tr>
		<?php
	}

	/**
	 * @param        $term_id
	 * @param string $tt_id
	 * @param string $taxonomy
	 *
	 * Save term meta data
	 */
	public function save_form_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( self::TAXONOMY_NAME !== $taxonomy ) {
			return;
		}

		if ( ! empty( $_POST['product_cat_banner_id'] ) ) {
			update_term_meta( $term_id, 'banner_id', absint( sanitize_text_field( $_POST['product_cat_banner_id'] ) ) );
		} else {
			delete_term_meta( $term_id, 'banner_id' );
		}
	}
}

Product_Category_Banner::instance()->initialize();
