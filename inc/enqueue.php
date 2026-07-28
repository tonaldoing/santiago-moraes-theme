<?php
/**
 * Enqueue styles and scripts.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build a cache-busted asset URL by embedding the file's mtime in the filename.
 *
 * Example: assets/css/style.min.css → assets/css/style.min.1721500000.css
 *
 * A matching RewriteRule in .htaccess strips the version number so the server
 * serves the original file. This avoids LiteSpeed stripping ?ver= query strings.
 */
function sm_asset_url( $relative_path ) {
	$full_path = SM_THEME_DIR . '/' . $relative_path;
	$ver       = file_exists( $full_path ) ? filemtime( $full_path ) : SM_THEME_VERSION;

	$url = SM_THEME_URI . '/' . preg_replace( '/\.(css|js)$/', '.' . $ver . '.$1', $relative_path );

	return $url;
}

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
	$css_deps = array();
	if ( $google_url ) {
		$css_deps[] = 'sm-google-fonts';
	}
	if ( $adobe_url ) {
		$css_deps[] = 'sm-adobe-fonts';
	}

	wp_enqueue_style(
		'sm-main',
		sm_asset_url( 'assets/css/style.min.css' ),
		$css_deps,
		null
	);

	// Navigation script.
	wp_enqueue_script(
		'sm-navigation',
		sm_asset_url( 'assets/js/navigation.min.js' ),
		array(),
		null,
		array( 'strategy' => 'defer' )
	);

	// Main script.
	$main_js = SM_THEME_DIR . '/assets/js/main.js';
	if ( file_exists( $main_js ) ) {
		wp_enqueue_script(
			'sm-main',
			sm_asset_url( 'assets/js/main.js' ),
			array(),
			null,
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

	// Hero video facade — front page only.
	if ( is_front_page() && sm_get_option( 'sm_hero_video_url', '' ) ) {
		wp_enqueue_script(
			'sm-hero-video',
			SM_THEME_URI . '/assets/js/hero-video.js',
			array(),
			SM_THEME_VERSION,
			array( 'strategy' => 'defer' )
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
			sm_asset_url( 'assets/js/modules/chord-transpose.js' ),
			array(),
			null,
			array( 'strategy' => 'defer' )
		);
		wp_enqueue_script(
			'sm-chord-autoscroll',
			sm_asset_url( 'assets/js/modules/chord-autoscroll.js' ),
			array(),
			null,
			array( 'strategy' => 'defer' )
		);
		wp_enqueue_script(
			'sm-chord-toggle',
			sm_asset_url( 'assets/js/modules/chord-toggle.js' ),
			array(),
			null,
			array( 'strategy' => 'defer' )
		);
		wp_enqueue_script(
			'sm-chord-diagrams',
			sm_asset_url( 'assets/js/modules/chord-diagrams.js' ),
			array(),
			null,
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

	if ( file_exists( SM_THEME_DIR . '/assets/css/style.min.css' ) ) {
		wp_enqueue_style(
			'sm-editor',
			sm_asset_url( 'assets/css/style.min.css' ),
			array( 'sm-google-fonts' ),
			null
		);
	}
}
