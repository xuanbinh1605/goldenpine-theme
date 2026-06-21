/**
 * GIF Hover Control
 *
 * Desktop : shows the poster/thumbnail on load; swaps to the animated GIF on
 *           mouseenter and reverts on mouseleave.
 * Mobile  : immediately sets the GIF src so it auto-plays.
 *
 * PHP outputs:
 *   src           = medium-sized WordPress thumbnail (visible immediately)
 *   data-gif-src  = original animated GIF URL
 *
 * The card element must carry the class `gpine-gif-card`.
 */
( function () {
	'use strict';

	function initGifCards() {
		var isMobile = window.matchMedia( '(max-width: 768px)' ).matches;
		var cards    = document.querySelectorAll( '.gpine-gif-card' );

		cards.forEach( function ( card ) {
			var img = card.querySelector( 'img[data-gif-src]' );
			if ( ! img ) return;

			var gifSrc    = img.getAttribute( 'data-gif-src' );
			var staticSrc = img.getAttribute( 'src' );

			if ( ! gifSrc ) return;

			if ( isMobile ) {
				// Auto-play on mobile.
				img.src = gifSrc;
				return;
			}

			// Desktop: poster shown by default; play GIF only while hovering.
			card.addEventListener( 'mouseenter', function () {
				img.src = gifSrc;
			} );

			card.addEventListener( 'mouseleave', function () {
				img.src = staticSrc;
			} );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initGifCards );
	} else {
		initGifCards();
	}
}() );
