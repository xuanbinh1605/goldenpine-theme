<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-events.php
 *
 * Customizer settings for the Events Archive page (archive-event.php).
 * Organised into three sections under an "Events Archive" panel:
 *
 *  1. Hero Section   — background image, section label, heading lines
 *  2. Events List    — main heading above the event cards
 *  3. CTA Section    — background image, heading, book button, call button
 *
 * Phone number for the Call Now button is shared from
 * Theme Options > Footer Settings (goldenpine_footer_phone).
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Events Archive Customizer panel, sections, settings, and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function goldenpine_customizer_register_events( WP_Customize_Manager $wp_customize ): void {

    // ===================================================================
    // PANEL — Events Archive
    // ===================================================================
    $wp_customize->add_panel(
        'goldenpine_events_archive',
        [
            'title'       => esc_html__( 'Events Archive', 'goldenpine-theme' ),
            'description' => esc_html__( 'Manage content on the Events listing page (/events/).', 'goldenpine-theme' ),
            'priority'    => 26,
        ]
    );

    // ===================================================================
    // SECTION 1: Hero
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_events_hero',
        [
            'title'    => esc_html__( 'Hero Section', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_events_archive',
            'priority' => 10,
        ]
    );

    // --- Background Image ---
    $wp_customize->add_setting(
        'goldenpine_events_hero_image',
        [
            'default'           => 0,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ]
    );
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'goldenpine_events_hero_image',
            [
                'label'       => esc_html__( 'Background Image', 'goldenpine-theme' ),
                'description' => esc_html__( 'Recommended: wide landscape image, min 1920×1080px.', 'goldenpine-theme' ),
                'section'     => 'goldenpine_events_hero',
                'mime_type'   => 'image',
            ]
        )
    );

    // --- Section Label ---
    $wp_customize->add_setting(
        'goldenpine_events_hero_label',
        [
            'default'           => 'Events',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_events_hero_label',
        [
            'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
            'section' => 'goldenpine_events_hero',
            'type'    => 'text',
        ]
    );

    // --- Heading Line 1 ---
    $wp_customize->add_setting(
        'goldenpine_events_hero_heading_1',
        [
            'default'           => "What's on",
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_events_hero_heading_1',
        [
            'label'   => esc_html__( 'Heading Line 1', 'goldenpine-theme' ),
            'section' => 'goldenpine_events_hero',
            'type'    => 'text',
        ]
    );

    // --- Heading Line 2 (Gold) ---
    $wp_customize->add_setting(
        'goldenpine_events_hero_heading_2',
        [
            'default'           => 'at Golden Pine.',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_events_hero_heading_2',
        [
            'label'       => esc_html__( 'Heading Line 2 (Gold)', 'goldenpine-theme' ),
            'description' => esc_html__( 'Displayed in gold color.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_events_hero',
            'type'        => 'text',
        ]
    );

    // ===================================================================
    // SECTION 2: Events List
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_events_list_section',
        [
            'title'    => esc_html__( 'Events List', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_events_archive',
            'priority' => 20,
        ]
    );

    // --- Main Heading ---
    $wp_customize->add_setting(
        'goldenpine_events_list_heading',
        [
            'default'           => 'Every night, another story.',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_events_list_heading',
        [
            'label'   => esc_html__( 'Main Heading', 'goldenpine-theme' ),
            'section' => 'goldenpine_events_list_section',
            'type'    => 'text',
        ]
    );

    // ===================================================================
    // SECTION 3: CTA
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_events_cta_section',
        [
            'title'    => esc_html__( 'CTA Section', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_events_archive',
            'priority' => 30,
        ]
    );

    // --- Background Image ---
    $wp_customize->add_setting(
        'goldenpine_events_cta_image',
        [
            'default'           => 0,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ]
    );
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'goldenpine_events_cta_image',
            [
                'label'       => esc_html__( 'Background Image', 'goldenpine-theme' ),
                'description' => esc_html__( 'Recommended: atmospheric / crowd photo.', 'goldenpine-theme' ),
                'section'     => 'goldenpine_events_cta_section',
                'mime_type'   => 'image',
            ]
        )
    );

    // --- Section Label ---
    $wp_customize->add_setting(
        'goldenpine_events_cta_label',
        [
            'default'           => 'Reserve',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_events_cta_label',
        [
            'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
            'section' => 'goldenpine_events_cta_section',
            'type'    => 'text',
        ]
    );

    // --- Heading Line 1 ---
    $wp_customize->add_setting(
        'goldenpine_events_cta_heading_1',
        [
            'default'           => "Don't miss",
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_events_cta_heading_1',
        [
            'label'   => esc_html__( 'Heading Line 1', 'goldenpine-theme' ),
            'section' => 'goldenpine_events_cta_section',
            'type'    => 'text',
        ]
    );

    // --- Heading Line 2 (Gold) ---
    $wp_customize->add_setting(
        'goldenpine_events_cta_heading_2',
        [
            'default'           => 'the night.',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_events_cta_heading_2',
        [
            'label'       => esc_html__( 'Heading Line 2 (Gold)', 'goldenpine-theme' ),
            'description' => esc_html__( 'Displayed in gold color.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_events_cta_section',
            'type'        => 'text',
        ]
    );

    // --- Book Button Text ---
    $wp_customize->add_setting(
        'goldenpine_events_cta_book_text',
        [
            'default'           => 'Book A Table',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_events_cta_book_text',
        [
            'label'   => esc_html__( 'Book Button Text', 'goldenpine-theme' ),
            'section' => 'goldenpine_events_cta_section',
            'type'    => 'text',
        ]
    );

    // --- Book Button Link ---
    $wp_customize->add_setting(
        'goldenpine_events_cta_book_link',
        [
            'default'           => '/booking',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_events_cta_book_link',
        [
            'label'   => esc_html__( 'Book Button Link', 'goldenpine-theme' ),
            'section' => 'goldenpine_events_cta_section',
            'type'    => 'url',
        ]
    );

    // --- Call Button Text ---
    $wp_customize->add_setting(
        'goldenpine_events_cta_call_text',
        [
            'default'           => 'Call Now',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_events_cta_call_text',
        [
            'label'       => esc_html__( 'Call Button Text', 'goldenpine-theme' ),
            'description' => esc_html__( 'Phone number is managed under Theme Options → Footer Settings.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_events_cta_section',
            'type'        => 'text',
        ]
    );
}
add_action( 'customize_register', 'goldenpine_customizer_register_events' );
