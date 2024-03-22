(
	function( $ ) {
		'use strict';

		var $body = $( 'body' );

		var MinimogGridDataHandler = function( $scope, $ ) {
			var $element = $scope.find( '.minimog-grid-wrapper' );
			$element.MinimogGridLayout().MinimogGridQuery();
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-blog.default', MinimogGridDataHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product.default', MinimogGridDataHandler );
		} );
	}
)( jQuery );
