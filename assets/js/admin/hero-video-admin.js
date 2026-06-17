/**
 * Hero Video Gallery — Admin Media Picker
 *
 * Uses the native wp.media() frame to select, reorder, and remove
 * video attachments for the Hero Videos meta box on the gpine_video CPT.
 *
 * Dependencies:
 *   - jquery            (bundled with WordPress)
 *   - jquery-ui-sortable (bundled with WordPress)
 *   - wp-media          (enqueued via goldenpine_enqueue_video_gallery_assets)
 *   - gpineVideoAdmin   (localized strings)
 *
 * @package GoldenpineTheme
 */

/* global wp, jQuery, gpineVideoAdmin */
( function ( $, wp, l10n ) {
    'use strict';

    var $list  = $( '#gpine-video-list' );
    var $input = $( '#gpine_hero_videos' );
    var $empty = $( '.gpine-video-gallery__empty' );
    var frame;

    // ------------------------------------------------------------------
    // Collect all current item IDs → write to hidden input.
    // ------------------------------------------------------------------
    function syncIds() {
        var ids = [];

        $list.find( '.gpine-video-item' ).each( function () {
            ids.push( $( this ).data( 'id' ) );
        } );

        $input.val( ids.join( ',' ) );

        // Show/hide the "no videos" message.
        if ( ids.length > 0 ) {
            $empty.hide();
        } else {
            $empty.show();
        }
    }

    // ------------------------------------------------------------------
    // Build a single list item from a wp.media attachment JSON object.
    // ------------------------------------------------------------------
    function renderItem( attachment ) {
        var title = attachment.title || attachment.filename || ( 'Video #' + attachment.id );
        var url   = attachment.url   || '';

        return $( '<li>' )
            .addClass( 'gpine-video-item' )
            .attr( 'data-id', attachment.id )
            .append(
                $( '<span>' )
                    .addClass( 'dashicons dashicons-menu-alt2 gpine-video-handle' )
                    .attr( 'title', l10n.dragText )
            )
            .append(
                $( '<span>' ).addClass( 'dashicons dashicons-video-alt3 gpine-video-icon' )
            )
            .append(
                $( '<span>' ).addClass( 'gpine-video-title' ).text( title )
            )
            .append(
                $( '<a>' )
                    .addClass( 'gpine-video-preview button button-small' )
                    .attr( { href: url, target: '_blank', rel: 'noopener noreferrer', title: l10n.previewText } )
                    .append( $( '<span>' ).addClass( 'dashicons dashicons-external' ).css( 'margin-top', '3px' ) )
            )
            .append(
                $( '<button>' )
                    .attr( { type: 'button', 'data-id': attachment.id, 'aria-label': l10n.removeText } )
                    .addClass( 'gpine-video-remove button button-small button-link-delete' )
                    .append( $( '<span>' ).addClass( 'dashicons dashicons-trash' ).css( 'margin-top', '3px' ) )
            );
    }

    // ------------------------------------------------------------------
    // "Add / Select Videos" button → open wp.media frame.
    // ------------------------------------------------------------------
    $( '#gpine-add-videos' ).on( 'click', function () {

        // Re-use the same frame if already created.
        if ( frame ) {
            frame.open();
            return;
        }

        frame = wp.media( {
            title:    l10n.frameTitle,
            button:   { text: l10n.buttonText },
            library:  { type: 'video' },
            multiple: 'add',
        } );

        frame.on( 'select', function () {
            var selection = frame.state().get( 'selection' );

            // Collect IDs already in the list to skip duplicates.
            var existingIds = $list.find( '.gpine-video-item' ).map( function () {
                return parseInt( $( this ).data( 'id' ), 10 );
            } ).get();

            selection.each( function ( model ) {
                var attachment = model.toJSON();

                if ( existingIds.indexOf( attachment.id ) !== -1 ) {
                    return; // Skip duplicates.
                }

                $list.append( renderItem( attachment ) );
                existingIds.push( attachment.id );
            } );

            syncIds();
        } );

        frame.open();
    } );

    // ------------------------------------------------------------------
    // Remove button — delegate on the list to catch dynamically added items.
    // ------------------------------------------------------------------
    $list.on( 'click', '.gpine-video-remove', function () {
        $( this ).closest( '.gpine-video-item' ).remove();
        syncIds();
    } );

    // ------------------------------------------------------------------
    // Drag-to-reorder via jQuery UI Sortable.
    // ------------------------------------------------------------------
    $list.sortable( {
        handle:      '.gpine-video-handle',
        placeholder: 'gpine-video-placeholder',
        axis:        'y',
        tolerance:   'pointer',
        update:      syncIds,
    } );

    // Initial sync on page load.
    syncIds();

} )( jQuery, wp, gpineVideoAdmin );
