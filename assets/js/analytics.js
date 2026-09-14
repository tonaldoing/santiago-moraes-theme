/**
 * Analytics events — sends site-specific interactions to GA4 via gtag().
 *
 * Loaded only when a Measurement ID is configured. Every event is a no-op
 * when gtag is unavailable (blocked by the browser, consent tools, etc.).
 *
 * GA4 only keeps parameters that have a registered custom dimension, so the
 * vocabulary here is closed on purpose:
 *
 *   Registered  song, album, source, platform, venue
 *   Pending     key, direction, label, speed
 *   Ignored     url (high cardinality, redundant with the Pages report)
 *
 * song and album are always slugs (lowercase, no accents, hyphenated), taken
 * from a URL or a data attribute — never from visible text, which editors can
 * change. source uses a single closed vocabulary (see SOURCE).
 *
 * Events (all custom events in GA4):
 *   album_click           { album, source }
 *   song_open             { song, album?, source }
 *   song_transpose        { song, direction, key, source }
 *   song_autoscroll       { song, source }
 *   song_autoscroll_speed { song, speed, source }
 *   song_chords_toggle    { song, source }
 *   song_print            { song, source }
 *   cancionero_filter     { album, source }
 *   show_click            { venue, label, source }
 *   shop_click            { url, source }
 *   platform_click        { platform, song?, album?, url, source }
 *   contact_submit        { source }
 *
 * @package Santiago_Moraes
 */

