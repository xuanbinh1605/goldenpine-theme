<?php
/**
 * Template Part — Contact Page Info Section
 *
 * Displays:
 *  - "Ready when you are" booking CTA card
 *  - Left column: Location card, Reach Us card (phone/zalo/email + social icons), Open Hours card
 *  - Right column: Google Maps embed with overlay badge
 *
 * Content managed via Appearance > Customize > Contact Page > Contact Info.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// CTA card.
$cta_heading  = get_theme_mod( 'goldenpine_contact_cta_heading', 'Ready when you are.' );
$cta_book     = get_theme_mod( 'goldenpine_contact_cta_book_text', 'Book A Table' );
$cta_book_url = get_theme_mod( 'goldenpine_contact_cta_book_link', home_url( '/booking' ) );
$cta_call     = get_theme_mod( 'goldenpine_contact_cta_call_text', 'Call Now' );

// Location card.
$venue_name     = get_theme_mod( 'goldenpine_contact_venue_name', 'Golden Pine Pub' );
$venue_address  = get_theme_mod( 'goldenpine_contact_venue_address', "296 Lê Duẩn, Hải Châu 1\nHải Châu, Đà Nẵng, Vietnam" );
$directions_url = get_theme_mod( 'goldenpine_contact_directions_url', 'https://maps.google.com/?q=296+Le+Duan+Da+Nang' );
$directions_txt = get_theme_mod( 'goldenpine_contact_directions_text', 'Get Directions' );

// Reach Us — phone shared from footer settings; zalo and email are contact-specific.
$phone     = get_theme_mod( 'goldenpine_footer_phone', '' );
$zalo      = get_theme_mod( 'goldenpine_contact_zalo', '' );
$email     = get_theme_mod( 'goldenpine_footer_email', '' );
$phone_raw = preg_replace( '/[^+\d]/', '', $phone );

// Social links from footer settings.
$instagram = get_theme_mod( 'goldenpine_social_instagram', '' );
$facebook  = get_theme_mod( 'goldenpine_social_facebook', '' );
$tiktok    = get_theme_mod( 'goldenpine_social_tiktok', '' );

// Open Hours — single row.
$hours_label = get_theme_mod( 'goldenpine_contact_hours_label', 'Mon – Sun' );
$hours_time  = get_theme_mod( 'goldenpine_contact_hours_time',  '9 PM – 2 AM' );
$has_hours   = $hours_label || $hours_time;

// Map — embed URL is built from a plain address string.
$map_query   = get_theme_mod( 'goldenpine_contact_map_query', '296 Le Duan, Hai Chau, Da Nang, Vietnam' );
$map_embed   = $map_query
	? 'https://maps.google.com/maps?q=' . rawurlencode( $map_query ) . '&output=embed'
	: '';
$map_name    = get_theme_mod( 'goldenpine_contact_map_name', 'Golden Pine Pub' );
$map_address = get_theme_mod( 'goldenpine_contact_map_address', '296 Lê Duẩn · Hải Châu · Đà Nẵng' );
?>

<section class="relative py-24 md:py-32 px-6 lg:px-12 overflow-hidden">

	<!-- Decorative glow -->
	<div
		aria-hidden="true"
		class="absolute -right-40 top-0 w-[500px] h-[500px] pointer-events-none"
		style="background: radial-gradient(circle, rgba(226, 190, 61, 0.08) 0%, transparent 70%); filter: blur(80px);"
	></div>

	<div class="relative max-w-7xl mx-auto">

		<!-- CTA Card -->
		<?php if ( $cta_heading ) : ?>
			<div class="rounded-3xl border border-border bg-card p-8 md:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10 md:mb-12">
				<p
					class="font-black uppercase text-foreground leading-tight text-balance tracking-tight max-w-2xl"
					style="font-size: clamp(1.8rem, 4vw, 3.5rem);"
				>
					<?php echo esc_html( $cta_heading ); ?>
				</p>
				<div class="flex flex-wrap items-center gap-3 shrink-0">
					<?php if ( $cta_book && $cta_book_url ) : ?>
						<a
							href="<?php echo esc_url( $cta_book_url ); ?>"
							class="group inline-flex items-center gap-3 rounded-full bg-gold pl-7 pr-3 py-3 text-sm md:text-base font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors box-glow-gold"
						>
							<?php echo esc_html( $cta_book ); ?>
							<span class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
							</span>
						</a>
					<?php endif; ?>
					<?php if ( $cta_call && $phone ) : ?>
						<a
							href="tel:<?php echo esc_attr( $phone_raw ); ?>"
							class="group inline-flex items-center gap-3 rounded-full border border-border bg-background pl-6 pr-3 py-3 text-sm md:text-base font-bold uppercase tracking-wider text-foreground hover:border-gold hover:text-gold transition-colors"
						>
							<?php echo esc_html( $cta_call ); ?>
							<span class="flex h-10 w-10 items-center justify-center rounded-full bg-foreground/5 transition-transform group-hover:-rotate-12" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
							</span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<!-- Two-column layout -->
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">

			<!-- Left: Info Cards -->
			<div class="lg:col-span-5 flex flex-col gap-6">

				<!-- Location Card -->
				<?php if ( $venue_name || $venue_address ) : ?>
					<div class="rounded-3xl border border-border bg-card p-8 flex flex-col gap-4">
						<div class="flex items-center gap-3">
							<div class="w-10 h-10 rounded-full bg-gold/10 flex items-center justify-center">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
							</div>
							<p class="text-xs font-bold tracking-[0.4em] uppercase text-gold">
								<?php esc_html_e( 'Location', 'goldenpine-theme' ); ?>
							</p>
						</div>
						<div>
							<?php if ( $venue_name ) : ?>
								<p class="font-black uppercase text-foreground text-2xl md:text-3xl leading-tight tracking-tight">
									<?php echo esc_html( $venue_name ); ?>
								</p>
							<?php endif; ?>
							<?php if ( $venue_address ) : ?>
								<p class="text-base font-light text-foreground/70 mt-2 leading-relaxed">
									<?php echo nl2br( esc_html( $venue_address ) ); ?>
								</p>
							<?php endif; ?>
						</div>
						<?php if ( $directions_url && $directions_txt ) : ?>
							<a
								href="<?php echo esc_url( $directions_url ); ?>"
								target="_blank"
								rel="noopener noreferrer"
								class="group inline-flex items-center gap-3 self-start rounded-full bg-foreground pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-background hover:bg-gold hover:text-black transition-colors mt-2"
							>
								<?php echo esc_html( $directions_txt ); ?>
								<span class="flex h-10 w-10 items-center justify-center rounded-full bg-background text-foreground transition-transform group-hover:translate-x-1 group-hover:bg-black group-hover:text-gold" aria-hidden="true">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
								</span>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<!-- Reach Us Card -->
				<?php if ( $phone || $zalo || $email ) : ?>
					<div class="rounded-3xl border border-border bg-card p-8 flex flex-col gap-5">
						<div class="flex items-center gap-3">
							<div class="w-10 h-10 rounded-full bg-gold/10 flex items-center justify-center">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
							</div>
							<p class="text-xs font-bold tracking-[0.4em] uppercase text-gold">
								<?php esc_html_e( 'Reach Us', 'goldenpine-theme' ); ?>
							</p>
						</div>

						<div class="flex flex-col divide-y divide-border">

							<!-- Phone -->
							<?php if ( $phone ) : ?>
								<a
									href="tel:<?php echo esc_attr( $phone_raw ); ?>"
									class="flex items-center justify-between gap-4 py-4 first:pt-0 group"
								>
									<div class="flex items-center gap-4">
										<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-foreground/55 group-hover:text-gold transition-colors shrink-0" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
										<div>
											<p class="text-[11px] font-bold uppercase tracking-widest text-foreground/55">
												<?php esc_html_e( 'Hotline', 'goldenpine-theme' ); ?>
											</p>
											<p class="text-base md:text-lg font-semibold text-foreground group-hover:text-gold transition-colors">
												<?php echo esc_html( $phone ); ?>
											</p>
										</div>
									</div>
									<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-foreground/35 group-hover:text-gold group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all shrink-0" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
								</a>
							<?php endif; ?>

							<!-- Zalo -->
							<?php if ( $zalo ) : ?>
								<a
									href="<?php echo esc_url( 'https://zalo.me/' . preg_replace( '/[^+\d]/', '', $zalo ) ); ?>"
									target="_blank"
									rel="noopener noreferrer"
									class="flex items-center justify-between gap-4 py-4 group"
								>
									<div class="flex items-center gap-4">
										<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-foreground/55 group-hover:text-gold transition-colors shrink-0" aria-hidden="true"><path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"></path></svg>
										<div>
											<p class="text-[11px] font-bold uppercase tracking-widest text-foreground/55">
												<?php esc_html_e( 'Zalo', 'goldenpine-theme' ); ?>
											</p>
											<p class="text-base md:text-lg font-semibold text-foreground group-hover:text-gold transition-colors">
												<?php echo esc_html( $zalo ); ?>
											</p>
										</div>
									</div>
									<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-foreground/35 group-hover:text-gold group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all shrink-0" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
								</a>
							<?php endif; ?>

							<!-- Email -->
							<?php if ( $email ) : ?>
								<a
									href="mailto:<?php echo esc_attr( $email ); ?>"
									class="flex items-center justify-between gap-4 py-4 last:pb-0 group"
								>
									<div class="flex items-center gap-4">
										<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-foreground/55 group-hover:text-gold transition-colors shrink-0" aria-hidden="true"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path><rect x="2" y="4" width="20" height="16" rx="2"></rect></svg>
										<div>
											<p class="text-[11px] font-bold uppercase tracking-widest text-foreground/55">
												<?php esc_html_e( 'Email', 'goldenpine-theme' ); ?>
											</p>
											<p class="text-base md:text-lg font-semibold text-foreground group-hover:text-gold transition-colors">
												<?php echo esc_html( $email ); ?>
											</p>
										</div>
									</div>
									<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-foreground/35 group-hover:text-gold group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all shrink-0" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
								</a>
							<?php endif; ?>

						</div>

						<!-- Social Icons -->
						<?php if ( $instagram || $facebook || $tiktok ) : ?>
							<div class="flex items-center gap-3 pt-2 border-t border-border">
								<?php if ( $instagram ) : ?>
									<a
										href="<?php echo esc_url( $instagram ); ?>"
										target="_blank"
										rel="noopener noreferrer"
										aria-label="<?php esc_attr_e( 'Instagram', 'goldenpine-theme' ); ?>"
										class="p-3 rounded-full border border-border hover:border-gold hover:bg-gold hover:text-black text-foreground/65 transition-colors"
									>
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line></svg>
									</a>
								<?php endif; ?>
								<?php if ( $facebook ) : ?>
									<a
										href="<?php echo esc_url( $facebook ); ?>"
										target="_blank"
										rel="noopener noreferrer"
										aria-label="<?php esc_attr_e( 'Facebook', 'goldenpine-theme' ); ?>"
										class="p-3 rounded-full border border-border hover:border-gold hover:bg-gold hover:text-black text-foreground/65 transition-colors"
									>
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
									</a>
								<?php endif; ?>
								<?php if ( $tiktok ) : ?>
									<a
										href="<?php echo esc_url( $tiktok ); ?>"
										target="_blank"
										rel="noopener noreferrer"
										aria-label="<?php esc_attr_e( 'TikTok', 'goldenpine-theme' ); ?>"
										class="p-3 rounded-full border border-border hover:border-gold hover:bg-gold hover:text-black text-foreground/65 transition-colors"
									>
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="8" cy="18" r="4"></circle><path d="M12 18V2l7 4"></path></svg>
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>

					</div>
				<?php endif; ?>

				<!-- Open Hours Card -->
				<?php if ( $has_hours ) : ?>
					<div class="rounded-3xl border border-border bg-card p-8 flex flex-col gap-5">
						<div class="flex items-center gap-3">
							<div class="w-10 h-10 rounded-full bg-gold/10 flex items-center justify-center">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold" aria-hidden="true"><path d="M12 6v6l4 2"></path><circle cx="12" cy="12" r="10"></circle></svg>
							</div>
							<p class="text-xs font-bold tracking-[0.4em] uppercase text-gold">
								<?php esc_html_e( 'Open Hours', 'goldenpine-theme' ); ?>
							</p>
						</div>
						<div class="flex items-center justify-between gap-4">
							<span class="text-base font-medium text-foreground/70">
								<?php echo esc_html( $hours_label ); ?>
							</span>
							<span class="text-base font-bold text-gold">
								<?php echo esc_html( $hours_time ); ?>
							</span>
						</div>
					</div>
				<?php endif; ?>

			</div><!-- /left -->

			<!-- Right: Map -->
			<?php if ( $map_embed ) : ?>
				<div class="lg:col-span-7">
					<div class="rounded-3xl border border-border overflow-hidden h-[500px] md:h-full min-h-[500px] relative bg-card">
						<iframe
							src="<?php echo esc_url( $map_embed ); ?>"
							width="100%"
							height="100%"
							allowfullscreen=""
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"
							title="<?php echo esc_attr( $venue_name ); ?>"
							class="h-full w-full"
							style="border: 0px;"
						></iframe>
						<?php if ( $map_name || $map_address ) : ?>
							<div class="absolute top-5 left-5 rounded-2xl bg-background/92 backdrop-blur border border-gold px-5 py-3 shadow-lg">
								<?php if ( $map_name ) : ?>
									<p class="text-[10px] tracking-widest uppercase text-gold font-black">
										<?php echo esc_html( $map_name ); ?>
									</p>
								<?php endif; ?>
								<?php if ( $map_address ) : ?>
									<p class="text-xs text-foreground mt-0.5">
										<?php echo esc_html( $map_address ); ?>
									</p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

		</div><!-- /grid -->

	</div>

</section>
