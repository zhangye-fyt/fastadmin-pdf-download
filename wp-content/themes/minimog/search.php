<?php
/**
 * The template for displaying search results pages.
 *
 * @link     https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package  Minimog
 * @since    1.0
 */
get_header();
?>
	<div id="page-content" class="page-content">
		<div class="container">
			<div class="row">

				<?php Minimog_Sidebar::instance()->render( 'left' ); ?>

				<div class="page-main-content">
					<div class="block-wrap">
						<?php if ( Minimog_Helper::is_search_has_results() && 'above' === Minimog::setting( 'search_page_search_form_display' ) ) : ?>
							<div
								class="search-page-search-form <?php echo esc_attr( Minimog::setting( 'search_page_search_form_display' ) ); ?>">
								<?php get_search_form(); ?>
							</div>
						<?php endif; ?>

						<?php minimog_load_template( 'content', 'search' ); ?>

						<?php if ( Minimog_Helper::is_search_has_results() && 'below' === Minimog::setting( 'search_page_search_form_display' ) ) : ?>
							<div
								class="search-page-search-form <?php echo esc_attr( Minimog::setting( 'search_page_search_form_display' ) ); ?>">
								<?php get_search_form(); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer();
