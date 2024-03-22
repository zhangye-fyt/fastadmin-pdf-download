(
	function( $ ) {
		'use strict';

		var $attributeType = $( '#attribute_type' );
		if ( $attributeType.length > 0 ) {
			var type = $attributeType.val();

			$( '.show-on-type--' + type ).show();

			$attributeType.on( 'change', function( e ) {
				var type = $attributeType.val();

				$( '.hide-on-types' ).each( function() {
					if ( $( this ).hasClass( 'show-on-type--' + type ) ) {
						$( this ).show();
					} else {
						$( this ).hide();
					}
				} );
			} );
		}

	}( jQuery )
);
