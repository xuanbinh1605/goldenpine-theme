<?php
/**
 * Goldenpine Theme — functions.php
 *
 * Entry point for all theme includes. Keep this file lean: it only
 * requires individual files from inc/ so that each concern is isolated
 * and easy to find, edit, or swap without touching anything else.
 *
 * Load order:
 *  1. Setup     — theme supports, image sizes, nav menus
 *  2. Enqueue   — scripts and styles
 *  3. Helpers   — reusable utility functions
 *  4. Admin     — dashboard-only customisations
 *  5. Post Types & Taxonomies — custom content types
 *  6. Customizer — Customizer panels, sections, settings
 *  7. AJAX      — front-end AJAX handlers
 *  8. Integrations — third-party plugin hooks
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Define theme constants for convenience.
// ---------------------------------------------------------------------------
define( 'GOLDENPINE_VERSION', '1.0.3' );
define( 'GOLDENPINE_DIR',     get_template_directory() );
define( 'GOLDENPINE_URI',     get_template_directory_uri() );

/**
 * Safely require a file and throw a descriptive error if missing.
 *
 * @param string $relative_path Path relative to the theme root.
 */
function goldenpine_require( string $relative_path ): void {
    $absolute = GOLDENPINE_DIR . '/' . ltrim( $relative_path, '/' );
    if ( file_exists( $absolute ) ) {
        require_once $absolute;
    } else {
        /* translators: %s: file path */
        wp_die( sprintf( esc_html__( 'Goldenpine Theme: required file not found — %s', 'goldenpine-theme' ), esc_html( $absolute ) ) );
    }
}

// ---------------------------------------------------------------------------
// 1. Core setup.
// ---------------------------------------------------------------------------
goldenpine_require( 'inc/setup.php' );
goldenpine_require( 'inc/enqueue.php' );

// ---------------------------------------------------------------------------
// 2. Admin (back-end only).
// ---------------------------------------------------------------------------
if ( is_admin() ) {
    goldenpine_require( 'inc/admin/admin-settings.php' );
}

// ---------------------------------------------------------------------------
// 3. Post Types, Taxonomies & Meta Boxes.
// ---------------------------------------------------------------------------
goldenpine_require( 'inc/post-types/video.php' );
goldenpine_require( 'inc/post-types/marquee.php' );
goldenpine_require( 'inc/meta-boxes/video-gallery.php' );

goldenpine_require( 'inc/post-types/event.php' );
goldenpine_require( 'inc/taxonomies/event-type.php' );
goldenpine_require( 'inc/meta-boxes/event-meta.php' );

// ---------------------------------------------------------------------------
// 4. Customizer.
// ---------------------------------------------------------------------------
goldenpine_require( 'inc/customizer/customizer-setup.php' );
goldenpine_require( 'inc/customizer/customizer-about.php' );
goldenpine_require( 'inc/customizer/customizer-the-space.php' );
goldenpine_require( 'inc/customizer/customizer-reservation.php' );
goldenpine_require( 'inc/customizer/customizer-events.php' );
goldenpine_require( 'inc/customizer/customizer-about-page.php' );
goldenpine_require( 'inc/customizer/customizer-contact.php' );
goldenpine_require( 'inc/customizer/customizer-booking.php' );
goldenpine_require( 'inc/customizer/customizer-front-events.php' );

// ---------------------------------------------------------------------------
// 5. Booking — CPT, AJAX handler, email functions.
// ---------------------------------------------------------------------------
goldenpine_require( 'inc/admin/booking-submissions.php' );
goldenpine_require( 'inc/email/booking-emails.php' );
goldenpine_require( 'inc/ajax/booking-ajax.php' );
