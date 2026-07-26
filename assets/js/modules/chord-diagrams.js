/**
 * Chord Diagrams Module.
 *
 * Renders SVG guitar chord diagrams for all chords used in a song.
 * Displays a collapsible grid of compact diagrams inserted before
 * the chord-viewer content area.
 *
 * Listens for 'chordsTransposed' custom event to update diagrams
 * when the user transposes chords.
 *
 * @package Santiago_Moraes
 */

( function () {
	'use strict';

	// -------------------------------------------------------
	// Chord fingering library.
	// strings[0] = low E (6th), strings[5] = high e (1st).
	// -1 = muted, 0 = open, N = fret number relative to baseFret.
	// -------------------------------------------------------

	var CHORD_DB = {
		// Major
		'C':    { strings: [ -1, 3, 2, 0, 1, 0 ], baseFret: 1, barres: [] },
		'D':    { strings: [ -1, -1, 0, 2, 3, 2 ], baseFret: 1, barres: [] },
		'E':    { strings: [ 0, 2, 2, 1, 0, 0 ], baseFret: 1, barres: [] },
		'F':    { strings: [ 1, 1, 2, 3, 3, 1 ], baseFret: 1, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'G':    { strings: [ 3, 2, 0, 0, 0, 3 ], baseFret: 1, barres: [] },
		'A':    { strings: [ -1, 0, 2, 2, 2, 0 ], baseFret: 1, barres: [] },
		'B':    { strings: [ -1, 1, 3, 3, 3, 1 ], baseFret: 2, barres: [ { fret: 1, from: 1, to: 5 } ] },

		// Minor
		'Cm':   { strings: [ -1, 1, 3, 3, 2, 1 ], baseFret: 3, barres: [ { fret: 1, from: 1, to: 5 } ] },
		'Dm':   { strings: [ -1, -1, 0, 2, 3, 1 ], baseFret: 1, barres: [] },
		'Em':   { strings: [ 0, 2, 2, 0, 0, 0 ], baseFret: 1, barres: [] },
		'Fm':   { strings: [ 1, 1, 3, 3, 2, 1 ], baseFret: 1, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'Gm':   { strings: [ 1, 1, 3, 3, 3, 1 ], baseFret: 3, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'Am':   { strings: [ -1, 0, 2, 2, 1, 0 ], baseFret: 1, barres: [] },
		'Bm':   { strings: [ -1, 1, 3, 3, 2, 1 ], baseFret: 2, barres: [ { fret: 1, from: 1, to: 5 } ] },

		// 7th
		'C7':   { strings: [ -1, 3, 2, 3, 1, 0 ], baseFret: 1, barres: [] },
		'D7':   { strings: [ -1, -1, 0, 2, 1, 2 ], baseFret: 1, barres: [] },
		'E7':   { strings: [ 0, 2, 0, 1, 0, 0 ], baseFret: 1, barres: [] },
		'F7':   { strings: [ 1, 1, 2, 1, 3, 1 ], baseFret: 1, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'G7':   { strings: [ 3, 2, 0, 0, 0, 1 ], baseFret: 1, barres: [] },
		'A7':   { strings: [ -1, 0, 2, 0, 2, 0 ], baseFret: 1, barres: [] },
		'B7':   { strings: [ -1, 2, 1, 2, 0, 2 ], baseFret: 1, barres: [] },

		// Minor 7
		'Am7':  { strings: [ -1, 0, 2, 0, 1, 0 ], baseFret: 1, barres: [] },
		'Bm7':  { strings: [ -1, 1, 3, 1, 2, 1 ], baseFret: 2, barres: [ { fret: 1, from: 1, to: 5 } ] },
		'Dm7':  { strings: [ -1, -1, 0, 2, 1, 1 ], baseFret: 1, barres: [] },
		'Em7':  { strings: [ 0, 2, 0, 0, 0, 0 ], baseFret: 1, barres: [] },

		// Major 7
		'Cmaj7': { strings: [ -1, 3, 2, 0, 0, 0 ], baseFret: 1, barres: [] },
		'Dmaj7': { strings: [ -1, -1, 0, 2, 2, 2 ], baseFret: 1, barres: [] },
		'Fmaj7': { strings: [ -1, -1, 3, 2, 1, 0 ], baseFret: 1, barres: [] },
		'Gmaj7': { strings: [ 3, 2, 0, 0, 0, 2 ], baseFret: 1, barres: [] },

		// Sus
		'Asus2': { strings: [ -1, 0, 2, 2, 0, 0 ], baseFret: 1, barres: [] },
		'Asus4': { strings: [ -1, 0, 2, 2, 3, 0 ], baseFret: 1, barres: [] },
		'Dsus2': { strings: [ -1, -1, 0, 2, 3, 0 ], baseFret: 1, barres: [] },
		'Dsus4': { strings: [ -1, -1, 0, 2, 3, 3 ], baseFret: 1, barres: [] },
		'Esus4': { strings: [ 0, 2, 2, 2, 0, 0 ], baseFret: 1, barres: [] },

		// Sharp / flat
		'F#':   { strings: [ 1, 1, 3, 3, 3, 1 ], baseFret: 2, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'F#m':  { strings: [ 1, 1, 3, 3, 2, 1 ], baseFret: 2, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'G#m':  { strings: [ 1, 1, 3, 3, 2, 1 ], baseFret: 4, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'Ab':   { strings: [ 1, 1, 3, 3, 3, 1 ], baseFret: 4, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'Bb':   { strings: [ -1, 1, 3, 3, 3, 1 ], baseFret: 1, barres: [ { fret: 1, from: 1, to: 5 } ] },
		'Bbm':  { strings: [ -1, 1, 3, 3, 2, 1 ], baseFret: 1, barres: [ { fret: 1, from: 1, to: 5 } ] },
		'C#m':  { strings: [ -1, 1, 3, 3, 2, 1 ], baseFret: 4, barres: [ { fret: 1, from: 1, to: 5 } ] },
		'Eb':   { strings: [ -1, -1, 1, 3, 4, 3 ], baseFret: 1, barres: [] },
		'C#':   { strings: [ -1, 1, 3, 3, 3, 1 ], baseFret: 4, barres: [ { fret: 1, from: 1, to: 5 } ] },
		'D#m':  { strings: [ -1, -1, 1, 3, 4, 2 ], baseFret: 1, barres: [] },
		'G#':   { strings: [ 1, 1, 3, 3, 3, 1 ], baseFret: 4, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'A#m':  { strings: [ -1, 1, 3, 3, 2, 1 ], baseFret: 1, barres: [ { fret: 1, from: 1, to: 5 } ] },

		// Add
		'Cadd9': { strings: [ -1, 3, 2, 0, 3, 0 ], baseFret: 1, barres: [] },

		// Enharmonic aliases
		'Db':   { strings: [ -1, 1, 3, 3, 3, 1 ], baseFret: 4, barres: [ { fret: 1, from: 1, to: 5 } ] },
		'Gb':   { strings: [ 1, 1, 3, 3, 3, 1 ], baseFret: 2, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'Abm':  { strings: [ 1, 1, 3, 3, 2, 1 ], baseFret: 4, barres: [ { fret: 1, from: 0, to: 5 } ] },
		'Dbm':  { strings: [ -1, 1, 3, 3, 2, 1 ], baseFret: 4, barres: [ { fret: 1, from: 1, to: 5 } ] },
		'Ebm':  { strings: [ -1, -1, 1, 3, 4, 2 ], baseFret: 1, barres: [] },
		'Gbm':  { strings: [ 1, 1, 3, 3, 2, 1 ], baseFret: 2, barres: [ { fret: 1, from: 0, to: 5 } ] }
	};

	// -------------------------------------------------------
	// SVG rendering constants.
	// -------------------------------------------------------

	var SVG_WIDTH = 56;
	var SVG_HEIGHT = 70;

	var GRID_LEFT = 10;
	var GRID_RIGHT = 50;
	var GRID_TOP = 18;
	var GRID_BOTTOM = 58;
	var NUM_FRETS = 4;

	var STRING_SPACING = ( GRID_RIGHT - GRID_LEFT ) / 5;
	var FRET_SPACING = ( GRID_BOTTOM - GRID_TOP ) / NUM_FRETS;

	// Colors.
	var COLOR_STRING = 'rgba(247,243,240,0.3)';
	var COLOR_FRET = 'rgba(247,243,240,0.2)';
	var COLOR_NUT = 'rgba(247,243,240,0.8)';
	var COLOR_DOT = '#EC4913';
	var COLOR_BARRE = '#EC4913';
	var COLOR_MARKER = 'rgba(247,243,240,0.5)';
	var COLOR_NAME = '#F7F3F0';
	var COLOR_FRET_NUM = 'rgba(247,243,240,0.5)';

	// -------------------------------------------------------
	// SVG rendering functions.
	// -------------------------------------------------------

	/**
	 * Get the X position for a string index (0=low E, 5=high e).
	 *
	 * @param {number} idx String index (0-5).
	 * @return {number} X coordinate.
	 */
	function stringX( idx ) {
		return GRID_LEFT + idx * STRING_SPACING;
	}

	/**
	 * Get the Y position for a fret number (1-based, within the visible grid).
	 * Returns the Y of the line below fret N.
	 *
	 * @param {number} fret Fret number (1 to NUM_FRETS).
	 * @return {number} Y coordinate of the fret wire.
	 */
	function fretY( fret ) {
		return GRID_TOP + fret * FRET_SPACING;
	}

	/**
	 * Build the SVG markup for a single chord diagram.
	 *
	 * @param {string} name Chord name (e.g., "Am").
	 * @param {Object} data Chord data from CHORD_DB.
	 * @return {string} SVG markup string.
	 */
	function renderDiagramSVG( name, data ) {
		var parts = [];

		parts.push(
			'<svg xmlns="http://www.w3.org/2000/svg" width="' + SVG_WIDTH + '" height="' + SVG_HEIGHT + '" viewBox="0 0 ' + SVG_WIDTH + ' ' + SVG_HEIGHT + '">'
		);

		// Chord name.
		parts.push(
			'<text x="' + ( SVG_WIDTH / 2 ) + '" y="9" text-anchor="middle" fill="' + COLOR_NAME + '" font-size="9" font-family="sans-serif" font-weight="600">'
			+ escapeXml( name )
			+ '</text>'
		);

		// Nut or fret number.
		if ( data.baseFret === 1 ) {
			parts.push(
				'<line x1="' + ( GRID_LEFT - 0.5 ) + '" y1="' + GRID_TOP + '" x2="' + ( GRID_RIGHT + 0.5 ) + '" y2="' + GRID_TOP + '" stroke="' + COLOR_NUT + '" stroke-width="2.5" stroke-linecap="round"/>'
			);
		} else {
			parts.push(
				'<text x="' + ( GRID_LEFT - 5 ) + '" y="' + ( GRID_TOP + FRET_SPACING / 2 + 3 ) + '" text-anchor="middle" fill="' + COLOR_FRET_NUM + '" font-size="7" font-family="sans-serif">'
				+ data.baseFret
				+ '</text>'
			);
		}

		// Fret lines (horizontal).
		for ( var f = 0; f <= NUM_FRETS; f++ ) {
			var y = GRID_TOP + f * FRET_SPACING;
			parts.push(
				'<line x1="' + GRID_LEFT + '" y1="' + y + '" x2="' + GRID_RIGHT + '" y2="' + y + '" stroke="' + COLOR_FRET + '" stroke-width="1"/>'
			);
		}

		// String lines (vertical).
		for ( var s = 0; s < 6; s++ ) {
			var x = stringX( s );
			parts.push(
				'<line x1="' + x + '" y1="' + GRID_TOP + '" x2="' + x + '" y2="' + GRID_BOTTOM + '" stroke="' + COLOR_STRING + '" stroke-width="1"/>'
			);
		}

		// Barres.
		for ( var b = 0; b < data.barres.length; b++ ) {
			var barre = data.barres[ b ];
			var bx1 = stringX( barre.from );
			var bx2 = stringX( barre.to );
			var by = GRID_TOP + ( barre.fret - 0.5 ) * FRET_SPACING;

			parts.push(
				'<line x1="' + bx1 + '" y1="' + by + '" x2="' + bx2 + '" y2="' + by + '" stroke="' + COLOR_BARRE + '" stroke-width="4" stroke-linecap="round" opacity="0.85"/>'
			);
		}

		// Finger dots and open/muted markers.
		var DOT_RADIUS = 2.8;
		var MARKER_Y = GRID_TOP - 5;

		for ( var i = 0; i < 6; i++ ) {
			var val = data.strings[ i ];
			var sx = stringX( i );

			if ( val === -1 ) {
				// Muted string: X marker.
				var mSize = 2.2;
				parts.push(
					'<line x1="' + ( sx - mSize ) + '" y1="' + ( MARKER_Y - mSize ) + '" x2="' + ( sx + mSize ) + '" y2="' + ( MARKER_Y + mSize ) + '" stroke="' + COLOR_MARKER + '" stroke-width="1.2" stroke-linecap="round"/>'
					+ '<line x1="' + ( sx + mSize ) + '" y1="' + ( MARKER_Y - mSize ) + '" x2="' + ( sx - mSize ) + '" y2="' + ( MARKER_Y + mSize ) + '" stroke="' + COLOR_MARKER + '" stroke-width="1.2" stroke-linecap="round"/>'
				);
			} else if ( val === 0 ) {
				// Open string: O marker.
				parts.push(
					'<circle cx="' + sx + '" cy="' + MARKER_Y + '" r="2.2" fill="none" stroke="' + COLOR_MARKER + '" stroke-width="1"/>'
				);
			} else {
				// Fretted note: filled dot.
				// Skip if this position is entirely covered by a barre (same fret, within range).
				var coveredByBarre = false;
				for ( var cb = 0; cb < data.barres.length; cb++ ) {
					if ( data.barres[ cb ].fret === val && i >= data.barres[ cb ].from && i <= data.barres[ cb ].to ) {
						coveredByBarre = true;
						break;
					}
				}

				if ( ! coveredByBarre ) {
					var dy = GRID_TOP + ( val - 0.5 ) * FRET_SPACING;
					parts.push(
						'<circle cx="' + sx + '" cy="' + dy + '" r="' + DOT_RADIUS + '" fill="' + COLOR_DOT + '"/>'
					);
				}
			}
		}

		parts.push( '</svg>' );
		return parts.join( '' );
	}

	/**
	 * Escape special XML characters.
	 *
	 * @param {string} str Input string.
	 * @return {string} Escaped string.
	 */
	function escapeXml( str ) {
		return str
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' );
	}

	// -------------------------------------------------------
	// Chord collection and rendering logic.
	// -------------------------------------------------------

	/**
	 * Collect unique chord names from the viewer content, in order of first appearance.
	 *
	 * @param {HTMLElement} content The .chord-viewer__content element.
	 * @return {string[]} Ordered array of unique chord names.
	 */
	function collectChords( content ) {
		var elements = content.querySelectorAll( '.chord[data-chord]' );
		var seen = {};
		var ordered = [];

		for ( var i = 0; i < elements.length; i++ ) {
			var name = elements[ i ].textContent.trim();
			if ( name && ! seen[ name ] ) {
				seen[ name ] = true;
				ordered.push( name );
			}
		}

		return ordered;
	}

	/**
	 * Build the diagrams grid HTML for a list of chord names.
	 *
	 * @param {string[]} chords Array of chord names.
	 * @return {string} HTML for the grid contents.
	 */
	function buildGridHTML( chords ) {
		var html = '';

		for ( var i = 0; i < chords.length; i++ ) {
			var name = chords[ i ];
			var data = CHORD_DB[ name ];

			if ( ! data ) {
				continue;
			}

			html += '<div class="chord-diagrams__item">'
				+ renderDiagramSVG( name, data )
				+ '</div>';
		}

		return html;
	}

	/**
	 * Create the diagram container and insert it into the DOM.
	 *
	 * @param {HTMLElement} viewer The .chord-viewer element.
	 * @param {HTMLElement} content The .chord-viewer__content element.
	 * @param {string[]} chords Ordered chord names.
	 * @return {Object} References to the created elements.
	 */
	function createContainer( viewer, content, chords ) {
		var container = document.createElement( 'div' );
		container.className = 'chord-diagrams';

		// Toggle button.
		var toggle = document.createElement( 'button' );
		toggle.className = 'chord-diagrams__toggle';
		toggle.setAttribute( 'aria-expanded', 'true' );
		toggle.innerHTML = '<span>Acordes usados</span>'
			+ '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 320 512" fill="currentColor">'
			+ '<path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/>'
			+ '</svg>';

		// Grid.
		var grid = document.createElement( 'div' );
		grid.className = 'chord-diagrams__grid';
		grid.innerHTML = buildGridHTML( chords );

		container.appendChild( toggle );
		container.appendChild( grid );

		// Insert BEFORE the .chord-viewer element (outside it) so the
		// sticky controls bar inside .chord-viewer cannot overlap the diagrams.
		viewer.parentNode.insertBefore( container, viewer );

		// Toggle collapse/expand.
		toggle.addEventListener( 'click', function () {
			var expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
			toggle.setAttribute( 'aria-expanded', expanded ? 'false' : 'true' );
			grid.style.display = expanded ? 'none' : '';
		} );

		return {
			container: container,
			grid: grid,
			toggle: toggle
		};
	}

	/**
	 * Update the diagrams grid with new chord data.
	 *
	 * @param {HTMLElement} grid The .chord-diagrams__grid element.
	 * @param {string[]} chords Ordered chord names.
	 */
	function updateGrid( grid, chords ) {
		grid.innerHTML = buildGridHTML( chords );
	}

	// -------------------------------------------------------
	// Lightbox.
	// -------------------------------------------------------

	var lightboxEl = null;

	/**
	 * Create the lightbox element (once) and append to body.
	 */
	function ensureLightbox() {
		if ( lightboxEl ) {
			return lightboxEl;
		}

		lightboxEl = document.createElement( 'div' );
		lightboxEl.className = 'chord-lightbox';
		lightboxEl.innerHTML = '<div class="chord-lightbox__inner">'
			+ '<button class="chord-lightbox__close" aria-label="Cerrar">'
			+ '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor">'
			+ '<path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3l105.4 105.3c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256l105.3-105.4z"/>'
			+ '</svg></button>'
			+ '<span class="chord-lightbox__name"></span>'
			+ '<div class="chord-lightbox__diagram"></div>'
			+ '</div>';

		document.body.appendChild( lightboxEl );

		// Close on backdrop click.
		lightboxEl.addEventListener( 'click', function ( e ) {
			if ( e.target === lightboxEl ) {
				closeLightbox();
			}
		} );

		// Close button.
		lightboxEl.querySelector( '.chord-lightbox__close' ).addEventListener( 'click', closeLightbox );

		// Close on Escape.
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && lightboxEl.classList.contains( 'chord-lightbox--open' ) ) {
				closeLightbox();
			}
		} );

		return lightboxEl;
	}

	/**
	 * Open the lightbox with a specific chord.
	 *
	 * @param {string} chordName The chord name.
	 */
	function openLightbox( chordName ) {
		var data = CHORD_DB[ chordName ];
		if ( ! data ) {
			return;
		}

		var lb = ensureLightbox();
		lb.querySelector( '.chord-lightbox__name' ).textContent = chordName;

		// Render large SVG (reuse same function, CSS scales it via width:100%).
		var svg = renderDiagramSVG( chordName, data );
		// Remove fixed width/height so CSS can scale it.
		svg = svg.replace( /width="\d+"/, 'width="100%"' ).replace( /height="\d+"/, '' );
		lb.querySelector( '.chord-lightbox__diagram' ).innerHTML = svg;

		lb.classList.add( 'chord-lightbox--open' );
		document.body.style.overflow = 'hidden';
	}

	function closeLightbox() {
		if ( lightboxEl ) {
			lightboxEl.classList.remove( 'chord-lightbox--open' );
			document.body.style.overflow = '';
		}
	}

	/**
	 * Bind click events on diagram items to open lightbox.
	 *
	 * @param {HTMLElement} grid The diagrams grid container.
	 */
	function bindLightboxClicks( grid ) {
		grid.addEventListener( 'click', function ( e ) {
			var item = e.target.closest( '.chord-diagrams__item' );
			if ( ! item ) {
				return;
			}
			// Get chord name from the SVG text element.
			var nameEl = item.querySelector( 'text' );
			if ( nameEl ) {
				openLightbox( nameEl.textContent );
			}
		} );
	}

	// -------------------------------------------------------
	// Initialization.
	// -------------------------------------------------------

	/**
	 * Initialize chord diagrams module.
	 */
	function init() {
		var viewer = document.querySelector( '.chord-viewer' );
		if ( ! viewer ) {
			return;
		}

		var content = viewer.querySelector( '.chord-viewer__content' );
		if ( ! content ) {
			return;
		}

		// Collect chords from the content.
		var chords = collectChords( content );

		if ( ! chords.length ) {
			return;
		}

		// Only render if at least one chord has diagram data.
		var hasAny = false;
		for ( var i = 0; i < chords.length; i++ ) {
			if ( CHORD_DB[ chords[ i ] ] ) {
				hasAny = true;
				break;
			}
		}

		if ( ! hasAny ) {
			return;
		}

		var refs = createContainer( viewer, content, chords );

		// Bind lightbox clicks on the diagram grid.
		bindLightboxClicks( refs.grid );

		// Listen for transpose events to refresh diagrams.
		document.addEventListener( 'chordsTransposed', function () {
			var updated = collectChords( content );
			updateGrid( refs.grid, updated );
		} );
	}

	// Run on DOM ready.
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
