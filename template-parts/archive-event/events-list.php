<?php
/**
 * Template Part — Events Archive Events List
 *
 * Displays all published events as a vertical card list. Each card shows:
 *  - Date column: day number, month abbreviation, day of week
 *  - Image column: featured image with event type badge
 *  - Content column: title, subtitle, time, Reserve and Details links
 *
 * A row of filter buttons (All + each event_type term) is rendered above
 * the list. Filtering is handled client-side via a small inline script.
 *
 * Section heading managed via Appearance > Customize > Events Archive > Events List.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_gpine_ev_list_heading = get_theme_mod( 'goldenpine_events_list_heading', 'Every night, another story.' );
$_gpine_ev_book_link    = get_theme_mod( 'goldenpine_events_cta_book_link', '/booking' );

// -----------------------------------------------------------------------
// Fetch all published events, ordered by event date descending.
// -----------------------------------------------------------------------
$events_query = new WP_Query(
	[
		'post_type'      => 'event',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'meta_key'       => '_gpine_event_date',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
	]
);

// Also fetch events that have no date set, appended after dated ones.
$events_no_date = new WP_Query(
	[
		'post_type'      => 'event',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'meta_query'     => [
			[
				'key'     => '_gpine_event_date',
				'compare' => 'NOT EXISTS',
			],
		],
	]
);

$all_events = array_merge(
	$events_query->posts,
	$events_no_date->posts
);

$event_count = count( $all_events );

// -----------------------------------------------------------------------
// Fetch all event_type taxonomy terms for filter buttons.
// -----------------------------------------------------------------------
$event_types = get_terms(
	[
		'taxonomy'   => 'event_type',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	]
);

if ( is_wp_error( $event_types ) ) {
	$event_types = [];
}
?>

<section class="px-6 lg:px-12 pt-16 md:pt-24">
	<div class="max-w-7xl mx-auto">

		<!-- Heading + Count -->
		<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 md:mb-14">
			<h2
				class="font-black uppercase text-foreground leading-[0.92] tracking-tight text-balance"
				style="font-size: clamp(2.2rem, 5vw, 5rem);"
			>
				<?php echo esc_html( $_gpine_ev_list_heading ); ?>
			</h2>
			<p class="text-sm font-bold uppercase tracking-widest text-foreground/50 md:text-right">
				<?php
				/* translators: %d: number of events */
				echo esc_html( sprintf( _n( '%d event', '%d events', $event_count, 'goldenpine-theme' ), $event_count ) );
				?>
			</p>
		</div>

		<!-- Filter Tabs -->
		<?php if ( ! empty( $event_types ) ) : ?>
			<div class="flex flex-wrap gap-2 md:gap-3 mb-12" id="gpine-event-filters">
				<button
					class="gpine-filter-btn text-sm font-bold uppercase tracking-wider px-5 py-2.5 rounded-full border transition-colors bg-gold text-black border-gold"
					data-filter="all"
					aria-pressed="true"
				>
					<?php esc_html_e( 'All', 'goldenpine-theme' ); ?>
				</button>
				<?php foreach ( $event_types as $event_type ) : ?>
					<button
						class="gpine-filter-btn text-sm font-bold uppercase tracking-wider px-5 py-2.5 rounded-full border transition-colors border-border bg-card text-foreground/75 hover:border-gold hover:text-gold"
						data-filter="<?php echo esc_attr( $event_type->slug ); ?>"
						aria-pressed="false"
					>
						<?php echo esc_html( $event_type->name ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>

<section class="px-6 lg:px-12 pb-24 md:pb-32">
	<div class="max-w-7xl mx-auto">

		<?php if ( ! empty( $all_events ) ) : ?>
			<div class="flex flex-col gap-5" id="gpine-events-list">

				<?php foreach ( $all_events as $event ) : ?>
					<?php
					// Date parts.
					$event_date  = get_post_meta( $event->ID, '_gpine_event_date', true );
					$day_num     = '';
					$month_abbr  = '';
					$day_abbr    = '';
					if ( $event_date ) {
						$ts         = strtotime( $event_date );
						$day_num    = $ts ? date_i18n( 'j', $ts )   : '';
						$month_abbr = $ts ? strtoupper( date_i18n( 'M', $ts ) ) : '';
						$day_abbr   = $ts ? strtoupper( date_i18n( 'D', $ts ) ) : '';
					}

					// Time display.
					$start_time   = get_post_meta( $event->ID, '_gpine_event_start_time', true );
					$end_time     = get_post_meta( $event->ID, '_gpine_event_end_time', true );
					$time_display = '';
					if ( $start_time || $end_time ) {
						$parts = [];
						if ( $start_time ) {
							$parts[] = strtoupper( date_i18n( 'g A', strtotime( $start_time ) ) );
						}
						if ( $end_time ) {
							$parts[] = strtoupper( date_i18n( 'g A', strtotime( $end_time ) ) );
						}
						$time_display = implode( ' — ', $parts );
					}

					// Subtitle.
					$subtitle = get_post_meta( $event->ID, '_gpine_event_subtitle', true );

					// Featured image.
					$image_url = get_the_post_thumbnail_url( $event->ID, 'large' );
					$image_alt = get_post_meta( get_post_thumbnail_id( $event->ID ), '_wp_attachment_image_alt', true );
					if ( ! $image_alt ) {
						$image_alt = get_the_title( $event->ID );
					}

					// Event type terms.
					$terms       = get_the_terms( $event->ID, 'event_type' );
					$term_slugs  = [];
					$first_term  = null;
					if ( $terms && ! is_wp_error( $terms ) ) {
						$first_term = $terms[0];
						foreach ( $terms as $term ) {
							$term_slugs[] = $term->slug;
						}
					}

					$event_permalink = get_permalink( $event->ID );
					$data_types      = ! empty( $term_slugs ) ? implode( ' ', $term_slugs ) : '';
					?>

				<article
					class="gpine-event-card gpine-clickable-card cursor-pointer group overflow-hidden rounded-3xl border border-border bg-card hover:border-gold/50 transition-colors box-glow-gold-hover"
					data-types="<?php echo esc_attr( $data_types ); ?>"
					data-href="<?php echo esc_url( $event_permalink ); ?>"
					role="link"
					tabindex="0"
				>
						<div class="grid grid-cols-1 md:grid-cols-12 gap-0">

							<!-- Date Column -->
							<div class="md:col-span-2 bg-background p-6 md:p-8 flex md:flex-col items-center md:items-start justify-center md:justify-center gap-3 border-b md:border-b-0 md:border-r border-border">
								<?php if ( $day_num && $month_abbr ) : ?>
									<span
										class="font-black text-gold leading-none"
										style="font-size: clamp(3.5rem, 6vw, 6rem);"
									>
										<?php echo esc_html( $day_num ); ?>
									</span>
									<div class="flex md:flex-col items-center md:items-start gap-2 md:gap-0.5">
										<span class="font-black uppercase text-foreground text-2xl leading-none tracking-tight">
											<?php echo esc_html( $month_abbr ); ?>
										</span>
										<?php if ( $day_abbr ) : ?>
											<span class="text-xs uppercase tracking-widest text-foreground/55 font-bold">
												<?php echo esc_html( $day_abbr ); ?>
											</span>
										<?php endif; ?>
									</div>
								<?php else : ?>
									<span class="text-xs uppercase tracking-widest text-foreground/40 font-bold">
										<?php esc_html_e( 'TBA', 'goldenpine-theme' ); ?>
									</span>
								<?php endif; ?>
							</div>

							<!-- Image Column -->
							<div class="md:col-span-4 relative h-52 md:h-auto min-h-[200px]">
								<?php if ( $image_url ) : ?>
									<img
										src="<?php echo esc_url( $image_url ); ?>"
										alt="<?php echo esc_attr( $image_alt ); ?>"
										loading="lazy"
										decoding="async"
										class="object-cover transition-transform duration-700 group-hover:scale-105"
										style="position: absolute; height: 100%; width: 100%; inset: 0px;"
									>
								<?php else : ?>
									<div class="absolute inset-0 bg-background/50"></div>
								<?php endif; ?>
								<?php if ( $first_term ) : ?>
									<span class="absolute top-4 left-4 rounded-full bg-black/70 backdrop-blur px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-gold">
										<?php echo esc_html( $first_term->name ); ?>
									</span>
								<?php endif; ?>
							</div>

							<!-- Content Column -->
							<div class="md:col-span-6 p-6 md:p-8 lg:p-10 flex flex-col justify-between gap-5">
								<div>
									<h3
										class="font-black uppercase text-foreground leading-tight text-balance group-hover:text-gold transition-colors tracking-tight mb-3"
										style="font-size: clamp(1.5rem, 3vw, 2.5rem);"
									>
										<?php echo esc_html( get_the_title( $event->ID ) ); ?>
									</h3>
									<?php if ( $subtitle ) : ?>
										<p class="text-base md:text-lg font-light text-foreground/65 leading-snug max-w-xl">
											<?php echo esc_html( $subtitle ); ?>
										</p>
									<?php endif; ?>
									<?php if ( $time_display ) : ?>
										<p class="text-sm font-bold uppercase tracking-widest text-foreground/55 mt-4">
											<?php echo esc_html( $time_display ); ?>
										</p>
									<?php endif; ?>
								</div>

								<!-- CTA Buttons -->
								<div class="flex flex-wrap items-center gap-3">
									<a
										class="group/btn inline-flex items-center gap-3 rounded-full bg-gold pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors"
										href="<?php echo esc_url( get_theme_mod( 'goldenpine_events_cta_book_link', home_url( '/booking' ) ) ); ?>"
									>
										<?php echo esc_html( get_theme_mod( 'goldenpine_events_cta_book_text', __( 'Reserve', 'goldenpine-theme' ) ) ); ?>
										<span class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover/btn:translate-x-1" aria-hidden="true">
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
										</span>
									</a>
									<a
										class="text-sm font-bold uppercase tracking-wider text-foreground/75 hover:text-gold px-3 py-3 transition-colors"
										href="<?php echo esc_url( $event_permalink ); ?>"
									>
										<?php esc_html_e( 'Details', 'goldenpine-theme' ); ?>
									</a>
								</div>

							</div>

						</div>
					</article>

				<?php endforeach; ?>

			</div><!-- #gpine-events-list -->

		<?php else : ?>
			<p class="text-foreground/50 text-center py-16">
				<?php esc_html_e( 'No events found. Check back soon.', 'goldenpine-theme' ); ?>
			</p>
		<?php endif; ?>

	</div>
</section>

<?php if ( ! empty( $event_types ) && ! empty( $all_events ) ) : ?>
<script>
( function () {
	'use strict';

	var filters   = document.querySelectorAll( '.gpine-filter-btn' );
	var cards     = document.querySelectorAll( '.gpine-event-card' );
	var activeBtn = document.querySelector( '.gpine-filter-btn[data-filter="all"]' );

	var activeClasses   = [ 'bg-gold', 'text-black', 'border-gold' ];
	var inactiveClasses = [ 'border-border', 'bg-card', 'text-foreground\/75' ];

	function setActive( btn ) {
		filters.forEach( function ( b ) {
			b.classList.remove( 'bg-gold', 'text-black', 'border-gold' );
			b.classList.add( 'border-border', 'bg-card' );
			b.style.color = '';
			b.setAttribute( 'aria-pressed', 'false' );
		} );
		btn.classList.add( 'bg-gold', 'text-black', 'border-gold' );
		btn.classList.remove( 'border-border', 'bg-card' );
		btn.setAttribute( 'aria-pressed', 'true' );
	}

	function filterCards( type ) {
		cards.forEach( function ( card ) {
			if ( type === 'all' ) {
				card.style.display = '';
			} else {
				var types = card.getAttribute( 'data-types' ) || '';
				card.style.display = types.split( ' ' ).indexOf( type ) !== -1 ? '' : 'none';
			}
		} );
	}

	filters.forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var type = btn.getAttribute( 'data-filter' );
			setActive( btn );
			filterCards( type );
		} );
	} );
}() );
</script>
<?php endif; ?>
