(
	function( $ ) {
		'use strict';

		var $window = $( window );
		var $body   = $( 'body' );

		var Helpers = window.minimog.Helpers;

		$( document ).ready( function() {
			initQuickViewPopup();
			addProductVariationToCart();
			singleProductAddToCart();
			loopProductVariationSelection();
			widgetProductCategories();
			initShopOffSidebar();
			$body.on( 'isw_selected', swatchesSelected );
			combinedVariationsDropdownTrigger();
			wooAdvancedDiscounts();
		} );

		$( document ).on( 'found_variation', '.variations_form', function( evt, variation ) {
			var $form             = $( evt.target ),
			    $product          = $form.closest( '.product' ),
			    $productPrice     = $product.find( '.entry-price-wrap' ).find( '.price' ),
			    $productSaleBadge = $product.find( '.entry-product-badges' );

			if ( variation.price_html ) {
				// Unwrap .price
				Helpers.setContentHTML( $productPrice, $( variation.price_html ).html() );
			}

			if ( typeof variation.sale_flash_html === 'string' ) {
				Helpers.setContentHTML( $productSaleBadge, $productSaleBadge.html() );
				$productSaleBadge.children( '.onsale, .flash-sale' ).remove();
				$productSaleBadge.prepend( variation.sale_flash_html )
			}
		} );

		$( document ).on( 'hide_variation', '.variations_form', function( evt, variation ) {
			var $form             = $( evt.target ),
			    $entrySummary     = $form.closest( '.entry-summary' ),
			    $productPrice     = $entrySummary.children( '.entry-price-wrap' ).children( '.price' ),
			    $productSaleBadge = $entrySummary.find( '.entry-product-badges' );

			Helpers.resetContentHTML( $productPrice );
			Helpers.resetContentHTML( $productSaleBadge );
		} );

		// Auto selected other attributes if they has only value.
		$( document ).on( 'change.wc-variation-form', '.variations select', function( evt ) {
			var $thisSelect = $( evt.currentTarget ),
			    $form       = $thisSelect.closest( '.variations_form' ),
			    $allSelects = $form.find( '.variations select' ).not( $thisSelect );

			$allSelects.each( function() {
				if ( $( this ).children( 'option' ).length === 2 ) {
					var newValue = $( this ).children( 'option:eq(1)' ).val();

					if ( $( this ).val() !== newValue ) {
						$( this ).val( newValue ).trigger( 'change' );
						$( this ).siblings( '.isw-swatch' ).find( '.isw-term' ).removeClass( 'isw-selected' ).filter( '[data-term="' + newValue + '"]' ).addClass( 'isw-selected' );
						return true;
					}
				}
			} );
		} );

		/**
		 * Support Product Bundle + Frequently Bought Together.
		 */
		function combinedVariationsDropdownTrigger() {
			$( document.body ).on( 'change', '.product-variation-select', function() {
				var $form = $( this ).parents( '.minimog-variation-select-wrap' ).find( '.variations_form' );
				var val   = $( this ).val();

				if ( '' !== val ) {
					var attributes = JSON.parse( val );

					for ( const key in attributes ) {
						$form.find( 'select[name="' + key + '"]' ).val( attributes[key] ).trigger( 'change' );
					}
				} else {
					$form.find( '.reset_variations' ).trigger( 'click' );
				}
			} );
		}

		function swatchesSelected( evt, attr, term, title, $thisTerm ) {
			var $termRow = $thisTerm.parents( '.row-isw-swatch' ).first();

			if ( $termRow.hasClass( 'row-isw-swatch--isw_color' ) || $termRow.hasClass( 'row-isw-swatch--isw_image' ) ) {
				$termRow.find( '.selected-term-name' ).html( title );
			}
		}

		function initShopOffSidebar() {
			var $pageSidebar = $( '.page-sidebar' );

			$( document ).on( 'click', '.btn-js-open-off-sidebar', function( evt ) {
				evt.preventDefault();
				var $toggleBtn  = $( this ),
				    $theSidebar = $pageSidebar.filter( '.sidebar-' + $toggleBtn.data( 'sidebar-target' ) );

				if ( $theSidebar.length > 0 ) {
					$theSidebar.addClass( 'off-sidebar-active' );
					$body.addClass( 'off-sidebar-opened' );
					Helpers.setBodyOverflow();

					var $sidebarContent = $theSidebar.find( '.page-sidebar-content-wrap' );
					$sidebarContent.outerHeight( window.innerHeight );

					if ( $.fn.perfectScrollbar && ! Helpers.isHandheld() ) {
						$sidebarContent.perfectScrollbar( {
							suppressScrollX: true
						} );
					}
				}
			} );

			$( document ).on( 'click', '.btn-close-off-sidebar', function( evt ) {
				evt.preventDefault();

				$body.removeClass( 'off-sidebar-opened' );
				$pageSidebar.removeClass( 'off-sidebar-active' );
				Helpers.unsetBodyOverflow();
			} );

			$pageSidebar.on( 'click', function( e ) {
				if ( e.target !== this ) {
					return;
				}

				$body.removeClass( 'off-sidebar-opened' );
				$pageSidebar.removeClass( 'off-sidebar-active' );
				Helpers.unsetBodyOverflow();
			} );

			var breakpoint = 991;

			if ( $body.hasClass( 'page-sidebar1-off-mobile' ) || $body.hasClass( 'page-sidebar2-off-mobile' ) ) {
				var prevWW = window.innerWidth;
				toggleOffSidebar();

				$window.on( 'resize', function() {
					var isToggle = true;
					if ( prevWW !== window.innerWidth ) { // Horizontal resize only.
						if ( (
							     prevWW <= breakpoint && window.innerWidth <= breakpoint
						     ) || (
							     prevWW > breakpoint && window.innerWidth > breakpoint
						     ) ) {
							isToggle = false;
						}

						if ( isToggle ) {
							toggleOffSidebar();
						}
					}

					prevWW = window.innerWidth;
				} );
			}

			function toggleOffSidebar() {
				var $offSidebars = $pageSidebar.filter( '.sidebar-off-mobile' );

				if ( window.innerWidth <= breakpoint ) {
					$offSidebars.addClass( 'sidebar-switching sidebar-off' );

					var $sidebarContent = $offSidebars.find( '.page-sidebar-content-wrap' );
					$sidebarContent.outerHeight( window.innerHeight );

					if ( $.fn.perfectScrollbar && ! Helpers.isHandheld() ) {
						$sidebarContent.perfectScrollbar( {
							suppressScrollX: true
						} );
					}
				} else {
					$offSidebars.addClass( 'sidebar-switching' ).removeClass( 'sidebar-off' );
					$body.removeClass( 'off-sidebar-opened' );
					Helpers.unsetBodyOverflow();

					var $sidebarContent = $offSidebars.find( '.page-sidebar-content-wrap' ),
					    ps              = $sidebarContent.data( 'perfectScrollbar' );

					$sidebarContent.css( 'height', 'auto' );

					if ( $.fn.perfectScrollbar && ps ) {
						ps.destroy();
					}
				}

				setTimeout( function() {
					$offSidebars.removeClass( 'sidebar-switching' );
				}, 350 );
			}
		}

		function initQuickViewPopup() {
			// Close quick view modal when product adding to wishlist.
			$( document.body ).on( 'click', '.modal-quick-view-popup .woosw-btn', function() {
				var $quickViewModal = $( this ).closest( '.modal-quick-view-popup' );

				if ( $quickViewModal.length > 0 && $.fn.MinimogModal ) {
					$quickViewModal.MinimogModal( 'close' );
				}
			} );

			$( '.minimog-product' ).on( 'click', '.quick-view-btn', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				var $button = $( this );

				// Avoid multi clicks.
				if ( $button.hasClass( 'loading' ) ) {
					return;
				}

				if ( ! $.fn.MinimogModal ) {
					return;
				}

				$button.addClass( 'loading' );
				var productID = $button.data( 'pid' );

				/**
				 * Avoid duplicate ajax request.
				 */
				var $popup = $body.children( '#' + 'modal-quick-view-product-' + productID );
				if ( $popup.length > 0 ) {
					openQuickViewPopup( $popup, $button );
				} else {
					var data = {
						action: 'product_quick_view',
						pid: productID
					};

					$.ajax( {
						url: $minimog.ajaxurl,
						type: 'GET',
						data: $.param( data ),
						dataType: 'json',
						cache: true,
						success: function( results ) {
							$popup = $( results.template );
							$body.append( $popup );
							openQuickViewPopup( $popup, $button );
						},
					} );
				}
			} );
		}

		function openQuickViewPopup( $popup, $button ) {
			var $contentWrap    = $popup.find( '.product-container' ),
			    $productWrap    = $contentWrap.find( '.woo-single-info' ),
			    $productImages  = $contentWrap.find( '.woo-single-images' ),
			    $variationsForm = $popup.find( '.cart.variations_form' );

			// Re init add to cart form variation.
			if ( $variationsForm.length > 0 && typeof wc_add_to_cart_variation_params !== 'undefined' ) {
				$variationsForm.wc_variation_form();

				if ( typeof isw != 'undefined' && typeof isw.Swatches !== 'undefined' ) {
					isw.Swatches.register( $variationsForm );
				}
			}

			Helpers.handleLazyImages( $popup );

			var $form         = $popup.find( '.variations_form' ),
			    $renderTarget = $variationsForm.find( '.row-isw-swatch-quantity-wrap' );

			$form.find( 'select[name="quantity"]' ).MinimogNiceSelect( { // Re init quantity dropdown.
				renderTarget: $renderTarget
			} );

			var $sliderWrap   = $productImages.children( '.woo-single-gallery' ),
			    $mainSlider   = $sliderWrap.children( '.minimog-main-swiper' ),
			    sliderOptions = {};
			if ( $sliderWrap.hasClass( 'has-thumbs-slider' ) ) {
				var thumbsSlider = $sliderWrap.children( '.minimog-thumbs-swiper' ).MinimogSwiper();
				sliderOptions    = {
					thumbs: {
						swiper: thumbsSlider
					}
				};
			}

			if ( $mainSlider.length > 0 ) {
				$mainSlider.MinimogSwiper( sliderOptions );
			}

			var modalPS = true;

			if ( 767 < (
				window.innerWidth - Helpers.getScrollbarWidth()
			) ) {
				var productImageHeight = $productImages.height(),
				    popupHeight        = productImageHeight,
				    popupContentHeight = productImageHeight,
				    maxHeight          = window.innerHeight - 60; // Max height of popup = Window height - top bottom spacing.

				var $scrollable = $productWrap;
				modalPS         = false;

				var settings = $contentWrap.data( 'quick-view' );

				var contentPadding = settings.spacing * 2;
				if ( settings.imageCover ) {
					$scrollable        = $contentWrap.find( '.quick-view-col-summary .inner-content' );
					popupHeight        = productImageHeight + contentPadding;
					popupContentHeight = Math.min( maxHeight, popupHeight );

					if ( settings.spacing > 0 ) {
						popupContentHeight -= contentPadding;
					} else {
						popupContentHeight -= 60;
					}
				} else {
					popupHeight        = productImageHeight + contentPadding;
					popupContentHeight = Math.min( maxHeight, popupHeight );
					popupContentHeight -= contentPadding;
				}

				$scrollable.outerHeight( parseInt( popupContentHeight ) );

				if ( $.fn.perfectScrollbar && ! Helpers.isHandheld() ) {
					$scrollable.perfectScrollbar( {
						suppressScrollX: true
					} );
				}

				if ( settings.imageCover ) {
					$contentWrap.find( '.woo-single-images' ).addClass( 'image-cover' );
				}
			}

			if ( $popup.data( 'minimogModal' ) ) {
				$popup.MinimogModal( 'open' );
			} else {
				$popup.MinimogModal( {
					perfectScrollbar: modalPS
				} );
			}

			$button.removeClass( 'loading' );
		}

		function singleProductAddToCart() {
			// wc_add_to_cart_params is required to continue, ensure the object exists.
			if ( typeof wc_add_to_cart_params === 'undefined' ) {
				return false;
			}

			/**
			 * Ajax add to cart.
			 * button query selector to compatible with Lumise add on.
			 */
			$( document ).on( 'click', 'button.single_add_to_cart_button.ajax_add_to_cart', function( evt ) {
				evt.preventDefault();

				var $thisButton = $( this );

				if ( $thisButton.hasClass( 'disabled' ) ) { // Variation select required.
					return false;
				}

				if ( $thisButton.hasClass( 'woosb-disabled' ) ) { // Smart Bundle select required.
					return false;
				}

				var $variationsForm = $thisButton.closest( 'form.cart' ),
				    productID       = $variationsForm.find( '[name=add-to-cart]' ).val(),
				    variationID     = $variationsForm.find( 'input[name=variation_id]' ).val(),
				    quantity        = $variationsForm.find( '.quantity .qty[name=quantity]' ).val();

				if ( 'add-to-cart' === $thisButton.attr( 'name' ) ) {
					productID = $thisButton.val();
				}

				if ( 0 === productID ) {
					return;
				}

				var data = {
					product_id: productID,
					variation_id: variationID,
				};

				$variationsForm.serializeArray().map( function( attr ) {
					if ( attr.name !== 'add-to-cart' ) {
						if ( attr.name.endsWith( '[]' ) ) {
							let name = attr.name.substring( 0, attr.name.length - 2 );
							if ( ! (
								name in data
							) ) {
								data[name] = [];
							}
							data[name].push( attr.value );
						} else {
							data[attr.name] = attr.value;
						}
					}
				} );

				// Custom quantity per button.
				if ( $thisButton.attr( 'data-qty' ) ) {
					quantity = parseInt( $thisButton.attr( 'data-qty' ) );
				}
				data.quantity = quantity;

				$thisButton.removeClass( 'added' ).addClass( 'loading updating-icon' );

				// Trigger event.
				$( 'body' ).trigger( 'adding_to_cart', [ $thisButton, data ] );

				// Ajax action.
				$.post( Helpers.getAjaxUrl( 'woocommerce_add_to_cart' ), data, function( response ) {

					if ( ! response ) {
						return;
					}

					if ( response.error && response.product_url ) {
						window.location = response.product_url;
						return;
					}

					// Redirect to checkout for Buy Now button.
					var redirect = $thisButton.data( 'redirect' );
					if ( redirect && '' !== redirect ) {
						window.location = redirect;
						return;
					}

					// Redirect to cart option.
					if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
						window.location = wc_add_to_cart_params.cart_url;
						return;
					}

					// Trigger event so themes can refresh other areas.
					$( document.body ).trigger( 'added_to_cart', [
						response.fragments, response.cart_hash, $thisButton
					] );

				} ).always( function() {
					$thisButton.addClass( 'added' ).removeClass( 'loading updating-icon' );
				} );
			} );
		}

		function addProductVariationToCart() {
			// wc_add_to_cart_params is required to continue, ensure the object exists.
			if ( typeof wc_add_to_cart_params === 'undefined' ) {
				return false;
			}

			// Ajax add product variation to cart
			$( document ).on( 'click', '.ajax_add_variation_to_cart', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				var $thisbutton  = $( this ),
				    quantity     = $thisbutton.data( 'quantity' ),
				    variation_id = 0,
				    data         = {};

				var $variationSelect = $thisbutton.siblings( 'select[name="product_variation_id"]' );

				if ( $variationSelect.length > 0 ) { // Combined attributes dropdown.
					var $variationSelectedOption = $variationSelect.children( ':selected' );
					variation_id                 = $variationSelectedOption.data( 'variation-id' );
					data                         = $variationSelectedOption.val();
					data                         = JSON.parse( data );
				} else {
					var $variationsForm = $thisbutton.parents( '.product' ).first().find( '.variations_form' );

					if ( $variationsForm.length > 0 ) {
						var check = true;

						var variations = $variationsForm.find( 'select[name^=attribute]' );

						variations.each( function() {
							var $this          = $( this ),
							    attributeName  = $this.attr( 'name' ),
							    attributeValue = $this.val();

							if ( attributeValue.length === 0 ) {
								check = false;
							} else {
								data[attributeName] = attributeValue;
							}
						} );

						if ( ! check ) {
							window.alert( 'Please select a product option before adding this product to your cart.' );

							return false;
						}

						variation_id = $variationsForm.find( '.variation_id' ).val();
					}
				}

				if ( ! variation_id || ! data ) {
					window.alert( 'Please select a product option before adding this product to your cart.' );

					return false;
				}

				$thisbutton.removeClass( 'added' ).addClass( 'loading updating-icon' );

				data.quantity   = quantity;
				data.product_id = variation_id;

				// Trigger event.
				$( 'body' ).trigger( 'adding_to_cart', [ $thisbutton, data ] );

				// Ajax action.
				$.post( Helpers.getAjaxUrl( 'woocommerce_add_to_cart' ), data, function( response ) {
					if ( ! response ) {
						return;
					}

					if ( response.error && response.product_url ) {
						window.location = response.product_url;
						return;
					}

					// Redirect to cart option
					if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
						window.location = wc_add_to_cart_params.cart_url;
						return;
					}

					// Trigger event so themes can refresh other areas.
					$( document.body ).trigger( 'added_to_cart', [
						response.fragments, response.cart_hash, $thisbutton
					] );

				} ).always( function() {
					$thisbutton.addClass( 'added' ).removeClass( 'loading updating-icon' );
				} );

				return false;
			} );
		}

		function loopProductVariationSelection() {
			$( document.body ).on( 'click', '.js-product-variation-term', function( evt ) {
				evt.preventDefault();

				var $currentTerm      = $( this ),
				    $form             = $currentTerm.parents( '.loop-product-variation-selector' ).first(),
				    $product          = $form.closest( '.product' ),
				    $productImage     = $product.find( '.product-main-image' ),
				    productAttribute  = $form.data( 'product_attribute' ),
				    variations        = $form.data( 'product_variations' ),
				    $variationsImages = $form.data( 'product_variations_images' ),
				    foundVariation    = false;

				// Remove cloned variation img.
				$productImage.find( '.variation-image' ).remove();
				$product.addClass( 'product-image-switching' );

				if ( $currentTerm.hasClass( 'selected' ) ) {
					Helpers.resetContentHTML( $product.find( '.price' ) );
					$product.removeClass( 'product-image-switching has-variation-selected' );
					$currentTerm.removeClass( 'selected' );
					return;
				}

				$currentTerm.siblings().removeClass( 'selected' );
				$currentTerm.addClass( 'selected' );

				var selectedTerm = $currentTerm.data( 'term' );

				var $selectBox = $product.find( 'select[name=' + productAttribute + ']' );
				if ( $selectBox.length > 0 ) {
					$selectBox.val( selectedTerm ).trigger( 'change' );
				}

				for ( var i = variations.length - 1; i >= 0; i -- ) {
					var currentVariation = variations[i];

					if ( currentVariation.attributes ) {
						var currentAttributes = Object.entries( currentVariation.attributes ); // Convert to array.

						// Compare selected variation with all variations to find best matches.
						currentAttributes.forEach( ( [ key, value ] ) => {
							if ( productAttribute === key && value === selectedTerm ) {
								foundVariation = currentVariation;
							}
						} );
					}
				}

				if ( false !== foundVariation ) {
					var image_url = $variationsImages[foundVariation['variation_id']],
					    $mainImg  = $productImage.children( '.product-main-image-img' ),
					    $newImg   = $( '<img />', {
						    'class': 'variation-image',
						    src: image_url,
						    alt: $mainImg.attr( 'alt' ),
					    } );

					$newImg.attr( 'width', $mainImg.attr( 'width' ) );
					$newImg.attr( 'height', $mainImg.attr( 'height' ) );

					$newImg.on( 'load', function() {
						$productImage.append( $newImg );
						$product.removeClass( 'product-image-switching' ).addClass( 'has-variation-selected' );
					} );

					var variationPriceHtml = foundVariation['price_html'];
					if ( '' !== variationPriceHtml ) { // Do nothing if all variations same price.
						Helpers.setContentHTML( $product.find( '.price' ), $( variationPriceHtml ).html() )
					}
				}
			} );
		}

		function widgetProductCategories() {
			$( '.widget_product_categories' ).each( function() {
				var $menu             = $( this ),
				    ACTIVE_CLASS      = 'opened',
				    animationDuration = 250;

				$menu.find( '.cat-parent' ).each( function() {
					var $currentItem = $( this ),
					    $newLink     = $( this ).children( 'a' ).clone(),
					    newLinkHtml  = $minimog.i18l.all,
					    $newItem     = $( '<li class="cat-item cat-item-all"></li>' );
					newLinkHtml      = newLinkHtml.replace( '%s', $newLink.html() );
					$newLink.html( newLinkHtml );

					if ( $currentItem.hasClass( 'current-cat' ) ) {
						$currentItem.removeClass( 'current-cat' );
						$currentItem.addClass( 'current-cat-parent' );

						$newItem.addClass( 'current-cat' );
					}

					$newItem.append( $newLink );
					$( this ).children( '.children' ).prepend( $newItem );
				} );

				$menu.on( 'click', 'a', function( evt ) {
					evt.stopPropagation();

					var $itemLink = $( this ),
					    $item     = $itemLink.parent( 'li' ),
					    $children = $itemLink.siblings( '.children' );

					if ( $children.length > 0 ) {
						evt.preventDefault();


						if ( $item.hasClass( ACTIVE_CLASS ) ) {
							$item.removeClass( ACTIVE_CLASS );
							$item.find( '.' + ACTIVE_CLASS ).removeClass( ACTIVE_CLASS ); // Close all children.
							$children.stop().slideUp( animationDuration );
						} else {
							$item.siblings( '.' + ACTIVE_CLASS ).removeClass( ACTIVE_CLASS ).children( '.children' ).stop().slideUp( animationDuration ); // Close other items.
							$item.addClass( ACTIVE_CLASS );
							$children.stop().slideDown( animationDuration );
						}
					}
				} );

				$menu.on( 'click', 'li.cat-parent', function( evt ) {
					$( this ).children( 'a' ).trigger( 'click' );
				} );

				var $currentCats = $menu.find( '.current-cat-parent, .current-cat' );
				$currentCats.addClass( ACTIVE_CLASS );
				$currentCats.children( '.children' ).stop().slideDown( animationDuration );
			} );
		}

		/**
		 * Rewrite Woo Advanced Discounts plugin
		 */
		function wooAdvancedDiscounts() {
			$( 'body' ).on( 'change', 'input[name="payment_method"], #billing_country, #shipping_country, #shipping_state, #billing_state', function() {
				setTimeout( function() {
					$( 'body' ).trigger( 'update_checkout' );
				}, 2000 );
			} );

			// Fix event for quick view.
			$( document.body ).on( 'show_variation', '.variations_form', function( evt, variation ) {
				var $form = $( this );

				var variation_id = $form.find( 'input[name="variation_id"]' ).val();
				if ( variation_id ) {
					$form.find( '.wad-qty-pricing-table' ).hide();
					$form.find( '.wad-qty-pricing-table[data-id="' + variation_id + '"]' ).show();
				}
			} );
		}
	}( jQuery )
);
