(
	function( $ ) {
		'use strict';

		$.fn.MinimogNiceSelect = function( options ) {
			var FOCUSED_CLASS = 'focused';
			var SELECTED_CLASS = 'selected';
			var defaults = {
				height: 190,
				width: 200
			};

			return this.each( function() {
				var $el = $( this );
				var dataSettings = $( this ).data( 'select' ) ? $( this ).data( 'select' ) : {};

				var settings = $.extend( defaults, options, dataSettings );

				var fieldLabel = settings.fieldLabel ? '<span class="label">' + settings.fieldLabel + '</span>' : '';
				var $current = $( '<div class="minimog-nice-select-current">' + fieldLabel + '<span class="value"></span></div>' );
				var $dropdown = $( '<ul class="minimog-nice-select"></ul>' );
				var dropdownHtml = '';

				$el.children( 'option' ).each( function() {
					var itemClass = 'item';
					if ( $( this ).is( ':selected' ) ) {
						$current.find( '.value' ).text( $( this ).text() );
						itemClass += ' ' + SELECTED_CLASS;
					}

					dropdownHtml += '<li class="' + itemClass + '" data-value="' + $( this ).val() + '">' + $( this ).text() + '</li>';
				} );

				$dropdown.html( dropdownHtml );
				var $wrap = $( '<div class="minimog-nice-select-wrap"></div>' );

				$wrap.append( $current ).append( $dropdown );

				$dropdown.width( settings.width );

				// Set height for dropdown.
				// Need delay 100 to get properly height.
				setTimeout( function() {
					$dropdown.height( Math.min( settings.height, $dropdown.height() ) );
				}, 100 );

				$current.on( 'click', function() {
					$wrap.toggleClass( FOCUSED_CLASS );
				} );

				$wrap.on( 'click', 'li', function() {
					$wrap.removeClass( FOCUSED_CLASS );

					if ( ! $( this ).hasClass( SELECTED_CLASS ) ) {
						$( this ).siblings().removeClass( SELECTED_CLASS );
						$( this ).addClass( SELECTED_CLASS );
						$current.find( '.value' ).text( $( this ).text() );
						$el.val( $( this ).data( 'value' ) );
						$el.trigger( 'change' );
					}
				} );

				$( document ).on( 'click touchstart', function( e ) {
					if ( $( e.target ).closest( $wrap ).length === 0 ) {
						$wrap.removeClass( FOCUSED_CLASS );
					}
				} );

				var isRendered = false;

				if ( settings.renderTarget ) {
					var $renderTarget = 'object' === typeof settings.renderTarget ? settings.renderTarget : $( settings.renderTarget );

					if ( $renderTarget.length > 0 ) {
						$renderTarget.append( $wrap );
						isRendered = true;
					}
				}

				if ( false === isRendered ) {
					$el.after( $wrap );
				}

				$el.hide();
			} );
		};
	}( jQuery )
);
