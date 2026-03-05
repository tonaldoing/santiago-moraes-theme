<?php
/**
 * Schema Markup — JSON-LD structured data.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'sm_schema_output', 3 );

/**
 * Output appropriate JSON-LD schema based on current page context.
 */
function sm_schema_output() {
	$schemas = array();

	// WebSite schema on every page.
	$schemas[] = sm_schema_website();

	// MusicGroup schema on homepage.
	if ( is_front_page() ) {
		$schemas[] = sm_schema_music_group();
	}

	// MusicAlbum on album taxonomy pages.
	if ( is_tax( 'album' ) ) {
		$album_schema = sm_schema_music_album();
		if ( $album_schema ) {
			$schemas[] = $album_schema;
		}
	}

	// MusicComposition on single song pages.
	if ( is_singular( 'cancion' ) ) {
		$song_schema = sm_schema_music_composition();
		if ( $song_schema ) {
			$schemas[] = $song_schema;
		}
	}

	// MusicEvent on single event pages.
	if ( is_singular( 'evento' ) ) {
		$event_schema = sm_schema_music_event();
		if ( $event_schema ) {
			$schemas[] = $event_schema;
		}
	}

	// Output each schema.
	foreach ( $schemas as $schema ) {
		if ( $schema ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
		}
	}
}

/**
 * WebSite schema.
 *
 * @return array
 */
function sm_schema_website() {
	return array(
		'@context' => 'https://schema.org',
		'@type'    => 'WebSite',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url( '/' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => home_url( '/?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		),
	);
}

/**
 * MusicGroup schema for the artist.
 *
 * @return array
 */
function sm_schema_music_group() {
	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'MusicGroup',
		'name'        => 'Santiago Moraes',
		'url'         => home_url( '/' ),
		'genre'       => array( 'Folk', 'Singer-Songwriter', 'Indie' ),
	);

	// OG image as band image.
	$hero = get_theme_mod( 'sm_hero_image', '' );
	if ( $hero ) {
		$schema['image'] = $hero;
	}

	// Social links as sameAs.
	$same_as = array();
	$social_keys = array(
		'sm_social_spotify',
		'sm_social_instagram',
		'sm_social_youtube',
		'sm_social_bandcamp',
		'sm_social_soundcloud',
		'sm_social_facebook',
		'sm_social_twitter',
	);
	foreach ( $social_keys as $key ) {
		$url = get_theme_mod( $key, '' );
		if ( $url ) {
			$same_as[] = $url;
		}
	}
	if ( $same_as ) {
		$schema['sameAs'] = $same_as;
	}

	return $schema;
}

/**
 * MusicAlbum schema for album taxonomy pages.
 *
 * @return array|null
 */
function sm_schema_music_album() {
	$term = get_queried_object();
	if ( ! $term || ! is_a( $term, 'WP_Term' ) ) {
		return null;
	}

	$year        = get_term_meta( $term->term_id, '_album_year', true );
	$cover_id    = get_term_meta( $term->term_id, '_album_cover_id', true );
	$spotify_url = get_term_meta( $term->term_id, '_album_spotify_url', true );

	$schema = array(
		'@context'  => 'https://schema.org',
		'@type'     => 'MusicAlbum',
		'name'      => $term->name,
		'url'       => get_term_link( $term ),
		'byArtist'  => array(
			'@type' => 'MusicGroup',
			'name'  => 'Santiago Moraes',
			'url'   => home_url( '/' ),
		),
	);

	if ( $year ) {
		$schema['datePublished'] = $year;
	}

	if ( $cover_id ) {
		$img = wp_get_attachment_image_url( $cover_id, 'large' );
		if ( $img ) {
			$schema['image'] = $img;
		}
	}

	if ( $spotify_url ) {
		$schema['sameAs'] = $spotify_url;
	}

	// Track list.
	$tracks = get_posts( array(
		'post_type'      => 'cancion',
		'posts_per_page' => 50,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'album',
				'terms'    => $term->term_id,
			),
		),
		'fields'         => 'ids',
	) );

	if ( $tracks ) {
		$track_list = array();
		$position   = 1;
		foreach ( $tracks as $track_id ) {
			$track_list[] = array(
				'@type'    => 'MusicRecording',
				'name'     => get_the_title( $track_id ),
				'position' => $position,
				'url'      => get_permalink( $track_id ),
			);
			$position++;
		}
		$schema['track'] = $track_list;
		$schema['numTracks'] = count( $tracks );
	}

	return $schema;
}

