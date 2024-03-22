(
	function( $ ) {
		'use strict';

		window.minimogSwiperBP = {
			defaults: {
				361: {
					name: 'mobile_extra'
				},
				576: {
					name: 'tablet'
				},
				768: {
					name: 'tablet_extra'
				},
				992: {
					name: 'laptop'
				},
				1200: {
					name: 'desktop'
				},
				1600: {
					name: 'widescreen'
				}
			}
		};

		$.fn.MinimogSwiper = function( options ) {
			var defaults = {},
			    settings = $.extend( true, {}, defaults, options );

			var $swiper;

			this.each( function() {
				var $slider                 = $( this ),
				    $sliderInner            = $slider.children( '.swiper-inner' ).first(),
				    $sliderContainer        = $sliderInner.children( '.swiper-container' ).first(),
				    sliderSettings          = $slider.data(),
				    items                   = {},
				    itemsGroup              = {},
				    gutter                  = {},
				    speed                   = parseNumberValue( sliderSettings.speed, 500 ),
				    useElementorBreakpoints = $slider.hasClass( 'use-elementor-breakpoints' );

				items.desktop = parseItemValue( sliderSettings.itemsDesktop, 1 );
				items.widescreen = parseItemValue( sliderSettings.itemsWideScreen, items.desktop );
				items.laptop = parseItemValue( sliderSettings.itemsLaptop, items.desktop );
				items.tablet_extra = parseItemValue( sliderSettings.itemsTabletExtra, items.laptop );
				items.tablet = parseItemValue( sliderSettings.itemsTablet, items.tablet_extra );
				items.mobile_extra = parseItemValue( sliderSettings.itemsMobileExtra, items.tablet );
				items.mobile = parseItemValue( sliderSettings.itemsMobile, items.mobile_extra );
				itemsGroup.desktop = parseItemGroupValue( sliderSettings.itemsGroupDesktop, items.desktop, items.desktop ); // Slides Per Group, Default same as Slides Per View.
				itemsGroup.widescreen = parseItemGroupValue( sliderSettings.itemsGroupWideScreen, itemsGroup.desktop, items.widescreen );
				itemsGroup.laptop = parseItemGroupValue( sliderSettings.itemsGroupLaptop, itemsGroup.desktop, items.laptop );
				itemsGroup.tablet_extra = parseItemGroupValue( sliderSettings.itemsGroupTabletExtra, itemsGroup.laptop, items.tablet_extra );
				itemsGroup.tablet = parseItemGroupValue( sliderSettings.itemsGroupTablet, itemsGroup.tablet_extra, items.tablet );
				itemsGroup.mobile_extra = parseItemGroupValue( sliderSettings.itemsGroupMobileExtra, itemsGroup.tablet, items.mobile_extra );
				itemsGroup.mobile = parseItemGroupValue( sliderSettings.itemsGroupMobile, itemsGroup.mobile_extra, items.mobile );
				gutter.desktop = parseNumberValue( sliderSettings.gutterDesktop, 0 ); // Distance between slides.
				gutter.widescreen = parseNumberValue( sliderSettings.gutterWideScreen, gutter.desktop );
				gutter.laptop = parseNumberValue( sliderSettings.gutterLaptop, gutter.desktop );
				gutter.tablet_extra = parseNumberValue( sliderSettings.gutterTabletExtra, gutter.laptop );
				gutter.tablet = parseNumberValue( sliderSettings.gutterTablet, gutter.tablet_extra );
				gutter.mobile_extra = parseNumberValue( sliderSettings.gutterMobileExtra, gutter.tablet );
				gutter.mobile = parseNumberValue( sliderSettings.gutterMobile, gutter.mobile_extra );

				var swiperOptions = $.extend( {}, {
					init: false,
					watchSlidesVisibility: true,
					slidesPerView: items.mobile,
					slidesPerGroup: itemsGroup.mobile,
					spaceBetween: gutter.mobile,
					resizeObserver: true,
					breakpoints: getSwiperBreakpoints( items, itemsGroup, gutter, useElementorBreakpoints )
				}, settings );

				swiperOptions.watchOverflow = true;

				if ( sliderSettings.slideColumns ) {
					swiperOptions.slidesPerColumn = sliderSettings.slideColumns;
				}

				if ( sliderSettings.initialSlide ) {
					swiperOptions.initialSlide = sliderSettings.initialSlide;
				}

				if ( sliderSettings.autoHeight ) {
					swiperOptions.autoHeight = true;
				}

				if ( typeof sliderSettings.simulateTouch !== 'undefined' && ! sliderSettings.simulateTouch ) {
					swiperOptions.simulateTouch = false;
				}

				if ( speed ) {
					swiperOptions.speed = speed;
				}

				// Maybe: fade, flip.
				if ( sliderSettings.effect ) {
					swiperOptions.effect = sliderSettings.effect;

					if ( 'fade' === sliderSettings.effect ) {
						if ( 'custom' === sliderSettings.fadeEffect ) {
							swiperOptions.fadeEffect = {
								crossFade: false
							};
						} else {
							swiperOptions.fadeEffect = {
								crossFade: true
							};
						}
					}
				}

				if ( sliderSettings.loop ) {
					swiperOptions.loop = true;

					if ( sliderSettings.loopedSlides ) {
						swiperOptions.loopedSlides = sliderSettings.loopedSlides;
					}
				}

				if ( sliderSettings.centered ) {
					swiperOptions.centeredSlides = true;
				}

				if ( sliderSettings.autoplay ) {
					swiperOptions.autoplay = {
						delay: sliderSettings.autoplay,
						disableOnInteraction: false
					};

					if ( sliderSettings.autoplayReverseDirection ) {
						swiperOptions.autoplay.reverseDirection = true;
					}
				}

				if ( sliderSettings.freeMode ) {
					swiperOptions.freeMode = true;
				}

				var $wrapControls;

				if ( sliderSettings.wrapControls ) {
					var $wrapControlsWrap = $( '<div class="swiper-controls-wrap"></div>' );
					$wrapControls = $( '<div class="swiper-controls"></div>' );

					$wrapControlsWrap.append( $wrapControls );
					$slider.append( $wrapControlsWrap );
				}

				if ( sliderSettings.nav ) {

					if ( sliderSettings.customNav && sliderSettings.customNav !== '' ) {
						var $customBtn       = $( '#' + sliderSettings.customNav ),
						    $fractionWrapper = $( '.pagination-wrapper', $customBtn ),
						    $swiperPrev      = $customBtn.find( '.slider-prev-btn' ),
						    $swiperNext      = $customBtn.find( '.slider-next-btn' );

						if ( $customBtn.hasClass( 'style-02' ) ) {
							swiperOptions.pagination = {
								el: $fractionWrapper,
								type: 'custom',
								clickable: true
							};

							swiperOptions.pagination.renderCustom = function( swiper, current, total ) {
								return '<div class="fraction"><div class="text">' + $customBtn.data( 'text' ) + '</div><div class="current">' + current + '</div><div class="separator">/</div><div class="total">' + total + '</div></div>';
							};
						} else if ( $customBtn.hasClass( 'style-03' ) || $customBtn.hasClass( 'style-04' ) ) {
							swiperOptions.pagination = {
								el: $fractionWrapper,
								type: 'bullets',
								clickable: true
							};

							swiperOptions.pagination.renderBullet = function( index, className ) {
								return '<span class="' + className + '"></span>';
							};
						}

					} else {
						var $swiperPrev = $( '<div class="swiper-nav-button swiper-button-prev"><i class="nav-button-icon"></i><span class="nav-button-text">' + $minimogSwiper.prevText + '</span></div>' );
						var $swiperNext = $( '<div class="swiper-nav-button swiper-button-next"><i class="nav-button-icon"></i><span class="nav-button-text">' + $minimogSwiper.nextText + '</span></div>' );

						if ( '03' === sliderSettings.navStyle ) {
							var $arrowRightSvg = '<svg width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 8L21.2222 8" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.7773 1L22.7773 8L15.7773 15" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
							var $arrowLeftSvg = '<svg width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23 8L2.77778 8" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.22266 1L1.22266 8L8.22266 15" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
							$swiperPrev = $( '<div class="swiper-nav-button swiper-button-prev">' + $arrowLeftSvg + '</div>' );
							$swiperNext = $( '<div class="swiper-nav-button swiper-button-next">' + $arrowRightSvg + '</div>' );
						}

						var $swiperNavButtons = $( '<div class="swiper-nav-buttons"></div>' );
						$swiperNavButtons.append( $swiperPrev ).append( $swiperNext );

						var $swiperNavButtonsWrap = $( '<div class="swiper-nav-buttons-wrap"></div>' );

						if ( 'grid' == sliderSettings.navAlignedBy ) {
							var navContainerClass = sliderSettings.navGridContainer ? sliderSettings.navGridContainer : 'container';
							$swiperNavButtonsWrap.append( '<div class="' + navContainerClass + '"></div>' );
							$swiperNavButtonsWrap.children( '[class*=container]' ).append( $swiperNavButtons );
						} else {
							$swiperNavButtonsWrap.append( $swiperNavButtons );
						}

						if ( $wrapControls ) {
							$wrapControls.append( $swiperNavButtonsWrap );
						} else {
							$sliderInner.append( $swiperNavButtonsWrap );
						}
					}

					swiperOptions.navigation = {
						nextEl: $swiperNext,
						prevEl: $swiperPrev
					};
				}

				if ( sliderSettings.pagination ) {
					var $swiperPaginationWrap = $( '<div class="swiper-pagination-wrap"><div class="swiper-pagination-inner"></div></div>' );
					var $swiperPagination = $( '<div class="swiper-pagination"></div>' );

					$swiperPaginationWrap.find( '.swiper-pagination-inner' ).append( $swiperPagination );

					var $swiperPaginationContainerWrap = $( '<div class="swiper-pagination-container"></div>' );

					if ( 'grid' == sliderSettings.paginationAlignedBy ) {
						$swiperPaginationContainerWrap.append( '<div class="container"><div class="row"><div class="col-sm-12"></div></div></div>' );
						$swiperPaginationContainerWrap.find( '.col-sm-12' ).append( $swiperPaginationWrap );
					} else {
						$swiperPaginationContainerWrap.append( $swiperPaginationWrap );
					}

					if ( $wrapControls ) {
						$wrapControls.append( $swiperPaginationContainerWrap );
					} else {
						$slider.append( $swiperPaginationContainerWrap );
					}

					swiperOptions.pagination = {
						el: $swiperPagination,
						type: sliderSettings.paginationType ? sliderSettings.paginationType : 'bullets',
						clickable: true
					};

					if ( sliderSettings.paginationDynamicBullets ) {
						swiperOptions.pagination.dynamicBullets = true;
					}

					if ( $slider.hasClass( 'pagination-style-04' ) ) {
						$swiperPaginationWrap.find( '.swiper-pagination-inner' ).append( '<div class="swiper-alt-arrow-button swiper-alt-arrow-prev" data-action="prev"></div><div class="swiper-alt-arrow-button swiper-alt-arrow-next" data-action="next"></div>' );

						swiperOptions.pagination.renderCustom = function( swiper, current, total ) {
							return '<div class="fraction"><div class="text">' + sliderSettings.paginationText + '</div><div class="current">' + current + '</div><div class="separator">/</div><div class="total">' + total + '</div></div>';
						};
					} else if ( $slider.hasClass( 'pagination-style-03' ) ) {
						swiperOptions.pagination.renderCustom = function( swiper, current, total ) {
							return '<div class="fraction"><div class="current">' + addLeadingZero( current ) + '</div><div class="separator"></div><div class="total">' + addLeadingZero( total ) + '</div></div>';
						};
					} else if ( $slider.hasClass( 'pagination-style-06' ) ) {
						swiperOptions.pagination.renderCustom = function( swiper, current, total ) {
							return '<div class="fraction"><div class="current">' + current + '<div class="separator">/</div></div><div class="total">' + total + '</div></div>';
						};
					} else if ( $slider.hasClass( 'pagination-style-07' ) ) {
						swiperOptions.pagination.renderBullet = function( index, className ) {
							return '<span class="' + className + '">' + addLeadingZero( index + 1 ) + '<span class="dot">.</span></span>';
						};
					} else if ( $slider.hasClass( 'pagination-style-08' ) || $slider.hasClass( 'pagination-style-10' ) ) {
						$swiperPaginationWrap.find( '.swiper-pagination-inner' ).append( '<div class="swiper-alt-arrow-button swiper-alt-arrow-prev" data-action="prev"></div><div class="swiper-alt-arrow-button swiper-alt-arrow-next" data-action="next"></div>' );

						swiperOptions.pagination.renderBullet = function( index, className ) {
							return '<span class="' + className + '"></span>';
						};
					}
				}

				if ( sliderSettings.mousewheel ) {
					swiperOptions.mousewheel = {
						enabled: true
					};
				}

				if ( sliderSettings.vertical ) {
					swiperOptions.direction = 'vertical';
				}

				if ( sliderSettings.slideToClickedSlide ) {
					swiperOptions.slideToClickedSlide = true;
					swiperOptions.touchRatio = 0.2;
				}

				$swiper = new Swiper( $sliderContainer, swiperOptions );

				if ( sliderSettings.layerTransition ) {
					$swiper.on( 'init', function() {
						var slides = $swiper.$wrapperEl.find( '.swiper-slide' );
						/**
						 * index = $swiper.activeIndex;
						 * currentSlide = slides.eq( index );
						 *
						 * Work properly if slides per view is greater than 1
						 */
						var currentSlide = $( slides ).filter( '.swiper-slide-visible' );
						currentSlide.addClass( 'animated' );
					} );

					$swiper.on( 'slideChangeTransitionEnd', function() {
						var slides = $swiper.$wrapperEl.find( '.swiper-slide' );
						/**
						 * index = $swiper.activeIndex;
						 * currentSlide = slides.eq( index );
						 *
						 * Work properly if slides per view is greater than 1
						 */
						var visibleSlides = $( slides ).filter( '.swiper-slide-visible' );
						visibleSlides.addClass( 'animated' );

						slides.removeClass( 'swiper-ken-burn-active' );
						visibleSlides.addClass( 'swiper-ken-burn-active' );
					} );

					$swiper.on( 'slideChangeTransitionStart', function() {
						var slides = $swiper.$wrapperEl.find( '.swiper-slide' );
						slides.removeClass( 'animated' );
					} );
				}

				if ( sliderSettings.vertical && sliderSettings.verticalAutoHeight ) {
					$swiper.on( 'init', function() {
						setSlideHeight( this );
					} );

					$swiper.on( 'transitionEnd', function() {
						setSlideHeight( this );
					} );

					$swiper.on( 'resize', function() {
						setSlideHeight( this );
					} );
				}

				$swiper.on( 'resize', function() {
					var slidesPerView = this.params.slidesPerView;

					$( this.$wrapperEl ).attr( 'data-active-items', slidesPerView );
				} );

				/**
				 * Use beforeInit instead of init to avoid broken slider view auto.
				 * Updated: On some cases Normal per views return "auto" instead of real per view on beforeInit
				 * then we needed init event to avoid broken render.
				 */
				$swiper.on( 'beforeInit', function() {
					var slidesPerView = this.params.slidesPerView;
					$( this.$wrapperEl ).attr( 'data-active-items', slidesPerView );
				} );

				$swiper.on( 'init', function() {
					var slidesPerView = this.params.slidesPerView;
					$( this.$wrapperEl ).attr( 'data-active-items', slidesPerView );

					var slides = $swiper.$wrapperEl.find( '.swiper-slide' );
					var visibleSlides = $( slides ).filter( '.swiper-slide-visible' );
					visibleSlides.addClass( 'minimog-slide-active' );
				} );

				$swiper.on( 'slideChangeTransitionEnd', function() {
					var slides = $swiper.$wrapperEl.find( '.swiper-slide' );
					var visibleSlides = $( slides ).filter( '.swiper-slide-visible' );
					visibleSlides.addClass( 'minimog-slide-active' );
				} );

				/**
				 * Hide pagination if slider has only bullet
				 */
				$swiper.on( 'paginationRender', function() {
					var slidesLength = $swiper.virtual && $swiper.params.virtual.enabled ? $swiper.virtual.slides.length : $swiper.slides.length;
					var numberOfBullets = $swiper.params.loop ? Math.ceil( (
						                                                       slidesLength - (
							                                                       $swiper.loopedSlides * 2
						                                                       )
					                                                       ) / $swiper.params.slidesPerGroup ) : $swiper.snapGrid.length;

					var $wrapper = $( $swiper.$wrapperEl ).closest( '.tm-swiper' );
					numberOfBullets > 1 ? $wrapper.removeClass( 'pagination-hidden' ) : $wrapper.addClass( 'pagination-hidden' );
				} );

				if ( sliderSettings.centered ) {
					$swiper.on( 'slideChangeTransitionStart', function() {
						$swiper.$wrapperEl.find( '.swiper-slide' ).removeClass( 'swiper-slide-centered swiper-slide-uncentered' );
					} );

					$swiper.on( 'transitionEnd', function() {
						findCenteredSlides( this, $swiper.$wrapperEl.find( '.swiper-slide' ) );
					} );

					$swiper.on( 'update', function() {
						var swiper = this;

						setTimeout( function() {
							var slides = $swiper.$wrapperEl.find( '.swiper-slide' );
							//slides.removeClass( 'swiper-slide-uncentered swiper-slide-centered' );

							findCenteredSlides( swiper, slides );
						}, 500 ); // Delay to sure swiper layout render completed.
					} );
				}

				// If lazy load + retina enable.
				if ( $.fn.laziestloader ) {
					$slider.elementorWaypoint( function() {
						var _self    = this.element ? this.element : this,
						    $self    = $( _self ),
						    llImages = $self.find( '.ll-image' );

						if ( llImages.length > 0 ) {
							llImages.laziestloader( {}, function() {
								$( this ).unwrap( '.minimog-lazy-image' );
							} ).trigger( 'laziestloader' );
						}

						this.destroy(); // trigger once.
					}, {
						offset: '90%'
					} );
				}

				/**
				 * Center Mode Handler
				 */
				if ( sliderSettings.centeredHightlight && 'scale' === sliderSettings.centeredHightlight ) {
					$swiper.on( 'beforeInit resize', function() {
						setSlideHeightCenterMode( this );
					} );
				}

				$swiper.init();

				$slider.on( 'click', '.swiper-alt-arrow-button', function() {
					'prev' === $( this ).data( 'action' ) ? $swiper.slidePrev() : $swiper.slideNext();
				} );

				$( document ).trigger( 'MinimogSwiperInit', [ $swiper, $slider, swiperOptions ] );
			} );

			return $swiper;
		};

		function getBreakpoints( forElementor = false ) {
			if ( forElementor ) {
				if ( window.minimogSwiperBP.elementor ) {
					return window.minimogSwiperBP.elementor
				} else {
					var breakpoints      = elementorFrontendConfig.responsive.breakpoints,
					    finalBreakpoints = {},
					    previousBP       = 0,
					    lastBP           = '',
					    lastBPValue      = 0;

					for ( const key in breakpoints ) {
						if ( breakpoints.hasOwnProperty( key ) && breakpoints[ key ].is_enabled ) {

							lastBP = key;

							var bpValue = previousBP + 1;

							lastBPValue = bpValue;
							previousBP = breakpoints[ key ].value;

							if ( 'mobile' === key ) {
								continue;
							}

							finalBreakpoints[ bpValue ] = {
								name: key
							};
						}
					}

					if ( 'widescreen' === lastBP ) {
						finalBreakpoints[ lastBPValue ].name = 'desktop';
						finalBreakpoints[ breakpoints[ 'widescreen' ].value ] = {
							name: 'widescreen'
						};
					} else {
						finalBreakpoints[ previousBP + 1 ] = {
							name: 'desktop'
						};
					}

					window.minimogSwiperBP.elementor = finalBreakpoints;

					return window.minimogSwiperBP.elementor;
				}
			} else {
				return window.minimogSwiperBP.defaults;
			}
		}

		function getSwiperBreakpoints( items, itemsGroup, gutter, forElementor = false ) {
			var breakpoints              = getBreakpoints( forElementor ),
			    swiperBreakpointSettings = {};

			for ( const key in breakpoints ) {
				var name = breakpoints[ key ].name;

				swiperBreakpointSettings[ key ] = {
					slidesPerView: items[ name ],
					slidesPerGroup: itemsGroup[ name ],
					spaceBetween: gutter[ name ],
				}
			}

			return swiperBreakpointSettings;
		}

		function addLeadingZero( number ) {
			// Convert to string.
			number = number.toString();

			// Add leading 0.
			return number.padStart( 2, '0' );
		}

		function parseNumberValue( setting = '', defaultValue = '' ) {
			if ( undefined === setting || '' === setting || isNaN( setting ) ) {
				return defaultValue;
			}

			return parseInt( setting );
		}

		function parseItemValue( setting = '', defaultValue = '' ) {
			if ( undefined === setting || '' === setting ) {
				return defaultValue;
			}

			// Normalize slide per view, reset fake view to exist view.
			if ( 'auto-fixed' === setting ) {
				return 'auto';
			}

			return setting;
		}

		function parseItemGroupValue( setting = '', inherit, itemsPerView ) {
			if ( 'auto' === itemsPerView ) {
				return 1;
			}

			if ( 'auto' === inherit ) {
				inherit = 1;
			} else if ( 'inherit' === inherit || parseInt( inherit ) > parseInt( itemsPerView ) ) {
				inherit = itemsPerView;
			}

			if ( undefined === setting || '' === setting ) {
				return inherit || 1;
			} else if ( 'inherit' === setting ) {
				return itemsPerView || 1;
			}

			return parseInt( setting );
		}

		function setSlideHeight( swiper ) {
			var slides = swiper.$wrapperEl.find( '.swiper-slide' );
			slides.css( { height: 'auto' } );

			var currentSlide  = swiper.activeIndex,
			    itemHeight    = $( swiper.slides[ currentSlide ] ).height(),
			    slidesPerView = swiper.params.slidesPerView,
			    spaceBetween  = swiper.params.spaceBetween,
			    wrapperHeight = slidesPerView * itemHeight + (
				    slidesPerView - 1
			    ) * spaceBetween;

			$( swiper.$el ).height( wrapperHeight );
			$( swiper.$wrapperEl ).find( '.swiper-slide' ).height( itemHeight );

			swiper.update();
		}

		function setSlideHeightCenterMode( swiper ) {
			var slides = swiper.$wrapperEl.find( '.swiper-slide' ).each( function() {
				var $thisSlide = $( this );
				$thisSlide.css( '--placeholder-height', $thisSlide.children().height() + 'px' );
			} );
		}

		function findCenteredSlides( swiper, slides ) {
			var visibleSlides = $( slides ).filter( '.swiper-slide-visible' ),
			    elOffsetLeft  = $( swiper.$el ).offset().left,
			    elOffsetRight = elOffsetLeft + $( swiper.$el ).outerWidth();

			visibleSlides.each( function() {
				var thisSlideOffsetLeft  = $( this ).offset().left - 1,
				    thisSlideOffsetRight = $( this ).offset().left + 1 + $( this ).outerWidth();

				if ( thisSlideOffsetLeft > elOffsetLeft && thisSlideOffsetRight < elOffsetRight ) {
					$( this ).addClass( 'swiper-slide-centered' ).removeClass( 'swiper-slide-uncentered' );
				} else {
					$( this ).removeClass( 'swiper-slide-centered' ).addClass( 'swiper-slide-uncentered' );
				}
			} );
		}
	}( jQuery )
);
