/**
 * Utility functions — shared across the whole theme.
 *
 * Exposed as a global `goldenpineUtils` object so any page-specific
 * script can call individual helpers without re-importing modules.
 *
 * @package GoldenpineTheme
 */

/* global goldenpineUtils */
window.goldenpineUtils = ( function () {
    'use strict';

    // ------------------------------------------------------------------
    // Sticky header — adds .is-scrolled when page is scrolled
    // ------------------------------------------------------------------
    function initStickyHeader() {
        var header = document.querySelector( '.site-header' );
        if ( ! header ) return;

        var threshold = 10;

        function onScroll() {
            header.classList.toggle( 'is-scrolled', window.scrollY > threshold );
        }

        window.addEventListener( 'scroll', onScroll, { passive: true } );
        onScroll(); // apply on load if already scrolled
    }

    // ------------------------------------------------------------------
    // Smooth scroll — respect prefers-reduced-motion
    // ------------------------------------------------------------------
    function initSmoothScroll() {
        var prefersReduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
        if ( prefersReduced ) return;

        document.querySelectorAll( 'a[href^="#"]' ).forEach( function ( anchor ) {
            anchor.addEventListener( 'click', function ( e ) {
                var target = document.querySelector( this.getAttribute( 'href' ) );
                if ( ! target ) return;
                e.preventDefault();
                target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
            } );
        } );
    }

    // ------------------------------------------------------------------
    // Lazy-load images via IntersectionObserver
    // (fallback: loads images immediately if observer not supported)
    // ------------------------------------------------------------------
    function initLazyImages() {
        var lazyImages = document.querySelectorAll( 'img[data-src]' );
        if ( ! lazyImages.length ) return;

        if ( 'IntersectionObserver' in window ) {
            var observer = new IntersectionObserver(
                function ( entries ) {
                    entries.forEach( function ( entry ) {
                        if ( entry.isIntersecting ) {
                            var img = entry.target;
                            img.src = img.dataset.src;
                            if ( img.dataset.srcset ) img.srcset = img.dataset.srcset;
                            img.removeAttribute( 'data-src' );
                            observer.unobserve( img );
                        }
                    } );
                },
                { rootMargin: '200px 0px' }
            );
            lazyImages.forEach( function ( img ) { observer.observe( img ); } );
        } else {
            lazyImages.forEach( function ( img ) {
                img.src = img.dataset.src;
            } );
        }
    }

    // ------------------------------------------------------------------
    // Debounce helper
    // ------------------------------------------------------------------
    function debounce( fn, delay ) {
        var timer;
        return function () {
            clearTimeout( timer );
            timer = setTimeout( fn.bind( this, arguments ), delay );
        };
    }

    // ------------------------------------------------------------------
    // Trap focus inside an element (for modals, drawers)
    // ------------------------------------------------------------------
    function trapFocus( element ) {
        var focusable = element.querySelectorAll(
            'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
        );
        if ( ! focusable.length ) return function () {};

        var first = focusable[ 0 ];
        var last  = focusable[ focusable.length - 1 ];

        function onKeyDown( e ) {
            if ( e.key !== 'Tab' ) return;
            if ( e.shiftKey ) {
                if ( document.activeElement === first ) { e.preventDefault(); last.focus(); }
            } else {
                if ( document.activeElement === last )  { e.preventDefault(); first.focus(); }
            }
        }

        element.addEventListener( 'keydown', onKeyDown );
        first.focus();

        // Return a cleanup function
        return function () {
            element.removeEventListener( 'keydown', onKeyDown );
        };
    }

    // Public API
    return {
        initStickyHeader: initStickyHeader,
        initSmoothScroll: initSmoothScroll,
        initLazyImages:   initLazyImages,
        debounce:         debounce,
        trapFocus:        trapFocus,
    };

} )();
