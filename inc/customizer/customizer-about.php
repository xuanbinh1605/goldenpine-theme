<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-about.php
 *
 * Customizer settings for the About section on the front page.
 * Manages the section background image only.
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

    $wp_customize->add_setting(
        'goldenpine_about_bg_image',
        [
            'default'           => 0,
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        ]
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'goldenpine_about_bg_image',
            [
                'label'       => esc_html__( 'Background Image', 'goldenpine-theme' ),
                'description' => esc_html__( 'If not set, the About section is hidden.', 'goldenpine-theme' ),
                'section'     => 'goldenpine_about_section',
                'mime_type'   => 'image',
            ]
        )
    );
}
add_action( 'customize_register', 'goldenpine_customizer_register_about' );
