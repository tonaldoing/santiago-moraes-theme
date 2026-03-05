<?php
/**
 * Performance optimizations.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Remove unnecessary wp_head items.
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/**
 * Remove emoji DNS prefetch.
 */
add_filter( 'emoji_svg_url', '__return_false' );

/**
 * Remove global styles inline CSS if not using block editor features.
 */
add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'classic-theme-styles' );
}, 100 );

/**
 * Disable XML-RPC.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove query strings from static resources.
 */
add_filter( 'script_loader_src', 'sm_remove_query_strings', 15 );
add_filter( 'style_loader_src', 'sm_remove_query_strings', 15 );

/**
 * Strip ver= query string from enqueued assets.
 *
 * @param string $src Asset URL.
 * @return string
 */
function sm_remove_query_strings( $src ) {
	if ( strpos( $src, 'ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}

/**
 * Add resource hints for preconnect to external domains.
 */
add_filter( 'wp_resource_hints', 'sm_resource_hints', 10, 2 );

/**
 * Preconnect to Google Fonts, Google Analytics, Spotify, YouTube.
 *
 * @param array  $hints Existing hints.
 * @param string $relation_type Hint type.
 * @return array
 */
function sm_resource_hints( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		// Google Fonts (if loaded).
		$hints[] = array(
			'href'        => 'https://fonts.googleapis.com',
			'crossorigin' => '',
		);
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);

		// Google Analytics.
		$ga_id = get_theme_mod( 'sm_ga_id', '' );
		if ( $ga_id ) {
			$hints[] = array(
				'href'        => 'https://www.googletagmanager.com',
				'crossorigin' => '',
			);
		}
	}

	return $hints;
}

/**
 * Limit post revisions for performance.
 */
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
	define( 'WP_POST_REVISIONS', 5 );
}
