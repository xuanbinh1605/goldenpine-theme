<?php
/**
 * Template Part — Single Event Essentials Sidebar
 *
 * Displays event essentials in card format:
 *  - Performer Image (if exists)
 *  - Performer
 *  - Dress Code
 *  - Location
 *
 * Each field is conditionally rendered. If all fields are empty,
 * the entire section is not displayed.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Fetch event metadata.
$performer_image_id   = get_post_meta( get_the_ID(), '_gpine_event_performer_image', true );
$performer            = get_post_meta( get_the_ID(), '_gpine_event_performer', true );
$dress_code           = get_post_meta( get_the_ID(), '_gpine_event_dress_code', true );
$location_name        = get_post_meta( get_the_ID(), '_gpine_event_location_name', true );
$location_description = get_post_meta( get_the_ID(), '_gpine_event_location_description', true );

// Check if any essentials data exists.
$has_essentials = $performer_image_id || $performer || $dress_code || $location_name || $location_description;

if ( ! $has_essentials ) {
	return;
}
?>

<div class="flex flex-col gap-4">

	<p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-2 flex items-center gap-3">
		<span class="h-px w-8 bg-gold inline-block"></span>
		<?php esc_html_e( 'The Essentials', 'goldenpine-theme' ); ?>
	</p>

	<!-- Performer Image -->
	<?php if ( $performer_image_id ) : ?>
		<?php
		$performer_image_url = wp_get_attachment_image_url( $performer_image_id, 'full' );
		$performer_image_alt = get_post_meta( $performer_image_id, '_wp_attachment_image_alt', true );
		?>
		<?php if ( $performer_image_url ) : ?>
			<div class="rounded-3xl overflow-hidden border border-border bg-card">
				<img
					src="<?php echo esc_url( $performer_image_url ); ?>"
					alt="<?php echo esc_attr( $performer_image_alt ? $performer_image_alt : 'Performer' ); ?>"
					class="w-full h-auto object-cover"
					loading="lazy"
					decoding="async"
				>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<!-- Performer -->
	<?php if ( $performer ) : ?>
		<div class="flex items-center gap-5 rounded-3xl border border-border bg-card p-6 box-glow-gold-hover">
			<div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center shrink-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><path d="M16 3.128a4 4 0 0 1 0 7.744"></path><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><circle cx="9" cy="7" r="4"></circle></svg>
			</div>
			<div class="flex-1">
				<p class="text-[10px] font-bold tracking-widest uppercase text-foreground/55 mb-1">
					<?php esc_html_e( 'Performer', 'goldenpine-theme' ); ?>
				</p>
				<p class="text-base md:text-lg font-semibold text-foreground">
					<?php echo esc_html( $performer ); ?>
				</p>
			</div>
		</div>
	<?php endif; ?>

	<!-- Dress Code -->
	<?php if ( $dress_code ) : ?>
		<div class="flex items-center gap-5 rounded-3xl border border-border bg-card p-6 box-glow-gold-hover">
			<div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center shrink-0">
				<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold" aria-hidden="true"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"></path></svg>
			</div>
			<div class="flex-1">
				<p class="text-[10px] font-bold tracking-widest uppercase text-foreground/55 mb-1">
					<?php esc_html_e( 'Dress Code', 'goldenpine-theme' ); ?>
				</p>
				<p class="text-base md:text-lg font-semibold text-foreground">
					<?php echo esc_html( $dress_code ); ?>
				</p>
			</div>
		</div>
	<?php endif; ?>

	<!-- Location -->
	<?php if ( $location_name || $location_description ) : ?>
		<div class="rounded-3xl border border-border bg-card p-6 mt-2">
			<p class="text-[10px] font-bold tracking-widest uppercase text-gold mb-3">
				<?php esc_html_e( 'Location', 'goldenpine-theme' ); ?>
			</p>
			<?php if ( $location_name ) : ?>
				<p class="text-lg font-black uppercase text-foreground tracking-tight">
					<?php echo esc_html( $location_name ); ?>
				</p>
			<?php endif; ?>
			<?php if ( $location_description ) : ?>
				<p class="text-sm font-light text-foreground/65 leading-relaxed mt-1">
					<?php echo esc_html( $location_description ); ?>
				</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

</div>
