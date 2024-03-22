(
	function( window, $ ) {
		'use strict';

		window.minimog = window.minimog || {};
		var $supports_html5_storage = true;

		try {
			$supports_html5_storage = (
				'sessionStorage' in window && window.sessionStorage !== null
			);
			window.sessionStorage.setItem( 'mg', 'test' );
			window.sessionStorage.removeItem( 'mg' );
			window.localStorage.setItem( 'mg', 'test' );
			window.localStorage.removeItem( 'mg' );
		} catch ( err ) {
			$supports_html5_storage = false;
		}

		minimog.Storage = {
			isSupported: $supports_html5_storage,
			set: function( key, value ) {
				var settings = JSON.parse( localStorage.getItem( 'minimog' ) );
				settings = settings ? settings : {};

				settings[ key ] = value;

				localStorage.setItem( 'minimog', JSON.stringify( settings ) );
			},
			get: function( key, defaults = '' ) {
				var settings = JSON.parse( localStorage.getItem( 'minimog' ) );

				if ( settings && settings.hasOwnProperty( key ) ) {
					return settings[ key ];
				}

				return defaults;
			},
		};

		minimog.Helpers = {
			getAjaxUrl: ( action ) => {
				return $minimog.minimog_ajax_url.toString().replace( '%%endpoint%%', action );
			},

			isEmptyObject: ( obj ) => {
				for ( let name in obj ) {
					return false;
				}

				return true;
			},

			isValidSelector: ( selector ) => {
				if ( selector.match( /^([.#])(.+)/ ) ) {
					return true;
				}

				return false;
			},

			isHandheld: () => {
				let check = false;
				(
					function( a ) {
						if ( /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test( a ) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test( a.substr( 0, 4 ) ) ) {
							check = true;
						}
					}
				)( navigator.userAgent || navigator.vendor || window.opera );
				return check;
			},

			randomInteger: ( min, max ) => {
				return Math.floor( Math.random() * (
					max - min + 1
				) ) + min;
			},

			/**
			 * Add a URL parameter (or changing it if it already exists)
			 * @param {string} url - This is typically document.location.search
			 * @param {string} key - The key to set
			 * @param {string} val - Value
			 */
			addUrlParam( url, key, val ) {
				key = encodeURI( key );
				val = encodeURI( val );

				if ( '' !== val ) {
					var re = new RegExp( "([?&])" + key + "=.*?(&|$)", "i" );
					var separator = url.indexOf( '?' ) !== - 1 ? "&" : "?";

					// Update value if key exist.
					if ( url.match( re ) ) {
						url = url.replace( re, '$1' + key + "=" + val + '$2' );
					} else {
						url += separator + key + '=' + val;
					}
				} else {
					this.removeUrlParam( url, key );
				}

				return url;
			},

			removeUrlParam( url, key ) {
				const params = new URLSearchParams( url );
				params.delete( key );
				return url;
			},

			getUrlParamsAsObject: ( query ) => {
				var params = {};

				if ( - 1 === query.indexOf( '?' ) ) {
					return params;
				}

				query = query.substring( query.indexOf( '?' ) + 1 );

				var re = /([^&=]+)=?([^&]*)/g;
				var decodeRE = /\+/g;

				var decode = function( str ) {
					return decodeURIComponent( str.replace( decodeRE, " " ) );
				};

				var e;
				while ( e = re.exec( query ) ) {
					var k = decode( e[ 1 ] ), v = decode( e[ 2 ] );
					if ( k.substring( k.length - 2 ) === '[]' ) {
						k = k.substring( 0, k.length - 2 );
						(
							params[ k ] || (
								params[ k ] = []
							)
						).push( v );
					}
					else {
						params[ k ] = v;
					}
				}

				var assign = function( obj, keyPath, value ) {
					var lastKeyIndex = keyPath.length - 1;
					for ( var i = 0; i < lastKeyIndex; ++ i ) {
						var key = keyPath[ i ];
						if ( ! (
							key in obj
						) ) {
							obj[ key ] = {}
						}
						obj = obj[ key ];
					}
					obj[ keyPath[ lastKeyIndex ] ] = value;
				}

				for ( var prop in params ) {
					var structure = prop.split( '[' );
					if ( structure.length > 1 ) {
						var levels = [];
						structure.forEach( function( item, i ) {
							var key = item.replace( /[?[\]\\ ]/g, '' );
							levels.push( key );
						} );
						assign( params, levels, params[ prop ] );
						delete(
							params[ prop ]
						);
					}
				}
				return params;
			},

			getScrollbarWidth: () => {
				// When not has scrollbar
				if( window.innerWidth <= document.documentElement.clientWidth ) {
					return 0;
				}

				// Creating invisible container.
				const outer = document.createElement( 'div' );
				outer.style.visibility = 'hidden';
				outer.style.overflow = 'scroll'; // forcing scrollbar to appear.
				outer.style.msOverflowStyle = 'scrollbar'; // needed for WinJS apps.
				document.body.appendChild( outer );

				// Creating inner element and placing it in the container.
				const inner = document.createElement( 'div' );
				outer.appendChild( inner );

				// Calculating difference between container's full width and the child width.
				const scrollbarWidth = (
					outer.offsetWidth - inner.offsetWidth
				);

				// Removing temporary elements from the DOM.
				outer.parentNode.removeChild( outer );

				return scrollbarWidth;
			},

			setBodyOverflow() {
				$( 'body' ).css( {
					'overflow': 'hidden',
					'paddingRight': this.getScrollbarWidth() + 'px'
				} );
			},

			unsetBodyOverflow: () => {
				$( 'body' ).css( {
					'overflow': 'visible',
					'paddingRight': 0
				} );
			},

			setBodyHandling: () => {
				$( 'body' ).removeClass( 'completed' ).addClass( 'handling' );
			},

			setBodyCompleted: () => {
				$( 'body' ).removeClass( 'handling' ).addClass( 'completed' );
			},

			setElementHandling: ( $element ) => {
				$element.addClass( 'updating-icon' );
			},

			unsetElementHandling: ( $element ) => {
				$element.removeClass( 'updating-icon' );
			},

			getStyle: ( el, style ) => {
				if ( window.getComputedStyle ) {
					return style ? document.defaultView.getComputedStyle( el, null ).getPropertyValue( style ) : document.defaultView.getComputedStyle( el, null );
				}
				else if ( el.currentStyle ) {
					return style ? el.currentStyle[ style.replace( /-\w/g, ( s ) => {
						return s.toUpperCase().replace( '-', '' );
					} ) ] : el.currentStyle;
				}
			},

			setCookie: ( cname, cvalue, exdays ) => {
				let d = new Date();
				d.setTime( d.getTime() + (
					exdays * 24 * 60 * 60 * 1000
				) );
				let expires = 'expires=' + d.toUTCString();
				document.cookie = cname + '=' + cvalue + '; ' + expires + '; path=/';
			},

			getCookie: ( cname ) => {
				var name = cname + '=';
				var ca = document.cookie.split( ';' );
				for ( var i = 0; i < ca.length; i ++ ) {
					var c = ca[ i ];
					while ( c.charAt( 0 ) == ' ' ) {
						c = c.substring( 1 );
					}
					if ( c.indexOf( name ) == 0 ) {
						return c.substring( name.length, c.length );
					}
				}
				return '';
			},

			copyToClipboard: ( text ) => {
				if ( window.clipboardData && window.clipboardData.setData ) {
					// Internet Explorer-specific code path to prevent textarea being shown while dialog is visible.
					return window.clipboardData.setData( "Text", text );

				}
				else if ( document.queryCommandSupported && document.queryCommandSupported( "copy" ) ) {
					var textarea = document.createElement( "textarea" );
					textarea.textContent = text;
					textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in Microsoft Edge.
					document.body.appendChild( textarea );
					textarea.select();
					try {
						return document.execCommand( "copy" );  // Security exception may be thrown by some browsers.
					}
					catch ( ex ) {
						console.warn( "Copy to clipboard failed.", ex );
						return prompt( "Copy to clipboard: Ctrl+C, Enter", text );
					}
					finally {
						document.body.removeChild( textarea );
					}
				}
			},

			handleLazyImages: ( $wrapper ) => {
				if ( $.fn.laziestloader ) {
					$wrapper.find( '.ll-image' ).laziestloader( {}, function() {
						$( this ).unwrap( '.minimog-lazy-image' );
					} ).trigger( 'laziestloader' );
				}
			},

			/**
			 * Store html string into data-o_content attribute
			 * @param $element
			 * @param content
			 */
			setContentHTML: ( $element, content ) => {
				if ( undefined === $element.attr( 'data-o_content' ) ) {
					$element.attr( 'data-o_content', $element.html() );
				}
				$element.html( content );
			},

			/**
			 * Restore original html from data-o_content
			 * @param $element
			 */
			resetContentHTML: ( $element ) => {
				if ( undefined !== $element.attr( 'data-o_content' ) ) {
					$element.html( $element.attr( 'data-o_content' ) );
				}
			}
		}

	}( window, jQuery )
);

(
	function( $ ) {
		'use strict';

		var $window           = $( window ),
		    $body             = $( 'body' ),
		    $pageTopBar       = $( '#page-top-bar' ),
		    $pageHeader       = $( '#page-header' ),
		    $headerInner      = $pageHeader.find( '#page-header-inner' ),
		    queueResetDelay,
		    animateQueueDelay = 200,
		    wWidth            = window.innerWidth,
		    wHeight           = window.innerHeight,
		    Helpers           = window.minimog.Helpers;

		window.minimog.LightGallery = {
			selector: '.zoom',
			mode: 'lg-fade',
			thumbnail: $minimog.light_gallery_thumbnail === '1',
			download: $minimog.light_gallery_download === '1',
			autoplay: $minimog.light_gallery_auto_play === '1',
			zoom: $minimog.light_gallery_zoom === '1',
			share: $minimog.light_gallery_share === '1',
			fullScreen: $minimog.light_gallery_full_screen === '1',
			hash: false,
			animateThumb: false,
			showThumbByDefault: false,
			getCaptionFromTitleOrAlt: false
		};

		// Call this asap for better rendering.
		calMobileMenuBreakpoint();

		$window.on( 'resize', function() {
			if ( wWidth !== window.innerWidth ) {
				$window.trigger( 'hresize' );
			}

			if ( wHeight !== window.innerHeight ) {
				$window.trigger( 'vresize' );
			}

			wWidth  = window.innerWidth;
			wHeight = window.innerHeight;
		} );

		$window.on( 'hresize', function() {
			calMobileMenuBreakpoint();
		} );

		$window.on( 'load', function() {
			initPreLoader();
			initStickyHeader();
			handlerEntranceAnimation();
			handlerEntranceQueueAnimation();
			handlerLanguageSwitcherAlignment();
			handlerTopBarSubMenuAlignment();
			handlerHeaderCurrencySwitcherAlignment();
		} );

		$( document ).ready( function() {
			initSliders();

			initGridMainQuery();
			initSearchPopup();
			initTopBarCollapsible();
			initSmartmenu();
			initSplitNavHeader();
			initTopBarCountdown();
			initMobileMenu();
			initCookieNotice();
			initLightGalleryPopups();
			initVideoPopups();
			handlerPageNotFound();

			initGridWidget();
			initAccordion();
			initNiceSelect();
			initSmoothScrollLinks();
			initModal();
			scrollToTop();
			handlerVerticalCategoryMenu();
			/**
			 * We need call init on window load to avoid plugin working wrongly
			 */
			initLazyLoaderImages();
		} );

		function handlerEntranceAnimation() {
			var items = $( '.modern-grid' ).children( '.grid-item' );

			items.elementorWaypoint( function() {
				// Fix for different ver of waypoints plugin.
				var _self = this.element ? this.element : this,
				    $self = $( _self );
				$self.addClass( 'animate' );
				this.destroy(); // trigger once.
			}, {
				offset: '100%'
			} );
		}

		function handlerEntranceQueueAnimation() {
			$( '.minimog-entrance-animation-queue' ).each( function() {
				var itemQueue  = [],
				    queueTimer,
				    queueDelay = $( this ).data( 'animation-delay' ) ? $( this )
					    .data( 'animation-delay' ) : animateQueueDelay;

				$( this ).children( '.item' ).elementorWaypoint( function() {
					// Fix for different ver of waypoints plugin.
					var _self = this.element ? this.element : $( this );

					queueResetDelay = setTimeout( function() {
						queueDelay = animateQueueDelay;
					}, animateQueueDelay );

					itemQueue.push( _self );
					processItemQueue( itemQueue, queueDelay, queueTimer );
					queueDelay += animateQueueDelay;

					this.destroy(); // trigger once.
				}, {
					offset: '100%'
				} );
			} );
		}

		function processItemQueue( itemQueue, queueDelay, queueTimer, queueResetDelay ) {
			clearTimeout( queueResetDelay );
			queueTimer = window.setInterval( function() {
				if ( itemQueue !== undefined && itemQueue.length ) {
					$( itemQueue.shift() ).addClass( 'animate' );
					processItemQueue();
				} else {
					window.clearInterval( queueTimer );
				}
			}, queueDelay );
		}

		function initPreLoader() {
			$body.addClass( 'loaded' );

			setTimeout( function() {
				var $loader = $( '#page-preloader' );

				if ( $loader.length > 0 ) {
					$loader.remove();
				}
			}, 2000 );
		}

		function initSliders() {
			$( '.tm-slider' ).each( function() {
				if ( $( this ).hasClass( 'minimog-swiper-linked-yes' ) ) {
					var mainSlider   = $( this ).children( '.minimog-main-swiper' ).MinimogSwiper();
					var thumbsSlider = $( this ).children( '.minimog-thumbs-swiper' ).MinimogSwiper();

					mainSlider.controller.control   = thumbsSlider;
					thumbsSlider.controller.control = mainSlider;
				} else {
					$( this ).MinimogSwiper();
				}
			} );
		}

		function initLightGalleryPopups() {
			if ( $.fn.lightGallery ) {
				$( '.minimog-light-gallery' ).each( function() {
					$( this ).lightGallery( window.minimog.LightGallery );
				} );
			}
		}

		function initVideoPopups() {
			if ( $.fn.lightGallery ) {
				var options = {
					selector: 'a',
					fullScreen: false,
					zoom: false,
					getCaptionFromTitleOrAlt: false,
					counter: false
				};

				$( '.tm-popup-video' ).each( function() {
					$( this ).lightGallery( options );
				} );
			}
		}

		function initGridMainQuery() {
			if ( $.fn.MinimogGridLayout ) {
				$( '.minimog-main-post' ).MinimogGridLayout();
			}
		}

		function initGridWidget() {
			if ( $.fn.MinimogGridLayout ) {
				$( '.minimog-instagram-widget' ).MinimogGridLayout();
			}
		}

		function initNiceSelect() {
			if ( $.fn.MinimogNiceSelect ) {
				$( '.minimog-nice-select' ).MinimogNiceSelect();
			}
		}

		function initAccordion() {
			if ( $.fn.MinimogAccordion ) {
				$( '.minimog-accordion' ).MinimogAccordion();
			}
		}

		function initModal() {
			if ( $.fn.MinimogModal ) {
				$body.on( 'click', '[data-minimog-toggle="modal"]', function( evt ) {
					var $target = $( $( this ).data( 'minimog-target' ) );

					if ( $target.length > 0 ) {
						evt.preventDefault();

						if ( $( this ).attr( 'data-minimog-dismiss' ) === '1' ) {
							$target.MinimogModal( 'close' );
						} else {
							$target.MinimogModal( 'open' );
						}
					}
				} );
			}
		}

		function initSmoothScrollLinks() {
			if ( ! $.fn.smoothScroll ) {
				return;
			}

			// Allows for easy implementation of smooth scrolling for buttons.
			$( '.smooth-scroll-link' ).on( 'click', function( evt ) {
				var target = $( this ).attr( 'href' );

				if ( Helpers.isValidSelector( target ) ) {
					evt.preventDefault();
					evt.stopPropagation();

					handlerSmoothScroll( target );
				}
			} );
		}

		function handlerSmoothScroll( target ) {
			$.smoothScroll( {
				offset: - 30,
				scrollTarget: $( target ),
				speed: 600,
				easing: 'linear'
			} );
		}

		function initSmartmenu() {
			var $primaryMenu = $pageHeader.find( '.sm.sm-simple' );

			$primaryMenu.smartmenus( {
				showTimeout: 0,
				hideTimeout: 150,
			} );

			// Add animation for sub menu.
			$primaryMenu.on( {
				'show.smapi': function( e, menu ) {
					var $thisMenu = $( menu );
					$thisMenu.removeClass( 'hide-animation' ).addClass( 'show-animation' );

					if ( ! $thisMenu.hasClass( 'menu-loaded' ) ) {
						if ( $.fn.laziestloader ) {
							var $images = $thisMenu.find( '.ll-image' );
							handleLazyImages( $images );

							var $backgroundImages = $thisMenu.find( '.ll-background' );
							handleLazyBackgrounds( $backgroundImages );
						}

						// Update Swiper Size.
						$thisMenu.find( '.tm-swiper' ).each( function() {
							var swiper = $( this )
								.children( '.swiper-inner' )
								.children( '.swiper-container' )[0].swiper;
							swiper.update();
						} );

						// Update Grid Layout.
						if ( $.fn.MinimogGridLayout ) {
							$thisMenu.find( '.minimog-grid-wrapper' ).MinimogGridLayout( 'updateLayout' );
						}

						$thisMenu.addClass( 'menu-loaded' );
					}
				},
				'hide.smapi': function( e, menu ) {
					$( menu ).removeClass( 'show-animation' ).addClass( 'hide-animation' );
				}
			} ).on( 'animationend webkitAnimationEnd oanimationend MSAnimationEnd', 'ul', function( e ) {
				$( this ).removeClass( 'show-animation hide-animation' );
				e.stopPropagation();
			} );
		}

		function scrollToTop() {
			if ( $minimog.scroll_top_enable != 1 ) {
				return;
			}
			var $scrollUp     = $( '#page-scroll-up' );
			var lastScrollTop = 0;

			$window.on( 'scroll', function() {
				var st = $( this ).scrollTop();
				if ( st > lastScrollTop ) {
					$scrollUp.removeClass( 'show' );
				} else {
					if ( $window.scrollTop() > 200 ) {
						$scrollUp.addClass( 'show' );
					} else {
						$scrollUp.removeClass( 'show' );
					}
				}
				lastScrollTop = st;
			} );

			$scrollUp.on( 'click', function( evt ) {
				$( 'html, body' ).animate( { scrollTop: 0 }, 600 );
				evt.preventDefault();
			} );
		}

		function openMobileMenu( $mobileMenu ) {
			$body.addClass( 'page-mobile-menu-opened' );

			Helpers.setBodyOverflow();

			$( document ).trigger( 'mobileMenuOpen' );

			$mobileMenu.attr( 'aria-hidden', 'false' ).prop( 'hidden', false );
		}

		function closeMobileMenu( $mobileMenu ) {
			$body.removeClass( 'page-mobile-menu-opened' );

			Helpers.unsetBodyOverflow();

			$( document ).trigger( 'mobileMenuClose' );

			$mobileMenu.attr( 'aria-hidden', 'true' ).prop( 'hidden', true );
		}

		function calMobileMenuBreakpoint() {
			var menuBreakpoint = parseInt( $minimog.mobile_menu_breakpoint );

			if ( wWidth <= menuBreakpoint ) {
				$body.removeClass( 'primary-nav-rendering' ).removeClass( 'desktop-menu' ).addClass( 'mobile-menu' );
			} else {
				$body.removeClass( 'primary-nav-rendering' ).addClass( 'desktop-menu' ).removeClass( 'mobile-menu' );
			}
		}

		function initMobileMenu() {
			var $btnOpenMobileMenu = $( '#page-open-mobile-menu' );
			var duration           = 300;

			if ( $btnOpenMobileMenu.length > 0 ) {
				var settings = $btnOpenMobileMenu.data( 'menu-settings' );

				var animation = settings.animation ? settings.animation : 'slide';
				var direction = settings.direction ? settings.direction : 'left';

				$body.addClass( 'mobile-menu-' + animation + '-to-' + direction );
			}

			var $mobileMenu     = $( '#page-mobile-main-menu' ),
			    $tabContentWrap = $( '.mobile-menu-nav-menus' ),
			    $menu           = $mobileMenu.find( '.menu__container' );

			if ( $.fn.perfectScrollbar && ! Helpers.isHandheld() ) {
				$mobileMenu.find( '.page-mobile-menu-content' ).perfectScrollbar();
			}

			$btnOpenMobileMenu.on( 'click', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				openMobileMenu( $mobileMenu );
			} );

			$mobileMenu.on( 'click', '.tm-button', function() {
				closeMobileMenu( $mobileMenu );
			} );

			$mobileMenu.on( 'click', '#page-close-mobile-menu', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				closeMobileMenu( $mobileMenu );
			} );

			$mobileMenu.on( 'click', function( e ) {
				if ( e.target !== this ) {
					return;
				}

				closeMobileMenu( $mobileMenu );
			} );

			$mobileMenu.on( 'click', '.mobile-nav-tabs li', function( evt ) {
				var $thisTab = $( this );

				if ( $thisTab.hasClass( 'active' ) ) {
					return;
				}

				$thisTab.siblings().removeClass( 'active' ).attr( 'aria-selected', 'false' ).attr( 'tabindex', '-1' );
				$thisTab.addClass( 'active' ).attr( 'aria-selected', 'true' ).attr( 'tabindex', '0' );

				var $thisTabContent = $tabContentWrap.children( '#' + $thisTab.attr( 'aria-controls' ) );

				$thisTabContent.siblings().attr( 'aria-expanded', false ).prop( 'hidden', true );
				$thisTabContent.attr( 'aria-expanded', true ).prop( 'hidden', false );
			} );

			$menu.on( 'click', '.toggle-sub-menu', function( evt ) {
				var $li = $( this ).parents( 'li' ).first();

				evt.preventDefault();
				evt.stopPropagation();

				var _friends = $li.siblings( '.opened' );
				_friends.removeClass( 'opened' );
				_friends.find( '.opened' ).removeClass( 'opened' );
				_friends.find( '.sub-menu' ).stop().slideUp( duration );

				if ( $li.hasClass( 'opened' ) ) {
					$li.removeClass( 'opened' );
					$li.find( '.opened' ).removeClass( 'opened' );
					$li.find( '.sub-menu' ).stop().slideUp( duration );
				} else {
					$li.addClass( 'opened' );
					var $subMenu = $li.children( '.sub-menu' );
					$subMenu.stop().slideDown( duration, function() {
						// Need wait for animation end to make ll image working properly.
						if ( ! $subMenu.hasClass( 'menu-loaded' ) ) {
							if ( $.fn.laziestloader ) {
								var $images = $subMenu.find( '.ll-image' );
								handleLazyImages( $images, true );

								var $backgroundImages = $subMenu.find( '.ll-background' );
								handleLazyBackgrounds( $backgroundImages, true );
							}

							// Update Swiper Size.
							$subMenu.find( '.tm-swiper' ).each( function() {
								var swiper = $( this )
									.children( '.swiper-inner' )
									.children( '.swiper-container' )[0].swiper;
								swiper.update();
							} );

							// Update Grid Layout.
							if ( $.fn.MinimogGridLayout ) {
								$subMenu.find( '.minimog-grid-wrapper' ).MinimogGridLayout( 'updateLayout' );
							}

							$subMenu.addClass( 'menu-loaded' );
						}
					} );
				}
			} );
		}

		function initSplitNavHeader() {
			if ( 0 >= $headerInner.length || 1 !== $headerInner.data( 'centered-logo' ) ) {
				return;
			}

			var $navigation = $headerInner.find( '#page-navigation' ),
			    $navItems   = $navigation.find( '#menu-primary > li' ),
			    $logo       = $headerInner.find( '.branding__logo img.logo' ),
			    itemsNumber = $navItems.length,
			    isRTL       = $body.hasClass( 'rtl' ),
			    midIndex    = parseInt( itemsNumber / 2 + .5 * isRTL - .5 ),
			    $midItem    = $navItems.eq( midIndex ),
			    rule        = isRTL ? 'marginLeft' : 'marginRight';

			var recalc = function() {
				var logoWidth  = $logo.outerWidth(),
				    logoHeight = $logo.closest( '.branding__logo' ).height(),
				    leftWidth  = 0,
				    rightWidth = 0;

				$logo.closest( '.header-content-inner' ).css( 'min-height', logoHeight + 'px' );

				for ( var i = itemsNumber - 1; i >= 0; i -- ) {
					var itemWidth = $navItems.eq( i ).outerWidth();

					if ( i > midIndex ) {
						rightWidth += itemWidth;
					} else {
						leftWidth += itemWidth;
					}
				}

				var diff = leftWidth - rightWidth;

				if ( isRTL ) {
					if ( leftWidth > rightWidth ) {
						$navigation.find( '#menu-primary > li:first-child' ).css( 'marginRight', - diff );
					} else {
						$navigation.find( '#menu-primary > li:last-child' ).css( 'marginLeft', diff );
					}
				} else {
					if ( leftWidth > rightWidth ) {
						$navigation.find( '#menu-primary > li:last-child' ).css( 'marginRight', diff );
					} else {
						$navigation.find( '#menu-primary > li:first-child' ).css( 'marginLeft', - diff );
					}
				}

				$midItem.css( rule, logoWidth + 66 );
			};

			recalc();

			$logo.on( 'loaded', function() {
				setTimeout( function() {
					recalc();
					$navigation.addClass( 'menu-calculated' );
				}, 100 ); // Delay 100 wait for logo rendered.
			} );

			$window.on( 'hresize', recalc );
		}

		function initStickyHeader() {
			if ( $minimog.header_sticky_enable != 1 || 0 >= $pageHeader.length || $headerInner.data( 'sticky' ) != '1' ) {
				return;
			}

			var $headerHolder = $pageHeader.children( '.page-header-place-holder' ),
			    offset        = $headerInner.offset().top,
			    _hHeight      = $headerInner.outerHeight(),
			    ACTIVE_CLASS  = 'header-pinned',
			    lastST        = 0,
			    isPinned      = false,
			    stickyTimer   = null;

			// Fix offset top return negative value on some devices.
			if ( offset < 0 ) {
				offset = 0;
			}

			offset += _hHeight;

			if ( ! $pageHeader.hasClass( 'header-layout-fixed' ) ) {
				$headerHolder.height( _hHeight );
				$headerInner.addClass( 'held' );

				$window.on( 'hresize', function() {
					var _hHeight = $headerInner.outerHeight();

					$headerHolder.height( _hHeight );
				} );
			}

			$window.on( 'scroll', function() {
				var currentST = $( this ).scrollTop();

				clearTimeout( stickyTimer );

				if ( currentST <= offset ) { // When on top remove sticky without delay.
					isPinned = false;
					$pageHeader.removeClass( ACTIVE_CLASS );
				} else {
					if ( currentST < lastST ) { // Scroll up.
						if ( ! isPinned ) {
							toggleSticky();
						}
					} else {  // Scroll down.
						if ( isPinned ) {
							toggleSticky();
						}
					}
				}

				lastST = currentST;
			} );

			function toggleSticky() {
				stickyTimer = setTimeout( function() {
					if ( ! isPinned ) {
						isPinned = true;
						$pageHeader.addClass( ACTIVE_CLASS );
						$pageHeader.css( '--logo-sticky-height', $pageHeader.find( '.branding__logo' )
						                                                    .height() + 'px' );
					} else {
						isPinned = false;
						$pageHeader.removeClass( ACTIVE_CLASS );
					}
				}, 200 );
			}
		}

		function initTopBarCountdown() {
			if ( ! $.fn.countdown ) {
				return;
			}

			if ( 0 >= $pageTopBar.length ) {
				return;
			}

			var $countdownWrap = $pageTopBar.find( '.top-bar-countdown-timer' );

			if ( 0 >= $countdownWrap.length ) {
				return;
			}

			var $countdown = $countdownWrap.find( '.countdown-timer' ),
			    settings   = $countdownWrap.data( 'countdown' ),
			    labels     = settings.labels;

			$countdown.countdown( settings.datetime, function( event ) {
				var templateStr = '<div class="countdown-clock">' + '<div class="clock-item days"><span class="number">%D</span><span class="text">' + labels.days + '</span></div>' + '<span class="clock-divider days"></span>' + '<div class="clock-item hours"><span class="number">%H</span><span class="text">' + labels.hours + '</span></div>' + '<span class="clock-divider hours"></span>' + '<div class="clock-item minutes"><span class="number">%M</span><span class="text">' + labels.minutes + '</span></div>' + '<span class="clock-divider minutes"></span>' + '<div class="clock-item seconds"><span class="number">%S</span><span class="text">' + labels.seconds + '</span></div>' + '</div>';
				$( this ).html( event.strftime( templateStr ) );
			} );
		}

		function initSearchPopup() {
			var settings = $minimog.search,
			    delay    = parseInt( settings.delay );

			delay = ! isNaN( delay ) ? delay : 1000;

			var $popupSearch     = $( '#popup-search' ),
			    $popupSearchForm = $popupSearch.find( '.search-form' ),
			    $dropdownCat     = $popupSearchForm.find( '.search-select' );

			// Use Select2 enhancement if possible.
			if ( $.fn.selectWoo ) {
				$dropdownCat.selectWoo( {
					dropdownAutoWidth: true,
					dropdownCssClass: 'select2-dropdown-search-cat',
					selectionCssClass: 'hello-three'
				} );
			}

			$( document.body ).on( 'click', '.page-open-popup-search', function( evt ) {
				evt.preventDefault();

				openSearch();
			} );

			$pageHeader.on( 'mousedown', '#placeholder_cat_dropdown', function( evt ) {
				evt.preventDefault();

				openSearch( 'cat' );
			} );

			$pageHeader.on( 'focus', '.search-field', function( evt ) {
				evt.preventDefault();

				openSearch();
			} );

			$popupSearchForm.on( 'change', '.search-select', function( evt ) {
				evt.preventDefault();

				var selectedOptionText = $( this ).children( 'option' ).filter( ':selected' ).text();

				$pageHeader.find( '.search-select' ).children( 'option' ).html( selectedOptionText );

				openSearch();
			} );

			$pageHeader.on( 'click', '.search-submit', function( evt ) {
				evt.preventDefault();

				openSearch();
			} );

			$( '#search-popup-close' ).on( 'click', function( evt ) {
				evt.preventDefault();

				closeSearch();
			} );

			$popupSearch.on( 'click', function( evt ) {
				if ( evt.target !== this ) {
					return;
				}

				closeSearch();
			} );

			var $gridWrapper = $popupSearch.find( '.minimog-grid-wrapper' );
			$gridWrapper.MinimogGridLayout();

			var searching = null;

			$popupSearchForm.on( 'focus', '.search-field', function() {
				$popupSearchForm.addClass( 'search-field-focused' );
			} );

			$popupSearchForm.on( 'blur', '.search-field', function() {
				$popupSearchForm.removeClass( 'search-field-focused' );
			} );

			$popupSearchForm.on( 'keyup', '.search-field', function() {
				var $field = $( this );

				clearTimeout( searching );
				searching = setTimeout( function() {
					if ( $field.val().length > 2 ) {
						doSearch()
					} else {
						clearSearch();
					}
				}, delay );
			} );

			function doSearch() {
				var formData   = $popupSearchForm.serializeArray(),
				    searchTerm = '';

				formData.forEach( function( item, index, array ) {
					if ( 's' === item.name ) {
						searchTerm = item.value;

						return false;
					}
				} );

				if ( '' === searchTerm ) {
					$popupSearch.find( '.popup-search-results' ).hide();
					$popupSearch.find( '.row-popular-search-keywords' ).show();
					return;
				}

				formData.push( {
					name: 'action',
					value: 'minimog_search_products'
				} );

				$.ajax( {
					url: $minimog.ajaxurl,
					type: 'GET',
					data: $.param( formData ),
					dataType: 'json',
					cache: true,
					success: function( response ) {
						$popupSearch.find( '.row-popular-search-keywords' ).hide();
						$popupSearch.find( '.popup-search-results' ).show();
						$popupSearch.find( '.popup-search-current' ).text( searchTerm );

						var $gridWrapper = $popupSearch.find( '.minimog-grid-wrapper' ),
						    $grid        = $gridWrapper.find( '.minimog-grid' );
						$grid.children( '.grid-item' ).remove();
						$gridWrapper.MinimogGridLayout( 'update', $( response.data.template ) );
					},
					beforeSend: function() {
						$popupSearch.removeClass( 'loaded' ).addClass( 'loading' );
					},
					complete: function() {
						$popupSearch.removeClass( 'loading' ).addClass( 'loaded' );
					},
				} );
			}

			function clearSearch() {
				$popupSearch.find( '.popup-search-results' ).hide();
				$popupSearch.find( '.row-popular-search-keywords' ).show();
			}

			function openSearch( focus = '' ) {
				Helpers.setBodyOverflow();
				$popupSearch.addClass( 'open' ).attr( 'aria-hidden', 'false' ).prop( 'hidden', false );

				setTimeout( function() {
					if ( 'cat' === focus ) {
						$popupSearch.find( '.search-select' ).select2( 'open' );
					} else {
						$popupSearch.find( '.search-field' ).trigger( 'focus' );
					}
				}, 300 );

				$popupSearch.find( '.ll-image.ll-notloaded' ).trigger( 'laziestloader' );

				if ( $.fn.perfectScrollbar && ! Helpers.isHandheld() ) {
					$popupSearch.children( '.inner' ).perfectScrollbar();
				}
			}

			function closeSearch() {
				Helpers.unsetBodyOverflow();
				$popupSearch.removeClass( 'open' ).attr( 'aria-hidden', 'true' ).prop( 'hidden', true );
			}
		}

		function initTopBarCollapsible() {
			if ( 0 >= $pageTopBar.length ) {
				return;
			}

			var ACTIVE_CLASS   = 'expanded';
			var $topbarContent = $pageTopBar.find( '.top-bar-section' );

			$pageTopBar.on( 'click', '#top-bar-collapsible-toggle', function() {
				if ( $pageTopBar.hasClass( ACTIVE_CLASS ) ) {
					$pageTopBar.removeClass( ACTIVE_CLASS );
					$pageTopBar.find( '.top-bar-wrap' ).css( { height: '26px' } );
				} else {
					$pageTopBar.addClass( ACTIVE_CLASS );
					$pageTopBar.find( '.top-bar-wrap' ).css( { height: $topbarContent.outerHeight() + 'px' } );
				}
			} );

			$window.on( 'hresize', function() {
				if ( $pageTopBar.hasClass( ACTIVE_CLASS ) ) {
					$pageTopBar.find( '.top-bar-wrap' ).css( { height: $topbarContent.outerHeight() + 'px' } );
				}
			} );
		}

		function initCookieNotice() {
			// Fix Nginx Redis Cache skip cookie.
			if ( typeof Storage !== 'undefined' && localStorage.getItem( 'minimog_cookie_accepted' ) === 'yes' ) {
				return;
			}

			var $cookiePopup = $( '#cookie-notice-popup' );

			if ( 0 >= $cookiePopup.length ) {
				return;
			}

			$cookiePopup.removeClass( 'close' ).addClass( 'show' );

			$cookiePopup.on( 'click', '#btn-accept-cookie', function() {
				$cookiePopup.removeClass( 'show' ).addClass( 'close' );

				var data = $.param( {
					action: 'minimog_cookie_accepted'
				} );

				$.ajax( {
					url: $minimog.ajaxurl,
					type: 'POST',
					data: data,
					dataType: 'json',
					success: function( response ) {
						if ( typeof Storage !== 'undefined' ) {
							localStorage.setItem( 'minimog_cookie_accepted', 'yes' );
						}
					}
				} );
			} );
		}

		function handlerPageNotFound() {
			if ( ! $body.hasClass( 'error404' ) ) {
				return;
			}

			$( '#btn-go-back' ).on( 'click', function( e ) {
				e.preventDefault();

				window.history.back();
			} );
		}

		function handlerLanguageSwitcherAlignment() {
			var $languageSwitcher = $( '#switcher-language-wrapper' );

			if ( 0 >= $languageSwitcher.length ) {
				return;
			}

			// WPML.
			$languageSwitcher.on( 'mouseenter', '.wpml-ls-current-language', function() {
				handlerSubMenuAlignment( $( this ) );
			} );

			// Polylang.
			$languageSwitcher.on( 'mouseenter', '.current-lang', function() {
				handlerSubMenuAlignment( $( this ) );
			} );
		}

		function handlerTopBarSubMenuAlignment() {
			var $topBarMenus = $pageTopBar.find( '.menu' );

			if ( 0 < $topBarMenus.length ) {
				$topBarMenus.on( 'mouseenter', '.menu-item-has-children', function() {
					handlerSubMenuAlignment( $( this ) );
				} );
			}

			var $languageSwitcher = $pageTopBar.find( '#switcher-language-wrapper' );
			if ( 0 < $languageSwitcher.length ) {
				if ( $languageSwitcher.hasClass( 'polylang' ) ) {
					calculateTopBarSubMenuAlignment( $languageSwitcher, {
						toggle: '.current-lang'
					} );
				} else if ( $languageSwitcher.hasClass( 'translate-press' ) ) {
					calculateTopBarSubMenuAlignment( $languageSwitcher, {
						toggle: '.trp-language-switcher',
						children: '.trp-ls-shortcode-language'
					} );
				} else if ( $languageSwitcher.hasClass( 'wpml' ) ) {
					calculateTopBarSubMenuAlignment( $languageSwitcher, {
						toggle: '.wpml-ls-current-language'
					} );
				}
			}

			var $currencySwitcher = $pageTopBar.find( '.currency-switcher-menu-wrap' );
			if ( 0 < $currencySwitcher.length ) {
				if ( $currencySwitcher.hasClass( 'curcy' ) ) {
					calculateTopBarSubMenuAlignment( $currencySwitcher, {
						toggle: '.menu-item-has-children'
					} );
				} else if ( $currencySwitcher.hasClass( 'wcml' ) ) {
					calculateTopBarSubMenuAlignment( $currencySwitcher, {
						toggle: '.wcml-cs-active-currency'
					} );
				} else if ( $currencySwitcher.hasClass( 'aelia' ) ) {
					calculateTopBarSubMenuAlignment( $currencySwitcher, {
						toggle: '.dropdown_selector',
						children: '.dropdown'
					} );
				}
			}
		}

		function calculateTopBarSubMenuAlignment( $languageSwitcher, args = {} ) {
			let docWidth = $( document ).width(),
			    options  = $.extend( true, {}, {
				    children: 'ul',
			    }, args );


			var $listItem = $languageSwitcher.find( options.toggle );
			if ( 0 >= $listItem.length ) {
				return;
			}

			calculatePosition();

			$languageSwitcher.on( 'click touch mouseenter', options.toggle, function() {
				calculatePosition();
			} );

			function calculatePosition() {
				var $subMenu   = $listItem.children( options.children ),
				    itemOffset = $listItem.offset(),
				    subMenuY   = itemOffset.top + $listItem.outerHeight();

				$subMenu.css( {
					top: subMenuY,
				} );

				if ( itemOffset.left + $subMenu.outerWidth() >= docWidth ) {
					$subMenu.css( {
						left: 'auto',
						right: docWidth - (
							itemOffset.left + $listItem.outerWidth()
						),
					} );
				} else {
					$subMenu.css( {
						left: itemOffset.left,
						right: 'auto'
					} );
				}
			}
		}

		function handlerHeaderCurrencySwitcherAlignment() {
			var $currencySwitcher = $pageHeader.find( '.currency-switcher-menu-wrap' );

			if ( 0 >= $currencySwitcher.length ) {
				return;
			}

			$currencySwitcher.on( 'mouseenter', '.menu-item-has-children', function() {
				handlerSubMenuAlignment( $( this ) );
			} );

			$currencySwitcher.on( 'mouseenter', '.dropdown_selector', function() { // by Aelia.
				handlerSubMenuAlignment( $( this ), {
					children: '.dropdown',
				} );
			} );
		}

		function handlerSubMenuAlignment( $listItem, args = {} ) {
			let docWidth = $( document ).width(),
			    options  = $.extend( true, {}, {
				    children: 'ul',
				    offset: 200,
				    appendClass: 'hover-back'
			    }, args );

			$listItem.children( options.children ).each( function() {
				var $subMenu = $( this );
				$subMenu.removeClass( options.appendClass );
				if ( $subMenu.offset().left + options.offset >= docWidth ) {
					$subMenu.addClass( options.appendClass );
				}
			} );
		}

		function handlerVerticalCategoryMenu() {
			var $wrapper = $( '#header-categories-nav' );

			if ( 0 >= $wrapper.length ) {
				return;
			}

			var FIXED_NAV_CLASS = 'categories-nav-fixed';

			$wrapper.on( 'mouseenter', function() {
				$wrapper.removeClass( 'hide-animation' ).addClass( 'show-animation' );
			} ).on( 'mouseleave', function() {
				if ( $wrapper.hasClass( FIXED_NAV_CLASS ) && ! $pageHeader.hasClass( 'header-pinned' ) ) {
					$wrapper.removeClass( 'show-animation hide-animation' );
				} else {
					$wrapper.removeClass( 'show-animation' ).addClass( 'hide-animation' );
				}
			} ).on( 'animationend webkitAnimationEnd oanimationend MSAnimationEnd', 'nav', function( evt ) {
				evt.stopPropagation();
				$wrapper.removeClass( 'hide-animation' );
			} );
		}

		function initLazyLoaderImages() {
			if ( $.fn.laziestloader ) {
				var $images = $( '.ll-image' );
				handleLazyImages( $images );

				var $backgroundImages = $( '.ll-background' );
				handleLazyBackgrounds( $backgroundImages );
			}
		}

		function handleLazyImages( $images, force = false ) {
			$images.laziestloader( {}, function() {
				$( this ).unwrap( '.minimog-lazy-image' );
			} );

			if ( force ) {
				$images.trigger( 'laziestloader' );
			}
		}

		function handleLazyBackgrounds( $backgroundImages, force = false ) {
			$backgroundImages.laziestloader( {
				setSourceMode: true
			}, function() {
				var $lazyItem = $( this );
				var src       = $lazyItem.data( 'src' );

				$( '<img/>' ).attr( 'src', src ).on( 'load', function() {
					$lazyItem.css( 'background-image', 'url( ' + src + ' )' ).removeClass( 'll-background-unload' );

					$( this ).remove(); // Prevent memory leaks.
				} );
			} );

			if ( force ) {
				$backgroundImages.trigger( 'laziestloader' );
			}
		}
	}( jQuery )
);