/**
 * MusicComposition schema for single songs.
 *
 * @return array|null
 */
function sm_schema_music_composition() {
	$post = get_queried_object();
	if ( ! $post ) {
		return null;
	}

	$schema = array(
		'@context'  => 'https://schema.org',
		'@type'     => 'MusicComposition',
		'name'      => get_the_title( $post ),
		'url'       => get_permalink( $post ),
		'composer'  => array(
			'@type' => 'MusicGroup',
			'name'  => 'Santiago Moraes',
		),
	);

	// Musical key.
	$key = get_post_meta( $post->ID, '_cancion_original_key', true );
	if ( $key ) {
		$schema['musicalKey'] = $key;
	}

	// Lyrics presence.
	$lyrics = get_post_meta( $post->ID, '_cancion_lyrics', true );
	if ( $lyrics ) {
		$schema['lyrics'] = array(
			'@type' => 'CreativeWork',
			'text'  => wp_trim_words( wp_strip_all_tags( $lyrics ), 50 ),
		);
	}

	// Album.
	$albums = wp_get_post_terms( $post->ID, 'album' );
	if ( ! is_wp_error( $albums ) && ! empty( $albums ) ) {
		$album = $albums[0];
		$schema['includedComposition'] = array(
			'@type' => 'MusicAlbum',
			'name'  => $album->name,
			'url'   => get_term_link( $album ),
		);
	}

	// Spotify URL.
	$spotify = get_post_meta( $post->ID, '_cancion_spotify_url', true );
	if ( $spotify ) {
		$schema['sameAs'] = $spotify;
	}

	return $schema;
}

/**
 * MusicEvent schema for single events.
 *
 * @return array|null
 */
function sm_schema_music_event() {
	$post = get_queried_object();
	if ( ! $post ) {
		return null;
	}

	$date       = get_post_meta( $post->ID, '_evento_date', true );
	$time       = get_post_meta( $post->ID, '_evento_time', true );
	$venue      = get_post_meta( $post->ID, '_evento_venue', true );
	$city       = get_post_meta( $post->ID, '_evento_city', true );
	$ticket_url = get_post_meta( $post->ID, '_evento_ticket_link', true );
	$price      = get_post_meta( $post->ID, '_evento_price', true );

	if ( ! $date ) {
		return null;
	}

	$start_date = $date;
	if ( $time ) {
		$start_date .= 'T' . $time;
	}

	$schema = array(
		'@context'  => 'https://schema.org',
		'@type'     => 'MusicEvent',
		'name'      => get_the_title( $post ),
		'url'       => get_permalink( $post ),
		'startDate' => $start_date,
		'performer' => array(
			'@type' => 'MusicGroup',
			'name'  => 'Santiago Moraes',
		),
		'eventStatus'     => 'https://schema.org/EventScheduled',
		'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
	);

	if ( $venue || $city ) {
		$location = array(
			'@type' => 'Place',
		);
		if ( $venue ) {
			$location['name'] = $venue;
		}
		if ( $city ) {
			$location['address'] = array(
				'@type'           => 'PostalAddress',
				'addressLocality' => $city,
			);
		}
		$schema['location'] = $location;
	}

	// Featured image.
	if ( has_post_thumbnail( $post ) ) {
		$img = wp_get_attachment_image_url( get_post_thumbnail_id( $post ), 'large' );
		if ( $img ) {
			$schema['image'] = $img;
		}
	}

	// Offers / tickets.
	if ( $ticket_url ) {
		$offer = array(
			'@type' => 'Offer',
			'url'   => $ticket_url,
		);
		if ( $price ) {
			// Try to extract numeric price.
			$numeric = preg_replace( '/[^0-9.]/', '', $price );
			if ( $numeric && is_numeric( $numeric ) ) {
				$offer['price']         = $numeric;
				$offer['priceCurrency'] = 'ARS';
			} else {
				$offer['price'] = 0;
				$offer['priceCurrency'] = 'ARS';
			}
			$offer['availability'] = 'https://schema.org/InStock';
		}
		$schema['offers'] = $offer;
	}

	return $schema;
}
