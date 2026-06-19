<?php
/**
 * Goldenpine Theme — template-parts/booking/hero.php
 *
 * Booking page hero section.
 * Full-bleed background image with animated gradient,
 * a back-to-home link, and the main headline.
 *
 * Content managed via Appearance > Customize > Booking Page > Hero.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_image_id  = get_theme_mod( 'goldenpine_booking_hero_image', 0 );
$hero_image_url = $hero_image_id
	? wp_get_attachment_image_url( $hero_image_id, 'full' )
	: get_template_directory_uri() . '/assets/images/booking-hero.jpg';

$hero_label    = get_theme_mod( 'goldenpine_booking_hero_label', 'Reservations' );
$hero_heading1 = get_theme_mod( 'goldenpine_booking_hero_heading_1', 'Book your' );
$hero_heading2 = get_theme_mod( 'goldenpine_booking_hero_heading_2', 'table.' );
?>

<section class="relative min-h-[70vh] flex flex-col justify-end overflow-hidden">

	<!-- Background image -->
	<div class="absolute inset-0 z-0">
		<?php if ( $hero_image_url ) : ?>
			<img
				src="<?php echo esc_url( $hero_image_url ); ?>"
				alt="<?php esc_attr_e( 'Golden Pine Pub — Book a table', 'goldenpine-theme' ); ?>"
				class="w-full h-full object-cover object-center"
				loading="eager"
				decoding="async"
			>
		<?php endif; ?>

		<!-- Gradient overlay -->
		<div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/55 to-[#1e1e1e]"></div>

		<!-- Noise texture -->
		<div
			aria-hidden="true"
			class="absolute inset-0 pointer-events-none opacity-[0.04] mix-blend-overlay"
			style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 200 200%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27n%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.75%27 numOctaves=%274%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23n)%27/%3E%3C/svg%3E'); background-size: 200px 200px;"
		></div>
	</div>

	<!-- Back link -->
	<div class="relative z-10 px-6 lg:px-12 pt-28 md:pt-32">
		<a
			class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-white/65 hover:text-gold transition-colors"
			href="<?php echo esc_url( home_url( '/' ) ); ?>"
		>
			<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
			<?php esc_html_e( 'Home', 'goldenpine-theme' ); ?>
		</a>
	</div>

	<!-- Heading -->
	<div class="relative z-10 px-6 lg:px-12 pb-16 md:pb-24 mt-auto">

		<?php if ( $hero_label ) : ?>
			<p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-8 flex items-center gap-3">
				<span class="h-px w-8 bg-gold inline-block"></span>
				<?php echo esc_html( $hero_label ); ?>
			</p>
		<?php endif; ?>

		<h1
			class="font-black uppercase text-white leading-[0.9] tracking-tight text-balance"
			style="font-size: clamp(3.5rem, 11vw, 10rem);"
		>
			<?php echo esc_html( $hero_heading1 ); ?><br>
			<span class="text-gold"><?php echo esc_html( $hero_heading2 ); ?></span>
		</h1>

	</div>

</section>
