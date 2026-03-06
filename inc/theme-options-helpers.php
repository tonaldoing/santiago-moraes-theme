<?php
/**
 * Theme Options helper functions.
 *
 * Provides sm_get_option() to retrieve individual options from the
 * consolidated sm_options row, plus the CSS custom properties and
 * tracking code output hooks previously in customizer.php.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Retrieve a single theme option from the sm_options array.
 *
 * @param string $key     Option key (e.g. 'sm_color_accent').
 * @param mixed  $default Default value if not set.
 * @return mixed
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
// One-time migration from theme_mod to sm_options.
// =====================================================================

add_action( 'admin_init', 'sm_maybe_migrate_customizer_to_options' );

/**
 * Migrate existing Customizer values into the sm_options option.
 *
 * Runs once; sets a version flag so it never repeats.
 */
function sm_maybe_migrate_customizer_to_options() {
	if ( get_option( 'sm_options_migrated' ) ) {
		return;
	}

	// All known theme_mod keys with their defaults.
	$keys_defaults = array(
		// Colors.
		'sm_color_accent'       => '#EC4913',
		'sm_color_dark'         => '#1B110D',
		'sm_color_secondary'    => '#9A5F4C',
		'sm_color_black'        => '#010101',
		'sm_color_border'       => '#E7D5CF',
		'sm_color_cream'        => '#F7F3F0',
		'sm_color_bg_light'     => '#54200F',
		'sm_color_white'        => '#FFFFFF',
		'sm_color_box'          => '#F7F3F0',
		'sm_color_heading'      => '#1B110D',
		'sm_color_body'         => '#0A0A0A',
		'sm_color_box_heading'  => '#1B110D',
		'sm_color_box_text'     => '#0A0A0A',
		'sm_color_hover_link'   => '#EC4913',
		'sm_color_hover_accent' => '#D33F0E',
		'sm_color_hover_light'  => '#1B110D',
		'sm_color_header_bg'           => '#FFFFFF',
		'sm_color_header_text'         => '#F7F3F0',
		'sm_color_header_text_scroll'  => '#1B110D',
		'sm_color_btn_primary_bg'    => '#EC4913',
		'sm_color_btn_primary_text'  => '#FFFFFF',
		'sm_color_btn_primary_hover' => '#D33F0E',
		// Typography.
		'sm_font_heading'       => 'Be Vietnam Pro',
		'sm_font_body'          => 'Montserrat',
		'sm_font_button'        => 'Be Vietnam Pro',
		'sm_font_size_base'     => 16,
		// Header / Logo.
		'sm_logo_type'          => 'text',
		'sm_logo_text'          => 'Santiago Moraes',
		'sm_logo_image'         => '',
		'sm_header_height'      => 90,
		// Social.
		'sm_social_spotify'     => '',
		'sm_social_instagram'   => '',
		'sm_social_youtube'     => '',
		'sm_social_bandcamp'    => '',
		'sm_social_soundcloud'  => '',
		'sm_social_facebook'    => '',
		'sm_social_twitter'     => '',
		// Hero.
		'sm_hero_line1'         => 'Santiago',
		'sm_hero_line2'         => 'Moraes',
		'sm_hero_image'         => '',
		'sm_hero_btn1_text'     => 'Escuchar ahora',
		'sm_hero_btn1_url'      => '',
		'sm_hero_btn2_text'     => 'Proximos Shows',
		'sm_hero_btn2_url'      => '#shows',
		// Music.
		'sm_featured_album_id'  => 0,
		'sm_player_enabled'     => true,
		'sm_player_homepage'    => true,
		'sm_player_spotify_url' => '',
		// Footer.
		'sm_footer_copyright'   => '',
		'sm_footer_credits'     => 'Designed with FeeloLab',
		'sm_footer_scroll_top'  => true,
		// Contact.
		'sm_contact_email'      => '',
		'sm_contact_phone'      => '',
		'sm_contact_address'    => '',
		'sm_contact_maps_url'   => '',
		// Tracking.
		'sm_ga_id'              => '',
		'sm_custom_head_code'   => '',
	);

	$existing = get_option( 'sm_options', array() );
	$migrated = is_array( $existing ) ? $existing : array();

	foreach ( $keys_defaults as $key => $default ) {
		// Only migrate if not already present in sm_options.
		if ( ! isset( $migrated[ $key ] ) ) {
			$value = get_theme_mod( $key, $default );
			$migrated[ $key ] = $value;
		}
	}

	update_option( 'sm_options', $migrated );
	update_option( 'sm_options_migrated', '1' );
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
		'--color-accent'    => sm_get_option( 'sm_color_accent', '#EC4913' ),
		'--color-dark'      => sm_get_option( 'sm_color_dark', '#1B110D' ),
		'--color-secondary' => sm_get_option( 'sm_color_secondary', '#9A5F4C' ),
		'--color-black'     => sm_get_option( 'sm_color_black', '#010101' ),
		'--color-border'    => sm_get_option( 'sm_color_border', '#E7D5CF' ),
		'--color-cream'     => sm_get_option( 'sm_color_cream', '#F7F3F0' ),
		'--color-bg-light'  => sm_get_option( 'sm_color_bg_light', '#54200F' ),
		'--color-white'     => sm_get_option( 'sm_color_white', '#FFFFFF' ),
		'--color-box'       => sm_get_option( 'sm_color_box', '#F7F3F0' ),
		'--color-heading'     => sm_get_option( 'sm_color_heading', '#1B110D' ),
		'--color-body'        => sm_get_option( 'sm_color_body', '#0A0A0A' ),
		'--color-box-heading'  => sm_get_option( 'sm_color_box_heading', '#1B110D' ),
		'--color-box-text'     => sm_get_option( 'sm_color_box_text', '#0A0A0A' ),
		'--color-hover-link'   => sm_get_option( 'sm_color_hover_link', '#EC4913' ),
		'--color-hover-light'  => sm_get_option( 'sm_color_hover_light', '#1B110D' ),
		'--color-header-bg'           => sm_get_option( 'sm_color_header_bg', '#FFFFFF' ),
		'--color-header-text'         => sm_get_option( 'sm_color_header_text', '#F7F3F0' ),
		'--color-header-text-scroll'  => sm_get_option( 'sm_color_header_text_scroll', '#1B110D' ),
		'--color-btn-primary-bg'    => sm_get_option( 'sm_color_btn_primary_bg', '#EC4913' ),
		'--color-btn-primary-text'  => sm_get_option( 'sm_color_btn_primary_text', '#FFFFFF' ),
		'--color-btn-primary-hover' => sm_get_option( 'sm_color_btn_primary_hover', '#D33F0E' ),
		'--font-heading'    => "'" . esc_attr( sm_get_option( 'sm_font_heading', 'Be Vietnam Pro' ) ) . "', sans-serif",
		'--font-body'       => "'" . esc_attr( sm_get_option( 'sm_font_body', 'Montserrat' ) ) . "', sans-serif",
		'--font-button'     => "'" . esc_attr( sm_get_option( 'sm_font_button', 'Be Vietnam Pro' ) ) . "', sans-serif",
		'--font-size-base'  => absint( sm_get_option( 'sm_font_size_base', 16 ) ) . 'px',
		'--header-height'   => absint( sm_get_option( 'sm_header_height', 90 ) ) . 'px',
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
	// Custom head code (first priority).
	$custom = sm_get_option( 'sm_custom_head_code', '' );
	if ( $custom ) {
		echo $custom . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Admin-controlled.
	}

	// Google Analytics.
	$ga_id = sm_get_option( 'sm_ga_id', '' );
	if ( $ga_id ) {
		?>
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_id ); ?>"></script>
		<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?php echo esc_js( $ga_id ); ?>');</script>
		<?php
	}
}

