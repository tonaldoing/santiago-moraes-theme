<?php
/**
 * Enqueue styles and scripts.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'sm_enqueue_assets' );

/**
 * Enqueue front-end styles and scripts.
 */
function sm_enqueue_assets() {
	// Google Fonts — dynamic based on Theme Options.
	$google_url = sm_google_fonts_url();
	if ( $google_url ) {
		wp_enqueue_style( 'sm-google-fonts', $google_url, array(), null );
	}

	// Adobe Fonts (Typekit) — only when an Adobe font is selected.
	$adobe_url = sm_needs_adobe_fonts();
	if ( $adobe_url ) {
		wp_enqueue_style( 'sm-adobe-fonts', $adobe_url, array(), null );
	}

	// Main stylesheet (compiled from SCSS).
	$css_file = SM_THEME_DIR . '/assets/css/style.css';
	$css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : SM_THEME_VERSION;
	$css_deps = array();
	if ( $google_url ) {
		$css_deps[] = 'sm-google-fonts';
	}
	if ( $adobe_url ) {
		$css_deps[] = 'sm-adobe-fonts';
	}

	wp_enqueue_style(
		'sm-main',
		SM_THEME_URI . '/assets/css/style.css',
		$css_deps,
		$css_ver
	);

	// Navigation script.
	wp_enqueue_script(
		'sm-navigation',
		SM_THEME_URI . '/assets/js/navigation.js',
		array(),
		SM_THEME_VERSION,
		array( 'strategy' => 'defer' )
	);

	// Main script.
	$main_js = SM_THEME_DIR . '/assets/js/main.js';
	if ( file_exists( $main_js ) ) {
		wp_enqueue_script(
			'sm-main',
			SM_THEME_URI . '/assets/js/main.js',
			array(),
			filemtime( $main_js ),
			array( 'strategy' => 'defer' )
		);
	}

	// Contact form — on contact page template, front page, or pages with the contact-form block.
	if ( is_page_template( 'templates/template-contact.php' ) || is_front_page() || ( is_singular() && has_block( 'sm/contact-form' ) ) ) {
		wp_enqueue_script(
			'sm-contact-form',
			SM_THEME_URI . '/assets/js/contact-form.js',
			array(),
			SM_THEME_VERSION,
			array( 'strategy' => 'defer' )
		);
		wp_localize_script(
			'sm-contact-form',
			'smContactData',
			array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) )
		);
	}

	// Home contact form — front page only (compact inline form).
	if ( is_front_page() ) {
		wp_enqueue_script(
			'sm-home-contact',
			SM_THEME_URI . '/assets/js/home-contact.js',
			array(),
			SM_THEME_VERSION,
			array( 'strategy' => 'defer' )
		);
	}

	// Events toggle — only on events archive.
	if ( is_post_type_archive( 'evento' ) ) {
		wp_enqueue_script(
			'sm-events-toggle',
			SM_THEME_URI . '/assets/js/events-toggle.js',
			array(),
			SM_THEME_VERSION,
			array( 'strategy' => 'defer' )
		);
	}

	// Acordes filter — only on the acordes page template.
	if ( is_page_template( 'templates/template-acordes.php' ) ) {
		wp_enqueue_script(
			'sm-acordes-filter',
			SM_THEME_URI . '/assets/js/acordes-filter.js',
			array(),
			SM_THEME_VERSION,
			array( 'strategy' => 'defer' )
		);
	}

	// Sticky Spotify player — deshabilitado temporalmente.
	// if ( sm_get_option( 'sm_player_enabled', true ) ) {
	// 	wp_enqueue_script(
	// 		'sm-sticky-player',
	// 		SM_THEME_URI . '/assets/js/modules/sticky-player.js',
	// 		array(),
	// 		SM_THEME_VERSION,
	// 		array( 'strategy' => 'defer' )
	// 	);
	// }

	// Chord modules — only on single songs.
	if ( is_singular( 'cancion' ) ) {
		wp_enqueue_script(
			'sm-chord-transpose',
			SM_THEME_URI . '/assets/js/modules/chord-transpose.js',
			array(),
			SM_THEME_VERSION,
			array( 'strategy' => 'defer' )
		);
		wp_enqueue_script(
			'sm-chord-autoscroll',
			SM_THEME_URI . '/assets/js/modules/chord-autoscroll.js',
			array(),
			SM_THEME_VERSION,
			array( 'strategy' => 'defer' )
		);
		wp_enqueue_script(
			'sm-chord-toggle',
			SM_THEME_URI . '/assets/js/modules/chord-toggle.js',
			array(),
			SM_THEME_VERSION,
			array( 'strategy' => 'defer' )
		);
	}
}

add_action( 'enqueue_block_editor_assets', 'sm_enqueue_editor_assets' );

/**
 * Enqueue editor-specific styles.
 */
function sm_enqueue_editor_assets() {
	// Google Fonts for the editor too.
	$google_url = sm_google_fonts_url();
	if ( $google_url ) {
		wp_enqueue_style( 'sm-google-fonts', $google_url, array(), null );
	}

	// Adobe Fonts for the editor.
	$adobe_url = sm_needs_adobe_fonts();
	if ( $adobe_url ) {
		wp_enqueue_style( 'sm-adobe-fonts', $adobe_url, array(), null );
	}

	$css_file = SM_THEME_DIR . '/assets/css/style.css';

	if ( file_exists( $css_file ) ) {
		wp_enqueue_style(
			'sm-editor',
			SM_THEME_URI . '/assets/css/style.css',
			array( 'sm-google-fonts' ),
			filemtime( $css_file )
		);
	}
}
