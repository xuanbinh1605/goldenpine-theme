<?php
/**
 * Template Part — Single Event Related Events Section
 *
 * "More Nights Ahead" section displaying random related events.
 * Queries for published events excluding the current event.
 *
 * For each related event, displays:
 *  - Featured image
 *  - Event date badge
 *  - Title
 *  - Event type taxonomy
 *  - Date and time
 *  - Permalink
 *
 * Hidden if no related events are found.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Query configuration.
$related_events_limit = 3;

$related_query = new WP_Query(
	[
		'post_type'      => 'event',
		'post_status'    => 'publish',
		'posts_per_page' => $related_events_limit,
		'orderby'        => 'rand',
		'post__not_in'   => [ get_the_ID() ],
	]
);

if ( ! $related_query->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>

<section class="py-20 md:py-28 px-6 lg:px-12 bg-card">
	<div class="max-w-7xl mx-auto">

		<!-- Section Header -->
		<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 md:mb-14">
			<h2 class="font-black uppercase text-foreground leading-[0.92] tracking-tight text-balance" style="font-size: clamp(2.2rem, 5vw, 5rem);">
				<?php esc_html_e( 'More nights ahead.', 'goldenpine-theme' ); ?>
			</h2>
			<a class="group inline-flex items-center gap-3 rounded-full border border-border bg-background pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-foreground hover:border-gold hover:text-gold transition-colors self-start md:self-auto" href="<?php echo esc_url( home_url( '/events' ) ); ?>">
				<?php esc_html_e( 'All Events', 'goldenpine-theme' ); ?>
				<span class="flex h-10 w-10 items-center justify-center rounded-full bg-foreground/5 transition-transform group-hover:translate-x-1" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
				</span>
			</a>
		</div>

		<!-- Related Events Grid -->
		<div class="grid grid-cols-1 md:grid-cols-3 gap-5">

			<?php
			while ( $related_query->have_posts() ) :
				$related_query->the_post();

				// Fetch related event metadata.
				$related_event_date       = get_post_meta( get_the_ID(), '_gpine_event_date', true );
				$related_event_start_time = get_post_meta( get_the_ID(), '_gpine_event_start_time', true );
				$related_event_end_time   = get_post_meta( get_the_ID(), '_gpine_event_end_time', true );

				// Parse date for badge display.
				$related_day_num    = '';
				$related_month_abbr = '';
				if ( $related_event_date ) {
					$timestamp = strtotime( $related_event_date );
					if ( $timestamp ) {
						$related_day_num    = date_i18n( 'j', $timestamp );
						$related_month_abbr = strtoupper( date_i18n( 'M', $timestamp ) );
					}
				}

				// Build date/time display string.
				$related_date_time_display = '';
				if ( $related_event_date || $related_event_start_time || $related_event_end_time ) {
					$date_parts = [];

					if ( $related_event_date ) {
						$timestamp = strtotime( $related_event_date );
						if ( $timestamp ) {
							$day_abbr = strtoupper( date_i18n( 'D', $timestamp ) );
							$date_parts[] = $day_abbr;
						}
					}

					if ( $related_event_start_time || $related_event_end_time ) {
						$time_str = '';
						if ( $related_event_start_time ) {
							$start_timestamp = strtotime( $related_event_start_time );
							$time_str = strtoupper( date_i18n( 'g A', $start_timestamp ) );
						}
						if ( $related_event_end_time ) {
							$end_timestamp = strtotime( $related_event_end_time );
							$time_str .= ' — ' . strtoupper( date_i18n( 'g A', $end_timestamp ) );
						}
						$date_parts[] = $time_str;
					}

					$related_date_time_display = implode( ' · ', $date_parts );
				}
				?>

				<a class="group block overflow-hidden rounded-3xl border border-border bg-background hover:border-gold/50 transition-colors box-glow-gold-hover" href="<?php the_permalink(); ?>">

					<!-- Event Image with Date Badge -->
					<div class="relative h-52">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php
							the_post_thumbnail(
								'medium_large',
								[
									'class'   => 'object-cover transition-transform duration-700 group-hover:scale-105',
									'style'   => 'position: absolute; height: 100%; width: 100%; inset: 0px;',
									'loading' => 'lazy',
									'decoding' => 'async',
								]
							);
							?>
						<?php endif; ?>

						<div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
						<div class="absolute inset-0 rounded-none ring-0 group-hover:ring-2 ring-inset ring-gold/30 transition-all duration-500 pointer-events-none"></div>

						<!-- Date Badge -->
						<?php if ( $related_day_num && $related_month_abbr ) : ?>
							<div class="absolute top-4 left-4 rounded-2xl bg-black/70 backdrop-blur px-3 py-1.5 flex items-end gap-2">
								<span class="font-black text-gold leading-none text-2xl">
									<?php echo esc_html( $related_day_num ); ?>
								</span>
								<span class="text-[10px] font-bold uppercase tracking-widest text-white/85 pb-0.5">
									<?php echo esc_html( $related_month_abbr ); ?>
								</span>
							</div>
						<?php endif; ?>
					</div>

					<!-- Event Info -->
					<div class="p-6 flex items-center justify-between gap-4">
						<div>
							<?php if ( get_the_title() ) : ?>
								<h3 class="font-black uppercase text-foreground text-xl leading-tight tracking-tight group-hover:text-gold transition-colors">
									<?php the_title(); ?>
								</h3>
							<?php endif; ?>

							<?php if ( $related_date_time_display ) : ?>
								<p class="text-xs font-bold uppercase tracking-widest text-foreground/55 mt-2">
									<?php echo esc_html( $related_date_time_display ); ?>
								</p>
							<?php endif; ?>
						</div>

						<span class="shrink-0 flex h-10 w-10 items-center justify-center rounded-full border border-border group-hover:bg-gold group-hover:border-gold group-hover:text-black text-foreground transition-colors" aria-hidden="true">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
						</span>
					</div>

				</a>

			<?php endwhile; ?>

		</div><!-- /grid -->

	</div>
</section>

<?php
wp_reset_postdata();
?>
