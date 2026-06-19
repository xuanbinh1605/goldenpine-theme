<?php
/**
 * Goldenpine Theme — single-event.php
 *
 * Single template for the 'event' Custom Post Type. Displays full event
 * details including featured image, date/time, description, gallery,
 * event essentials (performer, dress code, etc.), and related events.
 *
 * All sections are conditionally rendered: if a field is empty, that
 * section will not be displayed on the front end.
 *
 * Sections loaded:
 *  - hero             : full-width hero with featured image and event metadata
 *  - booking-cta      : date display and booking call-to-action
 *  - description      : event description content and sidebar essentials
 *  - gallery          : event photo gallery (if images exist)
 *  - related-events   : "More Nights Ahead" section with random related events
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main page-single-event min-h-screen bg-background">

	<?php
	while ( have_posts() ) :
		the_post();

		// Hero section — featured image, title, subtitle, event type, date/time.
		get_template_part( 'template-parts/single-event/hero' );

		// Booking CTA — date card and action buttons.
		get_template_part( 'template-parts/single-event/booking-cta' );

		// Description + Essentials + Gallery — two-column layout: description/gallery and sidebar.
		get_template_part( 'template-parts/single-event/description' );

		// Related events — "More Nights Ahead" section.
		get_template_part( 'template-parts/single-event/related-events' );

	endwhile;
	?>

</main><!-- #primary -->

<?php get_footer(); ?>
