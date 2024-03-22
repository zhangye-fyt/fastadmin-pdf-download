<div class="entry-post-meta">
	<div class="entry-post-meta__inner">
		<?php if ( Minimog::setting( 'single_post_author_enable' ) === '1' ) : ?>
			<?php Minimog_Post::instance()->meta_author_template(); ?>
		<?php endif; ?>

		<?php if ( Minimog::setting( 'single_post_date_enable' ) === '1' ) : ?>
			<?php Minimog_Post::instance()->entry_date(); ?>
		<?php endif; ?>
	</div>
</div>
