<?php
/**
 * Custom Gutenberg Blocks — server-side rendered.
 *
 * Registers 8 blocks under the "santiago-moraes" namespace.
 * Each block uses block.json metadata + a PHP render callback.
 * The client-side edit UI is provided by assets/js/blocks-editor.js.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'block_categories_all', 'sm_block_category', 10, 2 );

/**
 * Register a custom block category for the theme.
 *
 * @param array[]                 $categories Array of block categories.
 * @param WP_Block_Editor_Context $context    Current editor context.
 * @return array[]
 */
function sm_block_category( $categories, $context ) {
	return array_merge(
		array(
			array(
				'slug'  => 'santiago-moraes',
				'title' => __( 'Santiago Moraes', 'santiago-moraes' ),
				'icon'  => 'format-audio',
			),
		),
		$categories
	);
}

add_action( 'init', 'sm_register_blocks' );

/**
 * Register all custom blocks from the blocks/ directory.
 *
 * Each block.json references its render.php via the "render" field.
 * The shared editor script handle is injected so the editor knows
 * how to display the blocks in the inserter and provide controls.
 */
function sm_register_blocks() {
	// Register the shared editor script first so the handle exists.
	$asset_file = SM_THEME_DIR . '/assets/js/blocks-editor.js';

	wp_register_script(
		'sm-blocks-editor',
		SM_THEME_URI . '/assets/js/blocks-editor.js',
		array(
			'wp-blocks',
			'wp-element',
			'wp-block-editor',
			'wp-components',
			'wp-server-side-render',
			'wp-data',
		),
		file_exists( $asset_file ) ? filemtime( $asset_file ) : SM_THEME_VERSION,
		true
	);

	$blocks = array(
		'hero-section',
		'section',
		'platform-links',
		'upcoming-shows',
		'latest-songs',
		'contact-form',
		'social-links',
		'album-grid',
	);

	foreach ( $blocks as $block ) {
		$block_dir = SM_THEME_DIR . '/blocks/' . $block;

		if ( file_exists( $block_dir . '/block.json' ) ) {
			register_block_type(
				$block_dir,
				array(
					'editor_script_handles' => array( 'sm-blocks-editor' ),
				)
			);
		}
	}
}
