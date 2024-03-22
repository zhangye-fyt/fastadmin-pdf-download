(
	function( $ ) {
		'use strict';

		var $body  = $( 'body' ),
		    Helper = window.minimog.Helpers;

		$( window ).on( 'load', function() {
			navOnePage();
			mobileMenu();
		} );

		function navOnePage() {
			var $mainNav = $( '#page-navigation' ).find( '.menu__container' ).first();
			var $li = $mainNav.children( '.menu-item' );
			var $links = $li.children( 'a[href*="#"]:not([href="#"])' );

			$li.each( function() {
				if ( $( this ).hasClass( 'current-menu-item' ) ) {
					var _link = $( this ).children( 'a' );

					if ( _link[ 0 ].hash !== '' ) {
						$( this ).removeClass( 'current-menu-item' );
					}
				}
			} );

			// Handler links class when scroll to target section.
			if ( $.fn.elementorWaypoint ) {
				$links.each( function() {
					var $this = $( this );
					var target = this.hash;
					var parent = $this.parent();

					if ( Helper.isValidSelector( target ) ) {
						var $target = $( target );

						if ( $target.length > 0 ) {
							$target.elementorWaypoint( function( direction ) {
								if ( direction === 'down' ) {
									parent.siblings( 'li' ).removeClass( 'current-menu-item' );
									parent.addClass( 'current-menu-item' );
								}
							}, {
								offset: '25%'
							} );

							$target.elementorWaypoint( function( direction ) {
								if ( direction === 'up' ) {
									parent.siblings( 'li' ).removeClass( 'current-menu-item' );
									parent.addClass( 'current-menu-item' );
								}
							}, {
								offset: '-25%'
							} );
						}
					}
				} );
			}

			// Allows for easy implementation of smooth scrolling for navigation links.
			$links.on( 'click', function() {
				var $this = $( this );
				var target = this.hash;
				var parent = $this.parent( 'li' );

				parent.siblings( 'li' ).removeClass( 'current-menu-item' );
				parent.addClass( 'current-menu-item' );

				if ( Helper.isValidSelector( target ) ) {
					handlerSmoothScroll( target );
				}

				return false;
			} );

			// Smooth scroll to section if url has hash tag when page loaded.
			var hashTag = window.location.hash;

			if ( Helper.isValidSelector( hashTag ) ) {
				handlerSmoothScroll( hashTag );
			}
		}

		function mobileMenu() {
			var $menu = $( '#mobile-menu-primary' );
			$menu.on( 'click', 'a', function( e ) {
				var $this = $( this );
				var _li = $( this ).parent( 'li' );
				var target = $this.attr( 'href' );

				if ( Helper.isValidSelector( target ) ) {
					$body.removeClass( 'page-mobile-menu-opened' );

					$( document ).trigger( 'mobileMenuClose' );

					_li.siblings( 'li' ).removeClass( 'current-menu-item' );
					_li.addClass( 'current-menu-item' );

					setTimeout( function() {
						handlerSmoothScroll( target );
					}, 300 );

					return false;
				}
			} );
		}

		function handlerSmoothScroll( target ) {
			$.smoothScroll( {
				offset: 100,
				scrollTarget: $( target ),
				speed: 600,
				easing: 'linear'
			} );
		}
	}( jQuery )
);
