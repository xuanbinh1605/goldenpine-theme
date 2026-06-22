/**
 * Goldenpine Theme — assets/js/page-specific-js/booking-page.js
 *
 * Booking page frontend logic:
 *   1. CSS injection  — form design improvements + calendar styles
 *   2. Toast system   — slide-in notifications (success / error / warning)
 *   3. Validation     — live (blur) + full form-submit check
 *   4. Date picker    — fully custom calendar with event-date highlights
 *   5. AJAX submit    — secure, duplicate-safe form submission
 *
 * Depends on jQuery (enqueued via wp_enqueue_script).
 * Config delivered server-side via wp_localize_script → `gpineBooking`.
 *
 * @package GoldenpineTheme
 */

( function ( $ ) {
	'use strict';

	/* =========================================================================
	   CONFIG
	   ========================================================================= */
	var cfg        = window.gpineBooking || {};
	var ajaxUrl    = cfg.ajaxUrl    || '';
	var nonce      = cfg.nonce      || '';
	var i18n       = cfg.i18n       || {};
	var eventDates = cfg.eventDates || []; // array of 'YYYY-MM-DD' strings

	var L = {
		success:      i18n.success     || 'Booking submitted!',
		error:        i18n.error       || 'Something went wrong. Please try again.',
		validName:    i18n.validName   || 'Please enter your full name.',
		validPhone:   i18n.validPhone  || 'Please enter a valid phone number.',
		validEmail:   i18n.validEmail  || 'Please enter a valid email address.',
		validDate:    i18n.validDate   || 'Please select a reservation date.',
		validTime:    i18n.validTime   || 'Please select an arrival time.',
		validGuests:  i18n.validGuests || 'Please select the number of guests.',
		submitting:   i18n.submitting  || 'Submitting\u2026',
		submit:       i18n.submit      || 'Confirm Reservation',
	};

	/* =========================================================================
	   1. CSS INJECTION
	      Injects styles for: form improvements, focus rings, calendar popup.
	   ========================================================================= */
	( function injectStyles() {
		if ( document.getElementById( 'gpine-booking-styles' ) ) return;

		var s = document.createElement( 'style' );
		s.id  = 'gpine-booking-styles';

		s.textContent = [

			/* ── Form field focus ring ── */
			'#gpine-booking-form .booking-field:focus,',
			'#gpine-booking-form select:focus{',
			'  border-color:#e2be3d!important;',
			'  box-shadow:0 0 0 3px rgba(226,190,61,.13);',
			'  outline:none;',
			'}',
			'#gpine-booking-form .booking-field.border-red-500{',
			'  box-shadow:0 0 0 3px rgba(239,68,68,.12);',
			'}',
			/* Labels brighter */
			'#gpine-booking-form label{',
			'  color:rgba(245,240,232,.88);',
			'}',
			/* Date trigger button — error state */
			'#booking_date_btn.gpine-date-error{',
			'  border-color:#ef4444!important;',
			'  box-shadow:0 0 0 3px rgba(239,68,68,.12);',
			'}',
			/* Date trigger button — filled state */
			'#booking_date_btn.gpine-date-filled{',
			'  border-color:#e2be3d;',
			'}',

			/* ── Calendar popup ── */
			'.gpine-calendar{',
			'  position:absolute;top:calc(100% + 8px);left:0;',
			'  z-index:200;',
			'  background:#1f1f1f;',
			'  border:1px solid rgba(226,190,61,.38);',
			'  border-radius:18px;',
			'  padding:18px;',
			'  box-shadow:0 28px 64px rgba(0,0,0,.75),0 0 0 1px rgba(226,190,61,.07);',
			'  min-width:292px;',
			'  user-select:none;',
			'  animation:gpineCalIn .2s cubic-bezier(.22,1,.36,1) forwards;',
			'}',
			'@keyframes gpineCalIn{',
			'  from{opacity:0;transform:translateY(-10px) scale(.96);}',
			'  to{opacity:1;transform:translateY(0) scale(1);}',
			'}',

			/* header */
			'.gpine-cal__header{',
			'  display:flex;align-items:center;justify-content:space-between;',
			'  margin-bottom:14px;',
			'}',
			'.gpine-cal__nav{',
			'  display:flex;align-items:center;justify-content:center;',
			'  width:30px;height:30px;',
			'  background:rgba(226,190,61,.1);',
			'  border:1px solid rgba(226,190,61,.22);',
			'  border-radius:8px;',
			'  color:#e2be3d;',
			'  font-size:18px;line-height:1;',
			'  cursor:pointer;',
			'  transition:background .15s,border-color .15s;',
			'  padding:0;',
			'}',
			'.gpine-cal__nav:hover{background:rgba(226,190,61,.22);border-color:rgba(226,190,61,.45);}',
			'.gpine-cal__month{',
			'  font-size:14px;font-weight:700;letter-spacing:.04em;',
			'  color:#f5f0e8;',
			'}',

			/* weekday row */
			'.gpine-cal__weekdays{',
			'  display:grid;grid-template-columns:repeat(7,1fr);gap:2px;',
			'  margin-bottom:6px;',
			'}',
			'.gpine-cal__weekdays span{',
			'  font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;',
			'  color:rgba(245,240,232,.38);text-align:center;padding:4px 0;',
			'}',

			/* day grid */
			'.gpine-cal__grid{',
			'  display:grid;grid-template-columns:repeat(7,1fr);gap:3px;',
			'}',
			'.gpine-cal__day{',
			'  position:relative;',
			'  display:flex;flex-direction:column;align-items:center;justify-content:center;',
			'  height:36px;',
			'  border-radius:9px;',
			'  font-size:13px;font-weight:500;',
			'  cursor:pointer;',
			'  color:#f5f0e8;',
			'  background:transparent;',
			'  border:1px solid transparent;',
			'  transition:background .13s,border-color .13s,color .13s;',
			'  padding:0;',
			'}',
			'.gpine-cal__day:hover:not(:disabled):not(.gpine-cal__day--past):not(.gpine-cal__day--other){',
			'  background:rgba(226,190,61,.13);',
			'  border-color:rgba(226,190,61,.3);',
			'}',
			'.gpine-cal__day--other{',
			'  color:rgba(245,240,232,.18);',
			'  pointer-events:none;',
			'}',
			'.gpine-cal__day--past{',
			'  color:rgba(245,240,232,.28);',
			'  cursor:not-allowed;',
			'  text-decoration:line-through;',
			'  text-decoration-color:rgba(245,240,232,.2);',
			'}',
			'.gpine-cal__day--today:not(.gpine-cal__day--selected){',
			'  border-color:rgba(226,190,61,.5);',
			'  color:#e2be3d;font-weight:700;',
			'}',
			'.gpine-cal__day--selected{',
			'  background:#e2be3d!important;',
			'  color:#111!important;',
			'  font-weight:700;',
			'  border-color:#e2be3d!important;',
			'}',

			/* event dot */
			'.gpine-cal__day--event::after{',
			'  content:\'\';',
			'  position:absolute;bottom:4px;',
			'  width:4px;height:4px;border-radius:50%;',
			'  background:#e2be3d;',
			'}',
			'.gpine-cal__day--selected.gpine-cal__day--event::after{background:#111;}',
			'.gpine-cal__day--past.gpine-cal__day--event::after{background:rgba(226,190,61,.35);}',

			/* legend */
			'.gpine-cal__legend{',
			'  display:flex;align-items:center;gap:7px;',
			'  margin-top:14px;padding-top:12px;',
			'  border-top:1px solid rgba(255,255,255,.07);',
			'  font-size:11px;color:rgba(245,240,232,.5);',
			'}',
		'.gpine-cal__legend-dot{',
		'  width:6px;height:6px;border-radius:50%;',
		'  background:#e2be3d;flex-shrink:0;',
		'}',

		/* ── Custom Dropdown (for Time & Guests) ── */
		'.gpine-dropdown-wrap{',
		'  position:relative;',
		'}',
		'.gpine-dropdown-wrap select{display:none;}',
		'.gpine-dropdown__trigger{',
		'  width:100%;',
		'  background:var(--background);',
		'  border:1px solid var(--border);',
		'  border-radius:18px;',
		'  padding:16px 20px;',
		'  font-size:16px;',
		'  color:var(--foreground);',
		'  cursor:pointer;',
		'  transition:border-color .2s;',
		'  display:flex;align-items:center;justify-content:space-between;',
		'  text-align:left;',
		'}',
		'.gpine-dropdown__trigger:hover{border-color:var(--gold-main);}',
		'.gpine-dropdown__trigger.is-open{border-color:var(--gold-main);}',
		'.gpine-dropdown__trigger.is-error{border-color:#ef4444;}',
		'.gpine-dropdown__placeholder{color:rgba(245,240,232,.5);}',
		'.gpine-dropdown__value{color:var(--foreground);}',
		'.gpine-dropdown__icon{',
		'  color:var(--gold-main);',
		'  flex-shrink:0;',
		'  transition:transform .2s;',
		'}',
		'.gpine-dropdown__trigger.is-open .gpine-dropdown__icon{transform:rotate(180deg);}',
		'.gpine-dropdown__menu{',
		'  position:absolute;top:calc(100% + 8px);left:0;right:0;',
		'  z-index:200;',
		'  background:#1f1f1f;',
		'  border:1px solid rgba(226,190,61,.38);',
		'  border-radius:18px;',
		'  padding:8px;',
		'  box-shadow:0 28px 64px rgba(0,0,0,.75),0 0 0 1px rgba(226,190,61,.07);',
		'  max-height:280px;',
		'  overflow-y:auto;',
		'  animation:gpineCalIn .2s cubic-bezier(.22,1,.36,1) forwards;',
		'}',
		'.gpine-dropdown__option{',
		'  display:flex;align-items:center;',
		'  padding:12px 14px;',
		'  border-radius:10px;',
		'  font-size:14px;font-weight:500;',
		'  color:#f5f0e8;',
		'  background:transparent;',
		'  border:1px solid transparent;',
		'  cursor:pointer;',
		'  transition:background .13s,border-color .13s;',
		'  margin-bottom:2px;',
		'}',
		'.gpine-dropdown__option:hover{',
		'  background:rgba(226,190,61,.13);',
		'  border-color:rgba(226,190,61,.3);',
		'}',
		'.gpine-dropdown__option.is-selected{',
		'  background:#e2be3d!important;',
		'  color:#111!important;',
		'  font-weight:700;',
		'  border-color:#e2be3d!important;',
		'}',
		'.gpine-dropdown__menu::-webkit-scrollbar{width:6px;}',
		'.gpine-dropdown__menu::-webkit-scrollbar-track{background:transparent;}',
		'.gpine-dropdown__menu::-webkit-scrollbar-thumb{',
		'  background:rgba(226,190,61,.3);',
		'  border-radius:10px;',
		'}',
		'.gpine-dropdown__menu::-webkit-scrollbar-thumb:hover{background:rgba(226,190,61,.5);}',

		/* Toast (unchanged) */
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
			'.gpine-toast.is-leaving{animation:gpineToastOut .25s ease forwards;}',
			'.gpine-toast--success{background:#1a2a1a;border:1px solid rgba(34,197,94,.27);}',
			'.gpine-toast--success .gpine-toast__icon{color:#22C55E;}',
			'.gpine-toast--error{background:#2a1a1a;border:1px solid rgba(239,68,68,.27);}',
			'.gpine-toast--error .gpine-toast__icon{color:#EF4444;}',
			'.gpine-toast--warning{background:#2a2211;border:1px solid rgba(201,168,76,.35);}',
			'.gpine-toast--warning .gpine-toast__icon{color:#C9A84C;}',
			'.gpine-toast__icon{flex-shrink:0;margin-top:1px;}',
			'.gpine-toast__body{flex:1;}',
			'.gpine-toast__title{font-weight:700;font-size:13px;letter-spacing:.02em;margin-bottom:2px;}',
			'.gpine-toast__msg{font-size:13px;opacity:.85;}',
			'.gpine-toast__close{',
			'  flex-shrink:0;background:none;border:none;cursor:pointer;',
			'  color:rgba(255,255,255,.4);padding:0 0 0 4px;font-size:18px;line-height:1;',
			'  transition:color .15s;margin-top:-1px;',
			'}',
			'.gpine-toast__close:hover{color:#fff;}',
			'@keyframes gpineToastIn{from{opacity:0;transform:translateX(100%);}to{opacity:1;transform:translateX(0);}}',
			'@keyframes gpineToastOut{from{opacity:1;transform:translateX(0);}to{opacity:0;transform:translateX(60%);}}',

		].join( '' );

		document.head.appendChild( s );
	}() );

	/* =========================================================================
	   2. TOAST NOTIFICATION SYSTEM
	   ========================================================================= */
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
			var c  = getContainer();
			var el = document.createElement( 'div' );
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
			success: function ( t, m, d ) { show( 'success', t, m, d ); },
			error:   function ( t, m, d ) { show( 'error',   t, m, d ); },
			warning: function ( t, m, d ) { show( 'warning', t, m, d ); },
		};
	}() );

	/* =========================================================================
	   3. VALIDATION HELPERS
	   ========================================================================= */

	/**
	 * Show an error on a field.
	 * For hidden inputs (the date value input) the visual error is applied to
	 * the nearest <button> sibling instead of the hidden input itself.
	 */
	function setFieldError( field, message ) {
		var wrapper = field.closest( '.flex.flex-col' );
		if ( ! wrapper ) return;

		var errEl = wrapper.querySelector( '.field-error' );
		if ( errEl ) {
			errEl.textContent = message;
			errEl.classList.remove( 'hidden' );
		}

		if ( field.type === 'hidden' ) {
			var btn = wrapper.querySelector( 'button' );
			if ( btn ) {
				btn.classList.add( 'gpine-date-error' );
				btn.classList.remove( 'gpine-date-filled' );
			}
		} else {
			field.classList.add( 'border-red-500' );
			field.classList.remove( 'border-gold' );
		}
	}

	/**
	 * Clear an error on a field.
	 */
	function clearFieldError( field ) {
		var wrapper = field.closest( '.flex.flex-col' );
		if ( ! wrapper ) return;

		var errEl = wrapper.querySelector( '.field-error' );
		if ( errEl ) {
			errEl.textContent = '';
			errEl.classList.add( 'hidden' );
		}

		if ( field.type === 'hidden' ) {
			var btn = wrapper.querySelector( 'button' );
			if ( btn ) btn.classList.remove( 'gpine-date-error' );
		} else {
			field.classList.remove( 'border-red-500' );
		}
	}

	/**
	 * Validate a single field. Returns true if valid.
	 */
	function validateField( field ) {
		var id  = field.id;
		var val = field.value.trim();
		clearFieldError( field );

		switch ( id ) {
			case 'booking_name':
				if ( val.length < 2 ) { setFieldError( field, L.validName ); return false; }
				break;

			case 'booking_phone':
				if ( ! val || ! /^[+\d\s\-().]{6,20}$/.test( val ) ) {
					setFieldError( field, L.validPhone ); return false;
				}
				break;

			case 'booking_email':
				if ( val && ! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( val ) ) {
					setFieldError( field, L.validEmail ); return false;
				}
				break;

			case 'booking_date':
				if ( ! val ) { setFieldError( field, L.validDate ); return false; }
				var today = new Date(); today.setHours( 0, 0, 0, 0 );
				var sel   = new Date( val + 'T00:00:00' );
				if ( sel < today ) { setFieldError( field, 'Date cannot be in the past.' ); return false; }
				break;

			case 'booking_time':
				if ( ! val ) { setFieldError( field, L.validTime ); return false; }
				break;

			case 'booking_guests':
				if ( ! val ) { setFieldError( field, L.validGuests ); return false; }
				break;
		}

		return true;
	}

	/**
	 * Run validation across all required fields. Returns true when all pass.
	 */
	function validateForm() {
		var required = [ 'booking_name', 'booking_phone', 'booking_date', 'booking_time', 'booking_guests' ];
		var ok       = true;

		required.forEach( function ( id ) {
			var f = document.getElementById( id );
			if ( f && ! validateField( f ) ) ok = false;
		} );

		var emailF = document.getElementById( 'booking_email' );
		if ( emailF && emailF.value.trim() && ! validateField( emailF ) ) ok = false;

		return ok;
	}

	/* =========================================================================
	   4. CUSTOM DATE PICKER CALENDAR
	   ========================================================================= */
	( function initDatePicker() {

		var anchor   = document.getElementById( 'gpine-datepicker-anchor' );
		var btn      = document.getElementById( 'booking_date_btn' );
		var hiddenIn = document.getElementById( 'booking_date' );
		var display  = document.getElementById( 'booking_date_display' );

		if ( ! anchor || ! btn || ! hiddenIn || ! display ) return;

		// Build a lookup Set for O(1) event-date checks.
		var eventSet = {};
		eventDates.forEach( function ( d ) { eventSet[ d ] = true; } );

		var MONTHS   = [ 'January','February','March','April','May','June','July','August','September','October','November','December' ];
		var WEEKDAYS = [ 'Su','Mo','Tu','We','Th','Fr','Sa' ];

		var calEl       = null;
		var isOpen      = false;
		var curYear, curMonth, selectedISO;

		var now         = new Date();
		curYear         = now.getFullYear();
		curMonth        = now.getMonth();
		selectedISO     = '';

		/* ── helpers ── */
		function pad( n )          { return n < 10 ? '0' + n : '' + n; }
		function toISO( y, m, d )  { return y + '-' + pad( m + 1 ) + '-' + pad( d ); }

		function isPast( y, m, d ) {
			var t = new Date(); t.setHours( 0, 0, 0, 0 );
			return new Date( y, m, d ) < t;
		}
		function isToday( y, m, d ) {
			return now.getFullYear() === y && now.getMonth() === m && now.getDate() === d;
		}

		/* ── Build the 42-cell grid for a given month ── */
		function buildCells( y, m ) {
			var firstDow    = new Date( y, m, 1 ).getDay();
			var daysInMonth = new Date( y, m + 1, 0 ).getDate();
			var daysInPrev  = new Date( y, m, 0 ).getDate();
			var cells       = [];

			// Prev-month fill.
			for ( var i = firstDow - 1; i >= 0; i-- ) {
				var py = m === 0 ? y - 1 : y;
				var pm = m === 0 ? 11 : m - 1;
				cells.push( { d: daysInPrev - i, y: py, m: pm, other: true } );
			}
			// Current month.
			for ( var d = 1; d <= daysInMonth; d++ ) {
				cells.push( { d: d, y: y, m: m, other: false } );
			}
			// Next-month fill.
			var remaining = 42 - cells.length;
			for ( var nd = 1; nd <= remaining; nd++ ) {
				var ny = m === 11 ? y + 1 : y;
				var nm = m === 11 ? 0 : m + 1;
				cells.push( { d: nd, y: ny, m: nm, other: true } );
			}
			return cells;
		}

		/* ── Render / re-render the calendar ── */
		function render() {
			var cells = buildCells( curYear, curMonth );

			/* Header */
			var header =
				'<div class="gpine-cal__header">' +
					'<button type="button" class="gpine-cal__nav" data-dir="-1">&#8249;</button>' +
					'<span class="gpine-cal__month">' + MONTHS[ curMonth ] + ' ' + curYear + '</span>' +
					'<button type="button" class="gpine-cal__nav" data-dir="1">&#8250;</button>' +
				'</div>';

			/* Weekday row */
			var wdays = '<div class="gpine-cal__weekdays">' +
				WEEKDAYS.map( function ( w ) { return '<span>' + w + '</span>'; } ).join( '' ) +
			'</div>';

			/* Day grid */
			var grid = '<div class="gpine-cal__grid">';
			cells.forEach( function ( cell ) {
				var iso  = toISO( cell.y, cell.m, cell.d );
				var cls  = 'gpine-cal__day';
				var past = isPast( cell.y, cell.m, cell.d );

				if ( cell.other )                        cls += ' gpine-cal__day--other';
				if ( ! cell.other && past )              cls += ' gpine-cal__day--past';
				if ( ! cell.other && isToday( cell.y, cell.m, cell.d ) ) cls += ' gpine-cal__day--today';
				if ( iso === selectedISO && ! cell.other ) cls += ' gpine-cal__day--selected';
				if ( ! cell.other && eventSet[ iso ] )   cls += ' gpine-cal__day--event';

				var dis = ( cell.other || past ) ? ' disabled' : '';
				var dat = dis ? '' : ' data-date="' + iso + '"';

				grid += '<button type="button" class="' + cls + '"' + dat + dis + '>' + cell.d + '</button>';
			} );
			grid += '</div>';

			/* Legend — only when there are future event dates */
			var legend = Object.keys( eventSet ).length
				? '<div class="gpine-cal__legend"><span class="gpine-cal__legend-dot"></span><span>Event night</span></div>'
				: '';

			calEl.innerHTML = header + wdays + grid + legend;

			/* Month nav */
			calEl.querySelectorAll( '.gpine-cal__nav' ).forEach( function ( navBtn ) {
				navBtn.addEventListener( 'click', function ( e ) {
					e.stopPropagation();
					var dir = parseInt( this.dataset.dir, 10 );
					curMonth += dir;
					if ( curMonth > 11 ) { curMonth = 0; curYear++; }
					if ( curMonth < 0  ) { curMonth = 11; curYear--; }
					render();
				} );
			} );

			/* Day selection */
			calEl.querySelectorAll( '.gpine-cal__day[data-date]' ).forEach( function ( dayBtn ) {
				dayBtn.addEventListener( 'click', function ( e ) {
					e.stopPropagation();
					selectDate( this.dataset.date );
				} );
			} );
		}

		/* ── Select a date ── */
		function selectDate( iso ) {
			selectedISO      = iso;
			hiddenIn.value   = iso;

			var d = new Date( iso + 'T00:00:00' );
			display.textContent = d.toLocaleDateString( 'en-US', {
				weekday: 'short', month: 'short', day: 'numeric', year: 'numeric',
			} );
			display.style.color = '#f5f0e8';
			btn.classList.add( 'gpine-date-filled' );
			btn.classList.remove( 'gpine-date-error' );
			btn.setAttribute( 'aria-expanded', 'false' );
			clearFieldError( hiddenIn );
			close();
		}

		/* ── Open ── */
		function open() {
			if ( ! calEl ) {
				calEl = document.createElement( 'div' );
				calEl.className = 'gpine-calendar';
				anchor.appendChild( calEl );
			}
			isOpen = true;
			btn.setAttribute( 'aria-expanded', 'true' );
			render();
			setTimeout( function () {
				document.addEventListener( 'click', outsideClick );
				document.addEventListener( 'keydown', escClose );
			}, 0 );
		}

		/* ── Close ── */
		function close() {
			isOpen = false;
			btn.setAttribute( 'aria-expanded', 'false' );
			document.removeEventListener( 'click', outsideClick );
			document.removeEventListener( 'keydown', escClose );
			if ( calEl ) {
				calEl.remove();
				calEl = null;
			}
		}

		function outsideClick( e ) {
			if ( ! anchor.contains( e.target ) ) close();
		}
		function escClose( e ) {
			if ( e.key === 'Escape' ) { close(); btn.focus(); }
		}

		/* ── Trigger ── */
		btn.addEventListener( 'click', function ( e ) {
			e.stopPropagation();
			isOpen ? close() : open();
		} );

	}() );

	/* =========================================================================
	   5. CUSTOM DROPDOWNS (Time & Guests)
	   ========================================================================= */
	( function () {
		var selects = document.querySelectorAll( '#booking_time, #booking_guests' );
		if ( ! selects.length ) return;

		selects.forEach( function ( select ) {
			var wrap = document.createElement( 'div' );
			wrap.className = 'gpine-dropdown-wrap';
			select.parentNode.insertBefore( wrap, select );
			wrap.appendChild( select );

			var trigger = document.createElement( 'button' );
			trigger.type = 'button';
			trigger.className = 'gpine-dropdown__trigger';
			trigger.setAttribute( 'aria-haspopup', 'listbox' );
			trigger.setAttribute( 'aria-expanded', 'false' );

			var valueSpan = document.createElement( 'span' );
			valueSpan.className = 'gpine-dropdown__value gpine-dropdown__placeholder';
			valueSpan.textContent = select.options[ select.selectedIndex ].text;

			var icon = document.createElement( 'svg' );
			icon.className = 'gpine-dropdown__icon';
			icon.setAttribute( 'xmlns', 'http://www.w3.org/2000/svg' );
			icon.setAttribute( 'width', '18' );
			icon.setAttribute( 'height', '18' );
			icon.setAttribute( 'viewBox', '0 0 24 24' );
			icon.setAttribute( 'fill', 'none' );
			icon.setAttribute( 'stroke', 'currentColor' );
			icon.setAttribute( 'stroke-width', '2' );
			icon.setAttribute( 'stroke-linecap', 'round' );
			icon.setAttribute( 'stroke-linejoin', 'round' );
			icon.setAttribute( 'aria-hidden', 'true' );
			icon.innerHTML = '<path d="m6 9 6 6 6-6"/>';

			trigger.appendChild( valueSpan );
			trigger.appendChild( icon );
			wrap.appendChild( trigger );

			var menu = null;
			var isOpen = false;

			function createMenu() {
				menu = document.createElement( 'div' );
				menu.className = 'gpine-dropdown__menu';
				menu.setAttribute( 'role', 'listbox' );

				Array.from( select.options ).forEach( function ( opt, idx ) {
					if ( idx === 0 && opt.value === '' ) return; // Skip placeholder option

					var option = document.createElement( 'div' );
					option.className = 'gpine-dropdown__option';
					option.setAttribute( 'role', 'option' );
					option.setAttribute( 'data-value', opt.value );
					option.textContent = opt.text;

					if ( opt.value === select.value ) {
						option.classList.add( 'is-selected' );
					}

					option.addEventListener( 'click', function ( e ) {
						e.stopPropagation();
						selectOption( opt.value, opt.text );
					} );

					menu.appendChild( option );
				} );

				wrap.appendChild( menu );
			}

			function selectOption( value, text ) {
				select.value = value;
				valueSpan.textContent = text;
				valueSpan.classList.remove( 'gpine-dropdown__placeholder' );
				trigger.classList.remove( 'is-error' );

				// Clear field error
				var errorP = wrap.nextElementSibling;
				if ( errorP && errorP.classList.contains( 'field-error' ) ) {
					errorP.classList.add( 'hidden' );
				}

				// Trigger change event
				var event = new Event( 'change', { bubbles: true } );
				select.dispatchEvent( event );

				close();
			}

			function open() {
				if ( isOpen ) return;
				createMenu();
				isOpen = true;
				trigger.classList.add( 'is-open' );
				trigger.setAttribute( 'aria-expanded', 'true' );

				setTimeout( function () {
					document.addEventListener( 'click', outsideClick );
					document.addEventListener( 'keydown', escClose );
				}, 0 );
			}

			function close() {
				if ( ! isOpen ) return;
				isOpen = false;
				trigger.classList.remove( 'is-open' );
				trigger.setAttribute( 'aria-expanded', 'false' );
				document.removeEventListener( 'click', outsideClick );
				document.removeEventListener( 'keydown', escClose );
				if ( menu ) {
					menu.remove();
					menu = null;
				}
			}

			function outsideClick( e ) {
				if ( ! wrap.contains( e.target ) ) close();
			}

			function escClose( e ) {
				if ( e.key === 'Escape' ) {
					close();
					trigger.focus();
				}
			}

			trigger.addEventListener( 'click', function ( e ) {
				e.stopPropagation();
				isOpen ? close() : open();
			} );

			// Expose error state setter for validation
			select.setDropdownError = function ( hasError ) {
				if ( hasError ) {
					trigger.classList.add( 'is-error' );
				} else {
					trigger.classList.remove( 'is-error' );
				}
			};
		} );
	}() );

	/* =========================================================================
	   6. LIVE VALIDATION — blur events
	   ========================================================================= */
	$( document ).on( 'blur', '#gpine-booking-form .booking-field', function () {
		// Hidden inputs (date value) have no interactive blur — skip.
		if ( this.type === 'hidden' ) return;
		validateField( this );
	} );

	$( document ).on( 'input change', '#gpine-booking-form .booking-field', function () {
		if ( this.type === 'hidden' ) return;
		clearFieldError( this );
	} );

	/* =========================================================================
	   7. AJAX FORM SUBMISSION
	   ========================================================================= */
	var isSubmitting = false;

	$( '#gpine-booking-form' ).on( 'submit', function ( e ) {
		e.preventDefault();
		if ( isSubmitting ) return;

		if ( ! validateForm() ) {
			GpineToast.error( 'Validation Error', 'Please fill in all required fields correctly.' );
			var firstErr = document.querySelector( '#gpine-booking-form .field-error:not(.hidden)' );
			if ( firstErr ) firstErr.scrollIntoView( { behavior: 'smooth', block: 'center' } );
			return;
		}

		var $form  = $( this );
		var $btn   = $( '#gpine-booking-submit' );
		var $label = $( '#gpine-booking-btn-label' );

		isSubmitting = true;
		$btn.prop( 'disabled', true ).addClass( 'opacity-60 cursor-not-allowed' );
		$label.text( L.submitting );

		$.ajax( {
			url:      ajaxUrl,
			type:     'POST',
			data:     $form.serialize(),
			dataType: 'json',
		} )
		.done( function ( response ) {
			if ( response.success ) {
				var ref = ( response.data || {} ).ref || '';
				GpineToast.success(
					'Booking Submitted!',
					ref ? 'Reference\u00a0' + ref + '. We\u2019ll confirm within the hour.' : L.success,
					8000
				);
				$form[0].reset();
				// Reset date picker display.
				var disp = document.getElementById( 'booking_date_display' );
				var dBtn = document.getElementById( 'booking_date_btn' );
				if ( disp ) { disp.textContent = 'Select date'; disp.style.color = ''; }
				if ( dBtn ) { dBtn.classList.remove( 'gpine-date-filled', 'gpine-date-error' ); }
				$form[0].scrollIntoView( { behavior: 'smooth', block: 'start' } );

			} else {
				var errData = response.data || {};
				var msg     = errData.message || L.error;
				if ( errData.fields ) {
					$.each( errData.fields, function ( fid, fmsg ) {
						var f = document.getElementById( fid );
						if ( f ) setFieldError( f, fmsg );
					} );
				}
				GpineToast.error( 'Submission Failed', msg );
			}
		} )
		.fail( function ( jqXHR ) {
			// Try to read the actual server message from the JSON body first.
			var serverMsg = '';
			try {
				var parsed = JSON.parse( jqXHR.responseText );
				serverMsg = ( ( parsed || {} ).data || {} ).message || '';
			} catch ( _e ) {}

			var msg;
			if ( 0 === jqXHR.status ) {
				msg = 'Network error. Check your connection and try again.';
			} else if ( 403 === jqXHR.status ) {
				msg = serverMsg || 'Security check failed. Please refresh the page.';
			} else if ( 409 === jqXHR.status ) {
				msg = serverMsg || 'A booking with these details was already submitted. Please wait 2 hours before trying again.';
			} else if ( 429 === jqXHR.status ) {
				msg = serverMsg || 'Too many submissions. Please wait a while before trying again.';
			} else {
				msg = serverMsg || L.error;
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
