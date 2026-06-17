<?php
/**
 * Goldenpine Theme — inc/setup.php
 *
 * Theme setup: register support for WordPress features, declare image sizes,
 * register navigation menus, and register widget areas.
 * Called from functions.php on the 'after_setup_theme' hook.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Theme setup — runs once after the active theme is loaded.
// ---------------------------------------------------------------------------
function goldenpine_setup(): void {

    // Let WordPress manage the <title> tag automatically.
    add_theme_support( 'title-tag' );

    // Enable featured images on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Clean HTML5 markup for core elements.
    add_theme_support(
        'html5',
        [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ]
    );

    // Built-in WordPress custom logo (fallback for header/footer logos).
    add_theme_support(
        'custom-logo',
        [
            'height'      => 80,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
        ]
    );

    // Gutenberg wide/full alignment and responsive embeds.
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );

    // Load theme text domain for translations.
    load_theme_textdomain( 'goldenpine-theme', GOLDENPINE_DIR . '/languages' );

    // -----------------------------------------------------------------------
    // Navigation menu locations.
    // -----------------------------------------------------------------------
    register_nav_menus(
        [
            'header'  => esc_html__( 'Header', 'goldenpine-theme' ),
            'explore' => esc_html__( 'Explore', 'goldenpine-theme' ),
        ]
    );
}
add_action( 'after_setup_theme', 'goldenpine_setup' );

// ---------------------------------------------------------------------------
// Custom image sizes.
// ---------------------------------------------------------------------------
function goldenpine_register_image_sizes(): void {
    add_image_size( 'goldenpine-hero',      1920, 900, true );
    add_image_size( 'goldenpine-card',       600, 400, true );
    add_image_size( 'goldenpine-thumbnail',  400, 300, true );
}
add_action( 'after_setup_theme', 'goldenpine_register_image_sizes' );

// ---------------------------------------------------------------------------
// Widget areas (sidebars).
// ---------------------------------------------------------------------------
function goldenpine_widgets_init(): void {

    register_sidebar(
        [
            'name'          => esc_html__( 'Primary Sidebar', 'goldenpine-theme' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Widgets in the primary sidebar.', 'goldenpine-theme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]
    );

    register_sidebar(
        [
            'name'          => esc_html__( 'Footer Widget Area', 'goldenpine-theme' ),
            'id'            => 'footer-widgets',
            'description'   => esc_html__( 'Widgets in the footer area.', 'goldenpine-theme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]
    );
}
add_action( 'widgets_init', 'goldenpine_widgets_init' );
