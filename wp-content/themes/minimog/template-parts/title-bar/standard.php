<div id="page-title-bar" <?php Minimog_Title_Bar::instance()->the_wrapper_class(); ?>>
	<div class="page-title-bar-bg"></div>

	<div class="page-title-bar-inner">
		<div class="page-title-bar-content">
			<div <?php Minimog_Title_Bar::instance()->the_container_class(); ?>>
				<?php Minimog_THA::instance()->title_bar_heading_before(); ?>

				<?php Minimog_Title_Bar::instance()->render_title(); ?>

				<?php Minimog_THA::instance()->title_bar_heading_after(); ?>

				<?php minimog_load_template( 'breadcrumb' ); ?>
			</div>
		</div>
	</div>
</div>
