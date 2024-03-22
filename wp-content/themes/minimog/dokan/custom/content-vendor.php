<?php
/**
 * The template for displaying vendor content within loops
 *
 * @package Minimog/Dokan/Templates
 * @version 1.0.0
 */
$seller = $args['seller'];

/**
 * @var \WeDevs\Dokan\Vendor\Vendor $vendor
 */
$vendor            = dokan()->vendor->get( $seller->ID );
$store_banner_id   = $vendor->get_banner_id();
$store_name        = $vendor->get_shop_name();
$store_url         = $vendor->get_shop_url();
$store_rating      = $vendor->get_rating();
$is_store_featured = $vendor->is_featured();
$store_phone       = $vendor->get_phone();
$store_email       = $vendor->get_email();
$store_info        = dokan_get_store_info( $seller->ID );
$store_address     = dokan_get_seller_short_address( $seller->ID, false );
$store_banner_url  = MINIMOG_THEME_ASSETS_URI . '/dokan/default-store-cover.jpg';
if ( $store_banner_id ) {
	$store_banner_url = Minimog_Image::get_attachment_url_by_id( [
		'id'   => $store_banner_id,
		'size' => '480x308',
	] );
}
$show_store_open_close    = dokan_get_option( 'store_open_close', 'dokan_appearance', 'on' );
$dokan_store_time_enabled = isset( $store_info['dokan_store_time_enabled'] ) ? $store_info['dokan_store_time_enabled'] : '';
$store_open_is_on         = ( 'on' === $show_store_open_close && 'yes' === $dokan_store_time_enabled && ! $is_store_featured ) ? 'store_open_is_on' : '';
?>
<div class="grid-item dokan-single-seller <?php echo ( ! $store_banner_id ) ? 'no-banner-img' : ''; ?>">
	<div class="store-wrapper">
		<div class="store-header">
			<div class="store-banner">
				<a href="<?php echo esc_url( $store_url ); ?>">
					<div class="store-banner-bg"
					     style="background-image: url(<?php echo esc_url( $store_banner_url ); ?>)"></div>
				</a>
			</div>
			<div class="store-badges">
				<?php if ( $is_store_featured ) : ?>
					<div class="store-badge hot featured-label">
						<span><?php esc_html_e( 'Featured', 'minimog' ); ?></span></div>
				<?php endif ?>

				<?php do_action( 'dokan_seller_listing_after_featured', $seller, $store_info ); ?>

				<?php if ( 'on' === $show_store_open_close && 'yes' === $dokan_store_time_enabled ) : ?>
					<?php if ( dokan_is_store_open( $seller->ID ) ) { ?>
						<div class="store-badge is-opening dokan-store-is-open-close-status dokan-store-is-open-status"
						     title="<?php esc_attr_e( 'Store is Open', 'minimog' ); ?>">
							<span><?php esc_html_e( 'Open', 'minimog' ); ?></span></div>
					<?php } else { ?>
						<div class="store-badge is-closed dokan-store-is-open-close-status dokan-store-is-closed-status"
						     title="<?php esc_attr_e( 'Store is Closed', 'minimog' ); ?>">
							<span><?php esc_html_e( 'Closed', 'minimog' ); ?></span></div>
					<?php } ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="store-info">
			<div class="store-main-info">
				<div class="seller-avatar">
					<a href="<?php echo esc_url( $store_url ); ?>">
						<img src="<?php echo esc_url( $vendor->get_avatar() ) ?>"
						     alt="<?php echo esc_attr( $vendor->get_shop_name() ) ?>"
						     size="150">
					</a>
				</div>
				<h2 class="store-name">
					<a href="<?php echo esc_attr( $store_url ); ?>"><?php echo esc_html( $store_name ); ?></a>
					<?php apply_filters( 'dokan_store_list_loop_after_store_name', $vendor ); ?>
				</h2>
			</div>

			<?php if ( ! empty( $store_rating['count'] ) ): ?>
				<div class="dokan-seller-rating"
				     title="<?php echo sprintf( esc_attr__( 'Rated %s out of 5', 'minimog' ), esc_attr( $store_rating['rating'] ) ) ?>">
					<?php echo wp_kses_post( dokan_generate_ratings( $store_rating['rating'], 5 ) ); ?>
					<p class="rating">
						<?php echo esc_html( sprintf( __( '%s out of 5', 'minimog' ), $store_rating['rating'] ) ); ?>
					</p>
				</div>
			<?php endif ?>

			<?php if ( ! dokan_is_vendor_info_hidden( 'address' ) && $store_address ): ?>
				<?php
				$allowed_tags = array(
					'span' => array(
						'class' => array(),
					),
					'br'   => array(),
				);
				?>
				<p class="store-address"><?php echo wp_kses( $store_address, $allowed_tags ); ?></p>
			<?php endif ?>

			<?php if ( ! dokan_is_vendor_info_hidden( 'phone' ) && $store_phone ) { ?>
				<p class="store-phone"><?php echo esc_html( $store_phone ); ?></p>
			<?php } ?>

			<?php if ( ! dokan_is_vendor_info_hidden( 'email' ) && $store_email ) { ?>
				<p class="store-email">
					<a class="link-transition-02"
					   href="<?php echo esc_url( 'mailto:' . $store_email ) ?>"><?php echo esc_html( $store_email ); ?></a>
				</p>
			<?php } ?>

			<?php do_action( 'dokan_seller_listing_after_store_data', $seller, $store_info ); ?>
		</div>
	</div>
</div>
