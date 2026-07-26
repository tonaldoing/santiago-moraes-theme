<?php
/**
 * Homepage — Últimas Canciones section — Rebranding.
 *
 * Grid cards with gap-as-border pattern.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$latest_songs = new WP_Query( array(
	'post_type'      => 'cancion',
	'posts_per_page' => 4,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

if ( ! $latest_songs->have_posts() ) {
	return;
}
?>

<section class="latest-songs" id="canciones">
	<div class="latest-songs__inner">

		<div class="latest-songs__header">
			<h2 class="latest-songs__title"><?php esc_html_e( 'Últimas canciones', 'santiago-moraes' ); ?></h2>
			<a href="<?php echo esc_url( home_url( '/acordes/' ) ); ?>" class="link-arrow">
				<?php esc_html_e( 'Acordes y letras →', 'santiago-moraes' ); ?>
			</a>
		</div>

		<div class="latest-songs__grid">
			<?php
			while ( $latest_songs->have_posts() ) :
				$latest_songs->the_post();

				$song_id    = get_the_ID();
				$key        = get_post_meta( $song_id, '_cancion_original_key', true );
				$albums     = wp_get_post_terms( $song_id, 'album' );
				$album_name = ( ! is_wp_error( $albums ) && ! empty( $albums ) ) ? $albums[0]->name : '';
				?>
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="song-card">
					<?php if ( $key ) : ?>
						<span class="song-card__key mono-label"><?php echo esc_html( $key ); ?></span>
					<?php endif; ?>

					<span class="song-card__body">
						<span class="song-card__name"><?php the_title(); ?></span>
						<?php if ( $album_name ) : ?>
							<span class="song-card__album"><?php echo esc_html( $album_name ); ?></span>
						<?php endif; ?>
					</span>
				</a>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>

	</div>
</section>
