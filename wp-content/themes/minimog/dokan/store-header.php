<?php
/**
 * @var \WeDevs\Dokan\Vendor\Vendor $store_user
 */
$store_user    = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info    = $store_user->get_shop_info();
$social_info   = $store_user->get_social_profiles();
$social_fields = dokan_get_social_profile_fields();

$dokan_store_times = ! empty( $store_info['dokan_store_time'] ) ? $store_info['dokan_store_time'] : [];
$current_time      = dokan_current_datetime();
$today             = strtolower( $current_time->format( 'l' ) );

$dokan_appearance = get_option( 'dokan_appearance' );
$profile_layout   = empty( $dokan_appearance['store_header_template'] ) ? 'default' : $dokan_appearance['store_header_template'];
$store_address    = dokan_get_seller_short_address( $store_user->get_id(), false );

$dokan_store_time_enabled = isset( $store_info['dokan_store_time_enabled'] ) ? $store_info['dokan_store_time_enabled'] : '';
$store_open_notice        = isset( $store_info['dokan_store_open_notice'] ) && ! empty( $store_info['dokan_store_open_notice'] ) ? $store_info['dokan_store_open_notice'] : __( 'Store Open', 'minimog' );
$store_closed_notice      = isset( $store_info['dokan_store_close_notice'] ) && ! empty( $store_info['dokan_store_close_notice'] ) ? $store_info['dokan_store_close_notice'] : __( 'Store Closed', 'minimog' );
$show_store_open_close    = dokan_get_option( 'store_open_close', 'dokan_appearance', 'on' );
$hide_rating              = dokan_get_option( 'hide_vendor_rating', 'dokan_appearance' );

$general_settings = get_option( 'dokan_general', [] );
$banner_width     = dokan_get_vendor_store_banner_width();

if ( ( 'default' === $profile_layout ) || ( 'layout2' === $profile_layout ) ) {
	$profile_img_class = 'profile-img-circle';
} else {
	$profile_img_class = 'profile-img-square';
}

