<?php
/**
 * Search form on header
 *
 * @package Minimog
 * @since   1.0.0
 * @version 2.3.1
 */

defined( 'ABSPATH' ) || exit;

$header_type       = Minimog_Global::instance()->get_header_type();
$content_type      = Minimog::setting( 'search_page_filter' );
$with_categories   = Minimog::setting( 'popup_search_categories_enable' );
$search_form_style = Minimog::setting( 'header_search_form_style' );

$wrap_class = 'header-search-form';
$form_class = 'search-form popup-search-form style-' . $search_form_style;

if ( ! empty( $args['extra_class'] ) ) {
	$wrap_class .= " {$args['extra_class']}";
}

if ( 'product' === $content_type ) {
	$form_class .= ' woocommerce-product-search';
}

if ( ! empty( $with_categories ) ) {
	$form_class .= ' search-form-categories';
}

$icon_style = Minimog::setting( 'header_icons_style' );
switch ( $icon_style ) :
	case 'icon-set-02':
		$icon_key = 'search-light';
		break;
	case 'icon-set-03':
		$icon_key = 'phr-magnifying-glass';
		break;
	case 'icon-set-04':
		$icon_key = 'search-solid';
		break;
	case 'icon-set-05':
		$icon_key = 'phb-magnifying-glass';
		break;
	default:
		$icon_key = 'search';
		break;
endswitch;
?>
<div class="<?php echo esc_attr( $wrap_class ); ?>">
	<form role="search" method="get" class="<?php echo esc_attr( $form_class ); ?>"
	      action="<?php echo esc_url( home_url( '/' ) ); ?>">

		<?php if ( ! empty( $with_categories ) ) : ?>
			<div class="search-category-field">
				<select name="placeholder_cat_dropdown" id="placeholder_cat_dropdown" class="search-select">
					<option value="0"><?php esc_html_e( 'All Categories', 'minimog' ); ?></option>
				</select>
			</div>
		<?php endif; ?>
		<span class="screen-reader-text">
			<?php echo esc_html_x( 'Search for:', 'label', 'minimog' ); ?>
		</span>
		<input type="search" class="search-field"
		       placeholder="<?php echo esc_attr( $args['search_field_placeholder'] ); ?>"
		       value="<?php echo get_search_query() ?>" name="s"
		       title="<?php echo esc_attr_x( 'Search for:', 'label', 'minimog' ); ?>"/>
		<button type="submit" class="search-submit">
			<span class="search-btn-icon">
				<?php echo Minimog_SVG_Manager::instance()->get( $icon_key ); ?>
			</span>
			<span class="search-btn-text">
				<?php echo esc_html_x( 'Search', 'submit button', 'minimog' ); ?>
			</span>
		</button>
	</form>
</div>
