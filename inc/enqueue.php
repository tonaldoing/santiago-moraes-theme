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
 *
 * Outside production (Local runs nginx without that rule) a plain ?ver= query is used.
 */
function sm_asset_url( $relative_path ) {
	$full_path = SM_THEME_DIR . '/' . $relative_path;
	$ver       = file_exists( $full_path ) ? filemtime( $full_path ) : SM_THEME_VERSION;

	if ( 'production' !== wp_get_environment_type() ) {
		return add_query_arg( 'ver', $ver, SM_THEME_URI . '/' . $relative_path );
	}

	return SM_THEME_URI . '/' . preg_replace( '/\.(css|js)$/', '.' . $ver . '.$1', $relative_path );
}

add_action( 'wp_enqueue_scripts', 'sm_enqueue_assets' );

/**
 * Enqueue front-end styles and scripts.
 */
function sm_enqueue_assets() {
	// Main stylesheet (compiled from SCSS; fonts are self-hosted via _fonts.scss).
	wp_enqueue_style(
		'sm-main',
		sm_asset_url( 'assets/css/style.min.css' ),
		array(),
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

	// Contact form — contact page template only.
	if ( is_page_template( 'templates/template-contact.php' ) ) {
		wp_enqueue_script(
			'sm-contact-form',
			sm_asset_url( 'assets/js/contact-form.min.js' ),
			array(),
			null,
			array( 'strategy' => 'defer' )
		);
		wp_localize_script(
			'sm-contact-form',
			'smContactData',
			array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ) )
		);
	}

	// Analytics events — only when a GA4 Measurement ID is configured.
	if ( sm_get_option( 'sm_ga_id', '' ) ) {
		wp_enqueue_script(
			'sm-analytics',
			sm_asset_url( 'assets/js/analytics.min.js' ),
			array(),
			null,
			array( 'strategy' => 'defer' )
		);
	}

	// Acordes filter — only on the acordes page template.
	if ( is_page_template( 'templates/template-acordes.php' ) ) {
		wp_enqueue_script(
			'sm-acordes-filter',
			sm_asset_url( 'assets/js/acordes-filter.min.js' ),
			array(),
			null,
			array( 'strategy' => 'defer' )
		);
	}

	// Sticky Spotify player — deshabilitado temporalmente.
	// if ( sm_get_option( 'sm_player_enabled', true ) ) {
	// 	wp_enqueue_script(
	// 		'sm-sticky-player',
	// 		SM_THEME_URI . '/assets/js/modules/sticky-player.min.js',
	// 		array(),
	// 		SM_THEME_VERSION,
	// 		array( 'strategy' => 'defer' )
	// 	);
	// }

	// Chord modules — only on single songs.
	if ( is_singular( 'cancion' ) ) {
		wp_enqueue_script(
			'sm-chord-transpose',
			sm_asset_url( 'assets/js/modules/chord-transpose.min.js' ),
			array(),
			null,
			array( 'strategy' => 'defer' )
		);
		wp_enqueue_script(
			'sm-chord-autoscroll',
			sm_asset_url( 'assets/js/modules/chord-autoscroll.min.js' ),
			array(),
			null,
			array( 'strategy' => 'defer' )
		);
		wp_enqueue_script(
			'sm-chord-toggle',
			sm_asset_url( 'assets/js/modules/chord-toggle.min.js' ),
			array(),
			null,
			array( 'strategy' => 'defer' )
		);
		wp_enqueue_script(
			'sm-chord-diagrams',
			sm_asset_url( 'assets/js/modules/chord-diagrams.min.js' ),
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
	if ( file_exists( SM_THEME_DIR . '/assets/css/style.min.css' ) ) {
		wp_enqueue_style(
			'sm-editor',
			sm_asset_url( 'assets/css/style.min.css' ),
			array(),
			null
		);
	}
}

add_action( 'admin_enqueue_scripts', 'sm_enqueue_admin_assets' );

/**
 * Enqueue admin assets — media uploader on the Album taxonomy screen only.
 *
 * @param string $hook_suffix Current admin page hook.
 */
function sm_enqueue_admin_assets( $hook_suffix ) {
	$screen = get_current_screen();

	// edit-tags.php = list + add form; term.php = edit form. Both need the picker.
	if ( ! $screen || ! in_array( $screen->base, array( 'edit-tags', 'term' ), true ) || 'album' !== $screen->taxonomy ) {
		return;
	}

	wp_enqueue_media();

	wp_enqueue_script(
		'sm-taxonomy-media',
		sm_asset_url( 'assets/js/taxonomy-media.min.js' ),
		array( 'jquery' ),
		null,
		true
	);

	wp_localize_script(
		'sm-taxonomy-media',
		'smTaxonomyMedia',
		array(
			'title'  => __( 'Seleccionar imagen de portada', 'santiago-moraes' ),
			'button' => __( 'Usar esta imagen', 'santiago-moraes' ),
			'upload' => __( 'Subir imagen', 'santiago-moraes' ),
			'change' => __( 'Cambiar imagen', 'santiago-moraes' ),
		)
	);
}
