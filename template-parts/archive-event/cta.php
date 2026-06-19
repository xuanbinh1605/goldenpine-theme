<?php
/**
 * Template Part — Events Archive CTA Section
 *
 * "Don't miss the night" booking call-to-action. Mirrors the structure of
 * the front-page reservation section with events-archive-specific content.
 *
 * Content managed via Appearance > Customize > Events Archive > CTA Section.
 * The phone number is shared from Theme Options > Footer Settings.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_gpine_ev_cta_label     = get_theme_mod( 'goldenpine_events_cta_label',     'Reserve' );
$_gpine_ev_cta_heading_1 = get_theme_mod( 'goldenpine_events_cta_heading_1', "Don't miss" );
$_gpine_ev_cta_heading_2 = get_theme_mod( 'goldenpine_events_cta_heading_2', 'the night.' );
$_gpine_ev_cta_book_text = get_theme_mod( 'goldenpine_events_cta_book_text', 'Book A Table' );
$_gpine_ev_cta_book_link = get_theme_mod( 'goldenpine_events_cta_book_link', home_url( '/booking' ) );
$_gpine_ev_cta_call_text = get_theme_mod( 'goldenpine_events_cta_call_text', 'Call Now' );

$_gpine_ev_cta_img_id  = absint( get_theme_mod( 'goldenpine_events_cta_image', 59 ) );
$_gpine_ev_cta_img_url = $_gpine_ev_cta_img_id
	? wp_get_attachment_image_url( $_gpine_ev_cta_img_id, 'full' )
	: '';
$_gpine_ev_cta_img_alt = $_gpine_ev_cta_img_id
	? (string) get_post_meta( $_gpine_ev_cta_img_id, '_wp_attachment_image_alt', true )
	: '';

// Phone from shared footer setting.
$_gpine_ev_cta_phone = get_theme_mod( 'goldenpine_footer_phone', '' );
?>

<section class="relative py-24 md:py-32 px-6 lg:px-12 overflow-hidden">

	<!-- Background Layer -->
	<div class="absolute inset-0 z-0">
		<?php if ( $_gpine_ev_cta_img_url ) : ?>
			<img
				src="<?php echo esc_url( $_gpine_ev_cta_img_url ); ?>"
				alt="<?php echo esc_attr( $_gpine_ev_cta_img_alt ); ?>"
				loading="lazy"
				decoding="async"
				class="object-cover object-center"
				style="position: absolute; height: 100%; width: 100%; inset: 0px;"
			>
		<?php else : ?>
			<div class="absolute inset-0 bg-background"></div>
		<?php endif; ?>
		<div class="absolute inset-0 bg-gradient-to-br from-black/88 via-black/78 to-black/92"></div>
	</div>

	<!-- Gold radial glow -->
	<div
		aria-hidden="true"
		class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[300px] pointer-events-none z-0"
		style="background: radial-gradient(rgba(226, 190, 61, 0.14) 0%, transparent 70%); filter: blur(80px);"
	></div>

	<!-- Content -->
	<div class="relative z-10 max-w-5xl mx-auto text-center">

		<?php if ( $_gpine_ev_cta_label ) : ?>
			<p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-8 flex items-center justify-center gap-3">
				<span class="h-px w-8 bg-gold inline-block"></span>
				<?php echo esc_html( $_gpine_ev_cta_label ); ?>
				<span class="h-px w-8 bg-gold inline-block"></span>
			</p>
		<?php endif; ?>

		<h2
			class="font-black uppercase text-white leading-[0.92] tracking-tight text-balance mb-10"
			style="font-size: clamp(3rem, 8vw, 8rem);"
		>
			<?php echo esc_html( $_gpine_ev_cta_heading_1 ); ?><br>
			<span class="text-gold"><?php echo esc_html( $_gpine_ev_cta_heading_2 ); ?></span>
		</h2>

		<!-- CTA Buttons -->
		<div class="flex flex-wrap items-center justify-center gap-3 md:gap-4">

			<a
				href="<?php echo esc_url( $_gpine_ev_cta_book_link ); ?>"
				class="group inline-flex items-center gap-3 rounded-full bg-gold pl-8 pr-3 py-4 text-base font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors box-glow-gold"
			>
				<?php echo esc_html( $_gpine_ev_cta_book_text ); ?>
				<span class="flex h-11 w-11 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
				</span>
			</a>

			<?php if ( $_gpine_ev_cta_phone ) : ?>
				<a
					href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $_gpine_ev_cta_phone ) ); ?>"
					class="group inline-flex items-center gap-3 rounded-full border border-white/30 bg-white/5 backdrop-blur-md pl-8 pr-3 py-4 text-base font-bold uppercase tracking-wider text-white hover:border-gold hover:text-gold transition-colors"
				>
					<?php echo esc_html( $_gpine_ev_cta_call_text ); ?>
					<span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 transition-transform group-hover:-rotate-12" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
					</span>
				</a>
			<?php endif; ?>

		</div>

	</div>

</section>
