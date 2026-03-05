<?php
/**
 * Song metadata display — album, year, key, capo, tempo.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$original_key = get_post_meta( get_the_ID(), '_cancion_original_key', true );
$year         = get_post_meta( get_the_ID(), '_cancion_year', true );
$capo         = get_post_meta( get_the_ID(), '_cancion_capo', true );
$tempo        = get_post_meta( get_the_ID(), '_cancion_tempo', true );

// Get album name from taxonomy (not meta field).
$album_terms = get_the_terms( get_the_ID(), 'album' );
$album       = ( $album_terms && ! is_wp_error( $album_terms ) ) ? $album_terms[0]->name : '';

// Get genre terms.
$genres = get_the_terms( get_the_ID(), 'genero' );

// Check if there's any meta to display.
if ( ! $original_key && ! $album && ! $year && ! $capo && ! $tempo && ! $genres ) {
	return;
}
?>
<div class="song-meta">
	<?php if ( $original_key ) : ?>
		<span class="song-meta__item">
			<span class="song-meta__label"><?php esc_html_e( 'Tonalidad:', 'santiago-moraes' ); ?></span>
			<?php echo esc_html( $original_key ); ?>
		</span>
	<?php endif; ?>

	<?php if ( $album ) : ?>
		<span class="song-meta__item">
			<span class="song-meta__label"><?php esc_html_e( 'Álbum:', 'santiago-moraes' ); ?></span>
			<?php echo esc_html( $album ); ?>
		</span>
	<?php endif; ?>

	<?php if ( $year ) : ?>
		<span class="song-meta__item">
			<span class="song-meta__label"><?php esc_html_e( 'Año:', 'santiago-moraes' ); ?></span>
			<?php echo esc_html( $year ); ?>
		</span>
	<?php endif; ?>

	<?php if ( $capo && (int) $capo > 0 ) : ?>
		<span class="song-meta__item">
			<span class="song-meta__label"><?php esc_html_e( 'Capo:', 'santiago-moraes' ); ?></span>
			<?php /* translators: %d: capo fret number */ ?>
			<?php printf( esc_html__( 'Traste %d', 'santiago-moraes' ), (int) $capo ); ?>
		</span>
	<?php endif; ?>

	<?php if ( $tempo ) : ?>
		<span class="song-meta__item">
			<span class="song-meta__label"><?php esc_html_e( 'Tempo:', 'santiago-moraes' ); ?></span>
			<?php echo esc_html( $tempo ); ?>
		</span>
	<?php endif; ?>

	<?php if ( $genres && ! is_wp_error( $genres ) ) : ?>
		<span class="song-meta__item">
			<span class="song-meta__label"><?php esc_html_e( 'Género:', 'santiago-moraes' ); ?></span>
			<?php
			$genre_names = wp_list_pluck( $genres, 'name' );
			echo esc_html( implode( ', ', $genre_names ) );
			?>
		</span>
	<?php endif; ?>
</div>
