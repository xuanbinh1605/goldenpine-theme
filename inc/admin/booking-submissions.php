<?php
/**
 * Goldenpine Theme — inc/admin/booking-submissions.php
 *
 * Registers the `gpine_booking` Custom Post Type used to persist every
 * table-reservation submission from the Booking page form.
 *
 * The post type is intentionally private / admin-only:
 *   - Not publicly queryable
 *   - Hidden from front-end
 *   - No public archive or single-post URL
 *
 * Admin features:
 *   - Custom columns: Reference ID, Name, Phone, Date, Time, Guests, Status, Submitted
 *   - Color-coded status badges
 *   - Status editable via an inline meta box on the edit screen
 *   - Filterable by status via admin URL query
 *   - Booking details displayed in a structured meta box
 *
 * Meta keys stored per submission:
 *   _gpine_booking_ref          — reference ID  e.g. GP-20260619-4821
 *   _gpine_booking_name         — full name
 *   _gpine_booking_phone        — phone / WhatsApp
 *   _gpine_booking_email        — optional email
 *   _gpine_booking_date         — ISO date  e.g. 2026-07-04
 *   _gpine_booking_time         — e.g. "9 PM"
 *   _gpine_booking_guests       — e.g. "4" or "9+"
 *   _gpine_booking_note         — optional special requests
 *   _gpine_booking_status       — new | contacted | confirmed | cancelled
 *   _gpine_booking_ip           — submitter IP
 *   _gpine_booking_submitted_at — Unix timestamp of submission
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ============================================================================
// 1. Register Custom Post Type
// ============================================================================

/**
 * Register the gpine_booking CPT.
 */
