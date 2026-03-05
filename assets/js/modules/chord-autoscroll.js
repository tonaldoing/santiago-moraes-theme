/**
 * Chord Auto-Scroll Module.
 *
 * Play/pause smooth auto-scrolling with adjustable speed.
 * Pauses when the user manually scrolls, resumes on button press.
 *
 * @package Santiago_Moraes
 */

( function () {
	'use strict';

	var isScrolling = false;
	var animationId = null;
	var lastTimestamp = null;
	var userScrolled = false;
	var userScrollTimer = null;

	/**
	 * Get current speed from the range slider (pixels per second).
	 *
	 * Slider range: 1–10. Maps to ~20–120 pixels per second.
	 *
	 * @param {HTMLInputElement} slider The speed slider.
	 * @return {number} Speed in pixels per second.
	 */
	function getSpeed( slider ) {
		var val = parseInt( slider.value, 10 );
		return 10 + ( val * 12 );
	}

	/**
	 * Scroll animation frame callback.
	 *
	 * @param {number} timestamp Current timestamp from requestAnimationFrame.
	 * @param {HTMLInputElement} slider The speed slider.
	 */
	function scrollStep( timestamp, slider ) {
		if ( ! isScrolling ) {
			return;
		}

		if ( lastTimestamp === null ) {
			lastTimestamp = timestamp;
		}

		var elapsed = timestamp - lastTimestamp;
		lastTimestamp = timestamp;

		// Calculate pixels to scroll this frame.
		var speed = getSpeed( slider );
		var pixels = ( speed * elapsed ) / 1000;

		window.scrollBy( 0, pixels );

		// Check if we've reached the bottom.
		var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
		var scrollHeight = document.documentElement.scrollHeight;
		var clientHeight = document.documentElement.clientHeight;

		if ( scrollTop + clientHeight >= scrollHeight - 1 ) {
			stopScrolling();
			return;
		}

		animationId = requestAnimationFrame( function ( ts ) {
			scrollStep( ts, slider );
		} );
	}

	/**
	 * Start auto-scrolling.
	 *
	 * @param {HTMLInputElement} slider The speed slider.
	 */
	function startScrolling( slider ) {
		if ( isScrolling ) {
			return;
		}
		isScrolling = true;
		userScrolled = false;
		lastTimestamp = null;
		animationId = requestAnimationFrame( function ( ts ) {
			scrollStep( ts, slider );
		} );
	}

	/**
	 * Stop auto-scrolling.
	 */
	function stopScrolling() {
		isScrolling = false;
		lastTimestamp = null;
		if ( animationId ) {
			cancelAnimationFrame( animationId );
			animationId = null;
		}
	}

	/**
	 * Initialize auto-scroll controls.
	 */
	function init() {
		var viewer = document.querySelector( '.chord-viewer' );
		if ( ! viewer ) {
			return;
		}

		var btn = viewer.querySelector( '.chord-control--autoscroll' );
		var slider = viewer.querySelector( '.chord-control__speed' );

		if ( ! btn || ! slider ) {
			return;
		}

		// Toggle on button click.
		btn.addEventListener( 'click', function () {
			if ( isScrolling ) {
				stopScrolling();
				btn.classList.remove( 'chord-control--active' );
				btn.setAttribute( 'aria-pressed', 'false' );
			} else {
				startScrolling( slider );
				btn.classList.add( 'chord-control--active' );
				btn.setAttribute( 'aria-pressed', 'true' );
			}
		} );

		// Pause on user manual scroll (wheel, touch, key).
		function onUserScroll() {
			if ( ! isScrolling ) {
				return;
			}

			// Debounce: only pause if user keeps scrolling for a bit.
			userScrolled = true;
			clearTimeout( userScrollTimer );
			userScrollTimer = setTimeout( function () {
				if ( userScrolled && isScrolling ) {
					stopScrolling();
					btn.classList.remove( 'chord-control--active' );
					btn.setAttribute( 'aria-pressed', 'false' );
				}
			}, 150 );
		}

		window.addEventListener( 'wheel', onUserScroll, { passive: true } );
		window.addEventListener( 'touchmove', onUserScroll, { passive: true } );

		// Keyboard shortcuts.
		document.addEventListener( 'keydown', function ( e ) {
			// Space bar toggles auto-scroll (only if not in an input).
			if ( e.code === 'Space' && e.target === document.body ) {
				e.preventDefault();
				btn.click();
			}
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
