(
	function( $ ) {
		'use strict';

		var $body = $( 'body' );

		var Helpers = window.minimog.Helpers;

		$( document ).ready( function() {
			initProductImagesFeatures();
			initSingleStickyAddToCartForm();

			commentAttachmentUploadHandler();
			commentAttachmentGalleryInit();
			commentTextCollapsible();
			fixProductBoughtTogether();
			setProductViewed();
			liveViewsVisitors();
			initSizeChart();
			initProductShare();

			$body.on( 'isw_selected', swatchesSelected );

			$( '.minimog-tabs' ).MinimogTabPanel();

			$( document.body ).on( 'click', '.woobt-add-btn', function( evt ) {
				evt.preventDefault();

				var ACTIVE_CLASS  = 'checked',
				    $toggleButton = $( this ),
				    pid           = $toggleButton.attr( 'data-pid' ),
				    $product      = $toggleButton.parents( '.woobt-images' ).siblings( '.woobt-products' ).find( '.woobt-product[data-pid="' + pid + '"]' ),
				    $checkbox     = $product.find( '.woobt-checkbox' ),
				    isCheck       = $toggleButton.hasClass( ACTIVE_CLASS );

				$toggleButton.toggleClass( ACTIVE_CLASS );
				$checkbox.prop( 'checked', ! isCheck );
				$checkbox.trigger( 'change' );
			} );

			$( document.body ).on( 'change', '.woobt-layout-separate .woobt-checkbox', function() {
				var $checkbox        = $( this ),
				    $product         = $checkbox.closest( '.woobt-product' ),
				    pID              = $product.data( 'pid' ),
				    isChecked        = $checkbox.prop( 'checked' ),
				    $productImageAdd = $product.parents( '.woobt-products' ).siblings( '.woobt-images' ).find( '.woobt-image-' + pID ).find( '.woobt-add-btn' );

				isChecked ? $productImageAdd.addClass( 'checked' ) : $productImageAdd.removeClass( 'checked' );
			} );

			initProduct360();
		} );

		function initSizeChart() {
			// Size Chart Table.
			var chartTable = $( '.minimog-size-guide__table-wrapper' );
			if ( $.fn.perfectScrollbar && chartTable.length > 0 ) {
				chartTable.perfectScrollbar( {
					suppressScrollY: true,
					useBothWheelAxes: true,
				} );
			}
		}

		function initProduct360() {
			$( document.body ).on( 'click', '.btn-open-product-360', function( evt ) {
				evt.preventDefault();

				var $modal  = $( '<div id="modal-product-360"></div>' ),
				    options = $.extend( {}, {
					    sense: - 1,
					    responsive: true,
					    animate: false,
					    plugins: [
						    '360', // Required.
						    'wheel',
						    'drag'
					    ]
				    }, $( this ).data( 'spritespin-settings' ) );

				$body.append( $modal );

				$modal.MinimogModal( {
					dynamic: true,
					afterInit: function( modal ) {
						var $modalContent = modal.$el.find( '.modal-content-inner' ),
						    $spinObject   = $( '<div id="product-360-spritespin"></div>' );
						$modalContent.append( $spinObject );
						$spinObject.spritespin( options );
					}
				} );
			} );
		}

		function fixProductBoughtTogether() {
			var $variationsForm     = $( '#woo-single-info .entry-summary > .variations_form' ),
			    $frequentlyProducts = $variationsForm.siblings( '.woobt-wrap' ),
			    $currentProduct     = $frequentlyProducts.find( '.woobt-product-this' );

			/**
			 * Update Variations for This Product in Bought Together List.
			 */
			$variationsForm.find( 'select' ).on( 'change', function() {
				var $select        = $( this ),
				    attributeName  = $select.attr( 'name' ),
				    attributeValue = $select.val();

				$currentProduct.find( '.variations_form select[name="' + attributeName + '"]' ).val( attributeValue ).trigger( 'change' );
			} );

			if ( $.fn.perfectScrollbar && ! Helpers.isHandheld() ) {
				$( '.woobt-products-wrap' ).perfectScrollbar( {
					suppressScrollY: true,
					useBothWheelAxes: true,
				} );
			}
		}

		function swatchesSelected( evt, attr, term, title, $thisTerm ) {
			var $termRow = $thisTerm.parents( '.row-isw-swatch' ).first();

			if ( $termRow.hasClass( 'row-isw-swatch--isw_select' ) ) {
				return;
			}

			$termRow.find( '.selected-term-name' ).html( title );
		}

		function initProductImagesFeatures() {
			// Selector without parent to compatible Elementor Pro.
			var $sliderWrap = $( '.woo-single-gallery' );

			if ( 0 >= $sliderWrap.length ) {
				return;
			}

			function slider() {
				if ( ! $minimog.isSingle || 'product' !== $minimog.postType ) {
					return;
				}

				if ( $minimogProductSingle.featureStyle !== 'slider' && $minimogProductSingle.featureStyle !== 'slider-02' && $minimogProductSingle.featureStyle !== 'carousel' ) {
					return;
				}

				var options = {};
				if ( $sliderWrap.hasClass( 'has-thumbs-slider' ) ) {
					var thumbsSlider = $sliderWrap.find( '.minimog-thumbs-swiper' ).MinimogSwiper();
					options = {
						thumbs: {
							swiper: thumbsSlider,
							autoScrollOffset: 1,
						}
					};
				}
				$sliderWrap.children( '.minimog-main-swiper' ).MinimogSwiper( options );
			}

			function lightGallery() {
				if ( $.fn.lightGallery && $sliderWrap.hasClass( 'has-light-gallery' ) ) {
					$sliderWrap.removeData( 'lightGallery' ); // Destroy init before re-init.
					$sliderWrap.lightGallery( window.minimog.LightGallery );
				}
			}

			slider();
			// Init Light Gallery after Swiper to make working loop mode.
			lightGallery();

			$sliderWrap.on( 'minimog_wc_gallery_init_light_gallery', lightGallery );
		}

		function initSingleStickyAddToCartForm() {
			if ( '1' !== $minimogProductSingle.singleProductStickyBar ) {
				return;
			}

			var $stickyBar = $( '#sticky-product-bar' );

			if ( 0 >= $stickyBar.length ) {
				return;
			}

			var $addToCartBtn = $( '.entry-product form.cart' ).find( '.single_add_to_cart_button' ),
			    $cartForm     = $( '.entry-product .entry-summary form.cart' ),
			    offsetTop     = $addToCartBtn.offset().top,
			    barHeight     = $stickyBar.outerHeight(),
			    ACTIVE_CLASS  = 'showing';

			$body.css( '--sticky-atc-bar-height', barHeight + 'px' );

			$stickyBar.on( 'click', '.sticky-product-bar-close', function( evt ) {
				evt.preventDefault();
				$stickyBar.addClass( 'hide' );
				$body.css( '--sticky-atc-bar-height', 0 );
			} );

			$( window ).on( 'scroll', function() {
				$( this ).scrollTop() > offsetTop ? $stickyBar.addClass( ACTIVE_CLASS ) : $stickyBar.removeClass( ACTIVE_CLASS );
			} );

			$( document.body ).on( 'click', '.sticky-product-add_to_cart_button', function( evt ) {
				evt.preventDefault();

				var offset = $cartForm.offset().top - 132; // admin bar + header height + content spacing.

				$( 'html, body' ).animate( { scrollTop: offset }, 600 );
			} );
		}

		function commentAttachmentUploadHandler() {
			var $commentForm = $( '#commentform' );

			$commentForm.on( 'click', '.comment-form__attachment-button', function() {
				var $attachmentButton = $( this ).closest( '.comment-form' ).find( '.comment-form-attachment__input' );
				$attachmentButton.trigger( 'click' );
			} );

			$commentForm.on( 'change', '.comment-form-attachment__input', function( evt ) {
				var $attachmentButton = $( this ).closest( '.comment-form' ).find( '.comment-form__attachment-button' );

				var fileName = '';

				if ( this.files && this.files.length > 1 ) {
					fileName = $minimogProductSingle.i18n.filesSelected.replace( '{count}', this.files.length );
				} else {
					fileName = evt.target.value.split( '\\' ).pop();
				}

				$attachmentButton.find( '.button-text' ).text( fileName );
			} );
		}

		function commentAttachmentGalleryInit() {
			var _download   = $minimog.light_gallery_download === '1',
			    _autoPlay   = $minimog.light_gallery_auto_play === '1',
			    _zoom       = $minimog.light_gallery_zoom === '1',
			    _fullScreen = $minimog.light_gallery_full_screen === '1',
			    _share      = $minimog.light_gallery_share === '1',
			    _thumbnail  = $minimog.light_gallery_thumbnail === '1';

			var options = {
				selector: 'a',
				mode: 'lg-fade',
				thumbnail: _thumbnail,
				download: _download,
				autoplay: _autoPlay,
				zoom: _zoom,
				share: _share,
				fullScreen: _fullScreen,
				hash: false,
				animateThumb: false,
				showThumbByDefault: false,
				getCaptionFromTitleOrAlt: false
			};

			$( '.dco-attachment-gallery' ).each( function() {
				$( this ).lightGallery( options );
			} );
		}

		function commentTextCollapsible() {
			var $readmore = $( '.js-text-collapsible' ),
			    $tabPanel = $readmore.closest( '.minimog-tabs' );

			new Readmore( $readmore, {
				moreLink: '<a href="#" class="btn btn-flat btn-small">' + $minimogProductSingle.i18n.readMore + '</a>',
				lessLink: '<a href="#" class="btn btn-flat btn-small">' + $minimogProductSingle.i18n.readLess + '</a>',
				afterToggle: function( trigger, element, expanded ) {
					if ( $tabPanel.length > 0 ) {
						$tabPanel.MinimogTabPanel( 'updateLayout' );
					}
				}
			} );
		}

		function setProductViewed() {
			if ( 'product' !== $minimog.postType ) {
				return;
			}

			var pid        = $minimog.postID,
			    cookieName = 'recent_viewed_products',
			    oldIDs     = Helpers.getCookie( cookieName ),
			    viewedStr  = pid;

			if ( oldIDs !== '' ) {
				var viewed = oldIDs.split( ',' );

				for ( var i = 0; i < viewed.length; i ++ ) {
					while ( viewed[ i ] == pid ) {
						viewed.splice( i, 1 );
					}
				}

				viewed.unshift( pid );

				// Store max 20 items.
				if ( viewed.length > 20 ) {
					viewed.slice( 1, 20 );
				}

				viewedStr = viewed.join();
			}

			Helpers.setCookie( cookieName, viewedStr, 7 );
		}

		function liveViewsVisitors() {
			var $wrap = $( '#live-viewing-visitors' );

			if ( 0 >= $wrap.length ) {
				return;
			}

			var { min, max, duration, labels } = $wrap.data( 'settings' );
			var $text = $wrap.find( '.text' );
			min = parseInt( min );
			max = parseInt( max );

			setInterval( function() {
				var current = Helpers.randomInteger( min, max ),
				    text    = current > 1 ? labels.plural : labels.singular;
				text = text.replace( '%s', '<span class="count">' + current + '</span>' );
				$text.html( text );
			}, duration );
		}

		function initProductShare() {
			$( document.body ).on( 'click', '#product-share-url', function() {
				var $parent = $( this ).parent(),
				    result  = Helpers.copyToClipboard( $( this ).val() );


				if ( result ) {
					$parent.attr( 'aria-label', $parent.attr( 'data-copied' ) );
				}

				setTimeout( function() {
					$parent.attr( 'aria-label', $parent.attr( 'data-copy' ) );
				}, 2000 );
			} );
		}
	}( jQuery )
);
