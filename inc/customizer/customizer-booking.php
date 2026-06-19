<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-booking.php
 *
 * Registers all WordPress Customizer settings and controls for the
 * Booking page template.
 *
 * Panel: Booking Page
 *   Section: Hero
 *     - Background image
 *     - Label text
 *     - Heading line 1
 *     - Heading line 2 (gold)
 *   Section: Form Section
 *     - Intro paragraph
 *     - Feature badge labels (×4)
 *   Section: Sidebar
 *     - Gallery images (×4)
 *     - Phone label
 *     - Phone availability subtext
 *     - Message (Messenger) link
 *
 * Note: Phone number is shared from the Footer panel setting
 *       (`goldenpine_footer_phone`) — change once, updates everywhere.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Booking page Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 */
function goldenpine_booking_customizer( WP_Customize_Manager $wp_customize ): void {

	// ── Panel ──────────────────────────────────────────────────────────────────
	$wp_customize->add_panel( 'goldenpine_booking_panel', [
		'title'    => __( 'Booking Page', 'goldenpine-theme' ),
		'priority' => 140,
	] );

	// ============================================================================
	// SECTION 1: Hero
	// ============================================================================
	$wp_customize->add_section( 'goldenpine_booking_hero', [
		'title'    => __( 'Hero Section', 'goldenpine-theme' ),
		'panel'    => 'goldenpine_booking_panel',
		'priority' => 10,
	] );

	// Background image.
	$wp_customize->add_setting( 'goldenpine_booking_hero_image', [
		'default'           => 0,
		'sanitize_callback' => 'absint',
	] );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'goldenpine_booking_hero_image', [
		'label'     => __( 'Background Image', 'goldenpine-theme' ),
		'section'   => 'goldenpine_booking_hero',
		'mime_type' => 'image',
	] ) );

	// Label text.
	$wp_customize->add_setting( 'goldenpine_booking_hero_label', [
		'default'           => 'Reservations',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'goldenpine_booking_hero_label', [
		'label'   => __( 'Label (above heading)', 'goldenpine-theme' ),
		'section' => 'goldenpine_booking_hero',
		'type'    => 'text',
	] );

	// Heading line 1.
	$wp_customize->add_setting( 'goldenpine_booking_hero_heading_1', [
		'default'           => 'Book your',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'goldenpine_booking_hero_heading_1', [
		'label'   => __( 'Heading Line 1', 'goldenpine-theme' ),
		'section' => 'goldenpine_booking_hero',
		'type'    => 'text',
	] );

	// Heading line 2 (renders in gold).
	$wp_customize->add_setting( 'goldenpine_booking_hero_heading_2', [
		'default'           => 'table.',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'goldenpine_booking_hero_heading_2', [
		'label'       => __( 'Heading Line 2 (gold)', 'goldenpine-theme' ),
		'section'     => 'goldenpine_booking_hero',
		'type'        => 'text',
	] );

	// ============================================================================
	// SECTION 2: Form Section
	// ============================================================================
	$wp_customize->add_section( 'goldenpine_booking_form_section', [
		'title'    => __( 'Form Section', 'goldenpine-theme' ),
		'panel'    => 'goldenpine_booking_panel',
		'priority' => 20,
	] );

	// Intro paragraph.
	$wp_customize->add_setting( 'goldenpine_booking_form_intro', [
		'default'           => "Fill in your details and our team will confirm your table via phone or WhatsApp within the hour. No deposit required.",
		'sanitize_callback' => 'sanitize_textarea_field',
	] );
	$wp_customize->add_control( 'goldenpine_booking_form_intro', [
		'label'   => __( 'Intro Paragraph', 'goldenpine-theme' ),
		'section' => 'goldenpine_booking_form_section',
		'type'    => 'textarea',
	] );

	// Feature badges.
	$badge_defaults = [
		1 => 'Free reservation',
		2 => 'Open 7 nights',
		3 => 'Groups welcome',
		4 => 'Fast response',
	];

	foreach ( $badge_defaults as $num => $default ) {
		$wp_customize->add_setting( "goldenpine_booking_badge_{$num}", [
			'default'           => $default,
			'sanitize_callback' => 'sanitize_text_field',
		] );
		$wp_customize->add_control( "goldenpine_booking_badge_{$num}", [
			/* translators: badge number */
			'label'   => sprintf( __( 'Feature Badge %d', 'goldenpine-theme' ), $num ),
			'section' => 'goldenpine_booking_form_section',
			'type'    => 'text',
		] );
	}

	// ============================================================================
	// SECTION 3: Sidebar
	// ============================================================================
	$wp_customize->add_section( 'goldenpine_booking_sidebar', [
		'title'    => __( 'Sidebar', 'goldenpine-theme' ),
		'panel'    => 'goldenpine_booking_panel',
		'priority' => 30,
	] );

	// Gallery images.
	for ( $i = 1; $i <= 4; $i++ ) {
		$wp_customize->add_setting( "goldenpine_booking_gallery_image_{$i}", [
			'default'           => 0,
			'sanitize_callback' => 'absint',
		] );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, "goldenpine_booking_gallery_image_{$i}", [
			/* translators: image number */
			'label'     => sprintf( __( 'Gallery Image %d', 'goldenpine-theme' ), $i ),
			'section'   => 'goldenpine_booking_sidebar',
			'mime_type' => 'image',
		] ) );
	}

	// Phone section label.
	$wp_customize->add_setting( 'goldenpine_booking_phone_label', [
		'default'           => 'Prefer to call?',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'goldenpine_booking_phone_label', [
		'label'       => __( 'Phone Card Label', 'goldenpine-theme' ),
		'description' => __( 'The phone number itself is shared from the Footer settings.', 'goldenpine-theme' ),
		'section'     => 'goldenpine_booking_sidebar',
		'type'        => 'text',
	] );

	// Phone availability subtext.
	$wp_customize->add_setting( 'goldenpine_booking_phone_subtext', [
		'default'           => 'Available every day from 4 PM. WhatsApp and Messenger too.',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'goldenpine_booking_phone_subtext', [
		'label'   => __( 'Phone Card Subtext', 'goldenpine-theme' ),
		'section' => 'goldenpine_booking_sidebar',
		'type'    => 'text',
	] );

	// Messenger / WhatsApp link.
	$wp_customize->add_setting( 'goldenpine_booking_message_link', [
		'default'           => 'https://m.me/goldenpinepub',
		'sanitize_callback' => 'esc_url_raw',
	] );
	$wp_customize->add_control( 'goldenpine_booking_message_link', [
		'label'   => __( 'Message Button URL (Messenger / WhatsApp)', 'goldenpine-theme' ),
		'section' => 'goldenpine_booking_sidebar',
		'type'    => 'url',
	] );
}
add_action( 'customize_register', 'goldenpine_booking_customizer' );
