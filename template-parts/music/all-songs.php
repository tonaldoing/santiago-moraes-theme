<?php
/**
 * All Songs fallback — When no album taxonomy terms exist.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$songs = new WP_Query(
	array(
		'post_type'      => 'cancion',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
	)
);

if ( ! $songs->have_posts() ) {
	wp_reset_postdata();
	return;
}

$track_num = 1;
?>

<section class="tracklist tracklist--all">
	<div class="tracklist__inner">
		<h1 class="tracklist__title"><?php esc_html_e( 'Todas las canciones', 'santiago-moraes' ); ?></h1>

		<div class="tracklist__list">
			<?php
			while ( $songs->have_posts() ) :
				$songs->the_post();

				$audio_file = get_post_meta( get_the_ID(), '_cancion_audio_file', true );
				$has_lyrics = (bool) get_post_meta( get_the_ID(), '_cancion_lyrics', true );
				?>

				<div class="track-row">
					<span class="track-row__number"><?php echo esc_html( str_pad( $track_num, 2, '0', STR_PAD_LEFT ) ); ?></span>

					<div class="track-row__info">
						<span class="track-row__title"><?php the_title(); ?></span>
					</div>

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
