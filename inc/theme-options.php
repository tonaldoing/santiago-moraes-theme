<?php
/**
 * Theme Options admin page.
 *
 * Replaces the Customizer settings with a dedicated dashboard page
 * under Apariencia > Santiago Moraes.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

// =====================================================================
// Sanitize helpers (moved from customizer.php).
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
 * Curated font catalog available in the Tipografias tab.
 *
 * Keys = stored value (font name), used to build Google Fonts URL.
 *
 * @return array
 */
function sm_get_font_choices() {
	return array(
		'Be Vietnam Pro'   => 'Be Vietnam Pro',
		'Montserrat'       => 'Montserrat',
		'Poppins'          => 'Poppins',
		'Inter'            => 'Inter',
		'DM Sans'          => 'DM Sans',
		'Raleway'          => 'Raleway',
		'Lato'             => 'Lato',
		'Open Sans'        => 'Open Sans',
		'Nunito'           => 'Nunito',
		'Roboto'           => 'Roboto',
		'Space Grotesk'    => 'Space Grotesk',
		'Outfit'           => 'Outfit',
		'Work Sans'        => 'Work Sans',
		'Source Sans 3'    => 'Source Sans 3',
		'Playfair Display'    => 'Playfair Display',
		'Lora'                => 'Lora',
		'Averia Sans Libre'   => 'Averia Sans Libre',
		'avenir-lt-pro'       => 'Avenir LT Pro (Adobe)',
	);
}

// =====================================================================
// Register the admin page.
// =====================================================================

add_action( 'admin_menu', 'sm_add_theme_options_page' );

/**
 * Add Theme Options page under Apariencia.
 */
function sm_add_theme_options_page() {
	add_theme_page(
		__( 'Santiago Moraes — Opciones', 'santiago-moraes' ),
		__( 'Santiago Moraes', 'santiago-moraes' ),
		'edit_theme_options',
		'sm-theme-options',
		'sm_render_theme_options_page'
	);
}

// =====================================================================
// Register settings.
// =====================================================================

add_action( 'admin_init', 'sm_register_theme_options' );

/**
 * Register the sm_options setting with the Settings API.
 */
function sm_register_theme_options() {
	register_setting( 'sm_options_group', 'sm_options', array(
		'sanitize_callback' => 'sm_sanitize_options',
	) );
}

/**
 * Sanitize the full options array on save.
 *
 * @param array $input Raw form values.
 * @return array Sanitized values.
 */
