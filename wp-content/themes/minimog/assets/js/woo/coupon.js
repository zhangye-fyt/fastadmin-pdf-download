(
	function( $ ) {
		'use strict';

		window.minimog = window.minimog || {};

		var $body         = $( 'body' ),
		    $popupFlyCart = $( '#popup-fly-cart' ),
		    Helpers       = window.minimog.Helpers;

		minimog.WC_Coupon = {
			init: function() {
				var self = this;

				$( document ).on( 'submit', '.form-coupon', function( evt ) {
					var $form = $( this );
					var couponCode = $form.find( 'input[name="coupon_code"]' ).val();

					$form.closest( '.minimog-modal' ).MinimogModal( 'close' );

					self.apply_coupon( couponCode );

					return false;
				} );

				$( document ).on( 'click', '.apply-coupon-link', function( evt ) {
					evt.preventDefault();

					var $link = $( this );

					if ( $link.hasClass( 'coupon-selected' ) ) {
						return;
					}

					if ( $link.hasClass( 'coupon-disabled' ) ) {
						return;
					}

					var $modal = $link.closest( '.minimog-modal' );
					if ( $.fn.MinimogModal && 0 < $modal.length ) {
						$modal.MinimogModal( 'close' );
					}

					var couponCode = $( this ).attr( 'data-coupon' );

					self.apply_coupon( couponCode );

					return false;
				} );

				$( document ).on( 'click', '.remove-coupon-link', { WC_Coupon: self }, self.remove_coupon );
			},
			apply_coupon: function( couponCode ) {
				var self = this;

				if ( '' === couponCode ) {
					return false;
				}

				var data = {
					action: 'minimog_apply_coupon',
					coupon_code: couponCode,
					security: $minimogWoo.apply_coupon_nonce
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

						if ( $minimogWoo.is_checkout ) {
							$( document.body ).trigger( 'applied_coupon_in_checkout', [ couponCode ] );
							$( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
						} else {
							$( document.body ).trigger( 'applied_coupon', [ couponCode ] );
						}
					},
					beforeSend: function() {
						if ( self.is_fly_cart_opening() ) {
							self.close_fly_cart_modal();
							self.block_loading();
						} else {
							Helpers.setBodyHandling();
						}
					},
					complete: function() {
						if ( self.is_fly_cart_opening() ) {
							self.unblock_loading();
						} else {
							Helpers.setBodyCompleted();
						}
					}
				} );
			},
			remove_coupon: function( evt ) {
				evt.preventDefault();

				var self = evt.data.WC_Coupon;

				var couponCode = $( this ).attr( 'data-coupon' );

				var data = {
					action: 'minimog_remove_coupon',
					coupon_code: couponCode,
					security: $minimogWoo.remove_coupon_nonce
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

						if ( $minimogWoo.is_checkout ) {
							$( document.body ).trigger( 'removed_coupon_in_checkout', [ couponCode ] );
							$( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
						} else {
							$( document.body ).trigger( 'removed_coupon', [ couponCode ] );
						}
					},
					beforeSend: function() {
						if ( self.is_fly_cart_opening() ) {
							self.block_loading();
						} else {
							Helpers.setBodyHandling();
						}
					},
					complete: function() {
						if ( self.is_fly_cart_opening() ) {
							self.unblock_loading();
						} else {
							Helpers.setBodyCompleted();
						}
					}
				} );
			},
			block_loading: function() {
				$popupFlyCart.find( '.fly-cart-messages' ).slideUp( 300, function() {
					$popupFlyCart.find( '.fly-cart-messages' ).empty();
				} );
				$popupFlyCart.removeClass( 'loaded' ).addClass( 'loading' );
			},
			unblock_loading: function() {
				$popupFlyCart.removeClass( 'loading' ).addClass( 'loaded' );
			},
			is_fly_cart_opening: function() {
				return $body.hasClass( 'popup-fly-cart-opened' );
			},
			close_fly_cart_modal: function() {
				$popupFlyCart.find( '.fly-cart-addon-modal' ).removeClass( 'open' );
				$popupFlyCart.removeClass( 'modal-open' );
			}
		};

		$( document ).ready( function() {
			minimog.WC_Coupon.init();
		} );
	}( jQuery )
);
