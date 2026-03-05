<?php
/**
 * Favicon & Web App Manifest support.
 *
 * WordPress handles favicon via Site Identity in the Customizer (wp_site_icon).
 * This file adds the web app manifest and theme-color meta tag.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'sm_theme_color_meta', 4 );

/**
 * Output theme-color meta tag for mobile browsers.
 */
function sm_theme_color_meta() {
	$color = get_theme_mod( 'sm_color_black', '#010101' );
	echo '<meta name="theme-color" content="' . esc_attr( $color ) . '">' . "\n";
	echo '<meta name="msapplication-TileColor" content="' . esc_attr( $color ) . '">' . "\n";
	echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
	echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n";
}

/**
 * Register the web manifest route.
 */
add_action( 'init', 'sm_register_manifest_route' );

/**
 * Add a rewrite rule for site.webmanifest.
 */
function sm_register_manifest_route() {
	add_rewrite_rule( '^site\.webmanifest$', 'index.php?sm_manifest=1', 'top' );
}

add_filter( 'query_vars', 'sm_manifest_query_vars' );

/**
 * Register query var.
 *
 * @param array $vars Query vars.
 * @return array
 */
function sm_manifest_query_vars( $vars ) {
	$vars[] = 'sm_manifest';
	return $vars;
}

add_action( 'template_redirect', 'sm_serve_manifest' );

/**
 * Serve the web manifest JSON.
 */
function sm_serve_manifest() {
	if ( ! get_query_var( 'sm_manifest' ) ) {
		return;
	}

	$site_name = get_bloginfo( 'name' );
	$color     = get_theme_mod( 'sm_color_black', '#010101' );

	$manifest = array(
		'name'             => $site_name,
		'short_name'       => $site_name,
		'start_url'        => '/',
		'display'          => 'standalone',
		'background_color' => $color,
		'theme_color'      => $color,
		'icons'            => array(),
	);

	// Use WP site icon if set.
	$site_icon_id = get_option( 'site_icon' );
	if ( $site_icon_id ) {
		$sizes = array( 192, 512 );
		foreach ( $sizes as $size ) {
			$url = get_site_icon_url( $size );
			if ( $url ) {
				$manifest['icons'][] = array(
					'src'   => $url,
					'sizes' => $size . 'x' . $size,
					'type'  => 'image/png',
				);
			}
		}
	}

	header( 'Content-Type: application/manifest+json' );
	echo wp_json_encode( $manifest, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
	exit;
}

/**
 * Add manifest link to head.
 */
add_action( 'wp_head', 'sm_manifest_link', 4 );

/**
 * Output manifest link tag.
 */
function sm_manifest_link() {
	echo '<link rel="manifest" href="' . esc_url( home_url( '/site.webmanifest' ) ) . '">' . "\n";
}
