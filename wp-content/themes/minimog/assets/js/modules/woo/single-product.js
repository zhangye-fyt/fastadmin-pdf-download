/*global wc_single_product_params, PhotoSwipe, PhotoSwipeUI_Default */
/**
 * This is edited version of wc-single-product.
 * Remove unused code to reduce file size.
 * - Update review link action
 * - Remove Tabs
 * - Remove ProductGallery
 * @from Woocommerce 6.9.0
 */
jQuery( function( $ ) {

	// wc_single_product_params is required to continue.
	if ( typeof wc_single_product_params === 'undefined' ) {
		return false;
	}

	$( window ).on( 'load', function() {
		var hash  = window.location.hash,
		    url   = window.location.href,
		    $tabs = $( '.woocommerce-tabs .minimog-tabs' ).find( '.minimog-tabs__header' );

		if ( hash.toLowerCase().indexOf( 'comment-' ) >= 0 || hash === '#reviews' || hash === '#tab-reviews' ) {
			$tabs.find( '.tab-title.reviews_tab' ).trigger( 'click' );
		} else if ( url.indexOf( 'comment-page-' ) > 0 || url.indexOf( 'cpage=' ) > 0 ) {
			$tabs.find( '.tab-title.reviews_tab' ).trigger( 'click' );
		} else if ( hash === '#tab-additional_information' ) {
			$tabs.find( '.tab-title.additional_information_tab' ).trigger( 'click' );
		}
	} );

	$( 'body' )
		.on( 'click', 'a.woocommerce-review-link', function( evt ) {
			evt.preventDefault();
			var $reviewTab = $( '.tab-title.reviews_tab' );

			if ( $reviewTab.length > 0 ) {
				$( 'html, body' ).animate( { scrollTop: $reviewTab.offset().top - 50 }, 300 );

				$reviewTab.trigger( 'click' );
			}
			return true;
		} )
		// Star ratings for comments.
		.on( 'init', '#rating', function() {
			$( '#rating' )
				.hide()
				.before(
					'<p class="stars">\
						<span>\
							<a class="star-1" href="#">1</a>\
							<a class="star-2" href="#">2</a>\
							<a class="star-3" href="#">3</a>\
							<a class="star-4" href="#">4</a>\
							<a class="star-5" href="#">5</a>\
						</span>\
					</p>'
				);
		} )
		.on( 'click', '#respond p.stars a', function() {
			var $star      = $( this ),
			    $rating    = $( this ).closest( '#respond' ).find( '#rating' ),
			    $container = $( this ).closest( '.stars' );

			$rating.val( $star.text() );
			$star.siblings( 'a' ).removeClass( 'active' );
			$star.addClass( 'active' );
			$container.addClass( 'selected' );

			return false;
		} )
		.on( 'click', '#respond #submit', function() {
			var $rating = $( this ).closest( '#respond' ).find( '#rating' ),
			    rating  = $rating.val();

			if ( $rating.length > 0 && ! rating && wc_single_product_params.review_rating_required === 'yes' ) {
				window.alert( wc_single_product_params.i18n_required_rating_text );

				return false;
			}
		} );

	// Init Star Ratings.
	$( '#rating' ).trigger( 'init' );
} );
