<?php
/**
 * Goldenpine Theme — inc/taxonomies/event-type.php
 *
 * Registers the 'event_type' hierarchical taxonomy attached to the 'event' CPT.
 * Used to categorise events by format (e.g., DJ Night, Live Music, Ladies Night).
 *
 * Configuration:
 *  - Hierarchical: yes (category-style, supports parent/child terms).
 *  - REST API: enabled (show_in_rest).
 *  - Archive pages: supported via public + rewrite.
 *  - Admin column: shown automatically on the event list screen.
 *  - Clean URLs: /event-type/{term-slug}/
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Register taxonomy.
// ---------------------------------------------------------------------------
function goldenpine_register_event_type_taxonomy(): void {

    $labels = [
        'name'                       => esc_html_x( 'Event Types',                          'taxonomy general name',  'goldenpine-theme' ),
        'singular_name'              => esc_html_x( 'Event Type',                            'taxonomy singular name', 'goldenpine-theme' ),
        'menu_name'                  => esc_html_x( 'Event Types',                           'admin menu',             'goldenpine-theme' ),
        'all_items'                  => esc_html__( 'All Event Types',                       'goldenpine-theme' ),
        'parent_item'                => esc_html__( 'Parent Event Type',                     'goldenpine-theme' ),
        'parent_item_colon'          => esc_html__( 'Parent Event Type:',                    'goldenpine-theme' ),
        'new_item_name'              => esc_html__( 'New Event Type Name',                   'goldenpine-theme' ),
        'add_new_item'               => esc_html__( 'Add New Event Type',                    'goldenpine-theme' ),
        'edit_item'                  => esc_html__( 'Edit Event Type',                       'goldenpine-theme' ),
        'update_item'                => esc_html__( 'Update Event Type',                     'goldenpine-theme' ),
        'view_item'                  => esc_html__( 'View Event Type',                       'goldenpine-theme' ),
        'separate_items_with_commas' => esc_html__( 'Separate event types with commas',      'goldenpine-theme' ),
        'add_or_remove_items'        => esc_html__( 'Add or remove event types',             'goldenpine-theme' ),
        'choose_from_most_used'      => esc_html__( 'Choose from the most used event types', 'goldenpine-theme' ),
        'popular_items'              => esc_html__( 'Popular Event Types',                   'goldenpine-theme' ),
        'search_items'               => esc_html__( 'Search Event Types',                    'goldenpine-theme' ),
        'not_found'                  => esc_html__( 'No event types found.',                 'goldenpine-theme' ),
        'no_terms'                   => esc_html__( 'No event types',                        'goldenpine-theme' ),
        'items_list'                 => esc_html__( 'Event types list',                      'goldenpine-theme' ),
        'items_list_navigation'      => esc_html__( 'Event types list navigation',           'goldenpine-theme' ),
        'back_to_items'              => esc_html__( '&larr; Go to Event Types',              'goldenpine-theme' ),
    ];

    register_taxonomy(
        'event_type',
        [ 'event' ],
        [
            'labels'             => $labels,
            'hierarchical'       => true,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_admin_column'  => true,
            'show_in_nav_menus'  => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => [
                'slug'         => 'event-type',
                'with_front'   => false,
                'hierarchical' => true,
            ],
            'show_tagcloud'      => false,
        ]
    );
}
add_action( 'init', 'goldenpine_register_event_type_taxonomy' );
