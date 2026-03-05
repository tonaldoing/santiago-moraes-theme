<?php
/**
 * Chord/Lyrics Parser Engine.
 *
 * Parses raw lyrics text with inline chord notation [Am], [G7], etc.
 * and outputs semantic HTML with BEM classes for the chord viewer.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Chord pattern — matches standard chord names inside brackets.
 *
 * Matches: A-G, optional #/b, optional modifiers (m, min, maj, dim, aug, sus, add, etc.),
 * optional numbers and slashes for bass notes.
 */
define( 'SM_CHORD_PATTERN', '/^\[([A-G][#b]?(?:m|min|maj|dim|aug|sus|add|dom|M|°|ø)?(?:\d+)?(?:\/[A-G][#b]?)?)\]$/' );

/**
 * Section header keywords (case-insensitive, with optional numbers/spaces).
 */
define( 'SM_SECTION_KEYWORDS', array(
	'intro',
	'verso',
	'pre-coro',
	'pre coro',
	'precoro',
	'coro',
	'puente',
	'solo',
	'outro',
	'interludio',
	'final',
	'estribillo',
) );

/**
 * Check if a bracketed token is a chord (vs. a section header).
 *
 * @param string $text Text inside brackets (without the brackets).
 * @return bool True if it matches a chord pattern.
 */
function sm_is_chord( $text ) {
	return (bool) preg_match( '/^[A-G][#b]?(?:m|min|maj|dim|aug|sus|add|dom|M|°|ø)?(?:\d+)?(?:\/[A-G][#b]?)?$/', $text );
}

/**
 * Check if a line is a section header.
 *
 * A section header is a line that contains ONLY a single bracketed token
 * that is NOT a chord. e.g., [Intro], [Verso 1], [Coro], [Pre-Coro]
 *
 * @param string $line The raw line of text.
 * @return string|false The section name if it's a header, false otherwise.
 */
function sm_detect_section_header( $line ) {
	$trimmed = trim( $line );

	// Must match: [SomeText] and nothing else on the line.
	if ( ! preg_match( '/^\[([^\]]+)\]$/', $trimmed, $matches ) ) {
		return false;
	}

	$inner = trim( $matches[1] );

	// If it matches a chord pattern, it's NOT a section header.
	if ( sm_is_chord( $inner ) ) {
		return false;
	}

	// Verify it matches a known section keyword (with optional number suffix).
	$lower = mb_strtolower( $inner );
	foreach ( SM_SECTION_KEYWORDS as $keyword ) {
		if ( $lower === $keyword || preg_match( '/^' . preg_quote( $keyword, '/' ) . '\s*\d*$/i', $lower ) ) {
			return $inner;
		}
	}

	// Accept any non-chord bracketed text as a section header (flexible).
	return $inner;
}

/**
 * Normalize a section name into a CSS-safe modifier.
 *
 * e.g., "Verso 1" => "verso", "Pre-Coro" => "pre-coro"
 *
 * @param string $section_name Raw section name.
 * @return string CSS modifier string.
 */
function sm_section_modifier( $section_name ) {
	$modifier = mb_strtolower( $section_name );
	$modifier = preg_replace( '/\s*\d+$/', '', $modifier ); // Remove trailing number.
	$modifier = preg_replace( '/\s+/', '-', $modifier );     // Spaces to hyphens.
	$modifier = preg_replace( '/[^a-z0-9\-]/', '', $modifier ); // Strip non-alpha.
	return $modifier;
}

/**
 * Check if a line contains only chords (and whitespace).
 *
 * e.g., "[Am] [G] [F] [E]" => true
 *       "[Am]Hoy te vi" => false
 *
 * @param string $line Raw line.
 * @return bool True if the line is chords-only.
 */
function sm_is_chords_only_line( $line ) {
	// Remove all chord tokens and whitespace. If nothing remains, it's chords-only.
	$stripped = preg_replace( '/\[[A-G][#b]?(?:m|min|maj|dim|aug|sus|add|dom|M|°|ø)?(?:\d+)?(?:\/[A-G][#b]?)?\]/', '', $line );
	return '' === trim( $stripped );
}

/**
 * Extract all chord tokens from a chords-only line.
 *
 * @param string $line A chords-only line.
 * @return array Array of chord strings.
 */
function sm_extract_chords( $line ) {
	preg_match_all( '/\[([A-G][#b]?(?:m|min|maj|dim|aug|sus|add|dom|M|°|ø)?(?:\d+)?(?:\/[A-G][#b]?)?)\]/', $line, $matches );
	return $matches[1];
}

