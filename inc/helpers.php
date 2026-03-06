<?php
/**
 * Helper functions.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Extract a Spotify embed URL from any Spotify link.
 *
 * Accepts album, track, playlist, or artist URLs in the format:
 *   https://open.spotify.com/album/26NInlEZ66aKG9MMguyEpT
 *   https://open.spotify.com/track/3pLdWV...
 *   https://open.spotify.com/playlist/37i9dQ...
 *   https://open.spotify.com/artist/2pfLPT...
 *
 * Returns the embed iframe URL or empty string on failure.
 *
 * @param string $url         Full Spotify URL.
 * @param string $extra_params Extra query params (e.g. '&view=coverart').
 * @return string Embed URL.
 */
function sm_spotify_embed_url( $url, $extra_params = '' ) {
	if ( empty( $url ) ) {
		return '';
	}

	// Match: open.spotify.com/(album|track|playlist|artist)/ID
	if ( preg_match( '#open\.spotify\.com/(album|track|playlist|artist)/([a-zA-Z0-9]+)#', $url, $m ) ) {
		return 'https://open.spotify.com/embed/' . $m[1] . '/' . $m[2] . '?theme=0' . $extra_params;
	}

	return '';
}

/**
 * Get the default Spotify URL for the sticky player.
 *
 * Priority: Theme Options override > featured album Spotify URL > empty.
 *
 * @return string Full Spotify URL (not embed).
 */
function sm_get_default_spotify_url() {
	// Theme Options override.
	$custom = sm_get_option( 'sm_player_spotify_url', '' );
	if ( $custom ) {
		return $custom;
	}

	// Featured album.
	$album_id = absint( sm_get_option( 'sm_featured_album_id', 0 ) );
	if ( $album_id ) {
		$url = get_term_meta( $album_id, '_album_spotify_url', true );
		if ( $url ) {
			return $url;
		}
	}

	return '';
}

/**
 * Get the context-aware Spotify URL for the current page.
 *
 * On single-cancion: uses the song's Spotify URL if available.
 * On album taxonomy: uses the album's Spotify URL.
 * Otherwise: falls back to the default.
 *
 * @return string Full Spotify URL.
 */
function sm_get_contextual_spotify_url() {
	// Single song — use song's Spotify URL.
	if ( is_singular( 'cancion' ) ) {
		$song_url = get_post_meta( get_the_ID(), '_cancion_spotify_url', true );
		if ( $song_url ) {
			return $song_url;
		}
	}

	// Album taxonomy page — use album's Spotify URL.
	if ( is_tax( 'album' ) ) {
		$term = get_queried_object();
		if ( $term ) {
			$album_url = get_term_meta( $term->term_id, '_album_spotify_url', true );
			if ( $album_url ) {
				return $album_url;
			}
		}
	}

	return sm_get_default_spotify_url();
}

/**
 * Count total upcoming events (date >= today).
 *
 * @return int Number of upcoming events.
 */
function sm_count_upcoming_events() {
	$count = wp_cache_get( 'sm_upcoming_events_count' );
	if ( false !== $count ) {
		return (int) $count;
	}

	$query = new WP_Query( array(
		'post_type'      => 'evento',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_evento_date',
				'value'   => gmdate( 'Y-m-d' ),
				'compare' => '>=',
				'type'    => 'DATE',
			),
		),
	) );

	$count = $query->post_count;
	wp_cache_set( 'sm_upcoming_events_count', $count );

	return $count;
}
