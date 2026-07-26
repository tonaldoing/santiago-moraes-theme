<?php
/**
 * Theme Options helper functions.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Retrieve a single theme option from the sm_options array.
 */
function sm_get_option( $key, $default = '' ) {
	static $options = null;

	if ( null === $options ) {
		$options = get_option( 'sm_options', array() );
	}

	if ( isset( $options[ $key ] ) && '' !== $options[ $key ] ) {
		return $options[ $key ];
	}

	return $default;
}

// =====================================================================
// Output CSS custom properties.
// =====================================================================

add_action( 'wp_head', 'sm_customizer_css', 5 );

/**
 * Output CSS custom properties on :root from theme options.
 */
function sm_customizer_css() {
	$vars = array(
		// Core palette.
		'--color-ink'       => sm_get_option( 'sm_color_ink', '#1F3C57' ),
		'--color-paper'     => sm_get_option( 'sm_color_paper', '#E9DCC6' ),
		'--color-ochre'     => sm_get_option( 'sm_color_ochre', '#E08B3E' ),
		'--color-brick'     => sm_get_option( 'sm_color_brick', '#A8341C' ),
		'--color-cream'     => sm_get_option( 'sm_color_cream', '#F4E9D6' ),
		'--color-warm'      => sm_get_option( 'sm_color_warm', '#D9CBB2' ),
		'--color-muted'     => sm_get_option( 'sm_color_muted', '#C9B896' ),
		'--color-brown'     => sm_get_option( 'sm_color_brown', '#3A2A1C' ),
		'--color-olive'     => sm_get_option( 'sm_color_olive', '#6B573C' ),
		'--color-footer-text' => sm_get_option( 'sm_color_footer_text', '#C6B79C' ),
	);

	$css = ':root{';
	foreach ( $vars as $prop => $val ) {
		$css .= $prop . ':' . $val . ';';
	}
	$css .= '}';

	echo '<style id="sm-customizer-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

// =====================================================================
// Output tracking code.
// =====================================================================

add_action( 'wp_head', 'sm_tracking_head', 1 );

/**
 * Output GA4 and custom head code.
 */
function sm_tracking_head() {
	$custom = sm_get_option( 'sm_custom_head_code', '' );
	if ( $custom ) {
		echo $custom . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Admin-controlled.
	}

	$ga_id = sm_get_option( 'sm_ga_id', '' );
	if ( $ga_id ) {
		?>
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_id ); ?>"></script>
		<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?php echo esc_js( $ga_id ); ?>');</script>
		<?php
	}
}

// =====================================================================
// Google Fonts URL builder — Rebranding fonts.
// =====================================================================

/**
 * Build a Google Fonts CSS URL for the rebranding fonts.
 *
 * Archivo Black + Newsreader (with italic) + DM Mono.
 */
function sm_google_fonts_url() {
	$families = array(
		'family=Archivo+Black',
		'family=Newsreader:ital,opsz,wght@0,6..72,300;0,6..72,400;0,6..72,500;1,6..72,300;1,6..72,400',
		'family=DM+Mono:wght@400;500',
	);

	return 'https://fonts.googleapis.com/css2?' . implode( '&', $families ) . '&display=swap';
}

/**
 * External (non-Google) font keys and their stylesheet URLs.
 */
function sm_get_external_font_keys() {
	return array();
}

/**
 * Check whether any selected theme font needs an external stylesheet.
 */
function sm_needs_adobe_fonts() {
	return false;
}
