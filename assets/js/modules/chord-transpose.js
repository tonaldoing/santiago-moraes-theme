/**
 * Chord Transpose Module.
 *
 * Shifts all chords up/down by semitones.
 * Handles sharps, flats, and all chord types.
 *
 * @package Santiago_Moraes
 */

( function () {
	'use strict';

	// Chromatic scale using sharps.
	var NOTES_SHARP = [ 'C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B' ];
	// Chromatic scale using flats.
	var NOTES_FLAT = [ 'C', 'Db', 'D', 'Eb', 'E', 'F', 'Gb', 'G', 'Ab', 'A', 'Bb', 'B' ];

	// Map enharmonic equivalents to a semitone index.
	var NOTE_MAP = {
		'C': 0, 'C#': 1, 'Db': 1,
		'D': 2, 'D#': 3, 'Eb': 3,
		'E': 4, 'Fb': 4, 'E#': 5,
		'F': 5, 'F#': 6, 'Gb': 6,
		'G': 7, 'G#': 8, 'Ab': 8,
		'A': 9, 'A#': 10, 'Bb': 10,
		'B': 11, 'Cb': 11, 'B#': 0
	};

	// Track current transposition state.
	var currentSteps = 0;

	/**
	 * Determine if a chord root uses flats or sharps.
	 *
	 * @param {string} root The root note (e.g., "Bb", "F#").
	 * @return {boolean} True if the root uses flats.
	 */
	function usesFlats( root ) {
		return root.length > 1 && root[ 1 ] === 'b';
	}

	/**
	 * Transpose a single chord string by a number of semitones.
	 *
	 * @param {string} chord  Full chord (e.g., "Am7", "F#m", "Bb/D").
	 * @param {number} steps  Number of semitones to shift (positive = up).
	 * @param {boolean} preferFlats Whether to use flats in output.
	 * @return {string} The transposed chord.
	 */
	function transposeChord( chord, steps, preferFlats ) {
		// Handle slash chords: "Am/G" => transpose both parts.
		if ( chord.indexOf( '/' ) !== -1 ) {
			var parts = chord.split( '/' );
			return transposeChord( parts[ 0 ], steps, preferFlats ) + '/' + transposeChord( parts[ 1 ], steps, preferFlats );
		}

		// Extract root note (1 or 2 chars) and suffix.
		var match = chord.match( /^([A-G][#b]?)(.*)$/ );
		if ( ! match ) {
			return chord;
		}

		var root = match[ 1 ];
		var suffix = match[ 2 ];

		var index = NOTE_MAP[ root ];
		if ( index === undefined ) {
			return chord;
		}

		var newIndex = ( ( index + steps ) % 12 + 12 ) % 12;
		var scale = preferFlats ? NOTES_FLAT : NOTES_SHARP;
		var newRoot = scale[ newIndex ];

		return newRoot + suffix;
	}

	/**
	 * Determine whether to prefer flats based on the original key.
	 *
	 * Flat keys: F, Bb, Eb, Ab, Db, Gb and their relative minors.
	 *
	 * @param {string} originalKey The original key.
	 * @return {boolean} True if flats should be used.
	 */
	function shouldUseFlats( originalKey ) {
		if ( ! originalKey ) {
			return false;
		}
		var root = originalKey.match( /^([A-G][#b]?)/ );
		if ( ! root ) {
			return false;
		}
		var flatKeys = [ 'F', 'Bb', 'Eb', 'Ab', 'Db', 'Gb', 'Dm', 'Gm', 'Cm', 'Fm', 'Bbm', 'Ebm' ];
		return flatKeys.indexOf( originalKey ) !== -1 || usesFlats( root[ 1 ] );
	}

	/**
	 * Apply transposition to all chord elements in the viewer.
	 *
	 * @param {HTMLElement} viewer The chord-viewer container.
	 * @param {number} steps Total semitone shift from original.
	 */
	function applyTransposition( viewer, steps ) {
		var preferFlats = shouldUseFlats( viewer.dataset.originalKey );
		var chordElements = viewer.querySelectorAll( '.chord[data-chord]' );

		chordElements.forEach( function ( el ) {
			var original = el.getAttribute( 'data-chord' );
			el.textContent = transposeChord( original, steps, preferFlats );
		} );

		// Update key display.
		var keyDisplay = viewer.querySelector( '.chord-control__key-current' );
		if ( keyDisplay && viewer.dataset.originalKey ) {
			if ( steps === 0 ) {
				keyDisplay.textContent = viewer.dataset.originalKey;
			} else {
				var newKey = transposeChord( viewer.dataset.originalKey, steps, preferFlats );
				keyDisplay.textContent = newKey;
			}
		}
	}

	/**
	 * Initialize transpose controls.
	 */
	function init() {
		var viewer = document.querySelector( '.chord-viewer' );
		if ( ! viewer ) {
			return;
		}

		var btnUp = viewer.querySelector( '.chord-control--transpose-up' );
		var btnDown = viewer.querySelector( '.chord-control--transpose-down' );

		if ( btnUp ) {
			btnUp.addEventListener( 'click', function () {
				currentSteps += 1;
				if ( currentSteps >= 12 ) {
					currentSteps = 0;
				}
				applyTransposition( viewer, currentSteps );
			} );
		}

		if ( btnDown ) {
			btnDown.addEventListener( 'click', function () {
				currentSteps -= 1;
				if ( currentSteps <= -12 ) {
					currentSteps = 0;
				}
				applyTransposition( viewer, currentSteps );
			} );
		}
	}

	// Run on DOM ready.
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
