<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-reservation.php
 *
 * Customizer settings for the Reservation section on the front page.
 * Manages background image, section label, headings, description,
 * and CTA button text/link.
 *
 * The background image is stored as an attachment ID. If no image is
 * set, the image overlay is simply not rendered.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Reservation section customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function goldenpine_customizer_register_reservation( WP_Customize_Manager $wp_customize ): void {

    // Ensure the Front Page panel exists (registered in customizer-about.php).
    if ( ! $wp_customize->get_panel( 'goldenpine_frontpage' ) ) {
        $wp_customize->add_panel(
            'goldenpine_frontpage',
            [
                'title'    => esc_html__( 'Front Page Settings', 'goldenpine-theme' ),
                'priority' => 25,
            ]
        );
    }

    // ===================================================================
    // SECTION: Reservation / Book
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_reservation_section',
        [
            'title'    => esc_html__( 'Reservation Section', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_frontpage',
            'priority' => 30,
        ]
    );

    // --- Background Image ---------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_reservation_bg_image',
        [
            'default'           => 0,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ]
    );
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'goldenpine_reservation_bg_image',
            [
                'label'       => esc_html__( 'Background Image', 'goldenpine-theme' ),
                'description' => esc_html__( 'If not set, a plain dark background is shown.', 'goldenpine-theme' ),
                'section'     => 'goldenpine_reservation_section',
                'mime_type'   => 'image',
            ]
        )
    );

    // --- Section Label ------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_reservation_label',
        [
            'default'           => 'Reservations',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_reservation_label',
        [
            'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
            'section' => 'goldenpine_reservation_section',
            'type'    => 'text',
        ]
    );

    // --- Main Heading Line 1 ------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_reservation_heading_1',
        [
            'default'           => 'Your table is',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_reservation_heading_1',
        [
            'label'   => esc_html__( 'Main Heading (Line 1)', 'goldenpine-theme' ),
            'section' => 'goldenpine_reservation_section',
            'type'    => 'text',
        ]
    );

    // --- Main Heading Line 2 (Gold) -----------------------------------
    $wp_customize->add_setting(
        'goldenpine_reservation_heading_2',
        [
            'default'           => 'waiting.',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_reservation_heading_2',
        [
            'label'       => esc_html__( 'Main Heading (Line 2 - Gold)', 'goldenpine-theme' ),
            'description' => esc_html__( 'Displayed in gold color.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_reservation_section',
            'type'        => 'text',
        ]
    );

    // --- Description --------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_reservation_description',
        [
            'default'           => "Secure your spot in seconds. We'll confirm within the hour.",
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_textarea_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_reservation_description',
        [
            'label'   => esc_html__( 'Description', 'goldenpine-theme' ),
            'section' => 'goldenpine_reservation_section',
            'type'    => 'textarea',
        ]
    );

    // --- Book CTA Button Text -----------------------------------------
    $wp_customize->add_setting(
        'goldenpine_reservation_book_text',
        [
            'default'           => 'Book A Table',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_reservation_book_text',
        [
            'label'   => esc_html__( 'Book Button Text', 'goldenpine-theme' ),
            'section' => 'goldenpine_reservation_section',
            'type'    => 'text',
        ]
    );

    // --- Book CTA Button Link -----------------------------------------
    $wp_customize->add_setting(
        'goldenpine_reservation_book_link',
        [
            'default'           => '/booking',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_reservation_book_link',
        [
            'label'   => esc_html__( 'Book Button Link', 'goldenpine-theme' ),
            'section' => 'goldenpine_reservation_section',
            'type'    => 'url',
        ]
    );

    // --- Call Now Button Text -----------------------------------------
    $wp_customize->add_setting(
        'goldenpine_reservation_call_text',
        [
            'default'           => 'Call Now',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_reservation_call_text',
        [
            'label'       => esc_html__( 'Call Button Text', 'goldenpine-theme' ),
            'description' => esc_html__( 'Phone number is managed under Theme Options → Footer Settings.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_reservation_section',
            'type'        => 'text',
        ]
    );
}
add_action( 'customize_register', 'goldenpine_customizer_register_reservation' );
