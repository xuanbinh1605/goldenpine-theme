<?php
/**
 * Goldenpine Theme — front-page.php
 *
 * Template for the static front page (set under Settings > Reading).
 * Each visual section is a separate partial inside template-parts/front-page/
 * so sections can be reordered, hidden, or extended without touching this file.
 *
 * Sections loaded here:
 *  - hero        : full-width hero banner
 *  - features    : key feature/value-proposition grid
 *  - testimonials: customer quotes carousel
 *  - stats       : animated numbers / statistics strip
 *  - cta         : call-to-action banner (shared component)
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main page-front-page min-h-screen bg-background">

    <?php get_template_part( 'template-parts/front-page/hero' ); ?>
    <?php get_template_part( 'template-parts/front-page/features' ); ?>
    <?php get_template_part( 'template-parts/front-page/testimonials' ); ?>
    <?php get_template_part( 'template-parts/front-page/stats' ); ?>
    <?php get_template_part( 'template-parts/components/cta-section' ); ?>

</main><!-- #primary -->

<?php get_footer(); ?>
