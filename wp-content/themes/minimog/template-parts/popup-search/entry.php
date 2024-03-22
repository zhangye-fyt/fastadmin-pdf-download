<div id="popup-search" class="page-search-popup" aria-hidden="true" role="dialog" hidden>
	<div class="inner scroll-y">
		<div class="container-wide">
			<div class="row-search-popup-heading row-flex row-middle">
				<div class="search-popup-heading col-grow">
					<h4><?php esc_html_e( 'Search our store', 'minimog' ); ?></h4>
				</div>
				<div class="col-shrink">
					<a href="#" id="search-popup-close" class="search-popup-close">
						<span class="fal fa-times"></span>
					</a>
				</div>
			</div>
			<div class="row row-xs-center">
				<div class="col-md-3 col-search-popup-branding">
					<div class="popup-search-logo">
						<?php Minimog_Logo::instance()->render( [
							'skin' => 'dark',
						] ); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="page-search-popup-content">
						<?php minimog_load_template( 'popup-search/components/search-form' ); ?>
					</div>
				</div>
				<div class="col-md-3 flex justify-end items-center col-search-popup-icons">
					<?php minimog_load_template( 'popup-search/components/login-button' ); ?>
					<?php minimog_load_template( 'popup-search/components/wishlist-button' ); ?>
					<?php minimog_load_template( 'popup-search/components/mini-cart-button' ); ?>
				</div>
			</div>
			<div class="row row-popular-search-keywords">
				<div class="col-md-push-3 col-md-6">
					<?php minimog_load_template( 'popup-search/components/popular-keywords' ); ?>
				</div>
			</div>
			<div class="popup-search-results" style="display: none;">
				<div class="popup-search-results-title">
					<?php echo sprintf( esc_html__( 'Results for "%s"', 'minimog' ), '<span class="popup-search-current"></span>' ); ?>
				</div>
				<?php if ( Minimog_Woo::instance()->is_activated() ): ?>
					<?php wc_get_template( 'custom/before-shop-loop-grid.php' ); ?>

					<?php wc_get_template( 'custom/after-shop-loop-grid.php' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
