(
	function( $ ) {
		'use strict';

		var initProductImages = function( $scope, $ ) {
			var $element = $scope.find( '.minimog-feature-product' ),
				$sliderWrap = $element.find( '.woo-single-gallery' );

			if ( 0 >= $sliderWrap.length ) {
				return;
			}

			var options = {};
			if ( $sliderWrap.hasClass( 'has-thumbs-slider' ) ) {
				var thumbsSlider = $sliderWrap.find( '.minimog-thumbs-swiper' ).MinimogSwiper();
				options = {
					thumbs: {
						swiper: thumbsSlider
					}
				};
			}

			var mainSlider = $sliderWrap.children( '.minimog-main-swiper' ).MinimogSwiper( options );

			var $form = $element.find( '.variations_form' );
			var variations = $form.data( 'product_variations' );

			$form.find( 'select' ).on( 'change', function() {
				var isFieldSelected = true;
				var needReset = false;
				var globalAttrs = {};

				var formValues = $form.serializeArray();

				// Check all attributes selected.
				for ( var i = 0; i < formValues.length; i ++ ) {

					var _name = formValues[ i ].name;

					if ( _name.substring( 0, 10 ) === 'attribute_' ) {

						globalAttrs[ _name ] = formValues[ i ].value;

						if ( formValues[ i ].value === '' ) {
							isFieldSelected = false;

							break;
						}
					}
				}

				if ( isFieldSelected === true ) {
					// Convert to array.
					var selectedAttributes = Object.entries( globalAttrs );

					var variationImageID = 0;
					var minMatch = 0;

					for ( var i = variations.length - 1; i >= 0; i -- ) {
						var currentVariation = variations[ i ];
						var currentAttributes = Object.entries( currentVariation.attributes ); // Convert to array.
						var loopMatch = 0;

						// Compare selected variation with all variations to find best matches.
						currentAttributes.forEach( ( [ key, value ] ) => {
							selectedAttributes.forEach( ( [ selectedKey, selectedValue ] ) => {

								if ( selectedKey === key ) {
									if ( selectedValue === value
									     || '' === value // Any Terms.
									) {
										loopMatch ++;
									}
								}
							} );
						} );

						if ( minMatch < loopMatch ) {
							minMatch = loopMatch;
							variationImageID = currentVariation.image_id;
						}
					}

					if ( variationImageID ) {
						mainSlider.$wrapperEl.find( '.swiper-slide' ).each( function( index ) {
							var slideImageID = $( this ).attr( 'data-image-id' );
							slideImageID = parseInt( slideImageID );

							if ( slideImageID === variationImageID ) {
								mainSlider.slideTo( index );

								return false;
							}
						} );
					} else {
						needReset = true;
					}
				} else {
					needReset = true;
				}

				// Reset to main image.
				if ( needReset ) {
					var $mainImage = mainSlider.$wrapperEl.find( '.product-main-image' );
					var index = $mainImage.index();
					mainSlider.slideTo( index );
				}
			} );
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-feature-product.default', initProductImages );
		} );
	}
)( jQuery );
