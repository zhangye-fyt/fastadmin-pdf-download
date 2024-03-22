( function( $ ) {
		'use strict';

		$( document ).ready( function () {
			$( '.sale_price_dates_fields' ).each( function () {
				var $these_sale_dates = $( this );
				var sale_schedule_set = false;
				var $wrap = $these_sale_dates.closest( 'div, table' );

				$these_sale_dates.find( 'input' ).each( function () {
					if ( '' !== $( this ).val() ) {
						sale_schedule_set = true;
					}
				} );

				if ( ! sale_schedule_set ) {
					$( '.woo-sctr-countdown-timer-admin-product' ).slideUp( 0 );
				}
			} );

			$( '.sale_schedule' ).on( 'click', function(e) {
				e.preventDefault();

				$( this ).closest( '.form-field' ).siblings( '.woo-sctr-countdown-timer-admin-product' ).slideDown(0);
			} );

			$( '.cancel_sale_schedule' ).on( 'click', function(e) {
				e.preventDefault();

				$( this ).closest( '.form-field' ).siblings( '.woo-sctr-countdown-timer-admin-product' ).slideUp(0);
			} );
		});

		$( '#woocommerce-product-data' ).on( 'woocommerce_variations_loaded', function(e) {
			$( '.woo-sctr-countdown-timer-admin-product' ).slideUp();

			$( '.sale_schedule' ).on( 'click', function(e) {
				e.preventDefault();

				$( this ).closest( '.form-field' ).siblings( '.woo-sctr-countdown-timer-admin-product' ).slideDown(0);
			} );

			$( '.cancel_sale_schedule' ).on( 'click', function(e) {
				e.preventDefault();

				$( this ).closest( '.form-field' ).siblings( '.woo-sctr-countdown-timer-admin-product' ).slideUp(0);
			} );
		});

}( jQuery ));
