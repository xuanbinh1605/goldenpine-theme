/**
 * Customizer Live Preview — JavaScript
 *
 * Runs inside the Customizer preview frame. Listens for setting changes
 * posted by wp.customize and applies them instantly without a full refresh,
 * providing a smooth live-preview experience.
 *
 * Each wp.customize() call maps to a Customizer setting ID defined in
 * inc/customizer/. Use `selective_refresh` for partial refreshes on the
 * server side where plain JS updates are not sufficient.
 *
 * @see inc/customizer/customizer-setup.php
 * @package GoldenpineTheme
 */

( function ( $ ) {
    'use strict';

    /* ----------------------------------------------------------------
       Colours
    ---------------------------------------------------------------- */
    wp.customize( 'goldenpine_primary_color', function ( value ) {
        value.bind( function ( newVal ) {
            document.documentElement.style.setProperty( '--color-primary', newVal );
        } );
    } );

    wp.customize( 'goldenpine_secondary_color', function ( value ) {
        value.bind( function ( newVal ) {
            document.documentElement.style.setProperty( '--color-secondary', newVal );
        } );
    } );

    /* ----------------------------------------------------------------
       Typography
    ---------------------------------------------------------------- */
    wp.customize( 'goldenpine_body_font_size', function ( value ) {
        value.bind( function ( newVal ) {
            document.body.style.fontSize = newVal + 'px';
        } );
    } );

    /* ----------------------------------------------------------------
       Site identity
    ---------------------------------------------------------------- */
    wp.customize( 'blogname', function ( value ) {
        value.bind( function ( newVal ) {
            document.querySelectorAll( '.site-name' ).forEach( function ( el ) {
                el.textContent = newVal;
            } );
        } );
    } );

    wp.customize( 'blogdescription', function ( value ) {
        value.bind( function ( newVal ) {
            document.querySelectorAll( '.site-description' ).forEach( function ( el ) {
                el.textContent = newVal;
            } );
        } );
    } );

    /* ----------------------------------------------------------------
       Hero section (front page)
    ---------------------------------------------------------------- */
    wp.customize( 'goldenpine_hero_heading', function ( value ) {
        value.bind( function ( newVal ) {
            var el = document.querySelector( '.hero__heading' );
            if ( el ) el.textContent = newVal;
        } );
    } );

    wp.customize( 'goldenpine_hero_subheading', function ( value ) {
        value.bind( function ( newVal ) {
            var el = document.querySelector( '.hero__subheading' );
            if ( el ) el.textContent = newVal;
        } );
    } );

    wp.customize( 'goldenpine_hero_cta_label', function ( value ) {
        value.bind( function ( newVal ) {
            var el = document.querySelector( '.hero__cta' );
            if ( el ) el.textContent = newVal;
        } );
    } );

} )( jQuery );
