<?php
/**
 * Chord Viewer — Main chord/lyrics display with controls.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$lyrics       = get_post_meta( get_the_ID(), '_cancion_lyrics', true );
$original_key = get_post_meta( get_the_ID(), '_cancion_original_key', true );

if ( empty( $lyrics ) ) {
	return;
}

echo sm_render_chord_viewer( $lyrics, $original_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped within sm_render_chord_viewer.
