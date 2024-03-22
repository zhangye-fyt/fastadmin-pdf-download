(
	function( $ ) {
		'use strict';

		var MinimogCounterHandler = function( $scope, $ ) {
			var $element = $scope.find( '.tm-counter' );

			elementorFrontend.waypoint( $element, function() {
				var settings = $element.data( 'counter' ),
					digits = settings.digits;

				var $number = $element.find( '.tm-counter__number' );

				$number.countTo( {
					from: settings.from,
					to: settings.to,
					decimals: settings.decimal,
					speed: settings.duration,
					refreshInterval: 50,
					formatter: function( value, options ) {
						var result;

						if ( 'full' === settings.formatType ) {
							result = format( value.toFixed( options.decimals ), settings.separator );
						} else {
							result = shortFormatNumber( value, digits )
						}

						return result.padStart( 2, '0' ) || 0;
					},
					onUpdate: function( value ) {
						var unit = '';
						if ( 'short' === settings.formatType ) {
							if ( value > 999999999 ) {
								unit = 'b';
							} else if ( value > 999999 ) {
								unit = 'm';
							} else if ( value > 999 ) {
								unit = 'k';
							}
							$number.append( unit );
						}
					},
				} );
			} );

			function format( x, sep, grp ) {
				var sx = ( '' + x ).split( '.' ),
					s = '',
					i,
					j;
				sep || ( sep = '' ); // default separator.
				grp || grp === 0 || ( grp = 3 ); // default grouping
				i = sx[ 0 ].length;
				while ( i > grp ) {
					j = i - grp;
					s = sep + sx[ 0 ].slice( j, i ) + s;
					i = j;
				}
				s = sx[ 0 ].slice( 0, i ) + s;
				sx[ 0 ] = s;
				return sx.join( '.' );
			}

			function shortFormatNumber( number, digits ) {
				var value = Math.abs( number ),
					sign = Math.sign( number );
				if ( number > 999999999 ) {
					value = sign * ( Math.round( Math.abs( number ) / 100 ) / 10000000 );
				} else if ( number > 999999 ) {
					value = sign * ( Math.round( Math.abs( number ) / 100 ) / 10000 );
				} else if ( number > 999 ) {
					value = sign * ( Math.round( Math.abs( number ) / 100 ) / 10 );
				}

				return value.toFixed( digits );
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-counter.default', MinimogCounterHandler );
		} );
	}
)( jQuery );