/**
 * Parse a lyric line with inline chords into chord-pair spans.
 *
 * Input:  "[Am]Hoy te vi [G]pasar por la [F]calle"
 * Output: array of ['chord' => 'Am', 'lyric' => 'Hoy te vi '], ...
 *
 * @param string $line Raw lyric line with inline chords.
 * @return array Array of associative arrays with 'chord' and 'lyric' keys.
 */
function sm_parse_lyric_line( $line ) {
	$pairs  = array();
	$parts  = preg_split( '/(\[[A-G][#b]?(?:m|min|maj|dim|aug|sus|add|dom|M|°|ø)?(?:\d+)?(?:\/[A-G][#b]?)?\])/', $line, -1, PREG_SPLIT_DELIM_CAPTURE );

	$current_chord = '';

	foreach ( $parts as $part ) {
		if ( '' === $part ) {
			continue;
		}

		// Is this a chord token?
		if ( preg_match( '/^\[([A-G][#b]?(?:m|min|maj|dim|aug|sus|add|dom|M|°|ø)?(?:\d+)?(?:\/[A-G][#b]?)?)\]$/', $part, $m ) ) {
			// If we had a previous chord with no lyric, push it.
			if ( '' !== $current_chord ) {
				$pairs[] = array(
					'chord' => $current_chord,
					'lyric' => '',
				);
			}
			$current_chord = $m[1];
		} else {
			// This is lyric text.
			$pairs[] = array(
				'chord' => $current_chord,
				'lyric' => $part,
			);
			$current_chord = '';
		}
	}

	// Flush remaining chord with no lyrics.
	if ( '' !== $current_chord ) {
		$pairs[] = array(
			'chord' => $current_chord,
			'lyric' => '',
		);
	}

	return $pairs;
}

/**
 * Parse raw lyrics text and return structured data.
 *
 * @param string $raw_lyrics The raw lyrics with [chord] notation.
 * @return array Array of sections, each containing lines.
 */
function sm_parse_lyrics( $raw_lyrics ) {
	if ( empty( $raw_lyrics ) ) {
		return array();
	}

	$raw_lyrics = str_replace( "\r\n", "\n", $raw_lyrics );
	$raw_lyrics = str_replace( "\r", "\n", $raw_lyrics );
	$lines      = explode( "\n", $raw_lyrics );

	$sections        = array();
	$current_section = array(
		'title'    => '',
		'modifier' => '',
		'lines'    => array(),
	);

	foreach ( $lines as $line ) {
		// Check for section header.
		$section_name = sm_detect_section_header( $line );

		if ( false !== $section_name ) {
			// Save previous section if it has content.
			if ( ! empty( $current_section['lines'] ) || '' !== $current_section['title'] ) {
				$sections[] = $current_section;
			}

			$current_section = array(
				'title'    => $section_name,
				'modifier' => sm_section_modifier( $section_name ),
				'lines'    => array(),
			);
			continue;
		}

		// Empty line.
		if ( '' === trim( $line ) ) {
			$current_section['lines'][] = array(
				'type' => 'empty',
			);
			continue;
		}

		// Chords-only line.
		if ( sm_is_chords_only_line( $line ) ) {
			$current_section['lines'][] = array(
				'type'   => 'chords_only',
				'chords' => sm_extract_chords( $line ),
			);
			continue;
		}

		// Lyric line (may have inline chords or just text).
		$has_chords = (bool) preg_match( '/\[[A-G]/', $line );

		if ( $has_chords ) {
			$current_section['lines'][] = array(
				'type'  => 'lyric_with_chords',
				'pairs' => sm_parse_lyric_line( $line ),
			);
		} else {
			$current_section['lines'][] = array(
				'type' => 'lyric_only',
				'text' => $line,
			);
		}
	}

	// Don't forget the last section.
	if ( ! empty( $current_section['lines'] ) || '' !== $current_section['title'] ) {
		$sections[] = $current_section;
	}

	return $sections;
}

/**
 * Render parsed lyrics as semantic HTML.
 *
 * @param string $raw_lyrics  Raw lyrics text with chord notation.
 * @param string $original_key The original key of the song (e.g., "Am").
 * @return string HTML output for the chord viewer.
 */
