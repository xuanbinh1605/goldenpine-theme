<?php
/**
 * Template Name: Booking
 *
 * Goldenpine Theme — page-booking.php
 *
 * Booking / reservation page template. Displays a hero, booking form,
 * photo sidebar, and a direct-call card.
 *
 * Sections:
 *  - hero : full-bleed image with heading
 *  - form : reservation form + sidebar
 *
 * Content managed via Appearance > Customize > Booking Page.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main page-booking min-h-screen bg-background">

	<?php get_template_part( 'template-parts/booking/hero' ); ?>
	<?php get_template_part( 'template-parts/booking/form' ); ?>

</main><!-- #primary -->

<?php get_footer(); ?>
