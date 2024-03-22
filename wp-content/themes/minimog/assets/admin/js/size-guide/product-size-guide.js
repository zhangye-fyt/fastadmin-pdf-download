jQuery( document ).ready( function( $ ) {
	$( '#minimog_size_guide-display' ).on( 'change', function() {
		var $select = $( this );

		if ( 'tab' === $select.val() ) {
			$select.closest( '.form-field' ).nextAll( '.minimog_size_guide-button_position_field' ).hide();

			$( '#minimog_size_guide-button_position' ).trigger( 'change' );
		} else {
			$select.closest( '.form-field' ).nextAll( '.minimog_size_guide-button_position_field' ).show();
		}
	} ).trigger( 'change' );
} );