<?php
/**
 * Bandsintown integration — fetches upcoming events from the public API
 * and renders them with the theme's own markup (no third-party JS).
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

define( 'SM_BANDSINTOWN_DEFAULT_ARTIST', 'id_15656705' );
define( 'SM_BANDSINTOWN_APP_ID', 'js_santiagomoraes' );
define( 'SM_BANDSINTOWN_CACHE_TTL', 6 * HOUR_IN_SECONDS );

/**
 * Artist identifier used in API calls: "id_<numeric id>" or the exact artist name.
 *
 * @return string
 */
function sm_bandsintown_artist() {
	return sm_get_option( 'sm_bandsintown_artist', SM_BANDSINTOWN_DEFAULT_ARTIST );
}

/**
 * Public artist page on Bandsintown.
 *
 * @return string
 */
function sm_bandsintown_artist_url() {
	$artist = sm_bandsintown_artist();

	if ( 0 === strpos( $artist, 'id_' ) ) {
		return 'https://www.bandsintown.com/a/' . rawurlencode( substr( $artist, 3 ) );
	}

	return 'https://www.bandsintown.com/' . rawurlencode( $artist );
}

/**
 * Upcoming events, normalized. Cached in a transient; a long-lived backup option
 * is served when the API is unreachable so the widget never goes blank.
 *
 * @return array[] See sm_bandsintown_normalize_event() for the item shape.
 */
function sm_bandsintown_get_events() {
	$cached = get_transient( 'sm_bandsintown_events' );

	if ( is_array( $cached ) ) {
		return $cached;
	}

	$url = add_query_arg(
		array(
			'app_id' => SM_BANDSINTOWN_APP_ID,
			'date'   => 'upcoming',
		),
		'https://rest.bandsintown.com/artists/' . rawurlencode( sm_bandsintown_artist() ) . '/events'
	);

	$response = wp_remote_get(
		$url,
		array(
			'timeout'    => 8,
			'user-agent' => 'Mozilla/5.0 (compatible; SantiagoMoraesTheme/' . SM_THEME_VERSION . '; +' . home_url( '/' ) . ')',
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		$backup = get_option( 'sm_bandsintown_events_backup', array() );
		$backup = is_array( $backup ) ? $backup : array();
		set_transient( 'sm_bandsintown_events', $backup, 30 * MINUTE_IN_SECONDS );
		return $backup;
	}

	$raw    = json_decode( wp_remote_retrieve_body( $response ), true );
	$events = array();

	if ( is_array( $raw ) ) {
		foreach ( $raw as $item ) {
			$event = sm_bandsintown_normalize_event( $item );
			if ( $event ) {
				$events[] = $event;
			}
		}
	}

	usort( $events, fn( $a, $b ) => $a['ts'] <=> $b['ts'] );

	set_transient( 'sm_bandsintown_events', $events, SM_BANDSINTOWN_CACHE_TTL );
	update_option( 'sm_bandsintown_events_backup', $events, false );

	return $events;
}

/**
 * Reduce a raw API event to the fields the templates use.
 *
 * Bandsintown returns venue-local times without an offset, so the timestamp is
 * parsed as-is and never converted to the site timezone.
 *
 * @param array $item Raw event from the API.
 * @return array|null
 */
function sm_bandsintown_normalize_event( $item ) {
	if ( empty( $item['datetime'] ) ) {
		return null;
	}

	$ts = strtotime( $item['datetime'] . ' UTC' );

	if ( ! $ts ) {
		return null;
	}

	$months = array( 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic' );
	$venue  = $item['venue'] ?? array();
	$title  = trim( (string) ( $item['title'] ?? '' ) );
	$name   = trim( (string) ( $venue['name'] ?? '' ) );

	// Bandsintown fills the venue name with the event title ("Artist - City") when none is set; drop the city suffix.
	if ( $name && $name === $title && false !== strrpos( $name, ' - ' ) ) {
		$name = trim( substr( $name, 0, strrpos( $name, ' - ' ) ) );
	}

	$city_parts = array_filter( array( $venue['city'] ?? '', $venue['country'] ?? '' ) );

	// Ticket link when one is offered, otherwise the event page.
	$url = $item['url'] ?? '';
	foreach ( (array) ( $item['offers'] ?? array() ) as $offer ) {
		if ( ! empty( $offer['url'] ) && 'Tickets' === ( $offer['type'] ?? '' ) ) {
			$url = $offer['url'];
			break;
		}
	}

	return array(
		'ts'       => $ts,
		'day'      => gmdate( 'j', $ts ),
		'month'    => $months[ (int) gmdate( 'n', $ts ) - 1 ],
		'time'     => gmdate( 'H:i', $ts ),
		'title'    => $title,
		'venue'    => $name,
		'city'     => implode( ', ', $city_parts ),
		'url'      => esc_url_raw( (string) $url ),
		'free'     => ! empty( $item['free'] ),
		'sold_out' => ! empty( $item['sold_out'] ),
	);
}

/**
 * Render an upcoming-events list.
 *
 * @param array $args {
 *     @type int    $limit    Max events (0 = all). Default 5.
 *     @type string $modifier Extra BEM modifier for .show-row. Default ''.
 * }
 */
function sm_bandsintown_list( $args = array() ) {
	$args   = wp_parse_args( $args, array( 'limit' => 5, 'modifier' => '' ) );
	$events = sm_bandsintown_get_events();

	if ( $args['limit'] > 0 ) {
		$events = array_slice( $events, 0, (int) $args['limit'] );
	}

	$row_class = 'show-row' . ( $args['modifier'] ? ' show-row--' . sanitize_html_class( $args['modifier'] ) : '' );

	if ( empty( $events ) ) :
		?>
		<p class="shows__empty"><?php esc_html_e( 'Sin fechas anunciadas por ahora.', 'santiago-moraes' ); ?></p>
		<?php
		return;
	endif;

	foreach ( $events as $event ) :
		// The API often repeats "Artist - City" as the venue name; prefer the real venue when there is one.
		$venue_label = $event['venue'];
		if ( ! $venue_label || $venue_label === $event['title'] ) {
			$venue_label = $event['title'] ? $event['title'] : $event['city'];
		}
		?>
		<a href="<?php echo esc_url( $event['url'] ); ?>" class="<?php echo esc_attr( $row_class ); ?>" target="_blank" rel="noopener noreferrer">
			<span class="show-row__date">
				<span class="show-row__day"><?php echo esc_html( $event['day'] ); ?></span>
				<span class="show-row__month"><?php echo esc_html( $event['month'] ); ?></span>
			</span>
			<span class="show-row__info">
				<span class="show-row__venue"><?php echo esc_html( $venue_label ); ?></span>
				<?php if ( $event['city'] ) : ?>
					<span class="show-row__city"><?php echo esc_html( $event['city'] . ( $event['time'] ? ' · ' . $event['time'] : '' ) ); ?></span>
				<?php endif; ?>
			</span>
			<span class="show-row__cta mono-label">
				<?php
				if ( $event['sold_out'] ) {
					esc_html_e( 'Agotado', 'santiago-moraes' );
				} elseif ( $event['free'] ) {
					esc_html_e( 'Gratis', 'santiago-moraes' );
				} else {
					esc_html_e( 'Entradas', 'santiago-moraes' );
				}
				?>
			</span>
		</a>
		<?php
	endforeach;
}

/**
 * Flush the events cache when Theme Options are saved (the artist may have changed).
 */
function sm_bandsintown_flush_cache() {
	delete_transient( 'sm_bandsintown_events' );
}
add_action( 'update_option_sm_options', 'sm_bandsintown_flush_cache' );
