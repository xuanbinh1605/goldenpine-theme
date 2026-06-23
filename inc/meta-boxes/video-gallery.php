<?php
/**
 * Goldenpine Theme — inc/meta-boxes/video-gallery.php
 *
 * Registers TWO "Hero Videos" gallery meta boxes on the gpine_video CPT:
 *  - "Hero Videos (PC)" — stores IDs in '_gpine_hero_videos_pc'
 *  - "Hero Videos (Mobile)" — stores IDs in '_gpine_hero_videos_mobile'
 *
 * Video attachment IDs are stored as comma-separated strings in post meta.
 *
 * Admin UI uses the native wp.media() frame (wp-media) for picking, sorting,
 * and removing video attachments. Sorting is powered by jQuery UI Sortable
 * (bundled with WordPress).
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Register the meta boxes (PC and Mobile).
// ---------------------------------------------------------------------------
function goldenpine_register_video_gallery_metabox(): void {
    add_meta_box(
        'goldenpine_hero_videos_pc',
        esc_html__( 'Hero Videos (PC)', 'goldenpine-theme' ),
        'goldenpine_video_gallery_render_pc',
        'gpine_video',
        'normal',
        'high'
    );

    add_meta_box(
        'goldenpine_hero_videos_mobile',
        esc_html__( 'Hero Videos (Mobile)', 'goldenpine-theme' ),
        'goldenpine_video_gallery_render_mobile',
        'gpine_video',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'goldenpine_register_video_gallery_metabox' );

// ---------------------------------------------------------------------------
// Enqueue admin assets only on the Video CPT edit screen.
// ---------------------------------------------------------------------------
function goldenpine_enqueue_video_gallery_assets( string $hook ): void {

    $screen = get_current_screen();
    if ( ! $screen || 'gpine_video' !== $screen->post_type ) {
        return;
    }

    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
        return;
    }

    // WordPress media library frame.
    wp_enqueue_media();

    // jQuery UI Sortable (bundled with WP).
    wp_enqueue_script( 'jquery-ui-sortable' );

    wp_enqueue_style(
        'goldenpine-hero-video-admin',
        GOLDENPINE_URI . '/assets/css/admin/_hero-video-admin.css',
        [],
        GOLDENPINE_VERSION
    );

    wp_enqueue_script(
        'goldenpine-hero-video-admin',
        GOLDENPINE_URI . '/assets/js/admin/hero-video-admin.js',
        [ 'jquery', 'jquery-ui-sortable', 'media-upload' ],
        GOLDENPINE_VERSION,
        true
    );

    wp_localize_script(
        'goldenpine-hero-video-admin',
        'gpineVideoAdmin',
        [
            'frameTitle'  => esc_html__( 'Select Hero Videos',  'goldenpine-theme' ),
            'buttonText'  => esc_html__( 'Add to Hero',          'goldenpine-theme' ),
            'previewText' => esc_html__( 'Preview',              'goldenpine-theme' ),
            'removeText'  => esc_html__( 'Remove video',         'goldenpine-theme' ),
            'dragText'    => esc_html__( 'Drag to reorder',      'goldenpine-theme' ),
        ]
    );
}
add_action( 'admin_enqueue_scripts', 'goldenpine_enqueue_video_gallery_assets' );

// ---------------------------------------------------------------------------
// Render the meta box (PC).
// ---------------------------------------------------------------------------
function goldenpine_video_gallery_render_pc( WP_Post $post ): void {
    goldenpine_video_gallery_render_metabox( $post, 'pc', '_gpine_hero_videos_pc' );
}

// ---------------------------------------------------------------------------
// Render the meta box (Mobile).
// ---------------------------------------------------------------------------
function goldenpine_video_gallery_render_mobile( WP_Post $post ): void {
    goldenpine_video_gallery_render_metabox( $post, 'mobile', '_gpine_hero_videos_mobile' );
}

// ---------------------------------------------------------------------------
// Shared render function for both PC and Mobile meta boxes.
// ---------------------------------------------------------------------------
function goldenpine_video_gallery_render_metabox( WP_Post $post, string $device, string $meta_key ): void {

    wp_nonce_field( 'goldenpine_save_hero_videos_' . $device, 'goldenpine_hero_videos_nonce_' . $device );

    // Retrieve stored IDs and resolve each to a URL + title.
    $ids_raw     = get_post_meta( $post->ID, $meta_key, true );
    $ids         = array_filter( array_map( 'absint', explode( ',', (string) $ids_raw ) ) );
    $videos_data = [];

    foreach ( $ids as $attachment_id ) {
        $url = wp_get_attachment_url( $attachment_id );
        if ( ! $url ) {
            continue; // Skip deleted or invalid attachments.
        }
        $title         = get_the_title( $attachment_id );
        $videos_data[] = [
            'id'    => $attachment_id,
            'url'   => $url,
            'title' => $title ?: wp_basename( $url ),
        ];
    }
    ?>

    <div class="gpine-video-gallery" data-device="<?php echo esc_attr( $device ); ?>">

        <p class="description gpine-video-gallery__help">
            <?php
            printf(
                /* translators: %s: device type (PC or Mobile) */
                esc_html__( 'Upload or select videos for %s display. Drag rows to reorder. Only .mp4 / .webm / .ogg files are recommended.', 'goldenpine-theme' ),
                '<strong>' . esc_html( strtoupper( $device ) ) . '</strong>'
            );
            ?>
        </p>

        <?php if ( empty( $videos_data ) ) : ?>
            <p class="gpine-video-gallery__empty">
                <?php esc_html_e( 'No videos added yet. Click "Add / Select Videos" below to get started.', 'goldenpine-theme' ); ?>
            </p>
        <?php endif; ?>

        <ul id="gpine-video-list-<?php echo esc_attr( $device ); ?>" class="gpine-video-list">
            <?php foreach ( $videos_data as $video ) : ?>
                <li class="gpine-video-item" data-id="<?php echo esc_attr( $video['id'] ); ?>">

                    <span
                        class="dashicons dashicons-menu-alt2 gpine-video-handle"
                        title="<?php esc_attr_e( 'Drag to reorder', 'goldenpine-theme' ); ?>"
                    ></span>

                    <span class="dashicons dashicons-video-alt3 gpine-video-icon"></span>

                    <span class="gpine-video-title">
                        <?php echo esc_html( $video['title'] ); ?>
                    </span>

                    <a
                        href="<?php echo esc_url( $video['url'] ); ?>"
                        class="gpine-video-preview button button-small"
                        target="_blank"
                        rel="noopener noreferrer"
                        title="<?php esc_attr_e( 'Preview', 'goldenpine-theme' ); ?>"
                    >
                        <span class="dashicons dashicons-external" style="margin-top:3px;"></span>
                    </a>

                    <button
                        type="button"
                        class="gpine-video-remove button button-small button-link-delete"
                        data-id="<?php echo esc_attr( $video['id'] ); ?>"
                        aria-label="<?php esc_attr_e( 'Remove video', 'goldenpine-theme' ); ?>"
                    >
                        <span class="dashicons dashicons-trash" style="margin-top:3px;"></span>
                    </button>

                </li>
            <?php endforeach; ?>
        </ul>

        <input
            type="hidden"
            id="gpine_hero_videos_<?php echo esc_attr( $device ); ?>"
            name="<?php echo esc_attr( $meta_key ); ?>"
            value="<?php echo esc_attr( implode( ',', array_column( $videos_data, 'id' ) ) ); ?>"
        >

        <button type="button" id="gpine-add-videos-<?php echo esc_attr( $device ); ?>" class="button button-primary gpine-video-gallery__add">
            <span class="dashicons dashicons-plus-alt2" style="vertical-align:middle;margin-top:-2px;margin-right:4px;line-height:0.9;"></span>
            <?php esc_html_e( 'Add / Select Videos', 'goldenpine-theme' ); ?>
        </button>

    </div><!-- .gpine-video-gallery -->

    <?php
}

