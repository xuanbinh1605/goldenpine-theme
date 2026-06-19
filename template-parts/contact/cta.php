<?php
/**
 * Template Part — Contact Page CTA Section
 *
 * Full-bleed atmospheric image with "Da Nang's most talked about night"
 * heading and Book A Table / Call Now buttons.
 *
 * Content managed via Appearance > Customize > Contact Page > CTA Section.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$label     = get_theme_mod( 'goldenpine_contact_cta2_label',     'Every Night Is An Event' );
$heading1  = get_theme_mod( 'goldenpine_contact_cta2_heading_1', "Da Nang's most" );
$heading2  = get_theme_mod( 'goldenpine_contact_cta2_heading_2', 'talked about night.' );
$book_text = get_theme_mod( 'goldenpine_contact_cta2_book_text', 'Book A Table' );
$book_link = get_theme_mod( 'goldenpine_contact_cta2_book_link', home_url( '/booking' ) );
$call_text = get_theme_mod( 'goldenpine_contact_cta2_call_text', 'Call Now' );
$phone_raw = preg_replace( '/[^+\d]/', '', get_theme_mod( 'goldenpine_footer_phone', '' ) );

$image_id  = absint( get_theme_mod( 'goldenpine_contact_cta2_image', 0 ) );
$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '';
$image_alt = $image_id ? (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
?>

<section class="relative overflow-hidden">

	<!-- Background Layer -->
	<div class="absolute inset-0 z-0">
		<?php if ( $image_url ) : ?>
			<img
				src="<?php echo esc_url( $image_url ); ?>"
				alt="<?php echo esc_attr( $image_alt ); ?>"
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
	<div class="relative z-10 max-w-6xl mx-auto px-6 lg:px-12 py-24 md:py-32 text-center">

		<?php if ( $label ) : ?>
			<p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-8 flex items-center justify-center gap-3">
				<span class="h-px w-8 bg-gold inline-block"></span>
				<?php echo esc_html( $label ); ?>
				<span class="h-px w-8 bg-gold inline-block"></span>
			</p>
		<?php endif; ?>

		<h2
			class="font-black uppercase text-white leading-[0.92] tracking-tight text-balance mb-10"
			style="font-size: clamp(3rem, 8vw, 8rem);"
		>
			<?php echo esc_html( $heading1 ); ?><br>
			<span class="text-gold"><?php echo esc_html( $heading2 ); ?></span>
		</h2>

		<div class="flex flex-wrap items-center justify-center gap-3 md:gap-4">
			<?php if ( $book_text && $book_link ) : ?>
				<a
					href="<?php echo esc_url( $book_link ); ?>"
					class="group inline-flex items-center gap-3 rounded-full bg-gold pl-8 pr-3 py-4 text-base font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors box-glow-gold"
				>
					<?php echo esc_html( $book_text ); ?>
					<span class="flex h-11 w-11 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
					</span>
				</a>
			<?php endif; ?>

			<?php if ( $call_text && $phone_raw ) : ?>
				<a
					href="tel:<?php echo esc_attr( $phone_raw ); ?>"
					class="group inline-flex items-center gap-3 rounded-full border border-white/30 bg-white/5 backdrop-blur-md pl-8 pr-3 py-4 text-base font-bold uppercase tracking-wider text-white hover:border-gold hover:text-gold transition-colors"
				>
					<?php echo esc_html( $call_text ); ?>
					<span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 transition-transform group-hover:-rotate-12" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
					</span>
				</a>
			<?php endif; ?>
		</div>

	</div>

</section>
