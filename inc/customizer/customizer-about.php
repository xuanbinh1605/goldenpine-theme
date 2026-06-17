<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-about.php
 *
 * Customizer settings for the About section on the front page.
 * Manages section heading, main headline, stat cards, description, and CTA button.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register About Section customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function goldenpine_customizer_register_about( WP_Customize_Manager $wp_customize ): void {

    // ===================================================================
    // PANEL: Front Page Settings
    // ===================================================================
    if ( ! $wp_customize->get_panel( 'goldenpine_frontpage' ) ) {
        $wp_customize->add_panel(
            'goldenpine_frontpage',
            [
                'title'       => esc_html__( 'Front Page Settings', 'goldenpine-theme' ),
                'description' => esc_html__( 'Customize content for the front page sections.', 'goldenpine-theme' ),
                'priority'    => 25,
            ]
        );
    }

    // ===================================================================
    // SECTION: About Section
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_about_section',
        [
            'title'    => esc_html__( 'About Section', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_frontpage',
            'priority' => 10,
        ]
    );

    // --- Section Label -------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_label',
        [
            'default'           => 'About the Club',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_label',
        [
            'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'text',
        ]
    );

    // --- Main Heading Line 1 -------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_heading_1',
        [
            'default'           => 'More than a bar.',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_heading_1',
        [
            'label'   => esc_html__( 'Main Heading (Line 1)', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'text',
        ]
    );

    // --- Main Heading Line 2 (Gold) ------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_heading_2',
        [
            'default'           => 'An unforgettable night.',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_heading_2',
        [
            'label'       => esc_html__( 'Main Heading (Line 2 - Gold)', 'goldenpine-theme' ),
            'description' => esc_html__( 'This line will be displayed in gold color.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_about_section',
            'type'        => 'text',
        ]
    );

    // ===================================================================
    // Stat Cards
    // ===================================================================

    // --- Stat Card 1: Number -------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_stat1_number',
        [
            'default'           => '50+',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_stat1_number',
        [
            'label'   => esc_html__( 'Stat Card 1 - Number', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'text',
        ]
    );

    // --- Stat Card 1: Text ---------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_stat1_text',
        [
            'default'           => 'Live events a year',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_stat1_text',
        [
            'label'   => esc_html__( 'Stat Card 1 - Text', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'text',
        ]
    );

    // --- Stat Card 2: Number -------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_stat2_number',
        [
            'default'           => '120',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_stat2_number',
        [
            'label'   => esc_html__( 'Stat Card 2 - Number', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'text',
        ]
    );

    // --- Stat Card 2: Text ---------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_stat2_text',
        [
            'default'           => 'Signature cocktails',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_stat2_text',
        [
            'label'   => esc_html__( 'Stat Card 2 - Text', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'text',
        ]
    );

    // --- Stat Card 3: Number -------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_stat3_number',
        [
            'default'           => '500+',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_stat3_number',
        [
            'label'   => esc_html__( 'Stat Card 3 - Number', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'text',
        ]
    );

    // --- Stat Card 3: Text ---------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_stat3_text',
        [
            'default'           => 'Guests every night',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_stat3_text',
        [
            'label'   => esc_html__( 'Stat Card 3 - Text', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'text',
        ]
    );

    // ===================================================================
    // Description & CTA
    // ===================================================================

    // --- Description ---------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_description',
        [
            'default'           => 'Premium cocktails, live shows, and the most energetic crowd in Da Nang — every single night.',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_textarea_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_description',
        [
            'label'   => esc_html__( 'Description Text', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'textarea',
        ]
    );

    // --- CTA Button Text -----------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_cta_text',
        [
            'default'           => 'Learn More',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_cta_text',
        [
            'label'   => esc_html__( 'CTA Button Text', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'text',
        ]
    );

    // --- CTA Button Link -----------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_about_cta_link',
        [
            'default'           => '/about',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_about_cta_link',
        [
            'label'   => esc_html__( 'CTA Button Link', 'goldenpine-theme' ),
            'section' => 'goldenpine_about_section',
            'type'    => 'url',
        ]
    );
}
add_action( 'customize_register', 'goldenpine_customizer_register_about' );
