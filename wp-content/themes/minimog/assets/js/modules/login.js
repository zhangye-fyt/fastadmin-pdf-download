(
	function( $ ) {
		'use strict';

		var Helpers     = window.minimog.Helpers,
		    valMessages = $minimogLogin.validatorMessages;

		$.extend( $.validator.messages, valMessages.simple, {
			maxlength: $.validator.format( valMessages.format.maxlength ),
			minlength: $.validator.format( valMessages.format.minlength ),
			rangelength: $.validator.format( valMessages.format.rangelength ),
			range: $.validator.format( valMessages.format.range ),
			max: $.validator.format( valMessages.format.max ),
			min: $.validator.format( valMessages.format.min )
		} );

		$( document ).ready( function() {
			var $body              = $( 'body' ),
			    $modalLogin        = $( '#modal-user-login' ),
			    $modalRegister     = $( '#modal-user-register' ),
			    $modalLostPassword = $( '#modal-user-lost-password' );

			// Remove inline css.
			$modalLogin.find( '.mo-openid-app-icons .mo_btn-social' ).prop( 'style', false );
			$modalLogin.find( '.mo-openid-app-icons .mo_btn-social .mofa' ).prop( 'style', false );
			$modalLogin.find( '.mo-openid-app-icons .mo_btn-social svg' ).prop( 'style', false );

			$modalLogin.find( '#minimog-login-form' ).validate( {
				rules: {
					user_login: {
						required: true
					},
					password: {
						required: true,
					}
				},
				submitHandler: function( form ) {
					var $form      = $( form ),
					    $submitBtn = $form.find( 'button[type="submit"]' );

					$.ajax( {
						url: $minimog.ajaxurl,
						type: 'POST',
						cache: false,
						dataType: 'json',
						data: $form.serialize(),
						success: function( response ) {
							if ( ! response.success ) {
								$form.find( '.form-response-messages' ).html( response.data.messages ).addClass( 'error' ).show();
								if ( typeof hcaptcha !== 'undefined' ) {
									var captchaID = $form.find( '.h-captcha' ).find( 'iframe' ).data( 'hcaptcha-widget-id' );
									hcaptcha.reset( captchaID );
								}
							} else {
								$form.find( '.form-response-messages' ).html( response.data.messages ).addClass( 'success' ).show();

								if ( '' !== response.data.redirect_url ) {
									window.location.href = response.data.redirect_url;
								} else {
									location.reload();
								}
							}
						},
						beforeSend: function() {
							$form.find( '.form-response-messages' ).html( '' ).removeClass( 'error success' ).hide();
							Helpers.setElementHandling( $submitBtn );
						},
						complete: function() {
							Helpers.unsetElementHandling( $submitBtn );
						}
					} );
				}
			} );

			if ( $body.hasClass( 'required-login' ) && ! $body.hasClass( 'logged-in' ) ) {
				$modalLogin.MinimogModal( 'open' );
			}

			$body.on( 'click', '.open-modal-login', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				$modalLogin.MinimogModal( 'open' );
			} );

			$body.on( 'click', '.open-modal-register', function( e ) {
				e.preventDefault();
				e.stopPropagation();


				$modalRegister.MinimogModal( 'open' );
			} );

			$body.on( 'click', '.open-modal-lost-password', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				$modalLostPassword.MinimogModal( 'open' );
			} );

			$body.on( 'click', '.btn-pw-toggle', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				var groupField = $( this ).parent( '.form-input-password' );
				var pwField = groupField.children( 'input' );

				if ( 'password' === pwField.attr( 'type' ) ) {
					pwField.attr( 'type', 'text' );
					groupField.addClass( 'show-pw' );
				} else {
					pwField.attr( 'type', 'password' );
					groupField.removeClass( 'show-pw' );
				}
			} );

			$modalRegister.find( '#minimog-register-form' ).validate( {
				rules: {
					fullname: {
						required: true,
					},
					username: {
						required: true,
						minlength: 4,
					},
					email: {
						required: true,
						email: true
					},
					password: {
						required: true,
						minlength: 8,
						maxlength: 30
					},
				},
				submitHandler: function( form ) {
					var $form      = $( form ),
					    $submitBtn = $form.find( 'button[type="submit"]' );

					$.ajax( {
						url: $minimog.ajaxurl,
						type: 'POST',
						cache: false,
						dataType: 'json',
						data: $form.serialize(),
						success: function( response ) {
							if ( ! response.success ) {
								$form.find( '.form-response-messages' ).html( response.data.messages ).addClass( 'error' ).show();
								if ( typeof hcaptcha !== 'undefined' ) {
									var captchaID = $form.find( '.h-captcha' ).find( 'iframe' ).data( 'hcaptcha-widget-id' );
									hcaptcha.reset( captchaID );
								}
							} else {
								$form.find( '.form-response-messages' ).html( response.data.messages ).addClass( 'success' ).show();
								location.reload();
							}
						},
						beforeSend: function() {
							$form.find( '.form-response-messages' ).html( '' ).removeClass( 'error success' ).hide();
							Helpers.setElementHandling( $submitBtn );
						},
						complete: function() {
							Helpers.unsetElementHandling( $submitBtn );
						}
					} );
				}
			} );

			$modalLostPassword.find( '#minimog-lost-password-form' ).on( 'submit', function( evt ) {
				evt.preventDefault();

				var $form      = $( this ),
				    $submitBtn = $form.find( 'button[type="submit"]' );

				$.ajax( {
					type: 'post',
					url: $minimog.ajaxurl,
					dataType: 'json',
					data: $form.serialize(),
					success: function( response ) {
						if ( ! response.success ) {
							$form.find( '.form-response-messages' ).html( response.data.messages ).addClass( 'error' ).show();
							if ( typeof hcaptcha !== 'undefined' ) {
								var captchaID = $form.find( '.h-captcha' ).find( 'iframe' ).data( 'hcaptcha-widget-id' );
								hcaptcha.reset( captchaID );
							}
						} else {
							$form.find( '.form-response-messages' ).html( response.data.messages ).addClass( 'success' ).show();
						}
					},
					beforeSend: function() {
						$form.find( '.form-response-messages' ).html( '' ).removeClass( 'error success' ).hide();
						Helpers.setElementHandling( $submitBtn );
					},
					complete: function() {
						Helpers.unsetElementHandling( $submitBtn );
					}
				} );
			} );
		} );

	}( jQuery )
);
