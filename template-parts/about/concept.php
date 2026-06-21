<?php
/**
 * Template Part — About Page Concept & Space Section
 *
 * Showcases 4 themed photo cards (Fire & Aerial, Cultural, Showcase, Seasonal)
 * followed by a social media CTA card with Instagram and Facebook links.
 *
 * Content managed via Appearance > Customize > About Page > Concept.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$label    = get_theme_mod( 'goldenpine_about_page_concept_label', 'Concept & Space' );
$heading1 = get_theme_mod( 'goldenpine_about_page_concept_h1', 'Every season,' );
$heading2 = get_theme_mod( 'goldenpine_about_page_concept_h2', 'a new world.' );
$subtext  = get_theme_mod( 'goldenpine_about_page_concept_subtext', 'Built to transform — never the same visit twice.' );

// 4 image cards
$cards = [];
for ( $i = 1; $i <= 4; $i++ ) {
	$image_id   = absint( get_theme_mod( "goldenpine_about_page_concept_card{$i}_image", 0 ) );
	$image_url  = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';
	$image_alt  = $image_id ? (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
	$title      = get_theme_mod( "goldenpine_about_page_concept_card{$i}_title", '' );

	$is_gif    = $image_url && strtolower( pathinfo( $image_url, PATHINFO_EXTENSION ) ) === 'gif';
	// For GIFs: use the medium-sized thumbnail (static first frame on most hosts) as the poster.
	$poster_url = ( $is_gif && $image_id )
		? ( wp_get_attachment_image_url( $image_id, 'medium' ) ?: $image_url )
		: $image_url;

	$cards[] = [
		'url'        => $image_url,
		'poster_url' => $poster_url,
		'is_gif'     => $is_gif,
		'alt'        => $image_alt,
		'title'      => $title,
	];
}

// Social CTA
$social_heading = get_theme_mod( 'goldenpine_about_page_social_heading', 'See what tonight looks like — follow the story.' );
$instagram_text = get_theme_mod( 'goldenpine_about_page_social_ig_text', 'Instagram' );
$instagram_url  = get_theme_mod( 'goldenpine_social_instagram', 'https://www.instagram.com/goldenpinepub.dn/' );
$facebook_text  = get_theme_mod( 'goldenpine_about_page_social_fb_text', 'Facebook' );
$facebook_url   = get_theme_mod( 'goldenpine_social_facebook', 'https://www.facebook.com/goldenpinepub/' );
?>

<section class="relative py-24 md:py-32 px-6 lg:px-12 bg-card overflow-hidden">

	<!-- Decorative glow -->
	<div
		aria-hidden="true"
		class="absolute -right-32 top-1/2 -translate-y-1/2 w-[600px] h-[600px] pointer-events-none"
		style="background: radial-gradient(circle, rgba(226, 190, 61, 0.08) 0%, transparent 70%); filter: blur(80px);"
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
				style="font-size: clamp(2rem, 7vw, 7rem);"
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

		<!-- 4 Image Cards -->
		<div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-12">
			<?php foreach ( $cards as $card ) : ?>
				<div class="relative overflow-hidden rounded-3xl h-56 md:h-80 group box-glow-gold-hover <?php echo $card['is_gif'] ? 'gpine-gif-card' : ''; ?>">
					<?php if ( $card['url'] ) : ?>
						<img
							src="<?php echo esc_url( $card['poster_url'] ); ?>"
							<?php if ( $card['is_gif'] ) : ?>
								data-gif-src="<?php echo esc_url( $card['url'] ); ?>"
							<?php endif; ?>
							alt="<?php echo esc_attr( $card['alt'] ); ?>"
							loading="lazy"
							decoding="async"
							class="object-cover transition-transform duration-700 group-hover:scale-105"
							style="position: absolute; height: 100%; width: 100%; inset: 0px;"
						>
					<?php else : ?>
						<div class="absolute inset-0 bg-background"></div>
					<?php endif; ?>
					<div class="absolute inset-0 rounded-3xl ring-0 group-hover:ring-2 ring-gold/40 transition-all duration-500 pointer-events-none"></div>
					<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
					<?php if ( $card['title'] ) : ?>
						<div class="absolute bottom-5 left-5">
							<h3 class="font-black uppercase text-white text-xl md:text-2xl leading-none tracking-tight">
								<?php echo esc_html( $card['title'] ); ?>
							</h3>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Social CTA Card -->
		<div class="rounded-3xl border border-border bg-background p-8 md:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
			<?php if ( $social_heading ) : ?>
				<p class="font-black uppercase text-foreground text-2xl md:text-4xl leading-tight text-balance max-w-xl tracking-tight">
					<?php echo esc_html( $social_heading ); ?>
				</p>
			<?php endif; ?>

			<div class="flex flex-wrap items-center gap-3 shrink-0">
				<?php if ( $instagram_url && $instagram_text ) : ?>
					<a
						href="<?php echo esc_url( $instagram_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="group inline-flex items-center gap-3 rounded-full bg-gold pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors"
					>
						<?php echo esc_html( $instagram_text ); ?>
						<span class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line></svg>
						</span>
					</a>
				<?php endif; ?>

				<?php if ( $facebook_url && $facebook_text ) : ?>
					<a
						href="<?php echo esc_url( $facebook_url ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						class="group inline-flex items-center gap-3 rounded-full border border-border bg-card pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-foreground hover:border-gold hover:text-gold transition-colors"
					>
						<?php echo esc_html( $facebook_text ); ?>
						<span class="flex h-10 w-10 items-center justify-center rounded-full bg-foreground/5 transition-transform group-hover:translate-x-1" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
						</span>
					</a>
				<?php endif; ?>
			</div>
		</div>

	</div>

</section>
