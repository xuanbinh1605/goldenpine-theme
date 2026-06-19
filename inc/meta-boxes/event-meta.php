<?php
/**
 * Goldenpine Theme — inc/meta-boxes/event-meta.php
 *
 * Registers the "Event Details" meta box on the event CPT and handles
 * saving all event-specific fields. Fields are grouped into five sections
 * rendered in a single meta box on the edit screen.
 *
 * Meta keys registered (all prefixed with _gpine_):
 *  _gpine_event_subtitle            — Short subtitle / strapline (text)
 *  _gpine_event_date                — Event date, YYYY-MM-DD (date)
 *  _gpine_event_start_time          — Start time, HH:MM 24-hour (time)
 *  _gpine_event_end_time            — End time, HH:MM 24-hour (time)
 *  _gpine_event_description         — Long-form rich-text description (html)
 *  _gpine_event_gallery             — Comma-separated attachment IDs (string)
 *  _gpine_event_performer           — Performer name (text)
 *  _gpine_event_dress_code          — Dress code requirement (text)
 *  _gpine_event_table_minimum       — Table minimum pricing (text)
 *  _gpine_event_age_limit           — Age restriction (text)
 *  _gpine_event_location_name       — Venue / location name (text)
 *  _gpine_event_location_description — Venue detail / door time note (textarea)
 *  _gpine_booking_phone             — Booking phone number (text)
 *
 * All fields are optional. Empty values are stored as empty strings so
 * frontend developers can use a simple `if ( $value )` conditional.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Register the meta box.
// ---------------------------------------------------------------------------
function goldenpine_register_event_meta_box(): void {
    add_meta_box(
        'goldenpine_event_details',
        esc_html__( 'Event Details', 'goldenpine-theme' ),
        'goldenpine_event_meta_render',
        'event',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'goldenpine_register_event_meta_box' );

// ---------------------------------------------------------------------------
// Enqueue admin assets only on the event CPT edit screen.
// ---------------------------------------------------------------------------
function goldenpine_enqueue_event_meta_assets( string $hook ): void {

    $screen = get_current_screen();
    if ( ! $screen || 'event' !== $screen->post_type ) {
        return;
    }

    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
        return;
    }

    // WordPress media library frame (required for the gallery picker).
    wp_enqueue_media();

    // jQuery UI Sortable for gallery drag-to-reorder.
    wp_enqueue_script( 'jquery-ui-sortable' );

    wp_enqueue_style(
        'goldenpine-event-meta-admin',
        GOLDENPINE_URI . '/assets/css/admin/_event-meta-admin.css',
        [],
        GOLDENPINE_VERSION
    );

    wp_enqueue_script(
        'goldenpine-event-meta-admin',
        GOLDENPINE_URI . '/assets/js/admin/event-meta-admin.js',
        [ 'jquery', 'jquery-ui-sortable', 'media-upload' ],
        GOLDENPINE_VERSION,
        true
    );

    wp_localize_script(
        'goldenpine-event-meta-admin',
        'gpineEventAdmin',
        [
            'frameTitle' => esc_html__( 'Select Event Gallery Images', 'goldenpine-theme' ),
            'buttonText' => esc_html__( 'Add to Gallery',               'goldenpine-theme' ),
            'removeText' => esc_html__( 'Remove image',                  'goldenpine-theme' ),
            'dragText'   => esc_html__( 'Drag to reorder',              'goldenpine-theme' ),
        ]
    );
}
add_action( 'admin_enqueue_scripts', 'goldenpine_enqueue_event_meta_assets' );

// ---------------------------------------------------------------------------
// Render the meta box.
// ---------------------------------------------------------------------------
function goldenpine_event_meta_render( WP_Post $post ): void {

    wp_nonce_field( 'goldenpine_save_event_meta', 'goldenpine_event_meta_nonce' );

    // Retrieve all stored meta values.
    $subtitle             = (string) get_post_meta( $post->ID, '_gpine_event_subtitle',             true );
    $date                 = (string) get_post_meta( $post->ID, '_gpine_event_date',                 true );
    $start_time           = (string) get_post_meta( $post->ID, '_gpine_event_start_time',           true );
    $end_time             = (string) get_post_meta( $post->ID, '_gpine_event_end_time',             true );
    $description          = (string) get_post_meta( $post->ID, '_gpine_event_description',          true );
    $gallery_ids_raw      = (string) get_post_meta( $post->ID, '_gpine_event_gallery',             true );
    $performer            = (string) get_post_meta( $post->ID, '_gpine_event_performer',            true );
    $dress_code           = (string) get_post_meta( $post->ID, '_gpine_event_dress_code',           true );
    $table_minimum        = (string) get_post_meta( $post->ID, '_gpine_event_table_minimum',        true );
    $age_limit            = (string) get_post_meta( $post->ID, '_gpine_event_age_limit',            true );
    $location_name        = (string) get_post_meta( $post->ID, '_gpine_event_location_name',        true );
    $location_description = (string) get_post_meta( $post->ID, '_gpine_event_location_description', true );
    $booking_phone        = (string) get_post_meta( $post->ID, '_gpine_booking_phone',              true );

    // Resolve gallery attachment thumbnails for display.
    $gallery_ids  = array_filter( array_map( 'absint', explode( ',', $gallery_ids_raw ) ) );
    $gallery_data = [];

    foreach ( $gallery_ids as $attachment_id ) {
        $thumb_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
        if ( ! $thumb_url ) {
            continue; // Skip deleted or missing attachments.
        }
        $gallery_data[] = [
            'id'    => $attachment_id,
            'thumb' => $thumb_url,
            'title' => (string) get_the_title( $attachment_id ),
        ];
    }

    ?>
    <div class="gpine-event-meta">

        <!-- ================================================================
             Section 1: Basic Information
        ================================================================ -->
        <div class="gpine-event-meta__section">
            <h3 class="gpine-event-meta__section-title">
                <?php esc_html_e( 'Basic Information', 'goldenpine-theme' ); ?>
            </h3>

            <table class="form-table gpine-event-meta__table">
                <tbody>

                    <!-- Subtitle -->
                    <tr>
                        <th scope="row">
                            <label for="gpine_event_subtitle">
                                <?php esc_html_e( 'Subtitle', 'goldenpine-theme' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="gpine_event_subtitle"
                                name="_gpine_event_subtitle"
                                value="<?php echo esc_attr( $subtitle ); ?>"
                                class="large-text"
                                placeholder="<?php esc_attr_e( "e.g. Da Nang's Biggest Electronic Night", 'goldenpine-theme' ); ?>"
                            >
                        </td>
                    </tr>

                    <!-- Event Date -->
                    <tr>
                        <th scope="row">
                            <label for="gpine_event_date">
                                <?php esc_html_e( 'Event Date', 'goldenpine-theme' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="date"
                                id="gpine_event_date"
                                name="_gpine_event_date"
                                value="<?php echo esc_attr( $date ); ?>"
                                class="regular-text"
                            >
                        </td>
                    </tr>

                    <!-- Start & End Time -->
                    <tr>
                        <th scope="row">
                            <?php esc_html_e( 'Event Time', 'goldenpine-theme' ); ?>
                        </th>
                        <td>
                            <label for="gpine_event_start_time" class="gpine-event-meta__time-label">
                                <?php esc_html_e( 'Start', 'goldenpine-theme' ); ?>
                            </label>
                            <input
                                type="time"
                                id="gpine_event_start_time"
                                name="_gpine_event_start_time"
                                value="<?php echo esc_attr( $start_time ); ?>"
                                class="gpine-event-meta__time-input"
                            >
                            <label for="gpine_event_end_time" class="gpine-event-meta__time-label">
                                <?php esc_html_e( 'End', 'goldenpine-theme' ); ?>
                            </label>
                            <input
                                type="time"
                                id="gpine_event_end_time"
                                name="_gpine_event_end_time"
                                value="<?php echo esc_attr( $end_time ); ?>"
                                class="gpine-event-meta__time-input"
                            >
                            <p class="description">
                                <?php esc_html_e( 'End time may extend past midnight (e.g., 02:00 for 2 AM the following day).', 'goldenpine-theme' ); ?>
                            </p>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div><!-- .gpine-event-meta__section -->

        <!-- ================================================================
             Section 2: Event Content
        ================================================================ -->
        <div class="gpine-event-meta__section">
            <h3 class="gpine-event-meta__section-title">
                <?php esc_html_e( 'Event Content', 'goldenpine-theme' ); ?>
            </h3>

            <p class="description gpine-event-meta__section-desc">
                <?php esc_html_e( 'Long-form event description shown on the event detail page.', 'goldenpine-theme' ); ?>
            </p>

            <?php
            wp_editor(
                $description,
                'gpine_event_description_editor',
                [
                    'textarea_name' => '_gpine_event_description',
                    'media_buttons' => true,
                    'teeny'         => false,
                    'textarea_rows' => 12,
                    'editor_class'  => 'gpine-event-meta__wysiwyg',
                ]
            );
            ?>
        </div><!-- .gpine-event-meta__section -->

        <!-- ================================================================
             Section 3: Gallery
        ================================================================ -->
        <div class="gpine-event-meta__section">
            <h3 class="gpine-event-meta__section-title">
                <?php esc_html_e( 'Gallery', 'goldenpine-theme' ); ?>
            </h3>

            <p class="description gpine-event-meta__help">
                <?php esc_html_e( 'Upload or select images from the Media Library. Drag thumbnails to reorder.', 'goldenpine-theme' ); ?>
            </p>

            <p
                class="gpine-event-meta__empty"
                id="gpine-gallery-empty"
                <?php echo ! empty( $gallery_data ) ? 'style="display:none"' : ''; ?>
            >
                <?php esc_html_e( 'No images added yet. Click "Add / Select Images" below to get started.', 'goldenpine-theme' ); ?>
            </p>

            <ul id="gpine-gallery-list" class="gpine-gallery-list">
                <?php foreach ( $gallery_data as $image ) : ?>
                    <li class="gpine-gallery-item" data-id="<?php echo esc_attr( $image['id'] ); ?>">
                        <img
                            src="<?php echo esc_url( $image['thumb'] ); ?>"
                            alt="<?php echo esc_attr( $image['title'] ); ?>"
                            class="gpine-gallery-thumb"
                        >
                        <span
                            class="gpine-gallery-handle dashicons dashicons-menu-alt2"
                            title="<?php esc_attr_e( 'Drag to reorder', 'goldenpine-theme' ); ?>"
                        ></span>
                        <button
                            type="button"
                            class="gpine-gallery-remove"
                            data-id="<?php echo esc_attr( $image['id'] ); ?>"
                            aria-label="<?php esc_attr_e( 'Remove image', 'goldenpine-theme' ); ?>"
                        >
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>

            <input
                type="hidden"
                id="gpine_event_gallery"
                name="_gpine_event_gallery"
                value="<?php echo esc_attr( implode( ',', array_column( $gallery_data, 'id' ) ) ); ?>"
            >

            <button type="button" id="gpine-add-gallery-images" class="button button-primary gpine-event-meta__add-btn">
                <span class="dashicons dashicons-plus-alt2" style="vertical-align:middle;margin-top:-2px;margin-right:4px;line-height:0.9;"></span>
                <?php esc_html_e( 'Add / Select Images', 'goldenpine-theme' ); ?>
            </button>
        </div><!-- .gpine-event-meta__section -->

        <!-- ================================================================
             Section 4: Event Essentials
        ================================================================ -->
        <div class="gpine-event-meta__section">
            <h3 class="gpine-event-meta__section-title">
                <?php esc_html_e( 'Event Essentials', 'goldenpine-theme' ); ?>
            </h3>

            <table class="form-table gpine-event-meta__table">
                <tbody>

                    <!-- Performer -->
                    <tr>
                        <th scope="row">
                            <label for="gpine_event_performer">
                                <?php esc_html_e( 'Performer', 'goldenpine-theme' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="gpine_event_performer"
                                name="_gpine_event_performer"
                                value="<?php echo esc_attr( $performer ); ?>"
                                class="large-text"
                                placeholder="<?php esc_attr_e( 'e.g. DJ VOLTAGE (International Guest)', 'goldenpine-theme' ); ?>"
                            >
                        </td>
                    </tr>

                    <!-- Dress Code -->
                    <tr>
                        <th scope="row">
                            <label for="gpine_event_dress_code">
                                <?php esc_html_e( 'Dress Code', 'goldenpine-theme' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="gpine_event_dress_code"
                                name="_gpine_event_dress_code"
                                value="<?php echo esc_attr( $dress_code ); ?>"
                                class="large-text"
                                placeholder="<?php esc_attr_e( 'e.g. Smart Casual — No Flip-Flops', 'goldenpine-theme' ); ?>"
                            >
                        </td>
                    </tr>

                    <!-- Table Minimum -->
                    <tr>
                        <th scope="row">
                            <label for="gpine_event_table_minimum">
                                <?php esc_html_e( 'Table Minimum', 'goldenpine-theme' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="gpine_event_table_minimum"
                                name="_gpine_event_table_minimum"
                                value="<?php echo esc_attr( $table_minimum ); ?>"
                                class="large-text"
                                placeholder="<?php esc_attr_e( 'e.g. From 2,000,000 VND / table', 'goldenpine-theme' ); ?>"
                            >
                        </td>
                    </tr>

                    <!-- Age Limit -->
                    <tr>
                        <th scope="row">
                            <label for="gpine_event_age_limit">
                                <?php esc_html_e( 'Age Limit', 'goldenpine-theme' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="gpine_event_age_limit"
                                name="_gpine_event_age_limit"
                                value="<?php echo esc_attr( $age_limit ); ?>"
                                class="small-text"
                                placeholder="<?php esc_attr_e( 'e.g. 18+', 'goldenpine-theme' ); ?>"
                            >
                        </td>
                    </tr>

                    <!-- Location Name -->
                    <tr>
                        <th scope="row">
                            <label for="gpine_event_location_name">
                                <?php esc_html_e( 'Location Name', 'goldenpine-theme' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="gpine_event_location_name"
                                name="_gpine_event_location_name"
                                value="<?php echo esc_attr( $location_name ); ?>"
                                class="large-text"
                                placeholder="<?php esc_attr_e( 'e.g. Golden Pine Pub', 'goldenpine-theme' ); ?>"
                            >
                        </td>
                    </tr>

                    <!-- Location Description -->
                    <tr>
                        <th scope="row">
                            <label for="gpine_event_location_description">
                                <?php esc_html_e( 'Location Description', 'goldenpine-theme' ); ?>
                            </label>
                        </th>
                        <td>
                            <textarea
                                id="gpine_event_location_description"
                                name="_gpine_event_location_description"
                                class="large-text"
                                rows="3"
                                placeholder="<?php esc_attr_e( 'e.g. Da Nang, Vietnam · Doors open 30 minutes before show time', 'goldenpine-theme' ); ?>"
                            ><?php echo esc_textarea( $location_description ); ?></textarea>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div><!-- .gpine-event-meta__section -->

        <!-- ================================================================
             Section 5: Contact
        ================================================================ -->
        <div class="gpine-event-meta__section">
            <h3 class="gpine-event-meta__section-title">
                <?php esc_html_e( 'Contact', 'goldenpine-theme' ); ?>
            </h3>

            <table class="form-table gpine-event-meta__table">
                <tbody>

                    <!-- Booking Phone -->
                    <tr>
                        <th scope="row">
                            <label for="gpine_booking_phone">
                                <?php esc_html_e( 'Booking Phone', 'goldenpine-theme' ); ?>
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                id="gpine_booking_phone"
                                name="_gpine_booking_phone"
                                value="<?php echo esc_attr( $booking_phone ); ?>"
                                class="regular-text"
                                placeholder="<?php esc_attr_e( 'e.g. +84000000000', 'goldenpine-theme' ); ?>"
                            >
                            <p class="description">
                                <?php esc_html_e( 'Used for the "Call Now" button on the event page.', 'goldenpine-theme' ); ?>
                            </p>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div><!-- .gpine-event-meta__section -->

    </div><!-- .gpine-event-meta -->
    <?php
}

// ---------------------------------------------------------------------------
// Save all meta box fields.
// ---------------------------------------------------------------------------
function goldenpine_save_event_meta( int $post_id ): void {

    // Verify nonce.
    if (
        ! isset( $_POST['goldenpine_event_meta_nonce'] ) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['goldenpine_event_meta_nonce'] ) ),
            'goldenpine_save_event_meta'
        )
    ) {
        return;
    }

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

    // -----------------------------------------------------------------------
    // Section 1: Basic Information.
    // -----------------------------------------------------------------------

    // Subtitle — plain text.
    if ( isset( $_POST['_gpine_event_subtitle'] ) ) {
        update_post_meta(
            $post_id,
            '_gpine_event_subtitle',
            sanitize_text_field( wp_unslash( $_POST['_gpine_event_subtitle'] ) )
        );
    }

    // Event Date — validate YYYY-MM-DD format; store empty string if invalid.
    if ( isset( $_POST['_gpine_event_date'] ) ) {
        $date_raw    = sanitize_text_field( wp_unslash( $_POST['_gpine_event_date'] ) );
        $date_stored = ( '' !== $date_raw && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date_raw ) )
            ? $date_raw
            : '';
        update_post_meta( $post_id, '_gpine_event_date', $date_stored );
    }

    // Start Time — validate HH:MM format; store empty string if invalid.
    if ( isset( $_POST['_gpine_event_start_time'] ) ) {
        $time_raw    = sanitize_text_field( wp_unslash( $_POST['_gpine_event_start_time'] ) );
        $time_stored = ( '' !== $time_raw && preg_match( '/^\d{2}:\d{2}$/', $time_raw ) )
            ? $time_raw
            : '';
        update_post_meta( $post_id, '_gpine_event_start_time', $time_stored );
    }

    // End Time — validate HH:MM format; store empty string if invalid.
    if ( isset( $_POST['_gpine_event_end_time'] ) ) {
        $time_raw    = sanitize_text_field( wp_unslash( $_POST['_gpine_event_end_time'] ) );
        $time_stored = ( '' !== $time_raw && preg_match( '/^\d{2}:\d{2}$/', $time_raw ) )
            ? $time_raw
            : '';
        update_post_meta( $post_id, '_gpine_event_end_time', $time_stored );
    }

    // -----------------------------------------------------------------------
    // Section 2: Event Content — WYSIWYG, allow full post HTML.
    // -----------------------------------------------------------------------
    if ( isset( $_POST['_gpine_event_description'] ) ) {
        update_post_meta(
            $post_id,
            '_gpine_event_description',
            wp_kses_post( wp_unslash( $_POST['_gpine_event_description'] ) )
        );
    }

    // -----------------------------------------------------------------------
    // Section 3: Gallery — validate as comma-separated attachment IDs.
    // -----------------------------------------------------------------------
    if ( isset( $_POST['_gpine_event_gallery'] ) ) {
        $raw_ids     = sanitize_text_field( wp_unslash( $_POST['_gpine_event_gallery'] ) );
        $ids         = array_filter( array_map( 'absint', explode( ',', $raw_ids ) ) );
        $stored_ids  = implode( ',', $ids );
        update_post_meta( $post_id, '_gpine_event_gallery', $stored_ids );
    }

    // -----------------------------------------------------------------------
    // Section 4: Event Essentials — all plain text.
    // -----------------------------------------------------------------------
    $text_fields = [
        '_gpine_event_performer',
        '_gpine_event_dress_code',
        '_gpine_event_table_minimum',
        '_gpine_event_age_limit',
        '_gpine_event_location_name',
    ];

    foreach ( $text_fields as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta(
                $post_id,
                $key,
                sanitize_text_field( wp_unslash( $_POST[ $key ] ) )
            );
        }
    }

    // Location Description — textarea; preserve line breaks.
    if ( isset( $_POST['_gpine_event_location_description'] ) ) {
        update_post_meta(
            $post_id,
            '_gpine_event_location_description',
            sanitize_textarea_field( wp_unslash( $_POST['_gpine_event_location_description'] ) )
        );
    }

    // -----------------------------------------------------------------------
    // Section 5: Contact.
    // -----------------------------------------------------------------------
    if ( isset( $_POST['_gpine_booking_phone'] ) ) {
        update_post_meta(
            $post_id,
            '_gpine_booking_phone',
            sanitize_text_field( wp_unslash( $_POST['_gpine_booking_phone'] ) )
        );
    }
}
add_action( 'save_post_event', 'goldenpine_save_event_meta' );
