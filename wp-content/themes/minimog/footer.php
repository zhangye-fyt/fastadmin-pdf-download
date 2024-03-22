<?php
/**
 * The template for displaying the footer.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Minimog
 * @since   1.0
 */

?>
</div><!-- /.content-wrapper -->

<?php Minimog_THA::instance()->footer_before(); ?>

<?php minimog_load_template( 'footer/entry' ); ?>

<?php Minimog_THA::instance()->footer_after(); ?>

</div><!-- /.site -->

<?php wp_footer(); ?>
</body>
</html>
