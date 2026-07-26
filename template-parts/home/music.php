<?php
/**
 * Homepage Music section — Rebranding.
 *
 * Displays the featured album dynamically from admin settings
 * (Apariencia > Santiago Moraes > Musica > Album Destacado).
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

// Get the featured album term from admin settings.
$album_id = (int) sm_get_option( 'sm_featured_album_id', 0 );
$album    = $album_id ? get_term( $album_id, 'album' ) : null;

// Fallback: if no album selected, get the most recent one.
if ( ! $album || is_wp_error( $album ) ) {
	$fallback = get_terms( array(
		'taxonomy'   => 'album',
		'hide_empty' => false,
		'number'     => 1,
		'orderby'    => 'meta_value_num',
		'meta_key'   => '_album_year', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'order'      => 'DESC',
		'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'relation' => 'OR',
			array( 'key' => '_album_is_demo', 'value' => '1', 'compare' => '!=' ),
			array( 'key' => '_album_is_demo', 'compare' => 'NOT EXISTS' ),
		),
	) );
	if ( ! is_wp_error( $fallback ) && ! empty( $fallback ) ) {
		$album = $fallback[0];
	}
}

if ( ! $album ) {
	return;
}

$album_name  = $album->name;
$album_year  = get_term_meta( $album->term_id, '_album_year', true );
$album_desc  = get_term_meta( $album->term_id, '_album_description', true );
$cover_id    = get_term_meta( $album->term_id, '_album_cover_id', true );
$song_count  = $album->count;

// Album cover image.
if ( $cover_id ) {
	$album_image_tag = wp_get_attachment_image( (int) $cover_id, 'sm-album-cover', false, array(
		'loading' => 'lazy',
		'alt'     => esc_attr( $album_name ),
	) );
} else {
	$album_image_tag = '<div class="music__placeholder"><span class="mono-label">' . esc_html( strtolower( 'tapa · ' . $album_name ) ) . '<br>[soltar imagen aquí]</span></div>';
}

// Platform links from album meta.
$platforms = array();

$spotify = get_term_meta( $album->term_id, '_album_spotify_url', true );
if ( $spotify ) {
	$platforms[] = array( 'label' => 'Spotify', 'url' => $spotify );
}

$bandcamp = get_term_meta( $album->term_id, '_album_bandcamp_url', true );
if ( $bandcamp ) {
	$platforms[] = array( 'label' => 'Bandcamp', 'url' => $bandcamp );
}

$youtube = get_term_meta( $album->term_id, '_album_youtube_url', true );
if ( $youtube ) {
	$platforms[] = array( 'label' => 'YouTube', 'url' => $youtube );
}

$vinyl = get_term_meta( $album->term_id, '_album_vinyl_url', true );
if ( $vinyl ) {
	$platforms[] = array( 'label' => 'Vinilo', 'url' => $vinyl );
}

// Build meta text.
$meta_parts = array( 'Santiago Moraes' );
if ( $album_year ) {
	$meta_parts[] = $album_year;
}
if ( $song_count ) {
	$meta_parts[] = sprintf( _n( '%d canción', '%d canciones', $song_count, 'santiago-moraes' ), $song_count );
}
?>

<section class="music" id="musica">
	<div class="music__inner">

		<div class="music__cover">
			<?php echo $album_image_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<div class="music__info">
			<p class="music__tag mono-label"><?php esc_html_e( 'Último disco', 'santiago-moraes' ); ?></p>

			<h2 class="music__album-title"><?php echo esc_html( $album_name ); ?></h2>

			<p class="music__meta mono-label"><?php echo esc_html( implode( ' · ', $meta_parts ) ); ?></p>

			<?php if ( $album_desc ) : ?>
				<p class="music__description"><?php echo esc_html( $album_desc ); ?></p>
			<?php endif; ?>

			<?php if ( $platforms ) : ?>
				<div class="music__platforms">
					<?php foreach ( $platforms as $platform ) : ?>
						<a href="<?php echo esc_url( $platform['url'] ); ?>" class="btn btn--mono" target="_blank" rel="noopener noreferrer">
							<?php echo esc_html( $platform['label'] ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>
