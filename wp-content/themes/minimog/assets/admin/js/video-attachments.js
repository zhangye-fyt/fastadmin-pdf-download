(
	function( $ ) {
		'use strict';

		$( document ).ready( function() {
			setupMediaUpload();
		} );

		/**
		 * Setup media gallery for attaching media/video to image.
		 */
		function setupMediaUpload() {
			var $formPostType = $( 'form#post #post_type' );

			if ( $formPostType.length > 0 && 'attachment' === $formPostType.val() ) {
				setupMediaUploadForAttachmentPage();
				setup360SpriteUploadForAttachmentPage();
				setupGalleryUploadForAttachmentPage();
			} else {
				setupMediaUploadForProductAndLibraryPage();
				setup360SpriteUploadForProductAndLibraryPage();
				setupGalleryUploadForProductAndLibraryPage();
			}
		}

		function setupMediaUploadForAttachmentPage( $button ) {
			$( document.body ).on( 'click', '.minimog-video-upload', function( event ) {
				event.preventDefault();

				var $button       = $( this ),
				    attachment_id = $button.data( 'image-id' );

				// If the media frame already exists, reopen it.
				if ( wp.media.frames.minimog_product_video_media ) {
					wp.media.frames.minimog_product_video_media.attachment_id = attachment_id;
					wp.media.frames.minimog_product_video_media.open();
					return;
				}

				// Create the media frame.
				wp.media.frames.minimog_product_video_media = wp.media( {
					// Set the title of the modal.
					title: 'Select MP4',
					button: {
						text: 'Attach MP4'
					},
					library: {
						type: 'video/mp4'
					},
					multiple: false
				} );

				// When an image is selected, run a callback.
				wp.media.frames.minimog_product_video_media.on( 'select', function() {
					var selected_media = wp.media.frames.minimog_product_video_media.state().get( 'selection' ),
					    $media_field   = $( '#attachments-' + wp.media.frames.minimog_product_video_media.attachment_id + '-minimog_product_video' );

					selected_media.map( function( attachment ) {
						attachment = attachment.toJSON();

						$media_field.val( attachment.url ).change();
						return false;
					} );
				} );

				// Finally, open the modal.
				wp.media.frames.minimog_product_video_media.attachment_id = attachment_id;
				wp.media.frames.minimog_product_video_media.open();

				return false;
			} );
		}

		function setupMediaUploadForProductAndLibraryPage() {
			$( document.body ).on( 'click', '.minimog-video-upload', function( event ) {
				var $button       = $( this ),
				    attachment_id = $button.data( 'image-id' );

				event.preventDefault();

				// Set original frame each time, as there's numerous places
				// to trigger opening the media library.
				wp.media.frames.original = wp.media.frame;

				wp.media.frames.original.close();
				// Stop watching for uploads.
				wp.media.frames.original.state().deactivate();

				// If the media frame already exists, reopen it.
				if ( wp.media.frames.minimog_product_video_media ) {
					wp.media.frames.minimog_product_video_media.attachment_id = attachment_id;
					wp.media.frames.minimog_product_video_media.open();
					return;
				}

				// Create the media frame.
				wp.media.frames.minimog_product_video_media = wp.media( {
					// Set the title of the modal.
					title: 'Select MP4',
					button: {
						text: 'Attach MP4'
					},
					library: {
						type: 'video/mp4'
					},
					multiple: false
				} );

				// When an image is selected, run a callback.
				wp.media.frames.minimog_product_video_media.on( 'select', function() {
					// Open the original media library and activate watching for uploads.
					wp.media.frames.original.state().activate();
					wp.media.frames.original.open();
					// Reassign original window to the frame.
					wp.media.frame = wp.media.frames.original;
					// Select image to attach media to.
					if ( wp.media.frames.original.state().get( 'selection' ) ) { // returns false in media library page.
						wp.media.frames.original.state().get( 'selection' ).add( wp.media.attachment( wp.media.frames.minimog_product_video_media.attachment_id ) );
					}

					var selected_media = wp.media.frames.minimog_product_video_media.state().get( 'selection' ),
					    $media_field   = $( '#attachments-' + wp.media.frames.minimog_product_video_media.attachment_id + '-minimog_product_video' );

					selected_media.map( function( attachment ) {
						attachment = attachment.toJSON();
						$media_field.val( attachment.url ).change();

						return false;
					} );
				} );

				// Finally, open the modal.
				wp.media.frames.minimog_product_video_media.attachment_id = attachment_id;
				wp.media.frames.minimog_product_video_media.open();

				return false;
			} );
		}

		function setup360SpriteUploadForProductAndLibraryPage() {
			$( document.body ).on( 'click', '.minimog-product-360-sprite-upload', function( event ) {
				var $button       = $( this ),
				    attachment_id = $button.data( 'image-id' );

				event.preventDefault();

				// Set original frame each time, as there's numerous places
				// to trigger opening the media library.
				wp.media.frames.original = wp.media.frame;

				wp.media.frames.original.close();
				// Stop watching for uploads.
				wp.media.frames.original.state().deactivate();

				// If the media frame already exists, reopen it.
				if ( wp.media.frames.minimog_360_sprite ) {
					wp.media.frames.minimog_360_sprite.attachment_id = attachment_id;
					wp.media.frames.minimog_360_sprite.open();
					return;
				}

				// Create the media frame.
				wp.media.frames.minimog_360_sprite = wp.media( {
					title: 'Select Image',
					button: {
						text: 'Attach Image'
					},
					library: {
						type: 'image'
					},
					multiple: false
				} );

				// When an image is selected, run a callback.
				wp.media.frames.minimog_360_sprite.on( 'select', function() {
					// Open the original media library and activate watching for uploads.
					wp.media.frames.original.state().activate();
					wp.media.frames.original.open();
					// Reassign original window to the frame.
					wp.media.frame = wp.media.frames.original;
					// Select image to attach media to.
					if ( wp.media.frames.original.state().get( 'selection' ) ) { // returns false in media library page.
						wp.media.frames.original.state().get( 'selection' ).add( wp.media.attachment( wp.media.frames.minimog_360_sprite.attachment_id ) );
					}

					var selected_media = wp.media.frames.minimog_360_sprite.state().get( 'selection' ),
					    $input_field   = $( '#attachments-' + wp.media.frames.minimog_360_sprite.attachment_id + '-minimog_360_source_sprite' );

					selected_media.map( function( attachment ) {
						attachment = attachment.toJSON();

						$input_field.val( attachment.id ).change();

						return false;
					} );
				} );

				// Finally, open the modal.
				wp.media.frames.minimog_360_sprite.attachment_id = attachment_id;
				wp.media.frames.minimog_360_sprite.open();

				return false;
			} );

			$( document.body ).on( 'click', '.minimog-product-360-sprite-clear', function( event ) {
				event.preventDefault();

				var $button       = $( this ),
				    attachment_id = $button.data( 'image-id' ),
				    $input        = $( '#attachments-' + attachment_id + '-minimog_360_source_sprite' );

				$button.hide();
				$input.val( '' ).trigger( 'change' );
			} );
		}

		function setup360SpriteUploadForAttachmentPage( $button ) {
			$( document.body ).on( 'click', '.minimog-product-360-sprite-upload', function( event ) {
				event.preventDefault();

				var $button       = $( this ),
				    attachment_id = $button.data( 'image-id' );

				// If the media frame already exists, reopen it.
				if ( wp.media.frames.minimog_360_sprite ) {
					wp.media.frames.minimog_360_sprite.attachment_id = attachment_id;
					wp.media.frames.minimog_360_sprite.open();
					return;
				}

				// Create the media frame.
				wp.media.frames.minimog_360_sprite = wp.media( {
					title: 'Select Image',
					button: {
						text: 'Attach Image'
					},
					library: {
						type: 'image'
					},
					multiple: false
				} );

				// When an image is selected, run a callback.
				wp.media.frames.minimog_360_sprite.on( 'select', function() {
					var selected_media = wp.media.frames.minimog_360_sprite.state().get( 'selection' ),
					    $input_field   = $( '#attachments-' + wp.media.frames.minimog_360_sprite.attachment_id + '-minimog_360_source_sprite' );

					selected_media.map( function( attachment ) {
						attachment = attachment.toJSON();

						$input_field.val( attachment.id ).change();
						return false;
					} );
				} );

				// Finally, open the modal.
				wp.media.frames.minimog_360_sprite.attachment_id = attachment_id;
				wp.media.frames.minimog_360_sprite.open();

				return false;
			} );
		}

		function setupGalleryUploadForAttachmentPage( $button ) {
			$( document.body ).on( 'click', '.minimog-product-360-upload', function( event ) {
				event.preventDefault();

				var $button       = $( this ),
				    attachment_id = $button.data( 'image-id' ),
				    $input        = $( '#attachments-' + attachment_id + '-minimog_product_360' );

				// If the media frame already exists, reopen it.
				if ( wp.media.frames.minimog_360_media ) {
					wp.media.frames.minimog_360_media.attachment_id = attachment_id;
					wp.media.frames.minimog_360_media.open();
					return;
				}

				var gallery_ids = [];
				var hasImage = false;
				var action = 'add';

				if ( '' !== $input.val() ) {
					var attachments = JSON.parse( $input.val() );
					gallery_ids = _.pluck( attachments, 'id' );
					hasImage = true;
				}

				var frameStates = {
					add: 'gallery-library',
					edit: 'gallery-edit'
				};

				// Create the media frame.
				var options = {
					// Set the title of the modal.
					frame: 'post',
					title: wp.media.view.l10n.editGalleryTitle,
					button: {
						text: 'Insert Image'
					},
					library: {
						type: 'image'
					},
					multiple: true,
					state: 'gallery-edit',
					editing: true,
				};

				if ( hasImage ) {
					options.selection = fetchSelection( gallery_ids );
					action = 'edit';
				}

				options.state = frameStates[ action ];

				wp.media.frames.minimog_360_media = wp.media( options );

				// When an image is selected, run a callback.
				wp.media.frames.minimog_360_media.on( 'update', function( selection ) {
					var images = [];
					selection.each( function( image ) {
						images.push( {
							id: image.get( 'id' ),
							url: image.get( 'url' )
						} );
					} );

					$input.val( JSON.stringify( images ) );
				} );

				// Finally, open the modal.
				wp.media.frames.minimog_360_media.attachment_id = attachment_id;
				wp.media.frames.minimog_360_media.open();

				return false;
			} );

			$( document.body ).on( 'click', '.minimog-product-360-clear', function( event ) {
				event.preventDefault();

				var $button       = $( this ),
				    attachment_id = $button.data( 'image-id' ),
				    $input        = $( '#attachments-' + attachment_id + '-minimog_product_360' );

				$button.hide();
				$input.val( '' );
			} );
		}

		function setupGalleryUploadForProductAndLibraryPage() {
			$( document.body ).on( 'click', '.minimog-product-360-upload', function( event ) {
				console.clear();
				event.preventDefault();
				var $button       = $( this ),
				    attachment_id = $button.data( 'image-id' ),
				    $input        = $( '#attachments-' + attachment_id + '-minimog_product_360' );

				// Set original frame each time, as there's numerous places
				// to trigger opening the media library.
				wp.media.frames.original = wp.media.frame;

				wp.media.frames.original.close();
				// Stop watching for uploads.
				wp.media.frames.original.state().deactivate();

				// If the media frame already exists, reopen it.
				if ( wp.media.frames.minimog_360_media ) {
					wp.media.frames.minimog_360_media.attachment_id = attachment_id;
					wp.media.frames.minimog_360_media.open();
					return;
				}

				var gallery_ids = [];
				var hasImage = false;
				var action = 'add';

				if ( '' !== $input.val() ) {
					var attachments = JSON.parse( $input.val() );
					gallery_ids = _.pluck( attachments, 'id' );
					hasImage = true;
				}

				var frameStates = {
					add: 'gallery-library',
					edit: 'gallery-edit'
				};

				// Create the media frame.
				var options = {
					// Set the title of the modal.
					frame: 'post',
					title: wp.media.view.l10n.editGalleryTitle,
					button: {
						text: 'Insert Image'
					},
					library: {
						type: 'image'
					},
					multiple: true,
					state: 'gallery-edit',
					editing: true,
				};

				if ( hasImage ) {
					options.selection = fetchSelection( gallery_ids );
					action = 'edit';
				}

				options.state = frameStates[ action ];

				wp.media.frames.minimog_360_media = wp.media( options );

				// When an image is selected, run a callback.
				wp.media.frames.minimog_360_media.on( 'update', function( selection ) {
					// Open the original media library and activate watching for uploads.
					wp.media.frames.original.state().activate();
					wp.media.frames.original.open();
					// Reassign original window to the frame.
					wp.media.frame = wp.media.frames.original;
					// Select image to attach media to.
					if ( wp.media.frames.original.state().get( 'selection' ) ) { // returns false in media library page.
						wp.media.frames.original.state().get( 'selection' ).add( wp.media.attachment( wp.media.frames.minimog_360_media.attachment_id ) );
					}

					var images = [];
					selection.each( function( image ) {
						images.push( {
							id: image.get( 'id' ),
							url: image.get( 'url' )
						} );
					} );

					$input = $( '#attachments-' + wp.media.frames.minimog_360_media.attachment_id + '-minimog_product_360' );
					$input.val( JSON.stringify( images ) ).change();

					return false;
				} );

				// Finally, open the modal.
				wp.media.frames.minimog_360_media.attachment_id = attachment_id;
				wp.media.frames.minimog_360_media.open();

				return false;
			} );
		}

		function fetchSelection( ids ) {
			var attachments = wp.media.query( {
				orderby: 'post__in',
				order: 'ASC',
				type: 'image',
				perPage: - 1,
				post__in: ids
			} );

			return new wp.media.model.Selection( attachments.models, {
				props: attachments.props.toJSON(),
				multiple: true
			} );
		}

	}( jQuery )
);
