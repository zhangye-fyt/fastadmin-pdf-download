jQuery( document ).ready( function( $ ) {
	'use strict';

	$( document.body ).on( 'click', '.quantity .increase, .quantity .decrease', function( evt ) {
		if ( $( this ).hasClass( 'disabled' ) ) {
			return false;
		}
		// Get values.
		var $qty       = $( this ).siblings( '.qty' ),
		    currentVal = parseFloat( $qty.val() ),
		    max        = parseFloat( $qty.attr( 'max' ) ),
		    min        = parseFloat( $qty.attr( 'min' ) ),
		    step       = $qty.attr( 'step' );

		// Format values.
		if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) {
			currentVal = 0;
		}
		if ( max === '' || max === 'NaN' ) {
			max = '';
		}
		if ( min === '' || min === 'NaN' ) {
			min = 0;
		}
		if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) {
			step = 1;
		}

		// Change the value.
		if ( $( this ).is( '.increase' ) ) {
			if ( max && max === currentVal ) {
				evt.preventDefault();
			} else {
				if ( max && currentVal > max ) {
					$qty.val( max );
				} else {
					$qty.val( currentVal + parseFloat( step ) );
				}

				$qty.trigger( 'change' );
			}
		} else {
			if ( min && min === currentVal ) {
				evt.preventDefault();
			} else {
				if ( min && currentVal < min ) {
					$qty.val( min );
				} else if ( currentVal > 0 ) {
					$qty.val( currentVal - parseFloat( step ) );
				}

				$qty.trigger( 'change' );
			}
		}
	} );

	$( document.body ).on( 'blur', '.quantity .qty', function() {
		var $qty       = $( this ),
		    currentVal = parseFloat( $qty.val() ),
		    max        = parseFloat( $qty.attr( 'max' ) );

		if ( max !== '' && max !== 'NaN' && currentVal > max ) {
			$( this ).val( max );
		}
	} );
} );
