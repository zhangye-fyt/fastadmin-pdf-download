/* global $minimog */
/* global $minimogPopup */
(
	function( $ ) {
		'use strict';

		var Helpers     = window.minimog.Helpers,
		    Storage     = window.minimog.Storage,
		    $window     = $( window ),
		    shouldOpen  = true,
		    rules       = $minimogPopup.rules,
		    $promoPopup = $( '#modal-promo-popup' );

		if ( Storage.isSupported ) {
			var pageViews = Storage.get( 'pageViews', 0 );
			Storage.set( 'pageViews', ++ pageViews );
		}

		if ( '1' === rules.byTimes.enable ) {
			var popupTimes = Storage.get( 'popupTimes', 0 );

			if ( popupTimes >= rules.byTimes.times ) {
				shouldOpen = false;
			}
		}

		if ( '1' === rules.byPageViews.enable ) {
			var viewed = Storage.get( 'pageViews', 0 );

			if ( viewed <= rules.byPageViews.reach ) {
				shouldOpen = false;
			}
		}

		if ( ! shouldOpen ) {
			return;
		}

		if ( '1' === $minimogPopup.onLoad.enable ) {
			$window.on( 'load', function() {
				if ( $minimogPopup.onLoad.delay > 0 ) {
					setTimeout( function() {
						$( document.body ).trigger( 'MinimogPromoPopupOpen' );
					}, $minimogPopup.onLoad.delay * 1000 );
				} else {
					$( document.body ).trigger( 'MinimogPromoPopupOpen' );
				}
			} );
		}

		if ( '1' === $minimogPopup.onClick.enable ) {
			var clickTimes = 0;

			$( document ).on( 'click', addClickHandler );

			$promoPopup.on( 'MinimogModalOpen', function( evt ) {
				$( document ).off( 'click', addClickHandler );
			} );
		}

		if ( '1' === $minimogPopup.onScrolling.enable ) {
			var lastST = 0;

			$window.on( 'scroll', addScrollingHandler );

			$promoPopup.on( 'MinimogModalOpen', function() {
				$window.off( 'scroll', addScrollingHandler );
			} );
		}

		$promoPopup.on( 'click', '.btn-copy', addCopyHandler );

		$promoPopup.on( 'MinimogModalClose', function() {
			$promoPopup.off( 'click', '.btn-copy', addClickHandler() );
			updatePopupState();
		} );

		function updatePopupState() {
			if ( Storage.isSupported ) {
				var popupTimes = Storage.get( 'popupTimes', 0 );
				Storage.set( 'popupTimes', ++ popupTimes );
			}
		}

		function addClickHandler() {
			clickTimes ++;
			if ( clickTimes >= $minimogPopup.onClick.clickTimes ) {
				$( document.body ).trigger( 'MinimogPromoPopupOpen' );
			}
		}

		function addScrollingHandler() {
			var currentST     = $( this ).scrollTop(),
			    direction     = currentST > lastST ? 'down' : 'up',
			    docHeight     = $( document ).height(),
			    winHeight     = $( window ).height(),
			    scrollPercent = (
				                    currentST
			                    ) / (
				                    docHeight - winHeight
			                    ),
			    offset        = Math.round( scrollPercent * 100 );

			offset = 'up' === direction ? 100 - offset : offset;

			if ( direction === $minimogPopup.onScrolling.direction && offset >= $minimogPopup.onScrolling.offset ) {
				$( document.body ).trigger( 'MinimogPromoPopupOpen' );
			}

			lastST = currentST;
		}

		function addCopyHandler( evt ) {
			evt.preventDefault();
			var $button = $( this ),
			    result  = Helpers.copyToClipboard( $button.siblings( 'input' ).val() );

			if ( result ) {
				$button.text( $button.data( 'message-success' ) );
			}
		}

		$( document.body ).one( 'MinimogPromoPopupOpen', function() {
			$promoPopup.MinimogModal( 'open' );
		} );
	}( jQuery )
);
