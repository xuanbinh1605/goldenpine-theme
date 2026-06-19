<?php
/**
 * Goldenpine Theme — archive-event.php
 *
 * Archive template for the 'event' Custom Post Type. Displays a full-page
 * events listing with a hero, filterable event cards, and a booking CTA.
 *
 * Sections:
 *  - hero        : full-bleed image with archive heading
 *  - events-list : all events with taxonomy filter tabs
 *  - cta         : "Don't miss the night" booking call-to-action
 *
 * Content managed via Appearance > Customize > Events Archive.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main page-archive-event min-h-screen bg-background">

	<?php get_template_part( 'template-parts/archive-event/hero' ); ?>
	<?php get_template_part( 'template-parts/archive-event/events-list' ); ?>
	<?php get_template_part( 'template-parts/archive-event/cta' ); ?>

</main><!-- #primary -->

<?php get_footer(); ?>
