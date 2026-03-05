/**
 * Chord Toggle Module.
 *
 * Show/hide all chords for a lyrics-only reading view.
 * Remembers preference in sessionStorage.
 *
 * @package Santiago_Moraes
 */

( function () {
	'use strict';

	var STORAGE_KEY = 'sm_chords_hidden';

	/**
	 * Initialize toggle controls.
	 */
	function init() {
		var viewer = document.querySelector( '.chord-viewer' );
		if ( ! viewer ) {
			return;
		}

		var btn = viewer.querySelector( '.chord-control--toggle' );
		if ( ! btn ) {
			return;
		}

		// Check saved preference.
		var isHidden = false;
		try {
			isHidden = sessionStorage.getItem( STORAGE_KEY ) === '1';
		} catch ( e ) {
			// sessionStorage not available.
		}

		// Apply initial state.
		if ( isHidden ) {
			viewer.classList.add( 'chord-viewer--chords-hidden' );
			btn.classList.add( 'chord-control--active' );
			btn.setAttribute( 'aria-pressed', 'true' );
		}

		// Toggle on click.
		btn.addEventListener( 'click', function () {
			isHidden = ! isHidden;
			viewer.classList.toggle( 'chord-viewer--chords-hidden', isHidden );
			btn.classList.toggle( 'chord-control--active', isHidden );
			btn.setAttribute( 'aria-pressed', isHidden ? 'true' : 'false' );

			try {
				sessionStorage.setItem( STORAGE_KEY, isHidden ? '1' : '0' );
			} catch ( e ) {
				// Ignore.
			}
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
