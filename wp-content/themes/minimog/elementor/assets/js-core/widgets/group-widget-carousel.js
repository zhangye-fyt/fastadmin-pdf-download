(
	function( $ ) {
		'use strict';

		var SwiperHandler = function( $scope, $ ) {
			var $element = $scope.find( '.tm-slider-widget' );

			$element.MinimogSwiper();
		};

		var SwiperLinkedHandler = function( $scope, $ ) {
			var $element = $scope.find( '.tm-slider-widget' );

			if ( $scope.hasClass( 'minimog-swiper-linked-yes' ) ) {
				var thumbsSlider = $element.filter( '.minimog-thumbs-swiper' ).MinimogSwiper();
				var mainSlider = $element.filter( '.minimog-main-swiper' ).MinimogSwiper( {
					thumbs: {
						swiper: thumbsSlider,
						slidesPerGroup: 3
					}
				} );
			} else {
				$element.MinimogSwiper();
			}
		};

		var SwiperLinkedHandler2 = function( $scope, $ ) {
			var $element = $scope.find( '.tm-slider-widget' );

			if ( $scope.hasClass( 'minimog-swiper-linked-yes' ) ) {
				var thumbsSlider = $element.filter( '.minimog-thumbs-swiper' ).MinimogSwiper();
				var mainSlider = $element.filter( '.minimog-main-swiper' ).MinimogSwiper();

				mainSlider.controller.control = thumbsSlider;
				thumbsSlider.controller.control = mainSlider;
			} else {
				$element.MinimogSwiper();
			}
		};

		var SwiperSlideShowHandler = function( $scope, $ ) {
			var $element = $scope.find( '.tm-slider-widget' );

			var thumbsSlider = $element.filter( '.minimog-thumbs-swiper' ).MinimogSwiper();
			var mainSlider = $element.filter( '.minimog-main-swiper' ).MinimogSwiper();

			mainSlider.controller.control = thumbsSlider;
			thumbsSlider.controller.control = mainSlider;

			if ( $scope.closest( '.elementor-top-section' ).hasClass( 'elementor-section-stretched' ) ) {
				var contentWidth = parseInt( $element.data( 'content-width' ) ),
				    layout       = $element.data( 'layout' );

				if ( contentWidth === 0 ) {
					return;
				}

				$( window ).on( 'resize', function() {
					var windowWidth = $( window ).width(),
					    space, pos;

					space = (
						        windowWidth - contentWidth
					        ) / 2;
					pos = 'default' === layout ? 'padding-left' : 'padding-right';

					if ( elementorFrontendConfig.is_rtl ) {
						pos = 'padding-left' === pos ? 'padding-right' : 'padding-left';
					}

					if ( space < 15 ) {
						space = 15;
					}

					$( '.testimonial-item', $element ).css( {
						[ pos ]: space + 'px'
					} );

					$( '.swiper-pagination-container', $element ).css( {
						[ pos ]: space + 'px'
					} );
				} ).trigger( 'resize' );
			}
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-image-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-modern-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-modern-slider.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-team-member-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product-list-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-feature-product-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product-category-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product-brands-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-blog-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-marquee-list.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-image-box-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-slider-slideshow.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-product-category-banner-carousel.default', SwiperHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-parallax-sliders.default', SwiperLinkedHandler2 );

			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-testimonial-carousel.default', SwiperLinkedHandler );

			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-testimonial-slideshow.default', SwiperSlideShowHandler );
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-products-slideshow.default', SwiperSlideShowHandler );

			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-instagram-carousel.default', SwiperHandler );
		} );
	}
)( jQuery );
