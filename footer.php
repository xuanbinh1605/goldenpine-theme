<?php
/**
 * Goldenpine Theme — footer.php
 *
 * The global site footer. Closes the wrappers opened in header.php and
 * loads the footer partial from template-parts/global/.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

    <?php get_template_part( 'template-parts/global/site', 'footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
