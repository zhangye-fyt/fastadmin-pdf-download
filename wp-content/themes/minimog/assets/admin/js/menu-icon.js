(
	function( $ ) {
		'use strict';

		var minimog_attach_frame;

		$( document ).ready( function() {
			$( document.body ).on( 'click', '.minimog-menu-icon-select', function( evt ) {
				evt.preventDefault();

				var $button = $( this ),
				    itemID  = $button.data( 'item-id' );

				// If the frame already exists, re-open it.
				if ( minimog_attach_frame ) {
					wp.media.frames.minimog_attach_frame.menu_item_id = itemID;
					minimog_attach_frame.open();
					return;
				}

				minimog_attach_frame = wp.media.frames.minimog_attach_frame = wp.media( {
					title: 'Insert Media',
					button: {
						text: 'Select'
					},
					className: 'media-frame',
					frame: 'select',
					multiple: false,
					library: {
						type: [ 'image/svg+xml' ]
					},
					states: [
						new wp.media.controller.Library( {
							title: 'Insert Media',
							library: wp.media.query( {
								type: [ 'image', 'image/svg+xml' ]
							} ),
							multiple: false,
							date: false
						} )
					]
				} );

				minimog_attach_frame.on( 'select', function() {
					var attachment    = minimog_attach_frame.state().get( 'selection' ).first().toJSON(),

					    $itemField    = $( '#field-item-icon-' + wp.media.frames.minimog_attach_frame.menu_item_id ),
					    $image        = $itemField.find( '.minimog-menu-icon-view' ),
					    $input        = $itemField.find( '.minimog-menu-icon-input' ),
					    $removeButton = $itemField.find( '.minimog-menu-icon-remove' );

					$removeButton.show();
					$input.val( attachment.id );
					$image.html( '<img src="' + attachment.url + '" />' ).show();
				} );

				wp.media.frames.minimog_attach_frame.menu_item_id = itemID;
				// Finally, open up the frame, when everything has been set.
				minimog_attach_frame.open();
			} );

			$( document.body ).on( 'click', '.minimog-menu-icon-remove', function( evt ) {
				evt.preventDefault();

				var $button = $( this ),
				    $image  = $( this ).closest( '.field-item-icon' ).find( '.minimog-menu-icon-view' ),
				    $input  = $( this ).closest( '.field-item-icon' ).find( '.minimog-menu-icon-input' );

				$input.val( '' );
				$image.empty().hide();
				$button.hide();
			} );
		} );
	}( jQuery )
);
