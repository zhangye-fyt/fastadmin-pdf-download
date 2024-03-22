(
	function( $ ) {
		'use strict';

		var SwiperHandler = function( $scope, $ ) {
			var $slider = $scope.find( '.tm-slider-widget' );
			var $countdown = $scope.find( '.countdown' );
			var countSettings = $countdown.data();
			var daysText = countSettings.daysText;
			var hoursText = countSettings.hoursText;
			var minutesText = countSettings.minutesText;
			var secondsText = countSettings.secondsText;

			$slider.MinimogSwiper();
			$countdown.countdown( countSettings.date, function( event ) {
				var timeTemplate = '<div class="countdown-content">';

				timeTemplate += '<div class="day"><span class="number">%d</span><span class="text">' + daysText + '</span></div>';
				timeTemplate += '<div class="hour"><span class="number">%H</span><span class="text">' + hoursText + '</span></div>';
				timeTemplate += '<div class="minute"><span class="number">%M</span><span class="text">' + minutesText + '</span></div>';
				timeTemplate += '<div class="second"><span class="number">%S</span><span class="text">' + secondsText + '</span></div>';

				timeTemplate += '</div>';

				$( this ).html( event.strftime( timeTemplate ) );
			} );
		};

		var SwiperLinkedHandler = function( $scope, $ ) {
			var $element = $scope.find( '.tm-slider-widget' );

			$element.MinimogSwiper();
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product-carousel-countdown.default', SwiperHandler );
		} );
	}
)( jQuery );
