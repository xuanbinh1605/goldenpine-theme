<?php
/**
 * Goldenpine Theme — template-parts/front-page/the-space.php
 *
 * "The Space" section for the front page.
 * Displays three image gallery cards, a heading, and a social CTA card.
 * Each card image is fetched from WordPress media by attachment ID.
 * If no image is set for a card, the card image is not rendered.
 *
 * Content managed via Appearance > Customize > Front Page Settings > The Space Section.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ───────────────────────────────────────────────────────────────────────────
// Retrieve settings from Customizer with defaults
// ───────────────────────────────────────────────────────────────────────────
$_gpine_space_label       = get_theme_mod( 'goldenpine_space_label', 'The Space' );
$_gpine_space_heading_1   = get_theme_mod( 'goldenpine_space_heading_1', 'Where the' );
$_gpine_space_heading_2   = get_theme_mod( 'goldenpine_space_heading_2', 'magic happens.' );
$_gpine_space_description = get_theme_mod( 'goldenpine_space_description', 'Lose yourself in the lights, the sound, the crowd — step inside Golden Pine Pub.' );

// Card image attachment IDs — 0 means no image set.
$_gpine_card1_img_id  = absint( get_theme_mod( 'goldenpine_space_card1_image', 29 ) );
$_gpine_card1_title   = get_theme_mod( 'goldenpine_space_card1_title', 'The Shows' );

$_gpine_card2_img_id  = absint( get_theme_mod( 'goldenpine_space_card2_image', 30 ) );
$_gpine_card2_title   = get_theme_mod( 'goldenpine_space_card2_title', 'The Crowd' );

$_gpine_card3_img_id  = absint( get_theme_mod( 'goldenpine_space_card3_image', 31 ) );
$_gpine_card3_title   = get_theme_mod( 'goldenpine_space_card3_title', 'The Venue' );

// Social CTA card — reuse existing footer social URL settings.
$_gpine_space_cta_label    = get_theme_mod( 'goldenpine_space_cta_label', 'See it live' );
$_gpine_space_cta_text     = get_theme_mod( 'goldenpine_space_cta_text', "Follow us for tonight's highlights." );
$_gpine_space_instagram    = get_theme_mod( 'goldenpine_social_instagram', '' );
$_gpine_space_facebook     = get_theme_mod( 'goldenpine_social_facebook', '' );

// Helper: resolve image URL from attachment ID; returns empty string if unset.
$_gpine_card1_img_url = $_gpine_card1_img_id ? wp_get_attachment_image_url( $_gpine_card1_img_id, 'large' ) : '';
$_gpine_card2_img_url = $_gpine_card2_img_id ? wp_get_attachment_image_url( $_gpine_card2_img_id, 'large' ) : '';
$_gpine_card3_img_url = $_gpine_card3_img_id ? wp_get_attachment_image_url( $_gpine_card3_img_id, 'large' ) : '';

// For GIF images, get the medium thumbnail as a static poster (shown on load; GIF plays on hover).
$_gpine_space_gif_poster = function ( int $img_id, string $img_url ): array {
    $is_gif = $img_url && strtolower( pathinfo( $img_url, PATHINFO_EXTENSION ) ) === 'gif';
    $poster = $is_gif && $img_id
        ? ( wp_get_attachment_image_url( $img_id, 'medium' ) ?: $img_url )
        : $img_url;
    return [ 'is_gif' => $is_gif, 'poster' => $poster ];
};
$_gpine_card1_gif = $_gpine_space_gif_poster( $_gpine_card1_img_id, $_gpine_card1_img_url );
$_gpine_card2_gif = $_gpine_space_gif_poster( $_gpine_card2_img_id, $_gpine_card2_img_url );
$_gpine_card3_gif = $_gpine_space_gif_poster( $_gpine_card3_img_id, $_gpine_card3_img_url );

// Helper: resolve alt text from attachment alt field.
$_gpine_card1_alt = $_gpine_card1_img_id ? get_post_meta( $_gpine_card1_img_id, '_wp_attachment_image_alt', true ) : '';
$_gpine_card2_alt = $_gpine_card2_img_id ? get_post_meta( $_gpine_card2_img_id, '_wp_attachment_image_alt', true ) : '';
$_gpine_card3_alt = $_gpine_card3_img_id ? get_post_meta( $_gpine_card3_img_id, '_wp_attachment_image_alt', true ) : '';
?>

<section id="space" class="py-24 md:py-32 px-6 lg:px-12 bg-background">
    <div class="max-w-7xl mx-auto">

        <!-- Section Label -->
        <p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-8 flex items-center gap-3">
            <span class="h-px w-8 bg-gold inline-block"></span>
            <?php echo esc_html( $_gpine_space_label ); ?>
        </p>

        <!-- Heading + Description row -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-10 items-end mb-14 md:mb-16">
            <h2 class="md:col-span-8 font-black uppercase text-foreground leading-[0.92] tracking-tight text-balance"
                style="font-size: clamp(2rem, 7vw, 7rem);">
                <?php echo esc_html( $_gpine_space_heading_1 ); ?><br>
                <span class="text-gold"><?php echo esc_html( $_gpine_space_heading_2 ); ?></span>
            </h2>
            <p class="md:col-span-4 text-lg md:text-xl font-light text-foreground/75 leading-snug text-pretty">
                <?php echo esc_html( $_gpine_space_description ); ?>
            </p>
        </div>

        <!-- Image Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6">

            <?php
            $space_cards = [
                [
                    'url'    => $_gpine_card1_img_url,
                    'poster' => $_gpine_card1_gif['poster'],
                    'is_gif' => $_gpine_card1_gif['is_gif'],
                    'alt'    => $_gpine_card1_alt ?: $_gpine_card1_title,
                    'title'  => $_gpine_card1_title,
                ],
                [
                    'url'    => $_gpine_card2_img_url,
                    'poster' => $_gpine_card2_gif['poster'],
                    'is_gif' => $_gpine_card2_gif['is_gif'],
                    'alt'    => $_gpine_card2_alt ?: $_gpine_card2_title,
                    'title'  => $_gpine_card2_title,
                ],
                [
                    'url'    => $_gpine_card3_img_url,
                    'poster' => $_gpine_card3_gif['poster'],
                    'is_gif' => $_gpine_card3_gif['is_gif'],
                    'alt'    => $_gpine_card3_alt ?: $_gpine_card3_title,
                    'title'  => $_gpine_card3_title,
                ],
            ];

            foreach ( $space_cards as $sc ) :
            ?>
            <div class="group relative overflow-hidden rounded-3xl h-[360px] md:h-[480px] box-glow-gold-hover bg-card <?php echo $sc['is_gif'] ? 'gpine-gif-card' : ''; ?>">
                <?php if ( $sc['url'] ) : ?>
                    <img
                        src="<?php echo esc_url( $sc['poster'] ); ?>"
                        <?php if ( $sc['is_gif'] ) : ?>
                            data-gif-src="<?php echo esc_url( $sc['url'] ); ?>"
                        <?php endif; ?>
                        alt="<?php echo esc_attr( $sc['alt'] ); ?>"
                        loading="lazy"
                        decoding="async"
                        class="object-cover transition-transform duration-700 group-hover:scale-105"
                        style="position: absolute; height: 100%; width: 100%; inset: 0px;"
                    >
                <?php endif; ?>
                <div class="absolute inset-0 rounded-3xl ring-0 group-hover:ring-2 ring-gold/40 transition-all duration-500 pointer-events-none"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <h3 class="font-black uppercase text-white text-3xl md:text-4xl leading-none tracking-tight">
                        <?php echo esc_html( $sc['title'] ); ?>
                    </h3>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <!-- Social CTA Card -->
        <div class="rounded-3xl border border-border bg-card p-8 md:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

            <!-- CTA copy -->
            <div>
                <p class="text-xs font-bold tracking-[0.4em] uppercase text-gold mb-3 flex items-center gap-2">
                    <span class="h-px w-6 bg-gold inline-block"></span>
                    <?php echo esc_html( $_gpine_space_cta_label ); ?>
                </p>
                <p class="font-black uppercase text-foreground text-2xl md:text-4xl leading-tight text-balance tracking-tight">
                    <?php echo esc_html( $_gpine_space_cta_text ); ?>
                </p>
            </div>

            <!-- Social buttons -->
            <div class="flex flex-col md:flex-row items-center gap-3 shrink-0">

                <?php if ( $_gpine_space_instagram ) : ?>
                    <a
                        href="<?php echo esc_url( $_gpine_space_instagram ); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="<?php esc_attr_e( 'Instagram', 'goldenpine-theme' ); ?>"
                        class="group inline-flex items-center gap-3 rounded-full bg-gold pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-black hover:bg-gold-bright transition-colors"
                    >
                        <?php esc_html_e( 'Instagram', 'goldenpine-theme' ); ?>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-gold transition-transform group-hover:translate-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                            </svg>
                        </span>
                    </a>
                <?php endif; ?>

                <?php if ( $_gpine_space_facebook ) : ?>
                    <a
                        href="<?php echo esc_url( $_gpine_space_facebook ); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="<?php esc_attr_e( 'Facebook', 'goldenpine-theme' ); ?>"
                        class="group inline-flex items-center gap-3 rounded-full border border-border bg-background pl-6 pr-3 py-3 text-sm font-bold uppercase tracking-wider text-foreground hover:border-gold hover:text-gold transition-colors"
                    >
                        <?php esc_html_e( 'Facebook', 'goldenpine-theme' ); ?>
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-foreground/5 transition-transform group-hover:translate-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </span>
                    </a>
                <?php endif; ?>

            </div>

        </div>

    </div>
</section>