function sm_sanitize_options( $input ) {
	$clean = array();

	if ( ! is_array( $input ) ) {
		return $clean;
	}

	// Colors.
	$color_keys = array(
		'sm_color_accent',
		'sm_color_dark',
		'sm_color_secondary',
		'sm_color_black',
		'sm_color_border',
		'sm_color_cream',
		'sm_color_bg_light',
		'sm_color_white',
		'sm_color_box',
		'sm_color_heading',
		'sm_color_body',
		'sm_color_box_heading',
		'sm_color_box_text',
		'sm_color_hover_link',
		'sm_color_hover_light',
		'sm_color_header_bg',
		'sm_color_header_text',
		'sm_color_header_text_scroll',
		'sm_color_btn_primary_bg',
		'sm_color_btn_primary_text',
		'sm_color_btn_primary_hover',
	);
	foreach ( $color_keys as $key ) {
		$clean[ $key ] = isset( $input[ $key ] ) ? sanitize_hex_color( $input[ $key ] ) : '';
	}

	// Typography — validate against font catalog.
	$valid_fonts = array_keys( sm_get_font_choices() );
	$clean['sm_font_heading'] = isset( $input['sm_font_heading'] ) && in_array( $input['sm_font_heading'], $valid_fonts, true )
		? $input['sm_font_heading'] : 'Be Vietnam Pro';
	$clean['sm_font_body'] = isset( $input['sm_font_body'] ) && in_array( $input['sm_font_body'], $valid_fonts, true )
		? $input['sm_font_body'] : 'Montserrat';
	$clean['sm_font_button'] = isset( $input['sm_font_button'] ) && in_array( $input['sm_font_button'], $valid_fonts, true )
		? $input['sm_font_button'] : 'Be Vietnam Pro';
	$clean['sm_font_size_base'] = isset( $input['sm_font_size_base'] ) ? absint( $input['sm_font_size_base'] ) : 16;

	// Header / Logo.
	$clean['sm_logo_type']     = isset( $input['sm_logo_type'] ) && in_array( $input['sm_logo_type'], array( 'text', 'image' ), true ) ? $input['sm_logo_type'] : 'text';
	$clean['sm_logo_text']     = isset( $input['sm_logo_text'] ) ? sanitize_text_field( $input['sm_logo_text'] ) : 'Santiago Moraes';
	$clean['sm_logo_image']    = isset( $input['sm_logo_image'] ) ? esc_url_raw( $input['sm_logo_image'] ) : '';
	$clean['sm_header_height'] = isset( $input['sm_header_height'] ) ? absint( $input['sm_header_height'] ) : 90;

	// Social URLs.
	$social_keys = array(
		'sm_social_spotify',
		'sm_social_instagram',
		'sm_social_youtube',
		'sm_social_bandcamp',
		'sm_social_soundcloud',
		'sm_social_facebook',
		'sm_social_twitter',
	);
	foreach ( $social_keys as $key ) {
		$clean[ $key ] = isset( $input[ $key ] ) ? esc_url_raw( $input[ $key ] ) : '';
	}

	// Hero.
	$clean['sm_hero_line1']     = isset( $input['sm_hero_line1'] ) ? sanitize_text_field( $input['sm_hero_line1'] ) : 'Santiago';
	$clean['sm_hero_line2']     = isset( $input['sm_hero_line2'] ) ? sanitize_text_field( $input['sm_hero_line2'] ) : 'Moraes';
	$clean['sm_hero_image']     = isset( $input['sm_hero_image'] ) ? esc_url_raw( $input['sm_hero_image'] ) : '';
	$clean['sm_hero_btn1_text'] = isset( $input['sm_hero_btn1_text'] ) ? sanitize_text_field( $input['sm_hero_btn1_text'] ) : '';
	$clean['sm_hero_btn1_url']  = isset( $input['sm_hero_btn1_url'] ) ? esc_url_raw( $input['sm_hero_btn1_url'] ) : '';
	$clean['sm_hero_btn2_text'] = isset( $input['sm_hero_btn2_text'] ) ? sanitize_text_field( $input['sm_hero_btn2_text'] ) : '';
	$clean['sm_hero_btn2_url']  = isset( $input['sm_hero_btn2_url'] ) ? sanitize_text_field( $input['sm_hero_btn2_url'] ) : '';

	// Music.
	$clean['sm_featured_album_id']  = isset( $input['sm_featured_album_id'] ) ? absint( $input['sm_featured_album_id'] ) : 0;
	$clean['sm_player_enabled']     = ! empty( $input['sm_player_enabled'] );
	$clean['sm_player_homepage']    = ! empty( $input['sm_player_homepage'] );
	$clean['sm_player_spotify_url'] = isset( $input['sm_player_spotify_url'] ) ? esc_url_raw( $input['sm_player_spotify_url'] ) : '';

	// Footer.
	$clean['sm_footer_copyright']  = isset( $input['sm_footer_copyright'] ) ? sanitize_text_field( $input['sm_footer_copyright'] ) : '';
	$clean['sm_footer_credits']    = isset( $input['sm_footer_credits'] ) ? sanitize_text_field( $input['sm_footer_credits'] ) : '';
	$clean['sm_footer_scroll_top'] = ! empty( $input['sm_footer_scroll_top'] );

	// Contact.
	$clean['sm_contact_email']    = isset( $input['sm_contact_email'] ) ? sanitize_email( $input['sm_contact_email'] ) : '';
	$clean['sm_contact_phone']    = isset( $input['sm_contact_phone'] ) ? sanitize_text_field( $input['sm_contact_phone'] ) : '';
	$clean['sm_contact_address']  = isset( $input['sm_contact_address'] ) ? sanitize_text_field( $input['sm_contact_address'] ) : '';
	$clean['sm_contact_maps_url'] = isset( $input['sm_contact_maps_url'] ) ? esc_url_raw( $input['sm_contact_maps_url'] ) : '';

	// Tracking.
	$clean['sm_ga_id']            = isset( $input['sm_ga_id'] ) ? sanitize_text_field( $input['sm_ga_id'] ) : '';
	$clean['sm_custom_head_code'] = isset( $input['sm_custom_head_code'] ) ? $input['sm_custom_head_code'] : '';

	return $clean;
}

