<?php
/**
 * Render: sm/latest-songs
 *
 * Dynamic block — queries latest cancion CPT posts.
 * Reuses .latest-songs / .song-card BEM classes.
 *
 * @package Santiago_Moraes
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    InnerBlocks content (unused).
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$count             = absint( $attributes['count'] ?? 4 );
$show_chords_badge = $attributes['showChordsBadge'] ?? true;

$latest_songs = new WP_Query( array(
	'post_type'      => 'cancion',
	'posts_per_page' => $count,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

if ( ! $latest_songs->have_posts() ) {
	return;
}

$anchor = ! empty( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '';
?>

<section class="latest-songs"<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="latest-songs__inner">

		<h2 class="latest-songs__title"><?php esc_html_e( 'Ultimas Canciones', 'santiago-moraes' ); ?></h2>

		<div class="latest-songs__grid">
			<?php
			while ( $latest_songs->have_posts() ) :
				$latest_songs->the_post();

				$song_id = get_the_ID();
				$lyrics  = get_post_meta( $song_id, '_cancion_lyrics', true );
				$key     = get_post_meta( $song_id, '_cancion_original_key', true );

				// Get album name.
				$albums     = wp_get_post_terms( $song_id, 'album' );
				$album_name = ( ! is_wp_error( $albums ) && ! empty( $albums ) ) ? $albums[0]->name : '';
				?>
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="song-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="song-card__thumb">
							<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
						</div>
					<?php else : ?>
						<div class="song-card__thumb song-card__thumb--placeholder">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor"><path d="M499.1 6.3c8.1 6 12.9 15.6 12.9 25.7v400c0 26.5-28.7 48-64 48s-64-21.5-64-48s28.7-48 64-48c5.5 0 10.9 .5 16 1.5V124.9L192 159.7V456c0 26.5-28.7 48-64 48s-64-21.5-64-48s28.7-48 64-48c5.5 0 10.9 .5 16 1.5V32c0-14.3 9.5-26.9 23.3-30.8l256-72c5.3-1.5 11-1 15.8 2.1z"/></svg>
						</div>
					<?php endif; ?>

					<div class="song-card__body">
						<h3 class="song-card__name"><?php the_title(); ?></h3>

						<?php if ( $album_name ) : ?>
							<span class="song-card__album"><?php echo esc_html( $album_name ); ?></span>
						<?php endif; ?>

						<div class="song-card__meta">
							<?php if ( $key ) : ?>
								<span class="song-card__key"><?php echo esc_html( $key ); ?></span>
							<?php endif; ?>

							<?php if ( $show_chords_badge && ! empty( $lyrics ) ) : ?>
								<span class="song-card__chords" title="<?php esc_attr_e( 'Acordes disponibles', 'santiago-moraes' ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 512 512" fill="currentColor"><path d="M499.1 6.3c8.1 6 12.9 15.6 12.9 25.7v400c0 26.5-28.7 48-64 48s-64-21.5-64-48s28.7-48 64-48c5.5 0 10.9 .5 16 1.5V124.9L192 159.7V456c0 26.5-28.7 48-64 48s-64-21.5-64-48s28.7-48 64-48c5.5 0 10.9 .5 16 1.5V32c0-14.3 9.5-26.9 23.3-30.8l256-72c5.3-1.5 11-1 15.8 2.1z"/></svg>
									<?php esc_html_e( 'Acordes', 'santiago-moraes' ); ?>
								</span>
							<?php endif; ?>
						</div>
					</div>

					<span class="song-card__arrow">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512" fill="currentColor"><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h306.7L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
					</span>
				</a>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>

		<div class="latest-songs__footer">
			<a href="<?php echo esc_url( home_url( '/musica/' ) ); ?>" class="latest-songs__view-all">
				<?php esc_html_e( 'Ver todas las canciones', 'santiago-moraes' ); ?>
				<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 448 512" fill="currentColor"><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h306.7L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
			</a>
		</div>

	</div>
</section>
