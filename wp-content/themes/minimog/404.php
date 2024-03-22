<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link    https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Minimog
 * @since   1.0.0
 */

get_header( '404' );

$image     = Minimog_Helper::get_redux_image_url( 'error404_page_image' );
$title     = Minimog::setting( 'error404_page_title' );
$sub_title = Minimog::setting( 'error404_page_sub_title' );
$text      = Minimog::setting( 'error404_page_text' );
?>
	<div class="page-404-content">
		<div class="container">
			<div class="row row-xs-center">
				<div class="col-md-12">
					<?php if ( ! empty( $image ) ): ?>
						<div class="error-image">
							<img src="<?php echo esc_url( $image ); ?>"
							     alt="<?php esc_attr_e( 'Not Found Image', 'minimog' ); ?>"/>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $title ) ): ?>
						<h3 class="error-404-title">
							<?php echo wp_kses( $title, 'minimog-default' ); ?>
						</h3>
					<?php endif; ?>

					<?php if ( ! empty( $sub_title ) ): ?>
						<h4 class="error-404-sub-title">
							<?php echo wp_kses( $sub_title, 'minimog-default' ); ?>
						</h4>
					<?php endif; ?>

					<?php if ( ! empty( $text ) ): ?>
						<div class="error-404-text">
							<?php echo wp_kses( $text, 'minimog-default' ); ?>
						</div>
					<?php endif; ?>

					<?php if ( Minimog::setting( 'error404_page_search_enable' ) ): ?>
						<div class="error-search-form">
							<?php get_search_form(); ?>
						</div>
					<?php endif; ?>

					<?php if ( Minimog::setting( 'error404_page_buttons_enable' ) ): ?>
						<div class="error-buttons">
							<?php
							Minimog_Templates::render_button( [
								'text' => esc_html__( 'Go to Home', 'minimog' ),
								'link' => [
									'url' => esc_url( home_url( '/' ) ),
								],
								'id'   => 'btn-return-home',
							] );
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php get_footer();
