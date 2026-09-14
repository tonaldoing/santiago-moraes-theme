<?php
/**
 * Homepage — Discography grid (main column).
 *
 * Compact cover grid: studio albums first, then demos. Covers with chords
 * loaded get an "Acordes" stamp.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$all_albums = get_terms(
	array(
		'taxonomy'   => 'album',
		'hide_empty' => false,
	)
);

if ( is_wp_error( $all_albums ) || empty( $all_albums ) ) {
	return;
}

$all_albums = sm_sort_albums( $all_albums );

$studio_albums = array();
$demo_albums   = array();

foreach ( $all_albums as $album_term ) {
	if ( get_term_meta( $album_term->term_id, '_album_is_demo', true ) ) {
		$demo_albums[] = $album_term;
	} else {
		$studio_albums[] = $album_term;
	}
}

$display_albums = array_merge( $studio_albums, $demo_albums );
$songs_count    = sm_count_songs_with_chords();

$meta_parts   = array();
$meta_parts[] = sprintf( _n( '%d disco', '%d discos', count( $display_albums ), 'santiago-moraes' ), count( $display_albums ) );
if ( $songs_count ) {
	$meta_parts[] = sprintf( _n( '%d canción con acordes', '%d canciones con acordes', $songs_count, 'santiago-moraes' ), $songs_count );
}
?>

<section class="discos" id="discos">

	<div class="discos__header">
		<div>
			<h2 class="discos__title"><?php esc_html_e( 'Discos', 'santiago-moraes' ); ?></h2>
			<p class="discos__meta mono-label"><?php echo esc_html( implode( ' · ', $meta_parts ) ); ?></p>
		</div>
		<a href="<?php echo esc_url( sm_cancionero_url() ); ?>" class="link-arrow">
			<?php esc_html_e( 'Ver cancionero →', 'santiago-moraes' ); ?>
		</a>
	</div>

	<div class="discos__grid">
		<?php
		foreach ( $display_albums as $i => $album_term ) :
			get_template_part(
				'template-parts/music/album-card',
				null,
				array(
					'term'  => $album_term,
					'eager' => $i < 5,
				)
			);
		endforeach;
		?>
	</div>

</section>
