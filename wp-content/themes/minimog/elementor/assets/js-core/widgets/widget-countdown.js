(
	function( $ ) {
		'use strict';

		var CountdownHandler = function( $scope, $ ) {
			var $countdown    = $scope.find( '.countdown' ),
			    countSettings = $countdown.data();

			$countdown.countdown( countSettings.date, function( event ) {
				var templateStr = '<div class="countdown-clock">'
				                  + '<div class="clock-item days"><span class="number">%D</span><span class="text">' + countSettings.daysText + '</span></div>'
				                  + '<span class="clock-divider days"></span>'
				                  + '<div class="clock-item hours"><span class="number">%H</span><span class="text">' + countSettings.hoursText + '</span></div>'
				                  + '<span class="clock-divider hours"></span>'
				                  + '<div class="clock-item minutes"><span class="number">%M</span><span class="text">' + countSettings.minutesText + '</span></div>'
				                  + '<span class="clock-divider minutes"></span>'
				                  + '<div class="clock-item seconds"><span class="number">%S</span><span class="text">' + countSettings.secondsText + '</span></div>'
				                  + '</div>';
				$( this ).html( event.strftime( templateStr ) );
			} );
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-countdown.default', CountdownHandler );
		} );
	}
)( jQuery );