function goldenpine_register_booking_cpt(): void {
	$labels = [
		'name'               => _x( 'Booking Submissions', 'post type general name', 'goldenpine-theme' ),
		'singular_name'      => _x( 'Booking Submission', 'post type singular name', 'goldenpine-theme' ),
		'menu_name'          => _x( 'Bookings', 'admin menu', 'goldenpine-theme' ),
		'name_admin_bar'     => _x( 'Booking', 'add new on admin bar', 'goldenpine-theme' ),
		'add_new'            => __( 'Add New', 'goldenpine-theme' ),
		'add_new_item'       => __( 'Add New Booking', 'goldenpine-theme' ),
		'new_item'           => __( 'New Booking', 'goldenpine-theme' ),
		'edit_item'          => __( 'View Booking', 'goldenpine-theme' ),
		'view_item'          => __( 'View Booking', 'goldenpine-theme' ),
		'all_items'          => __( 'All Bookings', 'goldenpine-theme' ),
		'search_items'       => __( 'Search Bookings', 'goldenpine-theme' ),
		'not_found'          => __( 'No bookings found.', 'goldenpine-theme' ),
		'not_found_in_trash' => __( 'No bookings found in trash.', 'goldenpine-theme' ),
	];

	register_post_type(
		'gpine_booking',
		[
			'labels'              => $labels,
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'query_var'           => false,
			'rewrite'             => false,
			'capability_type'     => 'post',
			'capabilities'        => [
				'create_posts' => 'do_not_allow',
			],
			'map_meta_cap'        => true,
			'has_archive'         => false,
			'hierarchical'        => false,
			'supports'            => [ 'title' ],
			'menu_icon'           => 'dashicons-calendar-alt',
			'menu_position'       => 6,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'goldenpine_register_booking_cpt' );

// ============================================================================
// 2. Booking Status Helpers
// ============================================================================

/**
 * Returns all valid booking statuses.
 *
 * @return array<string, array{label: string, color: string}>
 */
function goldenpine_booking_statuses(): array {
	return [
		'new'       => [ 'label' => __( 'New',       'goldenpine-theme' ), 'color' => '#C9A84C' ], // gold
		'contacted' => [ 'label' => __( 'Contacted', 'goldenpine-theme' ), 'color' => '#3B82F6' ], // blue
		'confirmed' => [ 'label' => __( 'Confirmed', 'goldenpine-theme' ), 'color' => '#22C55E' ], // green
		'cancelled' => [ 'label' => __( 'Cancelled', 'goldenpine-theme' ), 'color' => '#EF4444' ], // red
	];
}

/**
 * Returns the status label for a given status key.
 *
 * @param string $status Status key.
 * @return string
 */
function goldenpine_booking_status_label( string $status ): string {
	$statuses = goldenpine_booking_statuses();
	return $statuses[ $status ]['label'] ?? ucfirst( $status );
}

/**
 * Returns the badge HTML for a booking status.
 *
 * @param string $status Status key.
 * @return string Escaped HTML string.
 */
function goldenpine_booking_status_badge( string $status ): string {
	$statuses = goldenpine_booking_statuses();
	$data     = $statuses[ $status ] ?? [ 'label' => ucfirst( $status ), 'color' => '#888' ];

	return sprintf(
		'<span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;letter-spacing:.05em;color:#fff;background:%s;">%s</span>',
		esc_attr( $data['color'] ),
		esc_html( $data['label'] )
	);
}

// ============================================================================
// 3. Custom Admin Columns
// ============================================================================

/**
 * Define the admin list columns.
 *
 * @param array $columns Default columns.
 * @return array
 */
function goldenpine_booking_columns( array $columns ): array {
	// Start fresh — keep only checkbox and replace the rest.
	return [
		'cb'             => $columns['cb'],
		'booking_ref'    => __( 'Reference', 'goldenpine-theme' ),
		'booking_name'   => __( 'Guest Name', 'goldenpine-theme' ),
		'booking_phone'  => __( 'Phone', 'goldenpine-theme' ),
		'booking_date'   => __( 'Date', 'goldenpine-theme' ),
		'booking_time'   => __( 'Time', 'goldenpine-theme' ),
		'booking_guests' => __( 'Guests', 'goldenpine-theme' ),
		'booking_status' => __( 'Status', 'goldenpine-theme' ),
		'booking_submitted' => __( 'Submitted', 'goldenpine-theme' ),
	];
}
add_filter( 'manage_gpine_booking_posts_columns', 'goldenpine_booking_columns' );

/**
 * Render column content.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function goldenpine_booking_column_content( string $column, int $post_id ): void {
	switch ( $column ) {
		case 'booking_ref':
			$ref = get_post_meta( $post_id, '_gpine_booking_ref', true );
			echo $ref
				? '<code style="font-size:11px;background:#f0f0f0;padding:2px 6px;border-radius:3px;">' . esc_html( $ref ) . '</code>'
				: '—';
			break;

		case 'booking_name':
			$name = get_post_meta( $post_id, '_gpine_booking_name', true );
			$url  = get_edit_post_link( $post_id );
			echo $name
				? '<a href="' . esc_url( $url ) . '" style="font-weight:600;">' . esc_html( $name ) . '</a>'
				: '—';
			break;

		case 'booking_phone':
			$phone = get_post_meta( $post_id, '_gpine_booking_phone', true );
			echo $phone
				? '<a href="tel:' . esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>'
				: '—';
			break;

		case 'booking_date':
			$date = get_post_meta( $post_id, '_gpine_booking_date', true );
			if ( $date ) {
				$ts = strtotime( $date );
				echo $ts
					? '<span style="white-space:nowrap;">' . esc_html( gmdate( 'D, d M Y', $ts ) ) . '</span>'
					: esc_html( $date );
			} else {
				echo '—';
			}
			break;

		case 'booking_time':
			$time = get_post_meta( $post_id, '_gpine_booking_time', true );
			echo $time ? esc_html( $time ) : '—';
			break;

		case 'booking_guests':
			$guests = get_post_meta( $post_id, '_gpine_booking_guests', true );
			echo $guests ? esc_html( $guests ) : '—';
			break;

		case 'booking_status':
			$status = get_post_meta( $post_id, '_gpine_booking_status', true ) ?: 'new';
			echo goldenpine_booking_status_badge( $status ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;

		case 'booking_submitted':
			$ts = get_post_meta( $post_id, '_gpine_booking_submitted_at', true );
			if ( $ts ) {
				$local = get_date_from_gmt( gmdate( 'Y-m-d H:i:s', (int) $ts ), 'D, d M Y H:i' );
				echo '<span style="white-space:nowrap;font-size:12px;color:#555;">' . esc_html( $local ) . '</span>';
			} else {
				echo '—';
			}
			break;
	}
}
add_action( 'manage_gpine_booking_posts_custom_column', 'goldenpine_booking_column_content', 10, 2 );

/**
 * Make date and submitted columns sortable.
 *
 * @param array $sortable Sortable columns map.
 * @return array
 */
function goldenpine_booking_sortable_columns( array $sortable ): array {
	$sortable['booking_date']      = 'booking_date';
	$sortable['booking_submitted'] = 'booking_submitted';
	return $sortable;
}
add_filter( 'manage_edit-gpine_booking_sortable_columns', 'goldenpine_booking_sortable_columns' );

/**
 * Handle sorting by custom meta keys.
 *
 * @param \WP_Query $query The current query.
 */
function goldenpine_booking_column_orderby( \WP_Query $query ): void {
	if ( ! is_admin() || ! $query->is_main_query() || 'gpine_booking' !== $query->get( 'post_type' ) ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	if ( 'booking_date' === $orderby ) {
		$query->set( 'meta_key', '_gpine_booking_date' );
		$query->set( 'orderby', 'meta_value' );
	}

	if ( 'booking_submitted' === $orderby ) {
		$query->set( 'meta_key', '_gpine_booking_submitted_at' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
add_action( 'pre_get_posts', 'goldenpine_booking_column_orderby' );

// ============================================================================
// 4. Default Sort: newest first
// ============================================================================

/**
 * Default sort: submitted descending when no explicit order is set.
 *
 * @param \WP_Query $query The current query.
 */
function goldenpine_booking_default_order( \WP_Query $query ): void {
	if ( ! is_admin() || ! $query->is_main_query() || 'gpine_booking' !== $query->get( 'post_type' ) ) {
		return;
	}

	if ( ! $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', '_gpine_booking_submitted_at' );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'order', 'DESC' );
	}
}
add_action( 'pre_get_posts', 'goldenpine_booking_default_order' );

// ============================================================================
// 5. Status Filter Dropdown Above List
// ============================================================================

/**
 * Add a "Filter by Status" dropdown above the bookings list.
 *
 * @param string $post_type Current post type.
 */
function goldenpine_booking_status_filter_ui( string $post_type ): void {
	if ( 'gpine_booking' !== $post_type ) {
		return;
	}

	$current  = isset( $_GET['booking_status_filter'] ) ? sanitize_key( $_GET['booking_status_filter'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$statuses = goldenpine_booking_statuses();
	?>
	<select name="booking_status_filter">
		<option value=""><?php esc_html_e( 'All Statuses', 'goldenpine-theme' ); ?></option>
		<?php foreach ( $statuses as $key => $data ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $current, $key ); ?>>
				<?php echo esc_html( $data['label'] ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php
}
add_action( 'restrict_manage_posts', 'goldenpine_booking_status_filter_ui' );

/**
 * Apply status filter to the query when the dropdown is used.
 *
 * @param \WP_Query $query The current query.
 */
function goldenpine_booking_apply_status_filter( \WP_Query $query ): void {
	if ( ! is_admin() || ! $query->is_main_query() || 'gpine_booking' !== $query->get( 'post_type' ) ) {
		return;
	}

	$status = isset( $_GET['booking_status_filter'] ) ? sanitize_key( $_GET['booking_status_filter'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( ! $status ) {
		return;
	}

	$meta_query   = (array) $query->get( 'meta_query' );
	$meta_query[] = [
		'key'   => '_gpine_booking_status',
		'value' => $status,
	];
	$query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'goldenpine_booking_apply_status_filter' );

// ============================================================================
// 6. Booking Details Meta Box
// ============================================================================

/**
 * Register the booking details meta boxes.
 */
function goldenpine_booking_meta_boxes(): void {
	add_meta_box(
		'gpine_booking_details',
		__( 'Booking Details', 'goldenpine-theme' ),
		'goldenpine_booking_details_meta_box_cb',
		'gpine_booking',
		'normal',
		'high'
	);

	add_meta_box(
		'gpine_booking_status_box',
		__( 'Booking Status', 'goldenpine-theme' ),
		'goldenpine_booking_status_meta_box_cb',
		'gpine_booking',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'goldenpine_booking_meta_boxes' );

/**
 * Render booking details meta box.
 *
 * @param \WP_Post $post Current post.
 */
function goldenpine_booking_details_meta_box_cb( \WP_Post $post ): void {
	$fields = [
		'_gpine_booking_ref'          => __( 'Reference ID', 'goldenpine-theme' ),
		'_gpine_booking_name'         => __( 'Full Name', 'goldenpine-theme' ),
		'_gpine_booking_phone'        => __( 'Phone', 'goldenpine-theme' ),
		'_gpine_booking_email'        => __( 'Email', 'goldenpine-theme' ),
		'_gpine_booking_date'         => __( 'Date', 'goldenpine-theme' ),
		'_gpine_booking_time'         => __( 'Time', 'goldenpine-theme' ),
		'_gpine_booking_guests'       => __( 'Guests', 'goldenpine-theme' ),
		'_gpine_booking_note'         => __( 'Special Requests', 'goldenpine-theme' ),
		'_gpine_booking_ip'           => __( 'Submitted From (IP)', 'goldenpine-theme' ),
		'_gpine_booking_submitted_at' => __( 'Submitted At', 'goldenpine-theme' ),
	];

	echo '<table class="widefat striped" style="font-size:13px;">';
	echo '<tbody>';

	foreach ( $fields as $meta_key => $label ) {
		$raw   = get_post_meta( $post->ID, $meta_key, true );
		$value = '';

		if ( '_gpine_booking_submitted_at' === $meta_key && $raw ) {
			$value = get_date_from_gmt( gmdate( 'Y-m-d H:i:s', (int) $raw ), 'D, d M Y — H:i:s' );
		} elseif ( '_gpine_booking_date' === $meta_key && $raw ) {
			$ts    = strtotime( $raw );
			$value = $ts ? gmdate( 'l, d F Y', $ts ) . ' <em style="color:#888;">(' . esc_html( $raw ) . ')</em>' : esc_html( $raw );
		} else {
			$value = $raw ? esc_html( $raw ) : '<em style="color:#aaa;">' . esc_html__( '—', 'goldenpine-theme' ) . '</em>';
		}

		printf(
			'<tr><th style="width:180px;text-align:left;vertical-align:top;padding:8px 10px;">%s</th><td style="padding:8px 10px;">%s</td></tr>',
			esc_html( $label ),
			$value // already escaped or intentional HTML above
		);
	}

	echo '</tbody></table>';
}

/**
 * Render and handle the booking status meta box.
 *
 * @param \WP_Post $post Current post.
 */
function goldenpine_booking_status_meta_box_cb( \WP_Post $post ): void {
	wp_nonce_field( 'goldenpine_update_booking_status', 'gpine_booking_status_nonce' );

	$current  = get_post_meta( $post->ID, '_gpine_booking_status', true ) ?: 'new';
	$statuses = goldenpine_booking_statuses();
	?>
	<p>
		<label for="gpine_booking_status" class="screen-reader-text"><?php esc_html_e( 'Booking Status', 'goldenpine-theme' ); ?></label>
		<select id="gpine_booking_status" name="gpine_booking_status" style="width:100%;">
			<?php foreach ( $statuses as $key => $data ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $current, $key ); ?>>
					<?php echo esc_html( $data['label'] ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<p style="margin-top:8px;">
		<?php echo goldenpine_booking_status_badge( $current ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</p>
	<?php
}

/**
 * Save the booking status when the post is saved.
 *
 * @param int $post_id Post ID being saved.
 */
function goldenpine_save_booking_status( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['gpine_booking_status_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_key( $_POST['gpine_booking_status_nonce'] ), 'goldenpine_update_booking_status' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['gpine_booking_status'] ) ) {
		return;
	}

	$valid   = array_keys( goldenpine_booking_statuses() );
	$new_val = sanitize_key( wp_unslash( $_POST['gpine_booking_status'] ) );

	if ( in_array( $new_val, $valid, true ) ) {
		update_post_meta( $post_id, '_gpine_booking_status', $new_val );
	}
}
add_action( 'save_post_gpine_booking', 'goldenpine_save_booking_status' );

// ============================================================================
// 7. Remove "Add New" button (submissions come from the front end only)
// ============================================================================

/**
 * Remove the "Add New" button from the bookings admin screen.
 */
function goldenpine_booking_remove_add_new(): void {
	global $current_screen;
	if ( isset( $current_screen->post_type ) && 'gpine_booking' === $current_screen->post_type ) {
		echo '<style>#wpbody .page-title-action { display:none; }</style>';
	}
}
add_action( 'admin_head', 'goldenpine_booking_remove_add_new' );

// ============================================================================
// 8. Admin list "New" count badge in menu
// ============================================================================

/**
 * Show a count of "New" bookings next to the menu item.
 */
function goldenpine_booking_menu_badge(): void {
	global $menu;

	$new_count = wp_cache_get( 'gpine_booking_new_count' );

	if ( false === $new_count ) {
		$new_count = (int) ( new \WP_Query( [
			'post_type'  => 'gpine_booking',
			'post_status' => 'publish',
			'meta_key'   => '_gpine_booking_status',
			'meta_value' => 'new',
			'fields'     => 'ids',
			'nopaging'   => true,
		] ) )->found_posts;

		wp_cache_set( 'gpine_booking_new_count', $new_count, '', 60 );
	}

	if ( $new_count > 0 && is_array( $menu ) ) {
		foreach ( $menu as $key => $item ) {
			if ( isset( $item[2] ) && 'edit.php?post_type=gpine_booking' === $item[2] ) {
				$menu[ $key ][0] .= ' <span class="awaiting-mod">' . esc_html( $new_count ) . '</span>';
				break;
			}
		}
	}
}
add_action( 'admin_menu', 'goldenpine_booking_menu_badge' );

// ============================================================================
// 9. Booking Settings Submenu
// ============================================================================

/**
 * Add a Settings submenu under Bookings.
 */
function goldenpine_booking_settings_submenu(): void {
	add_submenu_page(
		'edit.php?post_type=gpine_booking',
		__( 'Booking Settings', 'goldenpine-theme' ),
		__( 'Settings', 'goldenpine-theme' ),
		'manage_options',
		'gpine_booking_settings',
		'goldenpine_booking_settings_page'
	);
}
add_action( 'admin_menu', 'goldenpine_booking_settings_submenu' );

/**
 * Register booking settings.
 */
function goldenpine_register_booking_settings(): void {
	register_setting(
		'gpine_booking_settings_group',
		'gpine_booking_notification_email',
		[
			'type'              => 'string',
			'sanitize_callback' => 'goldenpine_sanitize_booking_email',
			'default'           => '',
		]
	);
}
add_action( 'admin_init', 'goldenpine_register_booking_settings' );

/**
 * Sanitize and validate the booking notification email.
 *
 * @param string $email Email address to validate.
 * @return string Sanitized email or empty string if invalid.
 */
function goldenpine_sanitize_booking_email( string $email ): string {
	$email = sanitize_email( trim( $email ) );
	
	// If empty, return empty (will use default admin email).
	if ( empty( $email ) ) {
		return '';
	}
	
	// Validate email format.
	if ( ! is_email( $email ) ) {
		add_settings_error(
			'gpine_booking_notification_email',
			'invalid_email',
			__( 'Please enter a valid email address.', 'goldenpine-theme' ),
			'error'
		);
		// Return the old value.
		return get_option( 'gpine_booking_notification_email', '' );
	}
	
	return $email;
}

/**
 * Render the booking settings page.
 */
function goldenpine_booking_settings_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'goldenpine-theme' ) );
	}
	
	$current_email = get_option( 'gpine_booking_notification_email', '' );
	$admin_email   = get_option( 'admin_email' );
	$display_email = $current_email ?: $admin_email;
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		
		<p class="description">
			<?php esc_html_e( 'Configure where booking notifications are sent when customers submit the booking form.', 'goldenpine-theme' ); ?>
		</p>
		
		<?php settings_errors( 'gpine_booking_notification_email' ); ?>
		
		<form method="post" action="options.php">
			<?php
			settings_fields( 'gpine_booking_settings_group' );
			do_settings_sections( 'gpine_booking_settings_group' );
			?>
			
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">
							<label for="gpine_booking_notification_email">
								<?php esc_html_e( 'Notification Email Address', 'goldenpine-theme' ); ?>
							</label>
						</th>
						<td>
							<input
								type="email"
								id="gpine_booking_notification_email"
								name="gpine_booking_notification_email"
								value="<?php echo esc_attr( $current_email ); ?>"
								class="regular-text ltr"
								placeholder="<?php echo esc_attr( $admin_email ); ?>"
							>
							<p class="description">
								<?php
								printf(
									/* translators: 1: default admin email, 2: current email being used */
									esc_html__( 'Leave blank to use the default WordPress admin email (%1$s). Currently using: %2$s', 'goldenpine-theme' ),
									'<code>' . esc_html( $admin_email ) . '</code>',
									'<strong>' . esc_html( $display_email ) . '</strong>'
								);
								?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
			
			<?php submit_button( __( 'Save Email Address', 'goldenpine-theme' ) ); ?>
		</form>
		
		<hr style="margin: 40px 0;">
		
		<h2><?php esc_html_e( 'How It Works', 'goldenpine-theme' ); ?></h2>
		<ul style="list-style: disc; margin-left: 20px; line-height: 1.8;">
			<li><?php esc_html_e( 'When a customer submits a booking request, an email notification is sent to the address configured above.', 'goldenpine-theme' ); ?></li>
			<li><?php esc_html_e( 'If the customer provides their email address (optional field), they will also receive a confirmation email.', 'goldenpine-theme' ); ?></li>
			<li><?php esc_html_e( 'All booking submissions are saved in the Bookings menu regardless of email delivery status.', 'goldenpine-theme' ); ?></li>
		</ul>
	</div>
	<?php
}

/**
 * Get the booking notification email address.
 * Returns custom email if set, otherwise falls back to WordPress admin email.
 *
 * @return string Email address for booking notifications.
 */
function goldenpine_get_booking_notification_email(): string {
	$custom_email = get_option( 'gpine_booking_notification_email', '' );
	return $custom_email ?: get_option( 'admin_email' );
}