// =====================================================================
// Enqueue admin assets on our page only.
// =====================================================================

add_action( 'admin_enqueue_scripts', 'sm_theme_options_enqueue' );

/**
 * Enqueue color picker + media uploader on the Theme Options page.
 *
 * @param string $hook Current admin page hook.
 */
function sm_theme_options_enqueue( $hook ) {
	if ( 'appearance_page_sm-theme-options' !== $hook ) {
		return;
	}

	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_media();
	wp_enqueue_script( 'wp-color-picker' );

	// Inline JS for color pickers + media uploaders.
	$js = "
	jQuery(document).ready(function($){
		$('.sm-color-picker').wpColorPicker();

		$('.sm-upload-btn').on('click',function(e){
			e.preventDefault();
			var btn = $(this);
			var input = btn.siblings('.sm-upload-input');
			var preview = btn.siblings('.sm-upload-preview');
			var frame = wp.media({
				title: 'Seleccionar imagen',
				button: { text: 'Usar imagen' },
				multiple: false
			});
			frame.on('select',function(){
				var attachment = frame.state().get('selection').first().toJSON();
				input.val(attachment.url);
				preview.html('<img src=\"'+attachment.url+'\" style=\"max-width:200px;height:auto;margin-top:8px;\">');
			});
			frame.open();
		});

		$('.sm-remove-btn').on('click',function(e){
			e.preventDefault();
			var btn = $(this);
			btn.siblings('.sm-upload-input').val('');
			btn.siblings('.sm-upload-preview').html('');
		});
	});
	";
	wp_add_inline_script( 'wp-color-picker', $js );
}

// =====================================================================
// Render the admin page.
// =====================================================================

/**
 * Render Theme Options page with tabs.
 */
function sm_render_theme_options_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$tabs = array(
		'general'  => __( 'General', 'santiago-moraes' ),
		'colores'  => __( 'Colores', 'santiago-moraes' ),
		'tipografia' => __( 'Tipografias', 'santiago-moraes' ),
		'hero'     => __( 'Hero', 'santiago-moraes' ),
		'musica'   => __( 'Musica', 'santiago-moraes' ),
		'redes'    => __( 'Redes Sociales', 'santiago-moraes' ),
		'contacto' => __( 'Contacto', 'santiago-moraes' ),
		'footer'   => __( 'Footer', 'santiago-moraes' ),
		'tracking' => __( 'Tracking', 'santiago-moraes' ),
	);

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';
	if ( ! array_key_exists( $active_tab, $tabs ) ) {
		$active_tab = 'general';
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Santiago Moraes — Opciones del Tema', 'santiago-moraes' ); ?></h1>

		<?php settings_errors( 'sm_options' ); ?>

		<h2 class="nav-tab-wrapper">
			<?php foreach ( $tabs as $slug => $label ) : ?>
				<a href="<?php echo esc_url( add_query_arg( 'tab', $slug, admin_url( 'themes.php?page=sm-theme-options' ) ) ); ?>"
				   class="nav-tab <?php echo $active_tab === $slug ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html( $label ); ?>
				</a>
			<?php endforeach; ?>
		</h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'sm_options_group' ); ?>

			<?php
			// Render hidden fields for tabs we're NOT editing so their
			// values don't get wiped on save.
			sm_render_hidden_fields( $active_tab );
			?>

			<table class="form-table" role="presentation">
				<?php
				switch ( $active_tab ) {
					case 'general':
						sm_tab_general();
						break;
					case 'colores':
						sm_tab_colores();
						break;
					case 'tipografia':
						sm_tab_tipografia();
						break;
					case 'hero':
						sm_tab_hero();
						break;
					case 'musica':
						sm_tab_musica();
						break;
					case 'redes':
						sm_tab_redes();
						break;
					case 'contacto':
						sm_tab_contacto();
						break;
					case 'footer':
						sm_tab_footer();
						break;
					case 'tracking':
						sm_tab_tracking();
						break;
				}
				?>
			</table>

			<?php submit_button( __( 'Guardar Cambios', 'santiago-moraes' ) ); ?>
		</form>
	</div>
	<?php
}

