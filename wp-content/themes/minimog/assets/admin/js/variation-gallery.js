(
	function( $ ) {
		'use strict';

		$( document ).ready( function() {
			$( document.body ).on( 'click', '.minimog-add-variation-gallery-image', function( evt ) {
				evt.preventDefault();

				var $wrap      = $( this ).closest( '.minimog-variation-gallery-wrapper' ),
				    $input     = $wrap.find( '.minimog-variation-gallery-ids' ),
				    galleryVal = $input.val(),
				    galleryIds = galleryVal.split( ',' ),
				    hasImages  = '' !== galleryVal,
				    thisFrame,
				    action     = hasImages ? 'edit' : 'add';

				openFrame( action );

				function openFrame( action ) {
					initFrame( action );
				}

				function initFrame( action ) {
					var frameStates = {
						create: 'gallery',
						add: 'gallery-library',
						edit: 'gallery-edit'
					};
					var options = {
						frame: 'post',
						multiple: true,
						state: frameStates[ action ],
						button: {
							text: 'Insert Media'
						}
					};

					if ( hasImages ) {
						options.selection = fetchSelection();
					}

					thisFrame = wp.media( options );

					thisFrame.on( {
						update: select,
						'menu:render:default': menuRender,
						'content:render:browse': gallerySettings
					}, this );

					thisFrame.open();
				}

				function select( selection ) {
					var images = [];
					var imagesHTML = '';
					selection.each( function( image ) {
						images.push( image.get( 'id' ) );
						imagesHTML += '<div class="minimog-variation-gallery-thumbnail" style="background-image: url(' + image.get( 'url' ) + ')"></div>'
					} );

					$wrap.find( '.minimog-variation-gallery-images' ).children( '.minimog-variation-gallery-thumbnail' ).remove();
					$wrap.find( '.minimog-variation-gallery-images' ).prepend( imagesHTML );
					$input.val( images.join( ',' ) ).trigger( 'change' );

					if ( images.length > 0 ) {
						$wrap.addClass( 'gallery-has-images' );
					}
				}

				function menuRender( view ) {
					view.unset( 'insert' );
					view.unset( 'featured-image' );
				}

				function gallerySettings( browser ) {
					browser.sidebar.on( 'ready', function() {
						browser.sidebar.unset( 'gallery' );
					} );
				}

				function fetchSelection() {
					var attachments = wp.media.query( {
						orderby: 'post__in',
						order: 'ASC',
						type: 'image',
						perPage: - 1,
						post__in: galleryIds
					} );
					return new wp.media.model.Selection( attachments.models, {
						props: attachments.props.toJSON(),
						multiple: true
					} );
				}
			} );

			$( document.body ).on( 'click', '.minimog-clear-variation-gallery-image a', function( evt ) {
				evt.preventDefault();

				var $wrap = $( this ).closest( '.minimog-variation-gallery-wrapper' );

				$wrap.find( '.minimog-variation-gallery-ids' ).val( '' ).trigger( 'change' );
				$wrap.find( '.minimog-variation-gallery-images' ).children( '.minimog-variation-gallery-thumbnail' ).remove();
				$wrap.removeClass( 'gallery-has-images' );
			} );
		} );

	}( jQuery )
);
