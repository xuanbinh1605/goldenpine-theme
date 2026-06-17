<?php
/**
 * Template Part — Global Site Footer
 *
 * Renders the footer with three columns: Brand (logo + description + social),
 * Info (address, hours, phone, email), and Navigation (Explore menu).
 * All content is editable from Appearance → Customize → Theme Options → Footer Settings.
 *
 * Social icons are only rendered when the corresponding Customizer URL is not empty.
 * SVG icons are generated server-side (not user-supplied) — no XSS risk.
 *
 * Customizer settings used:
 *   goldenpine_footer_logo, goldenpine_footer_description,
 *   goldenpine_footer_address, goldenpine_footer_hours,
 *   goldenpine_footer_phone, goldenpine_footer_email,
 *   goldenpine_social_facebook, goldenpine_social_instagram,
 *   goldenpine_social_x, goldenpine_social_youtube,
 *   goldenpine_social_linkedin, goldenpine_social_tiktok
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Retrieve all Customizer values.
// ---------------------------------------------------------------------------
$footer_logo_id  = (int) get_theme_mod( 'goldenpine_footer_logo', 0 );
$footer_logo_url = $footer_logo_id
    ? wp_get_attachment_image_url( $footer_logo_id, 'full' )
    : '';

$description      = get_theme_mod( 'goldenpine_footer_description', '' );
$address          = get_theme_mod( 'goldenpine_footer_address', '' );
$hours            = get_theme_mod( 'goldenpine_footer_hours', '' );
$hours_highlight  = get_theme_mod( 'goldenpine_footer_hours_highlight', '' );
$phone            = get_theme_mod( 'goldenpine_footer_phone', '' );
$email            = get_theme_mod( 'goldenpine_footer_email', '' );

// Social media links — each entry: [ url, label, svg_markup ].
// Order matches the reference: Instagram → Facebook → TikTok.
// SVGs are hardcoded server-side strings — not user input — safe to echo.
$social_links = [
    'instagram' => [
        'url'   => get_theme_mod( 'goldenpine_social_instagram', '' ),
        'label' => esc_html__( 'Instagram', 'goldenpine-theme' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>',
    ],
    'facebook'  => [
        'url'   => get_theme_mod( 'goldenpine_social_facebook', '' ),
        'label' => esc_html__( 'Facebook', 'goldenpine-theme' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
    ],
    'tiktok'    => [
        'url'   => get_theme_mod( 'goldenpine_social_tiktok', '' ),
        'label' => esc_html__( 'TikTok', 'goldenpine-theme' ),
        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="8" cy="18" r="4"/><path d="M12 18V2l7 4"/></svg>',
    ],
];

// Precompute whether at least one social URL is set.
$has_social = (bool) array_filter( array_column( $social_links, 'url' ) );

$site_name    = get_bloginfo( 'name' );
$current_year = gmdate( 'Y' );

// Closure reference stored so remove_filter() works reliably.
$_gpine_explore_link_filter = static function ( $atts ) {
    $atts['class'] = 'text-lg font-semibold transition-colors text-white hover:text-gold';
    return $atts;
};
?>

<footer id="site-footer" class="bg-black text-white" role="contentinfo">

    <!-- ================================================================
         CTA Section
    ================================================================ -->
    <div class="border-b border-white/10 px-6 lg:px-12 py-20 md:py-28">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-10 items-end">

            <h2 class="md:col-span-8 font-black uppercase leading-[0.92] tracking-tight text-balance text-5xl md:text-7xl">
                <?php esc_html_e( 'See you', 'goldenpine-theme' ); ?><br>
                <span class="text-gold"><?php esc_html_e( 'on the floor.', 'goldenpine-theme' ); ?></span>
            </h2>

            <div class="md:col-span-4 flex md:justify-end">
                <a
                    class="group inline-flex items-center gap-3 rounded-full bg-gold pl-7 pr-3 py-3 text-sm md:text-base font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors"
                    href="<?php echo esc_url( home_url( '/booking' ) ); ?>"
                >
                    <?php esc_html_e( 'Book A Table', 'goldenpine-theme' ); ?>
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1"
                        aria-hidden="true"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                    </span>
                </a>
            </div>

        </div>
    </div><!-- /cta -->

    <!-- ================================================================
         Main Footer Grid
    ================================================================ -->
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10">

            <!-- --------------------------------------------------------
                 Col 1: Brand
            -------------------------------------------------------- -->
            <div class="md:col-span-5 flex flex-col gap-5">

                <!-- Logo + Site Name -->
                <div class="flex items-center gap-3">
                    <?php if ( $footer_logo_url ) : ?>
                        <a
                            href="<?php echo esc_url( home_url( '/' ) ); ?>"
                            rel="home"
                            aria-label="<?php echo esc_attr( $site_name ); ?>"
                        >
                            <img
                                src="<?php echo esc_url( $footer_logo_url ); ?>"
                                alt="<?php echo esc_attr( $site_name ); ?>"
                                width="48"
                                height="48"
                                class="object-contain"
                                loading="lazy"
                                decoding="async"
                            >
                        </a>
                    <?php endif; ?>
                    <div>
                        <p class="text-base font-black tracking-widest text-gold uppercase">
                            <?php echo esc_html( $site_name ); ?>
                        </p>
                        <p class="text-xs tracking-[0.3em] uppercase text-white/60">Da Nang</p>
                    </div>
                </div>

                <!-- Description -->
                <?php if ( $description ) : ?>
                    <p class="text-base leading-relaxed max-w-sm text-white/70">
                        <?php echo esc_html( $description ); ?>
                    </p>
                <?php endif; ?>

                <!-- Social Icons — hidden when URL is empty -->
                <?php if ( $has_social ) : ?>
                    <div
                        class="flex items-center gap-3 pt-2"
                        aria-label="<?php esc_attr_e( 'Social Media Links', 'goldenpine-theme' ); ?>"
                    >
                        <?php foreach ( $social_links as $key => $social ) : ?>
                            <?php if ( ! empty( $social['url'] ) ) : ?>
                                <a
                                    href="<?php echo esc_url( $social['url'] ); ?>"
                                    class="p-3 rounded-full border transition-colors border-white/15 hover:border-gold hover:bg-gold text-white"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="<?php echo esc_attr( $social['label'] ); ?>"
                                >
                                    <?php echo $social['icon']; // SVG is hardcoded server-side — safe to echo. ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div><!-- /brand -->

            <!-- --------------------------------------------------------
                 Col 2: Explore Navigation
                 Populated from Appearance → Menus → "Explore" location.
                 Hidden if no menu is assigned.
            -------------------------------------------------------- -->
            <?php if ( has_nav_menu( 'explore' ) ) : ?>
            <div class="md:col-span-3">

                <p class="text-xs tracking-[0.35em] uppercase text-gold mb-6">
                    <?php esc_html_e( 'Explore', 'goldenpine-theme' ); ?>
                </p>

                <?php
                add_filter( 'nav_menu_link_attributes', $_gpine_explore_link_filter );
                wp_nav_menu(
                    [
                        'theme_location' => 'explore',
                        'menu_id'        => 'explore-menu',
                        'menu_class'     => 'flex flex-col gap-3',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ]
                );
                remove_filter( 'nav_menu_link_attributes', $_gpine_explore_link_filter );
                ?>

            </div><!-- /explore -->
            <?php endif; ?>

            <!-- --------------------------------------------------------
                 Col 3: Find Us — address, hours, reservations
                 Hidden entirely when all fields are empty.
            -------------------------------------------------------- -->
            <?php if ( $address || $hours || $phone || $email ) : ?>
            <div class="md:col-span-4">

                <p class="text-xs tracking-[0.35em] uppercase text-gold mb-6">
                    <?php esc_html_e( 'Find Us', 'goldenpine-theme' ); ?>
                </p>

                <div class="flex flex-col gap-5 text-sm">

                    <?php if ( $address ) : ?>
                        <div>
                            <p class="text-xs uppercase tracking-widest mb-1 text-white/60">
                                <?php esc_html_e( 'Address', 'goldenpine-theme' ); ?>
                            </p>
                            <p class="text-base leading-snug text-white">
                                <?php echo nl2br( esc_html( $address ) ); ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if ( $hours || $hours_highlight ) : ?>
                        <div>
                            <p class="text-xs uppercase tracking-widest mb-1 text-white/60">
                                <?php esc_html_e( 'Hours', 'goldenpine-theme' ); ?>
                            </p>
                            <p class="text-base text-white">
                                <?php
                                if ( $hours ) {
                                    echo esc_html( $hours );
                                    if ( $hours_highlight ) {
                                        echo ' ';
                                    }
                                }
                                if ( $hours_highlight ) {
                                    echo '<span class="text-gold">' . esc_html( $hours_highlight ) . '</span>';
                                }
                                ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if ( $phone || $email ) : ?>
                        <div>
                            <p class="text-xs uppercase tracking-widest mb-1 text-white/60">
                                <?php esc_html_e( 'Reservations', 'goldenpine-theme' ); ?>
                            </p>
                            <?php if ( $phone ) :
                                // Strip everything except digits, +, and ( ) for the tel: href.
                                $tel = preg_replace( '/[^\d+()]/', '', $phone );
                            ?>
                                <a
                                    href="tel:<?php echo esc_attr( $tel ); ?>"
                                    class="text-base hover:text-gold transition-colors block text-white"
                                >
                                    <?php echo esc_html( $phone ); ?>
                                </a>
                            <?php endif; ?>
                            <?php if ( $email ) : ?>
                                <a
                                    href="mailto:<?php echo esc_attr( $email ); ?>"
                                    class="text-base hover:text-gold transition-colors text-white"
                                >
                                    <?php echo esc_html( $email ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>

            </div><!-- /find us -->
            <?php endif; ?>

        </div>
    </div><!-- /main footer grid -->

    <!-- ================================================================
         Footer Bar
    ================================================================ -->
    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-5 flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="text-xs tracking-wider text-white/50">
                &copy; <?php echo esc_html( $current_year ); ?>
                <?php echo esc_html( $site_name ); ?>.
                <?php esc_html_e( 'All rights reserved.', 'goldenpine-theme' ); ?>
            </p>
            <div class="flex items-center gap-6">
                <a class="text-xs tracking-wider hover:text-gold transition-colors text-white/50" href="#">
                    <?php esc_html_e( 'Privacy', 'goldenpine-theme' ); ?>
                </a>
                <a class="text-xs tracking-wider hover:text-gold transition-colors text-white/50" href="#">
                    <?php esc_html_e( 'Terms', 'goldenpine-theme' ); ?>
                </a>
            </div>
        </div>
    </div><!-- /footer bar -->

</footer><!-- #site-footer -->
