<?php
/**
 * Santiago Moraes - Functions and definitions.
 *
 * @package Santiago_Moraes
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme constants.
 */
define( 'SM_THEME_VERSION', '1.0.0' );
define( 'SM_THEME_DIR', get_template_directory() );
define( 'SM_THEME_URI', get_template_directory_uri() );

/**
 * Custom Nav Walker (must load before header template).
 */
require SM_THEME_DIR . '/inc/walker.php';

/**
 * Core theme setup.
 */
require SM_THEME_DIR . '/inc/setup.php';

/**
 * Enqueue styles and scripts.
 */
require SM_THEME_DIR . '/inc/enqueue.php';

/**
 * Custom Post Types.
 */
require SM_THEME_DIR . '/inc/custom-post-types.php';

/**
 * Custom Taxonomies.
 */
require SM_THEME_DIR . '/inc/taxonomies.php';

/**
 * Customizer options.
 */
if ( file_exists( SM_THEME_DIR . '/inc/customizer.php' ) ) {
	require SM_THEME_DIR . '/inc/customizer.php';
}

/**
 * Helper functions.
 */
if ( file_exists( SM_THEME_DIR . '/inc/helpers.php' ) ) {
	require SM_THEME_DIR . '/inc/helpers.php';
}

/**
 * Contact form processing.
 */
if ( file_exists( SM_THEME_DIR . '/inc/contact-form.php' ) ) {
	require SM_THEME_DIR . '/inc/contact-form.php';
}

/**
 * Chord/lyrics parser engine.
 */
if ( file_exists( SM_THEME_DIR . '/inc/chord-parser.php' ) ) {
	require SM_THEME_DIR . '/inc/chord-parser.php';
}

/**
 * SEO meta tags (Open Graph, Twitter Cards, meta description).
 */
require SM_THEME_DIR . '/inc/seo.php';

/**
 * Schema.org JSON-LD structured data.
 */
require SM_THEME_DIR . '/inc/schema.php';

/**
 * Performance optimizations.
 */
require SM_THEME_DIR . '/inc/performance.php';

/**
 * Security hardening.
 */
require SM_THEME_DIR . '/inc/security.php';

/**
 * Favicon & Web App Manifest.
 */
require SM_THEME_DIR . '/inc/favicon.php';

/**
 * Custom Gutenberg Blocks.
 */
require SM_THEME_DIR . '/inc/blocks.php';

/**
 * Block Patterns.
 */
require SM_THEME_DIR . '/inc/patterns.php';
