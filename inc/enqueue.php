<?php
/**
 * Goldenpine Theme — inc/enqueue.php
 *
 * All wp_enqueue_scripts and wp_enqueue_style calls live here.
 * Scripts and stylesheets are version-stamped with GOLDENPINE_VERSION
 * so browsers fetch fresh assets after a theme update.
 *
 * Conditional loading pattern:
 *   if ( is_front_page() ) { wp_enqueue_script( 'goldenpine-front-page' ); }
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue theme styles and scripts on the front end.
 */
function goldenpine_enqueue_assets(): void {

    $ver = GOLDENPINE_VERSION;
    $uri = GOLDENPINE_URI;

    // -----------------------------------------------------------------------
    // Styles
    // -----------------------------------------------------------------------

    // 1. CSS custom properties / variables
    wp_enqueue_style( 'goldenpine-variables',  $uri . '/assets/css/base/_variables.css',  [],           $ver );

    // 2. Base
    wp_enqueue_style( 'goldenpine-global',     $uri . '/assets/css/base/_global.css',     [ 'goldenpine-variables' ], $ver );
    wp_enqueue_style( 'goldenpine-typography', $uri . '/assets/css/base/_typography.css', [ 'goldenpine-variables' ], $ver );
    wp_enqueue_style( 'goldenpine-utilities',  $uri . '/assets/css/base/_utilities.css',  [ 'goldenpine-variables' ], $ver );

    // 3. Layout
    wp_enqueue_style( 'goldenpine-grid',       $uri . '/assets/css/layout/_grid.css',       [ 'goldenpine-variables' ], $ver );
    wp_enqueue_style( 'goldenpine-header-css', $uri . '/assets/css/layout/_header.css',     [ 'goldenpine-variables' ], $ver );

    wp_enqueue_style( 'goldenpine-footer-css', $uri . '/assets/css/layout/_footer.css',     [ 'goldenpine-variables' ], $ver );
    wp_enqueue_style( 'goldenpine-nav-css',    $uri . '/assets/css/layout/_navigation.css', [ 'goldenpine-variables' ], $ver );
    wp_enqueue_style( 'goldenpine-sidebar',    $uri . '/assets/css/layout/_sidebar.css',    [ 'goldenpine-variables' ], $ver );

    // 4. Components
    wp_enqueue_style( 'goldenpine-buttons',  $uri . '/assets/css/components/_buttons.css', [ 'goldenpine-variables' ], $ver );
    wp_enqueue_style( 'goldenpine-cards',    $uri . '/assets/css/components/_cards.css',   [ 'goldenpine-variables' ], $ver );
    wp_enqueue_style( 'goldenpine-forms',    $uri . '/assets/css/components/_forms.css',   [ 'goldenpine-variables' ], $ver );
    wp_enqueue_style( 'goldenpine-modals',   $uri . '/assets/css/components/_modals.css',  [ 'goldenpine-variables' ], $ver );
    wp_enqueue_style( 'goldenpine-alerts',   $uri . '/assets/css/components/_alerts.css',  [ 'goldenpine-variables' ], $ver );

    // -----------------------------------------------------------------------
    // Scripts — common (every page)
    // -----------------------------------------------------------------------
    wp_enqueue_script(
        'goldenpine-utils',
        $uri . '/assets/js/common/utils.js',
        [],
        $ver,
        true
    );

    wp_enqueue_script(
        'goldenpine-navigation',
        $uri . '/assets/js/common/navigation.js',
        [ 'goldenpine-utils' ],
        $ver,
        true
    );

    wp_enqueue_script(
        'goldenpine-main',
        $uri . '/assets/js/common/main.js',
        [ 'goldenpine-utils', 'goldenpine-navigation' ],
        $ver,
        true
    );

    // -----------------------------------------------------------------------
    // Scripts — page-specific (conditional)
    // -----------------------------------------------------------------------
    if ( is_front_page() ) {
        wp_enqueue_script(
            'goldenpine-front-page',
            $uri . '/assets/js/page-specific-js/front-page.js',
            [ 'goldenpine-utils' ],
            $ver,
            true
        );
    }

    if ( is_page( 'about' ) ) {
        wp_enqueue_script(
            'goldenpine-about',
            $uri . '/assets/js/page-specific-js/about.js',
            [ 'goldenpine-utils' ],
            $ver,
            true
        );
    }

    if ( is_page( 'services' ) ) {
        wp_enqueue_script(
            'goldenpine-services',
            $uri . '/assets/js/page-specific-js/services.js',
            [ 'goldenpine-utils' ],
            $ver,
            true
        );
    }

    if ( is_page( 'contact' ) ) {
        wp_enqueue_script(
            'goldenpine-contact',
            $uri . '/assets/js/page-specific-js/contact.js',
            [ 'goldenpine-utils' ],
            $ver,
            true
        );

        // Localise AJAX vars for the contact form.
        wp_localize_script(
            'goldenpine-contact',
            'goldenpineContactVars',
            [
                'ajaxUrl'  => esc_url( admin_url( 'admin-ajax.php' ) ),
                'nonce'    => wp_create_nonce( 'goldenpine_contact_nonce' ),
                'sending'  => esc_html__( 'Sending…',                  'goldenpine-theme' ),
                'errorMsg' => esc_html__( 'An error occurred. Please try again.', 'goldenpine-theme' ),
            ]
        );
    }
}
add_action( 'wp_enqueue_scripts', 'goldenpine_enqueue_assets' );

/**
 * Enqueue Customizer live-preview script.
 */
function goldenpine_customizer_preview_scripts(): void {
    wp_enqueue_script(
        'goldenpine-customizer-preview',
        GOLDENPINE_URI . '/assets/js/customizer-js/customizer-preview.js',
        [ 'customize-preview', 'jquery' ],
        GOLDENPINE_VERSION,
        true
    );
}
add_action( 'customize_preview_init', 'goldenpine_customizer_preview_scripts' );

/**
 * Enqueue admin-specific styles.
 */
function goldenpine_admin_styles(): void {
    // Uncomment and create the file if you need custom admin CSS.
    // wp_enqueue_style( 'goldenpine-admin', GOLDENPINE_URI . '/assets/css/admin.css', [], GOLDENPINE_VERSION );
}
add_action( 'admin_enqueue_scripts', 'goldenpine_admin_styles' );
