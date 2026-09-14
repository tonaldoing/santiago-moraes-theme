<?php
/**
 * Theme Options admin page (Apariencia > Santiago Moraes).
 *
 * Every field lives once in sm_theme_options_schema(); sanitization, the
 * hidden inputs that preserve other tabs on save, and the form markup are all
 * derived from it. To add an option, add one entry to the schema.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

// =====================================================================
// Schema.
// =====================================================================

/**
 * Tabs and fields.
 *
 * Field keys:
 *   type        text | url | link | email | number | range | textarea | code | checkbox | radio | select | color | image | heading | note
 *               ("link" is a text field that accepts anchors like "#shows"; "code" is stored unfiltered, admin-only)
 *   label       Row label.
 *   default     Value shown when nothing is stored (also passed to sm_get_option by templates).
 *   description Help text under the control.
 *   choices     radio/select: value => label (or a callable returning that array).
 *   class       Input CSS class (regular-text, large-text, small-text…).
 *   placeholder / min / max / step / rows
 *   sanitize    Optional callable overriding the type's sanitizer.
 *
 * @return array
 */
function sm_theme_options_schema() {
	return array(
		'general'  => array(
			'label'  => __( 'General', 'santiago-moraes' ),
			'fields' => array(
				'sm_logo_type'         => array(
					'type'    => 'radio',
					'label'   => __( 'Logo tipo', 'santiago-moraes' ),
					'default' => 'text',
					'choices' => array(
						'text'  => __( 'Texto', 'santiago-moraes' ),
						'image' => __( 'Imagen', 'santiago-moraes' ),
					),
				),
				'sm_logo_text'         => array(
					'type'    => 'text',
					'label'   => __( 'Logo texto', 'santiago-moraes' ),
					'default' => 'Santiago Moraes',
				),
				'sm_logo_image'        => array(
					'type'  => 'image',
					'label' => __( 'Logo imagen', 'santiago-moraes' ),
				),
				'sm_header_height'     => array(
					'type'    => 'range',
					'label'   => __( 'Altura del header (px)', 'santiago-moraes' ),
					'default' => 90,
					'min'     => 60,
					'max'     => 120,
					'step'    => 5,
					'unit'    => 'px',
				),
				'_announcement'        => array(
					'type'  => 'heading',
					'label' => __( 'Barra de anuncio (homepage)', 'santiago-moraes' ),
				),
				'sm_announcement_text' => array(
					'type'        => 'text',
					'label'       => __( 'Texto del anuncio', 'santiago-moraes' ),
					'class'       => 'large-text',
					'description' => __( 'Ej: "Nuevo disco: Las siete menos diez — Ya disponible". Dejalo vacio para ocultar la barra.', 'santiago-moraes' ),
				),
				'sm_announcement_url'  => array(
					'type'        => 'url',
					'label'       => __( 'Link del anuncio (opcional)', 'santiago-moraes' ),
					'description' => __( 'Si tiene link, toda la barra es clickeable.', 'santiago-moraes' ),
				),
			),
		),

		'colores'  => array(
			'label'  => __( 'Colores', 'santiago-moraes' ),
			'fields' => array(
				'sm_color_ink'         => array( 'type' => 'color', 'default' => '#1F3C57', 'label' => __( 'Azul tinta (textos, header, bordes)', 'santiago-moraes' ) ),
				'sm_color_paper'       => array( 'type' => 'color', 'default' => '#E9DCC6', 'label' => __( 'Papel (fondo principal)', 'santiago-moraes' ) ),
				'sm_color_ochre'       => array( 'type' => 'color', 'default' => '#E08B3E', 'label' => __( 'Ocre (acentos, hero, hover)', 'santiago-moraes' ) ),
				'sm_color_brick'       => array( 'type' => 'color', 'default' => '#A8341C', 'label' => __( 'Ladrillo (links, CTA, tags)', 'santiago-moraes' ) ),
				'sm_color_cream'       => array( 'type' => 'color', 'default' => '#F4E9D6', 'label' => __( 'Crema (texto sobre oscuro)', 'santiago-moraes' ) ),
				'sm_color_warm'        => array( 'type' => 'color', 'default' => '#D9CBB2', 'label' => __( 'Gris calido (fondos secundarios)', 'santiago-moraes' ) ),
				'sm_color_muted'       => array( 'type' => 'color', 'default' => '#C9B896', 'label' => __( 'Beige apagado (bordes suaves)', 'santiago-moraes' ) ),
				'sm_color_brown'       => array( 'type' => 'color', 'default' => '#3A2A1C', 'label' => __( 'Marron (texto cuerpo)', 'santiago-moraes' ) ),
				'sm_color_olive'       => array( 'type' => 'color', 'default' => '#6B573C', 'label' => __( 'Oliva (texto secundario, metadata)', 'santiago-moraes' ) ),
				'sm_color_footer_text' => array( 'type' => 'color', 'default' => '#C6B79C', 'label' => __( 'Texto del footer', 'santiago-moraes' ) ),
			),
		),

		'hero'     => array(
			'label'  => __( 'Hero', 'santiago-moraes' ),
			'fields' => array(
				'sm_hero_line1'     => array( 'type' => 'text', 'label' => __( 'Titulo linea 1', 'santiago-moraes' ), 'default' => 'Santiago' ),
				'sm_hero_line2'     => array( 'type' => 'text', 'label' => __( 'Titulo linea 2 (outline)', 'santiago-moraes' ), 'default' => 'Moraes' ),
				'sm_hero_tag'       => array(
					'type'        => 'text',
					'label'       => __( 'Etiqueta (debajo del nombre)', 'santiago-moraes' ),
					'default'     => __( 'Letras y acordes de todas las canciones', 'santiago-moraes' ),
					'description' => __( 'Ej: "Letras y acordes de todas las canciones"', 'santiago-moraes' ),
				),
				'sm_hero_btn1_text' => array( 'type' => 'text', 'label' => __( 'Boton primario texto', 'santiago-moraes' ), 'default' => __( 'Cancionero', 'santiago-moraes' ) ),
				'sm_hero_btn1_url'  => array( 'type' => 'link', 'label' => __( 'Boton primario URL', 'santiago-moraes' ), 'description' => __( 'Vacio = pagina del Cancionero.', 'santiago-moraes' ) ),
				'sm_hero_btn2_text' => array( 'type' => 'text', 'label' => __( 'Boton secundario texto', 'santiago-moraes' ), 'default' => __( 'Escuchar', 'santiago-moraes' ) ),
				'sm_hero_btn2_url'  => array( 'type' => 'link', 'label' => __( 'Boton secundario URL', 'santiago-moraes' ), 'description' => __( 'Vacio = Spotify del artista. Acepta anclas como #shows.', 'santiago-moraes' ) ),
				'sm_hero_image'     => array(
					'type'        => 'image',
					'label'       => __( 'Imagen para redes (og:image)', 'santiago-moraes' ),
					'description' => __( 'Imagen que se usa al compartir la home en redes. Si esta vacia, usa la imagen por defecto del tema.', 'santiago-moraes' ),
				),
			),
		),

		'musica'   => array(
			'label'  => __( 'Musica', 'santiago-moraes' ),
			'fields' => array(
				'sm_featured_album_id'  => array(
					'type'    => 'select',
					'label'   => __( 'Album destacado', 'santiago-moraes' ),
					'default' => 0,
					'choices' => 'sm_get_album_choices',
				),
				'_shop'                 => array( 'type' => 'heading', 'label' => __( 'Tienda (widget en la home)', 'santiago-moraes' ) ),
				'sm_shop_url'           => array(
					'type'        => 'url',
					'label'       => __( 'URL de la tienda', 'santiago-moraes' ),
					'description' => __( 'Vacio = usa el link de vinilo del primer album que lo tenga cargado. Si ningun album lo tiene, el widget no se muestra.', 'santiago-moraes' ),
				),
				'sm_shop_label'         => array( 'type' => 'text', 'label' => __( 'Texto del boton', 'santiago-moraes' ), 'placeholder' => 'Vinilo' ),
				'sm_shop_text'          => array( 'type' => 'text', 'label' => __( 'Texto descriptivo', 'santiago-moraes' ), 'placeholder' => 'Discos físicos y merch.' ),
				'_player'               => array( 'type' => 'heading', 'label' => __( 'Reproductor Spotify (deshabilitado por ahora)', 'santiago-moraes' ) ),
				'sm_player_enabled'     => array( 'type' => 'checkbox', 'label' => __( 'Mostrar sticky player', 'santiago-moraes' ), 'default' => true ),
				'sm_player_homepage'    => array( 'type' => 'checkbox', 'label' => __( 'Player en homepage (embed grande)', 'santiago-moraes' ), 'default' => true ),
				'sm_player_spotify_url' => array(
					'type'        => 'url',
					'label'       => __( 'Spotify URL del player', 'santiago-moraes' ),
					'description' => __( 'Album, playlist, track o artista. Vacio = usa el album destacado.', 'santiago-moraes' ),
				),
			),
		),

		'shows'    => array(
			'label'  => __( 'Shows', 'santiago-moraes' ),
			'fields' => array(
				'sm_bandsintown_artist' => array(
					'type'        => 'text',
					'label'       => __( 'Artista en Bandsintown', 'santiago-moraes' ),
					'default'     => SM_BANDSINTOWN_DEFAULT_ARTIST,
					'description' => __( 'Formato "id_NUMERO" (recomendado, tomado de la URL bandsintown.com/a/NUMERO) o el nombre exacto del artista.', 'santiago-moraes' ),
				),
				'sm_shows_limit'        => array(
					'type'    => 'number',
					'label'   => __( 'Cantidad de shows en la home', 'santiago-moraes' ),
					'default' => 5,
					'min'     => 1,
					'max'     => 20,
					'class'   => 'small-text',
				),
				'_shows_cache'          => array(
					'type'  => 'note',
					'label' => __( 'Cache', 'santiago-moraes' ),
					'text'  => __( 'Las fechas se actualizan cada 6 horas. Guardar esta pagina fuerza una actualizacion inmediata.', 'santiago-moraes' ),
				),
			),
		),

		'redes'    => array(
			'label'  => __( 'Redes Sociales', 'santiago-moraes' ),
			'fields' => array(
				'sm_social_spotify'    => array( 'type' => 'url', 'label' => 'Spotify URL' ),
				'sm_social_instagram'  => array( 'type' => 'url', 'label' => 'Instagram URL' ),
				'sm_social_youtube'    => array( 'type' => 'url', 'label' => 'YouTube URL' ),
				'sm_social_bandcamp'   => array( 'type' => 'url', 'label' => 'Bandcamp URL' ),
				'sm_social_soundcloud' => array( 'type' => 'url', 'label' => 'SoundCloud URL' ),
				'sm_social_facebook'   => array( 'type' => 'url', 'label' => 'Facebook URL' ),
				'sm_social_twitter'    => array( 'type' => 'url', 'label' => 'Twitter/X URL' ),
			),
		),

		'contacto' => array(
			'label'  => __( 'Contacto', 'santiago-moraes' ),
			'fields' => array(
				'sm_contact_email'    => array( 'type' => 'email', 'label' => __( 'Email de contacto', 'santiago-moraes' ) ),
				'sm_contact_phone'    => array( 'type' => 'text', 'label' => __( 'Telefono', 'santiago-moraes' ) ),
				'sm_contact_address'  => array( 'type' => 'text', 'label' => __( 'Direccion', 'santiago-moraes' ) ),
				'sm_contact_maps_url' => array( 'type' => 'url', 'label' => __( 'Google Maps embed URL', 'santiago-moraes' ) ),
			),
		),

		'footer'   => array(
			'label'  => __( 'Footer', 'santiago-moraes' ),
			'fields' => array(
				'sm_footer_copyright'  => array( 'type' => 'text', 'label' => __( 'Texto de copyright', 'santiago-moraes' ) ),
				'sm_footer_credits'    => array( 'type' => 'text', 'label' => __( 'Creditos adicionales', 'santiago-moraes' ), 'default' => 'Designed with FeeloLab' ),
				'sm_footer_scroll_top' => array( 'type' => 'checkbox', 'label' => __( 'Mostrar scroll-to-top', 'santiago-moraes' ), 'default' => true ),
			),
		),

		'tracking' => array(
			'label'  => __( 'Tracking', 'santiago-moraes' ),
			'fields' => array(
				'sm_ga_id'            => array(
					'type'        => 'text',
					'label'       => __( 'Google Analytics 4 — Measurement ID', 'santiago-moraes' ),
					'placeholder' => 'G-XXXXXXXXXX',
					'sanitize'    => fn( $v ) => strtoupper( sanitize_text_field( $v ) ),
					'description' => __( 'Con el ID cargado se envian pageviews y eventos propios del sitio: album_click, song_open, song_transpose, song_autoscroll, song_chords_toggle, song_print, cancionero_filter, show_click, shop_click, platform_click, contact_submit. Los usuarios logueados con permiso de edicion no se rastrean.', 'santiago-moraes' ),
				),
				'sm_gsc_verification' => array(
					'type'        => 'text',
					'label'       => __( 'Google Search Console — codigo de verificacion', 'santiago-moraes' ),
					'description' => __( 'Solo el valor de "content" de la etiqueta meta google-site-verification que da Search Console.', 'santiago-moraes' ),
				),
				'sm_custom_head_code' => array(
					'type'        => 'code',
					'label'       => __( 'Codigo personalizado en head', 'santiago-moraes' ),
					'rows'        => 6,
					'description' => __( 'Facebook Pixel, etc. Se inserta en <head>.', 'santiago-moraes' ),
				),
			),
		),
	);
}

