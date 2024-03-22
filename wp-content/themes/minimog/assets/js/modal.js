(
	function( $ ) {
		'use strict';

		var MinimogModal = function( $el, options ) {
			this.$el          = $el;
			this.ACTIVE_CLASS = 'open';
			this.initialized  = false;
			this.defaults     = {
				perfectScrollbar: true,
				afterInit: () => {
				}
			};
			this.settings     = $.extend( {}, this.defaults, options );

			// jQuery methods.
			this.triggerMethod = ( method, options ) => {
				if ( typeof this[method] === 'function' ) {
					this[method]( options );
				}
			};

			this.setOptions = function( options ) {
				options = options || {};

				this.settings = $.extend( {}, this.settings, options );
			};

			this.getOptions = function() {
				return this.settings;
			};

			this.update = function( options ) {
				this.setOptions( options );
			};

			this.setHeight = function( newHeight ) {
				this.$el.find( '.modal-content-wrap' ).height( newHeight );
			};

			this.init = function() {
				var plugin = this;

				if ( false === plugin.initialized ) {
					if ( this.settings.dynamic ) {
						$el.addClass( 'minimog-modal' );
						$el.html( `<div class="modal-overlay"></div><div class="modal-content"><div class="button-close-modal"></div><div class="modal-content-wrap"><div class="modal-content-inner"></div></div></div>` );
						this.settings.afterInit( this );
					}

					$el.on( 'click', '.modal-overlay, .button-close-modal', function( e ) {
						e.preventDefault();
						e.stopPropagation();

						plugin.close();
					} );

					$( document.body ).on( 'keyup', function( e ) {
						// Esc button
						if ( e.keyCode === 27 ) {
							plugin.close();
						}
					} );

					plugin.initialized = true;
					$( document.body ).trigger( 'MinimogModalInit', [ $el ] );

					plugin.open();
				}
			};

			this.open = function() {
				var plugin = this;

				$( '.minimog-modal' ).filter( '.' + plugin.ACTIVE_CLASS ).removeClass( plugin.ACTIVE_CLASS ).attr( 'aria-hidden', 'true' ).prop( 'hidden', true );

				plugin.init();

				$el.addClass( plugin.ACTIVE_CLASS ).attr( 'aria-hidden', 'false' ).prop( 'hidden', false );

				window.minimog.Helpers.setBodyOverflow();

				if ( this.settings.perfectScrollbar && $.fn.perfectScrollbar && ! window.minimog.Helpers.isHandheld() ) {
					$el.find( '.modal-content-wrap' ).perfectScrollbar();
				}

				$( document.body ).trigger( 'MinimogModalOpen', [ $el ] );
				$el.trigger( 'MinimogModalOpen' );
			};

			this.close = function() {
				var plugin = this;

				$el.removeClass( plugin.ACTIVE_CLASS ).attr( 'aria-hidden', 'true' ).prop( 'hidden', true );

				window.minimog.Helpers.unsetBodyOverflow();

				$( document.body ).trigger( 'MinimogModalClose', [ $el ] );
				$el.trigger( 'MinimogModalClose' );

				if ( this.settings.dynamic ) {
					$el.remove();
				}
			};

			this.init();
		};

		const namespace = 'minimogModal';

		$.fn.extend( {
			MinimogModal: function( args, update ) {
				// Check if selected element exist.
				if ( ! this.length ) {
					return this;
				}

				// We need to return options.
				if ( args === 'options' ) {
					return $.data( this.get( 0 ), namespace ).getOptions();
				}

				return this.each( function() {
					var $el = $( this );

					let instance = $.data( this, namespace );

					if ( instance ) { // Already created then trigger method.
						instance.triggerMethod( args, update );
					} else { // Create new instance.
						instance = new MinimogModal( $el, args );
						$.data( this, namespace, instance );
					}
				} );
			}
		} );
	}( jQuery )
);
