<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-the-space.php
 *
 * Customizer settings for the Space section on the front page.
 * Manages section label, headings, 3 image cards (with titles), description,
 * and the social CTA card.
 *
 * Images are stored as attachment IDs. If no image is set, the card
 * image is simply not rendered.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register The Space section customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function goldenpine_customizer_register_the_space( WP_Customize_Manager $wp_customize ): void {

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
    // SECTION: The Space
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_space_section',
        [
            'title'    => esc_html__( 'The Space Section', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_frontpage',
            'priority' => 20,
        ]
    );

    // --- Section Label -------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_space_label',
        [
            'default'           => 'The Space',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_space_label',
        [
            'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
            'section' => 'goldenpine_space_section',
            'type'    => 'text',
        ]
    );

    // --- Main Heading Line 1 -------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_space_heading_1',
        [
            'default'           => 'Where the',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_space_heading_1',
        [
            'label'   => esc_html__( 'Main Heading (Line 1)', 'goldenpine-theme' ),
            'section' => 'goldenpine_space_section',
            'type'    => 'text',
        ]
    );

    // --- Main Heading Line 2 (Gold) ------------------------------------
    $wp_customize->add_setting(
        'goldenpine_space_heading_2',
        [
            'default'           => 'magic happens.',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_space_heading_2',
        [
            'label'       => esc_html__( 'Main Heading (Line 2 - Gold)', 'goldenpine-theme' ),
            'description' => esc_html__( 'Displayed in gold color.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_space_section',
            'type'        => 'text',
        ]
    );

    // --- Description ---------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_space_description',
        [
            'default'           => 'Lose yourself in the lights, the sound, the crowd — step inside Golden Pine Pub.',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_textarea_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_space_description',
        [
            'label'   => esc_html__( 'Description', 'goldenpine-theme' ),
            'section' => 'goldenpine_space_section',
            'type'    => 'textarea',
        ]
    );

    // ===================================================================
    // Card 1 — The Shows
    // ===================================================================
    $wp_customize->add_setting(
        'goldenpine_space_card1_image',
        [
            'default'           => 0,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ]
    );
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'goldenpine_space_card1_image',
            [
                'label'     => esc_html__( 'Card 1 — Image', 'goldenpine-theme' ),
                'section'   => 'goldenpine_space_section',
                'mime_type' => 'image',
            ]
        )
    );

    $wp_customize->add_setting(
        'goldenpine_space_card1_title',
        [
            'default'           => 'The Shows',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_space_card1_title',
        [
            'label'   => esc_html__( 'Card 1 — Title', 'goldenpine-theme' ),
            'section' => 'goldenpine_space_section',
            'type'    => 'text',
        ]
    );

    // ===================================================================
    // Card 2 — The Crowd
    // ===================================================================
    $wp_customize->add_setting(
        'goldenpine_space_card2_image',
        [
            'default'           => 0,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ]
    );
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'goldenpine_space_card2_image',
            [
                'label'     => esc_html__( 'Card 2 — Image', 'goldenpine-theme' ),
                'section'   => 'goldenpine_space_section',
                'mime_type' => 'image',
            ]
        )
    );

    $wp_customize->add_setting(
        'goldenpine_space_card2_title',
        [
            'default'           => 'The Crowd',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_space_card2_title',
        [
            'label'   => esc_html__( 'Card 2 — Title', 'goldenpine-theme' ),
            'section' => 'goldenpine_space_section',
            'type'    => 'text',
        ]
    );

    // ===================================================================
    // Card 3 — The Venue
    // ===================================================================
    $wp_customize->add_setting(
        'goldenpine_space_card3_image',
        [
            'default'           => 0,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ]
    );
    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'goldenpine_space_card3_image',
            [
                'label'     => esc_html__( 'Card 3 — Image', 'goldenpine-theme' ),
                'section'   => 'goldenpine_space_section',
                'mime_type' => 'image',
            ]
        )
    );

    $wp_customize->add_setting(
        'goldenpine_space_card3_title',
        [
            'default'           => 'The Venue',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_space_card3_title',
        [
            'label'   => esc_html__( 'Card 3 — Title', 'goldenpine-theme' ),
            'section' => 'goldenpine_space_section',
            'type'    => 'text',
        ]
    );

    // ===================================================================
    // Social CTA Card
    // ===================================================================
    $wp_customize->add_setting(
        'goldenpine_space_cta_label',
        [
            'default'           => 'See it live',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_space_cta_label',
        [
            'label'   => esc_html__( 'Social CTA — Eyebrow Label', 'goldenpine-theme' ),
            'section' => 'goldenpine_space_section',
            'type'    => 'text',
        ]
    );

    $wp_customize->add_setting(
        'goldenpine_space_cta_text',
        [
            'default'           => 'Follow us for tonight\'s highlights.',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );
    $wp_customize->add_control(
        'goldenpine_space_cta_text',
        [
            'label'       => esc_html__( 'Social CTA — Heading Text', 'goldenpine-theme' ),
            'description' => esc_html__( 'Instagram and Facebook URLs are managed under Theme Options → Footer Settings.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_space_section',
            'type'        => 'text',
        ]
    );
}
add_action( 'customize_register', 'goldenpine_customizer_register_the_space' );
