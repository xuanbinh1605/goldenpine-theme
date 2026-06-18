/**
 * Goldenpine Theme — assets/js/admin/event-meta-admin.js
 *
 * Event Gallery — Admin Media Picker
 *
 * Handles image selection, drag-to-reorder, and removal for the
 * event gallery meta box on the event CPT edit screen.
 *
 * Responsibilities:
 *  - Opens a wp.media() frame restricted to images.
 *  - Appends selected image thumbnails to the sortable list.
 *  - Prevents duplicate attachment IDs in the list.
 *  - Syncs the hidden input (#gpine_event_gallery) with the current
 *    ordered list of attachment IDs on every change.
 *  - Shows/hides the "no images" placeholder based on list state.
 *
 * Dependencies: jQuery, jQuery UI Sortable, wp.media (wp-media)
 *
 * @package GoldenpineTheme
 */
( function ( $, wp, config ) {
    'use strict';

    /** @type {wp.media.view.MediaFrame|undefined} */
    var frame;

    var $list   = $( '#gpine-gallery-list' );
    var $input  = $( '#gpine_event_gallery' );
    var $empty  = $( '#gpine-gallery-empty' );
    var $addBtn = $( '#gpine-add-gallery-images' );

    // -----------------------------------------------------------------------
    // Guard: bail if required DOM elements are missing.
    // -----------------------------------------------------------------------
    if ( ! $list.length || ! $input.length || ! $addBtn.length ) {
        return;
    }

    // -----------------------------------------------------------------------
    // Drag-to-reorder via jQuery UI Sortable.
    // -----------------------------------------------------------------------
    $list.sortable( {
        handle : '.gpine-gallery-handle',
        axis   : 'xy',
        cursor : 'grabbing',
        update : syncHiddenInput,
    } );

    // -----------------------------------------------------------------------
    // Sync the hidden input with the current ordered attachment IDs.
    // -----------------------------------------------------------------------
    function syncHiddenInput() {
        var ids = [];

        $list.find( '.gpine-gallery-item' ).each( function () {
            var id = parseInt( $( this ).data( 'id' ), 10 );
            if ( id > 0 ) {
                ids.push( id );
            }
        } );

        $input.val( ids.join( ',' ) );
    }

    // -----------------------------------------------------------------------
    // Show/hide the "no images" empty-state paragraph.
    // -----------------------------------------------------------------------
    function refreshEmptyState() {
        if ( $list.find( '.gpine-gallery-item' ).length === 0 ) {
            $empty.show();
        } else {
            $empty.hide();
        }
    }

    // Set initial empty state based on server-rendered list.
    refreshEmptyState();

    // -----------------------------------------------------------------------
    // Build a single gallery item element.
    // -----------------------------------------------------------------------
    function buildItem( id, thumbUrl, title ) {
        var safeTitle = $( '<div>' ).text( title ).html(); // HTML-encode title.

        return $( '<li>' )
            .addClass( 'gpine-gallery-item' )
            .attr( 'data-id', id )
            .append(
                $( '<img>' )
                    .addClass( 'gpine-gallery-thumb' )
                    .attr( 'src', thumbUrl )
                    .attr( 'alt', safeTitle )
            )
            .append(
                $( '<span>' )
                    .addClass( 'gpine-gallery-handle dashicons dashicons-menu-alt2' )
                    .attr( 'title', config.dragText )
            )
            .append(
                $( '<button>' )
                    .attr( 'type', 'button' )
                    .addClass( 'gpine-gallery-remove' )
                    .attr( 'data-id', id )
                    .attr( 'aria-label', config.removeText )
                    .append(
                        $( '<span>' ).addClass( 'dashicons dashicons-trash' )
                    )
            );
    }

    // -----------------------------------------------------------------------
    // Open the media library frame.
    // -----------------------------------------------------------------------
    $addBtn.on( 'click', function () {

        // Re-open existing frame if already created.
        if ( frame ) {
            frame.open();
            return;
        }

        frame = wp.media( {
            title    : config.frameTitle,
            button   : { text: config.buttonText },
            library  : { type: 'image' },
            multiple : true,
        } );

        frame.on( 'select', function () {
            var selection = frame.state().get( 'selection' );

            selection.each( function ( attachment ) {
                var id    = attachment.get( 'id' );
                var title = attachment.get( 'title' ) || '';
                var sizes = attachment.get( 'sizes' );

                // Prefer thumbnail size; fall back to full URL.
                var thumbUrl = ( sizes && sizes.thumbnail )
                    ? sizes.thumbnail.url
                    : attachment.get( 'url' );

                // Avoid duplicate entries.
                if ( $list.find( '[data-id="' + id + '"]' ).length ) {
                    return;
                }

                $list.append( buildItem( id, thumbUrl, title ) );
            } );

            syncHiddenInput();
            refreshEmptyState();
        } );

        frame.open();
    } );

    // -----------------------------------------------------------------------
    // Remove image — delegated so it works for dynamically added items.
    // -----------------------------------------------------------------------
    $list.on( 'click', '.gpine-gallery-remove', function () {
        $( this ).closest( '.gpine-gallery-item' ).remove();
        syncHiddenInput();
        refreshEmptyState();
    } );

}( jQuery, wp, gpineEventAdmin ) );
