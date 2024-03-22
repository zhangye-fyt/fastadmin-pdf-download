jQuery( document ).ready( function( $ ) {
	'use strict';

	var $body = $( 'body' );

	$body.on( 'click', '.kungfu-media-open', function( e ) {
		e.preventDefault();

		var $parent      = $( this ).parents( '.kungfu-attach-wrap' ).first(),
		    kungfu_attach_frame,
		    $mediaImage  = $parent.children( '.kungfu-media-image' ),
		    $media_input = $parent.find( '.kungfu-media' );

		// If the frame already exists, re-open it.
		if ( kungfu_attach_frame ) {
			kungfu_attach_frame.open();
			return;
		}

		kungfu_attach_frame = wp.media.frames.kungfu_attach_frame = wp.media( {
			title: 'Insert Media',
			button: {
				text: 'Select'
			},
			className: 'media-frame kungfu-media-frame',
			frame: 'select',
			multiple: false,
			library: {
				type: 'image'
			}
		} );

		kungfu_attach_frame.on( 'select', function() {
			var attachment = kungfu_attach_frame.state().get( 'selection' ).first().toJSON();
			$parent.find( '.kungfu-media-remove' ).show();
			$mediaImage.html( '<img src="' + attachment.url + '" />' );

			$media_input.val( attachment.id ).trigger( 'change' );
		} );

		// Finally, open up the frame, when everything has been set.
		kungfu_attach_frame.open();
	} );

	// REMOVE MEDIA
	$body.on( 'click', '.kungfu-media-remove', function( e ) {
		e.preventDefault();

		var $parent      = $( this ).parents( '.kungfu-attach-wrap' ).first(),
		    $mediaImage  = $parent.children( '.kungfu-media-image' ),
		    $media_input = $parent.find( '.kungfu-media' );

		$mediaImage.empty();
		$media_input.val( '' );
		$( this ).hide();
	} );
} );
