<?php
/**
 * Single Cancion (Song) template.
 *
 * 60/40 layout on desktop: chord viewer left, sidebar right.
 * Single column on mobile/tablet with sidebar below.
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main single-cancion">
	<?php
	while ( have_posts() ) :
		the_post();

		$lyrics      = get_post_meta( get_the_ID(), '_cancion_lyrics', true );
		$album_terms = get_the_terms( get_the_ID(), 'album' );
		$album_term  = ( $album_terms && ! is_wp_error( $album_terms ) ) ? $album_terms[0] : null;
		?>

		<div class="song-layout">

			<?php // ── Left Column: Header + Chord Viewer ── ?>
			<div class="song-layout__main">

				<header class="song-header">
					<?php if ( $album_term ) :
						$album_link = get_term_link( $album_term );
						if ( ! is_wp_error( $album_link ) ) :
							?>
							<a href="<?php echo esc_url( $album_link ); ?>" class="song-header__album-link">
								&larr; <?php
								/* translators: %s: album name */
								printf( esc_html__( 'Del álbum %s', 'santiago-moraes' ), esc_html( $album_term->name ) );
								?>
							</a>
						<?php endif; ?>
					<?php endif; ?>

					<h1 class="song-header__title"><?php the_title(); ?></h1>
				</header>

				<?php if ( ! empty( $lyrics ) ) : ?>
					<?php get_template_part( 'template-parts/song/chord-viewer' ); ?>
				<?php else : ?>
					<div class="song-coming-soon">
						<p class="song-coming-soon__text"><?php esc_html_e( 'Letra próximamente', 'santiago-moraes' ); ?></p>
					</div>
				<?php endif; ?>

			</div>

			<?php // ── Right Column: Sidebar ── ?>
			<?php get_template_part( 'template-parts/song/sidebar' ); ?>

		</div>

		<?php
		// ── Prev / Next Song within the same album ──
		if ( $album_term ) :
			$album_songs = new WP_Query(
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
					'fields'         => 'ids',
				)
			);

			$song_ids = $album_songs->posts;
			wp_reset_postdata();

			if ( count( $song_ids ) > 1 ) :
				$current_idx = array_search( get_the_ID(), $song_ids, true );
				$prev_song   = ( false !== $current_idx && $current_idx > 0 ) ? $song_ids[ $current_idx - 1 ] : null;
				$next_song   = ( false !== $current_idx && $current_idx < count( $song_ids ) - 1 ) ? $song_ids[ $current_idx + 1 ] : null;

				if ( $prev_song || $next_song ) :
					?>
					<nav class="song-nav">
						<div class="song-nav__inner">
							<?php if ( $prev_song ) : ?>
								<a href="<?php echo esc_url( get_permalink( $prev_song ) ); ?>" class="song-nav__link song-nav__link--prev">
									<span class="song-nav__arrow">&larr;</span>
									<span class="song-nav__label"><?php echo esc_html( get_the_title( $prev_song ) ); ?></span>
								</a>
							<?php else : ?>
								<span class="song-nav__link song-nav__link--empty"></span>
							<?php endif; ?>

							<?php if ( $next_song ) : ?>
								<a href="<?php echo esc_url( get_permalink( $next_song ) ); ?>" class="song-nav__link song-nav__link--next">
									<span class="song-nav__label"><?php echo esc_html( get_the_title( $next_song ) ); ?></span>
									<span class="song-nav__arrow">&rarr;</span>
								</a>
							<?php else : ?>
								<span class="song-nav__link song-nav__link--empty"></span>
							<?php endif; ?>
						</div>
					</nav>
					<?php
				endif;
			endif;
		endif;
		?>

		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
