<?php
/**
 * Template Name: Musica
 * Template Post Type: page
 *
 * Discography page — Rebranding.
 * Ochre header + album grid with brutalist cards.
 *
 * @package Santiago_Moraes
 */

get_header();

// Get all album terms, ordered by year (most recent first).
$all_albums = get_terms(
	array(
		'taxonomy'   => 'album',
		'hide_empty' => false,
		'orderby'    => 'meta_value_num',
		'meta_key'   => '_album_year', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'order'      => 'DESC',
	)
);

// Separate studio from demos.
$studio_albums = array();
$demo_albums   = array();

if ( ! is_wp_error( $all_albums ) && ! empty( $all_albums ) ) {
	foreach ( $all_albums as $album_term ) {
		$is_demo = (bool) get_term_meta( $album_term->term_id, '_album_is_demo', true );
		if ( $is_demo ) {
			$demo_albums[] = $album_term;
		} else {
			$studio_albums[] = $album_term;
		}
	}
}

$display_albums = array_merge( $studio_albums, $demo_albums );
?>

<main id="main" class="site-main page-music">

	<section class="disco-header">
		<div class="disco-header__inner">
			<p class="mono-label disco-header__tag"><?php esc_html_e( '2011 — 2026', 'santiago-moraes' ); ?></p>
			<h1 class="disco-header__title"><?php esc_html_e( 'Discografía', 'santiago-moraes' ); ?></h1>
		</div>
	</section>

	<?php if ( ! empty( $display_albums ) ) : ?>
		<div class="disco-grid-wrap">
			<div class="disco-grid-wrap__inner">
				<?php foreach ( $display_albums as $album_term ) :
					$year     = get_term_meta( $album_term->term_id, '_album_year', true );
					$cover_id = get_term_meta( $album_term->term_id, '_album_cover_id', true );
					$is_demo  = (bool) get_term_meta( $album_term->term_id, '_album_is_demo', true );
					$link     = get_term_link( $album_term );

					if ( is_wp_error( $link ) ) {
						continue;
					}

					$meta_parts = array();
					if ( $is_demo ) {
						$meta_parts[] = __( 'Descartes', 'santiago-moraes' );
					}
					if ( $year ) {
						$meta_parts[] = $year;
					}
					$song_count = $album_term->count;
					if ( $song_count ) {
						$meta_parts[] = sprintf( _n( '%d tema', '%d temas', $song_count, 'santiago-moraes' ), $song_count );
					}
					?>

					<a href="<?php echo esc_url( $link ); ?>" class="disco-card">
						<div class="disco-card__cover">
							<?php if ( $cover_id ) : ?>
								<?php echo wp_get_attachment_image(
									(int) $cover_id,
									'medium_large',
									false,
									array(
										'loading' => 'lazy',
										'alt'     => esc_attr( $album_term->name ),
										'class'   => 'disco-card__img',
									)
								); ?>
							<?php else : ?>
								<div class="disco-card__placeholder">
									<span class="mono-label"><?php echo esc_html( strtolower( __( 'tapa', 'santiago-moraes' ) . ' · ' . $album_term->name ) ); ?><br>[soltar imagen aquí]</span>
								</div>
							<?php endif; ?>
						</div>
						<p class="disco-card__name"><?php echo esc_html( $album_term->name ); ?></p>
						<?php if ( ! empty( $meta_parts ) ) : ?>
							<p class="disco-card__meta mono-label"><?php echo esc_html( implode( ' · ', $meta_parts ) ); ?></p>
						<?php endif; ?>
					</a>

				<?php endforeach; ?>
			</div>
		</div>
	<?php else : ?>
		<?php get_template_part( 'template-parts/music/all-songs' ); ?>
	<?php endif; ?>

</main>

<?php
get_footer();
