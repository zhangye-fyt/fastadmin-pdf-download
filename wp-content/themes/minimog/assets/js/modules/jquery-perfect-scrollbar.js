(
	function( $ ) {
		'use strict';

		$.fn.perfectScrollbar = function( args ) {
			// Check if selected element exist.
			if ( ! this.length ) {
				return this;
			}

			// Ignore option theme.
			if ( typeof args === 'object' ) {
				if ( args.hasOwnProperty( 'theme' ) ) {
					delete args.theme;
				}
			}

			const namespace = 'perfectScrollbar';

			return this.each( function() {
				var $el = $( this );

				let instance = $.data( this, namespace );

				if ( instance ) { // Already created then trigger method.
					if ( typeof instance[ args ] === 'function' ) {
						// Trigger class method.
						instance[ args ]();
					}
				} else { // Create new instance.
					instance = new PerfectScrollbar( $el.get( 0 ), args );

					$.data( this, namespace, instance );
				}
			} );
		}
	}( jQuery )
);
