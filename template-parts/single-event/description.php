<?php
/**
 * Template Part — Single Event Description Section
 *
 * Two-column layout:
 *  - Left: Event description (WYSIWYG content)
 *  - Right: Event Essentials sidebar (performer, dress code, etc.)
 *
 * Hidden if both description and all essentials fields are empty.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Fetch event metadata.
$event_description = get_post_meta( get_the_ID(), '_gpine_event_description', true );

// Check if we should render this section at all.
// If no description and no essentials, skip the entire section.
$has_description = ! empty( $event_description );

// We'll check essentials in the included template part.
// For now, always include the section wrapper if description exists.
// The essentials partial will handle its own conditional rendering.

if ( ! $has_description ) {
	// Check if essentials exist before deciding to skip.
	$performer            = get_post_meta( get_the_ID(), '_gpine_event_performer', true );
	$dress_code           = get_post_meta( get_the_ID(), '_gpine_event_dress_code', true );
	$location_name        = get_post_meta( get_the_ID(), '_gpine_event_location_name', true );
	$location_description = get_post_meta( get_the_ID(), '_gpine_event_location_description', true );

	$has_essentials = $performer || $dress_code || $location_name || $location_description;

	if ( ! $has_essentials ) {
		return;
	}
}
?>

<section class="py-20 md:py-28 px-6 lg:px-12">
	<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">

		<!-- Left Column: Description + Gallery -->
		<div class="lg:col-span-7">

			<?php if ( $has_description ) : ?>
				<p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-6 flex items-center gap-3">
					<span class="h-px w-8 bg-gold inline-block"></span>
					<?php esc_html_e( 'About the night', 'goldenpine-theme' ); ?>
				</p>
				<div class="text-xl md:text-2xl font-light text-foreground leading-snug text-pretty mb-10">
					<?php echo wp_kses_post( wpautop( $event_description ) ); ?>
				</div>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/single-event/gallery' ); ?>

		</div><!-- /description column -->

		<!-- Right Column: Event Essentials Sidebar -->
		<aside class="lg:col-span-5">
			<?php get_template_part( 'template-parts/single-event/essentials' ); ?>
		</aside>

	</div>
</section>
