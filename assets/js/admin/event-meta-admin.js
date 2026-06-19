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

    // -----------------------------------------------------------------------
    // Gallery — drag-to-reorder, add, and remove.
    // -----------------------------------------------------------------------
    ( function () {

        /** @type {wp.media.view.MediaFrame|undefined} */
        var frame;

        var $list   = $( '#gpine-gallery-list' );
        var $input  = $( '#gpine_event_gallery' );
        var $empty  = $( '#gpine-gallery-empty' );
        var $addBtn = $( '#gpine-add-gallery-images' );

        // Guard: bail if required DOM elements are missing.
        if ( ! $list.length || ! $input.length || ! $addBtn.length ) {
            return;
        }

        // Drag-to-reorder via jQuery UI Sortable.
        $list.sortable( {
            handle : '.gpine-gallery-handle',
            axis   : 'xy',
            cursor : 'grabbing',
            update : syncHiddenInput,
        } );

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

        function refreshEmptyState() {
            if ( $list.find( '.gpine-gallery-item' ).length === 0 ) {
                $empty.show();
            } else {
                $empty.hide();
            }
        }

        refreshEmptyState();

        function buildItem( id, thumbUrl, title ) {
            var safeTitle = $( '<div>' ).text( title ).html();

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

        $addBtn.on( 'click', function () {
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

                    var thumbUrl = ( sizes && sizes.thumbnail )
                        ? sizes.thumbnail.url
                        : attachment.get( 'url' );

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

        $list.on( 'click', '.gpine-gallery-remove', function () {
            $( this ).closest( '.gpine-gallery-item' ).remove();
            syncHiddenInput();
            refreshEmptyState();
        } );

    }() );

    // -----------------------------------------------------------------------
    // Performer Image — upload and remove.
    // -----------------------------------------------------------------------
    ( function () {

        var performerFrame;

        var $performerInput   = $( '#gpine_event_performer_image' );
        var $performerPreview = $( '.gpine-performer-image-preview' );
        var $uploadBtn        = $( '.gpine-upload-performer-image' );

        // Guard: bail if the performer image field is not on this screen.
        if ( ! $performerInput.length || ! $uploadBtn.length ) {
            return;
        }

        $uploadBtn.on( 'click', function ( e ) {
            e.preventDefault();

            if ( performerFrame ) {
                performerFrame.open();
                return;
            }

            performerFrame = wp.media( {
                title    : 'Select Performer Image',
                button   : { text: 'Use this image' },
                library  : { type: 'image' },
                multiple : false,
            } );

            performerFrame.on( 'select', function () {
                var attachment = performerFrame.state().get( 'selection' ).first().toJSON();
                var imageUrl   = ( attachment.sizes && attachment.sizes.medium )
                    ? attachment.sizes.medium.url
                    : attachment.url;

                $performerInput.val( attachment.id );
                $performerPreview.html(
                    '<img src="' + imageUrl + '" alt="" style="max-width: 200px; height: auto; display: block; border-radius: 8px;">'
                );
                $uploadBtn.text( 'Change Image' );

                // Insert remove button after upload button if not already present.
                if ( ! $( '.gpine-remove-performer-image' ).length ) {
                    $uploadBtn.after(
                        '<button type="button" class="button gpine-remove-performer-image" style="margin-left: 5px;">Remove</button>'
                    );
                }
            } );

            performerFrame.open();
        } );

        // Delegated: handles both server-rendered and dynamically inserted remove buttons.
        $( document ).on( 'click', '.gpine-remove-performer-image', function ( e ) {
            e.preventDefault();
            $performerInput.val( '' );
            $performerPreview.empty();
            $uploadBtn.text( 'Select Image' );
            $( this ).remove();
        } );

    }() );

}( jQuery, wp, gpineEventAdmin ) );
