<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-floating-social.php
 *
 * Customizer settings for the Floating Social Icons.
 * Allows editing URLs for Zalo, Messenger, and Phone.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Floating Social Icons Customizer section, settings, and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function goldenpine_customizer_register_floating_social( WP_Customize_Manager $wp_customize ): void {

    // ===================================================================
    // SECTION — Floating Social Icons
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_floating_social_section',
        [
            'title'       => esc_html__( 'Floating Social Icons', 'goldenpine-theme' ),
            'description' => esc_html__( 'Configure the URLs for the floating social media icons (Zalo, Messenger, Phone). Leave a field empty to hide that icon.', 'goldenpine-theme' ),
            'priority'    => 160,
        ]
    );

    // -------------------------------------------------------------------
    // 1. Zalo URL
    // -------------------------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_floating_zalo_url',
        [
            'default'           => 'https://zalo.me/0937239867',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_floating_zalo_url',
        [
            'label'       => esc_html__( 'Zalo URL', 'goldenpine-theme' ),
            'description' => esc_html__( 'Enter your Zalo chat URL (e.g., https://zalo.me/yourphone). Leave empty to hide.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_floating_social_section',
            'type'        => 'url',
            'priority'    => 10,
        ]
    );

    // -------------------------------------------------------------------
    // 2. Messenger URL
    // -------------------------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_floating_messenger_url',
        [
            'default'           => 'https://m.me/cataniauthentic2013/',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_floating_messenger_url',
        [
            'label'       => esc_html__( 'Messenger URL', 'goldenpine-theme' ),
            'description' => esc_html__( 'Enter your Facebook Messenger URL (e.g., https://m.me/yourpage). Leave empty to hide.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_floating_social_section',
            'type'        => 'url',
            'priority'    => 20,
        ]
    );

    // -------------------------------------------------------------------
    // 3. Phone Number
    // -------------------------------------------------------------------
    $wp_customize->add_setting(
        'goldenpine_floating_phone',
        [
            'default'           => '0935455667',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]
    );

    $wp_customize->add_control(
        'goldenpine_floating_phone',
        [
            'label'       => esc_html__( 'Phone Number', 'goldenpine-theme' ),
            'description' => esc_html__( 'Enter your phone number (e.g., 0935455667). Leave empty to hide.', 'goldenpine-theme' ),
            'section'     => 'goldenpine_floating_social_section',
            'type'        => 'text',
            'priority'    => 30,
        ]
    );
}
add_action( 'customize_register', 'goldenpine_customizer_register_floating_social' );
