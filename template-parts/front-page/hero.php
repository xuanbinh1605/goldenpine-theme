<?php
/**
 * Template Part — Front Page Hero
 *
 * Full-screen hero section with video backgrounds sourced from the
 * 'gpine_video' CPT gallery fields:
 *  - '_gpine_hero_videos_pc' for desktop/tablet (hidden on mobile)
 *  - '_gpine_hero_videos_mobile' for mobile (hidden on desktop)
 *
 * Videos are cycled by assets/js/page-specific-js/hero-video.js:
 *  - Single video: loops indefinitely.
 *  - Multiple videos: advances to the next on the 'ended' event, loops back.
 *
 * Conditional rendering: if the CPT, its single entry, or both gallery fields
 * yield no valid video attachments, the entire <section> is suppressed —
 * no empty wrappers or placeholders are output.
 *
 * Ticker items are hardcoded brand phrases; extend via the
 * goldenpine_hero_ticker_items filter if needed.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Resolve video attachments from the CPT gallery fields (PC and Mobile).
// ---------------------------------------------------------------------------

/**
 * Returns an array of video data arrays, each with 'id', 'url', 'mime' keys.
 * Returns an empty array when nothing is configured.
 *
 * @param string $device 'pc' or 'mobile'
 * @return array<int, array{id: int, url: string, mime: string}>
 */
function goldenpine_get_hero_videos( string $device = 'pc' ): array {

    // Guard: CPT must be registered.
    if ( ! post_type_exists( 'gpine_video' ) ) {
        return [];
    }

    $posts = get_posts(
        [
            'post_type'      => 'gpine_video',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]
    );

    if ( empty( $posts ) ) {
        return [];
    }

    $meta_key = '_gpine_hero_videos_' . $device;
    $ids_raw  = get_post_meta( $posts[0], $meta_key, true );

    if ( empty( $ids_raw ) ) {
        return [];
    }

    $ids    = array_filter( array_map( 'absint', explode( ',', (string) $ids_raw ) ) );
    $videos = [];

    foreach ( $ids as $attachment_id ) {
        $url  = wp_get_attachment_url( $attachment_id );
        $mime = get_post_mime_type( $attachment_id );

        // Accept only actual video MIME types.
        if ( ! $url || ! $mime || 0 !== strpos( $mime, 'video/' ) ) {
            continue;
        }

        $videos[] = [
            'id'   => $attachment_id,
            'url'  => $url,
            'mime' => $mime,
        ];
    }

    return $videos;
}

$_gpine_hero_videos_pc     = goldenpine_get_hero_videos( 'pc' );
$_gpine_hero_videos_mobile = goldenpine_get_hero_videos( 'mobile' );

// ---------------------------------------------------------------------------
// Conditional render gate — bail completely if no valid videos found.
// ---------------------------------------------------------------------------
if ( empty( $_gpine_hero_videos_pc ) && empty( $_gpine_hero_videos_mobile ) ) {
    return;
}

// ---------------------------------------------------------------------------
// Ticker items — fetch from Marquee CPT or hide if none exist.
// ---------------------------------------------------------------------------
$_gpine_ticker_items = [];

if ( post_type_exists( 'gpine_marquee' ) ) {
    $_gpine_marquee_posts = get_posts(
        [
            'post_type'      => 'gpine_marquee',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]
    );

    if ( ! empty( $_gpine_marquee_posts ) ) {
        $_gpine_ticker_items = array_map(
            fn( $post ) => get_the_title( $post ),
            $_gpine_marquee_posts
        );
    }
}

$_gpine_video_count_pc     = count( $_gpine_hero_videos_pc );
$_gpine_video_count_mobile = count( $_gpine_hero_videos_mobile );
?>

<section
    class="hero-section relative w-full h-screen min-h-[680px] flex flex-col justify-end overflow-hidden bg-black"
    aria-label="<?php esc_attr_e( 'Hero', 'goldenpine-theme' ); ?>"
