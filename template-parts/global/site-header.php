<?php
/**
 * Template Part — Global Site Header
 *
 * Outputs the site header with a dynamic logo (from Customizer) and the
 * primary navigation menu (registered as "Header" location in setup.php).
 *
 * The `.site-header` element receives the `.is-scrolled` class via
 * assets/js/common/navigation.js once the user scrolls past the top.
 * Transition and scrolled styles are injected via wp_add_inline_style()
 * in inc/enqueue.php.
 *
 * Customizer settings used:
 *   - goldenpine_header_logo (attachment ID)
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Resolve header logo: Customizer setting → site name text fallback.
$header_logo_id  = (int) get_theme_mod( 'goldenpine_header_logo', 0 );
$header_logo_url = $header_logo_id
    ? wp_get_attachment_image_url( $header_logo_id, 'full' )
    : '';
$site_name = get_bloginfo( 'name' );

// Closure references stored so remove_filter() works reliably.
$_gpine_header_link_filter = static function ( $atts ) {
    $atts['class'] = 'text-xs tracking-widest uppercase transition-colors duration-300 text-white/80 hover:text-gold';
    return $atts;
};

$_gpine_mobile_link_filter = static function ( $atts ) {
    $atts['class'] = 'text-sm tracking-widest uppercase transition-colors text-white/70 hover:text-gold';
    return $atts;
};
?>

<header id="site-header" class="site-header sticky top-0 left-0 right-0 z-50 transition-all duration-500 bg-gradient-to-b from-black/70 to-transparent" role="banner">

    <!-- ================================================================
         Nav Bar
    ================================================================ -->
    <div class="max-w-7xl mx-auto px-6 lg:px-10 h-20 flex items-center justify-between">

        <!-- Logo ------------------------------------------------------- -->
        <a
            class="flex items-center gap-3 shrink-0"
            href="<?php echo esc_url( home_url( '/' ) ); ?>"
            rel="home"
            aria-label="<?php echo esc_attr( $site_name ); ?>"
        >
            <?php if ( $header_logo_url ) : ?>
                <img
                    src="<?php echo esc_url( $header_logo_url ); ?>"
                    alt="<?php echo esc_attr( $site_name ); ?>"
                    width="52"
                    height="52"
                    class="object-contain drop-shadow-lg"
                    loading="eager"
                    decoding="async"
                >
            <?php endif; ?>
            <div class="flex flex-col leading-tight">
                <span class="text-sm font-black tracking-widest text-gold uppercase">
                    <?php echo esc_html( $site_name ); ?>
                </span>
                <span class="text-[10px] tracking-[0.25em] uppercase text-white/60">Da Nang</span>
            </div>
        </a><!-- /logo -->

        <!-- Desktop Navigation ----------------------------------------- -->
        <?php
        add_filter( 'nav_menu_link_attributes', $_gpine_header_link_filter );
        wp_nav_menu(
            [
                'theme_location' => 'header',
                'menu_id'        => 'header-menu',
                'menu_class'     => 'hidden md:flex items-center gap-8',
                'container'      => false,
                'fallback_cb'    => false,
                'depth'          => 2,
            ]
        );
        remove_filter( 'nav_menu_link_attributes', $_gpine_header_link_filter );
        ?>

        <!-- Book A Table CTA — desktop only ----------------------------- -->
        <div class="hidden md:block">
            <a
                class="group inline-flex items-center gap-2.5 rounded-full bg-gold pl-5 pr-2 py-2 text-xs font-bold uppercase tracking-widest text-black hover:bg-gold-bright transition-colors"
                href="<?php echo esc_url( home_url( '/booking' ) ); ?>"
            >
                <?php esc_html_e( 'Book A Table', 'goldenpine-theme' ); ?>
                <span
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-0.5"
                    aria-hidden="true"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                </span>
            </a>
        </div>

        <!-- Mobile Toggle ----------------------------------------------- -->
        <button
            class="nav-toggle md:hidden text-white"
            type="button"
            aria-expanded="false"
            aria-controls="primary-nav"
            aria-label="<?php esc_attr_e( 'Toggle Navigation', 'goldenpine-theme' ); ?>"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/></svg>
        </button>

    </div><!-- /nav bar -->

    <!-- ================================================================
         Mobile Navigation Drawer
         .nav-toggle and #primary-nav / .primary-nav are required by
         assets/js/common/navigation.js — do not rename.
    ================================================================ -->
    <nav
        id="primary-nav"
        class="primary-nav hidden md:hidden px-6 pb-8 pt-4 bg-[#0a0a0a]/98 backdrop-blur-md border-t border-white/10"
        aria-label="<?php esc_attr_e( 'Main Navigation', 'goldenpine-theme' ); ?>"
    >
        <?php
        add_filter( 'nav_menu_link_attributes', $_gpine_mobile_link_filter );
        wp_nav_menu(
            [
                'theme_location' => 'header',
                'menu_id'        => 'mobile-menu',
                'menu_class'     => 'flex flex-col gap-5',
                'container'      => false,
                'fallback_cb'    => false,
                'depth'          => 1,
            ]
        );
        remove_filter( 'nav_menu_link_attributes', $_gpine_mobile_link_filter );
        ?>

        <!-- Mobile CTA Button ------------------------------------------ -->
        <a
            class="mt-6 group inline-flex items-center gap-2.5 rounded-full bg-gold pl-5 pr-2 py-2 text-xs font-bold uppercase tracking-widest text-black hover:bg-gold-bright transition-colors"
            href="<?php echo esc_url( home_url( '/booking' ) ); ?>"
        >
            <?php esc_html_e( 'Book A Table', 'goldenpine-theme' ); ?>
            <span
                class="flex h-8 w-8 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-0.5"
                aria-hidden="true"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
            </span>
        </a>
    </nav><!-- #primary-nav -->

</header><!-- #site-header -->
