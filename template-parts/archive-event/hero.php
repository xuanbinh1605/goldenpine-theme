<?php
/**
 * Template Part — Events Archive Hero
 *
 * Full-bleed hero section with a static background image, gradient overlays,
 * noise texture, and the archive heading.
 *
 * Content managed via Appearance > Customize > Events Archive > Hero Section.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_gpine_ev_hero_label   = get_theme_mod( 'goldenpine_events_hero_label',    'Events' );
$_gpine_ev_hero_h1      = get_theme_mod( 'goldenpine_events_hero_heading_1', "What's on" );
$_gpine_ev_hero_h2      = get_theme_mod( 'goldenpine_events_hero_heading_2', 'at Golden Pine.' );

$_gpine_ev_hero_img_id  = absint( get_theme_mod( 'goldenpine_events_hero_image', 53 ) );
$_gpine_ev_hero_img_url = $_gpine_ev_hero_img_id
	? wp_get_attachment_image_url( $_gpine_ev_hero_img_id, 'full' )
	: '';
$_gpine_ev_hero_img_alt = $_gpine_ev_hero_img_id
	? (string) get_post_meta( $_gpine_ev_hero_img_id, '_wp_attachment_image_alt', true )
	: '';
?>

<section class="relative min-h-[75vh] flex flex-col justify-end overflow-hidden">

	<!-- Background Layer -->
	<div class="absolute inset-0 z-0">

		<?php if ( $_gpine_ev_hero_img_url ) : ?>
			<img
				src="<?php echo esc_url( $_gpine_ev_hero_img_url ); ?>"
				alt="<?php echo esc_attr( $_gpine_ev_hero_img_alt ); ?>"
				loading="eager"
				decoding="async"
				class="object-cover object-center"
				style="position: absolute; height: 100%; width: 100%; inset: 0px;"
			>
		<?php else : ?>
			<div class="absolute inset-0 bg-background"></div>
		<?php endif; ?>

		<!-- Gradient overlay -->
		<div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/55 to-[#1e1e1e]"></div>

		<!-- Noise texture -->
		<div
			aria-hidden="true"
			class="absolute inset-0 pointer-events-none opacity-[0.04] mix-blend-overlay"
			style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22n%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.75%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22/%3E%3C/svg%3E'); background-size: 200px 200px;"
		></div>

	</div>

	<!-- Content -->
	<div class="relative z-10 px-6 lg:px-12 pt-32 pb-16 md:pb-24 mt-auto">

		<?php if ( $_gpine_ev_hero_label ) : ?>
			<p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-8 flex items-center gap-3">
				<span class="h-px w-8 bg-gold inline-block"></span>
				<?php echo esc_html( $_gpine_ev_hero_label ); ?>
			</p>
		<?php endif; ?>

		<h1
			class="font-black uppercase text-white leading-[0.9] tracking-tight text-balance"
			style="font-size: clamp(3.5rem, 11vw, 10rem);"
		>
			<?php echo esc_html( $_gpine_ev_hero_h1 ); ?><br>
			<span class="text-gold"><?php echo esc_html( $_gpine_ev_hero_h2 ); ?></span>
		</h1>

	</div>

</section>
