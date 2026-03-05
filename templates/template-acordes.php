<?php
/**
 * Template Name: Acordes y Letras
 * Template Post Type: page
 *
 * Lists only songs that have lyrics/chords, with album filter buttons.
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

// Collect unique album terms from the results for the filter buttons.
$filter_albums = array();
if ( $songs->have_posts() ) {
	$temp_posts = $songs->posts;
	foreach ( $temp_posts as $p ) {
		$terms = get_the_terms( $p->ID, 'album' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$filter_albums[ $t->term_id ] = $t;
			}
		}
	}
	// Sort albums by year DESC.
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
			<h1 class="acordes-header__title"><?php esc_html_e( 'Acordes y Letras', 'santiago-moraes' ); ?></h1>
			<p class="acordes-header__subtitle"><?php esc_html_e( 'Todas las canciones con acordes para guitarra', 'santiago-moraes' ); ?></p>
		</div>
	</section>

	<?php if ( $songs->have_posts() ) : ?>

		<?php if ( ! empty( $filter_albums ) ) : ?>
			<div class="acordes-filters">
				<div class="acordes-filters__inner">
					<button class="acordes-filter acordes-filter--active" data-album="all" type="button">
						<?php esc_html_e( 'Todas', 'santiago-moraes' ); ?>
					</button>
					<?php foreach ( $filter_albums as $fa ) : ?>
						<button class="acordes-filter" data-album="<?php echo esc_attr( $fa->slug ); ?>" type="button">
							<?php echo esc_html( $fa->name ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<section class="acordes-list">
			<div class="acordes-list__inner">
				<?php
				while ( $songs->have_posts() ) :
					$songs->the_post();

					$original_key = get_post_meta( get_the_ID(), '_cancion_original_key', true );
					$album_terms  = get_the_terms( get_the_ID(), 'album' );
					$album_slugs  = array();
					$album_name   = '';

					if ( $album_terms && ! is_wp_error( $album_terms ) ) {
						foreach ( $album_terms as $at ) {
							$album_slugs[] = $at->slug;
						}
						$album_name = $album_terms[0]->name;
					}
					?>

					<a href="<?php the_permalink(); ?>" class="acordes-row" data-albums="<?php echo esc_attr( implode( ' ', $album_slugs ) ); ?>">
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
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</section>

	<?php else : ?>

		<section class="acordes-list">
			<div class="acordes-list__inner">
				<p class="acordes-list__empty"><?php esc_html_e( 'No hay canciones con acordes disponibles todavia.', 'santiago-moraes' ); ?></p>
			</div>
		</section>

	<?php endif; ?>

</main>

<?php
get_footer();
