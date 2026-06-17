/**
 * Goldenpine Theme — assets/js/page-specific-js/hero-video.js
 *
 * Manages the Hero section video carousel.
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

    var videos    = [];
    var current   = 0;
    var container = null;

    // ------------------------------------------------------------------
    // Initialise — collect video elements and wire up events.
    // ------------------------------------------------------------------
    function init() {
        container = document.querySelector( '.hero-videos-container' );
        if ( ! container ) return;

        videos = Array.prototype.slice.call(
            container.querySelectorAll( '.hero-video' )
        );

        if ( videos.length === 0 ) return;

        // Single video: the loop attribute is already set in the markup.
        if ( videos.length === 1 ) {
            playVideo( videos[ 0 ] );
            return;
        }

        // Multiple videos: advance on 'ended'.
        videos.forEach( function ( video, index ) {
            video.addEventListener( 'ended', function () {
                advanceTo( ( index + 1 ) % videos.length );
            } );
        } );

        // Start the first video.
        playVideo( videos[ 0 ] );
    }

    // ------------------------------------------------------------------
    // Crossfade to a target video index.
    // ------------------------------------------------------------------
    function advanceTo( nextIndex ) {
        var outgoing = videos[ current ];
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

        current = nextIndex;
    }

    // ------------------------------------------------------------------
    // Play a video element; handle browsers that require user interaction.
    // ------------------------------------------------------------------
    function playVideo( video ) {
        // Ensure video is muted for autoplay policy compliance.
        video.muted = true;

        // If video metadata isn't loaded yet, wait for it.
        if ( video.readyState < 2 ) {
            video.addEventListener( 'loadedmetadata', function onLoaded() {
                video.removeEventListener( 'loadedmetadata', onLoaded );
                attemptPlay( video );
            } );
            video.load(); // Trigger loading if not started.
        } else {
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
