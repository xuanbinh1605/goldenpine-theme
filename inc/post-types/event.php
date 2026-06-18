<?php
/**
 * Goldenpine Theme — inc/post-types/event.php
 *
 * Registers the 'event' Custom Post Type for managing event listings.
 * Each post represents a single event with its own detail page, date,
 * performer, gallery, and booking information.
 *
 * Admin enhancements:
 *  - Custom "Event Date" column with sortable support.
 *  - Default list ordering by published date, descending.
 *  - Meta-based sort when "Event Date" column header is clicked.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Register CPT.
// ---------------------------------------------------------------------------
function goldenpine_register_event_cpt(): void {

    $labels = [
        'name'                  => esc_html_x( 'Events',                    'post type general name',  'goldenpine-theme' ),
        'singular_name'         => esc_html_x( 'Event',                     'post type singular name', 'goldenpine-theme' ),
        'menu_name'             => esc_html_x( 'Events',                    'admin menu',              'goldenpine-theme' ),
        'add_new'               => esc_html__( 'Add New',                   'goldenpine-theme' ),
        'add_new_item'          => esc_html__( 'Add New Event',              'goldenpine-theme' ),
        'edit_item'             => esc_html__( 'Edit Event',                 'goldenpine-theme' ),
        'new_item'              => esc_html__( 'New Event',                  'goldenpine-theme' ),
        'view_item'             => esc_html__( 'View Event',                 'goldenpine-theme' ),
        'view_items'            => esc_html__( 'View Events',                'goldenpine-theme' ),
        'search_items'          => esc_html__( 'Search Events',              'goldenpine-theme' ),
        'not_found'             => esc_html__( 'No events found.',           'goldenpine-theme' ),
        'not_found_in_trash'    => esc_html__( 'No events found in Trash.',  'goldenpine-theme' ),
        'all_items'             => esc_html__( 'All Events',                 'goldenpine-theme' ),
        'archives'              => esc_html__( 'Event Archives',             'goldenpine-theme' ),
        'attributes'            => esc_html__( 'Event Attributes',           'goldenpine-theme' ),
        'featured_image'        => esc_html__( 'Event Image',                'goldenpine-theme' ),
        'set_featured_image'    => esc_html__( 'Set event image',            'goldenpine-theme' ),
        'remove_featured_image' => esc_html__( 'Remove event image',         'goldenpine-theme' ),
        'use_featured_image'    => esc_html__( 'Use as event image',         'goldenpine-theme' ),
        'insert_into_item'      => esc_html__( 'Insert into event',          'goldenpine-theme' ),
        'uploaded_to_this_item' => esc_html__( 'Uploaded to this event',     'goldenpine-theme' ),
        'filter_items_list'     => esc_html__( 'Filter events list',         'goldenpine-theme' ),
        'items_list_navigation' => esc_html__( 'Events list navigation',     'goldenpine-theme' ),
        'items_list'            => esc_html__( 'Events list',                'goldenpine-theme' ),
    ];

    register_post_type(
        'event',
        [
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_rest'        => true,
            'query_var'           => true,
            'rewrite'             => [ 'slug' => 'events', 'with_front' => false ],
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-calendar-alt',
            'supports'            => [
                'title',
                'thumbnail',
            ],
        ]
    );
}
add_action( 'init', 'goldenpine_register_event_cpt' );

// ---------------------------------------------------------------------------
// Customize admin columns — show event date for easy reference.
// ---------------------------------------------------------------------------
function goldenpine_event_admin_columns( array $columns ): array {

    $new_columns = [
        'cb'         => $columns['cb'],
        'title'      => $columns['title'],
        'event_date' => esc_html__( 'Event Date', 'goldenpine-theme' ),
    ];

    // Re-append taxonomy columns automatically added by registered taxonomies.
    foreach ( $columns as $key => $label ) {
        if ( str_starts_with( $key, 'taxonomy-' ) ) {
            $new_columns[ $key ] = $label;
        }
    }

    // Published date at the end.
    if ( isset( $columns['date'] ) ) {
        $new_columns['date'] = $columns['date'];
    }

    return $new_columns;
}
add_filter( 'manage_event_posts_columns', 'goldenpine_event_admin_columns' );

function goldenpine_event_admin_column_content( string $column, int $post_id ): void {

    if ( 'event_date' !== $column ) {
        return;
    }

    $date = get_post_meta( $post_id, '_gpine_event_date', true );

    if ( $date ) {
        $timestamp = strtotime( $date );
        echo $timestamp
            ? esc_html( date_i18n( get_option( 'date_format' ), $timestamp ) )
            : esc_html( $date );
    } else {
        echo '<span style="color:#a7aaad;">&#8212;</span>';
    }
}
add_action( 'manage_event_posts_custom_column', 'goldenpine_event_admin_column_content', 10, 2 );

// ---------------------------------------------------------------------------
// Make the Event Date column sortable.
// ---------------------------------------------------------------------------
function goldenpine_event_sortable_columns( array $columns ): array {
    $columns['event_date'] = 'event_date';
    return $columns;
}
add_filter( 'manage_edit-event_sortable_columns', 'goldenpine_event_sortable_columns' );

// ---------------------------------------------------------------------------
// Admin query: handle event_date sort + default ordering.
// ---------------------------------------------------------------------------
function goldenpine_event_admin_query( WP_Query $query ): void {

    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $screen = get_current_screen();
    if ( ! $screen || 'edit-event' !== $screen->id ) {
        return;
    }

    if ( 'event_date' === $query->get( 'orderby' ) ) {
        // Sort by meta value when the Event Date column header is clicked.
        $query->set( 'meta_key', '_gpine_event_date' );
        $query->set( 'orderby', 'meta_value' );
    } elseif ( ! $query->get( 'orderby' ) ) {
        // Default: most recently published first.
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );
    }
}
add_action( 'pre_get_posts', 'goldenpine_event_admin_query' );
