(
	function( $ ) {
		'use strict';

		var MinimogTabsHandler = function( $scope, $ ) {
			var $tabPanels = $scope.find( '.minimog-tabs' );

			$tabPanels.each( function() {
				var $tabs = $( this );
				var options = {};

				if ( $tabs.hasClass( 'minimog-tabs--nav-type-dropdown' ) ) {
					options.navType = 'dropdown';
				}

				$tabs.MinimogTabPanel( options );
			} );
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-tabs.default', MinimogTabsHandler );
		} );
	}
)( jQuery );
