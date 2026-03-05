<?php
/**
 * Theme setup.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'sm_theme_setup' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function sm_theme_setup() {
	// Make theme available for translation.
	load_theme_textdomain( 'santiago-moraes', SM_THEME_DIR . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary' => __( 'Menu Principal', 'santiago-moraes' ),
			'footer'  => __( 'Menu Footer', 'santiago-moraes' ),
			'mobile'  => __( 'Menu Movil', 'santiago-moraes' ),
		)
	);

	// Switch default core markup to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add support for custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 90,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Add support for align-wide blocks.
	add_theme_support( 'align-wide' );

	// Load front-end styles in the editor.
	add_editor_style( 'assets/css/style.css' );

	// Image sizes.
	add_image_size( 'sm-hero', 1000, 741, true );
	add_image_size( 'sm-album-cover', 600, 600, true );
	add_image_size( 'sm-event-poster', 400, 400, true );
	add_image_size( 'sm-card', 400, 300, true );
}

/**
 * Custom walker is not needed for the simple flat menu.
 * WordPress default walker works fine for the 3-item nav.
 */
