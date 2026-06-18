<?php
/**
 * Goldenpine Theme — inc/taxonomies/event-tag.php
 *
 * Registers the 'event_tag' non-hierarchical taxonomy attached to the 'event' CPT.
 * Used to tag events with genre or mood descriptors (e.g., EDM, VIP, House).
 *
 * Configuration:
 *  - Hierarchical: no (tag-style, flat list).
 *  - REST API: enabled (show_in_rest).
 *  - Archive pages: supported via public + rewrite.
 *  - Admin column: shown automatically on the event list screen.
 *  - Clean URLs: /event-tag/{term-slug}/
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Register taxonomy.
// ---------------------------------------------------------------------------
function goldenpine_register_event_tag_taxonomy(): void {

    $labels = [
        'name'                       => esc_html_x( 'Event Tags',                          'taxonomy general name',  'goldenpine-theme' ),
        'singular_name'              => esc_html_x( 'Event Tag',                            'taxonomy singular name', 'goldenpine-theme' ),
        'menu_name'                  => esc_html_x( 'Event Tags',                           'admin menu',             'goldenpine-theme' ),
        'all_items'                  => esc_html__( 'All Event Tags',                       'goldenpine-theme' ),
        'new_item_name'              => esc_html__( 'New Event Tag Name',                   'goldenpine-theme' ),
        'add_new_item'               => esc_html__( 'Add New Event Tag',                    'goldenpine-theme' ),
        'edit_item'                  => esc_html__( 'Edit Event Tag',                       'goldenpine-theme' ),
        'update_item'                => esc_html__( 'Update Event Tag',                     'goldenpine-theme' ),
        'view_item'                  => esc_html__( 'View Event Tag',                       'goldenpine-theme' ),
        'separate_items_with_commas' => esc_html__( 'Separate tags with commas',            'goldenpine-theme' ),
        'add_or_remove_items'        => esc_html__( 'Add or remove tags',                   'goldenpine-theme' ),
        'choose_from_most_used'      => esc_html__( 'Choose from the most used tags',       'goldenpine-theme' ),
        'popular_items'              => esc_html__( 'Popular Event Tags',                   'goldenpine-theme' ),
        'search_items'               => esc_html__( 'Search Event Tags',                    'goldenpine-theme' ),
        'not_found'                  => esc_html__( 'No event tags found.',                 'goldenpine-theme' ),
        'no_terms'                   => esc_html__( 'No event tags',                        'goldenpine-theme' ),
        'items_list'                 => esc_html__( 'Event tags list',                      'goldenpine-theme' ),
        'items_list_navigation'      => esc_html__( 'Event tags list navigation',           'goldenpine-theme' ),
        'back_to_items'              => esc_html__( '&larr; Go to Event Tags',              'goldenpine-theme' ),
    ];

    register_taxonomy(
        'event_tag',
        [ 'event' ],
        [
            'labels'             => $labels,
            'hierarchical'       => false,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_admin_column'  => true,
            'show_in_nav_menus'  => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => [
                'slug'       => 'event-tag',
                'with_front' => false,
            ],
            'show_tagcloud'      => true,
        ]
    );
}
add_action( 'init', 'goldenpine_register_event_tag_taxonomy' );
