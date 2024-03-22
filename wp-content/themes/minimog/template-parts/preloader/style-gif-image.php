<?php
$image_url = Minimog_Helper::get_redux_image_url( 'pre_loader_image' );
?>
<div class="minimog-pre-loader-gif-img-wrap">
	<?php if ( ! empty( $image_url ) ): ?>
		<img src="<?php echo esc_url( $image_url ); ?>"
		     alt="<?php esc_attr_e( 'Minimog Preloader', 'minimog' ); ?>" class="minimog-pre-loader-gif-img">
	<?php endif; ?>
</div>
