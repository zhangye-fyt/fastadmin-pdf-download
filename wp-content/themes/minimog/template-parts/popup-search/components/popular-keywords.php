<?php
/**
 * Popular keywords on search popup
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$keywords = Minimog_Helper::parse_redux_repeater_field_values( Minimog::setting( 'popular_search_keywords', '' ) );

if ( empty( $keywords ) ) {
	return;
}
?>
<div class="popular-search-keywords">
	<span class="label"><?php esc_html_e( 'Popular Searches:', 'minimog' ); ?></span>
	<?php foreach ( $keywords as $keyword ) : ?>
		<?php if ( ! empty( $keyword['text'] ) ) {
			echo '<a href="' . esc_url( get_search_link( $keyword['text'] ) ) . '">' . esc_html( $keyword['text'] ) . '</a>';
		} ?>
	<?php endforeach; ?>
</div>