if ( 'layout3' === $profile_layout ) {
	unset( $store_info['banner'] );

	$no_banner_class      = ' profile-frame-no-banner';
	$no_banner_class_tabs = ' dokan-store-tabs-no-banner';

} else {
	$no_banner_class      = '';
	$no_banner_class_tabs = '';
}
?>
<div class="dokan-profile-frame-wrapper">
	<div class="profile-frame<?php echo esc_attr( $no_banner_class ); ?>">

		<div class="profile-info-box profile-layout-<?php echo esc_attr( $profile_layout ); ?>">
			<div class="profile-info-cover">
				<?php
				$shop_banner_url = $store_user->get_banner() ? $store_user->get_banner() : MINIMOG_THEME_ASSETS_URI . '/dokan/default-store-cover.jpg';
				?>
				<img src="<?php echo esc_url( $shop_banner_url ); ?>"
				     alt="<?php echo esc_attr( $store_user->get_shop_name() ); ?>"
				     title="<?php echo esc_attr( $store_user->get_shop_name() ); ?>"
				     class="profile-info-img">

				<?php if ( $social_fields ) { ?>
					<div class="store-social-wrapper">
						<div class="store-social">
							<?php foreach ( $social_fields as $key => $field ) { ?>
								<?php if ( ! empty( $social_info[ $key ] ) ) { ?>
									<a href="<?php echo esc_url( $social_info[ $key ] ); ?>"
									   target="_blank"><i
											class="fab fa-<?php echo esc_attr( $field['icon'] ); ?>"></i></a>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>

			<div class="profile-info-summery-wrapper dokan-clearfix">
				<div class="profile-info-summery">
					<div class="profile-info-head">
						<div class="profile-img <?php echo esc_attr( $profile_img_class ); ?>">
							<img src="<?php echo esc_url( $store_user->get_avatar() ) ?>"
							     alt="<?php echo esc_attr( $store_user->get_shop_name() ) ?>"
							     width="150" height="150"/>
						</div>
						<?php if ( ! empty( $store_user->get_shop_name() ) && 'default' === $profile_layout ) { ?>
							<h1 class="store-name"><?php echo esc_html( $store_user->get_shop_name() ); ?><?php apply_filters( 'dokan_store_header_after_store_name', $store_user ); ?></h1>
						<?php } ?>
					</div>

					<div class="profile-info">
						<?php if ( ! empty( $store_user->get_shop_name() ) && 'default' !== $profile_layout ) { ?>
							<h1 class="store-name"><?php echo esc_html( $store_user->get_shop_name() ); ?></h1>
						<?php } ?>

						<ul class="dokan-store-info">
							<?php if ( ! dokan_is_vendor_info_hidden( 'address' ) && isset( $store_address ) && ! empty( $store_address ) ) { ?>
								<li class="dokan-store-address">
									<p class="store-info-heading"><?php esc_html_e( 'Address:', 'minimog' ); ?></p>
									<div class="store-info-text">
										<?php echo wp_kses_post( $store_address ); ?>
									</div>
								</li>
							<?php } ?>

							<?php if ( ! dokan_is_vendor_info_hidden( 'phone' ) && ! empty( $store_user->get_phone() ) ) { ?>
								<li class="dokan-store-phone">
									<p class="store-info-heading"><?php esc_html_e( 'Phone:', 'minimog' ); ?></p>
									<div class="store-info-text">
										<a href="tel:<?php echo esc_html( $store_user->get_phone() ); ?>"><?php echo esc_html( $store_user->get_phone() ); ?></a>
									</div>
								</li>
							<?php } ?>

							<?php if ( ! dokan_is_vendor_info_hidden( 'email' ) && $store_user->show_email() == 'yes' ) { ?>
								<li class="dokan-store-email">
									<p class="store-info-heading"><?php esc_html_e( 'Email:', 'minimog' ); ?></p>
									<div class="store-info-text">
										<a href="mailto:<?php echo esc_attr( antispambot( $store_user->get_email() ) ); ?>"><?php echo esc_attr( antispambot( $store_user->get_email() ) ); ?></a>
									</div>
								</li>
							<?php } ?>

							<?php if ( 'on' !== $hide_rating ) : ?>
								<li class="dokan-store-rating">
									<p class="store-info-heading"><?php esc_html_e( 'Rating:', 'minimog' ); ?></p>
									<div class="store-info-text">
										<?php echo wp_kses_post( dokan_get_readable_seller_rating( $store_user->get_id() ) ); ?>
									</div>
								</li>
							<?php endif; ?>

							<?php if ( 'on' === $show_store_open_close && 'yes' === $dokan_store_time_enabled ) : ?>
								<li class="dokan-store-open-close">
									<p class="store-info-heading"><?php esc_html_e( 'Open time:', 'minimog' ); ?></p>
									<div class="store-info-text">
										<a href="#"
										   class="store-open-close-notice"
										   data-minimog-toggle="modal"
										   data-minimog-target="#modal-store-open-times"
										>
											<span class='store-notice'>
											<?php if ( dokan_is_store_open( $store_user->get_id() ) ) : ?>
												<?php echo esc_html( $store_open_notice ); ?>
											<?php else : ?>
												<?php echo esc_html( $store_closed_notice ); ?>
											<?php endif; ?>
											</span>
										</a>
									</div>
								</li>
							<?php endif ?>

							<?php do_action( 'dokan_store_header_info_fields', $store_user->get_id() ); ?>
						</ul>

						<ul class="dokan-store-actions">
							<?php
							/**
							 * Move Hook position for better UX
							 */
							do_action( 'dokan_after_store_tabs', $store_user->get_id() );
							?>
						</ul>
					</div> <!-- .profile-info -->
				</div><!-- .profile-info-summery -->
			</div><!-- .profile-info-summery-wrapper -->
		</div> <!-- .profile-info-box -->
	</div> <!-- .profile-frame -->
</div>

<div class="minimog-modal modal-store-open-times" id="modal-store-open-times" aria-hidden="true" role="dialog" hidden>
	<div class="modal-overlay"></div>
	<div class="modal-content">
		<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
		<div class="modal-content-wrap">
			<div class="modal-content-inner">
				<div id="vendor-store-times" class="vendor-store-times">
					<div class="store-times-heading">
						<h4><?php esc_html_e( 'Weekly Store Timing', 'minimog' ); ?></h4>
					</div>
					<?php
					foreach ( dokan_get_translated_days() as $day_key => $day ) :
						$store_info = ! empty( $dokan_store_times[ $day_key ] ) ? $dokan_store_times[ $day_key ] : [];
						$store_status = ! empty( $store_info['status'] ) ? $store_info['status'] : 'close';
						?>
						<div class="store-time-tags">
							<div
								class="store-days <?php echo $today === $day_key ? 'current_day' : ''; ?>"><?php echo esc_html( $day ); ?></div>
							<div class="store-times">
								<?php if ( $store_status === 'close' ) : ?>
									<span
										class="store-close"><?php esc_html_e( 'CLOSED', 'minimog' ); ?></span>
								<?php endif; ?>

								<?php
								// Get store times.
								$opening_times = ! empty( $store_info['opening_time'] ) ? $store_info['opening_time'] : [];

								// If dokan pro doesn't exists then get single item.
								if ( ! dokan()->is_pro_exists() ) {
									$opening_times = ! empty( $opening_times ) && is_array( $opening_times ) ? $opening_times[0] : [];
								}

								$times_length               = ! empty( $opening_times ) ? count( (array) $opening_times ) : 0;

								// Get formatted times.
								for ( $index = 0; $index < $times_length; $index++ ) :
									$formatted_opening_time = $current_time->modify( $store_info['opening_time'][ $index ] );
									$formatted_closing_time = $current_time->modify( $store_info['closing_time'][ $index ] );

									// check if store is open or closed time is valid.
									if ( empty( $formatted_opening_time ) || empty( $formatted_closing_time ) ) {
										continue;
									}

									$exact_time = '';

									if ( $today === $day_key && $formatted_opening_time <= $current_time && $formatted_closing_time >= $current_time ) {
										$exact_time = 'current_time';
									}
									?>
									<span class="store-open <?php echo $exact_time; ?>"
									      href="#"><?php echo esc_html( $formatted_opening_time->format( wc_time_format() ) . ' - ' . $formatted_closing_time->format( wc_time_format() ) ); ?></span>
								<?php endfor; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
