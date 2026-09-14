<?php
/**
 * Homepage — Cancionero: latest songs with chords loaded (main column, under the discography).
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$recent = new WP_Query(
	array(
		'post_type'      => 'cancion',
		'post_status'    => 'publish',
		'posts_per_page' => 8,
		'orderby'        => 'modified',
		'order'          => 'DESC',
		'no_found_rows'  => true,
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_cancion_lyrics',
				'value'   => '',
				'compare' => '!=',
			),
		),
	)
);

if ( ! $recent->have_posts() ) {
	return;
}
?>

<section class="recent" id="cancionero">

	<div class="recent__header">
		<div>
			<h2 class="recent__title"><?php esc_html_e( 'Cancionero', 'santiago-moraes' ); ?></h2>
			<p class="recent__meta mono-label"><?php esc_html_e( 'Recién agregadas · letras y acordes', 'santiago-moraes' ); ?></p>
		</div>
		<a href="<?php echo esc_url( sm_cancionero_url() ); ?>" class="link-arrow">
			<?php esc_html_e( 'Ver todas →', 'santiago-moraes' ); ?>
		</a>
	</div>

	<ul class="recent__list">
		<?php
		while ( $recent->have_posts() ) :
			$recent->the_post();

			$key    = get_post_meta( get_the_ID(), '_cancion_original_key', true );
			$capo   = (int) get_post_meta( get_the_ID(), '_cancion_capo', true );
			$albums = get_the_terms( get_the_ID(), 'album' );
			$album  = ( $albums && ! is_wp_error( $albums ) ) ? $albums[0]->name : '';

			$meta = array_filter( array( $album, $capo > 0 ? 'Capo ' . $capo : '' ) );
			?>
			<li class="recent__item">
				<a href="<?php the_permalink(); ?>" class="recent__link" data-album="<?php echo esc_attr( $album ); ?>">
					<span class="recent__body">
						<span class="recent__name"><?php the_title(); ?></span>
						<?php if ( $meta ) : ?>
							<span class="recent__album"><?php echo esc_html( implode( ' · ', $meta ) ); ?></span>
						<?php endif; ?>
					</span>
					<?php if ( $key ) : ?>
						<span class="recent__key mono-label"><?php echo esc_html( $key ); ?></span>
					<?php endif; ?>
					<span class="recent__arrow" aria-hidden="true">&rarr;</span>
				</a>
			</li>
			<?php
		endwhile;
		wp_reset_postdata();
		?>
	</ul>

</section>
