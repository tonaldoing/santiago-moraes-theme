/**
 * Acordes page — album filter + preview panel + transpose.
 *
 * @package Santiago_Moraes
 */
( function () {
	'use strict';

	// =====================================================================
	// Chord transposition (standalone — same logic as chord-transpose.js)
	// =====================================================================

	var NOTES_SHARP = [ 'C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B' ];
	var NOTES_FLAT  = [ 'C', 'Db', 'D', 'Eb', 'E', 'F', 'Gb', 'G', 'Ab', 'A', 'Bb', 'B' ];

	var NOTE_MAP = {
		'C': 0, 'C#': 1, 'Db': 1,
		'D': 2, 'D#': 3, 'Eb': 3,
		'E': 4, 'Fb': 4, 'E#': 5,
		'F': 5, 'F#': 6, 'Gb': 6,
		'G': 7, 'G#': 8, 'Ab': 8,
		'A': 9, 'A#': 10, 'Bb': 10,
		'B': 11, 'Cb': 11, 'B#': 0
	};

	var FLAT_KEYS = [ 'F', 'Bb', 'Eb', 'Ab', 'Db', 'Gb', 'Dm', 'Gm', 'Cm', 'Fm', 'Bbm', 'Ebm' ];

	function transposeChord( chord, steps, preferFlats ) {
		if ( chord.indexOf( '/' ) !== -1 ) {
			var parts = chord.split( '/' );
			return transposeChord( parts[0], steps, preferFlats ) + '/' + transposeChord( parts[1], steps, preferFlats );
		}
		var match = chord.match( /^([A-G][#b]?)(.*)$/ );
		if ( ! match ) {
			return chord;
		}
		var index = NOTE_MAP[ match[1] ];
		if ( index === undefined ) {
			return chord;
		}
		var newIndex = ( ( index + steps ) % 12 + 12 ) % 12;
		var scale = preferFlats ? NOTES_FLAT : NOTES_SHARP;
		return scale[ newIndex ] + match[2];
	}

	function transposeText( text, steps, originalKey ) {
		if ( steps === 0 || ! text ) {
			return text;
		}
		var preferFlats = originalKey && FLAT_KEYS.indexOf( originalKey ) !== -1;
		return text.replace( /\[([A-G][#b]?[^\]]*)\]/g, function ( full, chord ) {
			return '[' + transposeChord( chord, steps, preferFlats ) + ']';
		} );
	}

	// =====================================================================
	// DOM references
	// =====================================================================

	var filters = document.querySelectorAll( '.acordes-filter' );
	var rows    = document.querySelectorAll( '.acordes-row' );
	var preview = document.getElementById( 'acordes-preview' );

	if ( ! rows.length ) {
		return;
	}

	var previewTitle  = document.getElementById( 'preview-title' );
	var previewMeta   = document.getElementById( 'preview-meta' );
	var previewLyrics = document.getElementById( 'preview-lyrics' );
	var previewLink   = document.getElementById( 'preview-link' );
	var btnUp         = document.getElementById( 'preview-up' );
	var btnDown       = document.getElementById( 'preview-down' );

	// State
	var currentSteps  = 0;
	var currentKey    = '';
	var originalText  = '';

	// =====================================================================
	// Preview panel
	// =====================================================================

	function updatePreview( row ) {
		if ( ! preview || ! row ) {
			return;
		}

		rows.forEach( function ( r ) {
			r.classList.remove( 'acordes-row--active' );
		} );
		row.classList.add( 'acordes-row--active' );

		// Reset transpose when switching songs.
		currentSteps = 0;
		currentKey   = row.dataset.key || '';
		originalText = row.dataset.preview || '';

		if ( previewTitle ) {
			previewTitle.textContent = row.dataset.title || '\u2014';
		}

		updateMetaDisplay();

		if ( previewLyrics ) {
			previewLyrics.textContent = originalText;
		}
		if ( previewLink ) {
			previewLink.href = row.dataset.url || '#';
		}
	}

	function updateMetaDisplay() {
		if ( ! previewMeta ) {
			return;
		}
		var parts = [];
		if ( currentKey ) {
			var displayKey = currentSteps === 0
				? currentKey
				: transposeChord( currentKey, currentSteps, FLAT_KEYS.indexOf( currentKey ) !== -1 );
			parts.push( 'Tono: ' + displayKey );
			if ( currentSteps !== 0 ) {
				parts.push( '(' + ( currentSteps > 0 ? '+' : '' ) + currentSteps + ')' );
			}
		}
		previewMeta.textContent = parts.join( ' ' );
	}

	function applyPreviewTranspose() {
		if ( previewLyrics ) {
			previewLyrics.textContent = transposeText( originalText, currentSteps, currentKey );
		}
		updateMetaDisplay();
	}

	// Init preview with first row.
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

	// =====================================================================
	// Transpose buttons
	// =====================================================================

	if ( btnUp ) {
		btnUp.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			currentSteps += 1;
			if ( currentSteps >= 12 ) {
				currentSteps = 0;
			}
			applyPreviewTranspose();
		} );
	}

	if ( btnDown ) {
		btnDown.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			currentSteps -= 1;
			if ( currentSteps <= -12 ) {
				currentSteps = 0;
			}
			applyPreviewTranspose();
		} );
	}

	// =====================================================================
	// Album filter buttons
	// =====================================================================

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