/**
 * Flat key => field map of every stored option (headings and notes excluded).
 *
 * @return array
 */
function sm_theme_options_fields() {
	static $fields = null;

	if ( null === $fields ) {
		$fields = array();
		foreach ( sm_theme_options_schema() as $tab ) {
			foreach ( $tab['fields'] as $key => $field ) {
				if ( ! in_array( $field['type'], array( 'heading', 'note' ), true ) ) {
					$fields[ $key ] = $field;
				}
			}
		}
	}

	return $fields;
}

/**
 * Choices for the featured album dropdown.
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
			$year = get_term_meta( $album->term_id, '_album_year', true );
			$choices[ $album->term_id ] = $album->name . ( $year ? ' (' . $year . ')' : '' );
		}
	}

	return $choices;
}

// =====================================================================
// Registration + sanitization.
// =====================================================================

add_action( 'admin_menu', 'sm_add_theme_options_page' );

/**
 * Add the page under Apariencia.
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

add_action( 'admin_init', 'sm_register_theme_options' );

/**
 * Register the single sm_options setting.
 */
function sm_register_theme_options() {
	register_setting( 'sm_options_group', 'sm_options', array(
		'sanitize_callback' => 'sm_sanitize_options',
	) );
}

add_action( 'update_option_sm_options', 'sm_purge_cache_on_save' );

