/*global woosw_vars */
(
	function( $ ) {
		'use strict';

		var Helpers = window.minimog.Helpers;

		$( document.body ).on( 'woosw_wishlist_show', function() {
			Helpers.setBodyOverflow();
		} );

		$( document.body ).on( 'woosw_wishlist_hide', function() {
			Helpers.unsetBodyOverflow();
		} );

		$( document.body ).on( 'woosw_add', function( evt, productID ) {
			updateButtonTooltip( productID, true );
		} );

		$( document.body ).on( 'woosw_remove', function( evt, productID ) {
			updateButtonTooltip( productID, false );
		} );

		$( document.body ).on( 'woosw_buttons_refreshed', function( evt, items ) {
			$.each( items, function( itemID, settings ) {
				var productID = typeof settings.parent !== 'undefined' && settings.parent > 0 ? settings.parent : itemID;

				updateButtonTooltip( productID, true );
			} );
		} );

		function updateButtonTooltip( productID, inWishlist = false ) {
			var $thisButton = $( '.woosw-btn-' + productID ),
			    $parent     = $thisButton.parent( '.wishlist-btn' );

			if ( $parent.length > 0 ) {
				inWishlist ? $parent.attr( 'data-hint', woosw_vars.button_text_added ) : $parent.attr( 'data-hint', woosw_vars.button_text );
			}
		}
	}( jQuery )
);
