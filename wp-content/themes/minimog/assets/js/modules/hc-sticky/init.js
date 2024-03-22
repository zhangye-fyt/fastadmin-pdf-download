(
	function( $ ) {
		'use strict';

		var $body = $( 'body' );

		/**
		 * Waiting for header sticky init.
		 */
		$( window ).on( 'load', function() {
			initStickyElement();
		} );

		/**
		 * Re init sticky kit when some components heights changed.
		 */
		$( document ).on( 'MinimogTabsChange', updateStickySize );
		$( document ).on( 'MinimogAccordionChange', updateStickySize );

		function initStickyElement() {
			$( '.tm-sticky-parent' ).each( function() {
				var $parent = $( this );
				var group = $parent.data( 'sticky-group' );
				var $columns = $( this ).find( '.tm-sticky-column[data-sticky-group="' + group + '"]' );
				var $stickyColumns = getStickyColumns( $columns );
				var offset = getStickyOffset();

				$stickyColumns.hcSticky( {
					stickTo: $parent,
					top: offset,
					responsive: {
						1007: { // 992px. Append scrollbar width.
							disable: true
						}
					}
				} );
			} );
		}

		function updateStickyOffset() {
			$( '.tm-sticky-parent' ).each( function() {
				var $parent = $( this );
				var group = $parent.data( 'sticky-group' );
				var $columns = $( this ).find( '.tm-sticky-column[data-sticky-group="' + group + '"]' );
				var $stickyColumns = getStickyColumns( $columns );
				var offset = getStickyOffset();

				$stickyColumns.hcSticky( 'update', {
					top: offset
				} );
			} );
		}

		function updateStickySize() {
			$( '.tm-sticky-parent' ).each( function() {
				var $parent = $( this );
				var group = $parent.data( 'sticky-group' );
				var $columns = $( this ).find( '.tm-sticky-column[data-sticky-group="' + group + '"]' );
				var $stickyColumns = getStickyColumns( $columns );
				var offset = getStickyOffset();

				$stickyColumns.hcSticky( 'refresh' );

				$( window ).trigger( 'scroll' );
			} );
		}

		function getStickyColumns( $columns ) {
			var $highestColumn;
			var highestHeight = 0;

			$columns.each( function() {
				var thisArea = $( this ).outerHeight();
				if ( thisArea > highestHeight ) {
					$highestColumn = $( this );
					highestHeight = thisArea;
				}
			} );

			return $columns.not( $highestColumn );
		}

		function getStickyOffset() {
			var $pageHeader = $( '#page-header' );
			var offset = 0;

			if ( $body.hasClass( 'header-pinned' ) && $pageHeader.length > 0 ) {
				offset = $pageHeader.find( '#page-header-inner' ).outerHeight();

				/**
				 * Header sticky smaller than normal header.
				 */
				if ( ! $pageHeader.hasClass( 'header-pinned' ) ) {
					$pageHeader.addClass( 'header-pinned' );
					offset = $pageHeader.find( '#page-header-inner' ).outerHeight();
					$pageHeader.removeClass( 'header-pinned' );
				}

				if ( isNaN( offset ) || offset < 0 ) {
					offset = 70;
				}
			}

			if ( $body.hasClass( 'admin-bar' ) ) {
				offset += 32;
			}

			/**
			 * Spacing header with content.
			 */
			offset += 30;

			return offset;
		}
	}( jQuery )
);
