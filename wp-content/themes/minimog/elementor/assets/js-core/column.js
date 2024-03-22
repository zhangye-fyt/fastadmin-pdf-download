(
	function( $ ) {
		'use strict';

		class MinimogCollapsibleHandler extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						container: '.elementor-column-wrap',
						content: '.elementor-widget-wrap',
					},
					classes: {
						isActive: 'tm-collapsible--active',
						isOpen: 'tm-collapsible--open',
					},
					speed: 300,
					prevDevice: '',
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );

				return {
					$container: this.findElement( selectors.container ),
					$content: this.findElement( selectors.content ),
				};
			}

			toggle( refresh ) {
				var elementSettings = this.getElementSettings();
				var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
				    activeDevices     = elementSettings.tm_collapsible_on;

				if ( elementSettings.prevDevice !== currentDeviceMode ) {
					if ( - 1 !== activeDevices.indexOf( currentDeviceMode ) ) {
						if ( true === refresh ) {
							this.reactivate();
						} else {
							this.activate();
						}
					} else {
						this.deactivate();
					}
				}

				elementSettings.prevDevice = currentDeviceMode;
			}

			activate() {
				var elementSettings = this.getElementSettings();
				const classes = this.getSettings( 'classes' );

				this.$element.addClass( classes.isActive );

				if ( 'open' === elementSettings.tm_collapsible_status && this.$element.hasClass( classes.isActive ) ) {
					this.elements.$content.show();
					this.$element.addClass( classes.isOpen );
				} else {
					this.elements.$content.hide();
					this.$element.removeClass( classes.isOpen );
				}
			}

			deactivate() {
				const classes = this.getSettings( 'classes' );

				this.$element.removeClass( classes.isActive );
				this.$element.removeClass( classes.isOpen );
				this.elements.$content.show();
			}

			reactivate() {
				this.deactivate();
				this.activate();
			}

			onElementChange( settingKey ) {
				if ( - 1 !== [ 'collapsible', 'collapsible_on', 'tm_collapsible_status' ].indexOf( settingKey ) ) {
					this.toggle( true );
				} // Settings that trigger a re-activation when changed.
			}

			bindEvents() {
				var that = this;
				var elementSettings = this.getElementSettings();
				if ( 'undefined' !== typeof elementSettings.tm_collapsible && 'yes' === elementSettings.tm_collapsible ) {
					var prevWindowWidth = window.innerWidth;
					elementorFrontend.elements.$window.on( 'resize', function() {
						if ( prevWindowWidth != window.innerWidth ) {
							that.toggle();
						}
						prevWindowWidth = window.innerWidth;
					} );
				}
			}

			generateHeading() {
				var elementSettings = this.getElementSettings(),
				    titleText       = elementSettings.tm_collapsible_title,
				    titleSize       = elementSettings.tm_collapsible_title_size;

				var $heading = this.$element.find( '.tm-collapsible__title' );

				// Avoid duplicate while typing
				if ( $heading.length ) {
					$heading.remove();
				}

				var $title = $( document.createElement( titleSize ) );

				$title.addClass( 'tm-collapsible__title' ).removeClass( 'has-text' );

				if ( 'undefined' !== typeof titleText && titleText ) {
					$title.addClass( 'has-text' ).text( titleText );
				}

				this.elements.$container.prepend( $title );
			}

			onInit() {
				super.onInit();

				var elementSettings = this.getElementSettings();

				if ( 'undefined' !== typeof elementSettings.tm_collapsible && 'yes' === elementSettings.tm_collapsible ) {
					if ( this.isEdit ) {
						this.$element.addClass( 'elementor-column__tm-collapsible' );
					}

					const settings = this.getSettings(),
					      classes  = this.getSettings( 'classes' );

					this.generateHeading();
					this.toggle();

					var $heading = this.$element.find( '.tm-collapsible__title' );

					$heading.on( 'click', ( event ) => {
						if ( ! this.$element.hasClass( classes.isActive ) ) {
							return false;
						}

						event.preventDefault();

						this.$element.toggleClass( classes.isOpen );
						this.elements.$content.slideToggle( settings.speed );
					} );

				} else {
					this.deactivate();
				}
			}
		}

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/column', ( $element ) => {
				elementorFrontend.elementsHandler.addHandler( MinimogCollapsibleHandler, { $element } );
			} );
		} );
	}
)( jQuery );
