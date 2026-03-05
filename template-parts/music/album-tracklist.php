<?php
/**
 * Album Tracklist — List of songs belonging to an album term.
 *
 * Expects $sm_album_term (WP_Term) to be set via set_query_var().
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$album_term = get_query_var( 'sm_album_term' );

if ( ! $album_term instanceof WP_Term ) {
	return;
}

// Query songs assigned to this album, ordered by menu_order (for custom ordering) then title.
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

if ( ! $songs->have_posts() ) {
	wp_reset_postdata();
	return;
}

$track_num = 1;
?>

<section class="tracklist">
	<div class="tracklist__inner">
		<h2 class="tracklist__title"><?php echo esc_html( $album_term->name ); ?> &mdash; <?php esc_html_e( 'Tracklist', 'santiago-moraes' ); ?></h2>

		<div class="tracklist__list">
			<?php
			while ( $songs->have_posts() ) :
				$songs->the_post();

				$audio_file   = get_post_meta( get_the_ID(), '_cancion_audio_file', true );
				$has_lyrics   = (bool) get_post_meta( get_the_ID(), '_cancion_lyrics', true );
				$original_key = get_post_meta( get_the_ID(), '_cancion_original_key', true );
				?>

				<div class="track-row">
					<span class="track-row__number"><?php echo esc_html( str_pad( $track_num, 2, '0', STR_PAD_LEFT ) ); ?></span>

					<div class="track-row__info">
						<span class="track-row__title"><?php the_title(); ?></span>
						<?php if ( $original_key ) : ?>
							<span class="track-row__key"><?php echo esc_html( $original_key ); ?></span>
						<?php endif; ?>
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
