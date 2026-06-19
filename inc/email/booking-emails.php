<?php
/**
 * Goldenpine Theme — inc/email/booking-emails.php
 *
 * Reusable email template functions for booking notifications.
 *
 * Functions:
 *   goldenpine_email_template( $content, $heading )   — branded HTML wrapper
 *   goldenpine_send_admin_booking_email( $data )       — notify site admin
 *   goldenpine_send_customer_booking_email( $data )    — confirm to customer
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ============================================================================
// 1. HTML Email Wrapper Template
// ============================================================================

/**
 * Wraps `$content` in a branded Golden Pine Pub HTML email layout.
 *
 * @param string $content Inner HTML content (already escaped).
 * @param string $heading Optional pre-header / preview text.
 * @return string Complete HTML email as a string.
 */
function goldenpine_email_template( string $content, string $heading = '' ): string {
	$site_name = get_bloginfo( 'name' ) ?: 'Golden Pine Pub';
	$gold      = '#C9A84C';
	$bg        = '#1a1a1a';
	$card_bg   = '#242424';
	$text      = '#e8e4da';
	$muted     = '#8a8070';

	ob_start();
	?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo esc_html( $site_name ); ?></title>
<style>
  body { margin:0; padding:0; background:<?php echo esc_attr( $bg ); ?>; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color:<?php echo esc_attr( $text ); ?>; }
  .wrapper { max-width:600px; margin:0 auto; padding:32px 16px; }
  .header { text-align:center; padding:32px 0 24px; border-bottom:1px solid rgba(201,168,76,.25); }
  .brand { font-size:11px; letter-spacing:.4em; text-transform:uppercase; color:<?php echo esc_attr( $gold ); ?>; font-weight:800; }
  .tagline { font-size:10px; letter-spacing:.25em; text-transform:uppercase; color:<?php echo esc_attr( $muted ); ?>; margin-top:4px; }
  .heading { font-size:26px; font-weight:900; text-transform:uppercase; color:#fff; letter-spacing:-.02em; margin:28px 0 8px; }
  .card { background:<?php echo esc_attr( $card_bg ); ?>; border:1px solid rgba(201,168,76,.2); border-radius:16px; padding:28px 32px; margin:20px 0; }
  .row { display:flex; padding:10px 0; border-bottom:1px solid rgba(255,255,255,.06); }
  .row:last-child { border-bottom:none; }
  .row-label { font-size:11px; text-transform:uppercase; letter-spacing:.1em; color:<?php echo esc_attr( $muted ); ?>; font-weight:700; min-width:140px; padding-top:1px; }
  .row-value { font-size:14px; color:<?php echo esc_attr( $text ); ?>; font-weight:600; }
  .row-value.gold { color:<?php echo esc_attr( $gold ); ?>; }
  .badge { display:inline-block; background:<?php echo esc_attr( $gold ); ?>; color:#000; font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; padding:4px 12px; border-radius:20px; }
  .note-box { background:rgba(201,168,76,.08); border:1px solid rgba(201,168,76,.2); border-radius:10px; padding:14px 16px; font-size:14px; color:<?php echo esc_attr( $text ); ?>; line-height:1.6; }
  .cta-btn { display:inline-block; background:<?php echo esc_attr( $gold ); ?>; color:#000; font-size:13px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; padding:14px 28px; border-radius:100px; text-decoration:none; margin:8px 0; }
  .footer { text-align:center; padding:24px 0; font-size:11px; color:<?php echo esc_attr( $muted ); ?>; line-height:1.8; border-top:1px solid rgba(255,255,255,.07); margin-top:24px; }
  .footer a { color:<?php echo esc_attr( $gold ); ?>; text-decoration:none; }
</style>
</head>
<body>
<div class="wrapper">

  <div class="header">
    <p class="brand"><?php echo esc_html( $site_name ); ?></p>
    <p class="tagline">Da Nang</p>
  </div>

  <?php if ( $heading ) : ?>
    <h1 class="heading"><?php echo esc_html( $heading ); ?></h1>
  <?php endif; ?>

  <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — caller is responsible ?>

  <div class="footer">
    <p><?php echo esc_html( $site_name ); ?> &bull; Da Nang, Vietnam</p>
    <p>
      <?php /* translators: %s: site name */ ?>
      &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $site_name ); ?>. All rights reserved.
    </p>
  </div>

</div>
</body>
</html>
	<?php
	return ob_get_clean();
}

// ============================================================================
// 2. Shared email headers builder
// ============================================================================

/**
 * Build the standard wp_mail headers array used by all booking emails.
 * Putting Content-Type directly in the headers array is more reliable than
 * using the wp_mail_content_type filter, which can be missed if another
 * plugin removes it or if an exception short-circuits the filter removal.
 *
 * @param string $reply_to Optional Reply-To address.
 * @return string[]
 */
function goldenpine_booking_mail_headers( string $reply_to = '' ): array {
	$site_name = get_bloginfo( 'name' ) ?: 'Golden Pine Pub';
	$from      = sanitize_email( get_option( 'admin_email' ) );

	$headers = [
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . $site_name . ' <' . $from . '>',
	];

	if ( $reply_to && is_email( $reply_to ) ) {
		$headers[] = 'Reply-To: ' . sanitize_email( $reply_to );
	}

	return $headers;
}

// ============================================================================
// 3. Admin Notification Email
// ============================================================================

/**
 * Send a notification email to the configured notification address with full
 * booking details.
 *
 * @param array $data Booking data array (sanitised values).
 * @return bool True if wp_mail() accepted the message, false otherwise.
 */
function goldenpine_send_admin_booking_email( array $data ): bool {
	$to        = sanitize_email( goldenpine_get_booking_notification_email() );
	$site_name = get_bloginfo( 'name' ) ?: 'Golden Pine Pub';

	// Bail early if no valid destination.
	if ( ! is_email( $to ) ) {
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( '[Goldenpine] Admin booking email skipped — invalid to address: ' . $to );
		return false;
	}

	$submitted = isset( $data['submitted_at'] )
		? get_date_from_gmt( gmdate( 'Y-m-d H:i:s', (int) $data['submitted_at'] ), 'd M Y, H:i' )
		: current_time( 'd M Y, H:i' );

	$subject = sprintf(
		'[%1$s] New Booking — %2$s (%3$s)',
		$site_name,
		$data['name'] ?? '',
		$data['ref'] ?? ''
	);

	ob_start();
	?>
<div class="card">
  <div class="row"><span class="row-label">Reference</span><span class="row-value gold"><?php echo esc_html( $data['ref'] ?? '' ); ?></span></div>
  <div class="row"><span class="row-label">Status</span><span class="badge">New</span></div>
  <div class="row"><span class="row-label">Guest Name</span><span class="row-value"><?php echo esc_html( $data['name'] ?? '' ); ?></span></div>
  <div class="row"><span class="row-label">Phone</span><span class="row-value"><?php echo esc_html( $data['phone'] ?? '' ); ?></span></div>
  <?php if ( ! empty( $data['email'] ) ) : ?>
  <div class="row"><span class="row-label">Email</span><span class="row-value"><?php echo esc_html( $data['email'] ); ?></span></div>
  <?php endif; ?>
  <div class="row"><span class="row-label">Date</span><span class="row-value"><?php echo esc_html( $data['date_formatted'] ?? $data['date'] ?? '' ); ?></span></div>
  <div class="row"><span class="row-label">Time</span><span class="row-value"><?php echo esc_html( $data['time'] ?? '' ); ?></span></div>
  <div class="row"><span class="row-label">Guests</span><span class="row-value"><?php echo esc_html( $data['guests'] ?? '' ); ?></span></div>
  <?php if ( ! empty( $data['note'] ) ) : ?>
  <div class="row"><span class="row-label">Special Requests</span><span class="row-value"><?php echo nl2br( esc_html( $data['note'] ) ); ?></span></div>
  <?php endif; ?>
  <div class="row"><span class="row-label">Submitted</span><span class="row-value"><?php echo esc_html( $submitted ); ?></span></div>
  <div class="row"><span class="row-label">IP Address</span><span class="row-value"><?php echo esc_html( $data['ip'] ?? '—' ); ?></span></div>
</div>
<p style="text-align:center;margin:24px 0;">
  <a class="cta-btn" href="<?php echo esc_url( admin_url( 'edit.php?post_type=gpine_booking' ) ); ?>">View All Bookings</a>
</p>
	<?php
	$inner   = ob_get_clean();
	$message = goldenpine_email_template( $inner, 'New Booking Received' );
	$headers = goldenpine_booking_mail_headers();

	$sent = wp_mail( $to, $subject, $message, $headers );

	if ( ! $sent ) {
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( '[Goldenpine] Admin booking email FAILED to: ' . $to . ' | Ref: ' . ( $data['ref'] ?? '' ) );
	}

	return $sent;
}

// ============================================================================
// 4. Customer Confirmation Email
// ============================================================================

/**
 * Send a confirmation email to the customer.
 * Only runs when $data['email'] is provided and valid.
 *
 * @param array $data Booking data array (sanitised values).
 * @return bool True if sent, false if no email or send failed.
 */
function goldenpine_send_customer_booking_email( array $data ): bool {
	$to = sanitize_email( $data['email'] ?? '' );

	if ( ! is_email( $to ) ) {
		return false;
	}

	$site_name = get_bloginfo( 'name' ) ?: 'Golden Pine Pub';
	$phone     = get_theme_mod( 'goldenpine_footer_phone', '+84 905 123 456' );
	$phone_raw = preg_replace( '/[^+\d]/', '', $phone );

	$subject = sprintf(
		'[%1$s] Booking Confirmation — %2$s',
		$site_name,
		$data['ref'] ?? ''
	);

	ob_start();
	?>
<p style="font-size:16px;color:#000;line-height:1.7;margin:16px 0 24px;">
  <?php
  printf(
	  esc_html__( 'Hi %s,', 'goldenpine-theme' ),
	  esc_html( explode( ' ', trim( $data['name'] ?? 'there' ) )[0] )
  );
  ?>
  <br>
  <?php esc_html_e( "Thank you for your reservation request at Golden Pine Pub. We've received your details and our team will confirm your table via phone or WhatsApp within the hour.", 'goldenpine-theme' ); ?>
</p>

<div class="card">
  <p style="font-size:11px;letter-spacing:.35em;text-transform:uppercase;color:#C9A84C;font-weight:800;margin:0 0 16px;">
    <?php esc_html_e( 'Your Booking Summary', 'goldenpine-theme' ); ?>
  </p>
  <div class="row"><span class="row-label">Reference</span><span class="row-value gold"><?php echo esc_html( $data['ref'] ?? '' ); ?></span></div>
  <div class="row"><span class="row-label">Name</span><span class="row-value"><?php echo esc_html( $data['name'] ?? '' ); ?></span></div>
  <div class="row"><span class="row-label">Date</span><span class="row-value"><?php echo esc_html( $data['date_formatted'] ?? $data['date'] ?? '' ); ?></span></div>
  <div class="row"><span class="row-label">Time</span><span class="row-value"><?php echo esc_html( $data['time'] ?? '' ); ?></span></div>
  <div class="row"><span class="row-label">Guests</span><span class="row-value"><?php echo esc_html( $data['guests'] ?? '' ); ?></span></div>
  <?php if ( ! empty( $data['note'] ) ) : ?>
  <div class="row"><span class="row-label">Special Requests</span><span class="row-value"><?php echo nl2br( esc_html( $data['note'] ) ); ?></span></div>
  <?php endif; ?>
</div>

<div class="note-box" style="margin:16px 0;">
  <strong style="color:#000;"><?php esc_html_e( 'What happens next?', 'goldenpine-theme' ); ?></strong><br>
  <span style="color:#000;">
  <?php
  printf(
	  esc_html__( 'Our team will call or WhatsApp %s to confirm your reservation. Please keep your phone handy. If you need to adjust or cancel, reply to this email or call us directly.', 'goldenpine-theme' ),
	  esc_html( $data['phone'] ?? '' )
  );
  ?>
  </span>
</div>

<p style="text-align:center;margin:28px 0 8px;">
  <?php if ( $phone_raw ) : ?>
    <a class="cta-btn" href="tel:<?php echo esc_attr( $phone_raw ); ?>"><?php echo esc_html( $phone ); ?></a>
  <?php endif; ?>
</p>

<p style="font-size:12px;color:#8a8070;text-align:center;line-height:1.7;margin-top:8px;">
  <?php esc_html_e( 'Please quote your reference ID when contacting us.', 'goldenpine-theme' ); ?>
  <strong style="color:#C9A84C;"><?php echo esc_html( $data['ref'] ?? '' ); ?></strong>
</p>
	<?php
	$inner   = ob_get_clean();
	$message = goldenpine_email_template( $inner, 'Booking Received!' );
	$headers = goldenpine_booking_mail_headers( sanitize_email( get_option( 'admin_email' ) ) );

	$sent = wp_mail( $to, $subject, $message, $headers );

	if ( ! $sent ) {
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( '[Goldenpine] Customer booking email FAILED to: ' . $to . ' | Ref: ' . ( $data['ref'] ?? '' ) );
	}

	return $sent;
}

// ============================================================================
// 5. Log wp_mail failures with PHPMailer error detail
// ============================================================================

/**
 * Hook into wp_mail_failed to write the failure reason to the PHP error log.
 * This fires whenever wp_mail() catches a PHPMailer exception.
 *
 * @param \WP_Error $error The error returned by PHPMailer.
 */
function goldenpine_log_mail_failure( \WP_Error $error ): void {
	// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	error_log( '[Goldenpine] wp_mail_failed — ' . $error->get_error_message() );
}
add_action( 'wp_mail_failed', 'goldenpine_log_mail_failure' );
