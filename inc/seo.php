<?php
/**
 * SEO — meta description, canonical, Open Graph, Twitter Cards.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'sm_seo_meta_tags', 2 );

/**
 * Output SEO meta tags in <head>.
 */
function sm_seo_meta_tags() {
	// Skip if a popular SEO plugin is active.
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'FLAVOR_SEO_VERSION' ) ) {
		return;
	}

	$site_name   = get_bloginfo( 'name' );
	$description = sm_get_meta_description();
	$canonical   = sm_get_canonical_url();
	$og_image    = sm_get_og_image();
	$og_type     = sm_get_og_type();
	$title       = wp_get_document_title();

	// Meta description.
	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}

	// Canonical.
	if ( $canonical ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
	}

	// Robots.
	if ( is_search() || is_404() ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	}

	// Open Graph.
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
	echo '<meta property="og:locale" content="es_ES">' . "\n";

	if ( $canonical ) {
		echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";
	}
	if ( $description ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $og_image ) {
		echo '<meta property="og:image" content="' . esc_url( $og_image ) . '">' . "\n";
	}

	// Twitter Card.
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $description ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	}
	if ( $og_image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $og_image ) . '">' . "\n";
	}
}

/**
 * Build a meta description based on context.
 *
 * @return string
 */
function sm_get_meta_description() {
	if ( is_front_page() ) {
		$tagline = get_bloginfo( 'description' );
		return $tagline ? $tagline : get_bloginfo( 'name' ) . ' — Musica, Shows y Acordes';
	}

	if ( is_singular() ) {
		$post = get_queried_object();
		if ( has_excerpt( $post ) ) {
			return wp_strip_all_tags( get_the_excerpt( $post ) );
		}
		// Auto-generate from content.
		$content = wp_strip_all_tags( $post->post_content );
		if ( $content ) {
			return wp_trim_words( $content, 25, '...' );
		}
		return '';
	}

	if ( is_tax( 'album' ) ) {
		$term = get_queried_object();
		$desc = get_term_meta( $term->term_id, '_album_description', true );
		if ( $desc ) {
			return wp_strip_all_tags( $desc );
		}
		return sprintf( '%s — Album de Santiago Moraes', $term->name );
	}

	if ( is_post_type_archive( 'evento' ) ) {
		return 'Proximos shows y eventos de Santiago Moraes';
	}

	if ( is_post_type_archive( 'cancion' ) ) {
		return 'Discografia completa de Santiago Moraes — acordes, letras y musica';
	}

	if ( is_search() ) {
		return sprintf( 'Resultados de busqueda para "%s"', get_search_query() );
	}

	if ( is_archive() ) {
		$title = get_the_archive_title();
		return wp_strip_all_tags( $title ) . ' — Santiago Moraes';
	}

	return '';
}

/**
 * Get canonical URL for the current page.
 *
 * @return string
 */
function sm_get_canonical_url() {
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		return get_permalink();
	}
	if ( is_tax() || is_category() || is_tag() ) {
		return get_term_link( get_queried_object() );
	}
	if ( is_post_type_archive() ) {
		return get_post_type_archive_link( get_queried_object()->name );
	}
	return '';
}

/**
 * Determine the Open Graph type.
 *
 * @return string
 */
function sm_get_og_type() {
	if ( is_front_page() ) {
		return 'website';
	}
	if ( is_singular( 'cancion' ) ) {
		return 'music.song';
	}
	if ( is_tax( 'album' ) ) {
		return 'music.album';
	}
	if ( is_singular( 'evento' ) ) {
		return 'event';
	}
	if ( is_singular() ) {
		return 'article';
	}
	return 'website';
}

/**
 * Get an appropriate OG image URL.
 *
 * @return string
 */
function sm_get_og_image() {
	// Singular: featured image.
	if ( is_singular() && has_post_thumbnail() ) {
		$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		return $img ? $img[0] : '';
	}

	// Album taxonomy: cover image.
	if ( is_tax( 'album' ) ) {
		$term     = get_queried_object();
		$cover_id = get_term_meta( $term->term_id, '_album_cover_id', true );
		if ( $cover_id ) {
			$img = wp_get_attachment_image_src( $cover_id, 'large' );
			return $img ? $img[0] : '';
		}
	}

	// Song single: try album cover.
	if ( is_singular( 'cancion' ) ) {
		$albums = wp_get_post_terms( get_the_ID(), 'album', array( 'fields' => 'ids' ) );
		if ( ! is_wp_error( $albums ) && ! empty( $albums ) ) {
			$cover_id = get_term_meta( $albums[0], '_album_cover_id', true );
			if ( $cover_id ) {
				$img = wp_get_attachment_image_src( $cover_id, 'large' );
				return $img ? $img[0] : '';
			}
		}
	}

	// Fallback: hero image from Theme Options.
	$hero = sm_get_option( 'sm_hero_image', '' );
	if ( $hero ) {
		return $hero;
	}

	// Final fallback: placeholder.
	return SM_THEME_URI . '/assets/images/hero-placeholder.webp';
}