>

    <!-- ================================================================
         Background layer — videos + overlays
    ================================================================ -->
    <div class="absolute inset-0 z-0" aria-hidden="true">

        <!-- PC Videos (hidden on mobile) ----------------------------- -->
        <?php if ( ! empty( $_gpine_hero_videos_pc ) ) : ?>
            <div class="hero-videos-container hidden md:block absolute inset-0" data-count="<?php echo esc_attr( $_gpine_video_count_pc ); ?>" data-device="pc">
                <?php foreach ( $_gpine_hero_videos_pc as $i => $video ) : ?>
                    <video
                        class="hero-video absolute inset-0 w-full h-full object-cover object-center<?php echo 0 === $i ? ' is-active' : ''; ?>"
                        src="<?php echo esc_url( $video['url'] ); ?>"
                        type="<?php echo esc_attr( $video['mime'] ); ?>"
                        <?php echo 1 === $_gpine_video_count_pc ? 'loop' : ''; ?>
                        autoplay
                        muted
                        playsinline
                        preload="<?php echo 0 === $i ? 'auto' : 'metadata'; ?>"
                        aria-hidden="true"
                    ></video>
                <?php endforeach; ?>
            </div><!-- .hero-videos-container (PC) -->
        <?php endif; ?>

        <!-- Mobile Videos (hidden on desktop) ------------------------ -->
        <?php if ( ! empty( $_gpine_hero_videos_mobile ) ) : ?>
            <div class="hero-videos-container block md:hidden absolute inset-0" data-count="<?php echo esc_attr( $_gpine_video_count_mobile ); ?>" data-device="mobile">
                <?php foreach ( $_gpine_hero_videos_mobile as $i => $video ) : ?>
                    <video
                        class="hero-video absolute inset-0 w-full h-full object-cover object-center<?php echo 0 === $i ? ' is-active' : ''; ?>"
                        src="<?php echo esc_url( $video['url'] ); ?>"
                        type="<?php echo esc_attr( $video['mime'] ); ?>"
                        <?php echo 1 === $_gpine_video_count_mobile ? 'loop' : ''; ?>
                        autoplay
                        muted
                        playsinline
                        preload="<?php echo 0 === $i ? 'auto' : 'metadata'; ?>"
                        aria-hidden="true"
                    ></video>
                <?php endforeach; ?>
            </div><!-- .hero-videos-container (Mobile) -->
        <?php endif; ?>

        <!-- Overlay stack — ordered lightest to darkest --------------- -->
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/55 to-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-transparent to-black/60"></div>
        <div class="absolute top-0 left-0 right-0 h-48 bg-gradient-to-b from-black/70 to-transparent"></div>

        <!-- Film grain noise ------------------------------------------ -->
        <div
            class="absolute inset-0 pointer-events-none opacity-[0.06] mix-blend-overlay"
            style="background-image:url(\"data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E\");background-size:256px 256px"
        ></div>

        <!-- Scanlines ------------------------------------------------- -->
        <div
            class="absolute inset-0 pointer-events-none opacity-[0.025]"
            style="background-image:repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.5) 2px, rgba(0,0,0,0.5) 4px)"
        ></div>

    </div><!-- /background layer -->

    <!-- ================================================================
         Gold glow — atmospheric accent
    ================================================================ -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div
            class="absolute bottom-[-80px] left-[5%] w-[700px] h-[400px]"
            style="background:radial-gradient(ellipse at 40% 80%, rgba(226,190,61,0.18) 0%, transparent 65%);filter:blur(72px)"
        ></div>
    </div>

    <!-- ================================================================
         Hero content area — z-10 so it sits above all backgrounds.
         Populate with headings, CTAs, etc. in child theme or via action.
    ================================================================ -->
    <div class="relative z-10 px-6 lg:px-14 pb-24 md:pb-32 mt-auto">
        <?php do_action( 'goldenpine_hero_content' ); ?>
    </div>

    <!-- ================================================================
         Scroll indicator — desktop only
    ================================================================ -->
    <div class="absolute bottom-24 right-8 z-10 hidden md:flex flex-col items-center gap-2" aria-hidden="true">
        <span class="text-[9px] tracking-[0.4em] uppercase text-white/30" style="writing-mode:vertical-rl">
            <?php esc_html_e( 'scroll', 'goldenpine-theme' ); ?>
        </span>
        <div class="hero-scroll-line h-12 w-px bg-gradient-to-b from-transparent via-gold/50 to-transparent"></div>
    </div>

    <!-- ================================================================
         Ticker bar — brand phrases marquee
    ================================================================ -->
    <?php if ( ! empty( $_gpine_ticker_items ) ) : ?>
        <div
            class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden border-t border-white/[0.07] bg-black/60 backdrop-blur-sm py-2.5"
            aria-hidden="true"
        >
            <?php
            // Build one set of items as a reusable string.
            ob_start();
            foreach ( $_gpine_ticker_items as $item ) :
            ?>
                <span class="inline-flex items-center gap-4 px-5 text-[10px] font-bold uppercase tracking-[0.35em] text-white/35">
                    <span class="h-1 w-1 rounded-full bg-gold/45 shrink-0"></span>
                    <?php echo esc_html( $item ); ?>
                </span>
            <?php
            endforeach;
            $ticker_set = ob_get_clean();
            ?>
            <div class="hero-ticker flex whitespace-nowrap" style="width:max-content">
                <!-- Two identical sets keep the loop seamless -->
                <div class="flex"><?php echo $ticker_set; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
                <div class="flex" aria-hidden="true"><?php echo $ticker_set; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
            </div>
        </div>
    <?php endif; ?>

</section><!-- .hero-section -->
