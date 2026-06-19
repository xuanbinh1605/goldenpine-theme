<?php
/**
 * Template Part — About Page Music & Atmosphere Section
 *
 * Displays 4 music genre cards (EDM, Cultural, Live, Show) in a grid,
 * followed by a prominent booking CTA card.
 *
 * Content managed via Appearance > Customize > About Page > Music.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$label    = get_theme_mod( 'goldenpine_about_page_music_label', 'Music & Atmosphere' );
$heading1 = get_theme_mod( 'goldenpine_about_page_music_h1', 'The sound of' );
$heading2 = get_theme_mod( 'goldenpine_about_page_music_h2', 'Golden Pine.' );
$subtext  = get_theme_mod( 'goldenpine_about_page_music_subtext', 'Four sounds, one unforgettable night.' );

// 4 music cards
$cards = [];
for ( $i = 1; $i <= 4; $i++ ) {
	$cards[] = [
		'number'      => sprintf( '%02d', $i ),
		'title'       => get_theme_mod( "goldenpine_about_page_music_card{$i}_title", '' ),
		'description' => get_theme_mod( "goldenpine_about_page_music_card{$i}_desc", '' ),
	];
}

// CTA
$cta_heading = get_theme_mod( 'goldenpine_about_page_cta_heading', 'Ready for tonight? Reserve before the floor fills.' );
$cta_book    = get_theme_mod( 'goldenpine_about_page_cta_book_text', 'Book A Table' );
$cta_link    = get_theme_mod( 'goldenpine_about_page_cta_book_link', home_url( '/booking' ) );
$cta_call    = get_theme_mod( 'goldenpine_about_page_cta_call_text', 'Call Now' );
$cta_phone   = get_theme_mod( 'goldenpine_footer_phone', '' );
?>

<section class="relative py-24 md:py-32 px-6 lg:px-12 bg-background overflow-hidden">

	<!-- Decorative glow -->
	<div
		aria-hidden="true"
		class="absolute left-1/2 -translate-x-1/2 bottom-0 w-[900px] h-[300px] pointer-events-none"
		style="background: radial-gradient(rgba(226, 190, 61, 0.08) 0%, transparent 70%); filter: blur(80px);"
	></div>

	<div class="relative max-w-7xl mx-auto">

		<?php if ( $label ) : ?>
			<p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-8 flex items-center gap-3">
				<span class="h-px w-8 bg-gold inline-block"></span>
				<?php echo esc_html( $label ); ?>
			</p>
		<?php endif; ?>

		<div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-10 items-end mb-12 md:mb-16">
			<h2
				class="md:col-span-8 font-black uppercase text-foreground leading-[0.92] tracking-tight text-balance"
				style="font-size: clamp(2.8rem, 7vw, 7rem);"
			>
				<?php echo esc_html( $heading1 ); ?><br>
				<span class="text-gold"><?php echo esc_html( $heading2 ); ?></span>
			</h2>
			<?php if ( $subtext ) : ?>
				<p class="md:col-span-4 text-lg md:text-xl font-light text-foreground/75 leading-snug text-pretty">
					<?php echo esc_html( $subtext ); ?>
				</p>
			<?php endif; ?>
		</div>

		<!-- 4 Music Cards -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-12 md:mb-16">
			<?php foreach ( $cards as $card ) : ?>
				<div class="rounded-3xl border border-border bg-card p-8 flex flex-col gap-5 min-h-[260px] box-glow-gold-hover">
					<?php if ( $card['number'] ) : ?>
						<span class="font-bold text-gold/55 text-sm tracking-widest">
							<?php echo esc_html( $card['number'] ); ?>
						</span>
					<?php endif; ?>

					<?php if ( $card['title'] ) : ?>
						<h3
							class="font-black uppercase text-foreground leading-none tracking-tight"
							style="font-size: clamp(2.5rem, 4vw, 2.9375rem);"
						>
							<?php echo esc_html( $card['title'] ); ?>
						</h3>
					<?php endif; ?>

					<?php if ( $card['description'] ) : ?>
						<p class="text-base font-light text-foreground/70 leading-relaxed mt-auto">
							<?php echo esc_html( $card['description'] ); ?>
						</p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Booking CTA Card -->
		<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 rounded-3xl bg-gold text-black p-8 md:p-10">
			<?php if ( $cta_heading ) : ?>
				<p
					class="font-black uppercase leading-tight text-balance tracking-tight max-w-2xl"
					style="font-size: clamp(1.8rem, 3.5vw, 4rem);"
				>
					<?php echo esc_html( $cta_heading ); ?>
				</p>
			<?php endif; ?>

			<div class="flex flex-wrap items-center gap-3 shrink-0">
				<?php if ( $cta_book && $cta_link ) : ?>
					<a
						href="<?php echo esc_url( $cta_link ); ?>"
						class="group inline-flex items-center gap-3 rounded-full bg-black pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-gold hover:bg-black/85 transition-colors"
					>
						<?php echo esc_html( $cta_book ); ?>
						<span class="flex h-10 w-10 items-center justify-center rounded-full bg-gold text-black transition-transform group-hover:translate-x-1" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
						</span>
					</a>
				<?php endif; ?>

				<?php if ( $cta_call && $cta_phone ) : ?>
					<a
						href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $cta_phone ) ); ?>"
						class="group inline-flex items-center gap-3 rounded-full border border-black/30 pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-black hover:bg-black hover:text-gold transition-colors"
					>
						<?php echo esc_html( $cta_call ); ?>
						<span class="flex h-10 w-10 items-center justify-center rounded-full bg-black/10 transition-transform group-hover:-rotate-12" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
						</span>
					</a>
				<?php endif; ?>
			</div>
		</div>

	</div>

</section>
