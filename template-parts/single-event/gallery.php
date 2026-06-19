<?php
/**
 * Template Part — Single Event Gallery Component
 *
 * Displays the event photo gallery if images exist.
 * Gallery images are stored as comma-separated attachment IDs
 * in the _gpine_event_gallery meta field.
 *
 * This component is designed to be included within the description
 * section's left column. Returns early if no gallery images exist.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Fetch gallery attachment IDs.
$gallery_ids_raw = get_post_meta( get_the_ID(), '_gpine_event_gallery', true );
$gallery_ids     = array_filter( array_map( 'absint', explode( ',', $gallery_ids_raw ) ) );

if ( empty( $gallery_ids ) ) {
	return;
}

// Build gallery data array.
$gallery_images = [];
foreach ( $gallery_ids as $attachment_id ) {
	$image_url = wp_get_attachment_image_url( $attachment_id, 'large' );
	$image_alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
	$image_title = get_the_title( $attachment_id );

	if ( ! $image_url ) {
		continue; // Skip deleted or missing attachments.
	}

	$gallery_images[] = [
		'url'   => $image_url,
		'alt'   => $image_alt ? $image_alt : $image_title,
		'title' => $image_title,
	];
}

if ( empty( $gallery_images ) ) {
	return;
}
?>

<div class="mt-12">
	<p class="text-xs font-bold tracking-[0.45em] uppercase text-gold mb-6 flex items-center gap-3">
		<span class="h-px w-8 bg-gold inline-block"></span>
		<?php esc_html_e( 'Gallery', 'goldenpine-theme' ); ?>
	</p>

	<div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
		<?php foreach ( $gallery_images as $index => $image ) : ?>
			<div class="relative overflow-hidden rounded-2xl aspect-square group box-glow-gold-hover">
				<img
					src="<?php echo esc_url( $image['url'] ); ?>"
					alt="<?php echo esc_attr( $image['alt'] ); ?>"
					class="object-cover transition-transform duration-700 group-hover:scale-110"
					style="position: absolute; height: 100%; width: 100%; inset: 0px;"
					loading="lazy"
					decoding="async"
				>
				<div class="absolute inset-0 rounded-2xl ring-0 group-hover:ring-1 ring-gold/40 transition-all duration-500 pointer-events-none"></div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
