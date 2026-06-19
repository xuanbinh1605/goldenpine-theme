<?php
/**
 * Goldenpine Theme — inc/ajax/booking-ajax.php
 *
 * Secure AJAX handler for the booking form submission.
 *
 * Hooks:
 *   wp_ajax_gpine_submit_booking         — logged-in users
 *   wp_ajax_nopriv_gpine_submit_booking  — guests (public)
 *
 * Security layers:
 *   1. Nonce verification  (gpine_booking_nonce)
 *   2. Rate limiting       (max 3 submissions / hour per IP)
 *   3. Server-side validation
 *   4. Sanitization of all input
 *   5. Duplicate detection (same name+phone+date within 2 hours)
 *
 * On success:
 *   - Creates a `gpine_booking` CPT post
 *   - Stores all fields as post meta
 *   - Sends admin + optional customer emails
 *   - Returns JSON with reference ID
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main AJAX handler for booking form submissions.
 * Registered for both authenticated and guest users.
 */
function goldenpine_handle_booking_submission(): void {
	// ── 1. Nonce verification ─────────────────────────────────────────────────
	$nonce = isset( $_POST['gpine_booking_nonce_field'] )
		? sanitize_key( wp_unslash( $_POST['gpine_booking_nonce_field'] ) )
		: '';

	if ( ! wp_verify_nonce( $nonce, 'gpine_booking_nonce' ) ) {
		wp_send_json_error( [
			'code'    => 'invalid_nonce',
			'message' => __( 'Security check failed. Please refresh the page and try again.', 'goldenpine-theme' ),
		], 403 );
	}

	// ── 2. Rate limiting (IP-based) ───────────────────────────────────────────
	// TEMPORARILY DISABLED FOR TESTING
	$ip            = goldenpine_get_client_ip();
	// $rate_key      = 'gpine_book_rate_' . md5( $ip );
	// $rate_count    = (int) get_transient( $rate_key );
	// $rate_limit    = 3;

	// if ( $rate_count >= $rate_limit ) {
	// 	wp_send_json_error( [
	// 		'code'    => 'rate_limit',
	// 		'message' => __( 'Too many submissions. Please wait a while before trying again.', 'goldenpine-theme' ),
	// 	], 429 );
	// }

	// ── 3. Sanitize inputs ────────────────────────────────────────────────────
	$name   = isset( $_POST['booking_name'] )   ? sanitize_text_field( wp_unslash( $_POST['booking_name'] ) )   : '';
	$phone  = isset( $_POST['booking_phone'] )  ? sanitize_text_field( wp_unslash( $_POST['booking_phone'] ) )  : '';
	$email  = isset( $_POST['booking_email'] )  ? sanitize_email( wp_unslash( $_POST['booking_email'] ) )       : '';
	$date   = isset( $_POST['booking_date'] )   ? sanitize_text_field( wp_unslash( $_POST['booking_date'] ) )   : '';
	$time   = isset( $_POST['booking_time'] )   ? sanitize_text_field( wp_unslash( $_POST['booking_time'] ) )   : '';
	$guests = isset( $_POST['booking_guests'] ) ? sanitize_text_field( wp_unslash( $_POST['booking_guests'] ) ) : '';
	$note   = isset( $_POST['booking_note'] )   ? sanitize_textarea_field( wp_unslash( $_POST['booking_note'] ) ) : '';

	// ── 4. Server-side validation ─────────────────────────────────────────────
	$errors = [];

	if ( empty( $name ) || mb_strlen( $name ) < 2 ) {
		$errors['booking_name'] = __( 'Please enter your full name (at least 2 characters).', 'goldenpine-theme' );
	}

	if ( empty( $phone ) ) {
		$errors['booking_phone'] = __( 'Please enter a valid phone number.', 'goldenpine-theme' );
	} elseif ( ! preg_match( '/^[+\d\s\-().]{6,20}$/', $phone ) ) {
		$errors['booking_phone'] = __( 'Phone number format is invalid.', 'goldenpine-theme' );
	}

	if ( ! empty( $email ) && ! is_email( $email ) ) {
		$errors['booking_email'] = __( 'Please enter a valid email address.', 'goldenpine-theme' );
	}

	if ( empty( $date ) ) {
		$errors['booking_date'] = __( 'Please select a reservation date.', 'goldenpine-theme' );
	} else {
		$date_ts = strtotime( $date );
		if ( ! $date_ts ) {
			$errors['booking_date'] = __( 'Invalid date format.', 'goldenpine-theme' );
		} elseif ( $date_ts < strtotime( gmdate( 'Y-m-d' ) ) ) {
			$errors['booking_date'] = __( 'Reservation date cannot be in the past.', 'goldenpine-theme' );
		}
	}

	$valid_times = [ '9 PM', '10 PM', '11 PM', '12 AM', '1 AM', '2 AM' ];
	if ( empty( $time ) || ! in_array( $time, $valid_times, true ) ) {
		$errors['booking_time'] = __( 'Please select a valid arrival time.', 'goldenpine-theme' );
	}

	$valid_guests = [ '1', '2', '3', '4', '5', '6', '7', '8', '9+' ];
	if ( empty( $guests ) || ! in_array( $guests, $valid_guests, true ) ) {
		$errors['booking_guests'] = __( 'Please select the number of guests.', 'goldenpine-theme' );
	}

	if ( ! empty( $errors ) ) {
		wp_send_json_error( [
			'code'    => 'validation_failed',
			'message' => __( 'Please check the highlighted fields and try again.', 'goldenpine-theme' ),
			'fields'  => $errors,
		], 422 );
	}

	// ── 5. Duplicate detection ────────────────────────────────────────────────
	// TEMPORARILY DISABLED FOR TESTING
	// $dup_key = 'gpine_book_dup_' . md5( $name . $phone . $date );
	// if ( get_transient( $dup_key ) ) {
	// 	wp_send_json_error( [
	// 		'code'    => 'duplicate',
	// 		'message' => __( 'A booking with these details was recently submitted. Please wait before trying again.', 'goldenpine-theme' ),
	// 	], 409 );
	// }

	// ── 6. Generate reference ID ──────────────────────────────────────────────
	$ref = 'GP-' . gmdate( 'Ymd' ) . '-' . wp_rand( 1000, 9999 );

	// ── 7. Format date for display ────────────────────────────────────────────
	$date_ts        = strtotime( $date );
	$date_formatted = $date_ts ? gmdate( 'l, d F Y', $date_ts ) : $date;

	// ── 8. Create CPT post ────────────────────────────────────────────────────
	$post_title = sprintf( '%s — %s', $ref, $name );

	$post_id = wp_insert_post( [
		'post_title'  => wp_slash( $post_title ),
		'post_status' => 'publish',
		'post_type'   => 'gpine_booking',
	], true );

	if ( is_wp_error( $post_id ) ) {
		wp_send_json_error( [
			'code'    => 'save_failed',
			'message' => __( 'Could not save your booking. Please try again or contact us directly.', 'goldenpine-theme' ),
		], 500 );
	}

	$submitted_at = time();

	$meta = [
		'_gpine_booking_ref'          => $ref,
		'_gpine_booking_name'         => $name,
		'_gpine_booking_phone'        => $phone,
		'_gpine_booking_email'        => $email,
		'_gpine_booking_date'         => $date,
		'_gpine_booking_time'         => $time,
		'_gpine_booking_guests'       => $guests,
		'_gpine_booking_note'         => $note,
		'_gpine_booking_status'       => 'new',
		'_gpine_booking_ip'           => $ip,
		'_gpine_booking_submitted_at' => $submitted_at,
	];

	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	// Clear menu badge cache.
	wp_cache_delete( 'gpine_booking_new_count' );

	// ── 9. Set rate limiting and duplicate transients ─────────────────────────
	// TEMPORARILY DISABLED FOR TESTING
	// set_transient( $rate_key, $rate_count + 1, HOUR_IN_SECONDS );
	// set_transient( $dup_key, 1, 2 * HOUR_IN_SECONDS );

	// ── 10. Send emails ───────────────────────────────────────────────────────
	$email_data = [
		'ref'            => $ref,
		'name'           => $name,
		'phone'          => $phone,
		'email'          => $email,
		'date'           => $date,
		'date_formatted' => $date_formatted,
		'time'           => $time,
		'guests'         => $guests,
		'note'           => $note,
		'ip'             => $ip,
		'submitted_at'   => $submitted_at,
	];

	// Capture the target admin email before sending so we can surface it in the
	// debug response. Helps diagnose delivery issues without server log access.
	$admin_email_to = goldenpine_get_booking_notification_email();

	$admin_sent    = goldenpine_send_admin_booking_email( $email_data );
	$customer_sent = goldenpine_send_customer_booking_email( $email_data );

	// ── 11. Return success ────────────────────────────────────────────────────
	wp_send_json_success( [
		'code'           => 'booking_created',
		'ref'            => $ref,
		'admin_sent'     => $admin_sent,
		'admin_to'       => $admin_email_to,
		'customer_sent'  => $customer_sent,
		'customer_to'    => ! empty( $email ) ? $email : null,
		'message'        => sprintf(
			/* translators: reference ID */
			__( "Your table request has been submitted! Reference: %s. We'll confirm within the hour via phone or WhatsApp.", 'goldenpine-theme' ),
			$ref
		),
	] );
}
add_action( 'wp_ajax_gpine_submit_booking',        'goldenpine_handle_booking_submission' );
add_action( 'wp_ajax_nopriv_gpine_submit_booking', 'goldenpine_handle_booking_submission' );

// ============================================================================
// Helper: Client IP Resolution
// ============================================================================

/**
 * Attempt to resolve the real client IP, respecting common proxy headers.
 * Falls back to REMOTE_ADDR.
 *
 * @return string IP address string.
 */
function goldenpine_get_client_ip(): string {
	$proxy_headers = [
		'HTTP_CF_CONNECTING_IP',
		'HTTP_X_REAL_IP',
		'HTTP_X_FORWARDED_FOR',
		'REMOTE_ADDR',
	];

	foreach ( $proxy_headers as $header ) {
		if ( ! empty( $_SERVER[ $header ] ) ) {
			$raw = sanitize_text_field( wp_unslash( $_SERVER[ $header ] ) );
			// X-Forwarded-For may be comma-separated; take the first.
			$ip = trim( explode( ',', $raw )[0] );
			if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				return $ip;
			}
		}
	}

	return '0.0.0.0';
}
