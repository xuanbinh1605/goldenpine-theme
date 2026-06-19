<?php
/**
 * Template Part — Single Event Booking CTA Section
 *
 * Outputs the booking call-to-action card with:
 *  - Large date display (if event date is set)
 *  - Time display (if start/end time is set)
 *  - "Book A Table" button
 *  - "Call Now" button (if booking phone is set)
 *
 * Hidden if no date, time, or booking phone exists.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Fetch event metadata.
$event_date       = get_post_meta( get_the_ID(), '_gpine_event_date', true );
$event_start_time = get_post_meta( get_the_ID(), '_gpine_event_start_time', true );
$event_end_time   = get_post_meta( get_the_ID(), '_gpine_event_end_time', true );
$booking_phone    = get_post_meta( get_the_ID(), '_gpine_booking_phone', true );

// Parse date components.
$day_num    = '';
$month_abbr = '';
$day_abbr   = '';
if ( $event_date ) {
	$timestamp = strtotime( $event_date );
	if ( $timestamp ) {
		$day_num    = date_i18n( 'j', $timestamp );
		$month_abbr = strtoupper( date_i18n( 'M', $timestamp ) );
		$day_abbr   = strtoupper( date_i18n( 'D', $timestamp ) );
	}
}

// Build time display string.
$time_display = '';
if ( $event_start_time || $event_end_time ) {
	$time_parts = [];
	if ( $event_start_time ) {
		$start_timestamp = strtotime( $event_start_time );
		$time_parts[] = strtoupper( date_i18n( 'g A', $start_timestamp ) );
	}
	if ( $event_end_time ) {
		$end_timestamp = strtotime( $event_end_time );
		$time_parts[] = strtoupper( date_i18n( 'g A', $end_timestamp ) );
	}
	$time_display = implode( ' — ', $time_parts );
}

// Only render section if we have date/time or booking phone.
if ( ! $event_date && ! $time_display && ! $booking_phone ) {
	return;
}
?>

<section class="px-6 lg:px-12 pt-16 md:pt-24">
	<div class="max-w-7xl mx-auto">
		<div class="rounded-3xl border border-gold/40 bg-card overflow-hidden box-glow-gold-hover">
			<div class="grid grid-cols-1 md:grid-cols-12">

				<!-- Left Column: Date Display -->
				<?php if ( $event_date && $day_num && $month_abbr && $day_abbr ) : ?>
					<div class="md:col-span-5 p-8 md:p-12 bg-background flex items-end gap-6 border-b md:border-b-0 md:border-r border-border">
						<span class="font-black text-gold leading-[0.85]" style="font-size: clamp(6rem, 12vw, 11rem);">
							<?php echo esc_html( $day_num ); ?>
						</span>
						<div class="pb-4 flex flex-col gap-2">
							<span class="font-black uppercase text-foreground text-4xl leading-none tracking-tight">
								<?php echo esc_html( $month_abbr ); ?>
							</span>
							<span class="text-sm font-bold uppercase tracking-[0.3em] text-foreground/55">
								<?php echo esc_html( $day_abbr ); ?>
							</span>
							<?php if ( $time_display ) : ?>
								<span class="text-sm font-bold uppercase tracking-[0.3em] text-foreground/55">
									<?php echo esc_html( $time_display ); ?>
								</span>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- Right Column: CTA Buttons -->
				<div class="<?php echo ( $event_date && $day_num ) ? 'md:col-span-7' : 'md:col-span-12'; ?> p-8 md:p-12 flex flex-col justify-center gap-6">
					<p class="font-black uppercase text-foreground leading-tight text-balance tracking-tight" style="font-size: clamp(1.8rem, 3.5vw, 3rem);">
						<?php esc_html_e( 'Tables fill fast. Lock yours in now.', 'goldenpine-theme' ); ?>
					</p>
					<div class="flex flex-wrap items-center gap-3">

						<!-- Book A Table Button -->
						<a class="group inline-flex items-center gap-3 rounded-full bg-gold pl-7 pr-3 py-3 text-sm md:text-base font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors box-glow-gold" href="<?php echo esc_url( home_url( '/booking' ) ); ?>">
							<?php esc_html_e( 'Book A Table', 'goldenpine-theme' ); ?>
							<span class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
							</span>
						</a>

						<!-- Call Now Button -->
						<?php if ( $booking_phone ) : ?>
							<a href="tel:<?php echo esc_attr( $booking_phone ); ?>" class="group inline-flex items-center gap-3 rounded-full border border-border bg-background pl-6 pr-3 py-3 text-sm md:text-base font-bold uppercase tracking-wider text-foreground hover:border-gold hover:text-gold transition-colors">
								<?php esc_html_e( 'Call Now', 'goldenpine-theme' ); ?>
								<span class="flex h-10 w-10 items-center justify-center rounded-full bg-foreground/5 transition-transform group-hover:-rotate-12" aria-hidden="true">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
								</span>
							</a>
						<?php endif; ?>

					</div>
				</div>

			</div>
		</div>
	</div>
</section>
