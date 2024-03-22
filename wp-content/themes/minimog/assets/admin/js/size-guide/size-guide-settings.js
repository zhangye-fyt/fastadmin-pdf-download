jQuery( document ).ready( function( $ ) {
	$( 'select#minimog_size_guide_display' ).on( 'change', function() {
		var $el = $( this );

		if ( 'tab' === $el.val() ) {
			$el.closest( 'tr' ).nextAll( ':lt(2)' ).hide();
		} else {
			$el.closest( 'tr' ).nextAll( ':lt(2)' ).show();
		}
	} ).trigger( 'change' );
} );