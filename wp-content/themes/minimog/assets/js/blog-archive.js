/**
 * Functions for archive pages.
 */
(
	function( $ ) {
		'use strict';

		var Helpers = window.minimog.Helpers;

		$( document ).ready( function() {
			handlerScrollInfinite();

			$( document.body ).on( 'click', '.minimog-grid-pagination a.page-numbers', function( evt ) {
				evt.preventDefault();

				var $link = $( this ),
				    href  = $link.attr( 'href' ),
				    url   = href.includes( window.location.origin ) ? href : window.location.origin + href;

				filterPostsByUrl( url );
			} );

			$( document.body ).on( 'click', '.archive-load-more-button', function( evt ) {
				evt.preventDefault();

				var $button = $( this ),
				    href    = $button.attr( 'data-url' ),
				    url     = href.includes( window.location.origin ) ? href : window.location.origin + href;

				filterPostsByUrl( url, true );
			} );
		} );

		function filterPostsByUrl( url, loadMore = false ) {
			url            = decodeURIComponent( url );
			/**
			 * We need send base url to render pagination links.
			 */
			const urlParts = new URL( url );
			var baseUrl    = urlParts.pathname + urlParts.search;

			if ( ! loadMore ) {
				history.pushState( {}, null, url );
			}

			var data = Helpers.getUrlParamsAsObject( url );

			data.action   = 'minimog_get_posts';
			data.base_url = baseUrl;

			var $queryVars = $( '#minimog-main-post-query' );
			if ( $queryVars.length > 0 ) {
				var queryVars = $queryVars.data( 'query' );
				jQuery.extend( data, queryVars );
			}

			var $filterWidgets = $( '.minimog-wp-widget-filter' );

			var showingWidgets = [];

			$filterWidgets.each( function() {
				var id              = $( this ).attr( 'id' );
				var $widgetInstance = $( this ).find( '.widget-instance' );

				if ( $widgetInstance.length > 0 ) {
					var instance = $widgetInstance.data( 'instance' );
					var name     = $widgetInstance.data( 'name' );

					showingWidgets.push( {
						'id': '#' + id,
						'name': name,
						'instance': instance
					} );
				}
			} );

			data.widgets = showingWidgets;

			var $btnLoadMore = $( '.archive-load-more-button' );

			$.ajax( {
				url: $minimog.ajaxurl,
				type: 'GET',
				data: data,
				dataType: 'json',
				cache: true,
				success: function( response ) {
					var $gridWrapper = $( '#minimog-main-post' );

					if ( ! loadMore ) {
						$gridWrapper.children( '.minimog-grid' ).children( '.grid-item' ).remove();
					}

					var html      = response.template;
					var $newItems = $( $.parseHTML( html ) );

					$gridWrapper.MinimogGridLayout( 'update', $newItems );

					if ( response.fragments ) {
						$.each( response.fragments, function( key, value ) {
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

						// Update Widget Scrollable size.
						if ( $.fn.perfectScrollbar && ! Helpers.isHandheld() ) {
							$( '.page-sidebar' ).find( '.widget-scrollable' ).each( function() {
								$( this ).find( '.widget-content-inner' ).perfectScrollbar( 'update' );
							} );
						}

						$( document.body ).trigger( 'minimog_get_product_fragments_loaded' );
					}
				},
				beforeSend: function() {
					if ( loadMore ) {
						Helpers.setElementHandling( $btnLoadMore );
					} else {
						Helpers.setBodyHandling();
					}
				},
				complete: function() {
					if ( loadMore ) {
						Helpers.unsetElementHandling( $btnLoadMore );
					} else {
						Helpers.setBodyCompleted();
					}
				}
			} );
		}

		function handlerScrollInfinite() {
			var $el = $( '.minimog-grid-pagination' );

			if ( 'infinite' !== $el.data( 'type' ) ) {
				return;
			}

			var lastST  = 0;
			var $window = $( window );

			$window.on( 'scroll', function() {
				var currentST = $( this ).scrollTop();

				// Scroll down only.
				if ( currentST > lastST ) {
					var windowHeight = $window.height();
					// 90% window height.
					var halfWH       = 90 / 100 * windowHeight;
					halfWH           = parseInt( halfWH );

					var elOffsetTop = $el.offset().top;
					var elHeight    = $el.outerHeight( true );
					var offsetTop   = elOffsetTop + elHeight;
					var finalOffset = offsetTop - halfWH;

					if ( currentST >= finalOffset ) {
						var $button = $el.find( '.archive-load-more-button' );

						if ( ! $button.hasClass( 'updating-icon' ) ) {
							$button.trigger( 'click' );
						}
					}
				}

				lastST = currentST;
			} );
		}

	}( jQuery )
);
