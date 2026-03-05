<?php
/**
 * Album taxonomy archive — Individual album page at /album/{slug}/.
 *
 * Dark hero with cover + info + streaming links, then light tracklist,
 * then prev/next album navigation.
 *
 * @package Santiago_Moraes
 */

get_header();

$album_term = get_queried_object();

if ( ! $album_term instanceof WP_Term ) {
	get_footer();
	return;
}

$year        = get_term_meta( $album_term->term_id, '_album_year', true );
$description = get_term_meta( $album_term->term_id, '_album_description', true );
$cover_id    = get_term_meta( $album_term->term_id, '_album_cover_id', true );
$spotify     = get_term_meta( $album_term->term_id, '_album_spotify_url', true );
$bandcamp    = get_term_meta( $album_term->term_id, '_album_bandcamp_url', true );
$youtube     = get_term_meta( $album_term->term_id, '_album_youtube_url', true );
$vinyl       = get_term_meta( $album_term->term_id, '_album_vinyl_url', true );
$is_demo     = (bool) get_term_meta( $album_term->term_id, '_album_is_demo', true );

// Cover image.
if ( $cover_id ) {
	$cover_tag = wp_get_attachment_image(
		(int) $cover_id,
		'large',
		false,
		array(
			'loading' => 'eager',
			'alt'     => esc_attr( $album_term->name . ' - Santiago Moraes' ),
			'class'   => 'album-page__cover-img',
		)
	);
} else {
	$cover_tag = '<img src="' . esc_url( SM_THEME_URI . '/assets/images/album-placeholder.webp' ) . '" alt="' . esc_attr( $album_term->name ) . '" width="600" height="600" loading="eager" class="album-page__cover-img">';
}
?>

