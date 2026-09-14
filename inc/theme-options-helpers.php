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
	$gsc = sm_get_option( 'sm_gsc_verification', '' );
	if ( $gsc ) {
		echo '<meta name="google-site-verification" content="' . esc_attr( $gsc ) . '">' . "\n";
	}

	$custom = sm_get_option( 'sm_custom_head_code', '' );
	if ( $custom ) {
		echo $custom . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Admin-controlled.
	}

	// Never send logged-in editors' own browsing to GA4.
	$ga_id = sm_get_option( 'sm_ga_id', '' );
	if ( $ga_id && ! current_user_can( 'edit_posts' ) ) {
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
 * Google Fonts are no longer used: Archivo Black, Newsreader and DM Mono are
 * self-hosted (assets/fonts + assets/scss/_fonts.scss). Kept for backwards
 * compatibility with callers; always returns an empty string.
 *
 * @return string
 */
function sm_google_fonts_url() {
	return '';
}


