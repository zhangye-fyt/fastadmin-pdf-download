/**
 * Functions for archive pages.
 */
(
	function( $ ) {
		'use strict';

		var $body             = $( 'body' ),
		    $pageSidebars     = $( '.page-sidebar' ),
		    Helpers           = window.minimog.Helpers,
		    collapseDuration  = 250,
		    COLLAPSED_CLASS   = 'collapsed',
		    COLLAPSIBLE_CLASS = 'sidebar-widgets-collapsible';

		$( document ).ready( function() {
			initArchiveLayoutSwitcher();
			initArchiveSidebarWidgetsCollapsible();
			handlerScrollInfinite();
			initSelect2LayeredNavDropdown();

			$( document.body ).on( 'MinimogGridLayoutColumnsChange', updateLayoutSwitcherState );

			$( document.body ).on( 'click', '.minimog-wp-widget-filter .filter-link', function( evt ) {
				evt.preventDefault();

				var $link = $( this ),
				    $item = $link.parent( 'li' ),
				    $list = $link.closest( 'ul' ),
				    href  = $link.attr( 'href' );

				if ( $list.hasClass( 'single-choice' ) ) {
					if ( $item.hasClass( 'chosen' ) ) {
						return;
					}

					$item.addClass( 'chosen' ).siblings().removeClass( 'chosen' );
				} else {
					$link.parent( 'li' ).toggleClass( 'chosen' );
				}

				var $thisSidebar = $( this ).closest( '.page-sidebar' );
				if ( $thisSidebar.length > 0 ) {
					$body.removeClass( 'off-sidebar-opened' );
					$thisSidebar.removeClass( 'off-sidebar-active' );
					Helpers.unsetBodyOverflow();
				}

				filterProductsByUrl( href );
			} );

			$( document.body ).on( 'click', '.js-product-filter-link', function( evt ) {
				evt.preventDefault();

				var $link = $( this );

				if ( $link.hasClass( 'disabled' ) ) {
					return;
				}

				var href = $link.attr( 'href' );

				filterProductsByUrl( href );
			} );

			$( document.body ).on( 'change', '.minimog-wp-widget-product-layered-nav-dropdown', function() {
				$( this ).closest( 'form' ).trigger( 'submit' );
			} );

			$( document.body ).on( 'submit', '.minimog-wp-widget-product-layered-nav-form', function() {
				var $form       = $( this ),
				    $filterName = $form.find( '.filter-name' ),
				    $queryType  = $form.find( '.filter-query-type' ),
				    filterVal   = $filterName.val(),
				    href        = $form.attr( 'action' );

				href = Helpers.addUrlParam( href, $filterName.attr( 'name' ), filterVal );

				if ( $queryType.length > 0 ) {
					if ( '' === filterVal ) {
						href = Helpers.removeUrlParam( href, $queryType.attr( 'name' ) );
					} else {
						href = Helpers.addUrlParam( href, $queryType.attr( 'name' ), $queryType.val() );
					}
				}

				filterProductsByUrl( href );

				return false;
			} );

			$( document.body ).on( 'submit', '.form-product-price-filter', function() {
				var $form    = $( this ),
				    minPrice = $form.find( '#min_price' ).val(),
				    maxPrice = $form.find( '#max_price' ).val(),
				    href     = $form.attr( 'action' );

				href = Helpers.addUrlParam( href, 'min_price', minPrice );
				href = Helpers.addUrlParam( href, 'max_price', maxPrice );

				filterProductsByUrl( href );

				return false;
			} );

			$( document.body ).on( 'click', '.woocommerce-pagination a.page-numbers', function( evt ) {
				evt.preventDefault();

				filterProductsByUrl( $( this ).attr( 'href' ), { scrollTop: 1 } );
			} );

			$( document.body ).on( 'click', '.shop-load-more-button', function( evt ) {
				evt.preventDefault();
				filterProductsByUrl( $( this ).attr( 'data-url' ), { loadMore: 1 } );
			} );

			$( document.body ).on( 'change', '.js-product-ordering select.orderby', function() {
				var url = Helpers.addUrlParam( window.location.href, 'orderby', $( this ).val() );

				filterProductsByUrl( url );
			} );
		} );

		$( document.body ).on( 'minimog_get_product_fragments_loaded', function() {
			initSelect2LayeredNavDropdown();

			$( document.body ).trigger( 'init_price_filter' );
		} );

		// Use Select2 enhancement if possible.
		function initSelect2LayeredNavDropdown() {
			if ( typeof $.fn.selectWoo !== 'function' ) {
				return;
			}

			$( '.minimog-wp-widget-product-layered-nav-dropdown' ).each( function() {
				var $dropdown = $( this );

				$dropdown.selectWoo( {
					placeholder: $dropdown.data( 'placeholder' ),
					minimumResultsForSearch: 5,
					width: '100%',
					allowClear: ! $dropdown.attr( 'multiple' ),
					language: {
						noResults: function() {
							return $minimog.i18l.noMatchesFound;
						}
					}
				} ).on( 'select2:unselecting', function( evt ) {
					// We need close dropdown on unselecting to avoid bug on re-init.
					$( this ).on( "select2:opening.cancelOpen", function( evt ) {
						evt.preventDefault();

						$( this ).off( "select2:opening.cancelOpen" );
					} );
				} );
			} );
		}

		function updateLayoutSwitcherState( evt, $grid, oldColumns, newColumns ) {
			var $layoutSwitcher = $( '#archive-layout-switcher' );

			if ( 0 >= $layoutSwitcher.length ) {
				return;
			}

			var $currentItem = $layoutSwitcher.find( '.switcher-item[data-columns=' + newColumns + ']' );
			if ( $currentItem.length > 0 ) {
				$currentItem.siblings().removeClass( 'selected' );
				$currentItem.addClass( 'selected' );
			}
		}

		function filterProductsByUrl( url, options = {} ) {
			var settings = $.extend( true, {}, {
				loadMore: 0,
				scrollTop: 0
			}, options );

			url = decodeURIComponent( url );

			if ( ! settings.loadMore ) {
				history.pushState( {}, null, url );
			}

			var $btnLoadMore = $( '.shop-load-more-button' );

			$( document.body ).trigger( 'minimog_getting_product' );

			$.ajax( {
				url: url,
				type: 'GET',
				dataType: 'html',
				success: function( response ) {
					var $response = $( response );

					var $gridWrapper = $( '#minimog-main-post' );

					if ( ! settings.loadMore ) {
						$gridWrapper.children( '.minimog-grid' ).children( '.grid-item' ).remove();
					}

					var $newItems = $response.find( '#minimog-main-post .grid-item' );
					$gridWrapper.MinimogGridLayout( 'update', $newItems );

					var fragments = [
						'.woocommerce-result-count',
						'.woocommerce-pagination',
						'.form-product-price-filter',
						'#active-filters-bar',
					];

					var $filterWidgets = $( '.minimog-wp-widget-filter' );

					$filterWidgets.each( function() {
						var id = $( this ).attr( 'id' );
						fragments.push( '#' + id );
					} );

					var totalFragments = fragments.length;

					for ( var i = 0; i < totalFragments; i ++ ) {
						var key  = fragments[ i ],
						    $key = $( key );

						if ( $key.length > 0 ) {
							$key.empty();
							var $newElement = $response.find( key );
							if ( $newElement.length > 0 ) {
								//$key.replaceWith( $newElement );
								$key.html( $newElement.html() );
							}
						}
					}

					$( document.body ).trigger( 'minimog_get_product_fragments_loaded', [ $response ] );

					if ( settings.scrollTop ) {
						var offsetTop = $gridWrapper.offset().top;
						offsetTop -= 198; // Header + topbar + filter bar.
						offsetTop = Math.max( 0, offsetTop );

						$( 'html, body' ).animate( { scrollTop: offsetTop }, 300 );
					}

					// Disable collapse if it open before.
					$pageSidebars.each( function() {
						var $thisSidebar = $( this );
						if ( $thisSidebar.hasClass( COLLAPSIBLE_CLASS ) ) {
							$thisSidebar.find( '.widget:not(.' + COLLAPSED_CLASS + ')' ).find( '.widget-content' ).stop().slideDown( collapseDuration );
						}
					} );

					// Update Widget Scrollable size.
					if ( $.fn.perfectScrollbar && ! Helpers.isHandheld() ) {
						$pageSidebars.find( '.widget-scrollable' ).each( function() {
							$( this ).find( '.widget-content-inner' ).perfectScrollbar( 'update' );
						} );
					}
				},
				beforeSend: function() {
					if ( settings.loadMore ) {
						Helpers.setElementHandling( $btnLoadMore );
					} else {
						Helpers.setBodyHandling();
					}
				},
				complete: function() {
					if ( settings.loadMore ) {
						Helpers.unsetElementHandling( $btnLoadMore );
					} else {
						Helpers.setBodyCompleted();
					}
				}
			} );
		}

		function handlerScrollInfinite() {
			var $el = $( '.woocommerce-pagination' );

			if ( 'infinite' !== $el.data( 'type' ) ) {
				return;
			}

			var lastST = 0;
			var $window = $( window );

			$window.on( 'scroll', function() {
				var currentST = $( this ).scrollTop();

				// Scroll down only.
				if ( currentST > lastST ) {
					var windowHeight = $window.height(),
					    // 90% window height.
					    halfWH       = parseInt( 90 / 100 * windowHeight ),
					    elOffsetTop  = $el.offset().top,
					    elHeight     = $el.outerHeight( true ),
					    offsetTop    = elOffsetTop + elHeight,
					    finalOffset  = offsetTop - halfWH;

					if ( currentST >= finalOffset ) {
						var $button = $el.find( '.shop-load-more-button' );

						if ( ! $button.hasClass( 'updating-icon' ) ) {
							$button.trigger( 'click' );
						}
					}
				}

				lastST = currentST;
			} );
		}

		function initArchiveLayoutSwitcher() {
			var $layoutSwitcher = $( '#archive-layout-switcher' ),
			    $gridWrapper    = $( '.minimog-main-post' ),
			    SELECTED_CLASS  = 'selected';

			$layoutSwitcher.on( 'click', '.switcher-item', function( evt ) {
				evt.preventDefault();

				var $item = $( this );

				if ( $item.hasClass( SELECTED_CLASS ) ) {
					return;
				}

				$item.siblings().removeClass( SELECTED_CLASS );
				$item.addClass( SELECTED_CLASS );

				var newLayout = $item.data( 'layout' );
				var newGridOptions = {};

				$gridWrapper.addClass( 'layout-switching' );

				switch ( newLayout ) {
					case 'grid-one':
						newGridOptions.columns = 1;
						newGridOptions.disableColumnChange = true;
						$gridWrapper.addClass( 'style-list' );
						break;
					case 'grid-two':
						newGridOptions.columns = 2;
						newGridOptions.disableColumnChange = false;
						$gridWrapper.removeClass( 'style-list' );
						break;
					case 'grid-three':
						newGridOptions.columns = 3;
						newGridOptions.disableColumnChange = false;
						$gridWrapper.removeClass( 'style-list' );
						break;
					case 'grid-four':
						newGridOptions.columns = 4;
						newGridOptions.disableColumnChange = false;
						$gridWrapper.removeClass( 'style-list' );
						break;
					case 'grid-five':
						newGridOptions.columns = 5;
						newGridOptions.disableColumnChange = false;
						$gridWrapper.removeClass( 'style-list' );
						break;
				}

				$gridWrapper.MinimogGridLayout( 'updateLayout', newGridOptions );
				$gridWrapper.on( 'MinimogGridLayoutResized', function() {
					$gridWrapper.removeClass( 'layout-switching' );
				} );
			} );
		}

		function initArchiveSidebarWidgetsCollapsible() {
			if ( 0 >= $pageSidebars.length ) {
				return;
			}

			$pageSidebars.find( '.widget' ).each( function() {
				var $widget = $( this );

				if ( $widget.hasClass( 'widget_block' ) ) { // Fix block group.
					return true;
				}

				// Wrap content with new div.
				$widget.children().not( '.widget-title, .widgettitle, .widget-instance, .widget-content' ).wrapAll( '<div class="widget-content"><div class="widget-content-inner"></div></div>' );

				if ( $widget.hasClass( 'widget-scrollable' ) && $.fn.perfectScrollbar && ! Helpers.isHandheld() ) {
					$widget.find( '.widget-content-inner' ).perfectScrollbar();
				}
			} );

			$pageSidebars.each( function() {
				var $thisSidebar = $( this );
				if ( $thisSidebar.hasClass( COLLAPSIBLE_CLASS ) ) {
					$thisSidebar.on( 'click', '.widget-title, .widgettitle', function( evt ) {
						var $widget = $( this ).closest( '.widget' );
						if ( $widget.hasClass( COLLAPSED_CLASS ) ) {
							$widget.removeClass( COLLAPSED_CLASS );
							$widget.find( '.widget-content' ).stop().slideDown( collapseDuration );
						} else {
							$widget.addClass( COLLAPSED_CLASS );
							$widget.find( '.widget-content' ).stop().slideUp( collapseDuration );
						}
					} );
				}
			} );
		}

	}( jQuery )
);
