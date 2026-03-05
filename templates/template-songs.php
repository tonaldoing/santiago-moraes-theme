<?php
/**
 * Template Name: Musica
 * Template Post Type: page
 *
 * Music page — artist discography portfolio.
 * Section A: Featured album hero (Customizer-driven)
 * Section B: Discography grid (non-demo albums)
 * Section C: Demos & outtakes grid
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main page-music">
	<?php
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

	if ( is_wp_error( $all_albums ) || empty( $all_albums ) ) {
		get_template_part( 'template-parts/music/all-songs' );
	} else {
		// Separate albums into studio vs demo.
		$studio_albums = array();
		$demo_albums   = array();

		foreach ( $all_albums as $album_term ) {
			$is_demo = (bool) get_term_meta( $album_term->term_id, '_album_is_demo', true );
			if ( $is_demo ) {
				$demo_albums[] = $album_term;
			} else {
				$studio_albums[] = $album_term;
			}
		}

		// ── SECTION A: Featured Album Hero ──
		$featured_id = (int) get_theme_mod( 'sm_featured_album_id', 0 );

		// Find the featured album term, or fall back to the most recent studio album.
		$featured_term = null;
		if ( $featured_id ) {
			$featured_term = get_term( $featured_id, 'album' );
			if ( is_wp_error( $featured_term ) || ! $featured_term ) {
				$featured_term = null;
			}
		}

		if ( ! $featured_term && ! empty( $studio_albums ) ) {
			$featured_term = $studio_albums[0];
		}

		if ( $featured_term ) {
			set_query_var( 'sm_album_term', $featured_term );
			get_template_part( 'template-parts/music/album-hero' );
		}

		// ── SECTION B: Discography Grid (non-demo albums, excluding featured) ──
		$disco_albums = array();
		foreach ( $studio_albums as $album_term ) {
			if ( $featured_term && $album_term->term_id === $featured_term->term_id ) {
				continue;
			}
			$disco_albums[] = $album_term;
		}

		if ( ! empty( $disco_albums ) ) {
			set_query_var( 'sm_albums', $disco_albums );
			set_query_var( 'sm_section_title', __( 'Discografia', 'santiago-moraes' ) );
			set_query_var( 'sm_is_demo', false );
			get_template_part( 'template-parts/music/discography-grid' );
		}

		// ── SECTION C: Demos & Descartes Grid ──
		if ( ! empty( $demo_albums ) ) {
			set_query_var( 'sm_albums', $demo_albums );
			set_query_var( 'sm_section_title', __( 'Demos y Descartes', 'santiago-moraes' ) );
			set_query_var( 'sm_is_demo', true );
			get_template_part( 'template-parts/music/discography-grid' );
		}
	}
	?>
</main>

<?php
get_footer();
