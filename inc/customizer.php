<?php
/**
 * Customizer settings for the Santiago Moraes theme.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'sm_customize_register' );

/**
 * Register Customizer panels, sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 */
function sm_customize_register( $wp_customize ) {

	// =====================================================================
	// PANEL: Santiago Moraes
	// =====================================================================
	$wp_customize->add_panel(
		'sm_panel',
		array(
			'title'    => __( 'Santiago Moraes', 'santiago-moraes' ),
			'priority' => 30,
		)
	);

	// =====================================================================
	// SECTION: Colores
	// =====================================================================
	$wp_customize->add_section( 'sm_colors_section', array(
		'title' => __( 'Colores', 'santiago-moraes' ),
		'panel' => 'sm_panel',
	) );

	$color_settings = array(
		'sm_color_accent'    => array( '#EC4913', __( 'Color principal (acento)', 'santiago-moraes' ) ),
		'sm_color_dark'      => array( '#1B110D', __( 'Color de texto', 'santiago-moraes' ) ),
		'sm_color_secondary' => array( '#9A5F4C', __( 'Color de texto secundario', 'santiago-moraes' ) ),
		'sm_color_black'     => array( '#010101', __( 'Color de fondo oscuro', 'santiago-moraes' ) ),
		'sm_color_border'    => array( '#E7D5CF', __( 'Color de bordes', 'santiago-moraes' ) ),
		'sm_color_cream'     => array( '#F7F3F0', __( 'Color crema (texto hero)', 'santiago-moraes' ) ),
	);

	foreach ( $color_settings as $id => $data ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $data[0],
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
			'label'   => $data[1],
			'section' => 'sm_colors_section',
		) ) );
	}

	// =====================================================================
	// SECTION: Tipografias
	// =====================================================================
	$wp_customize->add_section( 'sm_typography_section', array(
		'title' => __( 'Tipografias', 'santiago-moraes' ),
		'panel' => 'sm_panel',
	) );

	$wp_customize->add_setting( 'sm_font_heading', array(
		'default'           => 'Be Vietnam Pro',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_font_heading', array(
		'label'   => __( 'Fuente de titulos', 'santiago-moraes' ),
		'section' => 'sm_typography_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_font_body', array(
		'default'           => 'Montserrat',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_font_body', array(
		'label'   => __( 'Fuente de cuerpo', 'santiago-moraes' ),
		'section' => 'sm_typography_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_font_size_base', array(
		'default'           => 16,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'sm_font_size_base', array(
		'label'       => __( 'Tamano base (px)', 'santiago-moraes' ),
		'section'     => 'sm_typography_section',
		'type'        => 'range',
		'input_attrs' => array( 'min' => 14, 'max' => 20, 'step' => 1 ),
	) );

	// =====================================================================
	// SECTION: Logo y Header
	// =====================================================================
	$wp_customize->add_section( 'sm_header_section', array(
		'title' => __( 'Logo y Header', 'santiago-moraes' ),
		'panel' => 'sm_panel',
	) );

	$wp_customize->add_setting( 'sm_logo_type', array(
		'default'           => 'text',
		'sanitize_callback' => 'sm_sanitize_radio',
	) );
	$wp_customize->add_control( 'sm_logo_type', array(
		'label'   => __( 'Logo tipo', 'santiago-moraes' ),
		'section' => 'sm_header_section',
		'type'    => 'radio',
		'choices' => array(
			'text'  => __( 'Texto', 'santiago-moraes' ),
			'image' => __( 'Imagen', 'santiago-moraes' ),
		),
	) );

	$wp_customize->add_setting( 'sm_logo_text', array(
		'default'           => 'Santiago Moraes',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_logo_text', array(
		'label'   => __( 'Logo texto', 'santiago-moraes' ),
		'section' => 'sm_header_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_logo_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'sm_logo_image', array(
		'label'   => __( 'Logo imagen', 'santiago-moraes' ),
		'section' => 'sm_header_section',
	) ) );

	$wp_customize->add_setting( 'sm_header_height', array(
		'default'           => 90,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'sm_header_height', array(
		'label'       => __( 'Altura del header (px)', 'santiago-moraes' ),
		'section'     => 'sm_header_section',
		'type'        => 'range',
		'input_attrs' => array( 'min' => 60, 'max' => 120, 'step' => 5 ),
	) );

	// =====================================================================
	// SECTION: Redes Sociales
	// =====================================================================
	$wp_customize->add_section( 'sm_social_section', array(
		'title' => __( 'Redes Sociales', 'santiago-moraes' ),
		'panel' => 'sm_panel',
	) );

	$social_fields = array(
		'sm_social_spotify'   => 'Spotify URL',
		'sm_social_instagram' => 'Instagram URL',
		'sm_social_youtube'   => 'YouTube URL',
		'sm_social_bandcamp'  => 'Bandcamp URL',
		'sm_social_soundcloud' => 'SoundCloud URL',
		'sm_social_facebook'  => 'Facebook URL',
		'sm_social_twitter'   => 'Twitter/X URL',
	);

	foreach ( $social_fields as $id => $label ) {
		$wp_customize->add_setting( $id, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $label,
			'section' => 'sm_social_section',
			'type'    => 'url',
		) );
	}

	// =====================================================================
	// SECTION: Home / Hero
	// =====================================================================
	$wp_customize->add_section( 'sm_hero_section', array(
		'title' => __( 'Home / Hero', 'santiago-moraes' ),
		'panel' => 'sm_panel',
	) );

	$wp_customize->add_setting( 'sm_hero_line1', array(
		'default'           => 'Santiago',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_hero_line1', array(
		'label'   => __( 'Hero titulo linea 1', 'santiago-moraes' ),
		'section' => 'sm_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_hero_line2', array(
		'default'           => 'Moraes',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_hero_line2', array(
		'label'   => __( 'Hero titulo linea 2 (acento)', 'santiago-moraes' ),
		'section' => 'sm_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_hero_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'sm_hero_image', array(
		'label'   => __( 'Hero imagen', 'santiago-moraes' ),
		'section' => 'sm_hero_section',
	) ) );

	$wp_customize->add_setting( 'sm_hero_btn1_text', array(
		'default'           => 'Escuchar ahora',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_hero_btn1_text', array(
		'label'   => __( 'Boton primario texto', 'santiago-moraes' ),
		'section' => 'sm_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_hero_btn1_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'sm_hero_btn1_url', array(
		'label'   => __( 'Boton primario URL', 'santiago-moraes' ),
		'section' => 'sm_hero_section',
		'type'    => 'url',
	) );

	$wp_customize->add_setting( 'sm_hero_btn2_text', array(
		'default'           => 'Proximos Shows',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_hero_btn2_text', array(
		'label'   => __( 'Boton secundario texto', 'santiago-moraes' ),
		'section' => 'sm_hero_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_hero_btn2_url', array(
		'default'           => '#shows',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'sm_hero_btn2_url', array(
		'label'   => __( 'Boton secundario URL', 'santiago-moraes' ),
		'section' => 'sm_hero_section',
		'type'    => 'text',
	) );

	// =====================================================================
	// SECTION: Musica
	// =====================================================================
	$wp_customize->add_section( 'sm_featured_album_section', array(
		'title' => __( 'Musica', 'santiago-moraes' ),
		'panel' => 'sm_panel',
	) );

	$wp_customize->add_setting( 'sm_featured_album_id', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'sm_featured_album_id', array(
		'label'   => __( 'Album Destacado', 'santiago-moraes' ),
		'section' => 'sm_featured_album_section',
		'type'    => 'select',
		'choices' => sm_get_album_choices(),
	) );

	// ── Spotify Player ──

	$wp_customize->add_setting( 'sm_player_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'sm_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'sm_player_enabled', array(
		'label'   => __( 'Mostrar sticky player', 'santiago-moraes' ),
		'section' => 'sm_featured_album_section',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'sm_player_homepage', array(
		'default'           => true,
		'sanitize_callback' => 'sm_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'sm_player_homepage', array(
		'label'   => __( 'Player en homepage (embed grande)', 'santiago-moraes' ),
		'section' => 'sm_featured_album_section',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'sm_player_spotify_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'sm_player_spotify_url', array(
		'label'       => __( 'Spotify URL del player', 'santiago-moraes' ),
		'description' => __( 'Album, playlist, track o artista. Vacio = usa el album destacado.', 'santiago-moraes' ),
		'section'     => 'sm_featured_album_section',
		'type'        => 'url',
	) );

	// =====================================================================
	// SECTION: Footer
	// =====================================================================
	$wp_customize->add_section( 'sm_footer_section', array(
		'title' => __( 'Footer', 'santiago-moraes' ),
		'panel' => 'sm_panel',
	) );

	$wp_customize->add_setting( 'sm_footer_copyright', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_footer_copyright', array(
		'label'   => __( 'Texto de copyright', 'santiago-moraes' ),
		'section' => 'sm_footer_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_footer_credits', array(
		'default'           => 'Designed with FeeloLab',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_footer_credits', array(
		'label'   => __( 'Creditos adicionales', 'santiago-moraes' ),
		'section' => 'sm_footer_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_footer_scroll_top', array(
		'default'           => true,
		'sanitize_callback' => 'sm_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'sm_footer_scroll_top', array(
		'label'   => __( 'Mostrar scroll-to-top', 'santiago-moraes' ),
		'section' => 'sm_footer_section',
		'type'    => 'checkbox',
	) );

	// =====================================================================
	// SECTION: Contacto
	// =====================================================================
	$wp_customize->add_section( 'sm_contact_section', array(
		'title' => __( 'Contacto', 'santiago-moraes' ),
		'panel' => 'sm_panel',
	) );

	$wp_customize->add_setting( 'sm_contact_email', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_email',
	) );
	$wp_customize->add_control( 'sm_contact_email', array(
		'label'   => __( 'Email de contacto', 'santiago-moraes' ),
		'section' => 'sm_contact_section',
		'type'    => 'email',
	) );

	$wp_customize->add_setting( 'sm_contact_phone', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_contact_phone', array(
		'label'   => __( 'Telefono', 'santiago-moraes' ),
		'section' => 'sm_contact_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_contact_address', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_contact_address', array(
		'label'   => __( 'Direccion', 'santiago-moraes' ),
		'section' => 'sm_contact_section',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'sm_contact_maps_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'sm_contact_maps_url', array(
		'label'   => __( 'Google Maps embed URL', 'santiago-moraes' ),
		'section' => 'sm_contact_section',
		'type'    => 'url',
	) );

	// =====================================================================
	// SECTION: Tracking
	// =====================================================================
	$wp_customize->add_section( 'sm_tracking_section', array(
		'title' => __( 'Tracking', 'santiago-moraes' ),
		'panel' => 'sm_panel',
	) );

	$wp_customize->add_setting( 'sm_ga_id', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sm_ga_id', array(
		'label'       => __( 'Google Analytics ID', 'santiago-moraes' ),
		'description' => __( 'Ej: G-XXXXXXXXXX', 'santiago-moraes' ),
		'section'     => 'sm_tracking_section',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'sm_custom_head_code', array(
		'default'           => '',
		'sanitize_callback' => 'sm_sanitize_custom_code',
	) );
	$wp_customize->add_control( 'sm_custom_head_code', array(
		'label'       => __( 'Codigo personalizado en head', 'santiago-moraes' ),
		'description' => __( 'Facebook Pixel, etc. Se inserta en <head>.', 'santiago-moraes' ),
		'section'     => 'sm_tracking_section',
		'type'        => 'textarea',
	) );
}

// =====================================================================
// Sanitize helpers
// =====================================================================

/**
 * Build the choices array for the featured album dropdown.
 *
 * @return array
 */
function sm_get_album_choices() {
	$choices = array( 0 => __( '-- Seleccionar --', 'santiago-moraes' ) );

	$albums = get_terms( array(
		'taxonomy'   => 'album',
		'hide_empty' => false,
		'orderby'    => 'meta_value_num',
		'meta_key'   => '_album_year', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'order'      => 'DESC',
		'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'relation' => 'OR',
			array( 'key' => '_album_is_demo', 'value' => '1', 'compare' => '!=' ),
			array( 'key' => '_album_is_demo', 'compare' => 'NOT EXISTS' ),
		),
	) );

	if ( ! is_wp_error( $albums ) ) {
		foreach ( $albums as $album ) {
			$year  = get_term_meta( $album->term_id, '_album_year', true );
			$label = $album->name;
			if ( $year ) {
				$label .= ' (' . $year . ')';
			}
			$choices[ $album->term_id ] = $label;
		}
	}

	return $choices;
}

/**
 * Sanitize radio input.
 *
 * @param string $input Value.
 * @return string
 */
function sm_sanitize_radio( $input ) {
	$valid = array( 'text', 'image' );
	return in_array( $input, $valid, true ) ? $input : 'text';
}

/**
 * Sanitize checkbox.
 *
 * @param mixed $input Value.
 * @return bool
 */
function sm_sanitize_checkbox( $input ) {
	return (bool) $input;
}

/**
 * Sanitize custom code textarea — allow scripts.
 *
 * @param string $input Raw code.
 * @return string
 */
function sm_sanitize_custom_code( $input ) {
	return $input; // Allow raw code. Only admins can access Customizer.
}

// =====================================================================
// Output CSS custom properties
// =====================================================================

add_action( 'wp_head', 'sm_customizer_css', 5 );

/**
 * Output CSS custom properties on :root from Customizer settings.
 */
function sm_customizer_css() {
	$vars = array(
		'--color-accent'    => get_theme_mod( 'sm_color_accent', '#EC4913' ),
		'--color-dark'      => get_theme_mod( 'sm_color_dark', '#1B110D' ),
		'--color-secondary' => get_theme_mod( 'sm_color_secondary', '#9A5F4C' ),
		'--color-black'     => get_theme_mod( 'sm_color_black', '#010101' ),
		'--color-border'    => get_theme_mod( 'sm_color_border', '#E7D5CF' ),
		'--color-cream'     => get_theme_mod( 'sm_color_cream', '#F7F3F0' ),
		'--font-heading'    => "'" . esc_attr( get_theme_mod( 'sm_font_heading', 'Be Vietnam Pro' ) ) . "', sans-serif",
		'--font-body'       => "'" . esc_attr( get_theme_mod( 'sm_font_body', 'Montserrat' ) ) . "', sans-serif",
		'--font-size-base'  => absint( get_theme_mod( 'sm_font_size_base', 16 ) ) . 'px',
		'--header-height'   => absint( get_theme_mod( 'sm_header_height', 90 ) ) . 'px',
	);

	$css = ':root{';
	foreach ( $vars as $prop => $val ) {
		$css .= $prop . ':' . $val . ';';
	}
	$css .= '}';

	echo '<style id="sm-customizer-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

// =====================================================================
// Output tracking code
// =====================================================================

add_action( 'wp_head', 'sm_tracking_head', 1 );

/**
 * Output GA4 and custom head code.
 */
function sm_tracking_head() {
	// Custom head code (first priority).
	$custom = get_theme_mod( 'sm_custom_head_code', '' );
	if ( $custom ) {
		echo $custom . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Admin-controlled.
	}

	// Google Analytics.
	$ga_id = get_theme_mod( 'sm_ga_id', '' );
	if ( $ga_id ) {
		?>
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_id ); ?>"></script>
		<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?php echo esc_js( $ga_id ); ?>');</script>
		<?php
	}
}
