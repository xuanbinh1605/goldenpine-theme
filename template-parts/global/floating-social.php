<?php
/**
 * Goldenpine Theme — template-parts/global/floating-social.php
 *
 * Floating social media icons (Zalo, Messenger, Phone).
 * URLs are customizable via Customizer > Floating Social Icons.
 *
 * @package GoldenpineTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Retrieve URLs from Customizer.
$zalo_url      = get_theme_mod( 'goldenpine_floating_zalo_url', 'https://zalo.me/0937239867' );
$messenger_url = get_theme_mod( 'goldenpine_floating_messenger_url', 'https://m.me/cataniauthentic2013/' );
$phone_number  = get_theme_mod( 'goldenpine_floating_phone', '0935455667' );

// Exit early if all URLs are empty.
if ( empty( $zalo_url ) && empty( $messenger_url ) && empty( $phone_number ) ) {
    return;
}
?>

<div class="gpine-floating-social">

    <?php if ( ! empty( $zalo_url ) ) : ?>
        <a href="<?php echo esc_url( $zalo_url ); ?>" target="_blank" rel="noopener" class="gpine-floating-icon gpine-floating-icon-pulse gpine-floating-icon-zalo" title="<?php esc_attr_e( 'Chat via Zalo', 'goldenpine-theme' ); ?>" aria-label="<?php esc_attr_e( 'Zalo', 'goldenpine-theme' ); ?>">
            <svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <rect width="60" height="60" rx="30" fill="transparent"></rect>
                <path d="M44.3946 38C47.7068 37.7574 49.745 36.0607 50 32.4242C49.4904 28.7878 47.7068 27.0909 44.3946 26.6061C40.8278 27.0908 39.0445 28.7878 38.7898 32.4242C39.0447 36.0607 40.8278 37.7574 44.3946 38ZM44.3946 29.5152C45.9233 29.5152 46.6879 30.4848 46.6879 32.4242C46.6879 34.3637 45.9233 35.3332 44.3946 35.3332C42.8661 35.3332 42.1018 34.3637 41.847 32.4242C42.1018 30.4848 42.8661 29.5152 44.3946 29.5152ZM27.3247 38C28.5988 38 29.6177 37.5152 30.3821 36.788C30.6369 37.5152 31.1464 38 31.9106 38C32.9297 38 33.4393 37.2726 33.4393 36.0607V28.7878C33.4393 27.3334 32.9297 26.8485 31.9106 26.8485C31.1464 26.8485 30.6369 27.3334 30.3821 28.0606C29.8725 27.0909 28.8534 26.6061 27.3247 26.6061C24.2673 27.0909 22.4842 29.0304 22.2292 32.4243C22.4842 36.0606 24.2675 37.7574 27.3247 38ZM27.8342 29.5152C29.3628 29.5152 30.1273 30.4848 30.3821 32.4242C30.1273 34.3637 29.3628 35.3332 27.8342 35.3332C26.3055 35.3332 25.541 34.3637 25.2864 32.4242C25.541 30.4848 26.3055 29.5152 27.8342 29.5152ZM36.2419 38C37.261 38 37.7706 37.5152 37.7706 36.3029V23.6972C37.7706 22.7272 37.2609 22 36.2419 22C35.2227 22 34.7134 22.7272 34.7134 23.6972V36.3029C34.7134 37.5152 35.2227 38 36.2419 38ZM10.0001 24.6668C10.2547 25.3939 10.5093 25.8789 11.5283 25.8789H17.1338L10.2546 35.3332C10 35.5757 10 36.0607 10 36.5455C10 37.2726 10.5092 37.7574 11.7832 38H20.191C21.2101 37.7574 21.7194 37.2726 21.7194 36.5455C21.7194 35.5757 21.2101 35.3333 20.191 35.0909H14.0765L20.7005 26.1211C21.2101 25.6363 21.2101 25.1516 21.2101 24.6668C21.2101 23.6972 20.7005 23.2122 19.4266 23.2122H11.5283C10.5093 23.2122 10.2547 23.6972 10.0001 24.6668Z" fill="white"></path>
            </svg>
        </a>
    <?php endif; ?>

    <?php if ( ! empty( $messenger_url ) ) : ?>
        <a href="<?php echo esc_url( $messenger_url ); ?>" target="_blank" rel="noopener" class="gpine-floating-icon gpine-floating-icon-pulse gpine-floating-icon-messenger" title="<?php esc_attr_e( 'Chat via Messenger', 'goldenpine-theme' ); ?>" aria-label="<?php esc_attr_e( 'Messenger', 'goldenpine-theme' ); ?>">
            <svg fill="white" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 2C6.477 2 2 6.145 2 11.25c0 2.9 1.45 5.48 3.72 7.19V22l3.38-1.86c.9.25 1.86.39 2.9.39 5.523 0 10-4.145 10-9.25S17.523 2 12 2zm1.025 12.5l-2.575-2.75-5.025 2.75 5.525-5.875 2.575 2.75 4.975-2.75-5.475 5.875z"></path>
            </svg>
        </a>
    <?php endif; ?>

    <?php if ( ! empty( $phone_number ) ) : ?>
        <a href="tel:<?php echo esc_attr( $phone_number ); ?>" class="gpine-floating-icon gpine-floating-icon-pulse gpine-floating-icon-phone" title="<?php echo esc_attr( $phone_number ); ?>" aria-label="<?php esc_attr_e( 'Call', 'goldenpine-theme' ); ?>">
            <svg fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"></path>
            </svg>
        </a>
    <?php endif; ?>

</div>