// =====================================================================
// Hidden fields — preserve values from other tabs on save.
// =====================================================================

/**
 * Render hidden inputs for all keys NOT in the current tab,
 * so saving one tab doesn't erase the others.
 *
 * @param string $active_tab Current tab slug.
 */
function sm_render_hidden_fields( $active_tab ) {
	$tab_keys = array(
		'general'    => array( 'sm_logo_type', 'sm_logo_text', 'sm_logo_image', 'sm_header_height' ),
		'colores'    => array( 'sm_color_accent', 'sm_color_dark', 'sm_color_secondary', 'sm_color_black', 'sm_color_border', 'sm_color_cream', 'sm_color_bg_light', 'sm_color_white', 'sm_color_box', 'sm_color_heading', 'sm_color_body', 'sm_color_box_heading', 'sm_color_box_text', 'sm_color_hover_link', 'sm_color_hover_light', 'sm_color_header_bg', 'sm_color_header_text', 'sm_color_header_text_scroll', 'sm_color_btn_primary_bg', 'sm_color_btn_primary_text', 'sm_color_btn_primary_hover' ),
		'tipografia' => array( 'sm_font_heading', 'sm_font_body', 'sm_font_button', 'sm_font_size_base' ),
		'hero'       => array( 'sm_hero_line1', 'sm_hero_line2', 'sm_hero_image', 'sm_hero_btn1_text', 'sm_hero_btn1_url', 'sm_hero_btn2_text', 'sm_hero_btn2_url' ),
		'musica'     => array( 'sm_featured_album_id', 'sm_player_enabled', 'sm_player_homepage', 'sm_player_spotify_url' ),
		'redes'      => array( 'sm_social_spotify', 'sm_social_instagram', 'sm_social_youtube', 'sm_social_bandcamp', 'sm_social_soundcloud', 'sm_social_facebook', 'sm_social_twitter' ),
		'contacto'   => array( 'sm_contact_email', 'sm_contact_phone', 'sm_contact_address', 'sm_contact_maps_url' ),
		'footer'     => array( 'sm_footer_copyright', 'sm_footer_credits', 'sm_footer_scroll_top' ),
		'tracking'   => array( 'sm_ga_id', 'sm_custom_head_code' ),
	);

	$checkbox_keys = array( 'sm_player_enabled', 'sm_player_homepage', 'sm_footer_scroll_top' );

	foreach ( $tab_keys as $tab => $keys ) {
		if ( $tab === $active_tab ) {
			continue;
		}
		foreach ( $keys as $key ) {
			$value = sm_get_option( $key, '' );
			// Checkboxes: output "1" if truthy so the sanitizer preserves them.
			if ( in_array( $key, $checkbox_keys, true ) ) {
				$value = $value ? '1' : '';
			}
			echo '<input type="hidden" name="sm_options[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '">';
		}
	}
}

// =====================================================================
// Tab renderers.
// =====================================================================

/**
 * General tab — Logo & Header.
 */
