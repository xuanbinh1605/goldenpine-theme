<?php
/**
 * Goldenpine Theme — inc/admin/admin-settings.php
 *
 * Optional admin dashboard settings and customisations.
 * Loaded only in the admin (see functions.php).
 *
 * Examples:
 *  - Custom admin dashboard widgets
 *  - Theme options page (if not using Customizer)
 *  - Admin notices
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add a custom dashboard widget (example).
 * Remove or customize as needed.
 */
function goldenpine_add_dashboard_widget(): void {
    wp_add_dashboard_widget(
        'goldenpine_dashboard_widget',
        esc_html__( 'Goldenpine Theme Info', 'goldenpine-theme' ),
        'goldenpine_dashboard_widget_content'
    );
}
add_action( 'wp_dashboard_setup', 'goldenpine_add_dashboard_widget' );

/**
 * Dashboard widget content.
 */
function goldenpine_dashboard_widget_content(): void {
    ?>
    <div class="goldenpine-dashboard-widget">
        <h3><?php esc_html_e( 'Welcome to Goldenpine', 'goldenpine-theme' ); ?></h3>
        <p><?php esc_html_e( 'Theme version:', 'goldenpine-theme' ); ?> <strong><?php echo esc_html( GOLDENPINE_VERSION ); ?></strong></p>
        <p>
            <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary">
                <?php esc_html_e( 'Customize Theme', 'goldenpine-theme' ); ?>
            </a>
        </p>
    </div>
    <?php
}
