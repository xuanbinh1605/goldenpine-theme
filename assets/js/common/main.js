/**
 * Main JavaScript — Common entry point
 *
 * Bootstraps all common modules that run on every page. Import or call
 * page-specific scripts from the corresponding page template files and
 * enqueue them conditionally in inc/enqueue.php.
 *
 * @package GoldenpineTheme
 */

( function () {
    'use strict';

    /**
     * DOMContentLoaded bootstrap.
     */
    document.addEventListener( 'DOMContentLoaded', function () {
        goldenpineNavigation.init();
        goldenpineUtils.initStickyHeader();
        goldenpineUtils.initSmoothScroll();
        goldenpineUtils.initLazyImages();
    } );

} )();
