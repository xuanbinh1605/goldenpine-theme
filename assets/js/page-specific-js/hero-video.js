/**
 * Goldenpine Theme — assets/js/page-specific-js/hero-video.js
 *
 * Manages the Hero section video carousel (PC and Mobile).
 *
 * Behaviour:
 *  - Single video:   loops indefinitely (loop attr set server-side).
 *  - Multiple videos: on each video's 'ended' event, crossfades to the
 *    next video in sequence and loops back to the first after the last.
 *
 * CSS classes toggled (defined in assets/css/components/_hero-video.css):
 *  .is-active  — currently visible video (opacity: 1)
 *  .is-leaving — video fading out       (opacity: 0, brief overlap)
 *
 * @package GoldenpineTheme
 */

/* global goldenpineHeroVideo */
window.goldenpineHeroVideo = ( function () {
    'use strict';

    // ------------------------------------------------------------------
    // Initialise — pick the correct container for the current viewport.
    // Only one container is ever active to avoid background videos
    // consuming autoplay budget and blocking crossfade transitions.
    // ------------------------------------------------------------------
    function init() {
        var mdBreakpoint  = 768; // must match Tailwind md: 768px
        var isMobile      = window.innerWidth < mdBreakpoint;
        var primaryDevice = isMobile ? 'mobile' : 'pc';
        var fallbackDevice= isMobile ? 'pc'     : 'mobile';

        var primary  = document.querySelector( '.hero-videos-container[data-device="' + primaryDevice  + '"]' );
        var fallback = document.querySelector( '.hero-videos-container[data-device="' + fallbackDevice + '"]' );

        // Prefer the device-appropriate container; fall back when it has no videos.
        var target = ( primary && primary.querySelector( '.hero-video' ) ) ? primary : fallback;
        if ( target ) {
            initContainer( target );
        }
    }

    // ------------------------------------------------------------------
    // Initialise a single video container.
    // ------------------------------------------------------------------
    function initContainer( container ) {
        var videos = Array.prototype.slice.call(
            container.querySelectorAll( '.hero-video' )
        );

        if ( videos.length === 0 ) return;

        // Single video: the loop attribute is already set in the markup.
        if ( videos.length === 1 ) {
            playVideo( videos[ 0 ] );
            return;
        }

        // Multiple videos: advance on 'ended'.
        var current = 0;

        videos.forEach( function ( video, index ) {
            video.addEventListener( 'ended', function () {
                var nextIndex = ( index + 1 ) % videos.length;
                advanceTo( videos, current, nextIndex );
                current = nextIndex;
            } );
        } );

        // Start the first video.
        playVideo( videos[ 0 ] );
    }

    // ------------------------------------------------------------------
    // Crossfade to a target video index.
    // ------------------------------------------------------------------
    function advanceTo( videos, currentIndex, nextIndex ) {
        var outgoing = videos[ currentIndex ];
        var incoming = videos[ nextIndex ];

        // Start fading out the current video.
        outgoing.classList.add( 'is-leaving' );
        outgoing.classList.remove( 'is-active' );

        // Show the next video.
        incoming.classList.add( 'is-active' );
        incoming.classList.remove( 'is-leaving' );
        playVideo( incoming );

        // After the CSS transition completes, clean up the outgoing video.
        var transitionDuration = 1000; // matches CSS transition: opacity 1s
        setTimeout( function () {
            outgoing.classList.remove( 'is-leaving' );
            outgoing.pause();
            outgoing.currentTime = 0;
        }, transitionDuration );
    }

    // ------------------------------------------------------------------
    // Play a video element; handle browsers that require user interaction.
    // ------------------------------------------------------------------
    function playVideo( video ) {
        // Ensure video is muted for autoplay policy compliance.
        video.muted = true;

        if ( video.readyState === 0 ) {
            // Nothing loaded yet — wait for the browser to signal it can play.
            var onCanPlay = function () {
                video.removeEventListener( 'canplay', onCanPlay );
                attemptPlay( video );
            };
            video.addEventListener( 'canplay', onCanPlay );
            video.load();
        } else {
            // Metadata or data already available — play immediately.
            attemptPlay( video );
        }
    }

    // ------------------------------------------------------------------
    // Attempt to play with error handling.
    // ------------------------------------------------------------------
    function attemptPlay( video ) {
        var playPromise = video.play();

        if ( playPromise !== undefined ) {
            playPromise.catch( function ( error ) {
                // Log for debugging but don't break the page.
                if ( window.console && console.warn ) {
                    console.warn( 'Hero video play failed:', error );
                }
            } );
        }
    }

    // ------------------------------------------------------------------
    // Public API.
    // ------------------------------------------------------------------
    return { init: init };

} )();

// Self-bootstrap on DOMContentLoaded.
document.addEventListener( 'DOMContentLoaded', function () {
    goldenpineHeroVideo.init();
} );
