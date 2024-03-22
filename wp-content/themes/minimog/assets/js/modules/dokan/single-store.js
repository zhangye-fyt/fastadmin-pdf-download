(
	function( $ ) {
		'use strict';

		var Helpers = window.minimog.Helpers;

		$( document ).ready( function() {
			initStoreTabs();
		} );

		function initStoreTabs() {
			if ( ! $.fn.perfectScrollbar || Helpers.isHandheld() ) {
				return;
			}

			var $storeTabs = $( '#dokan-store-tabs-wrap' );

			if ( $storeTabs.length <= 0 ) {
				return;
			}

			$storeTabs.perfectScrollbar( {
				suppressScrollY: true,
				useBothWheelAxes: true,
			} );
		}

	}( jQuery )
);