/**
 * Purge LiteSpeed Cache and the object cache when options are saved.
 */
function sm_purge_cache_on_save() {
	if ( class_exists( 'LiteSpeed\Purge' ) ) {
		do_action( 'litespeed_purge_all' );
	}
	wp_cache_flush();
}

/**
 * Sanitize one value according to its field type.
 *
 * @param mixed $value Raw value.
 * @param array $field Schema entry.
 * @return mixed
 */
function sm_sanitize_field( $value, $field ) {
	if ( ! empty( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
		return call_user_func( $field['sanitize'], $value );
	}

	switch ( $field['type'] ) {
		case 'checkbox':
			return ! empty( $value );
		case 'number':
		case 'range':
			if ( '' === $value || null === $value ) {
				return ''; // Keep empty so templates fall back to their own default.
			}
			$n = absint( $value );
			if ( isset( $field['min'] ) ) {
				$n = max( (int) $field['min'], $n );
			}
			if ( isset( $field['max'] ) ) {
				$n = min( (int) $field['max'], $n );
			}
			return $n;
		case 'select':
			$choices = is_callable( $field['choices'] ) ? call_user_func( $field['choices'] ) : $field['choices'];
			return array_key_exists( $value, $choices ) ? ( is_int( array_key_first( $choices ) ) ? absint( $value ) : $value ) : ( $field['default'] ?? '' );
		case 'radio':
			return array_key_exists( $value, $field['choices'] ) ? $value : ( $field['default'] ?? '' );
		case 'color':
			return (string) sanitize_hex_color( $value );
		case 'url':
		case 'image':
			return esc_url_raw( $value );
		case 'email':
			return sanitize_email( $value );
		case 'textarea':
			return sanitize_textarea_field( $value );
		case 'code':
			return (string) $value; // Admin-controlled raw HTML (tracking snippets).
		default:
			return sanitize_text_field( $value );
	}
}

/**
 * Sanitize the whole options array on save.
 *
 * @param array $input Raw form values.
 * @return array
 */
function sm_sanitize_options( $input ) {
	$clean = array();

	if ( ! is_array( $input ) ) {
		return $clean;
	}

	foreach ( sm_theme_options_fields() as $key => $field ) {
		$raw = $input[ $key ] ?? ( 'checkbox' === $field['type'] ? false : '' );
		if ( is_string( $raw ) ) {
			$raw = wp_unslash( $raw );
		}
		$clean[ $key ] = sm_sanitize_field( $raw, $field );
	}

	return $clean;
}

// =====================================================================
// Admin assets.
// =====================================================================

add_action( 'admin_enqueue_scripts', 'sm_theme_options_enqueue' );

/**
 * Color picker, media uploader and small helpers, on our page only.
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

	$js = <<<'JS'
jQuery(function ($) {
	$('.sm-color-picker').wpColorPicker();

	$('.sm-upload-btn').on('click', function (e) {
		e.preventDefault();
		var wrap = $(this).closest('.sm-image-field');
		var frame = wp.media({ title: 'Seleccionar imagen', button: { text: 'Usar imagen' }, multiple: false });
		frame.on('select', function () {
			var url = frame.state().get('selection').first().toJSON().url;
			wrap.find('.sm-upload-input').val(url);
			wrap.find('.sm-upload-preview').html('<img src="' + url + '" style="max-width:200px;height:auto;margin-top:8px;">');
		});
		frame.open();
	});

	$('.sm-remove-btn').on('click', function (e) {
		e.preventDefault();
		var wrap = $(this).closest('.sm-image-field');
		wrap.find('.sm-upload-input').val('');
		wrap.find('.sm-upload-preview').empty();
	});

	$('.sm-range').on('input', function () {
		$(this).next('.sm-range-value').text(this.value + ($(this).data('unit') || ''));
	});
});
JS;
	wp_add_inline_script( 'wp-color-picker', $js );
}

// =====================================================================
// Page.
// =====================================================================

/**
 * Render the tabbed options page.
 */
function sm_render_theme_options_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$schema = sm_theme_options_schema();

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';
	if ( ! array_key_exists( $active_tab, $schema ) ) {
		$active_tab = 'general';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Santiago Moraes — Opciones del Tema', 'santiago-moraes' ); ?></h1>

		<?php settings_errors( 'sm_options' ); ?>

		<h2 class="nav-tab-wrapper">
			<?php foreach ( $schema as $slug => $tab ) : ?>
				<a href="<?php echo esc_url( add_query_arg( 'tab', $slug, admin_url( 'themes.php?page=sm-theme-options' ) ) ); ?>"
				   class="nav-tab <?php echo $active_tab === $slug ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html( $tab['label'] ); ?>
				</a>
			<?php endforeach; ?>
		</h2>

		<form method="post" action="options.php">
			<?php
			settings_fields( 'sm_options_group' );
			sm_render_hidden_fields( $active_tab );
			?>

			<table class="form-table" role="presentation">
				<?php
				foreach ( $schema[ $active_tab ]['fields'] as $key => $field ) {
					sm_render_field( $key, $field );
				}
				?>
			</table>

			<?php submit_button( __( 'Guardar Cambios', 'santiago-moraes' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * Hidden inputs for every field outside the active tab, so saving one tab
 * never wipes the others.
 *
 * @param string $active_tab Tab slug being edited.
 */
function sm_render_hidden_fields( $active_tab ) {
	foreach ( sm_theme_options_schema() as $slug => $tab ) {
		if ( $slug === $active_tab ) {
			continue;
		}
		foreach ( $tab['fields'] as $key => $field ) {
			if ( in_array( $field['type'], array( 'heading', 'note' ), true ) ) {
				continue;
			}
			$value = sm_get_option( $key, $field['default'] ?? '' );
			if ( 'checkbox' === $field['type'] ) {
				$value = $value ? '1' : '';
			}
			echo '<input type="hidden" name="sm_options[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '">';
		}
	}
}

/**
 * Render one form-table row for a schema field.
 *
 * @param string $key   Option key.
 * @param array  $field Schema entry.
 */
function sm_render_field( $key, $field ) {
	$type = $field['type'];

	if ( 'heading' === $type ) {
		echo '<tr><td colspan="2"><h3 style="margin:24px 0 6px;font-size:14px;font-weight:600;color:#1d2327;border-bottom:1px solid #c3c4c7;padding-bottom:6px;">' . esc_html( $field['label'] ) . '</h3></td></tr>';
		return;
	}

	if ( 'note' === $type ) {
		echo '<tr><th scope="row">' . esc_html( $field['label'] ) . '</th><td><p class="description">' . esc_html( $field['text'] ) . '</p></td></tr>';
		return;
	}

	$name    = 'sm_options[' . $key . ']';
	$value   = sm_get_option( $key, $field['default'] ?? '' );
	$class   = $field['class'] ?? 'regular-text';
	$has_for = ! in_array( $type, array( 'radio', 'checkbox', 'image' ), true );
	?>
	<tr>
		<th scope="row">
			<?php if ( $has_for ) : ?>
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
			<?php else : ?>
				<?php echo esc_html( $field['label'] ); ?>
			<?php endif; ?>
		</th>
		<td>
			<?php
			switch ( $type ) {
				case 'checkbox':
					?>
					<label><input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( (bool) $value ); ?>> <?php esc_html_e( 'Activar', 'santiago-moraes' ); ?></label>
					<?php
					break;

				case 'radio':
					foreach ( $field['choices'] as $val => $label ) :
						?>
						<label><input type="radio" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $val ); ?>" <?php checked( $value, $val ); ?>> <?php echo esc_html( $label ); ?></label><br>
						<?php
					endforeach;
					break;

				case 'select':
					$choices = is_callable( $field['choices'] ) ? call_user_func( $field['choices'] ) : $field['choices'];
					?>
					<select id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>">
						<?php foreach ( $choices as $val => $label ) : ?>
							<option value="<?php echo esc_attr( $val ); ?>" <?php selected( (string) $value, (string) $val ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php
					break;

				case 'color':
					?>
					<input type="text" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="sm-color-picker" data-default-color="<?php echo esc_attr( $field['default'] ?? '' ); ?>">
					<?php
					break;

				case 'image':
					?>
					<div class="sm-image-field">
						<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_url( $value ); ?>" class="sm-upload-input">
						<button type="button" class="button sm-upload-btn"><?php esc_html_e( 'Seleccionar imagen', 'santiago-moraes' ); ?></button>
						<button type="button" class="button sm-remove-btn"><?php esc_html_e( 'Quitar', 'santiago-moraes' ); ?></button>
						<div class="sm-upload-preview">
							<?php if ( $value ) : ?>
								<img src="<?php echo esc_url( $value ); ?>" style="max-width:200px;height:auto;margin-top:8px;">
							<?php endif; ?>
						</div>
					</div>
					<?php
					break;

				case 'range':
					?>
					<input type="range" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" min="<?php echo esc_attr( $field['min'] ); ?>" max="<?php echo esc_attr( $field['max'] ); ?>" step="<?php echo esc_attr( $field['step'] ?? 1 ); ?>" class="sm-range" data-unit="<?php echo esc_attr( $field['unit'] ?? '' ); ?>">
					<span class="sm-range-value"><?php echo esc_html( $value . ( $field['unit'] ?? '' ) ); ?></span>
					<?php
					break;

				case 'textarea':
				case 'code':
					?>
					<textarea id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="<?php echo esc_attr( $field['rows'] ?? 4 ); ?>" class="large-text<?php echo 'code' === $type ? ' code' : ''; ?>"><?php echo esc_textarea( $value ); ?></textarea>
					<?php
					break;

				default:
					$input_type = in_array( $type, array( 'url', 'email', 'number' ), true ) ? $type : 'text';
					?>
					<input type="<?php echo esc_attr( $input_type ); ?>" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $class ); ?>"
						<?php echo isset( $field['placeholder'] ) ? 'placeholder="' . esc_attr( $field['placeholder'] ) . '"' : ''; ?>
						<?php echo isset( $field['min'] ) ? 'min="' . esc_attr( $field['min'] ) . '"' : ''; ?>
						<?php echo isset( $field['max'] ) ? 'max="' . esc_attr( $field['max'] ) . '"' : ''; ?>>
					<?php
			}

			if ( ! empty( $field['description'] ) ) {
				echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
			}
			?>
		</td>
	</tr>
	<?php
}
