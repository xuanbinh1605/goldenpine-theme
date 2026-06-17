<?php
/**
 * GoldenPine Theme — index.php
 *
 * WordPress requires this file as the final fallback template.
 * It renders a generic blog loop and is rarely displayed directly —
 * more specific templates (front-page.php, page.php, single.php, etc.)
 * take precedence in the WordPress Template Hierarchy.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main min-h-screen bg-background">
    <div class="container">

        <?php if ( have_posts() ) : ?>

            <header class="page-header">
                <?php
                if ( is_home() && ! is_front_page() ) {
                    single_post_title( '<h1 class="page-title">', '</h1>' );
                }
                ?>
            </header>

            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
                    </header>
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>

            <?php the_posts_navigation(); ?>

        <?php else : ?>

            <p><?php esc_html_e( 'No content found.', 'goldenpine-theme' ); ?></p>

        <?php endif; ?>

    </div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>
