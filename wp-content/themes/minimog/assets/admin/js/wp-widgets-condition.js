(
	function( $ ) {
		'use strict';

		$( document ).ready( function() {
			$( '.widget-liquid-right form' ).each( function() {
				var $form = $( this );

				$( document.body ).trigger( 'MinimogControlDependChange', [ $form ] );
			} );
		} );

		$( '.widget-liquid-right' ).on( 'change', '.widget-control--has-depend', function() {
			var $form = $( this ).closest( 'form' );

			$( document.body ).trigger( 'MinimogControlDependChange', [ $form ] );
		} );

		/**
		 * Update field display when widget added or updated.
		 * @see wp-admin/js/widgets.js
		 */
		$( document ).on( 'widget-added widget-updated', function( evt, $theWidget ) {
			var $form = $theWidget.find( 'form' );
			$( document.body ).trigger( 'MinimogControlDependChange', [ $form ] );
		} );

		$( document.body ).on( 'MinimogControlDependChange', function( evt, $form ) {
			$form.find( '.control-has-condition' ).each( function() {
				var $controlWrap  = $( this ),
				    conditions    = $( this ).data( 'condition' ),
				    meetCondition = true;

				for ( const controlID in conditions ) {
					var operator       = conditions[ controlID ][ 'operator' ] ? conditions[ controlID ][ 'operator' ] : '=',
					    conditionValue = conditions[ controlID ][ 'value' ],
					    thisControlVal = $form.find( '.widget-control--' + controlID ).val();


					switch ( operator ) {
						case '!':
							if ( conditionValue.length > 1 ) {
								if ( - 1 !== conditionValue.indexOf( thisControlVal ) ) {
									meetCondition = false;
								}
							} else {
								if ( conditionValue == thisControlVal ) {
									meetCondition = false;
								}
							}
							break;
						case '=':
							if ( conditionValue.length > 1 ) {
								if ( - 1 === conditionValue.indexOf( thisControlVal ) ) {
									meetCondition = false;
								}
							} else {
								if ( conditionValue != thisControlVal ) {
									meetCondition = false;
								}
							}
							break;
					}

					// If any conditions not meet then hide this control and finish.
					if ( ! meetCondition ) {
						$controlWrap.hide();
						return;
					}
				}

				if ( meetCondition ) {
					$controlWrap.show();
				}
			} );
		} );
	}( jQuery )
);
