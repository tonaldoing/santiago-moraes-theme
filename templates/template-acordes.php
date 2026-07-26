<?php
/**
 * Template Name: Acordes y Letras
 * Template Post Type: page
 *
 * Lists only songs that have lyrics/chords, with album filter buttons
 * and a brutalist preview panel (aside).
 *
 * @package Santiago_Moraes
 */

get_header();

// Get all songs with non-empty lyrics.
$songs = new WP_Query(
	array(
		'post_type'      => 'cancion',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_cancion_lyrics',
				'value'   => '',
				'compare' => '!=',
			),
		),
	)
);

// Collect unique album terms for the filter buttons.
$filter_albums = array();

if ( $songs->have_posts() ) {
	foreach ( $songs->posts as $p ) {
		$terms = get_the_terms( $p->ID, 'album' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$filter_albums[ $t->term_id ] = $t;
			}
		}
	}
	uasort( $filter_albums, function ( $a, $b ) {
		$ya = (int) get_term_meta( $a->term_id, '_album_year', true );
		$yb = (int) get_term_meta( $b->term_id, '_album_year', true );
		return $yb - $ya;
	} );
}
?>

<main id="main" class="site-main page-acordes">

	<section class="acordes-header">
		<div class="acordes-header__inner">
			<p class="mono-label acordes-header__tag"><?php esc_html_e( 'Para tocar en casa', 'santiago-moraes' ); ?></p>
			<h1 class="acordes-header__title"><?php esc_html_e( 'Acordes', 'santiago-moraes' ); ?><br><?php esc_html_e( 'y letras', 'santiago-moraes' ); ?></h1>
		</div>
	</section>

	<?php if ( $songs->have_posts() ) : ?>

		<div class="acordes-body">
			<div class="acordes-body__inner">

				<div class="acordes-main">

					<?php if ( ! empty( $filter_albums ) ) : ?>
						<div class="acordes-filters">
							<button class="acordes-filter acordes-filter--active" data-album="all" type="button">
								<?php esc_html_e( 'Todas', 'santiago-moraes' ); ?>
							</button>
							<?php foreach ( $filter_albums as $fa ) : ?>
								<button class="acordes-filter" data-album="<?php echo esc_attr( $fa->slug ); ?>" type="button">
									<?php echo esc_html( $fa->name ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<div class="acordes-list">
						<?php
						$is_first = true;
						while ( $songs->have_posts() ) :
							$songs->the_post();

							$original_key = get_post_meta( get_the_ID(), '_cancion_original_key', true );
							$capo         = get_post_meta( get_the_ID(), '_cancion_capo', true );
							$lyrics_raw   = get_post_meta( get_the_ID(), '_cancion_lyrics', true );
							$album_terms  = get_the_terms( get_the_ID(), 'album' );
							$album_slugs  = array();
							$album_name   = '';

							if ( $album_terms && ! is_wp_error( $album_terms ) ) {
								foreach ( $album_terms as $at ) {
									$album_slugs[] = $at->slug;
								}
								$album_name = $album_terms[0]->name;
							}

							// Preview snippet: first 8 lines of raw lyrics.
							$preview_snippet = '';
							if ( $lyrics_raw ) {
								$lines           = explode( "\n", $lyrics_raw );
								$preview_snippet = implode( "\n", array_slice( $lines, 0, 8 ) );
							}

							$meta_parts = array();
							if ( $original_key ) {
								$meta_parts[] = sprintf( __( 'Tono original: %s', 'santiago-moraes' ), $original_key );
							}
							if ( $capo && (int) $capo > 0 ) {
								$meta_parts[] = 'Capo ' . (int) $capo;
							}
							?>

							<a href="<?php the_permalink(); ?>"
							   class="acordes-row<?php echo $is_first ? ' acordes-row--active' : ''; ?>"
							   data-albums="<?php echo esc_attr( implode( ' ', $album_slugs ) ); ?>"
							   data-title="<?php echo esc_attr( get_the_title() ); ?>"
							   data-meta="<?php echo esc_attr( implode( ' · ', $meta_parts ) ); ?>"
							   data-preview="<?php echo esc_attr( $preview_snippet ); ?>"
							   data-url="<?php the_permalink(); ?>">
								<span class="acordes-row__title"><?php the_title(); ?></span>
								<?php if ( $album_name ) : ?>
									<span class="acordes-row__album"><?php echo esc_html( $album_name ); ?></span>
								<?php endif; ?>
								<?php if ( $original_key ) : ?>
									<span class="acordes-row__key"><?php echo esc_html( $original_key ); ?></span>
								<?php endif; ?>
								<span class="acordes-row__arrow">&rarr;</span>
							</a>

						<?php
						$is_first = false;
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>

				<aside class="acordes-preview" id="acordes-preview">
					<p class="acordes-preview__label"><?php esc_html_e( 'Vista previa', 'santiago-moraes' ); ?></p>
					<p class="acordes-preview__title" id="preview-title">&mdash;</p>
					<p class="acordes-preview__meta" id="preview-meta"></p>
					<pre class="acordes-preview__lyrics" id="preview-lyrics"></pre>
					<div class="acordes-preview__actions">
						<button type="button" class="acordes-preview__btn" id="preview-down">&minus; Tono</button>
						<button type="button" class="acordes-preview__btn" id="preview-up">+ Tono</button>
						<a href="#" class="acordes-preview__cta" id="preview-link"><?php esc_html_e( 'Ver completa', 'santiago-moraes' ); ?></a>
					</div>
				</aside>

			</div>
		</div>

	<?php else : ?>

		<section class="acordes-body">
			<div class="acordes-body__inner">
				<p class="acordes-list__empty"><?php esc_html_e( 'No hay canciones con acordes disponibles todavia.', 'santiago-moraes' ); ?></p>
			</div>
		</section>

	<?php endif; ?>

</main>

<?php
get_footer();