function sm_tab_general() {
	$logo_type     = sm_get_option( 'sm_logo_type', 'text' );
	$logo_text     = sm_get_option( 'sm_logo_text', 'Santiago Moraes' );
	$logo_image    = sm_get_option( 'sm_logo_image', '' );
	$header_height = sm_get_option( 'sm_header_height', 90 );
	?>
	<tr>
		<th scope="row"><?php esc_html_e( 'Logo tipo', 'santiago-moraes' ); ?></th>
		<td>
			<label><input type="radio" name="sm_options[sm_logo_type]" value="text" <?php checked( $logo_type, 'text' ); ?>> <?php esc_html_e( 'Texto', 'santiago-moraes' ); ?></label><br>
			<label><input type="radio" name="sm_options[sm_logo_type]" value="image" <?php checked( $logo_type, 'image' ); ?>> <?php esc_html_e( 'Imagen', 'santiago-moraes' ); ?></label>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_logo_text"><?php esc_html_e( 'Logo texto', 'santiago-moraes' ); ?></label></th>
		<td><input type="text" id="sm_logo_text" name="sm_options[sm_logo_text]" value="<?php echo esc_attr( $logo_text ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Logo imagen', 'santiago-moraes' ); ?></th>
		<td>
			<input type="hidden" name="sm_options[sm_logo_image]" value="<?php echo esc_url( $logo_image ); ?>" class="sm-upload-input">
			<button type="button" class="button sm-upload-btn"><?php esc_html_e( 'Seleccionar imagen', 'santiago-moraes' ); ?></button>
			<button type="button" class="button sm-remove-btn"><?php esc_html_e( 'Quitar', 'santiago-moraes' ); ?></button>
			<div class="sm-upload-preview">
				<?php if ( $logo_image ) : ?>
					<img src="<?php echo esc_url( $logo_image ); ?>" style="max-width:200px;height:auto;margin-top:8px;">
				<?php endif; ?>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_header_height"><?php esc_html_e( 'Altura del header (px)', 'santiago-moraes' ); ?></label></th>
		<td>
			<input type="range" id="sm_header_height" name="sm_options[sm_header_height]" value="<?php echo esc_attr( $header_height ); ?>" min="60" max="120" step="5">
			<span id="sm_header_height_val"><?php echo esc_html( $header_height ); ?>px</span>
			<script>document.getElementById('sm_header_height').addEventListener('input',function(){document.getElementById('sm_header_height_val').textContent=this.value+'px';});</script>
		</td>
	</tr>
	<?php
}

/**
 * Colores tab — grouped for clarity.
 */
function sm_tab_colores() {
	$groups = array(
		__( 'General', 'santiago-moraes' ) => array(
			'sm_color_accent'      => array( '#EC4913', 'Acento / links — --color-accent' ),
			'sm_color_hover_link'  => array( '#EC4913', 'Hover links y navegación — --color-hover-link' ),
			'sm_color_border'      => array( '#E7D5CF', 'Bordes — --color-border' ),
		),
		__( 'Fondos', 'santiago-moraes' ) => array(
			'sm_color_white'     => array( '#FFFFFF', 'Fondo principal — --color-white' ),
			'sm_color_black'     => array( '#010101', 'Fondo oscuro — --color-black' ),
			'sm_color_bg_light'  => array( '#54200F', 'Fondo secciones — --color-bg-light' ),
		),
		__( 'Textos', 'santiago-moraes' ) => array(
			'sm_color_heading'     => array( '#1B110D', 'Títulos — --color-heading' ),
			'sm_color_body'        => array( '#0A0A0A', 'Texto general — --color-body' ),
			'sm_color_secondary'   => array( '#9A5F4C', 'Texto secundario — --color-secondary' ),
			'sm_color_dark'        => array( '#1B110D', 'Texto destacado — --color-dark' ),
			'sm_color_cream'       => array( '#F7F3F0', 'Texto claro (hero, footer) — --color-cream' ),
			'sm_color_hover_light' => array( '#1B110D', 'Hover en fondo oscuro — --color-hover-light' ),
		),
		__( 'Cajas claras', 'santiago-moraes' ) => array(
			'sm_color_box'         => array( '#F7F3F0', 'Fondo — --color-box' ),
			'sm_color_box_heading' => array( '#1B110D', 'Títulos — --color-box-heading' ),
			'sm_color_box_text'    => array( '#0A0A0A', 'Texto — --color-box-text' ),
		),
		__( 'Header', 'santiago-moraes' ) => array(
			'sm_color_header_bg'           => array( '#FFFFFF', 'Fondo (scroll) — --color-header-bg' ),
			'sm_color_header_text'         => array( '#F7F3F0', 'Texto inicial (transparente) — --color-header-text' ),
			'sm_color_header_text_scroll'  => array( '#1B110D', 'Texto al hacer scroll — --color-header-text-scroll' ),
		),
		__( 'Botones', 'santiago-moraes' ) => array(
			'sm_color_btn_primary_bg'    => array( '#EC4913', 'Fondo primario — --color-btn-primary-bg' ),
			'sm_color_btn_primary_text'  => array( '#FFFFFF', 'Texto primario — --color-btn-primary-text' ),
			'sm_color_btn_primary_hover' => array( '#D33F0E', 'Hover primario — --color-btn-primary-hover' ),
		),
	);

	foreach ( $groups as $group_label => $colors ) :
		?>
		<tr><td colspan="2"><h3 style="margin:18px 0 6px;font-size:14px;font-weight:600;color:#1d2327;border-bottom:1px solid #c3c4c7;padding-bottom:6px;"><?php echo esc_html( $group_label ); ?></h3></td></tr>
		<?php
		foreach ( $colors as $key => $data ) :
			$val = sm_get_option( $key, $data[0] );
			?>
			<tr>
				<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $data[1] ); ?></label></th>
				<td><input type="text" id="<?php echo esc_attr( $key ); ?>" name="sm_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $val ); ?>" class="sm-color-picker" data-default-color="<?php echo esc_attr( $data[0] ); ?>"></td>
			</tr>
			<?php
		endforeach;
	endforeach;
}

