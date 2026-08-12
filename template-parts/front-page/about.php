<?php
/**
 * Goldenpine Theme — template-parts/front-page/about.php
 *
 * About section for the front page.
 * Displays only the admin-set background image.
 * If no image is set, the section is not rendered.
 *
 * Content managed via Appearance > Customize > Front Page Settings > About Section.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$_gpine_about_bg_img_id  = absint( get_theme_mod( 'goldenpine_about_bg_image', 0 ) );
$_gpine_about_bg_img_url = $_gpine_about_bg_img_id ? wp_get_attachment_image_url( $_gpine_about_bg_img_id, 'full' ) : '';
$_gpine_about_bg_img_alt = $_gpine_about_bg_img_id ? get_post_meta( $_gpine_about_bg_img_id, '_wp_attachment_image_alt', true ) : '';

if ( ! $_gpine_about_bg_img_url ) {
    return;
}
?>

<section id="about" class="relative overflow-hidden h-[360px] md:h-screen">
    <img
        src="<?php echo esc_url( $_gpine_about_bg_img_url ); ?>"
        alt="<?php echo esc_attr( $_gpine_about_bg_img_alt ); ?>"
        loading="lazy"
        decoding="async"
        class="object-cover object-center"
        style="position: absolute; height: 100%; width: 100%; inset: 0px;"
    >
</section>
