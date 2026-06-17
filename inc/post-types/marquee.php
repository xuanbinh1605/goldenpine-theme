<?php
/**
 * Goldenpine Theme — inc/post-types/marquee.php
 *
 * Registers the 'gpine_marquee' Custom Post Type for managing ticker items
 * displayed in the Hero section's animated marquee bar.
 *
 * Each post represents a single ticker item (e.g., "Premium Cocktails").
 * Posts are ordered by menu_order (drag-to-reorder via plugins like Simple
 * Custom Post Order) and displayed in a continuous scrolling animation.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Register CPT.
// ---------------------------------------------------------------------------
function goldenpine_register_marquee_cpt(): void {

    $labels = [
        'name'                  => esc_html_x( 'Marquee Items',         'post type general name',  'goldenpine-theme' ),
        'singular_name'         => esc_html_x( 'Marquee Item',          'post type singular name', 'goldenpine-theme' ),
        'menu_name'             => esc_html_x( 'Marquee',               'admin menu',              'goldenpine-theme' ),
        'add_new'               => esc_html__( 'Add New',                'goldenpine-theme' ),
        'add_new_item'          => esc_html__( 'Add New Marquee Item',   'goldenpine-theme' ),
        'edit_item'             => esc_html__( 'Edit Marquee Item',      'goldenpine-theme' ),
        'new_item'              => esc_html__( 'New Marquee Item',       'goldenpine-theme' ),
        'view_item'             => esc_html__( 'View Marquee Item',      'goldenpine-theme' ),
        'all_items'             => esc_html__( 'All Marquee Items',      'goldenpine-theme' ),
        'search_items'          => esc_html__( 'Search Marquee Items',   'goldenpine-theme' ),
        'not_found'             => esc_html__( 'No marquee items found.', 'goldenpine-theme' ),
        'not_found_in_trash'    => esc_html__( 'No marquee items found in Trash.', 'goldenpine-theme' ),
    ];

    register_post_type(
        'gpine_marquee',
        [
            'labels'              => $labels,
            'public'              => false,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_rest'        => false,
            'query_var'           => false,
            'rewrite'             => false,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'hierarchical'        => false,
            'supports'            => [ 'title', 'page-attributes' ],
            'menu_icon'           => 'dashicons-format-status',
            'capability_type'     => 'post',
            'menu_position'       => 20,
        ]
    );
}
add_action( 'init', 'goldenpine_register_marquee_cpt' );

// ---------------------------------------------------------------------------
// Customize admin columns — show menu_order for easy reference.
// ---------------------------------------------------------------------------
function goldenpine_marquee_admin_columns( array $columns ): array {
    $new_columns = [];

    foreach ( $columns as $key => $label ) {
        $new_columns[ $key ] = $label;

        // Insert Order column after Title.
        if ( 'title' === $key ) {
            $new_columns['menu_order'] = esc_html__( 'Order', 'goldenpine-theme' );
        }
    }

    return $new_columns;
}
add_filter( 'manage_gpine_marquee_posts_columns', 'goldenpine_marquee_admin_columns' );

function goldenpine_marquee_admin_column_content( string $column, int $post_id ): void {
    if ( 'menu_order' === $column ) {
        echo (int) get_post_field( 'menu_order', $post_id );
    }
}
add_action( 'manage_gpine_marquee_posts_custom_column', 'goldenpine_marquee_admin_column_content', 10, 2 );

// ---------------------------------------------------------------------------
// Make the Order column sortable.
// ---------------------------------------------------------------------------
function goldenpine_marquee_sortable_columns( array $columns ): array {
    $columns['menu_order'] = 'menu_order';
    return $columns;
}
add_filter( 'manage_edit-gpine_marquee_sortable_columns', 'goldenpine_marquee_sortable_columns' );

// ---------------------------------------------------------------------------
// Default query: order by menu_order ASC (drag-to-reorder plugins set this).
// ---------------------------------------------------------------------------
function goldenpine_marquee_default_order( WP_Query $query ): void {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $screen = get_current_screen();
    if ( ! $screen || 'edit-gpine_marquee' !== $screen->id ) {
        return;
    }

    // If no orderby is set, default to menu_order ASC.
    if ( ! $query->get( 'orderby' ) ) {
        $query->set( 'orderby', 'menu_order' );
        $query->set( 'order', 'ASC' );
    }
}
add_action( 'pre_get_posts', 'goldenpine_marquee_default_order' );
