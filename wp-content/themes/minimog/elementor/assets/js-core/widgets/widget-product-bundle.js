(
	function( $ ) {
		'use strict';

		var MinimogProductBundle = function( $scope, $ ) {
			var $element     = $scope.find( '.tm-product-bundle' ),
			    $products    = $( '.woosb-products', $element ),
			    $buttonPrice = $element.find( '.single_add_to_cart_button .price' );

			// Element data.
			var bundled_price          = $element.data( 'bundled_price' ),
			    bundled_price_from     = $element.data( 'bundled_price_from' ),
			    priceFormat            = $element.data( 'price_format' ),
			    priceDecimals          = $element.data( 'price_decimals' ),
			    priceThousandSeparator = $element.data( 'price_thousand_separator' ),
			    priceDecimalSeparator  = $element.data( 'price_decimal_separator' ),
			    currencySymbol         = $element.data( 'currency_symbol' ),
			    saveText               = $element.data( 'save_text' ),
			    discountAmount         = $element.data( 'discount_amount' );

			// Products data.
			var discount = $products.data( 'discount' );

			// Place price next to button text.
			$( document ).on( 'woosb_calc_price', function( e, total_sale, total, total_html, price_suffix ) {
				var discountAmountHtml = '';

				if ( parseFloat( discountAmount ) > 0 ) {
					discountAmountHtml = '<span class="woosb-discount-amount">(' + '-' + format_price( discountAmount ) + ')</span>';
				}

				$buttonPrice.html( format_price( total_sale ) );
			} );

			$( document ).on( 'woosb_check_qty', function( e, qty, $qty ) {
				var $product = $qty.closest( '.woosb-product' );

				if ( bundled_price === 'subtotal' ) {
					var price_suffix = $product.attr( 'data-price-suffix' );
					var ori_price = parseFloat( $product.attr( 'data-price' ) ) * parseFloat( qty );

					if ( parseFloat( discount ) > 0 && $products.attr( 'data-fixed-price' ) === 'no' ) {
						var new_price    = ori_price * (
							    100 - parseFloat( discount )
						    ) / 100,
						    amount_saved = ori_price - new_price;

						$product.find( '.woosb-price-new' )
						        .html( format_price( new_price ) + price_suffix )
						        .append( format_amount_saved( amount_saved ) )
						        .show();
					} else {
						$product.find( '.woosb-price-new' )
						        .html( format_price( ori_price ) + price_suffix )
						        .show();
					}
				}
			} );

			// Change price variation product.
			$( document ).on( 'found_variation', function( e, t ) {
				var $product = $( e['target'] ).closest( '.woosb-product' );

				if ( t['price_html'] !== undefined && t['price_html'] !== '' &&
					t[ 'display_price' ] !== undefined && t[ 'display_price' ] !== '' ) {
					change_price( $product, t[ 'display_price' ], t[ 'display_regular_price' ], t['price_html'] );
				}
			} );

			function change_price( $product, price, regular_price, price_html ) {
				var price_suffix = $product.attr( 'data-price-suffix' );

				// hide ori price.
				$product.find( '.woosb-price-ori' ).hide();

				// calculate new price.
				if ( bundled_price === 'subtotal' ) {
					var ori_price = parseFloat( price ) * parseFloat( $product.attr( 'data-qty' ) );

					if ( bundled_price_from === 'regular_price' && regular_price !== undefined ) {
						ori_price = parseFloat( regular_price ) * parseFloat( $product.attr( 'data-qty' ) );
					}

					var new_price = ori_price;

					if ( parseFloat( discount ) > 0 ) {
						new_price = ori_price * (
							100 - discount
						) / 100;
					}

					$product.find( '.woosb-price-new' )
					        .html( format_price( new_price ) + price_suffix )
					        .append( format_amount_saved( ori_price, new_price ) )
					        .show();
				} else {
					if ( parseFloat( discount ) > 0 ) {
						var ori_price = parseFloat( price );

						if ( bundled_price_from === 'regular_price' && regular_price !== undefined ) {
							ori_price = parseFloat( regular_price );
						}

						var new_price = ori_price * (
							100 - parseFloat( discount )
						) / 100;
						$product.find( '.woosb-price-new' )
						        .html( format_price( new_price ) + price_suffix )
						        .append( format_amount_saved( ori_price, new_price ) )
						        .show();
					} else {
						if ( bundled_price_from === 'regular_price' && regular_price !== undefined ) {
							$product.find( '.woosb-price-new' )
							        .html( format_price( regular_price ) + price_suffix )
							        .show();
						} else if ( '' !== price_html ) {
							$product.find( '.woosb-price-new' )
							        .html( price_html )
							        .show();
						}
					}
				}
			}

			// Format Price.
			function format_amount_saved( regular_price, sale_price = 0 ) {
				var price = regular_price - sale_price;

				if ( price <= 0 ) {
					return '';
				}

				return '<div class="woosb-price-saved">' + saveText + ' ' + format_price( price ) + '</div>';
			}

			function format_price( price ) {
				var price_html = '<span class="woocommerce-Price-amount amount">';
				var price_formatted = format_money( price, priceDecimals, '', priceThousandSeparator, priceDecimalSeparator );

				switch ( priceFormat ) {
					case '%1$s%2$s':
						//left
						price_html += '<span class="woocommerce-Price-currencySymbol">' + currencySymbol + '</span>' + price_formatted;
						break;
					case '%1$s %2$s':
						//left with space
						price_html += '<span class="woocommerce-Price-currencySymbol">' + currencySymbol + '</span> ' + price_formatted;
						break;
					case '%2$s%1$s':
						//right
						price_html += price_formatted +
						              '<span class="woocommerce-Price-currencySymbol">' + currencySymbol + '</span>';
						break;
					case '%2$s %1$s':
						//right with space
						price_html += price_formatted +
						              ' <span class="woocommerce-Price-currencySymbol">' + currencySymbol + '</span>';
						break;
					default:
						//default
						price_html += '<span class="woocommerce-Price-currencySymbol">' + currencySymbol + '</span> ' + price_formatted;
				}

				price_html += '</span>';

				return price_html;
			}

			function format_money( number, places, symbol, thousand, decimal ) {
				number = number || 0;
				places = ! isNaN( places = Math.abs( places ) ) ? places : 2;
				symbol = symbol !== undefined ? symbol : '$';
				thousand = thousand || ',';
				decimal = decimal || '.';

				var negative = number < 0 ? '-' : '',
				    i        = parseInt(
					    number = num_round( Math.abs( + number || 0 ), places ).toFixed( places ),
					    10 ) + '',
				    j        = 0;

				if ( i.length > 3 ) {
					j = i.length % 3;
				}

				return symbol + negative + (
					j ? i.substr( 0, j ) + thousand : ''
				) + i.substr( j ).replace( /(\d{3})(?=\d)/g, '$1' + thousand ) + (
					       places ?
					   decimal +
					   num_round( Math.abs( number - i ), places ).toFixed( places ).slice( 2 ) :
						       ''
				       );
			}

			function num_round( value, decimals ) {
				return Number( Math.round( value + 'e' + decimals ) + 'e-' + decimals );
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product-bundle.default', MinimogProductBundle );
		} );
	}
)( jQuery );
