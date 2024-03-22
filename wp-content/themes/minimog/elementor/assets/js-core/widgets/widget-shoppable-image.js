(
	function( $ ) {
		'use strict';

		var MinimogShoppableImage = function( $scope, $ ) {
			var $element = $scope.find( '.minimog-shoppable-image' ),
				dataTagType = $element.data( 'tag_type' ),
				$wrapper = $( '.mabel-siwc-img-wrapper', $element );

			var dataIcon = $wrapper.data("sw-icon"),
				dataTags = $wrapper.data("sw-tags"),
				dataText = $wrapper.data("sw-text"),
				dataTagSize = $wrapper.data("sw-size");

			var arrowSize = 12;

			var $icon = '<i class="'+ dataIcon +'"></i>';

			$( document.body ).on( 'click', function(e) {
				if ( ! $( e.target ).is( '.mb-siwc-tag' )
					&& ! $( e.target ).parents( '.mb-siwc-tag' ).length
					&& ! $( e.target ).is( '.mb-siwc-popup-inner')
					&& ! $( e.target ).parents( '.mb-siwc-popup-inner' ).length
				) {
					$( '.mb-siwc-tag', this )
						.removeClass( 'active' )
						.next().addClass( "mabel-invisible" );
				}
			} );

			$.each( dataTags, function ( index, data ) {
				if ( 'numeric' === dataTagType ) {
					$icon = ( index + 1 ).toFixed(0);
				}

				var img = data.thumb ? '<img src="' + data.thumb + '" alt="'+ data.title +'"/>' : '';

				var $productContent = '<div class="mb-siwc-popup-inner">'
					+ '<div class="siwc-thumb-wrapper"><a target="_blank" href="' + data.link + '">' + img + '</a></div>'
					+ '<div class="siwc-content-wrapper">'
					+ '<h3 class="product-title"><a target="_blank" href="' + data.link + '">' + data.title + '</a></h3>'
					+ '<p class="price">' + data.price + '</p>'
					+ '<a target="_blank" class="product-link" href="' + data.link + '">' + dataText + '</a>'
					+ '</div>'
					+ '</div>';

				var $popupInner =
					(
						$( '<span class="mb-siwc-tag">' + $icon + '</span>' )
						.css({ top: data.posY + "%", left: data.posX + "%" })
						.on( "click", function ( event ) {
							event.preventDefault();

							var $currentTag = $(event.currentTarget),
								$tag = $wrapper.find( ".mb-siwc-tag" );

							$currentTag.toggleClass( "active" ),
							$tag.not( $currentTag ).removeClass( "active" ),
							$tag.not( $currentTag ).next().addClass( "mabel-invisible" ),
							$currentTag.next().toggleClass( "mabel-invisible" );
						})
						.appendTo( $wrapper ),
						$( $productContent )
					);

				var $popup = $('<div class="mb-siwc-popup mabel-invisible">');

				data.thumb || $popup.addClass("siwc-no-thumb"),
					$popup
						.append( $popupInner )
						.appendTo( $wrapper )
						.css({
							top: data.posY + "%",
							left: data.posX + "%",
							marginLeft: -$popup.width() / 2,
							marginTop: dataTagSize / 2 + 7
						});

				$( window ).on( 'resize', function() {

					var p = getPopupPosition( data.posX, data.posY, $popup, $wrapper );

					if ("bottom" != p)
						switch (p) {
							case "top":
								$popup.css({
									marginTop : -1 * ( $popup.height() + dataTagSize / 2 + 7 )
								});
								break;
							case "top left":
								$popup.css({
									marginTop : -1 * ( $popup.height() + dataTagSize / 2 ),
									marginLeft: -1 * ( $popup.width() + dataTagSize / 2 )
								});
								break;
							case "top right":
								$popup.css({
									marginTop : -1 * ( $popup.height() + dataTagSize / 2 ),
									marginLeft: dataTagSize / 2
								});
								break;
							case "bottom left":
								$popup.css({
									marginTop: dataTagSize / 2,
									marginLeft: -1 * ( $popup.width() + dataTagSize / 2 )
								});
								break;
							case "bottom right":
								$popup.css({
									marginTop: dataTagSize / 2,
									marginLeft: dataTagSize / 2
								});
					}

					$popup.addClass( "mb-siwc-" + p.replace(" ", "-") );
				} ).trigger( 'resize' );
			});

			function getPopupPosition( posX, posY, $popup, $wrapper ) {
				void 0 === $wrapper && ( $wrapper = null ), null == $wrapper && ( $wrapper = $( document.body ) );
				var popupH        = $popup.height(),
					popupW        = $popup.width(),
					wrapperHeight = $wrapper.height(),
					wrapperWidth  = $wrapper.width(),
					h             = ( popupH / wrapperHeight ) * 100,
					w             = ( popupW / wrapperWidth ) * 100,
					x 			  = wrapperWidth * posX / 100,
					position      = posY + h > 100 ? "top" : "bottom";

				if ( posX + w > 100 && popupW < x ) {
					position += ' left';
				} else if ( posX - w < 0 && popupW < wrapperWidth - x ) {
					position += ' right';
				}

				return position;
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-shoppable-image.default', MinimogShoppableImage );
		} );
	}
)( jQuery );
