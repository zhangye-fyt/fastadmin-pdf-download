(
	function( $ ) {
		'use strict';

		var MinimogAccordion = function( $el, options ) {
			this.$el = $el;
			this.$currenSection = null;
			this.ACTIVE_CLASS = 'active';
			this.defaults = {
				speed: 300,
			};
			this.settings = $.extend( {}, this.defaults, options );

			// jQuery methods.
			this.triggerMethod = ( method, options ) => {
				if ( typeof this[ method ] === 'function' ) {
					this[ method ]( options );
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

			this.init = function() {
				var plugin = this;

				// Do it only on front-end.
				plugin.$el.children( '.accordion-section.active' ).children( '.accordion-content' ).css( 'display', 'block' );

				plugin.$el.on( 'click', '.accordion-header', function( evt ) {
					evt = evt || window.event;
					evt.preventDefault();
					evt.stopPropagation();

					var section = $( this ).parent( '.accordion-section' );
					plugin.$currenSection = section;

					$( document ).trigger( 'MinimogAccordionBeforeChange', [ plugin.$el, plugin.$currenSection ] );
					plugin.$el.trigger( 'MinimogAccordionBeforeChange', [ plugin.$el, plugin.$currenSection ] );

					if ( section.hasClass( plugin.ACTIVE_CLASS ) ) {
						section.removeClass( plugin.ACTIVE_CLASS );
						section.children( '.accordion-content' ).slideUp( plugin.settings.speed, function() {
							plugin.change();
						} );
					} else {
						var parent = section.parent( '.minimog-accordion' ).first();
						if ( ! parent.data( 'multi-open' ) ) {
							parent.children( '.' + plugin.ACTIVE_CLASS )
							      .removeClass( plugin.ACTIVE_CLASS )
							      .children( '.accordion-content' )
							      .slideUp( plugin.settings.speed );
						}
						section.addClass( plugin.ACTIVE_CLASS );
						section.children( '.accordion-content' ).slideDown( plugin.settings.speed, function() {
							plugin.change();
						} );
					}
				} );
			};

			this.change = function() {
				var plugin = this;

				$( document ).trigger( 'MinimogAccordionChange', [ plugin.$el, plugin.$currenSection ] );
				plugin.$el.trigger( 'MinimogAccordionChange', [ plugin.$el, plugin.$currenSection ] );
			};

			this.init();
		};

		const namespace = 'minimogAccordion';

		$.fn.extend( {
			MinimogAccordion: function( args, update ) {
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
						instance = new MinimogAccordion( $el, args );
						$.data( this, namespace, instance );
					}
				} );
			}
		} );
	}( jQuery )
);
