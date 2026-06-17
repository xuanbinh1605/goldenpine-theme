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
 * Register the main Goldenpine Customizer panel.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function goldenpine_customizer_register( WP_Customize_Manager $wp_customize ): void {

    // -------------------------------------------------------------------
    // Main theme panel
    // -------------------------------------------------------------------
    $wp_customize->add_panel(
        'goldenpine_theme_panel',
        [
            'title'       => esc_html__( 'Goldenpine Theme', 'goldenpine-theme' ),
            'description' => esc_html__( 'Customise the Goldenpine theme appearance and content.', 'goldenpine-theme' ),
            'priority'    => 30,
        ]
    );

    // -------------------------------------------------------------------
    // Site Identity tweaks (built-in section — just extend it)
    // -------------------------------------------------------------------
    $wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

    // -------------------------------------------------------------------
    // Selective refresh for site title / tagline
    // -------------------------------------------------------------------
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