/**
 * Tipografias tab.
 */
function sm_tab_tipografia() {
	$font_heading = sm_get_option( 'sm_font_heading', 'Be Vietnam Pro' );
	$font_body    = sm_get_option( 'sm_font_body', 'Montserrat' );
	$font_button  = sm_get_option( 'sm_font_button', 'Be Vietnam Pro' );
	$font_size    = sm_get_option( 'sm_font_size_base', 16 );
	$choices      = sm_get_font_choices();

	$font_fields = array(
		'sm_font_heading' => array( $font_heading, __( 'Fuente de titulos', 'santiago-moraes' ) ),
		'sm_font_body'    => array( $font_body, __( 'Fuente de cuerpo', 'santiago-moraes' ) ),
		'sm_font_button'  => array( $font_button, __( 'Fuente de botones', 'santiago-moraes' ) ),
	);

	foreach ( $font_fields as $key => $data ) :
		?>
		<tr>
			<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $data[1] ); ?></label></th>
			<td>
				<select id="<?php echo esc_attr( $key ); ?>" name="sm_options[<?php echo esc_attr( $key ); ?>]">
					<?php foreach ( $choices as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $data[0], $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<?php
	endforeach;
	?>
	<tr>
		<th scope="row"><label for="sm_font_size_base"><?php esc_html_e( 'Tamano base (px)', 'santiago-moraes' ); ?></label></th>
		<td>
			<input type="range" id="sm_font_size_base" name="sm_options[sm_font_size_base]" value="<?php echo esc_attr( $font_size ); ?>" min="14" max="20" step="1">
			<span id="sm_font_size_val"><?php echo esc_html( $font_size ); ?>px</span>
			<script>document.getElementById('sm_font_size_base').addEventListener('input',function(){document.getElementById('sm_font_size_val').textContent=this.value+'px';});</script>
		</td>
	</tr>
	<?php
}

/**
 * Hero tab.
 */
