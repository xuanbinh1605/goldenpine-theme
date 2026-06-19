<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-contact.php
 *
 * Customizer settings for the Contact page (page-contact.php).
 * Organised into four sections under a "Contact Page" panel:
 *
 *  1. Hero          — background image, label, heading
 *  2. Contact Info  — CTA card, location, reach us, open hours, map
 *  3. CTA Section   — background image, heading, buttons
 *
 * Shared settings (phone, email, social URLs) are read from
 * Theme Options > Footer Settings.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Contact Page Customizer panel, sections, settings, and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function goldenpine_customizer_register_contact( WP_Customize_Manager $wp_customize ): void {

    // ===================================================================
    // PANEL — Contact Page
    // ===================================================================
    $wp_customize->add_panel(
        'goldenpine_contact_page',
        [
            'title'       => esc_html__( 'Contact Page', 'goldenpine-theme' ),
            'description' => esc_html__( 'Manage content for the Contact page template.', 'goldenpine-theme' ),
            'priority'    => 28,
        ]
    );

    // ===================================================================
    // SECTION 1: Hero
    // ===================================================================
    $wp_customize->add_section( 'goldenpine_contact_hero_section', [
        'title'    => esc_html__( 'Hero Section', 'goldenpine-theme' ),
        'panel'    => 'goldenpine_contact_page',
        'priority' => 10,
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_hero_image', [ 'default' => 0, 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'goldenpine_contact_hero_image', [
        'label'     => esc_html__( 'Background Image', 'goldenpine-theme' ),
        'section'   => 'goldenpine_contact_hero_section',
        'mime_type' => 'image',
    ] ) );

    $wp_customize->add_setting( 'goldenpine_contact_hero_label', [ 'default' => 'Contact', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_hero_label', [
        'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_hero_section',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_hero_h1', [ 'default' => 'Find us.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_hero_h1', [
        'label'   => esc_html__( 'Heading Line 1', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_hero_section',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_hero_h2', [ 'default' => 'Join the night.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_hero_h2', [
        'label'       => esc_html__( 'Heading Line 2 (Gold)', 'goldenpine-theme' ),
        'section'     => 'goldenpine_contact_hero_section',
        'type'        => 'text',
    ] );

    // ===================================================================
    // SECTION 2: Contact Info
    // ===================================================================
    $wp_customize->add_section( 'goldenpine_contact_info_section', [
        'title'    => esc_html__( 'Contact Info', 'goldenpine-theme' ),
        'panel'    => 'goldenpine_contact_page',
        'priority' => 20,
    ] );

    // --- CTA Card ---
    $wp_customize->add_setting( 'goldenpine_contact_cta_heading', [ 'default' => 'Ready when you are.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_cta_heading', [
        'label'   => esc_html__( 'CTA Card Heading', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_cta_book_text', [ 'default' => 'Book A Table', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_cta_book_text', [
        'label'   => esc_html__( 'Book Button Text', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_cta_book_link', [ 'default' => '/booking', 'sanitize_callback' => 'esc_url_raw' ] );
    $wp_customize->add_control( 'goldenpine_contact_cta_book_link', [
        'label'   => esc_html__( 'Book Button Link', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'url',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_cta_call_text', [ 'default' => 'Call Now', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_cta_call_text', [
        'label'       => esc_html__( 'Call Button Text', 'goldenpine-theme' ),
        'description' => esc_html__( 'Phone managed under Theme Options → Footer Settings.', 'goldenpine-theme' ),
        'section'     => 'goldenpine_contact_info_section',
        'type'        => 'text',
    ] );

    // --- Location Card ---
    $wp_customize->add_setting( 'goldenpine_contact_venue_name', [ 'default' => 'Golden Pine Pub', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_venue_name', [
        'label'   => esc_html__( 'Venue Name', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_venue_address', [ 'default' => "296 Lê Duẩn, Hải Châu 1\nHải Châu, Đà Nẵng, Vietnam", 'sanitize_callback' => 'sanitize_textarea_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_venue_address', [
        'label'   => esc_html__( 'Venue Address', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'textarea',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_directions_url', [ 'default' => 'https://maps.google.com/?q=296+Le+Duan+Da+Nang', 'sanitize_callback' => 'esc_url_raw' ] );
    $wp_customize->add_control( 'goldenpine_contact_directions_url', [
        'label'   => esc_html__( 'Directions URL (Google Maps link)', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'url',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_directions_text', [ 'default' => 'Get Directions', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_directions_text', [
        'label'   => esc_html__( 'Directions Button Text', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'text',
    ] );

    // --- Reach Us: Zalo (phone & email shared from footer) ---
    $wp_customize->add_setting( 'goldenpine_contact_zalo', [ 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_zalo', [
        'label'       => esc_html__( 'Zalo Number', 'goldenpine-theme' ),
        'description' => esc_html__( 'Displayed in the Reach Us card. Phone and email managed under Footer Settings.', 'goldenpine-theme' ),
        'section'     => 'goldenpine_contact_info_section',
        'type'        => 'text',
    ] );

    // --- Open Hours (single row) ---
    $wp_customize->add_setting( 'goldenpine_contact_hours_label', [
        'default'           => 'Mon – Sun',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'goldenpine_contact_hours_label', [
        'label'   => esc_html__( 'Open Hours — Day Label', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_hours_time', [
        'default'           => '9 PM – 2 AM',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'goldenpine_contact_hours_time', [
        'label'   => esc_html__( 'Open Hours — Time', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'text',
    ] );

    // --- Map — address typed by user, embed URL generated automatically ---
    $wp_customize->add_setting( 'goldenpine_contact_map_query', [
        'default'           => '296 Le Duan, Hai Chau, Da Nang, Vietnam',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'goldenpine_contact_map_query', [
        'label'       => esc_html__( 'Map Address', 'goldenpine-theme' ),
        'description' => esc_html__( 'Type the venue address. Google Maps will be embedded automatically.', 'goldenpine-theme' ),
        'section'     => 'goldenpine_contact_info_section',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_map_name', [ 'default' => 'Golden Pine Pub', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_map_name', [
        'label'   => esc_html__( 'Map Overlay — Venue Name', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_map_address', [ 'default' => '296 Lê Duẩn · Hải Châu · Đà Nẵng', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_map_address', [
        'label'   => esc_html__( 'Map Overlay — Short Address', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_info_section',
        'type'    => 'text',
    ] );

    // ===================================================================
    // SECTION 3: CTA Section
    // ===================================================================
    $wp_customize->add_section( 'goldenpine_contact_cta2_section', [
        'title'    => esc_html__( 'CTA Section', 'goldenpine-theme' ),
        'panel'    => 'goldenpine_contact_page',
        'priority' => 30,
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_cta2_image', [ 'default' => 0, 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'goldenpine_contact_cta2_image', [
        'label'     => esc_html__( 'Background Image', 'goldenpine-theme' ),
        'section'   => 'goldenpine_contact_cta2_section',
        'mime_type' => 'image',
    ] ) );

    $wp_customize->add_setting( 'goldenpine_contact_cta2_label', [ 'default' => 'Every Night Is An Event', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_cta2_label', [
        'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_cta2_section',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_cta2_heading_1', [ 'default' => "Da Nang's most", 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_cta2_heading_1', [
        'label'   => esc_html__( 'Heading Line 1', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_cta2_section',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_cta2_heading_2', [ 'default' => 'talked about night.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_cta2_heading_2', [
        'label'       => esc_html__( 'Heading Line 2 (Gold)', 'goldenpine-theme' ),
        'section'     => 'goldenpine_contact_cta2_section',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_cta2_book_text', [ 'default' => 'Book A Table', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_cta2_book_text', [
        'label'   => esc_html__( 'Book Button Text', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_cta2_section',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_cta2_book_link', [ 'default' => '/booking', 'sanitize_callback' => 'esc_url_raw' ] );
    $wp_customize->add_control( 'goldenpine_contact_cta2_book_link', [
        'label'   => esc_html__( 'Book Button Link', 'goldenpine-theme' ),
        'section' => 'goldenpine_contact_cta2_section',
        'type'    => 'url',
    ] );

    $wp_customize->add_setting( 'goldenpine_contact_cta2_call_text', [ 'default' => 'Call Now', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_contact_cta2_call_text', [
        'label'       => esc_html__( 'Call Button Text', 'goldenpine-theme' ),
        'description' => esc_html__( 'Phone managed under Theme Options → Footer Settings.', 'goldenpine-theme' ),
        'section'     => 'goldenpine_contact_cta2_section',
        'type'        => 'text',
    ] );
}
add_action( 'customize_register', 'goldenpine_customizer_register_contact' );