// ---------------------------------------------------------------------------
// Save the meta box values (PC and Mobile).
// ---------------------------------------------------------------------------
function goldenpine_save_video_gallery( int $post_id ): void {

    // Skip autosave and revisions.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    // Capability check.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save PC videos.
    if (
        isset( $_POST['goldenpine_hero_videos_nonce_pc'] ) &&
        wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['goldenpine_hero_videos_nonce_pc'] ) ),
            'goldenpine_save_hero_videos_pc'
        )
    ) {
        $raw_pc = isset( $_POST['_gpine_hero_videos_pc'] )
            ? sanitize_text_field( wp_unslash( $_POST['_gpine_hero_videos_pc'] ) )
            : '';
        $ids_pc = array_filter( array_map( 'absint', explode( ',', $raw_pc ) ) );
        update_post_meta( $post_id, '_gpine_hero_videos_pc', implode( ',', $ids_pc ) );
    }

    // Save Mobile videos.
    if (
        isset( $_POST['goldenpine_hero_videos_nonce_mobile'] ) &&
        wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['goldenpine_hero_videos_nonce_mobile'] ) ),
            'goldenpine_save_hero_videos_mobile'
        )
    ) {
        $raw_mobile = isset( $_POST['_gpine_hero_videos_mobile'] )
            ? sanitize_text_field( wp_unslash( $_POST['_gpine_hero_videos_mobile'] ) )
            : '';
        $ids_mobile = array_filter( array_map( 'absint', explode( ',', $raw_mobile ) ) );
        update_post_meta( $post_id, '_gpine_hero_videos_mobile', implode( ',', $ids_mobile ) );
    }
}
add_action( 'save_post_gpine_video', 'goldenpine_save_video_gallery' );
