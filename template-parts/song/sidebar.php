<?php
/**
 * Song Sidebar — streaming links, details, related songs.
 *
 * Displayed in the right column on desktop, below chord viewer on mobile.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$song_id      = get_the_ID();
$spotify      = get_post_meta( $song_id, '_cancion_spotify_url', true );
$youtube      = get_post_meta( $song_id, '_cancion_youtube_url', true );
$soundcloud   = get_post_meta( $song_id, '_cancion_soundcloud_url', true );
$original_key = get_post_meta( $song_id, '_cancion_original_key', true );
$year         = get_post_meta( $song_id, '_cancion_year', true );
$capo         = get_post_meta( $song_id, '_cancion_capo', true );
$tempo        = get_post_meta( $song_id, '_cancion_tempo', true );

$album_terms = get_the_terms( $song_id, 'album' );
$album_term  = ( $album_terms && ! is_wp_error( $album_terms ) ) ? $album_terms[0] : null;
$album_name  = $album_term ? $album_term->name : '';

$genres      = get_the_terms( $song_id, 'genero' );
?>

<aside class="song-sidebar">

	<?php // ── Escuchar Card ── ?>
	<?php if ( $spotify || $youtube || $soundcloud ) : ?>
		<div class="song-sidebar__card">
			<h3 class="song-sidebar__card-title"><?php esc_html_e( 'Escuchar', 'santiago-moraes' ); ?></h3>
			<div class="song-sidebar__links">
				<?php if ( $spotify ) : ?>
					<a href="<?php echo esc_url( $spotify ); ?>" class="song-sidebar__link song-sidebar__link--spotify" target="_blank" rel="noopener noreferrer">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" width="20" height="20" fill="currentColor"><path d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>
						<span>Spotify</span>
					</a>
				<?php endif; ?>
				<?php if ( $youtube ) : ?>
					<a href="<?php echo esc_url( $youtube ); ?>" class="song-sidebar__link song-sidebar__link--youtube" target="_blank" rel="noopener noreferrer">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20" height="20" fill="currentColor"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
						<span>YouTube</span>
					</a>
				<?php endif; ?>
				<?php if ( $soundcloud ) : ?>
					<a href="<?php echo esc_url( $soundcloud ); ?>" class="song-sidebar__link song-sidebar__link--bandcamp" target="_blank" rel="noopener noreferrer">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20" fill="currentColor"><path d="M256 8C119 8 8 119 8 256S119 504 256 504 504 393 504 256 393 8 256 8zm48.2 326.1h-181L207.9 178h181z"/></svg>
						<span>Bandcamp</span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php // ── Detalles Card ── ?>
	<?php if ( $original_key || $album_name || $year || ( $capo && (int) $capo > 0 ) || $tempo || ( $genres && ! is_wp_error( $genres ) ) ) : ?>
		<div class="song-sidebar__card">
			<h3 class="song-sidebar__card-title"><?php esc_html_e( 'Detalles', 'santiago-moraes' ); ?></h3>
			<dl class="song-sidebar__details">
				<?php if ( $original_key ) : ?>
					<div class="song-sidebar__detail">
						<dt><?php esc_html_e( 'Tonalidad', 'santiago-moraes' ); ?></dt>
						<dd><?php echo esc_html( $original_key ); ?></dd>
					</div>
				<?php endif; ?>
				<?php if ( $album_name ) : ?>
					<div class="song-sidebar__detail">
						<dt><?php esc_html_e( 'Álbum', 'santiago-moraes' ); ?></dt>
						<dd><?php echo esc_html( $album_name ); ?></dd>
					</div>
				<?php endif; ?>
				<?php if ( $year ) : ?>
					<div class="song-sidebar__detail">
						<dt><?php esc_html_e( 'Año', 'santiago-moraes' ); ?></dt>
						<dd><?php echo esc_html( $year ); ?></dd>
					</div>
				<?php endif; ?>
				<?php if ( $capo && (int) $capo > 0 ) : ?>
					<div class="song-sidebar__detail">
						<dt><?php esc_html_e( 'Capo', 'santiago-moraes' ); ?></dt>
						<dd><?php printf( esc_html__( 'Traste %d', 'santiago-moraes' ), (int) $capo ); ?></dd>
					</div>
				<?php endif; ?>
				<?php if ( $tempo ) : ?>
					<div class="song-sidebar__detail">
						<dt><?php esc_html_e( 'Tempo', 'santiago-moraes' ); ?></dt>
						<dd><?php echo esc_html( $tempo ); ?></dd>
					</div>
				<?php endif; ?>
				<?php if ( $genres && ! is_wp_error( $genres ) ) : ?>
					<div class="song-sidebar__detail">
						<dt><?php esc_html_e( 'Género', 'santiago-moraes' ); ?></dt>
						<dd><?php echo esc_html( implode( ', ', wp_list_pluck( $genres, 'name' ) ) ); ?></dd>
					</div>
				<?php endif; ?>
			</dl>
		</div>
	<?php endif; ?>

	<?php
	// ── Del mismo álbum Card ──
	if ( $album_term ) :
		$album_songs = new WP_Query(
			array(
				'post_type'      => 'cancion',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'post__not_in'   => array( $song_id ),
				'orderby'        => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'album',
						'field'    => 'term_id',
						'terms'    => $album_term->term_id,
					),
				),
			)
		);

		if ( $album_songs->have_posts() ) :
			?>
			<div class="song-sidebar__card">
				<h3 class="song-sidebar__card-title">
					<?php
					/* translators: %s: album name */
					printf( esc_html__( 'Del álbum %s', 'santiago-moraes' ), esc_html( $album_term->name ) );
					?>
				</h3>
				<ul class="song-sidebar__tracklist">
					<?php
					while ( $album_songs->have_posts() ) :
						$album_songs->the_post();
						$has_lyrics = (bool) get_post_meta( get_the_ID(), '_cancion_lyrics', true );
						?>
						<li class="song-sidebar__track">
							<a href="<?php the_permalink(); ?>" class="song-sidebar__track-link">
								<span class="song-sidebar__track-title"><?php the_title(); ?></span>
								<?php if ( $has_lyrics ) : ?>
									<span class="song-sidebar__track-badge" title="<?php esc_attr_e( 'Tiene acordes', 'santiago-moraes' ); ?>">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="12" height="12" fill="currentColor"><path d="M499.1 6.3c8.1 6 12.9 15.6 12.9 25.7l0 72 0 264c0 44.2-43 80-96 80s-96-35.8-96-80s43-80 96-80c11.2 0 22 1.6 32 4.6L448 147 192 223.8 192 432c0 44.2-43 80-96 80s-96-35.8-96-80s43-80 96-80c11.2 0 22 1.6 32 4.6L128 160l0-72c0-12.8 7.6-24.4 19.4-29.5l288-120c10-4.2 21.6-3 30.7 3.3z"/></svg>
									</span>
								<?php endif; ?>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
			<?php
			wp_reset_postdata();
		endif;
	endif;
	?>

	<?php
	// ── Más canciones con acordes Card ──
	$related_args = array(
		'post_type'      => 'cancion',
		'posts_per_page' => 10,
		'post_status'    => 'publish',
		'post__not_in'   => array( $song_id ),
		'orderby'        => 'rand',
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_cancion_lyrics',
				'value'   => '',
				'compare' => '!=',
			),
		),
	);

	$related_songs = new WP_Query( $related_args );

	if ( $related_songs->have_posts() ) :
		?>
		<div class="song-sidebar__card">
			<h3 class="song-sidebar__card-title"><?php esc_html_e( 'Más canciones con acordes', 'santiago-moraes' ); ?></h3>
			<ul class="song-sidebar__related">
				<?php
				while ( $related_songs->have_posts() ) :
					$related_songs->the_post();
					$rel_album_terms = get_the_terms( get_the_ID(), 'album' );
					$rel_album_name  = ( $rel_album_terms && ! is_wp_error( $rel_album_terms ) ) ? $rel_album_terms[0]->name : '';
					?>
					<li class="song-sidebar__related-item">
						<a href="<?php the_permalink(); ?>" class="song-sidebar__related-link">
							<span class="song-sidebar__related-title"><?php the_title(); ?></span>
							<?php if ( $rel_album_name ) : ?>
								<span class="song-sidebar__related-album"><?php echo esc_html( $rel_album_name ); ?></span>
							<?php endif; ?>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
		<?php
		wp_reset_postdata();
	endif;
	?>

</aside>
