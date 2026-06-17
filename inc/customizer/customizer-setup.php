<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-setup.php
 *
 * Bootstrap the Customizer: register the main panel and global settings
 * that don't belong to a specific section file.
 *
 * Section-specific files:
 *   - customizer-colors.php
 *   - customizer-typography.php
 *   - customizer-home.php
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register all Customizer panels, sections, settings, and controls.
 *
 * Panel structure:
 *   Theme Options
 *   ├── Header Settings  → goldenpine_header_settings
 *   └── Footer Settings  → goldenpine_footer_settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function goldenpine_customizer_register( WP_Customize_Manager $wp_customize ): void {

    // ===================================================================
    // MAIN PANEL — Theme Options
    // ===================================================================
    $wp_customize->add_panel(
        'goldenpine_theme_options',
        [
            'title'       => esc_html__( 'Theme Options', 'goldenpine-theme' ),
            'description' => esc_html__( 'Manage header, footer, and global theme settings.', 'goldenpine-theme' ),
            'priority'    => 30,
        ]
    );

    // ===================================================================
    // SECTION: Header Settings
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_header_settings',
        [
            'title'    => esc_html__( 'Header Settings', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_theme_options',
            'priority' => 10,
        ]
    );

    // Header Logo — stores attachment ID.
    $wp_customize->add_setting(
        'goldenpine_header_logo',
        [
            'default'           => 0,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ]
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'goldenpine_header_logo',
            [
                'label'     => esc_html__( 'Header Logo', 'goldenpine-theme' ),
                'section'   => 'goldenpine_header_settings',
                'mime_type' => 'image',
            ]
        )
    );

    // ===================================================================
    // SECTION: Footer Settings
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_footer_settings',
        [
            'title'    => esc_html__( 'Footer Settings', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_theme_options',
            'priority' => 20,
        ]
    );

    // --- Footer Logo ---------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_footer_logo',
        [
            'default'           => 0,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ]
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'goldenpine_footer_logo',
            [
                'label'     => esc_html__( 'Footer Logo', 'goldenpine-theme' ),
                'section'   => 'goldenpine_footer_settings',
                'mime_type' => 'image',
            ]
        )
    );

    // --- Footer Description --------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_footer_description',
        [
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_textarea_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_footer_description',
        [
            'label'   => esc_html__( 'Footer Description', 'goldenpine-theme' ),
            'section' => 'goldenpine_footer_settings',
            'type'    => 'textarea',
        ]
    );

    // --- Address -------------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_footer_address',
        [
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_textarea_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_footer_address',
        [
            'label'   => esc_html__( 'Address', 'goldenpine-theme' ),
            'section' => 'goldenpine_footer_settings',
            'type'    => 'textarea',
        ]
    );

    // --- Opening Hours -------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_footer_hours',
        [
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_footer_hours',
        [
            'label'       => esc_html__( 'Opening Hours', 'goldenpine-theme' ),
            'description' => esc_html__( 'Regular hours text (e.g., "Daily")', 'goldenpine-theme' ),
            'section'     => 'goldenpine_footer_settings',
            'type'        => 'text',
        ]
    );

    // --- Opening Hours Highlight ---------------------------------------
    $wp_customize->add_setting(
        'goldenpine_footer_hours_highlight',
        [
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_footer_hours_highlight',
        [
            'label'       => esc_html__( 'Opening Hours Highlight', 'goldenpine-theme' ),
            'description' => esc_html__( 'Highlighted hours text (e.g., "5 PM — 2 AM") - displays in gold', 'goldenpine-theme' ),
            'section'     => 'goldenpine_footer_settings',
            'type'        => 'text',
        ]
    );

    // --- Reservations Phone --------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_footer_phone',
        [
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_footer_phone',
        [
            'label'   => esc_html__( 'Reservations Phone', 'goldenpine-theme' ),
            'section' => 'goldenpine_footer_settings',
            'type'    => 'text',
        ]
    );

    // --- Reservations Email --------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_footer_email',
        [
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_email',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_footer_email',
        [
            'label'   => esc_html__( 'Reservations Email', 'goldenpine-theme' ),
            'section' => 'goldenpine_footer_settings',
            'type'    => 'email',
        ]
    );

    // ===================================================================
    // Social Media URL fields
    // ===================================================================
    $social_fields = [
        'goldenpine_social_instagram' => esc_html__( 'Instagram URL',   'goldenpine-theme' ),
        'goldenpine_social_facebook'  => esc_html__( 'Facebook URL',    'goldenpine-theme' ),
        'goldenpine_social_tiktok'    => esc_html__( 'TikTok URL',      'goldenpine-theme' ),
    ];

    foreach ( $social_fields as $setting_id => $label ) {
        $wp_customize->add_setting(
            $setting_id,
            [
                'default'           => '',
                'transport'         => 'refresh',
                'sanitize_callback' => 'esc_url_raw',
            ]
        );

        $wp_customize->add_control(
            $setting_id,
            [
                'label'   => $label,
                'section' => 'goldenpine_footer_settings',
                'type'    => 'url',
            ]
        );
    }

    // ===================================================================
    // Site Identity — transport tweaks (built-in section)
    // ===================================================================
    $wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            [
                'selector'        => '.site-name',
                'render_callback' => fn() => bloginfo( 'name' ),
            ]
        );

        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            [
                'selector'        => '.site-description',
                'render_callback' => fn() => bloginfo( 'description' ),
            ]
        );
    }
}
add_action( 'customize_register', 'goldenpine_customizer_register' );

