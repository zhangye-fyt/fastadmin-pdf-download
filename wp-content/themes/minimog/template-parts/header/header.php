<header id="page-header" <?php Minimog_Header::instance()->get_wrapper_class(); ?>>
	<div class="page-header-place-holder"></div>
	<div id="page-header-inner" class="page-header-inner" data-sticky="1">
		<?php if ( Minimog_Header::instance()->is_active_above() ) : ?>
			<div class="header-above hide-on-pinned hide-sm">
				<div <?php Minimog_Header::instance()->get_container_class(); ?>>
					<div class="header-above-wrap">
						<div class="header-above-left header-col-start hide-md">
							<div class="header-content-inner">
								<?php Minimog_Header::instance()->print_info_list(); ?>
							</div>
						</div>
						<div class="header-above-center header-col-center">
							<div class="header-content-inner">
								<?php Minimog_Header::instance()->print_text(); ?>
							</div>
						</div>
						<div class="header-above-right header-col-end">
							<div class="header-content-inner">
								<?php Minimog_Header::instance()->print_info_list_secondary(); ?>

								<?php Minimog_Header::instance()->print_language_switcher(); ?>

								<?php Minimog_Header::instance()->print_currency_switcher(); ?>

								<?php Minimog_Header::instance()->print_social_networks(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div <?php Minimog_Header::instance()->get_container_class(); ?>>
			<div class="header-wrap">
				<?php Minimog_THA::instance()->header_wrap_top(); ?>

				<div class="header-left header-col-start">
					<div class="header-content-inner">
						<?php Minimog_Header::instance()->print_open_mobile_menu_button( [ 'direction' => 'right' ] ); ?>

						<?php minimog_load_template( 'navigation' ); ?>
					</div>
				</div>

				<div class="header-center header-col-center">
					<div class="header-content-inner">
						<?php minimog_load_template( 'branding' ); ?>
					</div>
				</div>

				<div class="header-right header-col-end">
					<div class="header-content-inner">
						<?php Minimog_Header::instance()->print_button(); ?>

						<?php Minimog_Header::instance()->print_search( [
							'template_position' => 'form',
							'toggle_device'     => 'md',
						] ); ?>

						<?php Minimog_Header::instance()->print_login_button(); ?>

						<?php Minimog_Header::instance()->print_search( [
							'template_position' => 'popup',
							'toggle_device'     => 'md',
						] ); ?>

						<?php Minimog_Header::instance()->print_wishlist_button(); ?>

						<?php Minimog_Header::instance()->print_mini_cart(); ?>
					</div>
				</div>

				<?php Minimog_THA::instance()->header_wrap_bottom(); ?>
			</div>
		</div>
	</div>
</header>