( function () {
	'use strict';

	// Closed vocabulary for the "source" (Origen) dimension. The song_open
	// values home / cancionero / album / album_chords predate this file and are
	// kept verbatim so historical series stay whole.
	var SOURCE = {
		HOME: 'home',
		CANCIONERO: 'cancionero',
		SONG: 'ficha_cancion',
		ALBUM: 'album',
		ALBUM_CHORDS: 'album_chords',
		SHOWS: 'shows',
		HEADER: 'header',
		FOOTER: 'footer',
		SIDEBAR: 'sidebar',
		CONTACT: 'contacto',
	};

	var PLATFORM_HOSTS = {
		'spotify.com': 'spotify',
		'youtube.com': 'youtube',
		'youtu.be': 'youtube',
		'bandcamp.com': 'bandcamp',
		'soundcloud.com': 'soundcloud',
		'instagram.com': 'instagram',
		'music.apple.com': 'apple_music',
		'littlebutterflyrecords.com': 'vinyl',
	};

	// Closed vocabulary for "platform", also used to read a platform off a BEM
	// modifier or a title attribute when the host is not recognizable.
	var PLATFORMS = [ 'spotify', 'youtube', 'bandcamp', 'soundcloud', 'instagram', 'apple_music', 'vinyl' ];

	var MODIFIER_PREFIXES = [ 'platform-card--', 'song-sidebar__link--', 'song-streaming__link--', 'widget-links__item--', 'header-social__link--' ];

	/**
	 * Send an event, dropping empty parameters so GA4 never records a blank
	 * dimension value. No-op when gtag is missing.
	 *
	 * @param {string} name   Event name.
	 * @param {Object} params Event parameters.
	 */
	function track( name, params ) {
		if ( typeof window.gtag !== 'function' ) {
			return;
		}

		var payload = {};

		for ( var key in params ) {
			if ( ! Object.prototype.hasOwnProperty.call( params, key ) ) {
				continue;
			}
			var value = params[ key ];
			if ( value === undefined || value === null || value === '' ) {
				continue;
			}
			payload[ key ] = value;
		}

		window.gtag( 'event', name, payload );
	}

	function text( el ) {
		return el ? el.textContent.trim().replace( /\s+/g, ' ' ) : '';
	}

	// -------------------------------------------------------------
	// Slugs — the single normalization used by every call site.
	// -------------------------------------------------------------

	/**
	 * Normalize a value to a slug: lowercase, unaccented, hyphenated.
	 *
	 * @param {string} value Raw value.
	 * @return {string} Slug, or '' when there is nothing to normalize.
	 */
	function slugify( value ) {
		if ( ! value ) {
			return '';
		}

		var slug = String( value ).toLowerCase();

		if ( typeof slug.normalize === 'function' ) {
			slug = slug.normalize( 'NFD' ).replace( /[\u0300-\u036f]/g, '' );
		}

		return slug.replace( /[^a-z0-9]+/g, '-' ).replace( /^-+|-+$/g, '' );
	}

	/**
	 * Slug of a URL sitting under a known base path.
	 *
	 * @param {string} url  Absolute or relative URL.
	 * @param {string} base Path segment before the slug ("canciones", "album").
	 * @return {string} Slug, or '' when the URL does not match.
	 */
	function slugFromUrl( url, base ) {
		if ( ! url ) {
			return '';
		}

		var path;

		try {
			path = new URL( url, window.location.href ).pathname;
		} catch ( err ) {
			return '';
		}

		var match = path.match( new RegExp( '/' + base + '/([^/]+)' ) );

		return match ? slugify( decodeURIComponent( match[ 1 ] ) ) : '';
	}

	/**
	 * First slug of a space-separated list ("hogar caprichos" → "hogar").
	 *
	 * @param {string} list Space-separated slugs.
	 * @return {string} First slug.
	 */
	function firstSlug( list ) {
		if ( ! list ) {
			return '';
		}

		var parts = String( list ).trim().split( /\s+/ );

		return parts[ 0 ] ? slugify( parts[ 0 ] ) : '';
	}

	function songSlugFromHref( el ) {
		return el ? slugFromUrl( el.href, 'canciones' ) : '';
	}

	// Album names shown on the page mapped to their real slug, read off the
	// album cards. WP slugs can be edited independently of the name, so this
	// lookup is preferred over slugifying the name.
	var albumSlugByName = null;

	function albumSlugFromName( name ) {
		if ( ! name ) {
			return '';
		}

		if ( ! albumSlugByName ) {
			albumSlugByName = {};

			var cards = document.querySelectorAll( '.disco-card' );

			for ( var i = 0; i < cards.length; i++ ) {
				var cardName = text( cards[ i ].querySelector( '.disco-card__name' ) ).toLowerCase();
				var cardSlug = slugFromUrl( cards[ i ].href, 'album' );

				if ( cardName && cardSlug ) {
					albumSlugByName[ cardName ] = cardSlug;
				}
			}
		}

		return albumSlugByName[ String( name ).trim().toLowerCase() ] || slugify( name );
	}

	// -------------------------------------------------------------
	// Page context.
	// -------------------------------------------------------------

	// Album of the current /album/{slug}/ page, if any.
	function currentAlbumSlug() {
		return slugFromUrl( window.location.href, 'album' );
	}

	// Song of the current /canciones/{slug}/ page, if any.
	function currentSongSlug() {
		return slugFromUrl( window.location.href, 'canciones' ) || slugify( text( document.querySelector( '.song-header__title' ) ) );
	}

	var schemaAlbumSlug;

	// Album of the current song page, from the "Del álbum X" link, falling back
	// to includedComposition.url in the MusicComposition JSON-LD.
	function currentSongAlbumSlug() {
		var link = document.querySelector( 'a.song-header__album-link' );
		var slug = link ? slugFromUrl( link.href, 'album' ) : '';

		if ( slug ) {
			return slug;
		}

		if ( schemaAlbumSlug === undefined ) {
			schemaAlbumSlug = '';

			var blocks = document.querySelectorAll( 'script[type="application/ld+json"]' );

			for ( var i = 0; i < blocks.length; i++ ) {
				var data;

				try {
					data = JSON.parse( blocks[ i ].textContent );
				} catch ( err ) {
					continue;
				}

				if ( data && data.includedComposition && data.includedComposition.url ) {
					schemaAlbumSlug = slugFromUrl( data.includedComposition.url, 'album' );
					break;
				}
			}
		}

		return schemaAlbumSlug;
	}

	// Song page controls: slug from the URL, key as currently displayed.
	function songContext() {
		var viewer = document.querySelector( '.chord-viewer' );

		if ( ! viewer ) {
			return null;
		}

		return {
			song: currentSongSlug(),
			key: text( viewer.querySelector( '.chord-control__key-current' ) ) || viewer.dataset.originalKey || '',
		};
	}

	// Key shown in the cancionero preview panel ("Tono: Eb (+1)") before the
	// click being tracked is applied.
	function previewKey( row ) {
		var meta = document.getElementById( 'preview-meta' );
		var match = meta ? meta.textContent.match( /:\s*([A-G][#b]?\S*)/ ) : null;

		if ( match ) {
			return match[ 1 ];
		}

		return row ? ( row.dataset.key || '' ) : '';
	}

	/**
	 * Where in the site the interaction happened.
	 *
	 * Chrome first: header / footer / home sidebar win over the page they are
	 * rendered on, so a Spotify link in the footer never looks like a song click.
	 *
	 * @param {Element} el Clicked element.
	 * @return {string} A SOURCE value, or '' when nothing matches.
	 */
	function sectionSource( el ) {
		if ( el.closest( '.site-header' ) ) {
			return SOURCE.HEADER;
		}
		if ( el.closest( '.site-footer' ) ) {
			return SOURCE.FOOTER;
		}
		if ( el.closest( '.home-sidebar' ) ) {
			return SOURCE.SIDEBAR;
		}
		if ( el.closest( '.album-page' ) ) {
			return SOURCE.ALBUM;
		}
		if ( el.closest( '.page-acordes' ) ) {
			return SOURCE.CANCIONERO;
		}
		if ( el.closest( '.single-cancion' ) ) {
			return SOURCE.SONG;
		}
		if ( el.closest( '.page-contact' ) ) {
			return SOURCE.CONTACT;
		}
		if ( document.body.classList.contains( 'home' ) ) {
			return SOURCE.HOME;
		}

		return '';
	}

	// -------------------------------------------------------------
	// Platforms.
	// -------------------------------------------------------------

	function platformFromUrl( url ) {
		for ( var host in PLATFORM_HOSTS ) {
			if ( url.indexOf( host ) !== -1 ) {
				return PLATFORM_HOSTS[ host ];
			}
		}

		return '';
	}

	/**
	 * Platform of a streaming/shop link: host first, then the BEM modifier or
	 * the title attribute, both constrained to the PLATFORMS vocabulary.
	 *
	 * @param {Element} el Anchor element.
	 * @return {string} Platform, or '' when unknown.
	 */
	function platformOf( el ) {
		var platform = platformFromUrl( el.href );

		if ( platform ) {
			return platform;
		}

		for ( var i = 0; i < PLATFORMS.length; i++ ) {
			for ( var j = 0; j < MODIFIER_PREFIXES.length; j++ ) {
				if ( el.classList.contains( MODIFIER_PREFIXES[ j ] + PLATFORMS[ i ] ) ) {
					return PLATFORMS[ i ];
				}
			}
		}

		var title = slugify( el.getAttribute( 'title' ) || '' );

		return PLATFORMS.indexOf( title ) !== -1 ? title : '';
	}

	/**
	 * Song and album a streaming link belongs to, so platform_click can be
	 * attributed. Both are omitted where the page carries no such context
	 * (header, footer, sidebar).
	 *
	 * @param {Element} el Anchor element.
	 * @return {Object} { source, song, album }.
	 */
	function platformContext( el ) {
		var ctx = { source: sectionSource( el ), song: '', album: '' };
		var trackRow = el.closest( '.track-row' );

		// Per-track links inside an album tracklist.
		if ( trackRow ) {
			ctx.song = songSlugFromHref( trackRow.querySelector( 'a.track-row__title' ) );
			ctx.album = currentAlbumSlug();
			return ctx;
		}

		if ( ctx.source === SOURCE.ALBUM ) {
			ctx.album = currentAlbumSlug();
			return ctx;
		}

		if ( ctx.source === SOURCE.SONG ) {
			ctx.song = currentSongSlug();
			ctx.album = currentSongAlbumSlug();
			return ctx;
		}

		return ctx;
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
				source: SOURCE.SONG,
			} );
			return;
		}
		if ( el.classList.contains( 'chord-control--autoscroll' ) ) {
			track( 'song_autoscroll', { song: ctx ? ctx.song : '', source: SOURCE.SONG } );
			return;
		}
		if ( el.classList.contains( 'chord-control--toggle' ) ) {
			track( 'song_chords_toggle', { song: ctx ? ctx.song : '', source: SOURCE.SONG } );
			return;
		}

		// Cancionero preview panel — same transpose event as the chord viewer.
		if ( el.classList.contains( 'acordes-preview__btn' ) ) {
			var previewRow = document.querySelector( '.acordes-row--active' );
			track( 'song_transpose', {
				song: previewRow ? songSlugFromHref( previewRow ) : '',
				direction: 'preview-down' === el.id ? 'down' : 'up',
				key: previewKey( previewRow ),
				source: SOURCE.CANCIONERO,
			} );
			return;
		}

		// Album cover cards.
		if ( el.classList.contains( 'disco-card' ) ) {
			track( 'album_click', {
				album: slugFromUrl( el.href, 'album' ) || albumSlugFromName( text( el.querySelector( '.disco-card__name' ) ) || el.getAttribute( 'aria-label' ) ),
				source: sectionSource( el ),
			} );
			return;
		}

		// Songs opened from lists.
		if ( el.classList.contains( 'acordes-row' ) ) {
			track( 'song_open', {
				song: songSlugFromHref( el ),
				album: firstSlug( el.dataset.albums ),
				source: SOURCE.CANCIONERO,
			} );
			return;
		}
		if ( el.classList.contains( 'acordes-preview__cta' ) ) {
			var activeRow = document.querySelector( '.acordes-row--active' );
			track( 'song_open', {
				song: songSlugFromHref( el ) || ( activeRow ? songSlugFromHref( activeRow ) : '' ),
				album: activeRow ? firstSlug( activeRow.dataset.albums ) : '',
				source: SOURCE.CANCIONERO,
			} );
			return;
		}
		if ( el.classList.contains( 'recent__link' ) ) {
			track( 'song_open', {
				song: songSlugFromHref( el ),
				album: albumSlugFromName( el.dataset.album ),
				source: SOURCE.HOME,
			} );
			return;
		}
		// Tracklist rows, except the per-track platform links handled below.
		if ( el.closest( '.track-row' ) && 'A' === el.tagName && ! el.classList.contains( 'track-row__platform' ) ) {
			var row = el.closest( '.track-row' );
			track( 'song_open', {
				song: songSlugFromHref( row.querySelector( 'a.track-row__title' ) ) || songSlugFromHref( el ),
				album: currentAlbumSlug(),
				source: row.classList.contains( 'track-row--chords' ) ? SOURCE.ALBUM_CHORDS : SOURCE.ALBUM,
			} );
			return;
		}

		// Cancionero album filters.
		if ( el.classList.contains( 'acordes-filter' ) ) {
			track( 'cancionero_filter', {
				album: slugify( el.dataset.album || text( el ) ),
				source: SOURCE.CANCIONERO,
			} );
			return;
		}

		// Shows (Bandsintown rows and "all dates" link).
		if ( el.classList.contains( 'show-row' ) ) {
			track( 'show_click', {
				venue: text( el.querySelector( '.show-row__venue' ) ),
				label: text( el.querySelector( '.show-row__cta' ) ),
				source: SOURCE.SHOWS,
			} );
			return;
		}

		// Shop.
		if ( el.closest( '.widget--shop' ) && 'A' === el.tagName ) {
			track( 'shop_click', { url: el.href, source: sectionSource( el ) } );
			return;
		}

		// Streaming / social platforms anywhere.
		if ( 'A' === el.tagName && el.href ) {
			var platform = platformOf( el );
			if ( platform ) {
				var link = platformContext( el );
				track( 'platform_click', {
					platform: platform,
					song: link.song,
					album: link.album,
					url: el.href,
					source: link.source,
				} );
			}
		}
	}, true );

	// -------------------------------------------------------------
	// Auto-scroll speed — "change" fires once the slider is released,
	// not on every pixel like "input".
	// -------------------------------------------------------------
	document.addEventListener( 'change', function ( e ) {
		var el = e.target;

		if ( ! el || ! el.classList || ! el.classList.contains( 'chord-control__speed' ) ) {
			return;
		}

		var ctx = songContext();

		track( 'song_autoscroll_speed', {
			song: ctx ? ctx.song : '',
			speed: Number( el.value ) || 0,
			source: SOURCE.SONG,
		} );
	}, true );

	// -------------------------------------------------------------
	// Print (chord sheet) — fires for Ctrl+P and any print button.
	// -------------------------------------------------------------
	window.addEventListener( 'beforeprint', function () {
		var ctx = songContext();
		if ( ctx ) {
			track( 'song_print', { song: ctx.song, source: SOURCE.SONG } );
		}
	} );

	// -------------------------------------------------------------
	// Contact form submissions.
	// -------------------------------------------------------------
	document.addEventListener( 'submit', function ( e ) {
		var form = e.target;
		if ( form.querySelector( 'input[name="action"][value="sm_contact_form"]' ) ) {
			track( 'contact_submit', { source: form.closest( '.home-sidebar' ) ? SOURCE.SIDEBAR : SOURCE.CONTACT } );
		}
	}, true );
} )();
