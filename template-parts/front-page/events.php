<?php
/**
 * Template Part — Front Page Events Section
 *
 * Displays the latest 3 upcoming events:
 *  - First event as a large featured card with "Next Up" badge
 *  - Next 2 events as smaller cards in a 2-column grid
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Retrieve customizer settings.
$section_label   = get_theme_mod( 'goldenpine_front_events_label', 'Tonight &amp; What\'s Next' );
$heading_line_1  = get_theme_mod( 'goldenpine_front_events_heading_1', 'Tonight is' );
$heading_line_2  = get_theme_mod( 'goldenpine_front_events_heading_2', 'calling.' );
$cta_button_text = get_theme_mod( 'goldenpine_front_events_cta_text', 'All Events' );

// Query the 3 most recently created events (by post date, newest first).
$events_query = new WP_Query(
	[
		'post_type'      => 'event',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
	]
);

if ( ! $events_query->have_posts() ) {
	return;
}

$events = $events_query->posts;
$featured_event = $events[0] ?? null;
$smaller_events = array_slice( $events, 1 );
?>

<section id="events" class="relative py-24 md:py-32 px-6 lg:px-12 bg-card overflow-hidden">

	<!-- Decorative gradient glows -->
	<div
		aria-hidden="true"
		class="absolute -left-40 bottom-0 w-[800px] h-[700px] pointer-events-none"
		style="background:radial-gradient(circle, rgba(226,190,61,0.26) 0%, transparent 70%);filter:blur(60px)"
	></div>
	<div
		aria-hidden="true"
		class="absolute right-0 top-1/3 w-[500px] h-[500px] pointer-events-none"
		style="background:radial-gradient(circle, rgba(226,190,61,0.16) 0%, transparent 70%);filter:blur(70px)"
	></div>
	<div
		aria-hidden="true"
		class="absolute left-1/2 top-0 w-[600px] h-[200px] pointer-events-none"
		style="background:radial-gradient(ellipse, rgba(226,190,61,0.10) 0%, transparent 70%);filter:blur(80px)"
	></div>

	<div class="relative max-w-7xl mx-auto">

		<!-- Label -->
		<p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-8 flex items-center gap-3">
			<span class="h-px w-8 bg-gold inline-block"></span>
			<?php echo esc_html( $section_label ); ?>
		</p>

		<!-- Heading + CTA -->
		<div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-10 items-end mb-14 md:mb-16">
			<h2
				class="md:col-span-8 font-black uppercase text-foreground leading-[0.92] tracking-tight text-balance"
				style="font-size:clamp(2.8rem, 7vw, 7rem)"
			>
				<?php echo esc_html( $heading_line_1 ); ?><br>
				<span class="text-gold"><?php echo esc_html( $heading_line_2 ); ?></span>
			</h2>
			<div class="md:col-span-4 flex md:justify-end">
				<a
					class="group inline-flex items-center gap-3 rounded-full border border-border bg-background pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-foreground hover:border-gold hover:text-gold transition-colors"
					href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>"
				>
					<?php echo esc_html( $cta_button_text ); ?>
					<span class="flex h-10 w-10 items-center justify-center rounded-full bg-foreground/5 transition-transform group-hover:translate-x-1">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right" aria-hidden="true">
							<path d="M7 7h10v10"></path>
							<path d="M7 17 17 7"></path>
						</svg>
					</span>
				</a>
			</div>
		</div>

		<?php if ( $featured_event ) : ?>
			<?php
			$feat_date       = get_post_meta( $featured_event->ID, '_gpine_event_date', true );
			$feat_start_time = get_post_meta( $featured_event->ID, '_gpine_event_start_time', true );
			$feat_end_time   = get_post_meta( $featured_event->ID, '_gpine_event_end_time', true );
			$feat_subtitle   = get_post_meta( $featured_event->ID, '_gpine_event_subtitle', true );

			$feat_date_obj   = $feat_date ? date_create( $feat_date ) : null;
			$feat_day        = $feat_date_obj ? $feat_date_obj->format( 'd' ) : '';
			$feat_month      = $feat_date_obj ? $feat_date_obj->format( 'M' ) : '';
			$feat_dow        = $feat_date_obj ? $feat_date_obj->format( 'D' ) : '';

			// Format time range (convert 24h to 12h AM/PM).
			$feat_time_display = '';
			if ( $feat_start_time && $feat_end_time ) {
				$start_12h = gmdate( 'g A', strtotime( $feat_start_time ) );
				$end_12h   = gmdate( 'g A', strtotime( $feat_end_time ) );
				$feat_time_display = $start_12h . ' — ' . $end_12h;
			}

			$feat_image_url = get_the_post_thumbnail_url( $featured_event->ID, 'large' );
			?>

		<!-- Featured Event Card (Large) -->
		<div 
			class="rounded-3xl overflow-hidden border border-gold/40 mb-6 box-glow-gold-hover cursor-pointer gpine-clickable-card"
			data-href="<?php echo esc_url( get_permalink( $featured_event->ID ) ); ?>"
			role="link"
			tabindex="0"
		>
			<div class="grid grid-cols-1 lg:grid-cols-2">

					<!-- Image Column -->
					<div class="relative h-[320px] lg:h-full min-h-[360px]">
						<?php if ( $feat_image_url ) : ?>
							<img
								alt="<?php echo esc_attr( get_the_title( $featured_event->ID ) ); ?>"
								loading="lazy"
								decoding="async"
								class="object-cover"
								style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
								src="<?php echo esc_url( $feat_image_url ); ?>"
							>
						<?php endif; ?>
						<span class="absolute top-5 left-5 rounded-full bg-gold px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-black">
							Next Up
						</span>
					</div>

					<!-- Content Column -->
					<div class="bg-background p-8 md:p-12 flex flex-col justify-between gap-8">

						<!-- Date Block -->
						<div class="flex items-end gap-5">
							<div
								class="font-black text-gold leading-[0.85]"
								style="font-size:clamp(5rem, 10vw, 9rem)"
							>
								<?php echo esc_html( ltrim( $feat_day, '0' ) ); ?>
							</div>
							<div class="pb-3 flex flex-col gap-1">
								<span class="font-black text-foreground text-3xl md:text-4xl leading-none uppercase tracking-tight">
									<?php echo esc_html( strtoupper( $feat_month ) ); ?>
								</span>
								<?php if ( $feat_dow && $feat_time_display ) : ?>
									<span class="text-sm uppercase tracking-[0.3em] text-foreground/55 font-medium">
										<?php echo esc_html( strtoupper( $feat_dow ) ); ?>
										&nbsp;·&nbsp;
										<?php echo esc_html( $feat_time_display ); ?>
									</span>
								<?php endif; ?>
							</div>
						</div>

						<!-- Title + Subtitle -->
						<div>
							<h3
								class="font-black uppercase text-foreground leading-tight text-balance mb-3 tracking-tight"
								style="font-size:clamp(1.8rem, 3.5vw, 3rem)"
							>
								<?php echo esc_html( get_the_title( $featured_event->ID ) ); ?>
							</h3>
							<?php if ( $feat_subtitle ) : ?>
								<p class="text-base md:text-lg text-foreground/70 leading-snug max-w-lg font-light">
									<?php echo esc_html( $feat_subtitle ); ?>
								</p>
							<?php endif; ?>
						</div>

						<!-- CTAs -->
						<div class="flex flex-wrap items-center gap-3">
							<a
								class="group inline-flex items-center gap-3 rounded-full bg-gold pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors box-glow-gold"
								href="<?php echo esc_url( home_url( '/booking' ) ); ?>"
							>
								Reserve Now
								<span class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right" aria-hidden="true">
										<path d="M7 7h10v10"></path>
										<path d="M7 17 17 7"></path>
									</svg>
								</span>
							</a>
							<a
								class="text-sm font-bold uppercase tracking-wider text-foreground/75 hover:text-gold transition-colors px-3 py-3"
								href="<?php echo esc_url( get_permalink( $featured_event->ID ) ); ?>"
							>
								Details
							</a>
						</div>

					</div><!-- .content -->
				</div><!-- .grid -->
			</div><!-- .featured-card -->
		<?php endif; ?>

		<?php if ( ! empty( $smaller_events ) ) : ?>
			<!-- Smaller Event Cards Grid -->
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				<?php foreach ( $smaller_events as $event ) : ?>
					<?php
					$evt_date       = get_post_meta( $event->ID, '_gpine_event_date', true );
					$evt_start_time = get_post_meta( $event->ID, '_gpine_event_start_time', true );
					$evt_end_time   = get_post_meta( $event->ID, '_gpine_event_end_time', true );

					$evt_date_obj   = $evt_date ? date_create( $evt_date ) : null;
					$evt_day        = $evt_date_obj ? $evt_date_obj->format( 'd' ) : '';
					$evt_month      = $evt_date_obj ? $evt_date_obj->format( 'M' ) : '';
					$evt_dow        = $evt_date_obj ? $evt_date_obj->format( 'D' ) : '';

					$evt_time_display = '';
					if ( $evt_start_time && $evt_end_time ) {
						$start_12h = gmdate( 'g A', strtotime( $evt_start_time ) );
						$end_12h   = gmdate( 'g A', strtotime( $evt_end_time ) );
						$evt_time_display = $start_12h . ' — ' . $end_12h;
					}

					$evt_image_url = get_the_post_thumbnail_url( $event->ID, 'medium_large' );
					?>

					<a
						class="group relative overflow-hidden rounded-3xl border border-border hover:border-gold-warm bg-background transition-colors box-glow-gold-hover"
						href="<?php echo esc_url( get_permalink( $event->ID ) ); ?>"
					>
						<!-- Image -->
						<div class="relative h-56">
							<?php if ( $evt_image_url ) : ?>
								<img
									alt="<?php echo esc_attr( get_the_title( $event->ID ) ); ?>"
									loading="lazy"
									decoding="async"
									class="object-cover transition-transform duration-700 group-hover:scale-105"
									style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
									src="<?php echo esc_url( $evt_image_url ); ?>"
								>
							<?php endif; ?>
							<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

							<!-- Date Badge -->
							<div class="absolute top-5 left-5 rounded-2xl bg-black/70 backdrop-blur px-4 py-2 flex items-end gap-2">
								<span class="font-black text-gold leading-none text-3xl">
									<?php echo esc_html( ltrim( $evt_day, '0' ) ); ?>
								</span>
								<span class="text-xs uppercase tracking-widest text-white/85 pb-0.5 font-bold">
									<?php echo esc_html( strtoupper( $evt_month ) ); ?>
								</span>
							</div>
						</div>

						<!-- Content -->
						<div class="p-6 flex items-center justify-between gap-4">
							<div>
								<h3 class="font-black uppercase text-foreground text-2xl leading-tight tracking-tight group-hover:text-gold transition-colors">
									<?php echo esc_html( get_the_title( $event->ID ) ); ?>
								</h3>
								<?php if ( $evt_dow && $evt_time_display ) : ?>
									<p class="text-sm text-foreground/55 mt-1 font-medium tracking-wide uppercase">
										<?php echo esc_html( strtoupper( $evt_dow ) ); ?>
										&nbsp;·&nbsp;
										<?php echo esc_html( $evt_time_display ); ?>
									</p>
								<?php endif; ?>
							</div>
							<span class="shrink-0 flex h-11 w-11 items-center justify-center rounded-full border border-border group-hover:bg-gold group-hover:border-gold group-hover:text-black text-foreground transition-colors">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right" aria-hidden="true">
									<path d="M7 7h10v10"></path>
									<path d="M7 17 17 7"></path>
								</svg>
							</span>
						</div>
					</a>

				<?php endforeach; ?>
			</div><!-- .grid -->
		<?php endif; ?>

	</div><!-- .container -->
</section>

<?php
wp_reset_postdata();
