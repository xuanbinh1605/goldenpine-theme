<?php
/**
 * Goldenpine Theme — template-parts/booking/form.php
 *
 * Booking page main section.
 * Left column  (7 cols): intro badges + booking form.
 * Right column (5 cols): photo mosaic + direct-call card.
 *
 * The <form> renders all fields and the nonce.
 * Submission is handled via AJAX — see inc/ajax/booking-ajax.php.
 * Live validation and AJAX logic live in assets/js/page-specific-js/booking-page.js.
 *
 * Content managed via Appearance > Customize > Booking Page > Form Section.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Customizer values ──────────────────────────────────────────────────────────
$intro_text = get_theme_mod(
	'goldenpine_booking_form_intro',
	"Fill in your details and our team will confirm your table via phone or WhatsApp within the hour. No deposit required."
);

$phone_number   = get_theme_mod( 'goldenpine_footer_phone', '+84 905 123 456' );
$phone_label    = get_theme_mod( 'goldenpine_booking_phone_label', 'Prefer to call?' );
$phone_subtext  = get_theme_mod( 'goldenpine_booking_phone_subtext', 'Available every day from 4 PM. WhatsApp and Messenger too.' );
$message_link   = get_theme_mod( 'goldenpine_booking_message_link', 'https://m.me/goldenpinepub' );
$phone_raw      = preg_replace( '/[^+\d]/', '', $phone_number );

// ── Sidebar gallery images ─────────────────────────────────────────────────────
$gallery_images = [];
for ( $i = 1; $i <= 4; $i++ ) {
	$img_id  = get_theme_mod( "goldenpine_booking_gallery_image_{$i}", 0 );
	$img_url = $img_id
		? wp_get_attachment_image_url( $img_id, 'large' )
		: '';
	$gallery_images[] = $img_url;
}

// Time options available for reservation (9 PM to 2 AM).
$time_options = [ '9 PM', '10 PM', '11 PM', '12 AM', '1 AM', '2 AM' ];

// Guest count options.
$guest_options = [
	'1'  => '1 guest',
	'2'  => '2 guests',
	'3'  => '3 guests',
	'4'  => '4 guests',
	'5'  => '5 guests',
	'6'  => '6 guests',
	'7'  => '7 guests',
	'8'  => '8 guests',
	'9+' => '9+ guests (large group)',
];

// Minimum bookable date = today.
$min_date = gmdate( 'Y-m-d' );
?>

<section class="py-20 md:py-28 px-6 lg:px-12 bg-background">
	<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">

		<!-- ====================================================================
		     LEFT COLUMN — intro + booking form
		     ==================================================================== -->
		<div class="lg:col-span-7">

			<!-- Intro paragraph -->
			<?php if ( $intro_text ) : ?>
				<p class="text-xl md:text-2xl font-light text-foreground/75 leading-snug max-w-xl mb-10">
					<?php echo esc_html( $intro_text ); ?>
				</p>
			<?php endif; ?>

			<!-- Feature badges -->
			<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-12">

				<div class="rounded-2xl border border-border bg-card p-4 flex items-center gap-3 box-glow-gold-hover">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold shrink-0" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
					<p class="text-sm font-bold text-foreground leading-tight"><?php echo esc_html( get_theme_mod( 'goldenpine_booking_badge_1', 'Free reservation' ) ); ?></p>
				</div>

				<div class="rounded-2xl border border-border bg-card p-4 flex items-center gap-3 box-glow-gold-hover">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold shrink-0" aria-hidden="true"><path d="M12 6v6l4 2"></path><circle cx="12" cy="12" r="10"></circle></svg>
					<p class="text-sm font-bold text-foreground leading-tight"><?php echo esc_html( get_theme_mod( 'goldenpine_booking_badge_2', 'Open 7 nights' ) ); ?></p>
				</div>

				<div class="rounded-2xl border border-border bg-card p-4 flex items-center gap-3 box-glow-gold-hover">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold shrink-0" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><path d="M16 3.128a4 4 0 0 1 0 7.744"></path><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><circle cx="9" cy="7" r="4"></circle></svg>
					<p class="text-sm font-bold text-foreground leading-tight"><?php echo esc_html( get_theme_mod( 'goldenpine_booking_badge_3', 'Groups welcome' ) ); ?></p>
				</div>

				<div class="rounded-2xl border border-border bg-card p-4 flex items-center gap-3 box-glow-gold-hover">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold shrink-0" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
					<p class="text-sm font-bold text-foreground leading-tight"><?php echo esc_html( get_theme_mod( 'goldenpine_booking_badge_4', 'Fast response' ) ); ?></p>
				</div>

			</div><!-- /.grid badges -->

			<!-- ================================================================
			     BOOKING FORM
			     ================================================================ -->
		<form
			id="gpine-booking-form"
			class="rounded-3xl border p-6 md:p-10 flex flex-col gap-5"
			style="border-color:rgba(226,190,61,0.22); background-color: #4f4b4b;"
			novalidate
		>
				<?php wp_nonce_field( 'gpine_booking_nonce', 'gpine_booking_nonce_field' ); ?>
				<input type="hidden" name="action" value="gpine_submit_booking">

				<!-- Full Name -->
				<div class="flex flex-col gap-2">
					<label for="booking_name" class="text-xs font-bold tracking-widest uppercase text-foreground/65">
						<?php esc_html_e( 'Full Name', 'goldenpine-theme' ); ?>
						<span class="text-gold" aria-hidden="true">*</span>
					</label>
					<input
						id="booking_name"
						type="text"
						name="booking_name"
						required
						autocomplete="name"
						placeholder="<?php esc_attr_e( 'John Smith', 'goldenpine-theme' ); ?>"
						class="booking-field bg-background border border-border rounded-2xl px-5 py-4 text-foreground text-base placeholder:text-foreground/30 focus:outline-none focus:border-gold transition-colors"
					>
					<p class="field-error text-xs text-red-400 hidden" aria-live="polite"></p>
				</div>

				<!-- Phone -->
				<div class="flex flex-col gap-2">
					<label for="booking_phone" class="text-xs font-bold tracking-widest uppercase text-foreground/65 flex items-center gap-1.5">
						<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
						<?php esc_html_e( 'Phone', 'goldenpine-theme' ); ?>
						<span class="text-gold" aria-hidden="true">*</span>
					</label>
					<input
						id="booking_phone"
						type="tel"
						name="booking_phone"
						required
						autocomplete="tel"
						placeholder="+84 xxx xxx xxx"
						class="booking-field bg-background border border-border rounded-2xl px-5 py-4 text-foreground text-base placeholder:text-foreground/30 focus:outline-none focus:border-gold transition-colors"
					>
					<p class="field-error text-xs text-red-400 hidden" aria-live="polite"></p>
					<p class="text-xs text-foreground/55 flex items-center gap-1.5 mt-1">
						<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
						<?php esc_html_e( "We'll call or WhatsApp this number to confirm.", 'goldenpine-theme' ); ?>
					</p>
				</div>

				<!-- Email (optional – used for confirmation email) -->
				<div class="flex flex-col gap-2">
					<label for="booking_email" class="text-xs font-bold tracking-widest uppercase text-foreground/65 flex items-center gap-1.5">
						<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
						<?php esc_html_e( 'Email', 'goldenpine-theme' ); ?>
						<span class="text-foreground/40 font-normal normal-case tracking-normal"><?php esc_html_e( '(optional – for confirmation)', 'goldenpine-theme' ); ?></span>
					</label>
					<input
						id="booking_email"
						type="email"
						name="booking_email"
						autocomplete="email"
						placeholder="your@email.com"
						class="booking-field bg-background border border-border rounded-2xl px-5 py-4 text-foreground text-base placeholder:text-foreground/30 focus:outline-none focus:border-gold transition-colors"
					>
					<p class="field-error text-xs text-red-400 hidden" aria-live="polite"></p>
				</div>

				<!-- Date + Time -->
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

					<!-- Date -->
					<div class="flex flex-col gap-2">
						<label for="booking_date" class="text-xs font-bold tracking-widest uppercase text-foreground/65 flex items-center gap-1.5">
							<?php esc_html_e( 'Date', 'goldenpine-theme' ); ?>
							<span class="text-gold" aria-hidden="true">*</span>
						</label>
					<!-- Anchor for the custom JS calendar popup -->
					<div class="relative" id="gpine-datepicker-anchor">
						<button
							type="button"
							id="booking_date_btn"
							class="w-full bg-background border border-border rounded-2xl px-5 py-4 text-base text-foreground hover:border-gold transition-colors flex items-center justify-between"
							aria-haspopup="true"
							aria-expanded="false"
						>
							<span id="booking_date_display" class="text-foreground/50"><?php esc_html_e( 'Select date', 'goldenpine-theme' ); ?></span>
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold shrink-0" aria-hidden="true"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path></svg>
						</button>
						<!-- Value is set programmatically by the custom calendar picker -->
						<input
							type="hidden"
							id="booking_date"
							name="booking_date"
							class="booking-field"
						>
					</div>
						<p class="field-error text-xs text-red-400 hidden" aria-live="polite"></p>
					</div>

					<!-- Time -->
					<div class="flex flex-col gap-2">
						<label for="booking_time" class="text-xs font-bold tracking-widest uppercase text-foreground/65 flex items-center gap-1.5">
							<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 6v6l4 2"></path><circle cx="12" cy="12" r="10"></circle></svg>
							<?php esc_html_e( 'Time', 'goldenpine-theme' ); ?>
							<span class="text-gold" aria-hidden="true">*</span>
						</label>
					<select
						id="booking_time"
						name="booking_time"
						required
						class="booking-field bg-background border border-border rounded-2xl px-5 py-4 text-foreground text-base focus:outline-none focus:border-gold transition-colors cursor-pointer"
					>
							<option value=""><?php esc_html_e( 'Select', 'goldenpine-theme' ); ?></option>
							<?php foreach ( $time_options as $time ) : ?>
								<option value="<?php echo esc_attr( $time ); ?>"><?php echo esc_html( $time ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="field-error text-xs text-red-400 hidden" aria-live="polite"></p>
					</div>

				</div><!-- /.grid date-time -->

				<!-- Guests -->
				<div class="flex flex-col gap-2">
					<label for="booking_guests" class="text-xs font-bold tracking-widest uppercase text-foreground/65 flex items-center gap-1.5">
						<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><path d="M16 3.128a4 4 0 0 1 0 7.744"></path><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><circle cx="9" cy="7" r="4"></circle></svg>
						<?php esc_html_e( 'Guests', 'goldenpine-theme' ); ?>
						<span class="text-gold" aria-hidden="true">*</span>
					</label>
				<select
					id="booking_guests"
					name="booking_guests"
					required
					class="booking-field bg-background border border-border rounded-2xl px-5 py-4 text-foreground text-base focus:outline-none focus:border-gold transition-colors cursor-pointer"
				>
						<?php foreach ( $guest_options as $val => $label ) : ?>
							<option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
					<p class="field-error text-xs text-red-400 hidden" aria-live="polite"></p>
				</div>

				<!-- Special Requests -->
				<div class="flex flex-col gap-2">
					<label for="booking_note" class="text-xs font-bold tracking-widest uppercase text-foreground/65">
						<?php esc_html_e( 'Special Requests', 'goldenpine-theme' ); ?>
					</label>
					<textarea
						id="booking_note"
						name="booking_note"
						rows="4"
						placeholder="<?php esc_attr_e( 'Birthday, VIP booth, which event you\'re attending', 'goldenpine-theme' ); ?>"
						class="booking-field bg-background border border-border rounded-2xl px-5 py-4 text-foreground text-base placeholder:text-foreground/30 focus:outline-none focus:border-gold transition-colors resize-none leading-relaxed"
					></textarea>
				</div>

				<!-- Submit button -->
				<button
					id="gpine-booking-submit"
					type="submit"
					class="group mt-2 inline-flex items-center justify-center gap-3 rounded-full bg-gold pl-8 pr-3 py-4 text-base font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors self-start box-glow-gold"
				>
					<span id="gpine-booking-btn-label"><?php esc_html_e( 'Confirm Reservation', 'goldenpine-theme' ); ?></span>
					<span class="flex h-11 w-11 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
					</span>
				</button>

				<!-- Terms note -->
				<p class="text-xs text-foreground/55 leading-relaxed mt-2">
					<?php
					printf(
						/* translators: %s: terms & conditions link */
						esc_html__( 'By submitting, you agree to our %s. Confirmation by phone or WhatsApp.', 'goldenpine-theme' ),
						'<a class="text-gold hover:text-gold-bright transition-colors" href="#">' . esc_html__( 'Terms &amp; Conditions', 'goldenpine-theme' ) . '</a>'
					);
					?>
				</p>

			</form><!-- #gpine-booking-form -->

		</div><!-- /.lg:col-span-7 -->

		<!-- ====================================================================
		     RIGHT COLUMN — photo mosaic + call card
		     ==================================================================== -->
		<aside class="lg:col-span-5 flex flex-col gap-6">

			<!-- Photo mosaic -->
			<?php if ( array_filter( $gallery_images ) ) : ?>
				<div class="grid grid-cols-2 gap-3">

					<?php foreach ( $gallery_images as $index => $img_url ) :
						if ( ! $img_url ) continue;
						$is_wide = ( 0 === $index ); // First image spans 2 cols.
						$ratio   = $is_wide ? 'aspect-[4/3]' : 'aspect-square';
						$span    = $is_wide ? 'col-span-2' : '';
					?>
						<div class="relative overflow-hidden rounded-3xl box-glow-gold-hover <?php echo esc_attr( $ratio . ' ' . $span ); ?>">
							<img
								src="<?php echo esc_url( $img_url ); ?>"
								alt="<?php echo esc_attr( sprintf( __( 'Golden Pine Pub — photo %d', 'goldenpine-theme' ), $index + 1 ) ); ?>"
								class="absolute inset-0 w-full h-full object-cover"
								loading="lazy"
								decoding="async"
							>
						</div>
					<?php endforeach; ?>

				</div>
			<?php endif; ?>

			<!-- Direct-call card -->
			<div class="rounded-3xl border border-gold/40 bg-card p-8 flex flex-col gap-5">

				<?php if ( $phone_label ) : ?>
					<p class="text-xs font-bold tracking-[0.4em] uppercase text-gold flex items-center gap-2">
						<span class="h-px w-6 bg-gold inline-block"></span>
						<?php echo esc_html( $phone_label ); ?>
					</p>
				<?php endif; ?>

				<?php if ( $phone_number ) : ?>
					<p
						class="font-black uppercase text-foreground leading-none tracking-tight"
						style="font-size: clamp(1.6rem, 3.5vw, 3rem);"
					>
						<?php echo esc_html( $phone_number ); ?>
					</p>
				<?php endif; ?>

				<?php if ( $phone_subtext ) : ?>
					<p class="text-base font-light text-foreground/65">
						<?php echo esc_html( $phone_subtext ); ?>
					</p>
				<?php endif; ?>

				<div class="flex flex-wrap items-center gap-3 pt-2">

					<?php if ( $phone_raw ) : ?>
						<a
							href="tel:<?php echo esc_attr( $phone_raw ); ?>"
							class="group inline-flex items-center gap-3 rounded-full bg-gold pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors"
						>
							<?php esc_html_e( 'Call Now', 'goldenpine-theme' ); ?>
							<span class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:-rotate-12">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path></svg>
							</span>
						</a>
					<?php endif; ?>

					<?php if ( $message_link ) : ?>
						<a
							href="<?php echo esc_url( $message_link ); ?>"
							target="_blank"
							rel="noopener noreferrer"
							class="group inline-flex items-center gap-3 rounded-full border border-border bg-background pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-foreground hover:border-gold hover:text-gold transition-colors"
						>
							<?php esc_html_e( 'Message', 'goldenpine-theme' ); ?>
							<span class="flex h-10 w-10 items-center justify-center rounded-full bg-foreground/5 transition-transform group-hover:translate-x-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"></path><path d="M7 17 17 7"></path></svg>
							</span>
						</a>
					<?php endif; ?>

				</div>

			</div><!-- /.call card -->

		</aside><!-- /.lg:col-span-5 -->

	</div><!-- /.max-w-7xl -->
</section>
