/**
 * Goldenpine Theme — assets/js/page-specific-js/booking-page.js
 *
 * Booking page frontend logic:
 *   1. Toast notification system
 *   2. Live (blur) field validation
 *   3. Date button ↔ native date input sync
 *   4. AJAX form submission
 *   5. Duplicate-submit prevention
 *
 * Dependencies: jQuery (loaded via wp_enqueue_script dependency chain).
 * Localised as `gpineBooking` via wp_localize_script.
 *
 * @package GoldenpineTheme
 */

( function ( $ ) {
	'use strict';

	/* =========================================================================
	   CONFIG (overridden by wp_localize_script)
	   ========================================================================= */
	var cfg = window.gpineBooking || {};
	var ajaxUrl  = cfg.ajaxUrl  || '';
	var nonce    = cfg.nonce    || '';
	var i18n     = cfg.i18n    || {};

	var L = {
		success:      i18n.success     || 'Booking submitted! We\'ll confirm within the hour.',
		error:        i18n.error       || 'Something went wrong. Please try again.',
		validName:    i18n.validName   || 'Please enter your full name.',
		validPhone:   i18n.validPhone  || 'Please enter a valid phone number.',
		validEmail:   i18n.validEmail  || 'Please enter a valid email address.',
		validDate:    i18n.validDate   || 'Please select a date.',
		validTime:    i18n.validTime   || 'Please select a time.',
		validGuests:  i18n.validGuests || 'Please select number of guests.',
		submitting:   i18n.submitting  || 'Submitting\u2026',
		submit:       i18n.submit      || 'Confirm Reservation',
	};

	/* =========================================================================
	   1. TOAST NOTIFICATION SYSTEM
	   ========================================================================= */

	// Inject toast container + keyframe CSS once.
	( function initToastStyles() {
		if ( document.getElementById( 'gpine-toast-styles' ) ) return;

		var style = document.createElement( 'style' );
		style.id = 'gpine-toast-styles';
		style.textContent = [
			'#gpine-toast-container{',
			'  position:fixed;bottom:24px;right:24px;z-index:99999;',
			'  display:flex;flex-direction:column;gap:10px;pointer-events:none;',
			'}',
			'.gpine-toast{',
			'  display:flex;align-items:flex-start;gap:12px;',
			'  min-width:280px;max-width:380px;',
			'  padding:14px 18px;border-radius:14px;',
			'  font-family:inherit;font-size:14px;font-weight:500;line-height:1.5;',
			'  color:#fff;pointer-events:auto;',
			'  box-shadow:0 8px 32px rgba(0,0,0,.45);',
			'  animation:gpineToastIn .3s cubic-bezier(.22,1,.36,1) forwards;',
			'}',
			'.gpine-toast.is-leaving{',
			'  animation:gpineToastOut .25s ease forwards;',
			'}',
			'.gpine-toast--success{ background:#1a2a1a;border:1px solid #22C55E44; }',
			'.gpine-toast--success .gpine-toast__icon{ color:#22C55E; }',
			'.gpine-toast--error{ background:#2a1a1a;border:1px solid #EF444444; }',
			'.gpine-toast--error .gpine-toast__icon{ color:#EF4444; }',
			'.gpine-toast--warning{ background:#2a2211;border:1px solid #C9A84C55; }',
			'.gpine-toast--warning .gpine-toast__icon{ color:#C9A84C; }',
			'.gpine-toast__icon{ flex-shrink:0;margin-top:1px; }',
			'.gpine-toast__body{ flex:1; }',
			'.gpine-toast__title{ font-weight:700;font-size:13px;letter-spacing:.02em;margin-bottom:2px; }',
			'.gpine-toast__msg{ font-size:13px;opacity:.85; }',
			'.gpine-toast__close{',
			'  flex-shrink:0;background:none;border:none;cursor:pointer;',
			'  color:rgba(255,255,255,.4);padding:0 0 0 4px;font-size:18px;line-height:1;',
			'  transition:color .15s;margin-top:-1px;',
			'}',
			'.gpine-toast__close:hover{ color:#fff; }',
			'@keyframes gpineToastIn{',
			'  from{ opacity:0;transform:translateX(100%); }',
			'  to{ opacity:1;transform:translateX(0); }',
			'}',
			'@keyframes gpineToastOut{',
			'  from{ opacity:1;transform:translateX(0); }',
			'  to{ opacity:0;transform:translateX(60%); }',
			'}',
		].join( '' );

		document.head.appendChild( style );
	}() );

	var GpineToast = ( function () {
		var container = null;

		function getContainer() {
			if ( ! container ) {
				container = document.getElementById( 'gpine-toast-container' );
				if ( ! container ) {
					container = document.createElement( 'div' );
					container.id = 'gpine-toast-container';
					container.setAttribute( 'aria-live', 'polite' );
					container.setAttribute( 'aria-atomic', 'false' );
					document.body.appendChild( container );
				}
			}
			return container;
		}

		var ICONS = {
			success: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>',
			error:   '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>',
			warning: '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>',
		};

		function show( type, title, message, duration ) {
			duration = duration || 5000;
			var c   = getContainer();
			var el  = document.createElement( 'div' );

			el.className = 'gpine-toast gpine-toast--' + type;
			el.setAttribute( 'role', 'alert' );
			el.innerHTML =
				'<span class="gpine-toast__icon">' + ( ICONS[ type ] || '' ) + '</span>' +
				'<div class="gpine-toast__body">' +
					( title   ? '<div class="gpine-toast__title">' + title   + '</div>' : '' ) +
					( message ? '<div class="gpine-toast__msg">'   + message + '</div>' : '' ) +
				'</div>' +
				'<button class="gpine-toast__close" aria-label="Dismiss">&times;</button>';

			c.appendChild( el );

			el.querySelector( '.gpine-toast__close' ).addEventListener( 'click', function () {
				dismiss( el );
			} );

			var timer = setTimeout( function () { dismiss( el ); }, duration );

			el.addEventListener( 'mouseenter', function () { clearTimeout( timer ); } );
			el.addEventListener( 'mouseleave', function () {
				timer = setTimeout( function () { dismiss( el ); }, 2000 );
			} );
		}

		function dismiss( el ) {
			if ( ! el.parentNode ) return;
			el.classList.add( 'is-leaving' );
			el.addEventListener( 'animationend', function () {
				if ( el.parentNode ) el.parentNode.removeChild( el );
			}, { once: true } );
		}

		return {
			success: function ( title, msg, dur ) { show( 'success', title, msg, dur ); },
			error:   function ( title, msg, dur ) { show( 'error',   title, msg, dur ); },
			warning: function ( title, msg, dur ) { show( 'warning', title, msg, dur ); },
		};
	}() );

	/* =========================================================================
	   2. FIELD VALIDATION HELPERS
	   ========================================================================= */

	/**
	 * Show an error message on a field.
	 * Looks for a `.field-error` sibling within the field's parent `.flex` wrapper.
	 *
	 * @param {HTMLElement} field
	 * @param {string}      message
	 */
	function setFieldError( field, message ) {
		var wrapper = field.closest( '.flex.flex-col' );
		if ( ! wrapper ) return;

		var errEl = wrapper.querySelector( '.field-error' );
		if ( errEl ) {
			errEl.textContent = message;
			errEl.classList.remove( 'hidden' );
		}

		field.classList.add( 'border-red-500' );
		field.classList.remove( 'border-gold' );
	}

	/**
	 * Clear the error state for a field.
	 *
	 * @param {HTMLElement} field
	 */
	function clearFieldError( field ) {
		var wrapper = field.closest( '.flex.flex-col' );
		if ( ! wrapper ) return;

		var errEl = wrapper.querySelector( '.field-error' );
		if ( errEl ) {
			errEl.textContent = '';
			errEl.classList.add( 'hidden' );
		}

		field.classList.remove( 'border-red-500' );
	}

	/**
	 * Validate a single field. Returns true if valid.
	 *
	 * @param {HTMLElement} field
	 * @returns {boolean}
	 */
	function validateField( field ) {
		var id  = field.id;
		var val = field.value.trim();

		clearFieldError( field );

		if ( 'booking_name' === id ) {
			if ( val.length < 2 ) {
				setFieldError( field, L.validName );
				return false;
			}
		}

		if ( 'booking_phone' === id ) {
			if ( ! val || ! /^[+\d\s\-().]{6,20}$/.test( val ) ) {
				setFieldError( field, L.validPhone );
				return false;
			}
		}

		if ( 'booking_email' === id && val ) {
			if ( ! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( val ) ) {
				setFieldError( field, L.validEmail || 'Please enter a valid email address.' );
				return false;
			}
		}

		if ( 'booking_date' === id ) {
			if ( ! val ) {
				var dateWrapper = document.getElementById( 'booking_date_btn' );
				if ( dateWrapper ) setFieldError( field, L.validDate );
				return false;
			}
			var today = new Date();
			today.setHours( 0, 0, 0, 0 );
			var selected = new Date( val );
			if ( selected < today ) {
				setFieldError( field, 'Date cannot be in the past.' );
				return false;
			}
		}

		if ( 'booking_time' === id ) {
			if ( ! val ) {
				setFieldError( field, L.validTime );
				return false;
			}
		}

		if ( 'booking_guests' === id ) {
			if ( ! val ) {
				setFieldError( field, L.validGuests );
				return false;
			}
		}

		return true;
	}

	/**
	 * Validate all required fields. Returns true if the form is valid.
	 *
	 * @returns {boolean}
	 */
	function validateForm() {
		var required = [ 'booking_name', 'booking_phone', 'booking_date', 'booking_time', 'booking_guests' ];
		var isValid  = true;

		required.forEach( function ( id ) {
			var field = document.getElementById( id );
			if ( field && ! validateField( field ) ) {
				isValid = false;
			}
		} );

		// Also validate optional email if filled.
		var emailField = document.getElementById( 'booking_email' );
		if ( emailField && emailField.value.trim() ) {
			if ( ! validateField( emailField ) ) {
				isValid = false;
			}
		}

		return isValid;
	}

	/* =========================================================================
	   3. DATE BUTTON ↔ NATIVE INPUT SYNC
	   ========================================================================= */

	( function initDatePicker() {
		var dateInput   = document.getElementById( 'booking_date' );
		var dateBtn     = document.getElementById( 'booking_date_btn' );
		var dateDisplay = document.getElementById( 'booking_date_display' );

		if ( ! dateInput || ! dateBtn || ! dateDisplay ) return;

		dateBtn.addEventListener( 'click', function () {
			// Try showPicker() (modern) then fallback to focus/click.
			try {
				dateInput.showPicker();
			} catch ( e ) {
				dateInput.focus();
				dateInput.click();
			}
		} );

		dateInput.addEventListener( 'change', function () {
			var val = dateInput.value;
			if ( val ) {
				var d = new Date( val + 'T00:00:00' );
				var formatted = d.toLocaleDateString( 'en-US', {
					weekday: 'short',
					year: 'numeric',
					month: 'short',
					day: 'numeric',
				} );
				dateDisplay.textContent = formatted;
				dateDisplay.classList.remove( 'text-foreground/50' );
				dateDisplay.classList.add( 'text-foreground' );
				dateBtn.classList.add( 'border-gold' );
				clearFieldError( dateInput );
			} else {
				dateDisplay.textContent = 'Select date';
				dateDisplay.classList.add( 'text-foreground/50' );
				dateDisplay.classList.remove( 'text-foreground' );
				dateBtn.classList.remove( 'border-gold' );
			}
		} );
	}() );

	/* =========================================================================
	   4. LIVE VALIDATION (blur events)
	   ========================================================================= */

	$( document ).on( 'blur', '#gpine-booking-form .booking-field', function () {
		validateField( this );
	} );

	$( document ).on( 'input change', '#gpine-booking-form .booking-field', function () {
		// Clear error as soon as user starts correcting.
		clearFieldError( this );
	} );

	/* =========================================================================
	   5. AJAX FORM SUBMISSION
	   ========================================================================= */

	var isSubmitting = false;

	$( '#gpine-booking-form' ).on( 'submit', function ( e ) {
		e.preventDefault();

		if ( isSubmitting ) return;

		// Client-side validation gate.
		if ( ! validateForm() ) {
			GpineToast.error(
				'Validation Error',
				'Please fill in all required fields correctly.'
			);
			// Scroll to first error.
			var firstErr = document.querySelector( '#gpine-booking-form .field-error:not(.hidden)' );
			if ( firstErr ) {
				firstErr.scrollIntoView( { behavior: 'smooth', block: 'center' } );
			}
			return;
		}

		var $form   = $( this );
		var $btn    = $( '#gpine-booking-submit' );
		var $label  = $( '#gpine-booking-btn-label' );

		// Lock submit button.
		isSubmitting = true;
		$btn.prop( 'disabled', true ).addClass( 'opacity-60 cursor-not-allowed' );
		$label.text( L.submitting );

		var formData = $form.serializeArray();
		formData.push( { name: 'nonce', value: nonce } );

		$.ajax( {
			url:      ajaxUrl,
			type:     'POST',
			data:     $form.serialize(),
			dataType: 'json',
		} )
		.done( function ( response ) {
			if ( response.success ) {
				var ref = response.data.ref || '';
				GpineToast.success(
					'Booking Submitted!',
					ref
						? 'Reference: ' + ref + '. We\'ll confirm within the hour.'
						: L.success,
					8000
				);
				$form[0].reset();
				// Reset date button display.
				var dateDisplay = document.getElementById( 'booking_date_display' );
				if ( dateDisplay ) {
					dateDisplay.textContent = 'Select date';
					dateDisplay.classList.add( 'text-foreground/50' );
					dateDisplay.classList.remove( 'text-foreground' );
				}
				var dateBtn = document.getElementById( 'booking_date_btn' );
				if ( dateBtn ) dateBtn.classList.remove( 'border-gold' );

				$form[0].scrollIntoView( { behavior: 'smooth', block: 'start' } );

			} else {
				var errData = response.data || {};
				var msg     = errData.message || L.error;

				// Populate per-field errors from server validation.
				if ( errData.fields ) {
					$.each( errData.fields, function ( fieldId, fieldMsg ) {
						var field = document.getElementById( fieldId );
						if ( field ) setFieldError( field, fieldMsg );
					} );
				}

				GpineToast.error( 'Submission Failed', msg );
			}
		} )
		.fail( function ( jqXHR ) {
			var msg = L.error;
			if ( 0 === jqXHR.status ) {
				msg = 'Network error. Please check your connection and try again.';
			} else if ( 429 === jqXHR.status ) {
				msg = 'Too many submissions. Please wait before trying again.';
			} else if ( 403 === jqXHR.status ) {
				msg = 'Security check failed. Please refresh the page.';
			}
			GpineToast.error( 'Error', msg );
		} )
		.always( function () {
			isSubmitting = false;
			$btn.prop( 'disabled', false ).removeClass( 'opacity-60 cursor-not-allowed' );
			$label.text( L.submit );
		} );
	} );

}( jQuery ) );
