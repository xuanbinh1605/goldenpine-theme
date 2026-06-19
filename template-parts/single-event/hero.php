<?php
/**
 * Template Part — Single Event Hero Section
 *
 * Outputs the hero banner for a single event with:
 *  - Featured image background (if set)
 *  - Event Type taxonomy badges
 *  - Event title
 *  - Event subtitle (if set)
 *  - Event date and time (if set)
 *
 * All sections are conditional: if no data exists, that element is not rendered.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Fetch event metadata.
$event_subtitle   = get_post_meta( get_the_ID(), '_gpine_event_subtitle', true );
$event_date       = get_post_meta( get_the_ID(), '_gpine_event_date', true );
$event_start_time = get_post_meta( get_the_ID(), '_gpine_event_start_time', true );
$event_end_time   = get_post_meta( get_the_ID(), '_gpine_event_end_time', true );

// Get Event Type taxonomy terms.
$event_types = get_the_terms( get_the_ID(), 'event_type' );

// Build date/time display string.
$date_time_display = '';
if ( $event_date || $event_start_time || $event_end_time ) {
	$date_parts = [];

	if ( $event_date ) {
		$timestamp = strtotime( $event_date );
		if ( $timestamp ) {
			// Format: "SAT, APR 19"
			$day_abbr   = strtoupper( date_i18n( 'D', $timestamp ) );
			$month_abbr = strtoupper( date_i18n( 'M', $timestamp ) );
			$day_num    = date_i18n( 'j', $timestamp );
			$date_parts[] = $day_abbr . ', ' . $month_abbr . ' ' . $day_num;
		}
	}

	if ( $event_start_time || $event_end_time ) {
		$time_str = '';
		if ( $event_start_time ) {
			// Convert 24-hour to 12-hour format: "21:00" → "9 PM"
			$start_timestamp = strtotime( $event_start_time );
			$time_str = strtoupper( date_i18n( 'g A', $start_timestamp ) );
		}
		if ( $event_end_time ) {
			$end_timestamp = strtotime( $event_end_time );
			$time_str .= ' — ' . strtoupper( date_i18n( 'g A', $end_timestamp ) );
		}
		$date_parts[] = $time_str;
	}

	$date_time_display = implode( ' · ', $date_parts );
}
?>

<section class="relative min-h-[85vh] flex flex-col justify-end overflow-hidden">

	<!-- Background Image -->
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="absolute inset-0 z-0">
			<?php
			the_post_thumbnail(
				'full',
				[
					'class'   => 'object-cover object-center',
					'style'   => 'position: absolute; height: 100%; width: 100%; inset: 0px;',
					'loading' => 'eager',
					'decoding' => 'async',
				]
			);
			?>
			<div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/55 to-[#1e1e1e]"></div>
			<div aria-hidden="true" class="absolute inset-0 pointer-events-none opacity-[0.04] mix-blend-overlay" style="background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E&quot;); background-size: 200px 200px;"></div>
		</div>
	<?php endif; ?>

	<!-- Back to Events Link -->
	<div class="relative z-10 px-6 lg:px-12 pt-28 md:pt-32">
		<a class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-white/65 hover:text-gold transition-colors" href="<?php echo esc_url( home_url( '/events' ) ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
			<?php esc_html_e( 'All Events', 'goldenpine-theme' ); ?>
		</a>
	</div>

	<!-- Hero Content -->
	<div class="relative z-10 px-6 lg:px-12 pb-16 md:pb-24 mt-auto">

		<!-- Badges: Event Type + Date/Time -->
		<?php if ( ! empty( $event_types ) || $date_time_display ) : ?>
			<div class="flex flex-wrap items-center gap-3 mb-8">

				<?php if ( ! empty( $event_types ) && ! is_wp_error( $event_types ) ) : ?>
					<?php foreach ( $event_types as $term ) : ?>
						<span class="rounded-full bg-gold px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-black">
							<?php echo esc_html( $term->name ); ?>
						</span>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php if ( $date_time_display ) : ?>
					<span class="rounded-full border border-white/20 bg-white/5 backdrop-blur px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-white/85">
						<?php echo esc_html( $date_time_display ); ?>
					</span>
				<?php endif; ?>

			</div>
		<?php endif; ?>

		<!-- Event Title -->
		<?php if ( get_the_title() ) : ?>
			<h1 class="font-black uppercase text-white leading-[0.9] tracking-tight text-balance mb-4" style="font-size: clamp(3rem, 10vw, 9rem);">
				<?php the_title(); ?>
			</h1>
		<?php endif; ?>

		<!-- Event Subtitle -->
		<?php if ( $event_subtitle ) : ?>
			<p class="text-xl md:text-2xl font-light text-gold max-w-2xl">
				<?php echo esc_html( $event_subtitle ); ?>
			</p>
		<?php endif; ?>

	</div>

</section>
