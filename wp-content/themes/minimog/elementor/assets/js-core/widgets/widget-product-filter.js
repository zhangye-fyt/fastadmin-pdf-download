(
	function( $ ) {
		'use strict';

		var MinimogProductFilter = function( $scope, $ ) {
			var $element = $scope.find( '.tm-product-filter' ),
				$form = $( '.tm-product-filter__form', $element );
			
			$element.on( 'click', '.term-link', termToggle );
			$element.on( 'submit', $form, formHandler );
			$element.on( 'click', '.tm-button', function( e ) {
				e.preventDefault();
				$form.trigger( 'submit' );
			} );

			// Toggle Term
			function termToggle( e ) {
				e.preventDefault();

				var $el = $( this ),
					$filter = $el.closest( '.tm-product-filter__filter' ),
					value = $el.data( 'term' ),
					$input = $el.siblings( '.filter-attribute-input' ),
					current = $input.val(),
					index = -1;

				if ( $filter.hasClass( 'tm-product-filter__multiple' ) ) {
					current = current ? current.split( ',' ) : [];
					index = current.indexOf( value );
					index = (-1 !== index) ? index : current.indexOf( value.toString() );

					if ( index !== -1 ) {
						current.splice( index, 1 );
					} else {
						current.push( value );
					}

					$input.val( current.join( ',' ) );
					$el.toggleClass( 'selected' );

					$input.prop( 'disabled', current.length <= 0 );

					if ( $filter.hasClass( 'tm-product-filter__attribute' ) ) {
						var $queryTypeInput = $input.next( 'input[name^=query_type_]' );

						if ( $queryTypeInput.length ) {
							$queryTypeInput.prop( 'disabled', current.length <= 1 );
						}
					}
				} else {
					if ( $el.hasClass( 'selected' ) ) {
						$el.removeClass( 'selected' );
						$input.val( '' ).prop( 'disabled', true );
					} else {
						$el.addClass( 'selected' ).siblings( '.selected' ).removeClass( 'selected' );
						$input.val( value ).prop( 'disabled', false );
					}
				}	

				$( document.body ).trigger( 'minimog_products_filter_change' );
			}

			// Form Handler
			function formHandler( e ) {
				var $inputs = $element.find( 'input[type=hidden]' ),
					action = $form.attr('action'),
					separator = action.indexOf('?') !== -1 ? '&' : '?',
					params = {},
					url = action;

				$inputs.each( function( e ) {
					var name = $( this ).attr( 'name' ),
						value = $( this ).val();
					
					if ( value ) {
						params[name] = value;
					}
				} );

				// the filter always contains "filtering" param
				// so it is empty if the length less than 2
				if ( Object.keys( params ).length > 1 ) {
					url += separator + $.param( params, true );
				}

				// Update Url
				if ( '?' === url.slice( -1 ) ) {
					url = url.slice( 0, -1 );
				}
		
				url = url.replace( /%2C/g, ',' );
				
				window.location.href = url;
				return false;
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product-filter.default', MinimogProductFilter );
		} );
	}
)( jQuery );
