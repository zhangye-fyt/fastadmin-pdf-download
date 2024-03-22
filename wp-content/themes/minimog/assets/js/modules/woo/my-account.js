(
	function( $ ) {
		'use strict';

		$( document ).ready( function() {
			// Remove inline css.
			$( '.mo-openid-app-icons' ).each( function() {
				$( this ).find( '.mo_btn-social' ).prop( 'style', false );
				$( this ).find( '.mo_btn-social .mofa' ).prop( 'style', false );
				$( this ).find( '.mo_btn-social svg' ).prop( 'style', false );
			} );
		} );

	}( jQuery )
);
