(
	function( $ ) {
		'use strict';

		var Helpers = window.minimog.Helpers;

		var $cartTable = $( '#cart-table-wrap' );

		$( document ).ready( function() {
			initCart();
		} );

		$( document.body ).on( 'wc_fragments_loaded wc_fragments_refreshed', function() {
			Helpers.handleLazyImages( $cartTable );
			Helpers.setBodyCompleted();
		} );

		$( '#modal-cart-order-notes' ).on( 'submit', 'form', save_order_notes );

		$( document ).on( 'submit', 'form.fly-cart-shipping-calculator', shipping_calculator_submit );

		$( document ).on( 'click', '.btn-remove-from-cart', remove_from_cart );

		function initCart() {
			var timeout;
			$cartTable.on( 'change', '.qty', function() {
				var $qty = $( this );

				if ( timeout !== undefined ) {
					clearTimeout( timeout );
				}

				timeout = setTimeout( function() {
					var itemKey = $qty.attr( 'name' );
					var itemQty = $qty.val();

					update_quantity( itemKey, itemQty );
				}, 500 );
			} );
		}

		function remove_from_cart( evt ) {
			/**
			 * Check if Enable AJAX add to cart buttons on archives.
			 */
			if ( typeof wc_add_to_cart_params === 'undefined' ) {
				return;
			}

			evt.preventDefault();

			var $thisButton = $( this );

			$.ajax( {
				type: 'POST',
				url: Helpers.getAjaxUrl( 'remove_from_cart' ),
				data: {
					cart_item_key: $thisButton.data( 'cart_item_key' )
				},
				dataType: 'json',
				beforeSend: function() {
					Helpers.setBodyHandling();
				},
				success: function( response ) {
					if ( ! response || ! response.fragments ) {
						window.location = $thisButton.attr( 'href' );
						return;
					}

					// Updated cart content.
					if ( response.output ) {
						$.each( response.output, function( key, value ) {
							$( key ).replaceWith( value );
						} );
					}

					$( document.body ).trigger( 'removed_from_cart', [
						response.fragments,
						response.cart_hash,
						$thisButton
					] );

					Helpers.setBodyCompleted();
					Helpers.handleLazyImages( $cartTable );
				},
				error: function() {
					window.location = $thisButton.attr( 'href' );
					return;
				}
			} );
		}

		function update_quantity( cart_item_key, cart_item_qty ) {
			var data = {
				action: 'minimog_update_product_quantity',
				cart_item_key: cart_item_key,
				cart_item_qty: cart_item_qty,
				security: $minimog.nonce,
			};

			$.ajax( {
				url: $minimog.ajaxurl,
				type: 'POST',
				cache: false,
				dataType: 'json',
				data: data,
				beforeSend: function() {
					Helpers.setBodyHandling();
				},
				error: function() {
					Helpers.setBodyCompleted();
				},
				success: function( response ) {
					if ( response.data && response.data.fragments ) {
						$.each( response.data.fragments, function( key, value ) {
							$( key ).replaceWith( value );
						} );
					}

					$( document.body ).trigger( 'minimog_update_qty', [ cart_item_key, cart_item_qty ] );

					$( document.body ).trigger( 'wc_fragment_refresh' );
				},
			} );
		}

		function save_order_notes() {
			var $form = $( this );
			var $submitButton = $form.find( 'button[type=submit]' );

			var notes = $( this ).find( 'textarea[name="order_comments"]' ).val();

			var data = {
				action: 'minimog_save_order_notes',
				order_notes: notes,
			};

			$.ajax( {
				type: 'POST',
				url: $minimog.ajaxurl,
				data: data,
				dataType: 'json',
				success: function( response ) {
					if ( response.data && response.data.fragments ) {
						$.each( response.data.fragments, function( key, value ) {
							$( key ).replaceWith( value );
						} );
					}

					// Sync notes on other places.
					$( 'textarea[name="order_comments"]' ).val( notes );
				},
				beforeSend: function() {
					Helpers.setElementHandling( $submitButton );
				},
				complete: function() {
					Helpers.unsetElementHandling( $submitButton );
					$form.closest( '.minimog-modal' ).MinimogModal( 'close' );
				}
			} );

			return false;
		}

		/**
		 * Clone from wc-cart
		 * Handles a shipping calculator form submit.
		 *
		 * @param {Object} evt The JQuery event.
		 */
		function shipping_calculator_submit( evt ) {
			evt.preventDefault();

			var $form = $( evt.currentTarget );

			$( '<input />' ).attr( 'type', 'hidden' )
			                .attr( 'name', 'action' )
			                .attr( 'value', 'minimog_calculate_shipping' )
			                .appendTo( $form );

			$.ajax( {
				type: $form.attr( 'method' ),
				url: $minimog.ajaxurl,
				data: $form.serialize(),
				dataType: 'json',
				success: function( response ) {
					if ( response.data.fragments ) {
						$.each( response.data.fragments, function( key, value ) {
							$( key ).empty();

							if ( '' !== value ) {
								/**
								 * Append only children.
								 * Avoid to use replaceWith to lost "key" events
								 */
								var $newElement = $( $.parseHTML( value ) );
								$( key ).html( $newElement.html() );
							}
						} );
					}
				},
				beforeSend: function() {
					$form.closest( '.minimog-modal' ).MinimogModal( 'close' );
					Helpers.setBodyHandling();
				},
				complete: function() {
					Helpers.setBodyCompleted();
				}
			} );
		}
	}( jQuery )
);
