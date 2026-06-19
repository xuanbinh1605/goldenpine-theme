<?php
/**
 * Goldenpine Theme — template-parts/front-page/about.php
 *
 * About section for the front page.
 * Displays section heading, main headline, stat cards, description, and CTA button.
 * All content is managed via Appearance > Customize > Front Page Settings > About Section.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ───────────────────────────────────────────────────────────────────────────
// Retrieve settings from Customizer with defaults
// ───────────────────────────────────────────────────────────────────────────
$_gpine_about_label       = get_theme_mod( 'goldenpine_about_label', 'About the Club' );
$_gpine_about_heading_1   = get_theme_mod( 'goldenpine_about_heading_1', 'More than a bar.' );
$_gpine_about_heading_2   = get_theme_mod( 'goldenpine_about_heading_2', 'An unforgettable night.' );

$_gpine_about_stat1_num   = get_theme_mod( 'goldenpine_about_stat1_number', '50+' );
$_gpine_about_stat1_text  = get_theme_mod( 'goldenpine_about_stat1_text', 'Live events a year' );

$_gpine_about_stat2_num   = get_theme_mod( 'goldenpine_about_stat2_number', '120' );
$_gpine_about_stat2_text  = get_theme_mod( 'goldenpine_about_stat2_text', 'Signature cocktails' );

$_gpine_about_stat3_num   = get_theme_mod( 'goldenpine_about_stat3_number', '500+' );
$_gpine_about_stat3_text  = get_theme_mod( 'goldenpine_about_stat3_text', 'Guests every night' );

$_gpine_about_description = get_theme_mod( 'goldenpine_about_description', 'Premium cocktails, live shows, and the most energetic crowd in Da Nang — every single night.' );

$_gpine_about_cta_text    = get_theme_mod( 'goldenpine_about_cta_text', 'Learn More' );
$_gpine_about_cta_link    = get_theme_mod( 'goldenpine_about_cta_link', '/about' );
?>

<section id="about" class="relative py-24 md:py-32 px-6 lg:px-12 bg-background overflow-hidden">
    
    <!-- Decorative gradient blur -->
    <div 
        aria-hidden="true" 
        class="absolute right-0 top-0 w-[600px] h-[600px] pointer-events-none" 
        style="background: radial-gradient(circle, rgba(226, 190, 61, 0.08) 0%, transparent 70%); filter: blur(80px);"
    ></div>

    <div class="relative max-w-7xl mx-auto">
        
        <!-- Section Label -->
        <p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-8 flex items-center gap-3">
            <span class="h-px w-8 bg-gold inline-block"></span>
            <?php echo esc_html( $_gpine_about_label ); ?>
        </p>

        <!-- Main Heading -->
        <h2 class="font-black uppercase text-foreground leading-[0.92] tracking-tight text-balance mb-12 md:mb-16" 
            style="font-size: clamp(2rem, 7vw, 7rem);">
            <?php echo esc_html( $_gpine_about_heading_1 ); ?><br>
            <span class="text-gold"><?php echo esc_html( $_gpine_about_heading_2 ); ?></span>
        </h2>

        <!-- Stat Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-12 md:mb-16">
            
            <!-- Stat Card 1 -->
            <div class="rounded-3xl border border-border bg-card p-8 md:p-10 flex flex-col gap-4 box-glow-gold-hover transition-all duration-500">
                <p class="font-black text-gold leading-none" style="font-size: clamp(4rem, 7vw, 7rem);">
                    <?php echo esc_html( $_gpine_about_stat1_num ); ?>
                </p>
                <p class="text-base md:text-lg font-medium text-foreground/80 leading-snug uppercase tracking-wide">
                    <?php echo esc_html( $_gpine_about_stat1_text ); ?>
                </p>
            </div>

            <!-- Stat Card 2 -->
            <div class="rounded-3xl border border-border bg-card p-8 md:p-10 flex flex-col gap-4 box-glow-gold-hover transition-all duration-500">
                <p class="font-black text-gold leading-none" style="font-size: clamp(4rem, 7vw, 7rem);">
                    <?php echo esc_html( $_gpine_about_stat2_num ); ?>
                </p>
                <p class="text-base md:text-lg font-medium text-foreground/80 leading-snug uppercase tracking-wide">
                    <?php echo esc_html( $_gpine_about_stat2_text ); ?>
                </p>
            </div>

            <!-- Stat Card 3 -->
            <div class="rounded-3xl border border-border bg-card p-8 md:p-10 flex flex-col gap-4 box-glow-gold-hover transition-all duration-500">
                <p class="font-black text-gold leading-none" style="font-size: clamp(4rem, 7vw, 7rem);">
                    <?php echo esc_html( $_gpine_about_stat3_num ); ?>
                </p>
                <p class="text-base md:text-lg font-medium text-foreground/80 leading-snug uppercase tracking-wide">
                    <?php echo esc_html( $_gpine_about_stat3_text ); ?>
                </p>
            </div>

        </div>

        <!-- Description & CTA -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-10 items-end">
            
            <!-- Description -->
            <p class="md:col-span-7 text-xl md:text-2xl font-light text-foreground/85 leading-snug max-w-2xl text-pretty">
                <?php echo esc_html( $_gpine_about_description ); ?>
            </p>

            <!-- CTA Button -->
            <div class="md:col-span-5 flex md:justify-end">
                <a 
                    class="group inline-flex items-center gap-3 rounded-full bg-foreground pl-7 pr-3 py-3 text-sm md:text-base font-bold uppercase tracking-wider text-background hover:bg-gold hover:text-black transition-colors" 
                    href="<?php echo esc_url( $_gpine_about_cta_link ); ?>"
                >
                    <?php echo esc_html( $_gpine_about_cta_text ); ?>
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-background text-foreground transition-transform group-hover:translate-x-1 group-hover:bg-black group-hover:text-gold">
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            width="16" 
                            height="16" 
                            viewBox="0 0 24 24" 
                            fill="none" 
                            stroke="currentColor" 
                            stroke-width="2" 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            class="lucide lucide-arrow-up-right" 
                            aria-hidden="true"
                        >
                            <path d="M7 7h10v10"></path>
                            <path d="M7 17 17 7"></path>
                        </svg>
                    </span>
                </a>
            </div>

        </div>

    </div>

</section>
