<?php
/**
 * Goldenpine Theme — 404.php
 *
 * Template for the 404 Not Found page.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main page-404 min-h-screen bg-background">
    <div class="container">
        <section class="error-404">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e( '404 — Page Not Found', 'goldenpine-theme' ); ?></h1>
            </header>
            <div class="page-content">
                <p><?php esc_html_e( "The page you're looking for doesn't exist or has been moved.", 'goldenpine-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
                    <?php esc_html_e( 'Return Home', 'goldenpine-theme' ); ?>
                </a>
            </div>
        </section>
    </div>
</main><!-- #primary -->

<?php get_footer(); ?>
