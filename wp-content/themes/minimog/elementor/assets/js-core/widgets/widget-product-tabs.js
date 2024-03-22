(
	function( $ ) {
		'use strict';

		var MinimogProductTabs = function( $scope, $ ) {
			$scope.find( '.minimog-tabs' ).each( function() {
				var $tabPanel   = $( this ),
				    tabSettings = {};

				$tabPanel.children( '.minimog-tabs__content' ).children( '.tab-content' ).each( function() {
					var $thisTab = $( this );

					$thisTab.find( '.tm-tab-product-element' ).each( function() {
						var $component = $( this );

						if ( $component.hasClass( 'minimog-grid-wrapper' ) ) {
							$component.MinimogGridLayout();

							$component.on( 'MinimogGridLayoutResized', function() {
								$tabPanel.MinimogTabPanel( 'updateLayout' );
							} );
						} else if ( $component.hasClass( 'tm-swiper' ) ) {
							$component.MinimogSwiper();

							if ( $thisTab.hasClass( 'active' ) && $component.hasClass( 'group-style-10' ) ) {
								/**
								 * Need to re-calculate because slide class visible maybe wrong when slides has drop shadow (margin, padding)
								 */
								setTimeout( function() {
									var swiper = $component.find( '.swiper-container' )[ 0 ].swiper;
									swiper.update();
								}, 200 );
							}
						}
					} );
				} );

				if ( $tabPanel.hasClass( 'minimog-tabs--nav-type-dropdown' ) ) {
					tabSettings.navType = 'dropdown';
				}

				$tabPanel.MinimogTabPanel( tabSettings );
			} );

			$( document.body ).on( 'MinimogTabChange', function( e, $tabPanel, $newTabContent ) {
				if ( ! $newTabContent.hasClass( 'ajax-loaded' ) ) {
					loadProductData( $tabPanel, $newTabContent );

					$newTabContent.addClass( 'ajax-loaded' );
				}
			} );

			function loadProductData( $tabPanel, $currentTab ) {
				var $component = $currentTab.find( '.tm-tab-product-element' ),
				    layout     = $currentTab.data( 'layout' ),
				    query      = $currentTab.data( 'query' );

				query.action = 'get_product_tabs';

				$.ajax( {
					url: $minimog.ajaxurl,
					type: 'GET',
					data: query,
					dataType: 'json',
					cache: true,
					success: function( response ) {
						var result = response.data;

						if ( ! result.found ) {
							$component.remove();
							$currentTab.find( '.minimog-grid-response-messages' ).html( result.template );
						} else {
							if ( 'grid' === layout ) {
								var $grid = $component.children( '.minimog-grid' );
								$grid.children().not( '.grid-sizer' ).remove();
								$component.MinimogGridLayout( 'update', $( result.template ) );
							} else {
								var swiper = $component.children( '.swiper-inner' ).children( '.swiper-container' )[ 0 ].swiper;
								swiper.removeAllSlides();
								swiper.appendSlide( result.template );
								swiper.update();

								var llImages = $component.find( '.ll-image' );

								if ( llImages.length > 0 ) {
									llImages.laziestloader( {}, function() {
										$( this ).unwrap( '.minimog-lazy-image' );
									} ).trigger( 'laziestloader' );
								}

								var autoplay = $currentTab.attr( 'data-slider-autoplay' );

								if ( autoplay && autoplay !== '' ) {
									swiper.params.autoplay.enabled = true;
									swiper.params.autoplay.delay = parseInt( autoplay );
									swiper.params.autoplay.disableOnInteraction = false;
									swiper.autoplay.start();
								}
							}

							$currentTab.find( '.loop-product-variation-selector' ).each( function() {
								$( this ).find( '.term-link' ).first().trigger( 'click' );
							} );
						}

						$tabPanel.MinimogTabPanel( 'updateLayout' );
					}
				} );
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product-tabs.default', MinimogProductTabs );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-carousel-product-tabs.default', MinimogProductTabs );
		} );
	}
)( jQuery );
