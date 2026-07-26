/**
 * Acordes page — album filter + preview panel.
 *
 * @package Santiago_Moraes
 */
( function () {
	'use strict';

	const filters = document.querySelectorAll( '.acordes-filter' );
	const rows    = document.querySelectorAll( '.acordes-row' );
	const preview = document.getElementById( 'acordes-preview' );

	if ( ! rows.length ) {
		return;
	}

	// Preview panel elements.
	const previewTitle  = document.getElementById( 'preview-title' );
	const previewMeta   = document.getElementById( 'preview-meta' );
	const previewLyrics = document.getElementById( 'preview-lyrics' );
	const previewLink   = document.getElementById( 'preview-link' );

	function updatePreview( row ) {
		if ( ! preview || ! row ) {
			return;
		}

		rows.forEach( function ( r ) {
			r.classList.remove( 'acordes-row--active' );
		} );
		row.classList.add( 'acordes-row--active' );

		if ( previewTitle ) {
			previewTitle.textContent = row.dataset.title || '\u2014';
		}
		if ( previewMeta ) {
			previewMeta.textContent = row.dataset.meta || '';
		}
		if ( previewLyrics ) {
			previewLyrics.textContent = row.dataset.preview || '';
		}
		if ( previewLink ) {
			previewLink.href = row.dataset.url || '#';
		}
	}

	// Initialize preview with first row.
	var firstRow = document.querySelector( '.acordes-row' );
	if ( firstRow ) {
		updatePreview( firstRow );
	}

	// Update preview on hover.
	rows.forEach( function ( row ) {
		row.addEventListener( 'mouseenter', function () {
			updatePreview( row );
		} );
	} );

	// Album filter buttons.
	if ( filters.length ) {
		filters.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var album = btn.dataset.album;

				filters.forEach( function ( b ) {
					b.classList.remove( 'acordes-filter--active' );
				} );
				btn.classList.add( 'acordes-filter--active' );

				var firstVisible = null;
				rows.forEach( function ( row ) {
					if ( album === 'all' ) {
						row.style.display = '';
						if ( ! firstVisible ) {
							firstVisible = row;
						}
					} else {
						var albums = row.dataset.albums ? row.dataset.albums.split( ' ' ) : [];
						if ( albums.indexOf( album ) !== -1 ) {
							row.style.display = '';
							if ( ! firstVisible ) {
								firstVisible = row;
							}
						} else {
							row.style.display = 'none';
						}
					}
				} );

				if ( firstVisible ) {
					updatePreview( firstVisible );
				}
			} );
		} );
	}
} )();