function sm_tab_hero() {
	$line1     = sm_get_option( 'sm_hero_line1', 'Santiago' );
	$line2     = sm_get_option( 'sm_hero_line2', 'Moraes' );
	$hero_img  = sm_get_option( 'sm_hero_image', '' );
	$btn1_text = sm_get_option( 'sm_hero_btn1_text', 'Escuchar ahora' );
	$btn1_url  = sm_get_option( 'sm_hero_btn1_url', '' );
	$btn2_text = sm_get_option( 'sm_hero_btn2_text', 'Proximos Shows' );
	$btn2_url  = sm_get_option( 'sm_hero_btn2_url', '#shows' );
	?>
	<tr>
		<th scope="row"><label for="sm_hero_line1"><?php esc_html_e( 'Hero titulo linea 1', 'santiago-moraes' ); ?></label></th>
		<td><input type="text" id="sm_hero_line1" name="sm_options[sm_hero_line1]" value="<?php echo esc_attr( $line1 ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_hero_line2"><?php esc_html_e( 'Hero titulo linea 2 (acento)', 'santiago-moraes' ); ?></label></th>
		<td><input type="text" id="sm_hero_line2" name="sm_options[sm_hero_line2]" value="<?php echo esc_attr( $line2 ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Hero imagen', 'santiago-moraes' ); ?></th>
		<td>
			<input type="hidden" name="sm_options[sm_hero_image]" value="<?php echo esc_url( $hero_img ); ?>" class="sm-upload-input">
			<button type="button" class="button sm-upload-btn"><?php esc_html_e( 'Seleccionar imagen', 'santiago-moraes' ); ?></button>
			<button type="button" class="button sm-remove-btn"><?php esc_html_e( 'Quitar', 'santiago-moraes' ); ?></button>
			<div class="sm-upload-preview">
				<?php if ( $hero_img ) : ?>
					<img src="<?php echo esc_url( $hero_img ); ?>" style="max-width:200px;height:auto;margin-top:8px;">
				<?php endif; ?>
			</div>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_hero_btn1_text"><?php esc_html_e( 'Boton primario texto', 'santiago-moraes' ); ?></label></th>
		<td><input type="text" id="sm_hero_btn1_text" name="sm_options[sm_hero_btn1_text]" value="<?php echo esc_attr( $btn1_text ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_hero_btn1_url"><?php esc_html_e( 'Boton primario URL', 'santiago-moraes' ); ?></label></th>
		<td><input type="url" id="sm_hero_btn1_url" name="sm_options[sm_hero_btn1_url]" value="<?php echo esc_url( $btn1_url ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_hero_btn2_text"><?php esc_html_e( 'Boton secundario texto', 'santiago-moraes' ); ?></label></th>
		<td><input type="text" id="sm_hero_btn2_text" name="sm_options[sm_hero_btn2_text]" value="<?php echo esc_attr( $btn2_text ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_hero_btn2_url"><?php esc_html_e( 'Boton secundario URL', 'santiago-moraes' ); ?></label></th>
		<td><input type="text" id="sm_hero_btn2_url" name="sm_options[sm_hero_btn2_url]" value="<?php echo esc_attr( $btn2_url ); ?>" class="regular-text"></td>
	</tr>
	<?php
}

/**
 * Musica tab.
 */
