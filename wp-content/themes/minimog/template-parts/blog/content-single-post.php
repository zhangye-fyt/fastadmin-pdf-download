<?php
/**
 * The template for displaying content all single posts.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Minimog
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-wrapper' ); ?>>
	<div class="entry-header">
		<div class="entry-header-content">
			<?php Minimog_Post::instance()->entry_categories(); ?>

			<?php if ( '1' === Minimog::setting( 'single_post_title_enable' ) ) : ?>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php endif; ?>

			<?php minimog_load_template( 'blog/single/meta' ); ?>
		</div>

		<?php Minimog_Post::instance()->entry_feature(); ?>
	</div>

	<div class="entry-post-wrap">
		<h2 class="screen-reader-text"><?php echo esc_html( get_the_title() ); ?></h2>

		<div class="entry-content">
			<?php
			the_content( sprintf( /* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'minimog' ), array( 'span' => array( 'class' => array() ) ) ), the_title( '<span class="screen-reader-text">"', '"</span>', false ) ) );

			Minimog_Templates::page_links();
			?>
		</div>

		<div class="entry-footer">
			<?php Minimog_Post::instance()->entry_tags(); ?>
			<?php Minimog_Post::instance()->entry_share(); ?>
		</div>

		<?php
		$author_desc = get_the_author_meta( 'description' );
		if ( '1' === Minimog::setting( 'single_post_author_box_enable' ) && ! empty( $author_desc ) ) {
			Minimog_Templates::post_author();
		}

		if ( '1' === Minimog::setting( 'single_post_pagination_enable' ) ) {
			Minimog_Post::instance()->nav_page_links();
		}

		if ( Minimog::setting( 'single_post_related_enable' ) ) {
			minimog_load_template( 'blog/single/related' );
		}

		// If comments are open or we have at least one comment, load up the comment template.
		if ( '1' === Minimog::setting( 'single_post_comment_enable' ) && ( comments_open() || get_comments_number() ) ) :
			comments_template();
		endif; ?>
	</div>
</article>
