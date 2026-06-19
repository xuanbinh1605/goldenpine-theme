<?php
/**
 * Template Name: Contact
 *
 * Goldenpine Theme — page-contact.php
 *
 * Template for the Contact page. Displays location, contact info,
 * opening hours, Google Maps embed, and a full-bleed CTA section.
 *
 * Sections:
 *  - hero  : full-bleed image with heading
 *  - info  : booking CTA card, location, reach us, hours, map
 *  - cta   : atmospheric booking call-to-action
 *
 * Content managed via Appearance > Customize > Contact Page.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main page-contact min-h-screen bg-background">

	<?php get_template_part( 'template-parts/contact/hero' ); ?>
	<?php get_template_part( 'template-parts/contact/info' ); ?>
	<?php get_template_part( 'template-parts/contact/cta' ); ?>

</main><!-- #primary -->

<?php get_footer(); ?>
