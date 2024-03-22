<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section
 *
 * @link     https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package  Minimog
 * @since    1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php html_class(); ?>>
<head>
	<?php Minimog_THA::instance()->head_top(); ?>
	<meta charset="<?php echo esc_attr( get_bloginfo( 'charset', 'display' ) ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url', 'display' ) ); ?>">
	<?php endif; ?>
	<?php Minimog_THA::instance()->head_bottom(); ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> <?php Minimog::body_attributes(); ?>>

<?php wp_body_open(); ?>

<?php Minimog_Templates::pre_loader(); ?>

<div id="page" class="site">
	<div class="content-wrapper">
		<?php Minimog_Templates::slider( 'above' ); ?>
		<?php Minimog_Top_Bar::instance()->render(); ?>

		<?php minimog_load_template( 'header/entry' ); ?>

		<?php Minimog_Templates::slider( 'below' ); ?>
		<?php Minimog_Title_Bar::instance()->render(); ?>
