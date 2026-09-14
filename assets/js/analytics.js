/**
 * Analytics events — sends site-specific interactions to GA4 via gtag().
 *
 * Loaded only when a Measurement ID is configured. Every event is a no-op
 * when gtag is unavailable (blocked by the browser, consent tools, etc.).
 *
 * Events (all in GA4 as custom events):
 *   album_click        { album }
 *   song_open          { song, album, source }        — click on a song from a list
 *   song_transpose     { song, direction, key }
 *   song_autoscroll    { song }
 *   song_chords_toggle { song }
 *   song_print         { song }
 *   cancionero_filter  { album }
 *   show_click         { venue, label }
 *   shop_click         { url }
 *   platform_click     { platform, url, location }
 *   contact_submit     { location }
 *
 * @package Santiago_Moraes
 */

( function () {
	'use strict';

	function track( name, params ) {
		if ( typeof window.gtag !== 'function' ) {
			return;
		}
		window.gtag( 'event', name, params || {} );
	}

	function text( el ) {
		return el ? el.textContent.trim().replace( /\s+/g, ' ' ) : '';
	}

	function songContext() {
		var viewer = document.querySelector( '.chord-viewer' );
		if ( ! viewer ) {
			return null;
		}
		var h1 = document.querySelector( 'h1' );
		return {
			song: text( h1 ),
			key: text( document.querySelector( '.chord-control__key-current' ) ),
		};
	}

	function platformFromUrl( url ) {
		var hosts = {
			'spotify.com': 'spotify',
			'youtube.com': 'youtube',
			'youtu.be': 'youtube',
			'bandcamp.com': 'bandcamp',
			'soundcloud.com': 'soundcloud',
			'instagram.com': 'instagram',
			'music.apple.com': 'apple_music',
		};
		for ( var host in hosts ) {
			if ( url.indexOf( host ) !== -1 ) {
				return hosts[ host ];
			}
		}
		return '';
	}

	// -------------------------------------------------------------
	// Click delegation.
	// -------------------------------------------------------------
	document.addEventListener( 'click', function ( e ) {
		var el = e.target.closest( 'a, button' );
		if ( ! el ) {
			return;
		}

		var ctx = songContext();

		// Chord viewer controls.
		if ( el.classList.contains( 'chord-control--transpose-up' ) || el.classList.contains( 'chord-control--transpose-down' ) ) {
			track( 'song_transpose', {
				song: ctx ? ctx.song : '',
				direction: el.classList.contains( 'chord-control--transpose-up' ) ? 'up' : 'down',
				key: ctx ? ctx.key : '',
			} );
			return;
		}
		if ( el.classList.contains( 'chord-control--autoscroll' ) ) {
			track( 'song_autoscroll', { song: ctx ? ctx.song : '' } );
			return;
		}
		if ( el.classList.contains( 'chord-control--toggle' ) ) {
			track( 'song_chords_toggle', { song: ctx ? ctx.song : '' } );
			return;
		}

		// Album cover cards.
		if ( el.classList.contains( 'disco-card' ) ) {
			track( 'album_click', { album: text( el.querySelector( '.disco-card__name' ) ) || el.getAttribute( 'aria-label' ) || '' } );
			return;
		}

		// Songs opened from lists.
		if ( el.classList.contains( 'acordes-row' ) ) {
			track( 'song_open', { song: el.dataset.title || text( el.querySelector( '.acordes-row__title' ) ), source: 'cancionero' } );
			return;
		}
		if ( el.classList.contains( 'recent__link' ) ) {
			track( 'song_open', { song: text( el.querySelector( '.recent__name' ) ), album: el.dataset.album || '', source: 'home' } );
			return;
		}
		if ( el.closest( '.track-row' ) && el.tagName === 'A' ) {
			var row = el.closest( '.track-row' );
			track( 'song_open', {
				song: text( row.querySelector( '.track-row__title' ) ),
				album: text( document.querySelector( '.album-page__title' ) ),
				source: row.classList.contains( 'track-row--chords' ) ? 'album_chords' : 'album',
			} );
			return;
		}

		// Cancionero album filters.
		if ( el.classList.contains( 'acordes-filter' ) ) {
			track( 'cancionero_filter', { album: el.dataset.album || text( el ) } );
			return;
		}

		// Shows (Bandsintown rows and "all dates" link).
		if ( el.classList.contains( 'show-row' ) ) {
			track( 'show_click', { venue: text( el.querySelector( '.show-row__venue' ) ), label: text( el.querySelector( '.show-row__cta' ) ) } );
			return;
		}

		// Shop.
		if ( el.closest( '.widget--shop' ) && el.tagName === 'A' ) {
			track( 'shop_click', { url: el.href } );
			return;
		}

		// Streaming / social platforms anywhere.
		if ( el.tagName === 'A' && el.href ) {
			var platform = platformFromUrl( el.href );
			if ( platform ) {
				var location = 'body';
				if ( el.closest( '.site-header' ) ) location = 'header';
				else if ( el.closest( '.site-footer' ) ) location = 'footer';
				else if ( el.closest( '.home-sidebar' ) ) location = 'sidebar';
				else if ( el.closest( '.album-page' ) ) location = 'album';
				else if ( el.closest( '.chord-viewer, .song-sidebar' ) ) location = 'song';
				track( 'platform_click', { platform: platform, url: el.href, location: location } );
			}
		}
	}, true );

	// -------------------------------------------------------------
	// Print (chord sheet) — fires for Ctrl+P and any print button.
	// -------------------------------------------------------------
	window.addEventListener( 'beforeprint', function () {
		var ctx = songContext();
		if ( ctx ) {
			track( 'song_print', { song: ctx.song } );
		}
	} );

	// -------------------------------------------------------------
	// Contact form submissions.
	// -------------------------------------------------------------
	document.addEventListener( 'submit', function ( e ) {
		var form = e.target;
		if ( form.querySelector( 'input[name="action"][value="sm_contact_form"]' ) ) {
			track( 'contact_submit', { location: form.closest( '.home-sidebar' ) ? 'sidebar' : 'page' } );
		}
	}, true );
} )();
