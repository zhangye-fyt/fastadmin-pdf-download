(
	function( $ ) {
		var form = $( '#minimog-register-form' );
		var $shopUrlPreview = form.find( '#ip_reg-url-alart' );
		var $sellerUrl = form.find( '#ip_reg_seller_url' );

		var Minimog_Dokan_Vendor_Registration = {
			init: function() {
				$( '.form-user-role input[type=radio]', form ).on( 'change', this.showSellerFields );

				$( '#ip_reg_shop_phone', form ).on( 'keydown', this.ensurePhoneNumber );
				$( '#ip_reg_company_name', form ).on( 'focusout', this.generateSlugFromCompany );

				$sellerUrl.on( 'keydown', this.constrainSlug );
				$sellerUrl.on( 'keyup', this.renderUrl );
			},
			showSellerFields: function() {
				var value = $( this ).val(),
				    $form = $( this ).closest( 'form' );

				if ( value === 'seller' ) {
					$form.find( '.show-if-seller' ).removeClass( 'display-none' );
				} else {
					$form.find( '.show-if-seller' ).addClass( 'display-none' );
				}
			},
			ensurePhoneNumber: function( e ) {
				// Allow: backspace, delete, tab, escape, enter and .
				if ( $.inArray( e.keyCode, [ 46, 8, 9, 27, 13, 91, 107, 109, 110, 187, 189, 190 ] ) !== - 1 ||

				     // Allow: Ctrl+A
				     (
					     e.keyCode == 65 && e.ctrlKey === true
				     ) ||

				     // Allow: home, end, left, right
				     (
					     e.keyCode >= 35 && e.keyCode <= 39
				     ) ) {
					// let it happen, don't do anything
					return;
				}

				if ( e.shiftKey && e.key === '.' ) {
					return;
				}

				// Ensure that it is a number and stop the keypress
				if ( (
					e.shiftKey && ! isNaN( Number( e.key ) )
				) ) {
					return;
				}

				if ( isNaN( Number( e.key ) ) ) {
					e.preventDefault();
				}
			},
			generateSlugFromCompany: function() {
				var value = getSlug( $( this ).val() );

				$sellerUrl.val( value );
				$shopUrlPreview.text( value );
				$sellerUrl.focus();
			},
			constrainSlug: function( e ) {
				var text = $( this ).val();

				// Allow: backspace, delete, tab, escape, enter and .
				if ( $.inArray( e.keyCode, [ 46, 8, 9, 27, 13, 91, 109, 110, 173, 189, 190 ] ) !== - 1 ||
				     // Allow: Ctrl+A
				     (
					     e.keyCode == 65 && e.ctrlKey === true
				     ) ||
				     // Allow: home, end, left, right
				     (
					     e.keyCode >= 35 && e.keyCode <= 39
				     ) ) {
					// let it happen, don't do anything
					return;
				}

				if ( (
					     e.shiftKey || (
						                   e.keyCode < 65 || e.keyCode > 90
					                   ) && (
						                   e.keyCode < 48 || e.keyCode > 57
					                   )
				     ) && (
					     e.keyCode < 96 || e.keyCode > 105
				     ) ) {
					e.preventDefault();
				}
			},
			renderUrl: function() {
				$shopUrlPreview.text( $( this ).val() );
			},
		};

		$( function() {
			Minimog_Dokan_Vendor_Registration.init();
		} );
	}
)( jQuery );
