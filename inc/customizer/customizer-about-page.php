<?php
/**
 * Goldenpine Theme — inc/customizer/customizer-about-page.php
 *
 * Customizer settings for the About page (page-about.php).
 * Organised into four sections under an "About Page" panel:
 *
 *  1. Hero Section    — background image, label, heading
 *  2. Story Section   — image, heading, text, CTA button
 *  3. Concept Section — 4 image cards + social media CTA
 *  4. Music Section   — 4 music genre cards + booking CTA
 *
 * Social media URLs (Instagram, Facebook) are shared from
 * Theme Options > Footer Settings.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register About Page Customizer panel, sections, settings, and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 * @return void
 */
function goldenpine_customizer_register_about_page( WP_Customize_Manager $wp_customize ): void {

    // ===================================================================
    // PANEL — About Page
    // ===================================================================
    $wp_customize->add_panel(
        'goldenpine_about_page',
        [
            'title'       => esc_html__( 'About Page', 'goldenpine-theme' ),
            'description' => esc_html__( 'Manage content for the About page template.', 'goldenpine-theme' ),
            'priority'    => 27,
        ]
    );

    // ===================================================================
    // SECTION 1: Hero
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_about_page_hero',
        [
            'title'    => esc_html__( 'Hero Section', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_about_page',
            'priority' => 10,
        ]
    );

    $wp_customize->add_setting( 'goldenpine_about_page_hero_image', [ 'default' => 0, 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'goldenpine_about_page_hero_image', [
        'label'    => esc_html__( 'Background Image', 'goldenpine-theme' ),
        'section'  => 'goldenpine_about_page_hero',
        'mime_type' => 'image',
    ] ) );

    $wp_customize->add_setting( 'goldenpine_about_page_hero_label', [ 'default' => 'About Us', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_hero_label', [
        'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_hero',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_hero_h1', [ 'default' => 'The story', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_hero_h1', [
        'label'   => esc_html__( 'Heading Line 1', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_hero',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_hero_h2', [ 'default' => 'behind the gold.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_hero_h2', [
        'label'       => esc_html__( 'Heading Line 2 (Gold)', 'goldenpine-theme' ),
        'description' => esc_html__( 'Displayed in gold color.', 'goldenpine-theme' ),
        'section'     => 'goldenpine_about_page_hero',
        'type'        => 'text',
    ] );

    // ===================================================================
    // SECTION 2: Story
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_about_page_story',
        [
            'title'    => esc_html__( 'Story Section', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_about_page',
            'priority' => 20,
        ]
    );

    $wp_customize->add_setting( 'goldenpine_about_page_story_label', [ 'default' => 'Our Story', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_story_label', [
        'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_story',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_story_image', [ 'default' => 0, 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'goldenpine_about_page_story_image', [
        'label'    => esc_html__( 'Story Image', 'goldenpine-theme' ),
        'section'  => 'goldenpine_about_page_story',
        'mime_type' => 'image',
    ] ) );

    $wp_customize->add_setting( 'goldenpine_about_page_story_heading', [ 'default' => 'Born to redefine Da Nang nightlife.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_story_heading', [
        'label'   => esc_html__( 'Heading', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_story',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_story_intro', [ 'default' => 'A living stage where culture, luxury, and the spirit of celebration come together every night.', 'sanitize_callback' => 'sanitize_textarea_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_story_intro', [
        'label'   => esc_html__( 'Intro Text (large)', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_story',
        'type'    => 'textarea',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_story_description', [ 'default' => 'From the hand-curated lighting rig overhead to the theatrical installations that transform the space season by season, every detail is designed to surprise and delight.', 'sanitize_callback' => 'sanitize_textarea_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_story_description', [
        'label'   => esc_html__( 'Description Text', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_story',
        'type'    => 'textarea',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_story_cta_text', [ 'default' => 'Book A Table', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_story_cta_text', [
        'label'   => esc_html__( 'CTA Button Text', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_story',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_story_cta_link', [ 'default' => '/booking', 'sanitize_callback' => 'esc_url_raw' ] );
    $wp_customize->add_control( 'goldenpine_about_page_story_cta_link', [
        'label'   => esc_html__( 'CTA Button Link', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_story',
        'type'    => 'url',
    ] );

    // ===================================================================
    // SECTION 3: Concept & Space
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_about_page_concept',
        [
            'title'    => esc_html__( 'Concept & Space Section', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_about_page',
            'priority' => 30,
        ]
    );

    $wp_customize->add_setting( 'goldenpine_about_page_concept_label', [ 'default' => 'Concept & Space', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_concept_label', [
        'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_concept',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_concept_h1', [ 'default' => 'Every season,', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_concept_h1', [
        'label'   => esc_html__( 'Heading Line 1', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_concept',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_concept_h2', [ 'default' => 'a new world.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_concept_h2', [
        'label'       => esc_html__( 'Heading Line 2 (Gold)', 'goldenpine-theme' ),
        'section'     => 'goldenpine_about_page_concept',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_concept_subtext', [ 'default' => 'Built to transform — never the same visit twice.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_concept_subtext', [
        'label'   => esc_html__( 'Subtext', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_concept',
        'type'    => 'text',
    ] );

    // 4 image cards
    $card_labels = [ 'Fire & Aerial', 'Cultural', 'Showcase', 'Seasonal' ];
    for ( $i = 1; $i <= 4; $i++ ) {
        $wp_customize->add_setting( "goldenpine_about_page_concept_card{$i}_image", [ 'default' => 0, 'sanitize_callback' => 'absint' ] );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, "goldenpine_about_page_concept_card{$i}_image", [
            'label'    => sprintf( esc_html__( 'Card %d Image', 'goldenpine-theme' ), $i ),
            'section'  => 'goldenpine_about_page_concept',
            'mime_type' => 'image',
        ] ) );

        $wp_customize->add_setting( "goldenpine_about_page_concept_card{$i}_title", [ 'default' => $card_labels[ $i - 1 ], 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( "goldenpine_about_page_concept_card{$i}_title", [
            'label'   => sprintf( esc_html__( 'Card %d Title', 'goldenpine-theme' ), $i ),
            'section' => 'goldenpine_about_page_concept',
            'type'    => 'text',
        ] );
    }

    // Social CTA
    $wp_customize->add_setting( 'goldenpine_about_page_social_heading', [ 'default' => 'See what tonight looks like — follow the story.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_social_heading', [
        'label'   => esc_html__( 'Social CTA Heading', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_concept',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_social_ig_text', [ 'default' => 'Instagram', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_social_ig_text', [
        'label'       => esc_html__( 'Instagram Button Text', 'goldenpine-theme' ),
        'description' => esc_html__( 'URL managed under Theme Options → Footer Settings.', 'goldenpine-theme' ),
        'section'     => 'goldenpine_about_page_concept',
        'type'        => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_social_fb_text', [ 'default' => 'Facebook', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_social_fb_text', [
        'label'       => esc_html__( 'Facebook Button Text', 'goldenpine-theme' ),
        'description' => esc_html__( 'URL managed under Theme Options → Footer Settings.', 'goldenpine-theme' ),
        'section'     => 'goldenpine_about_page_concept',
        'type'        => 'text',
    ] );

    // ===================================================================
    // SECTION 4: Music & Atmosphere
    // ===================================================================
    $wp_customize->add_section(
        'goldenpine_about_page_music',
        [
            'title'    => esc_html__( 'Music & Atmosphere Section', 'goldenpine-theme' ),
            'panel'    => 'goldenpine_about_page',
            'priority' => 40,
        ]
    );

    $wp_customize->add_setting( 'goldenpine_about_page_music_label', [ 'default' => 'Music & Atmosphere', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_music_label', [
        'label'   => esc_html__( 'Section Label', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_music',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_music_h1', [ 'default' => 'The sound of', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_music_h1', [
        'label'   => esc_html__( 'Heading Line 1', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_music',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_music_h2', [ 'default' => 'Golden Pine.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_music_h2', [
        'label'   => esc_html__( 'Heading Line 2 (Gold)', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_music',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_music_subtext', [ 'default' => 'Four sounds, one unforgettable night.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_music_subtext', [
        'label'   => esc_html__( 'Subtext', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_music',
        'type'    => 'text',
    ] );

    // 4 music cards
    $music_labels = [ 'EDM', 'Cultural', 'Live', 'Show' ];
    $music_desc = [
        'Pulsating sets from resident and guest DJs, Thursday through Saturday.',
        'Traditional Vietnamese artistry reimagined with modern production.',
        'Soulful bands and jazz acts from local and international artists.',
        'Aerial, fire, and costume spectaculars unlike anywhere in Da Nang.',
    ];

    for ( $i = 1; $i <= 4; $i++ ) {
        $wp_customize->add_setting( "goldenpine_about_page_music_card{$i}_title", [ 'default' => $music_labels[ $i - 1 ], 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( "goldenpine_about_page_music_card{$i}_title", [
            'label'   => sprintf( esc_html__( 'Card %d Title', 'goldenpine-theme' ), $i ),
            'section' => 'goldenpine_about_page_music',
            'type'    => 'text',
        ] );

        $wp_customize->add_setting( "goldenpine_about_page_music_card{$i}_desc", [ 'default' => $music_desc[ $i - 1 ], 'sanitize_callback' => 'sanitize_textarea_field' ] );
        $wp_customize->add_control( "goldenpine_about_page_music_card{$i}_desc", [
            'label'   => sprintf( esc_html__( 'Card %d Description', 'goldenpine-theme' ), $i ),
            'section' => 'goldenpine_about_page_music',
            'type'    => 'textarea',
        ] );
    }

    // Booking CTA
    $wp_customize->add_setting( 'goldenpine_about_page_cta_heading', [ 'default' => 'Ready for tonight? Reserve before the floor fills.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_cta_heading', [
        'label'   => esc_html__( 'CTA Heading', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_music',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_cta_book_text', [ 'default' => 'Book A Table', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_cta_book_text', [
        'label'   => esc_html__( 'Book Button Text', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_music',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_cta_book_link', [ 'default' => '/booking', 'sanitize_callback' => 'esc_url_raw' ] );
    $wp_customize->add_control( 'goldenpine_about_page_cta_book_link', [
        'label'   => esc_html__( 'Book Button Link', 'goldenpine-theme' ),
        'section' => 'goldenpine_about_page_music',
        'type'    => 'url',
    ] );

    $wp_customize->add_setting( 'goldenpine_about_page_cta_call_text', [ 'default' => 'Call Now', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'goldenpine_about_page_cta_call_text', [
        'label'       => esc_html__( 'Call Button Text', 'goldenpine-theme' ),
        'description' => esc_html__( 'Phone number managed under Theme Options → Footer Settings.', 'goldenpine-theme' ),
        'section'     => 'goldenpine_about_page_music',
        'type'        => 'text',
    ] );
}
add_action( 'customize_register', 'goldenpine_customizer_register_about_page' );
