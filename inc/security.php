<?php
/**
 * Security hardening.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add security headers via send_headers action.
 */
add_action( 'send_headers', 'sm_security_headers' );

/**
 * Output security headers.
 */
function sm_security_headers() {
	// Prevent clickjacking.
	header( 'X-Frame-Options: SAMEORIGIN' );

	// Prevent MIME-type sniffing.
	header( 'X-Content-Type-Options: nosniff' );

	// XSS Protection (legacy browsers).
	header( 'X-XSS-Protection: 1; mode=block' );

	// Referrer policy.
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );

	// Permissions policy — disable unused APIs.
	header( 'Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()' );
}

/**
 * Remove the WordPress version from RSS feeds.
 */
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Disable author archive enumeration (prevent user discovery via /?author=1).
 */
add_action( 'template_redirect', 'sm_disable_author_archives' );

/**
 * Redirect author archive pages to homepage.
 */
function sm_disable_author_archives() {
	if ( is_author() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}

/**
 * Disable REST API user endpoints for non-authenticated users.
 */
add_filter( 'rest_endpoints', 'sm_restrict_user_endpoints' );

/**
 * Remove /wp/v2/users endpoint for non-logged-in users.
 *
 * @param array $endpoints REST API endpoints.
 * @return array
 */
function sm_restrict_user_endpoints( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		unset( $endpoints['/wp/v2/users'] );
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
}
