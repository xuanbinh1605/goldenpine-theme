<?php
/**
 * Goldenpine Theme — inc/post-types/video.php
 *
 * Registers the 'gpine_video' Custom Post Type used as a global video manager.
 * Administrators manage all Hero videos from a single entry via the gallery
 * meta box (see inc/meta-boxes/video-gallery.php).
 *
 * Single-entry enforcement:
 *  - On admin_init: auto-creates the default entry if none exists.
 *  - On admin_init: redirects "Add New" to the existing entry.
 *  - On admin_head: hides "Add New" links via inline CSS once entry exists.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Register CPT.
// ---------------------------------------------------------------------------
function goldenpine_register_video_cpt(): void {

    $labels = [
        'name'               => esc_html_x( 'Videos',                   'post type general name',  'goldenpine-theme' ),
        'singular_name'      => esc_html_x( 'Video',                    'post type singular name', 'goldenpine-theme' ),
        'menu_name'          => esc_html_x( 'Videos',                   'admin menu',              'goldenpine-theme' ),
        'add_new'            => esc_html__( 'Add New',                   'goldenpine-theme' ),
        'add_new_item'       => esc_html__( 'Add New Video Manager',     'goldenpine-theme' ),
        'edit_item'          => esc_html__( 'Edit Video Manager',        'goldenpine-theme' ),
        'view_item'          => esc_html__( 'View Video Manager',        'goldenpine-theme' ),
        'all_items'          => esc_html__( 'Video Manager',             'goldenpine-theme' ),
        'not_found'          => esc_html__( 'No video manager found.',   'goldenpine-theme' ),
        'not_found_in_trash' => esc_html__( 'Nothing in Trash.',         'goldenpine-theme' ),
    ];

    register_post_type(
        'gpine_video',
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
            'supports'            => [ 'title' ],
            'menu_icon'           => 'dashicons-video-alt3',
            'capability_type'     => 'post',
        ]
    );
}
add_action( 'init', 'goldenpine_register_video_cpt' );

// ---------------------------------------------------------------------------
// Auto-create the default "Hero Videos" entry if none exists.
// Runs once on admin_init so the manager is always available.
// ---------------------------------------------------------------------------
function goldenpine_maybe_create_video_entry(): void {

    // Only run in admin; skip AJAX and cron.
    if ( ! is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
        return;
    }

    $existing = get_posts(
        [
            'post_type'      => 'gpine_video',
            'post_status'    => [ 'publish', 'draft' ],
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]
    );

    if ( empty( $existing ) ) {
        wp_insert_post(
            [
                'post_type'   => 'gpine_video',
                'post_title'  => esc_html__( 'Hero Videos', 'goldenpine-theme' ),
                'post_status' => 'publish',
            ]
        );
    }
}
add_action( 'admin_init', 'goldenpine_maybe_create_video_entry' );

// ---------------------------------------------------------------------------
// Redirect "Add New" to the existing entry — prevents a second record.
// ---------------------------------------------------------------------------
function goldenpine_redirect_video_add_new(): void {
    global $pagenow;

    if ( 'post-new.php' !== $pagenow ) {
        return;
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : '';
    if ( 'gpine_video' !== $post_type ) {
        return;
    }

    $existing = get_posts(
        [
            'post_type'      => 'gpine_video',
            'post_status'    => [ 'publish', 'draft' ],
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]
    );

    if ( ! empty( $existing ) ) {
        wp_safe_redirect( admin_url( 'post.php?post=' . $existing[0] . '&action=edit' ) );
        exit;
    }
}
add_action( 'admin_init', 'goldenpine_redirect_video_add_new' );

// ---------------------------------------------------------------------------
// Hide "Add New" from admin UI once an entry exists (via inline CSS).
// ---------------------------------------------------------------------------
function goldenpine_hide_video_add_new(): void {
    $screen = get_current_screen();
    if ( ! $screen || 'gpine_video' !== $screen->post_type ) {
        return;
    }

    $existing = get_posts(
        [
            'post_type'      => 'gpine_video',
            'post_status'    => [ 'publish', 'draft' ],
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]
    );

    if ( ! empty( $existing ) ) {
        echo '<style>
            .post-type-gpine_video .page-title-action,
            #menu-posts-gpine_video .wp-submenu a[href="post-new.php?post_type=gpine_video"] {
                display: none !important;
            }
        </style>';
    }
}
add_action( 'admin_head', 'goldenpine_hide_video_add_new' );

// ---------------------------------------------------------------------------
// Redirect the CPT list screen directly to the single entry's edit screen.
// Provides a single-item "settings" UX — no list table needed.
// ---------------------------------------------------------------------------
function goldenpine_redirect_video_list(): void {
    global $pagenow;

    if ( 'edit.php' !== $pagenow ) {
        return;
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : '';
    if ( 'gpine_video' !== $post_type ) {
        return;
    }

    $existing = get_posts(
        [
            'post_type'      => 'gpine_video',
            'post_status'    => [ 'publish', 'draft' ],
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]
    );

    if ( ! empty( $existing ) ) {
        wp_safe_redirect( admin_url( 'post.php?post=' . $existing[0] . '&action=edit' ) );
        exit;
    }
}
add_action( 'admin_init', 'goldenpine_redirect_video_list' );
