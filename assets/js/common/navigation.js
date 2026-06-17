/**
 * Navigation — mobile menu toggle + keyboard accessibility.
 *
 * Handles:
 *  - Mobile hamburger toggle (.nav-toggle opens/closes .primary-nav)
 *  - Escape key closes the drawer
 *  - Click outside closes the drawer
 *  - Focus trap while drawer is open
 *  - Dropdown sub-menus (hover on desktop handled by CSS; Enter/Space
 *    and arrow keys on keyboard for accessibility)
 *
 * Depends on goldenpineUtils.trapFocus() defined in utils.js.
 *
 * @package GoldenpineTheme
 */

/* global goldenpineNavigation, goldenpineUtils */
window.goldenpineNavigation = ( function () {
    'use strict';

    var toggle  = null;
    var nav     = null;
    var cleanup = null; // focus-trap cleanup

    function init() {
        toggle = document.querySelector( '.nav-toggle' );
        nav    = document.querySelector( '.primary-nav' );

        if ( ! toggle || ! nav ) return;

        toggle.addEventListener( 'click', handleToggle );
        document.addEventListener( 'keydown', handleKeydown );
        document.addEventListener( 'click', handleOutsideClick );
        initSubMenuKeyboard();
    }

    // ------------------------------------------------------------------
    // Open / close helpers
    // ------------------------------------------------------------------
    function open() {
        nav.classList.remove( 'hidden' );
        nav.classList.add( 'is-open' );
        toggle.setAttribute( 'aria-expanded', 'true' );
        document.body.classList.add( 'nav-open' );
        cleanup = goldenpineUtils.trapFocus( nav );
    }

    function close() {
        nav.classList.add( 'hidden' );
        nav.classList.remove( 'is-open' );
        toggle.setAttribute( 'aria-expanded', 'false' );
        document.body.classList.remove( 'nav-open' );
        toggle.focus();
        if ( typeof cleanup === 'function' ) {
            cleanup();
            cleanup = null;
        }
    }

    function isOpen() {
        return nav.classList.contains( 'is-open' );
    }

    // ------------------------------------------------------------------
    // Event handlers
    // ------------------------------------------------------------------
    function handleToggle() {
        isOpen() ? close() : open();
    }

    function handleKeydown( e ) {
        if ( e.key === 'Escape' && isOpen() ) {
            close();
        }
    }

    function handleOutsideClick( e ) {
        if ( isOpen() && ! nav.contains( e.target ) && ! toggle.contains( e.target ) ) {
            close();
        }
    }

    // ------------------------------------------------------------------
    // Keyboard-accessible dropdowns (Enter / Space / arrow keys)
    // ------------------------------------------------------------------
    function initSubMenuKeyboard() {
        var parents = document.querySelectorAll( '.primary-nav .menu-item-has-children > a' );

        parents.forEach( function ( link ) {
            link.setAttribute( 'aria-haspopup', 'true' );
            link.setAttribute( 'aria-expanded', 'false' );

            link.addEventListener( 'keydown', function ( e ) {
                var subMenu = link.nextElementSibling;
                if ( ! subMenu || ! subMenu.classList.contains( 'sub-menu' ) ) return;

                if ( e.key === 'Enter' || e.key === ' ' ) {
                    e.preventDefault();
                    var expanded = link.getAttribute( 'aria-expanded' ) === 'true';
                    link.setAttribute( 'aria-expanded', String( ! expanded ) );
                    subMenu.style.display = expanded ? '' : 'flex';
                }

                if ( e.key === 'ArrowDown' ) {
                    e.preventDefault();
                    link.setAttribute( 'aria-expanded', 'true' );
                    subMenu.style.display = 'flex';
                    var firstItem = subMenu.querySelector( 'a' );
                    if ( firstItem ) firstItem.focus();
                }
            } );
        } );
    }

    return { init: init };

} )();