function sm_render_chord_viewer( $raw_lyrics, $original_key = '' ) {
	$sections = sm_parse_lyrics( $raw_lyrics );

	if ( empty( $sections ) ) {
		return '';
	}

	ob_start();
	?>
	<div class="chord-viewer" data-original-key="<?php echo esc_attr( $original_key ); ?>">

		<div class="chord-viewer__controls">
			<div class="chord-viewer__controls-group chord-viewer__controls-group--transpose">
				<button type="button" class="chord-control chord-control--transpose-down" aria-label="<?php esc_attr_e( 'Bajar medio tono', 'santiago-moraes' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512" fill="currentColor"><path d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"/></svg>
				</button>
				<span class="chord-control__key-display">
					<span class="chord-control__key-current"><?php echo esc_html( $original_key ); ?></span>
				</span>
				<button type="button" class="chord-control chord-control--transpose-up" aria-label="<?php esc_attr_e( 'Subir medio tono', 'santiago-moraes' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512" fill="currentColor"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
				</button>
			</div>

			<div class="chord-viewer__controls-group chord-viewer__controls-group--scroll">
				<button type="button" class="chord-control chord-control--autoscroll" aria-label="<?php esc_attr_e( 'Auto-scroll', 'santiago-moraes' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 384 512" fill="currentColor"><path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80L0 432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.8 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/></svg>
					<span class="chord-control__label"><?php esc_html_e( 'Auto-scroll', 'santiago-moraes' ); ?></span>
				</button>
				<input type="range" class="chord-control__speed" min="1" max="10" value="3" aria-label="<?php esc_attr_e( 'Velocidad de scroll', 'santiago-moraes' ); ?>">
			</div>

			<div class="chord-viewer__controls-group chord-viewer__controls-group--actions">
				<button type="button" class="chord-control chord-control--toggle" aria-label="<?php esc_attr_e( 'Mostrar/ocultar acordes', 'santiago-moraes' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 576 512" fill="currentColor"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64a64 64 0 1 0 0 128 64 64 0 1 0 0-128z"/></svg>
					<span class="chord-control__label"><?php esc_html_e( 'Acordes', 'santiago-moraes' ); ?></span>
				</button>
				<button type="button" class="chord-control chord-control--print" onclick="window.print();" aria-label="<?php esc_attr_e( 'Imprimir', 'santiago-moraes' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512" fill="currentColor"><path d="M128 0C92.7 0 64 28.7 64 64l0 96 0 32 0 160-64 0 0-160c0-35.3 28.7-64 64-64l64 0 0-64c0-35.3 28.7-64 64-64L384 0c35.3 0 64 28.7 64 64l0 64 64 0c35.3 0 64 28.7 64 64l0 160-64 0 0-160-64 0 0 224c0 35.3-28.7 64-64 64L192 480c-35.3 0-64-28.7-64-64L128 0zm64 64l0 96 192 0 0-96L192 64zM160 352l192 0 0 64-192 0 0-64z"/></svg>
				</button>
			</div>
		</div>

		<div class="chord-viewer__content">
			<?php
			foreach ( $sections as $section ) :
				$mod_class = $section['modifier'] ? ' chord-section--' . esc_attr( $section['modifier'] ) : '';
				?>
				<section class="chord-section<?php echo $mod_class; ?>">
				<?php if ( '' !== $section['title'] ) : ?>
					<h3 class="chord-section__title"><?php echo esc_html( $section['title'] ); ?></h3>
				<?php endif; ?>
				<?php
				foreach ( $section['lines'] as $line_data ) :
					if ( 'empty' === $line_data['type'] ) :
						echo '<div class="chord-line chord-line--empty"></div>';

					elseif ( 'chords_only' === $line_data['type'] ) :
						// Build chords-only line as tight HTML — no whitespace between spans.
						$chords_html = '';
						foreach ( $line_data['chords'] as $chord ) {
							$chords_html .= '<span class="chord" data-chord="' . esc_attr( $chord ) . '">' . esc_html( $chord ) . '</span> ';
						}
						echo '<div class="chord-line chord-line--chords-only">' . trim( $chords_html ) . '</div>';

					elseif ( 'lyric_with_chords' === $line_data['type'] ) :
						// Build chord-pair line as tight HTML — ZERO whitespace between spans.
						$pairs_html = '';
						foreach ( $line_data['pairs'] as $pair ) {
							if ( '' !== $pair['chord'] ) {
								$pairs_html .= '<span class="chord-pair">'
									. '<span class="chord" data-chord="' . esc_attr( $pair['chord'] ) . '">' . esc_html( $pair['chord'] ) . '</span>'
									. '<span class="lyric">' . esc_html( $pair['lyric'] ) . '</span>'
									. '</span>';
							} else {
								$pairs_html .= '<span class="chord-pair chord-pair--no-chord">'
									. '<span class="lyric">' . esc_html( $pair['lyric'] ) . '</span>'
									. '</span>';
							}
						}
						echo '<div class="chord-line">' . $pairs_html . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above.

					elseif ( 'lyric_only' === $line_data['type'] ) :
						echo '<div class="chord-line chord-line--lyrics-only"><span class="lyric">' . esc_html( $line_data['text'] ) . '</span></div>';

					endif;
				endforeach;
				?>
				</section>
			<?php endforeach; ?>
		</div>

	</div>
	<?php
	return ob_get_clean();
}
