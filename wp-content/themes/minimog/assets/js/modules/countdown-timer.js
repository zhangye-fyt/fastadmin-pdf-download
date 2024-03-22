(
	function( $ ) {
		'use strict';

		class MinimogCountdownTimer {
			DAY_IN_MS = 24 * 60 * 60 * 1000;
			HOUR_IN_MS = 60 * 60 * 1000;
			MIN_IN_MS = 60 * 1000;

			constructor( container, options = {} ) {
				this.container = container;
				this.options = Object.assign( {}, {
					addZeroPrefix: true,
					loop: false,
					formatter: {
						day: 'd',
						hour: 'h',
						minute: 'm',
						second: 's',
					},
					callback: () => {
					}
				}, options );
				this.startTime = this.options.startTime;
				this.savedStartTime = this.startTime;
				this.endTime = this.options.endTime;
				this.intervalTime = 1000;
				this.timer = null;
				this.starting = false;
				this.start();
			}

			// jQuery methods.
			triggerMethod = ( method, options ) => {
				if ( typeof this[ method ] === 'function' ) {
					this[ method ]( options );
				} else {
					this.start();
				}
			};

			start = () => {
				if ( ! this.starting ) {
					this.timer = setInterval( () => {
						if ( this.startTime > this.endTime ) {
							this.stop();
						} else {
							this.update();
						}
					}, this.intervalTime );

					this.starting = true;
				}
			};
			update = () => {
				this.container.text( this.format( this.endTime - this.startTime ) );

				this.startTime += this.intervalTime;
			};
			stop = () => {
				clearInterval( this.timer );
				this.starting = false;
				if ( this.options.loop ) {
					this.startTime = this.savedStartTime;
					this.start();
				} else {
					this.timer = null;
					this.options.callback();
				}
			};
			clear = () => {
				clearInterval( this.timer );
				this.timer = null;
				this.starting = false;
				this.startTime = this.savedStartTime;
				this.container.text( this.format( 0 ) );
			};
			addZeroPrefix = ( num ) => {
				if ( this?.options?.addZeroPrefix && num < 10 ) {
					return `0${num}`
				}
				return num.toString()
			};
			format = ( ms ) => {
				var days    = Math.floor( ms / this.DAY_IN_MS ),
				    hours   = Math.floor( ms / this.HOUR_IN_MS ) % 24,
				    minutes = Math.floor( ms / this.MIN_IN_MS ) % 60,
				    seconds = Math.floor( ms / 1000 ) % 60;

				return this.getFormatText( days, 'day' ) + this.getFormatText( hours, 'hour' ) + this.getFormatText( minutes, 'minute' ) + this.getFormatText( seconds, 'second' );
			};
			getFormatText = ( value, type ) => {
				return value > 0 || 'minute' === type || 'second' === type ? (
					this.addZeroPrefix( value ) + this.options.formatter[ type ] + ' '
				) : '';
			}
		}

		const namespace = 'minimogCountdownTimer';

		$.fn.extend( {
			MinimogCountdownTimer: function( args, update ) {
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
						instance = new MinimogCountdownTimer( $el, args );
						$.data( this, namespace, instance );
					}
				} );
			}
		} );
	}( jQuery )
);
