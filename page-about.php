<?php
/**
 * Template Name: About
 *
 * Goldenpine Theme — page-about.php
 *
 * Template for the About page. Displays the full story of Golden Pine Pub
 * with hero, story section, concept & space showcase, and music atmosphere.
 *
 * Sections:
 *  - hero    : full-bleed hero with heading
 *  - story   : image + text about the venue
 *  - concept : themed photo grid + social CTA
 *  - music   : music genre cards + booking CTA
 *
 * Content managed via Appearance > Customize > About Page.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main page-about min-h-screen bg-background">

	<?php get_template_part( 'template-parts/about/hero' ); ?>
	<?php get_template_part( 'template-parts/about/story' ); ?>
	<?php get_template_part( 'template-parts/about/concept' ); ?>
	<?php get_template_part( 'template-parts/about/music' ); ?>

</main><!-- #primary -->

<?php get_footer(); ?>
