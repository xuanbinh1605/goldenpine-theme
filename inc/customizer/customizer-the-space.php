<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-the-space.php
 *
 * Customizer settings for the Space section on the front page.
 * Manages section label, headings, 6 image cards (with titles), description,
 * and the social CTA card.
 *
 * Images are stored as attachment IDs. If no image is set, the card
 * is not rendered.
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
    // Image Cards (6)
    // ===================================================================
    $card_titles = [ 'The Shows', 'The Crowd', 'The Venue', '', '', '' ];

    for ( $i = 1; $i <= 6; $i++ ) {
        $wp_customize->add_setting(
            "goldenpine_space_card{$i}_image",
            [
                'default'           => 0,
                'transport'         => 'refresh',
                'sanitize_callback' => 'absint',
            ]
        );
        $wp_customize->add_control(
            new WP_Customize_Media_Control(
                $wp_customize,
                "goldenpine_space_card{$i}_image",
                [
                    'label'     => sprintf( esc_html__( 'Card %d — Image', 'goldenpine-theme' ), $i ),
                    'section'   => 'goldenpine_space_section',
                    'mime_type' => 'image',
                ]
            )
        );

        $wp_customize->add_setting(
            "goldenpine_space_card{$i}_title",
            [
                'default'           => $card_titles[ $i - 1 ],
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
        $wp_customize->add_control(
            "goldenpine_space_card{$i}_title",
            [
                'label'   => sprintf( esc_html__( 'Card %d — Title', 'goldenpine-theme' ), $i ),
                'section' => 'goldenpine_space_section',
                'type'    => 'text',
            ]
        );
    }

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
