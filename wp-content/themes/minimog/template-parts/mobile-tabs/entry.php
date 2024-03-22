<div id="page-mobile-tabs" class="page-mobile-tabs">
	<div class="tabs">
		<?php do_action( 'minimog/mobile-tabs/before' ); ?>

		<?php foreach ( $args['tab_items'] as $tab_item => $is_active ): ?>
			<?php
			if ( empty( $is_active ) ) {
				continue;
			}

			minimog_load_template( 'mobile-tabs/components/' . $tab_item . '-button' );
			?>
		<?php endforeach; ?>

		<?php do_action( 'minimog/mobile-tabs/after' ); ?>
	</div>
</div>
