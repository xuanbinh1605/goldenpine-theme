<?php
/**
 * Template Part — About Page Story Section
 *
 * Two-column layout with image on left and text content on right,
 * including heading, intro, description, and CTA button.
 *
 * Content managed via Appearance > Customize > About Page > Story.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$label       = get_theme_mod( 'goldenpine_about_page_story_label', 'Our Story' );
$heading     = get_theme_mod( 'goldenpine_about_page_story_heading', 'Born to redefine Da Nang nightlife.' );
$intro       = get_theme_mod( 'goldenpine_about_page_story_intro', 'A living stage where culture, luxury, and the spirit of celebration come together every night.' );
$description = get_theme_mod( 'goldenpine_about_page_story_description', 'From the hand-curated lighting rig overhead to the theatrical installations that transform the space season by season, every detail is designed to surprise and delight.' );
$cta_text    = get_theme_mod( 'goldenpine_about_page_story_cta_text', 'Book A Table' );
$cta_link    = get_theme_mod( 'goldenpine_about_page_story_cta_link', home_url( '/booking' ) );

$image_id  = absint( get_theme_mod( 'goldenpine_about_page_story_image', 31 ) );
$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '';
$image_alt = $image_id ? (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
?>

<section class="relative py-24 md:py-32 px-6 lg:px-12 bg-background overflow-hidden">

	<!-- Decorative glow -->
	<div
		aria-hidden="true"
		class="absolute -left-40 top-0 w-[500px] h-[500px] pointer-events-none"
		style="background: radial-gradient(circle, rgba(226, 190, 61, 0.09) 0%, transparent 70%); filter: blur(80px);"
	></div>

	<div class="relative max-w-7xl mx-auto">

		<?php if ( $label ) : ?>
			<p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-8 flex items-center gap-3">
				<span class="h-px w-8 bg-gold inline-block"></span>
				<?php echo esc_html( $label ); ?>
			</p>
		<?php endif; ?>

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">

			<!-- Image -->
			<div class="lg:col-span-7 relative overflow-hidden rounded-3xl h-[440px] md:h-[600px] box-glow-gold-hover">
				<?php if ( $image_url ) : ?>
					<img
						src="<?php echo esc_url( $image_url ); ?>"
						alt="<?php echo esc_attr( $image_alt ); ?>"
						loading="lazy"
						decoding="async"
						class="object-cover"
						style="position: absolute; height: 100%; width: 100%; inset: 0px;"
					>
				<?php else : ?>
					<div class="absolute inset-0 bg-card"></div>
				<?php endif; ?>
			</div>

			<!-- Text Content -->
			<div class="lg:col-span-5 flex flex-col gap-6">
				<?php if ( $heading ) : ?>
					<h2
						class="font-black uppercase text-foreground leading-[0.92] tracking-tight text-balance"
						style="font-size: clamp(2.2rem, 4.5vw, 4rem);"
					>
						<?php echo esc_html( $heading ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( $intro ) : ?>
					<p class="text-lg md:text-xl font-light text-foreground/85 leading-snug">
						<?php echo esc_html( $intro ); ?>
					</p>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<p class="text-base md:text-lg font-light text-foreground/65 leading-relaxed">
						<?php echo esc_html( $description ); ?>
					</p>
				<?php endif; ?>

				<?php if ( $cta_text && $cta_link ) : ?>
					<div class="pt-2">
						<a
							href="<?php echo esc_url( $cta_link ); ?>"
							class="group inline-flex items-center gap-3 rounded-full bg-gold pl-7 pr-3 py-3 text-sm md:text-base font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors box-glow-gold"
						>
							<?php echo esc_html( $cta_text ); ?>
							<span class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
							</span>
						</a>
					</div>
				<?php endif; ?>
			</div>

		</div>

	</div>

</section>
