<?php
/**
 * Goldenpine Theme — template-parts/front-page/reservation.php
 *
 * Reservation / Book A Table section for the front page.
 * Displays a full-bleed background image with gradient overlays,
 * a centered heading, description, and two CTA buttons.
 *
 * The background image is fetched from WordPress media by attachment ID.
 * If no image is set the section renders without an image (plain dark bg).
 *
 * Content managed via Appearance > Customize > Front Page Settings > Reservation Section.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ───────────────────────────────────────────────────────────────────────────
// Retrieve settings from Customizer with defaults
// ───────────────────────────────────────────────────────────────────────────
$_gpine_res_label       = get_theme_mod( 'goldenpine_reservation_label', 'Reservations' );
$_gpine_res_heading_1   = get_theme_mod( 'goldenpine_reservation_heading_1', 'Your table is' );
$_gpine_res_heading_2   = get_theme_mod( 'goldenpine_reservation_heading_2', 'waiting.' );
$_gpine_res_description = get_theme_mod( 'goldenpine_reservation_description', "Secure your spot in seconds. We'll confirm within the hour." );
$_gpine_res_book_text   = get_theme_mod( 'goldenpine_reservation_book_text', 'Book A Table' );
$_gpine_res_book_link   = get_theme_mod( 'goldenpine_reservation_book_link', '/booking' );
$_gpine_res_call_text   = get_theme_mod( 'goldenpine_reservation_call_text', 'Call Now' );

// Background image — reuse footer phone for the call link.
$_gpine_res_bg_img_id  = absint( get_theme_mod( 'goldenpine_reservation_bg_image', 32 ) );
$_gpine_res_bg_img_url = $_gpine_res_bg_img_id ? wp_get_attachment_image_url( $_gpine_res_bg_img_id, 'full' ) : '';
$_gpine_res_bg_img_alt = $_gpine_res_bg_img_id ? get_post_meta( $_gpine_res_bg_img_id, '_wp_attachment_image_alt', true ) : '';

// Phone from existing footer setting.
$_gpine_res_phone      = get_theme_mod( 'goldenpine_footer_phone', '' );
?>

<section id="book" class="relative py-24 md:py-36 px-6 lg:px-12 overflow-hidden">

    <!-- Background Image Layer -->
    <?php if ( $_gpine_res_bg_img_url ) : ?>
        <div class="absolute inset-0 z-0">
            <img
                src="<?php echo esc_url( $_gpine_res_bg_img_url ); ?>"
                alt="<?php echo esc_attr( $_gpine_res_bg_img_alt ); ?>"
                loading="lazy"
                decoding="async"
                class="object-cover object-center"
                style="position: absolute; height: 100%; width: 100%; inset: 0px;"
            >
            <!-- Gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-black/88 via-black/78 to-black/92"></div>
            <!-- Noise texture overlay -->
            <div
                aria-hidden="true"
                class="absolute inset-0 pointer-events-none opacity-[0.04] mix-blend-overlay"
                style="background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E&quot;); background-size: 200px 200px;"
            ></div>
        </div>
    <?php else : ?>
        <!-- Fallback plain dark background when no image is set -->
        <div class="absolute inset-0 z-0 bg-background"></div>
    <?php endif; ?>

    <!-- Gold radial glow -->
    <div
        aria-hidden="true"
        class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] pointer-events-none z-0"
        style="background: radial-gradient(rgba(226, 190, 61, 0.15) 0%, transparent 70%); filter: blur(80px);"
    ></div>

    <!-- Content -->
    <div class="relative z-10 max-w-6xl mx-auto text-center">

        <!-- Section Label -->
        <p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-8 flex items-center justify-center gap-3">
            <span class="h-px w-8 bg-gold inline-block"></span>
            <?php echo esc_html( $_gpine_res_label ); ?>
            <span class="h-px w-8 bg-gold inline-block"></span>
        </p>

        <!-- Main Heading -->
        <h2 class="font-black uppercase text-white leading-[0.92] tracking-tight text-balance mb-8 md:mb-10"
            style="font-size: clamp(3rem, 8vw, 8rem);">
            <?php echo esc_html( $_gpine_res_heading_1 ); ?><br>
            <span class="text-gold"><?php echo esc_html( $_gpine_res_heading_2 ); ?></span>
        </h2>

        <!-- Description -->
        <p class="mx-auto max-w-xl text-lg md:text-xl font-light text-white/75 leading-snug text-pretty mb-10 md:mb-12">
            <?php echo esc_html( $_gpine_res_description ); ?>
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-3 md:gap-4">

            <!-- Book A Table button -->
            <a
                href="<?php echo esc_url( $_gpine_res_book_link ); ?>"
                class="group inline-flex items-center gap-3 rounded-full bg-gold pl-7 pr-3 py-3 md:pl-8 md:py-4 text-sm md:text-base font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors box-glow-gold"
            >
                <?php echo esc_html( $_gpine_res_book_text ); ?>
                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M7 7h10v10"></path>
                        <path d="M7 17 17 7"></path>
                    </svg>
                </span>
            </a>

            <!-- Call Now button — only rendered if phone is set -->
            <?php if ( $_gpine_res_phone ) : ?>
                <a
                    href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $_gpine_res_phone ) ); ?>"
                    class="group inline-flex items-center gap-3 rounded-full border border-white/30 bg-white/5 backdrop-blur-md pl-7 pr-3 py-3 md:pl-8 md:py-4 text-sm md:text-base font-bold uppercase tracking-wider text-white hover:border-gold hover:text-gold transition-colors"
                >
                    <?php echo esc_html( $_gpine_res_call_text ); ?>
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 transition-transform group-hover:-rotate-12">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"></path>
                        </svg>
                    </span>
                </a>
            <?php endif; ?>

        </div>

    </div>

</section>
