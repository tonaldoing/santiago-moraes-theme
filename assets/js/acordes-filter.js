/**
 * Acordes page — album filter (show/hide songs by album slug).
 *
 * @package Santiago_Moraes
 */
( function () {
	'use strict';

	const filters = document.querySelectorAll( '.acordes-filter' );
	const rows    = document.querySelectorAll( '.acordes-row' );

	if ( ! filters.length || ! rows.length ) {
		return;
	}

	filters.forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			const album = btn.dataset.album;

			// Update active state.
			filters.forEach( function ( b ) {
				b.classList.remove( 'acordes-filter--active' );
			} );
			btn.classList.add( 'acordes-filter--active' );

			// Show/hide rows.
			rows.forEach( function ( row ) {
				if ( album === 'all' ) {
					row.style.display = '';
				} else {
					const albums = row.dataset.albums ? row.dataset.albums.split( ' ' ) : [];
					row.style.display = albums.includes( album ) ? '' : 'none';
				}
			} );
		} );
	} );
} )();