// =====================================================================
// Google Fonts URL builder.
// =====================================================================

/**
 * Build a Google Fonts CSS URL from the three selected fonts.
 *
 * Deduplicates so if heading/body/button share the same font,
 * it's only requested once. Loads weights 400,500,700,900.
 *
 * @return string Google Fonts CSS2 URL.
 */
function sm_google_fonts_url() {
	// Fonts served by external providers other than Google Fonts.
	$external_fonts = sm_get_external_font_keys();

	$fonts = array(
		sm_get_option( 'sm_font_heading', 'Be Vietnam Pro' ),
		sm_get_option( 'sm_font_body', 'Montserrat' ),
		sm_get_option( 'sm_font_button', 'Be Vietnam Pro' ),
	);

	$unique   = array_unique( array_filter( $fonts ) );
	$families = array();

	foreach ( $unique as $font ) {
		// Skip fonts that come from Adobe Fonts / other providers.
		if ( isset( $external_fonts[ $font ] ) ) {
			continue;
		}
		$family     = rawurlencode( $font );
		$families[] = 'family=' . $family . ':wght@400;500;700;900';
	}

	if ( empty( $families ) ) {
		return '';
	}

	return 'https://fonts.googleapis.com/css2?' . implode( '&', $families ) . '&display=swap';
}

/**
 * External (non-Google) font keys and their stylesheet URLs.
 *
 * @return array font-family => stylesheet URL.
 */
function sm_get_external_font_keys() {
	return array(
		'avenir-lt-pro' => 'https://use.typekit.net/clj0jfd.css',
	);
}

/**
 * Check whether any selected theme font needs an external stylesheet.
 *
 * @return string|false The stylesheet URL, or false if none required.
 */
function sm_needs_adobe_fonts() {
	$external = sm_get_external_font_keys();
	$fonts    = array(
		sm_get_option( 'sm_font_heading', 'Be Vietnam Pro' ),
		sm_get_option( 'sm_font_body', 'Montserrat' ),
		sm_get_option( 'sm_font_button', 'Be Vietnam Pro' ),
	);

	foreach ( $fonts as $font ) {
		if ( isset( $external[ $font ] ) ) {
			return $external[ $font ];
		}
	}

	return false;
}
