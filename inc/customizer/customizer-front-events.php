<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-front-events.php
 *
 * Customizer settings for the Events section on the front page.
 * Manages section label, main heading, and CTA button text.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Front Page Events Section customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function goldenpine_customizer_register_front_events( WP_Customize_Manager $wp_customize ): void {

	// ===================================================================
	// PANEL: Front Page Settings (reuse existing)
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
	// SECTION: Events Section
	// ===================================================================
	$wp_customize->add_section(
		'goldenpine_front_events_section',
		[
			'title'    => esc_html__( 'Events Section', 'goldenpine-theme' ),
			'panel'    => 'goldenpine_frontpage',
			'priority' => 60,
		]
	);

	// --- Section Label -------------------------------------------------
	$wp_customize->add_setting(
		'goldenpine_front_events_label',
		[
			'default'           => 'Tonight &amp; What\'s Next',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		]
	);

	$wp_customize->add_control(
		'goldenpine_front_events_label',
		[
			'label'       => esc_html__( 'Section Label', 'goldenpine-theme' ),
			'description' => esc_html__( 'Small label text above the main heading.', 'goldenpine-theme' ),
			'section'     => 'goldenpine_front_events_section',
			'type'        => 'text',
		]
	);

	// --- Main Heading Line 1 -------------------------------------------
	$wp_customize->add_setting(
		'goldenpine_front_events_heading_1',
		[
			'default'           => 'Tonight is',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		]
	);

	$wp_customize->add_control(
		'goldenpine_front_events_heading_1',
		[
			'label'       => esc_html__( 'Main Heading - Line 1', 'goldenpine-theme' ),
			'description' => esc_html__( 'First line of the main heading (regular color).', 'goldenpine-theme' ),
			'section'     => 'goldenpine_front_events_section',
			'type'        => 'text',
		]
	);

	// --- Main Heading Line 2 (Gold) ------------------------------------
	$wp_customize->add_setting(
		'goldenpine_front_events_heading_2',
		[
			'default'           => 'calling.',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		]
	);

	$wp_customize->add_control(
		'goldenpine_front_events_heading_2',
		[
			'label'       => esc_html__( 'Main Heading - Line 2 (Gold)', 'goldenpine-theme' ),
			'description' => esc_html__( 'Second line of the main heading (displayed in gold).', 'goldenpine-theme' ),
			'section'     => 'goldenpine_front_events_section',
			'type'        => 'text',
		]
	);

	// --- All Events Button Text ----------------------------------------
	$wp_customize->add_setting(
		'goldenpine_front_events_cta_text',
		[
			'default'           => 'All Events',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		]
	);

	$wp_customize->add_control(
		'goldenpine_front_events_cta_text',
		[
			'label'       => esc_html__( 'View All Button Text', 'goldenpine-theme' ),
			'description' => esc_html__( 'Text for the button that links to the events archive.', 'goldenpine-theme' ),
			'section'     => 'goldenpine_front_events_section',
			'type'        => 'text',
		]
	);

}
add_action( 'customize_register', 'goldenpine_customizer_register_front_events' );