<main id="main" class="site-main album-page">

	<!-- ── Hero Section (dark) ── -->
	<section class="album-page__hero">
		<div class="album-page__hero-inner">

			<div class="album-page__cover">
				<?php echo $cover_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="album-page__info">
				<?php if ( $year ) : ?>
					<span class="album-page__year"><?php echo esc_html( $year ); ?></span>
				<?php endif; ?>

				<h1 class="album-page__title"><?php echo esc_html( $album_term->name ); ?></h1>

				<?php if ( $is_demo ) : ?>
					<span class="album-page__badge"><?php esc_html_e( 'Demo / Descarte', 'santiago-moraes' ); ?></span>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<p class="album-page__description"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

				<?php if ( $spotify || $bandcamp || $youtube || $vinyl ) : ?>
					<div class="platform-grid">
						<?php if ( $spotify ) : ?>
							<a href="<?php echo esc_url( $spotify ); ?>" class="platform-card platform-card--spotify" target="_blank" rel="noopener noreferrer">
								<span class="platform-card__icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>
								</span>
								<span class="platform-card__label">Spotify</span>
								<span class="platform-card__external">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
								</span>
							</a>
						<?php endif; ?>

						<?php if ( $bandcamp ) : ?>
							<a href="<?php echo esc_url( $bandcamp ); ?>" class="platform-card platform-card--bandcamp" target="_blank" rel="noopener noreferrer">
								<span class="platform-card__icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256S119 504 256 504 504 393 504 256 393 8 256 8zm48.2 326.1h-181L207.9 178h181z"/></svg>
								</span>
								<span class="platform-card__label">Bandcamp</span>
								<span class="platform-card__external">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
								</span>
							</a>
						<?php endif; ?>

						<?php if ( $youtube ) : ?>
							<a href="<?php echo esc_url( $youtube ); ?>" class="platform-card platform-card--youtube" target="_blank" rel="noopener noreferrer">
								<span class="platform-card__icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
								</span>
								<span class="platform-card__label">YouTube</span>
								<span class="platform-card__external">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
								</span>
							</a>
						<?php endif; ?>

						<?php if ( $vinyl ) : ?>
							<a href="<?php echo esc_url( $vinyl ); ?>" class="platform-card platform-card--vinyl" target="_blank" rel="noopener noreferrer">
								<span class="platform-card__icon">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256-96a96 96 0 1 1 0 192 96 96 0 1 1 0-192zm0 224a128 128 0 1 0 0-256 128 128 0 1 0 0 256zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/></svg>
								</span>
								<span class="platform-card__label">Vinilo</span>
								<span class="platform-card__external">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
								</span>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</section>

	<!-- ── Tracklist Section (light) ── -->
	<?php
	$songs = new WP_Query(
		array(
			'post_type'      => 'cancion',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
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

	if ( $songs->have_posts() ) :
		$track_num = 1;
		?>

		<section class="album-page__tracklist">
			<div class="album-page__tracklist-inner">
				<h2 class="album-page__tracklist-title"><?php esc_html_e( 'Tracklist', 'santiago-moraes' ); ?></h2>

				<div class="tracklist__list">
					<?php
					while ( $songs->have_posts() ) :
						$songs->the_post();

						$audio_file   = get_post_meta( get_the_ID(), '_cancion_audio_file', true );
						$has_lyrics   = (bool) get_post_meta( get_the_ID(), '_cancion_lyrics', true );
						$original_key = get_post_meta( get_the_ID(), '_cancion_original_key', true );
						$song_spotify = get_post_meta( get_the_ID(), '_cancion_spotify_url', true );
						$song_bc      = get_post_meta( get_the_ID(), '_cancion_soundcloud_url', true ); // Bandcamp stored here.
						?>

						<div class="track-row">
							<span class="track-row__number"><?php echo esc_html( str_pad( $track_num, 2, '0', STR_PAD_LEFT ) ); ?></span>

							<div class="track-row__info">
								<span class="track-row__title"><?php the_title(); ?></span>
								<?php if ( $original_key ) : ?>
									<span class="track-row__key"><?php echo esc_html( $original_key ); ?></span>
								<?php endif; ?>
							</div>

							<?php if ( $song_spotify || $song_bc ) : ?>
								<div class="track-row__platforms">
									<?php if ( $song_spotify ) : ?>
										<a href="<?php echo esc_url( $song_spotify ); ?>" class="track-row__platform" target="_blank" rel="noopener noreferrer" title="Spotify">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" width="16" height="16"><path fill="#1DB954" d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>
										</a>
									<?php endif; ?>
									<?php if ( $song_bc ) : ?>
										<a href="<?php echo esc_url( $song_bc ); ?>" class="track-row__platform" target="_blank" rel="noopener noreferrer" title="Bandcamp">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16"><path fill="#629AA9" d="M256 8C119 8 8 119 8 256S119 504 256 504 504 393 504 256 393 8 256 8zm48.2 326.1h-181L207.9 178h181z"/></svg>
										</a>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<?php if ( $audio_file ) : ?>
								<div class="track-row__player">
									<audio controls preload="none">
										<source src="<?php echo esc_url( $audio_file ); ?>">
									</audio>
								</div>
							<?php else : ?>
								<div class="track-row__player track-row__player--empty"></div>
							<?php endif; ?>

							<div class="track-row__actions">
								<?php if ( $has_lyrics ) : ?>
									<a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm">
										<?php esc_html_e( 'Ver letra y acordes', 'santiago-moraes' ); ?>
									</a>
								<?php else : ?>
									<a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm">
										<?php esc_html_e( 'Ver cancion', 'santiago-moraes' ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>

						<?php
						++$track_num;
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>

	<?php endif; ?>

	<!-- ── Prev / Next Album Navigation ── -->
	<?php
	// Get all non-demo albums ordered by year for prev/next navigation.
	$nav_albums = get_terms(
		array(
			'taxonomy'   => 'album',
			'hide_empty' => false,
			'orderby'    => 'meta_value_num',
			'meta_key'   => '_album_year', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'order'      => 'DESC',
			'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_album_is_demo',
					'value'   => $is_demo ? '1' : '0',
					'compare' => $is_demo ? '=' : 'IN',
				),
			),
		)
	);

	// Also include terms where _album_is_demo doesn't exist (legacy terms).
	if ( ! $is_demo ) {
		$legacy = get_terms(
			array(
				'taxonomy'   => 'album',
				'hide_empty' => false,
				'orderby'    => 'meta_value_num',
				'meta_key'   => '_album_year', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'order'      => 'DESC',
				'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_album_is_demo',
						'compare' => 'NOT EXISTS',
					),
				),
			)
		);

		if ( ! is_wp_error( $legacy ) && ! empty( $legacy ) ) {
			// Merge and sort by year DESC.
			$nav_albums = array_merge(
				is_wp_error( $nav_albums ) ? array() : $nav_albums,
				$legacy
			);
			usort( $nav_albums, function ( $a, $b ) {
				$ya = (int) get_term_meta( $a->term_id, '_album_year', true );
				$yb = (int) get_term_meta( $b->term_id, '_album_year', true );
				return $yb - $ya;
			} );
		}
	}

	$prev_album = null;
	$next_album = null;

	if ( ! is_wp_error( $nav_albums ) && count( $nav_albums ) > 1 ) {
		$current_index = null;
		foreach ( $nav_albums as $idx => $nav_term ) {
			if ( $nav_term->term_id === $album_term->term_id ) {
				$current_index = $idx;
				break;
			}
		}

		if ( null !== $current_index ) {
			if ( $current_index > 0 ) {
				$prev_album = $nav_albums[ $current_index - 1 ];
			}
			if ( $current_index < count( $nav_albums ) - 1 ) {
				$next_album = $nav_albums[ $current_index + 1 ];
			}
		}
	}

	if ( $prev_album || $next_album ) :
		?>
		<nav class="album-nav">
			<div class="album-nav__inner">
				<?php if ( $prev_album ) :
					$prev_link = get_term_link( $prev_album );
					if ( ! is_wp_error( $prev_link ) ) :
						?>
						<a href="<?php echo esc_url( $prev_link ); ?>" class="album-nav__link album-nav__link--prev">
							<span class="album-nav__arrow">&larr;</span>
							<span class="album-nav__label"><?php echo esc_html( $prev_album->name ); ?></span>
						</a>
					<?php endif; ?>
				<?php else : ?>
					<span class="album-nav__link album-nav__link--empty"></span>
				<?php endif; ?>

				<?php if ( $next_album ) :
					$next_link = get_term_link( $next_album );
					if ( ! is_wp_error( $next_link ) ) :
						?>
						<a href="<?php echo esc_url( $next_link ); ?>" class="album-nav__link album-nav__link--next">
							<span class="album-nav__label"><?php echo esc_html( $next_album->name ); ?></span>
							<span class="album-nav__arrow">&rarr;</span>
						</a>
					<?php endif; ?>
				<?php else : ?>
					<span class="album-nav__link album-nav__link--empty"></span>
				<?php endif; ?>
			</div>
		</nav>
	<?php endif; ?>

</main>

<?php
get_footer();
