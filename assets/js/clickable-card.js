/**
 * Clickable Card Handler
 *
 * Makes cards with the class 'gpine-clickable-card' clickable while
 * preserving functionality of interactive elements (links, buttons) inside.
 *
 * @package GoldenpineTheme
 */
(function () {
	'use strict';

	/**
	 * Initialize clickable cards
	 */
	function initClickableCards() {
		var cards = document.querySelectorAll('.gpine-clickable-card');

		cards.forEach(function (card) {
			var targetUrl = card.getAttribute('data-href');
			if (!targetUrl) return;

			// Handle click events
			card.addEventListener('click', function (e) {
				// Check if the click target is an interactive element or inside one
				var target = e.target;
				var isInteractive = false;

				// Walk up the DOM tree to check if we're inside an interactive element
				while (target && target !== card) {
					if (
						target.tagName === 'A' ||
						target.tagName === 'BUTTON' ||
						target.hasAttribute('onclick') ||
						target.hasAttribute('data-action')
					) {
						isInteractive = true;
						break;
					}
					target = target.parentElement;
				}

				// If not clicking an interactive element, navigate to the card URL
				if (!isInteractive) {
					window.location.href = targetUrl;
				}
			});

			// Handle keyboard events for accessibility (Enter or Space)
			card.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					window.location.href = targetUrl;
				}
			});
		});
	}

	// Initialize on DOM ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initClickableCards);
	} else {
		initClickableCards();
	}
})();
