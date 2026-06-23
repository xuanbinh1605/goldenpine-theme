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
    var log = ( window.console && console.log )
        ? function() { console.log.apply( console, [ '[HeroVideo]' ].concat( Array.prototype.slice.call( arguments ) ) ); }
        : function() {};

    function init() {
        var mdBreakpoint  = 768;
        var isMobile      = window.innerWidth < mdBreakpoint;
        var primaryDevice = isMobile ? 'mobile' : 'pc';
        var fallbackDevice= isMobile ? 'pc'     : 'mobile';

        log( 'init() — innerWidth:', window.innerWidth, '| device:', primaryDevice );

        var primary  = document.querySelector( '.hero-videos-container[data-device="' + primaryDevice  + '"]' );
        var fallback = document.querySelector( '.hero-videos-container[data-device="' + fallbackDevice + '"]' );

        log( 'primary container found:', !!primary, '| has videos:', !! ( primary && primary.querySelector( '.hero-video' ) ) );
        log( 'fallback container found:', !!fallback );

        var target = ( primary && primary.querySelector( '.hero-video' ) ) ? primary : fallback;
        if ( target ) {
            log( 'activating container [data-device="' + target.dataset.device + '"]' );
            initContainer( target );
        } else {
            log( 'WARN: no usable container found — hero section will be empty.' );
        }
    }

    // ------------------------------------------------------------------
    // Initialise a single video container.
    // ------------------------------------------------------------------
    function initContainer( container ) {
        var device = container.dataset.device || '?';
        var videos = Array.prototype.slice.call(
            container.querySelectorAll( '.hero-video' )
        );

        log( '[' + device + '] initContainer — videos found:', videos.length );

        if ( videos.length === 0 ) {
            log( '[' + device + '] WARN: no .hero-video elements inside container.' );
            return;
        }

        // Single video: the loop attribute is already set in the markup.
        if ( videos.length === 1 ) {
            log( '[' + device + '] single video — will loop.' );
            playVideo( videos[ 0 ], device, 0 );
            return;
        }

        // Multiple videos: advance on 'ended'.
        var current       = 0;
        var transitioning = false;

        // Ensure all non-first videos are fully stopped on init.
        // The browser may have started buffering or playing them due to
        // markup attributes before JS takes control.
        videos.forEach( function ( video, i ) {
            if ( i > 0 ) {
                video.pause();
                video.currentTime = 0;
                log( '[' + device + '] video[' + i + '] paused and reset on init.' );
            }
        } );

        videos.forEach( function ( video, i ) {
            log( '[' + device + '] video[' + i + '] src:', video.src, '| readyState:', video.readyState, '| loop:', video.loop );

            video.addEventListener( 'ended', function () {
                var firedIndex = videos.indexOf( video );
                log( '[' + device + '] ended fired — video[' + firedIndex + '] | current:', current, '| transitioning:', transitioning );

                if ( transitioning ) {
                    log( '[' + device + '] SKIP: transition already in progress.' );
                    return;
                }
                if ( firedIndex !== current ) {
                    log( '[' + device + '] SKIP: ended from non-active video (index ' + firedIndex + ', expected ' + current + ').' );
                    return;
                }

                transitioning = true;
                var nextIndex = ( current + 1 ) % videos.length;
                log( '[' + device + '] advancing ' + current + ' → ' + nextIndex );
                advanceTo( nextIndex );
            } );

            // Log key lifecycle events for every video.
            video.addEventListener( 'play',   function () { log( '[' + device + '] video[' + i + '] play' ); } );
            video.addEventListener( 'pause',  function () { log( '[' + device + '] video[' + i + '] pause  (currentTime=' + video.currentTime.toFixed(2) + ')' ); } );
            video.addEventListener( 'error',  function () { log( '[' + device + '] video[' + i + '] ERROR:', video.error ); } );
            video.addEventListener( 'stalled',function () { log( '[' + device + '] video[' + i + '] stalled' ); } );
            video.addEventListener( 'waiting',function () { log( '[' + device + '] video[' + i + '] waiting (buffering)' ); } );
        } );

        function advanceTo( nextIndex ) {
            var outgoing = videos[ current ];
            var incoming = videos[ nextIndex ];

            log( '[' + device + '] advanceTo(' + nextIndex + ') — outgoing readyState:', outgoing.readyState, '| incoming readyState:', incoming.readyState );

            outgoing.classList.add( 'is-leaving' );
            outgoing.classList.remove( 'is-active' );

            incoming.classList.add( 'is-active' );
            incoming.classList.remove( 'is-leaving' );
            incoming.currentTime = 0; // Always start the incoming video from the beginning.
            playVideo( incoming, device, nextIndex );

            var prev = current;
            current  = nextIndex;

            setTimeout( function () {
                var out = videos[ prev ];
                out.classList.remove( 'is-leaving' );
                out.pause();
                out.currentTime = 0;
                transitioning = false;
                log( '[' + device + '] cleanup done — video[' + prev + '] reset. transitioning=false.' );
            }, 1100 );
        }

        log( '[' + device + '] starting video[0]' );
        playVideo( videos[ 0 ], device, 0 );
    }

    // ------------------------------------------------------------------
    // Play a video element; handle browsers that require user interaction.
    // ------------------------------------------------------------------
    function playVideo( video, device, index ) {
        device = device || '?';
        index  = ( index !== undefined ) ? index : '?';
        video.muted = true;

        log( '[' + device + '] playVideo[' + index + '] — readyState:', video.readyState );

        if ( video.readyState === 0 ) {
            log( '[' + device + '] video[' + index + '] readyState=0, loading then waiting for canplay.' );
            var onCanPlay = function () {
                video.removeEventListener( 'canplay', onCanPlay );
                log( '[' + device + '] video[' + index + '] canplay fired — attempting play.' );
                attemptPlay( video, device, index );
            };
            video.addEventListener( 'canplay', onCanPlay );
            video.load();
        } else {
            log( '[' + device + '] video[' + index + '] readyState=' + video.readyState + ' — attempting play immediately.' );
            attemptPlay( video, device, index );
        }
    }

    // ------------------------------------------------------------------
    // Attempt to play with error handling.
    // ------------------------------------------------------------------
    function attemptPlay( video, device, index ) {
        var playPromise = video.play();

        if ( playPromise !== undefined ) {
            playPromise
                .then( function () {
                    log( '[' + ( device || '?' ) + '] video[' + index + '] play() resolved OK.' );
                } )
                .catch( function ( error ) {
                    log( '[' + ( device || '?' ) + '] video[' + index + '] play() REJECTED:', error.name, error.message );
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