function sm_tab_musica() {
	$album_id    = sm_get_option( 'sm_featured_album_id', 0 );
	$enabled     = sm_get_option( 'sm_player_enabled', true );
	$homepage    = sm_get_option( 'sm_player_homepage', true );
	$spotify_url = sm_get_option( 'sm_player_spotify_url', '' );
	$choices     = sm_get_album_choices();
	?>
	<tr>
		<th scope="row"><label for="sm_featured_album_id"><?php esc_html_e( 'Album Destacado', 'santiago-moraes' ); ?></label></th>
		<td>
			<select id="sm_featured_album_id" name="sm_options[sm_featured_album_id]">
				<?php foreach ( $choices as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $album_id, $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Mostrar sticky player', 'santiago-moraes' ); ?></th>
		<td><label><input type="checkbox" name="sm_options[sm_player_enabled]" value="1" <?php checked( $enabled ); ?>> <?php esc_html_e( 'Activar', 'santiago-moraes' ); ?></label></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Player en homepage (embed grande)', 'santiago-moraes' ); ?></th>
		<td><label><input type="checkbox" name="sm_options[sm_player_homepage]" value="1" <?php checked( $homepage ); ?>> <?php esc_html_e( 'Activar', 'santiago-moraes' ); ?></label></td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_player_spotify_url"><?php esc_html_e( 'Spotify URL del player', 'santiago-moraes' ); ?></label></th>
		<td>
			<input type="url" id="sm_player_spotify_url" name="sm_options[sm_player_spotify_url]" value="<?php echo esc_url( $spotify_url ); ?>" class="regular-text">
			<p class="description"><?php esc_html_e( 'Album, playlist, track o artista. Vacio = usa el album destacado.', 'santiago-moraes' ); ?></p>
		</td>
	</tr>
	<?php
}

/**
 * Redes Sociales tab.
 */
function sm_tab_redes() {
	$social = array(
		'sm_social_spotify'    => 'Spotify URL',
		'sm_social_instagram'  => 'Instagram URL',
		'sm_social_youtube'    => 'YouTube URL',
		'sm_social_bandcamp'   => 'Bandcamp URL',
		'sm_social_soundcloud' => 'SoundCloud URL',
		'sm_social_facebook'   => 'Facebook URL',
		'sm_social_twitter'    => 'Twitter/X URL',
	);

	foreach ( $social as $key => $label ) :
		$val = sm_get_option( $key, '' );
		?>
		<tr>
			<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
			<td><input type="url" id="<?php echo esc_attr( $key ); ?>" name="sm_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_url( $val ); ?>" class="regular-text"></td>
		</tr>
		<?php
	endforeach;
}

/**
 * Contacto tab.
 */
function sm_tab_contacto() {
	$email   = sm_get_option( 'sm_contact_email', '' );
	$phone   = sm_get_option( 'sm_contact_phone', '' );
	$address = sm_get_option( 'sm_contact_address', '' );
	$maps    = sm_get_option( 'sm_contact_maps_url', '' );
	?>
	<tr>
		<th scope="row"><label for="sm_contact_email"><?php esc_html_e( 'Email de contacto', 'santiago-moraes' ); ?></label></th>
		<td><input type="email" id="sm_contact_email" name="sm_options[sm_contact_email]" value="<?php echo esc_attr( $email ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_contact_phone"><?php esc_html_e( 'Telefono', 'santiago-moraes' ); ?></label></th>
		<td><input type="text" id="sm_contact_phone" name="sm_options[sm_contact_phone]" value="<?php echo esc_attr( $phone ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_contact_address"><?php esc_html_e( 'Direccion', 'santiago-moraes' ); ?></label></th>
		<td><input type="text" id="sm_contact_address" name="sm_options[sm_contact_address]" value="<?php echo esc_attr( $address ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_contact_maps_url"><?php esc_html_e( 'Google Maps embed URL', 'santiago-moraes' ); ?></label></th>
		<td><input type="url" id="sm_contact_maps_url" name="sm_options[sm_contact_maps_url]" value="<?php echo esc_url( $maps ); ?>" class="regular-text"></td>
	</tr>
	<?php
}

/**
 * Footer tab.
 */
function sm_tab_footer() {
	$copyright  = sm_get_option( 'sm_footer_copyright', '' );
	$credits    = sm_get_option( 'sm_footer_credits', 'Designed with FeeloLab' );
	$scroll_top = sm_get_option( 'sm_footer_scroll_top', true );
	?>
	<tr>
		<th scope="row"><label for="sm_footer_copyright"><?php esc_html_e( 'Texto de copyright', 'santiago-moraes' ); ?></label></th>
		<td><input type="text" id="sm_footer_copyright" name="sm_options[sm_footer_copyright]" value="<?php echo esc_attr( $copyright ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_footer_credits"><?php esc_html_e( 'Creditos adicionales', 'santiago-moraes' ); ?></label></th>
		<td><input type="text" id="sm_footer_credits" name="sm_options[sm_footer_credits]" value="<?php echo esc_attr( $credits ); ?>" class="regular-text"></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Mostrar scroll-to-top', 'santiago-moraes' ); ?></th>
		<td><label><input type="checkbox" name="sm_options[sm_footer_scroll_top]" value="1" <?php checked( $scroll_top ); ?>> <?php esc_html_e( 'Activar', 'santiago-moraes' ); ?></label></td>
	</tr>
	<?php
}

/**
 * Tracking tab.
 */
function sm_tab_tracking() {
	$ga_id   = sm_get_option( 'sm_ga_id', '' );
	$custom  = sm_get_option( 'sm_custom_head_code', '' );
	?>
	<tr>
		<th scope="row"><label for="sm_ga_id"><?php esc_html_e( 'Google Analytics ID', 'santiago-moraes' ); ?></label></th>
		<td>
			<input type="text" id="sm_ga_id" name="sm_options[sm_ga_id]" value="<?php echo esc_attr( $ga_id ); ?>" class="regular-text">
			<p class="description"><?php esc_html_e( 'Ej: G-XXXXXXXXXX', 'santiago-moraes' ); ?></p>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_custom_head_code"><?php esc_html_e( 'Codigo personalizado en head', 'santiago-moraes' ); ?></label></th>
		<td>
			<textarea id="sm_custom_head_code" name="sm_options[sm_custom_head_code]" rows="6" class="large-text code"><?php echo esc_textarea( $custom ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Facebook Pixel, etc. Se inserta en <head>.', 'santiago-moraes' ); ?></p>
		</td>
	</tr>
	<?php
}
