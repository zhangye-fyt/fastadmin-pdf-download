(
	function( $ ) {
		'use strict';

		var $body         = $( 'body' ),
		    $popupFlyCart = $( '#popup-fly-cart' ),
		    ACTIVE_CLASS  = 'popup-fly-cart-opened',
		    Helpers       = window.minimog.Helpers;

		$( document ).ready( function() {
			initFlyCart();
		} );

		$( document.body ).on( 'wc_fragments_loaded wc_fragments_refreshed', function() {
			unblock_loading();

			update_cart_goal();
			init_cart_countdown();

			if ( $body.hasClass( ACTIVE_CLASS ) ) {
				Helpers.handleLazyImages( $popupFlyCart );
				handle_cart_body_scrollbar();
			}
		} );

		$( document.body ).on( 'removed_coupon applied_coupon', update_cart_goal );

		$( window ).on( 'load', init_cart_countdown );

		function initFlyCart() {
			$( document.body ).on( 'click', '.mini-cart__button', function( evt ) {
				if ( $popupFlyCart.length > 0 ) {
					evt.preventDefault();

					open_fly_cart();
				}
			} );

			$body.on( 'added_to_cart', function( evt, fragments, cart_hash, $button ) {
				/**
				 * Fix Elementor Pro trigger this hook with poor params.
				 */
				if ( typeof $button === 'undefined' ) {
					return;
				}

				// Close quick view modal when product adding to cart.
				var $quickViewModal = $button.closest( '.modal-quick-view-popup' );
				if ( $quickViewModal.length > 0 && $.fn.MinimogModal ) {
					$quickViewModal.MinimogModal( 'close' );
				}

				// Close wishlist modal when product adding to cart.
				var $wishlistModal = $button.closest( '#woosw_wishlist' );
				if ( $wishlistModal.length > 0 ) {
					$wishlistModal.removeClass( 'woosw-show' );
					$( document.body ).trigger( 'woosw_wishlist_hide' );
				}

				if ( $popupFlyCart.length > 0 && 'open_cart_drawer' === $minimogWoo.add_to_cart_behaviour ) {
					open_fly_cart();
				}
			} );

			$( '#btn-close-fly-cart' ).on( 'click', function( evt ) {
				evt.preventDefault();

				close_fly_cart();
			} );

			$popupFlyCart.on( 'click', function( e ) {
				if ( e.target !== this ) {
					return;
				}

				close_fly_cart();
			} );

			$( document ).on( 'click', '.fly-cart-addon-modal-toggle', function( evt ) {
				evt.preventDefault();
				var targetSelector = $( this ).data( 'target' );

				var $flyCartModal = $( targetSelector );

				if ( $flyCartModal.length > 0 ) {
					var modalTemplate = $flyCartModal.data( 'minimog-template' );
					if ( ! $flyCartModal.hasClass( 'template-loaded' ) && modalTemplate ) {
						$.ajax( {
							url: Helpers.getAjaxUrl( 'template_lazyload' ),
							type: 'GET',
							data: {
								template: modalTemplate,
								context: 'wc_cart'
							},
							dataType: 'json',
							cache: false,
							success: function( response ) {
								if ( response.success ) {
									$flyCartModal.html( response.data.template );

									if ( $flyCartModal.hasClass( 'modal-shipping-calculator' ) ) {
										$( document.body ).trigger( 'country_to_state_changed' ); // Trigger select2 to load.
									}

									$popupFlyCart.addClass( 'modal-open' );
									$flyCartModal.addClass( 'open' );
									$flyCartModal.addClass( 'template-loaded' );
								}
							},
							beforeSend: function() {
								block_loading();
							},
							complete: function() {
								unblock_loading()
							},
						} );
					} else {
						$popupFlyCart.addClass( 'modal-open' );

						$flyCartModal.addClass( 'open' );
					}
				}
			} );

			$popupFlyCart.on( 'click', '.btn-close-fly-cart-modal', function( evt ) {
				evt.preventDefault();

				close_addon_modal();
			} );

			$popupFlyCart.on( 'click', '.js-remove-from-cart', remove_from_cart );

			var timeout;
			$popupFlyCart.on( 'change', '.qty', function() {
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

			$popupFlyCart.on( 'submit', '.form-fly-cart-order-notes', save_order_notes );

			// Shipping calculate.
			$( document ).on( 'change', 'select.shipping_method, :input[name^=shipping_method]', shipping_method_selected );

			$( document ).on( 'submit', 'form.fly-cart-shipping-calculator', shipping_calculator_submit );
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
				url: $minimogWoo.wc_ajax_url.toString().replace( '%%endpoint%%', 'remove_from_cart' ),
				data: {
					cart_item_key: $thisButton.data( 'cart_item_key' )
				},
				dataType: 'json',
				beforeSend: function() {
					block_loading();
				},
				success: function( response ) {
					if ( ! response || ! response.fragments ) {
						window.location = $thisButton.attr( 'href' );
						return;
					}

					$( document.body ).trigger( 'removed_from_cart', [
						response.fragments, response.cart_hash, $thisButton
					] );

					unblock_loading();
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
				success: function( response ) {
					cart_reload();

					$( document.body ).trigger( 'minimog_update_qty', [ cart_item_key, cart_item_qty ] );
				},
				beforeSend: function() {
					block_loading();
				},
				complete: function() {

				}
			} );
		}

		function save_order_notes() {
			var $form         = $( this );
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
					close_addon_modal();
				}
			} );

			return false;
		}

		function open_fly_cart() {
			Helpers.setBodyOverflow();

			$body.addClass( ACTIVE_CLASS );

			handle_cart_wrap_scrollbar();

			Helpers.handleLazyImages( $popupFlyCart );

			handle_cart_body_scrollbar();

			if ( ! $popupFlyCart.hasClass( 'opened' ) ) {
				//block_loading(); Move it to below event.
				$( document.body ).trigger( 'minimog_fly_cart_first_open', [ $popupFlyCart ] );
			}

			$popupFlyCart.addClass( 'opened' ); // Used this class to load content on first time.
		}

		function close_fly_cart() {
			Helpers.unsetBodyOverflow();

			$body.removeClass( ACTIVE_CLASS );
		}

		function is_fly_cart_opening() {
			if ( $body.hasClass( ACTIVE_CLASS ) ) {
				return true;
			}

			return false;
		}

		function close_addon_modal() {
			$popupFlyCart.find( '.fly-cart-addon-modal' ).removeClass( 'open' );
			$popupFlyCart.removeClass( 'modal-open' );
		}

		function cart_reload() {
			$( document.body ).trigger( 'wc_fragment_refresh' );
		}

		function handle_cart_wrap_scrollbar() {
			if ( ! $.fn.perfectScrollbar || Helpers.isHandheld() ) { // Use default scrollbar.
				return;
			}

			// Add delay to fixed wrong calculate of scrollbar on first load.
			setTimeout( function() {
				$popupFlyCart.find( '.fly-cart-wrap' ).perfectScrollbar();
			}, 100 );
		}

		function handle_cart_body_scrollbar() {
			var offsetTop   = $popupFlyCart.offset().top; // Admin bar.
			var $cartHeader = $popupFlyCart.find( '.fly-cart-header' );
			var $cartBody   = $popupFlyCart.find( '.fly-cart-body' );
			var $cartFooter = $popupFlyCart.find( '.fly-cart-footer' );
			var windowH     = window.innerHeight;

			// 20 is spacing from body to footer.
			var bodyH = windowH - $cartHeader.outerHeight() - $cartFooter.outerHeight() - 20 - offsetTop;
			bodyH     = Math.max( bodyH, 400 );

			$cartBody.outerHeight( bodyH );

			if ( $.fn.perfectScrollbar && ! Helpers.isHandheld() ) {
				$cartBody.perfectScrollbar( {
					wheelPropagation: true
				} );
			}
		}

		function block_loading() {
			$popupFlyCart.find( '.fly-cart-messages' ).slideUp( 300, function() {
				$popupFlyCart.find( '.fly-cart-messages' ).empty();
			} );
			$popupFlyCart.removeClass( 'loaded' ).addClass( 'loading' );
		}

		function unblock_loading() {
			$popupFlyCart.removeClass( 'loading' ).addClass( 'loaded' );
		}

		/**
		 * Clone from wc-cart
		 * Handles when a shipping method is selected.
		 */
		function shipping_method_selected() {
			var shipping_methods = {};

			var $wrapper = $( this ).closest( '.woocommerce-shipping-methods' );

			// eslint-disable-next-line max-len
			$wrapper.find( 'select.shipping_method, :input[name^=shipping_method][type=radio]:checked, :input[name^=shipping_method][type=hidden]' ).each( function() {
				shipping_methods[$( this ).data( 'index' )] = $( this ).val();
			} );

			var data = {
				security: $minimog.nonce,
				shipping_method: shipping_methods,
				action: 'minimog_update_shipping_method'
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
				},
				beforeSend: function() {
					if ( is_fly_cart_opening() ) {
						block_loading();
					} else {
						Helpers.setBodyHandling();
					}
				},
				complete: function() {
					if ( is_fly_cart_opening() ) {
						unblock_loading();
					} else {
						Helpers.setBodyCompleted();
					}
					$( document.body ).trigger( 'updated_shipping_method' );
				}
			} );
		}

		/**
		 * Clone from wc-cart
		 * Handles a shipping calculator form submit.
		 *
		 * @param {Object} evt The JQuery event.
		 */
		function shipping_calculator_submit( evt ) {
			evt.preventDefault();

			close_addon_modal();
			block_loading();

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
				complete: function() {
					unblock_loading();
				}
			} );
		}

		function update_cart_goal() {
			var $cartData = $( '.cart-data-info' ).first();
			if ( $cartData.length > 0 ) {
				var cartData = $cartData.data( 'value' );

				if ( 0 >= cartData.count ) {
					$body.addClass( 'cart-is-empty' );
				} else {
					$body.removeClass( 'cart-is-empty' );
				}

				if ( typeof cartData.free_shipping_class_only === 'number' && 1 === cartData.free_shipping_class_only ) {
					$body.addClass( 'cart-includes-only-free-shipping-class' );
				} else {
					$body.removeClass( 'cart-includes-only-free-shipping-class' );
				}
			}

			$( '.cart-goal-percent' ).each( function() {
				var $parent   = $( this ).closest( '.cart-goal' ),
				    $progress = $parent.find( '.cart-goal-progress .progress-bar' ),
				    newAmount = parseFloat( $( this ).val() );

				setTimeout( function() {
					$progress.css( {
						width: newAmount + '%'
					} );

					newAmount < 100 ? $parent.removeClass( 'cart-goal-done shakeY' ) : $parent.addClass( 'cart-goal-done shakeY' );
				}, 300 );
			} );
		}

		function init_cart_countdown() {
			var $cartData = $( '.cart-data-info' ).first();

			if ( 0 >= $cartData.length || ! $.fn.MinimogCountdownTimer ) {
				return;
			}

			var cartData = $cartData.data( 'value' );

			$( '.cart-countdown-timer' ).each( function() {
				var $timer     = $( this ),
				    $countdown = $timer.find( '.timer' ),
				    settings   = $timer.data( 'countdown' ),
				    startTime  = Date.now(),
				    endTime    = startTime + settings.length * 60 * 1000;

				var options = {
					startTime: startTime,
					endTime: endTime,
					addZeroPrefix: true,
					formatter: settings.formatter,
					loop: settings.loop,
					callback: () => {
						$timer.find( '.cart-countdown-message' ).text( settings.expired_message );
					}
				};

				// Reset countdown on cart empty.
				if ( 0 >= cartData.count ) {
					$countdown.MinimogCountdownTimer( options );
					$countdown.MinimogCountdownTimer( 'clear' );

					return true;
				}

				$countdown.MinimogCountdownTimer( options );
			} );
		}
	}( jQuery )
);
