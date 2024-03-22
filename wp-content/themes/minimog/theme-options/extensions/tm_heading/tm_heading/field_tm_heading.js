/* global redux_change */

/*global redux_change, redux*/

(
	function( $ ) {
		"use strict";

		redux.field_objects = redux.field_objects || {};

		$( document ).ready( function() {
			for( var prop in redux.field_objects ) {
				redux.field_objects[prop].init();
			}
			var $sectionWrapper = $( '.redux-section-collapse-wrapper' );

			if ( $sectionWrapper.length > 0 ) {
				$sectionWrapper.each( function() {
					var $el = $( this ),
						id = $el.data( 'id' ),
						$selector = $( '#redux-section-collapse-wrapper-' + id ),
						$table = $( '#section-table-' + id );

					$selector.on( 'click', function(e) {
						e.preventDefault();
	
						$( this ).toggleClass( 'active' );

						$table.slideToggle(
							0,
							'swing',
							function() {
								for( var prop in redux.field_objects ) {
									redux.field_objects[prop].init();
								}
							}
						);
					});
				});
			}
		});
	}
)( jQuery );
