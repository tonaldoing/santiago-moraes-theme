<?php
/**
 * Custom Post Types: Evento and Cancion.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

// =========================================================================
// Register Custom Post Types
// =========================================================================

add_action( 'init', 'sm_register_post_types' );

/**
 * Register custom post types.
 */
function sm_register_post_types() {
	sm_register_evento_cpt();
	sm_register_cancion_cpt();
}

/**
 * Register the Evento (Event) post type.
 */
function sm_register_evento_cpt() {
	$labels = array(
		'name'                  => __( 'Eventos', 'santiago-moraes' ),
		'singular_name'         => __( 'Evento', 'santiago-moraes' ),
		'menu_name'             => __( 'Eventos', 'santiago-moraes' ),
		'add_new'               => __( 'Agregar Evento', 'santiago-moraes' ),
		'add_new_item'          => __( 'Agregar Nuevo Evento', 'santiago-moraes' ),
		'edit_item'             => __( 'Editar Evento', 'santiago-moraes' ),
		'new_item'              => __( 'Nuevo Evento', 'santiago-moraes' ),
		'view_item'             => __( 'Ver Evento', 'santiago-moraes' ),
		'view_items'            => __( 'Ver Eventos', 'santiago-moraes' ),
		'search_items'          => __( 'Buscar Eventos', 'santiago-moraes' ),
		'not_found'             => __( 'No se encontraron eventos', 'santiago-moraes' ),
		'not_found_in_trash'    => __( 'No hay eventos en la papelera', 'santiago-moraes' ),
		'all_items'             => __( 'Todos los Eventos', 'santiago-moraes' ),
		'archives'              => __( 'Archivo de Eventos', 'santiago-moraes' ),
		'attributes'            => __( 'Atributos del Evento', 'santiago-moraes' ),
		'featured_image'        => __( 'Imagen del Evento', 'santiago-moraes' ),
		'set_featured_image'    => __( 'Establecer imagen del evento', 'santiago-moraes' ),
		'remove_featured_image' => __( 'Eliminar imagen del evento', 'santiago-moraes' ),
	);

	register_post_type(
		'evento',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'eventos' ),
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 6,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'show_in_rest'  => true,
			'rest_base'     => 'eventos',
		)
	);
}

/**
 * Register the Cancion (Song) post type.
 */
function sm_register_cancion_cpt() {
	$labels = array(
		'name'                  => __( 'Canciones', 'santiago-moraes' ),
		'singular_name'         => __( 'Cancion', 'santiago-moraes' ),
		'menu_name'             => __( 'Canciones', 'santiago-moraes' ),
		'add_new'               => __( 'Agregar Cancion', 'santiago-moraes' ),
		'add_new_item'          => __( 'Agregar Nueva Cancion', 'santiago-moraes' ),
		'edit_item'             => __( 'Editar Cancion', 'santiago-moraes' ),
		'new_item'              => __( 'Nueva Cancion', 'santiago-moraes' ),
		'view_item'             => __( 'Ver Cancion', 'santiago-moraes' ),
		'view_items'            => __( 'Ver Canciones', 'santiago-moraes' ),
		'search_items'          => __( 'Buscar Canciones', 'santiago-moraes' ),
		'not_found'             => __( 'No se encontraron canciones', 'santiago-moraes' ),
		'not_found_in_trash'    => __( 'No hay canciones en la papelera', 'santiago-moraes' ),
		'all_items'             => __( 'Todas las Canciones', 'santiago-moraes' ),
		'archives'              => __( 'Archivo de Canciones', 'santiago-moraes' ),
		'attributes'            => __( 'Atributos de la Cancion', 'santiago-moraes' ),
		'featured_image'        => __( 'Portada de la Cancion', 'santiago-moraes' ),
		'set_featured_image'    => __( 'Establecer portada', 'santiago-moraes' ),
		'remove_featured_image' => __( 'Eliminar portada', 'santiago-moraes' ),
	);

	register_post_type(
		'cancion',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'canciones' ),
			'menu_icon'     => 'dashicons-format-audio',
			'menu_position' => 5,
			'supports'      => array( 'title', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
			'show_in_rest'  => true,
			'rest_base'     => 'canciones',
		)
	);
}

// =========================================================================
// Register Meta Fields (REST API ready)
// =========================================================================

add_action( 'init', 'sm_register_meta_fields' );

/**
 * Register post meta for cancion and evento CPTs.
 */
function sm_register_meta_fields() {

	// --- Cancion meta fields ---
	$cancion_meta = array(
		'_cancion_lyrics'         => array(
			'type'              => 'string',
			'description'       => __( 'Letra con acordes en notacion de corchetes', 'santiago-moraes' ),
			'sanitize_callback' => 'sm_sanitize_lyrics',
		),
		'_cancion_original_key'   => array(
			'type'              => 'string',
			'description'       => __( 'Tonalidad original (ej: Am, G)', 'santiago-moraes' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_cancion_album'          => array(
			'type'              => 'string',
			'description'       => __( 'Nombre del album', 'santiago-moraes' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_cancion_year'           => array(
			'type'              => 'integer',
			'description'       => __( 'Ano de lanzamiento', 'santiago-moraes' ),
			'sanitize_callback' => 'absint',
		),
		'_cancion_audio_file'     => array(
			'type'              => 'string',
			'description'       => __( 'Archivo o URL de audio', 'santiago-moraes' ),
			'sanitize_callback' => 'esc_url_raw',
		),
		'_cancion_spotify_url'    => array(
			'type'              => 'string',
			'description'       => __( 'Link de Spotify', 'santiago-moraes' ),
			'sanitize_callback' => 'esc_url_raw',
		),
		'_cancion_youtube_url'    => array(
			'type'              => 'string',
			'description'       => __( 'Link de YouTube', 'santiago-moraes' ),
			'sanitize_callback' => 'esc_url_raw',
		),
		'_cancion_soundcloud_url' => array(
			'type'              => 'string',
			'description'       => __( 'Link de SoundCloud', 'santiago-moraes' ),
			'sanitize_callback' => 'esc_url_raw',
		),
		'_cancion_capo'           => array(
			'type'              => 'integer',
			'description'       => __( 'Posicion del capo (0 = sin capo)', 'santiago-moraes' ),
			'sanitize_callback' => 'absint',
		),
		'_cancion_tempo'          => array(
			'type'              => 'string',
			'description'       => __( 'Tempo (ej: 120 BPM, Lento)', 'santiago-moraes' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
	);

	foreach ( $cancion_meta as $key => $args ) {
		register_post_meta(
			'cancion',
			$key,
			array(
				'type'              => $args['type'],
				'description'       => $args['description'],
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => $args['sanitize_callback'],
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	// --- Evento meta fields ---
	$evento_meta = array(
		'_evento_date'        => array(
			'type'              => 'string',
			'description'       => __( 'Fecha del evento (YYYY-MM-DD)', 'santiago-moraes' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_evento_time'        => array(
			'type'              => 'string',
			'description'       => __( 'Hora del evento (HH:MM)', 'santiago-moraes' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_evento_venue'       => array(
			'type'              => 'string',
			'description'       => __( 'Nombre del lugar', 'santiago-moraes' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_evento_city'        => array(
			'type'              => 'string',
			'description'       => __( 'Ciudad', 'santiago-moraes' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'_evento_ticket_link' => array(
			'type'              => 'string',
			'description'       => __( 'Link de venta de entradas', 'santiago-moraes' ),
			'sanitize_callback' => 'esc_url_raw',
		),
		'_evento_price'       => array(
			'type'              => 'string',
			'description'       => __( 'Precio o Gratis', 'santiago-moraes' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
	);

	foreach ( $evento_meta as $key => $args ) {
		register_post_meta(
			'evento',
			$key,
			array(
				'type'              => $args['type'],
				'description'       => $args['description'],
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => $args['sanitize_callback'],
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}

/**
 * Sanitize lyrics field — preserve line breaks and square brackets.
 *
 * @param string $value Raw lyrics input.
 * @return string Sanitized lyrics.
 */
function sm_sanitize_lyrics( $value ) {
	// Normalize line endings.
	$value = str_replace( "\r\n", "\n", $value );
	$value = str_replace( "\r", "\n", $value );

	// Strip tags but preserve brackets and line breaks.
	$value = wp_strip_all_tags( $value );

	return $value;
}

// =========================================================================
// Meta Boxes — Evento
// =========================================================================

add_action( 'add_meta_boxes', 'sm_add_evento_meta_boxes' );

/**
 * Add meta boxes for Evento post type.
 */
function sm_add_evento_meta_boxes() {
	add_meta_box(
		'sm_evento_details',
		__( 'Detalles del Evento', 'santiago-moraes' ),
		'sm_render_evento_meta_box',
		'evento',
		'normal',
		'high'
	);
}

/**
 * Render Evento meta box.
 *
 * @param WP_Post $post Current post object.
 */
function sm_render_evento_meta_box( $post ) {
	wp_nonce_field( 'sm_evento_meta', 'sm_evento_nonce' );

	$date        = get_post_meta( $post->ID, '_evento_date', true );
	$time        = get_post_meta( $post->ID, '_evento_time', true );
	$venue       = get_post_meta( $post->ID, '_evento_venue', true );
	$city        = get_post_meta( $post->ID, '_evento_city', true );
	$ticket_link = get_post_meta( $post->ID, '_evento_ticket_link', true );
	$price       = get_post_meta( $post->ID, '_evento_price', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="sm_evento_date"><?php esc_html_e( 'Fecha', 'santiago-moraes' ); ?></label></th>
			<td><input type="date" id="sm_evento_date" name="_evento_date" value="<?php echo esc_attr( $date ); ?>" class="regular-text"></td>
		</tr>
		<tr>
			<th><label for="sm_evento_time"><?php esc_html_e( 'Hora', 'santiago-moraes' ); ?></label></th>
			<td><input type="time" id="sm_evento_time" name="_evento_time" value="<?php echo esc_attr( $time ); ?>" class="regular-text"></td>
		</tr>
		<tr>
			<th><label for="sm_evento_venue"><?php esc_html_e( 'Lugar', 'santiago-moraes' ); ?></label></th>
			<td><input type="text" id="sm_evento_venue" name="_evento_venue" value="<?php echo esc_attr( $venue ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Ej: Ciudad de Gatos', 'santiago-moraes' ); ?>"></td>
		</tr>
		<tr>
			<th><label for="sm_evento_city"><?php esc_html_e( 'Ciudad', 'santiago-moraes' ); ?></label></th>
			<td><input type="text" id="sm_evento_city" name="_evento_city" value="<?php echo esc_attr( $city ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Ej: La Plata', 'santiago-moraes' ); ?>"></td>
		</tr>
		<tr>
			<th><label for="sm_evento_ticket_link"><?php esc_html_e( 'Link de Entradas', 'santiago-moraes' ); ?></label></th>
			<td><input type="url" id="sm_evento_ticket_link" name="_evento_ticket_link" value="<?php echo esc_url( $ticket_link ); ?>" class="regular-text" placeholder="https://..."></td>
		</tr>
		<tr>
			<th><label for="sm_evento_price"><?php esc_html_e( 'Precio', 'santiago-moraes' ); ?></label></th>
			<td><input type="text" id="sm_evento_price" name="_evento_price" value="<?php echo esc_attr( $price ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Ej: $2000 o Gratis', 'santiago-moraes' ); ?>"></td>
		</tr>
	</table>
	<?php
}

// =========================================================================
// Meta Boxes — Cancion
// =========================================================================

add_action( 'add_meta_boxes', 'sm_add_cancion_meta_boxes' );

/**
 * Add meta boxes for Cancion post type.
 */
function sm_add_cancion_meta_boxes() {
	add_meta_box(
		'sm_cancion_lyrics',
		__( 'Letra y Acordes', 'santiago-moraes' ),
		'sm_render_cancion_lyrics_box',
		'cancion',
		'normal',
		'high'
	);

	add_meta_box(
		'sm_cancion_details',
		__( 'Detalles de la Cancion', 'santiago-moraes' ),
		'sm_render_cancion_details_box',
		'cancion',
		'normal',
		'high'
	);

	add_meta_box(
		'sm_cancion_links',
		__( 'Enlaces de Streaming', 'santiago-moraes' ),
		'sm_render_cancion_links_box',
		'cancion',
		'side',
		'default'
	);
}

/**
 * Render Cancion lyrics meta box.
 *
 * @param WP_Post $post Current post object.
 */
function sm_render_cancion_lyrics_box( $post ) {
	wp_nonce_field( 'sm_cancion_meta', 'sm_cancion_nonce' );

	$lyrics = get_post_meta( $post->ID, '_cancion_lyrics', true );
	?>
	<p class="description">
		<?php esc_html_e( 'Ingresa la letra con acordes entre corchetes. Ej: [Am]Hoy te vi [G]pasar', 'santiago-moraes' ); ?>
	</p>
	<p class="description" style="margin-bottom:8px;">
		<?php esc_html_e( 'Secciones: [Intro], [Verso 1], [Coro], [Pre-Coro], [Puente], [Solo], [Outro]', 'santiago-moraes' ); ?>
	</p>
	<textarea
		id="sm_cancion_lyrics"
		name="_cancion_lyrics"
		rows="25"
		style="width:100%;font-family:monospace;font-size:14px;line-height:1.6;tab-size:4;"
		placeholder="[Intro]&#10;[Am] [G] [F] [E]&#10;&#10;[Verso 1]&#10;[Am]Hoy te vi [G]pasar por la [F]calle&#10;[Am]sin mirar a[G]tras, sin de[E]cirme nada"
	><?php echo esc_textarea( $lyrics ); ?></textarea>
	<?php
}

/**
 * Render Cancion details meta box.
 *
 * @param WP_Post $post Current post object.
 */
function sm_render_cancion_details_box( $post ) {
	$original_key = get_post_meta( $post->ID, '_cancion_original_key', true );
	$album        = get_post_meta( $post->ID, '_cancion_album', true );
	$year         = get_post_meta( $post->ID, '_cancion_year', true );
	$capo         = get_post_meta( $post->ID, '_cancion_capo', true );
	$tempo        = get_post_meta( $post->ID, '_cancion_tempo', true );
	$audio_file   = get_post_meta( $post->ID, '_cancion_audio_file', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="sm_cancion_original_key"><?php esc_html_e( 'Tonalidad Original', 'santiago-moraes' ); ?></label></th>
			<td><input type="text" id="sm_cancion_original_key" name="_cancion_original_key" value="<?php echo esc_attr( $original_key ); ?>" class="small-text" placeholder="Am"></td>
		</tr>
		<tr>
			<th><label for="sm_cancion_album"><?php esc_html_e( 'Album', 'santiago-moraes' ); ?></label></th>
			<td><input type="text" id="sm_cancion_album" name="_cancion_album" value="<?php echo esc_attr( $album ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Ej: Hogar', 'santiago-moraes' ); ?>"></td>
		</tr>
		<tr>
			<th><label for="sm_cancion_year"><?php esc_html_e( 'Ano', 'santiago-moraes' ); ?></label></th>
			<td><input type="number" id="sm_cancion_year" name="_cancion_year" value="<?php echo esc_attr( $year ); ?>" class="small-text" min="1900" max="2100" placeholder="2022"></td>
		</tr>
		<tr>
			<th><label for="sm_cancion_capo"><?php esc_html_e( 'Capo', 'santiago-moraes' ); ?></label></th>
			<td>
				<input type="number" id="sm_cancion_capo" name="_cancion_capo" value="<?php echo esc_attr( $capo ); ?>" class="small-text" min="0" max="12" placeholder="0">
				<p class="description"><?php esc_html_e( '0 = sin capo', 'santiago-moraes' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="sm_cancion_tempo"><?php esc_html_e( 'Tempo', 'santiago-moraes' ); ?></label></th>
			<td><input type="text" id="sm_cancion_tempo" name="_cancion_tempo" value="<?php echo esc_attr( $tempo ); ?>" class="small-text" placeholder="120 BPM"></td>
		</tr>
		<tr>
			<th><label for="sm_cancion_audio_file"><?php esc_html_e( 'Audio (URL)', 'santiago-moraes' ); ?></label></th>
			<td><input type="url" id="sm_cancion_audio_file" name="_cancion_audio_file" value="<?php echo esc_url( $audio_file ); ?>" class="regular-text" placeholder="https://..."></td>
		</tr>
	</table>
	<?php
}

/**
 * Render Cancion streaming links meta box (sidebar).
 *
 * @param WP_Post $post Current post object.
 */
function sm_render_cancion_links_box( $post ) {
	$spotify    = get_post_meta( $post->ID, '_cancion_spotify_url', true );
	$youtube    = get_post_meta( $post->ID, '_cancion_youtube_url', true );
	$soundcloud = get_post_meta( $post->ID, '_cancion_soundcloud_url', true );
	?>
	<p>
		<label for="sm_cancion_spotify_url"><strong>Spotify</strong></label><br>
		<input type="url" id="sm_cancion_spotify_url" name="_cancion_spotify_url"
			value="<?php echo esc_url( $spotify ); ?>" style="width:100%;" placeholder="https://open.spotify.com/...">
	</p>
	<p>
		<label for="sm_cancion_youtube_url"><strong>YouTube</strong></label><br>
		<input type="url" id="sm_cancion_youtube_url" name="_cancion_youtube_url"
			value="<?php echo esc_url( $youtube ); ?>" style="width:100%;" placeholder="https://youtube.com/...">
	</p>
	<p>
		<label for="sm_cancion_soundcloud_url"><strong>SoundCloud</strong></label><br>
		<input type="url" id="sm_cancion_soundcloud_url" name="_cancion_soundcloud_url"
			value="<?php echo esc_url( $soundcloud ); ?>" style="width:100%;" placeholder="https://soundcloud.com/...">
	</p>
	<?php
}

// =========================================================================
// Save Meta Fields
// =========================================================================

add_action( 'save_post_evento', 'sm_save_evento_meta' );

/**
 * Save Evento meta fields.
 *
 * @param int $post_id Post ID.
 */
function sm_save_evento_meta( $post_id ) {
	if ( ! isset( $_POST['sm_evento_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sm_evento_nonce'] ) ), 'sm_evento_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_fields = array(
		'_evento_date',
		'_evento_time',
		'_evento_venue',
		'_evento_city',
		'_evento_price',
	);

	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	if ( isset( $_POST['_evento_ticket_link'] ) && '' !== $_POST['_evento_ticket_link'] ) {
		update_post_meta( $post_id, '_evento_ticket_link', esc_url_raw( wp_unslash( $_POST['_evento_ticket_link'] ) ) );
	} else {
		delete_post_meta( $post_id, '_evento_ticket_link' );
	}
}

add_action( 'save_post_cancion', 'sm_save_cancion_meta' );

/**
 * Save Cancion meta fields.
 *
 * @param int $post_id Post ID.
 */
function sm_save_cancion_meta( $post_id ) {
	if ( ! isset( $_POST['sm_cancion_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sm_cancion_nonce'] ) ), 'sm_cancion_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Text fields.
	$text_fields = array(
		'_cancion_original_key',
		'_cancion_album',
		'_cancion_tempo',
	);

	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	// URL fields.
	$url_fields = array(
		'_cancion_audio_file',
		'_cancion_spotify_url',
		'_cancion_youtube_url',
		'_cancion_soundcloud_url',
	);

	foreach ( $url_fields as $key ) {
		if ( isset( $_POST[ $key ] ) && '' !== $_POST[ $key ] ) {
			update_post_meta( $post_id, $key, esc_url_raw( wp_unslash( $_POST[ $key ] ) ) );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	// Integer fields.
	$int_fields = array( '_cancion_year', '_cancion_capo' );

	foreach ( $int_fields as $key ) {
		if ( isset( $_POST[ $key ] ) && '' !== $_POST[ $key ] ) {
			update_post_meta( $post_id, $key, absint( $_POST[ $key ] ) );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	// Lyrics — preserve line breaks and square brackets, strip HTML.
	if ( isset( $_POST['_cancion_lyrics'] ) ) {
		update_post_meta( $post_id, '_cancion_lyrics', sm_sanitize_lyrics( wp_unslash( $_POST['_cancion_lyrics'] ) ) );
	}
}

// =========================================================================
// Admin Columns — Cancion
// =========================================================================

add_filter( 'manage_cancion_posts_columns', 'sm_cancion_admin_columns' );

/**
 * Set custom columns for cancion list table.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function sm_cancion_admin_columns( $columns ) {
	$new_columns = array();

	foreach ( $columns as $key => $value ) {
		$new_columns[ $key ] = $value;

		if ( 'title' === $key ) {
			$new_columns['cancion_key']   = __( 'Tonalidad', 'santiago-moraes' );
			$new_columns['cancion_album'] = __( 'Album', 'santiago-moraes' );
			$new_columns['cancion_year']  = __( 'Ano', 'santiago-moraes' );
		}
	}

	return $new_columns;
}

add_action( 'manage_cancion_posts_custom_column', 'sm_cancion_admin_column_content', 10, 2 );

/**
 * Display content for custom cancion columns.
 *
 * @param string $column  Column name.
 * @param int    $post_id Post ID.
 */
function sm_cancion_admin_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'cancion_key':
			$key = get_post_meta( $post_id, '_cancion_original_key', true );
			echo esc_html( $key ? $key : "\xE2\x80\x94" );
			break;
		case 'cancion_album':
			$album = get_post_meta( $post_id, '_cancion_album', true );
			echo esc_html( $album ? $album : "\xE2\x80\x94" );
			break;
		case 'cancion_year':
			$year = get_post_meta( $post_id, '_cancion_year', true );
			echo esc_html( $year ? $year : "\xE2\x80\x94" );
			break;
	}
}

// =========================================================================
// Admin Columns — Evento
// =========================================================================

add_filter( 'manage_evento_posts_columns', 'sm_evento_admin_columns' );

/**
 * Set custom columns for evento list table.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function sm_evento_admin_columns( $columns ) {
	$new_columns = array();

	foreach ( $columns as $key => $value ) {
		if ( 'date' === $key ) {
			continue; // Replace WP default date with our event date.
		}
		$new_columns[ $key ] = $value;

		if ( 'title' === $key ) {
			$new_columns['evento_date']  = __( 'Fecha', 'santiago-moraes' );
			$new_columns['evento_venue'] = __( 'Lugar', 'santiago-moraes' );
			$new_columns['evento_city']  = __( 'Ciudad', 'santiago-moraes' );
			$new_columns['evento_price'] = __( 'Precio', 'santiago-moraes' );
		}
	}

	return $new_columns;
}

add_action( 'manage_evento_posts_custom_column', 'sm_evento_admin_column_content', 10, 2 );

/**
 * Display content for custom evento columns.
 *
 * @param string $column  Column name.
 * @param int    $post_id Post ID.
 */
function sm_evento_admin_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'evento_date':
			$date = get_post_meta( $post_id, '_evento_date', true );
			if ( $date ) {
				$date_obj = DateTime::createFromFormat( 'Y-m-d', $date );
				echo esc_html( $date_obj ? $date_obj->format( 'd/m/Y' ) : $date );
			} else {
				echo "\xE2\x80\x94";
			}
			break;
		case 'evento_venue':
			$venue = get_post_meta( $post_id, '_evento_venue', true );
			echo esc_html( $venue ? $venue : "\xE2\x80\x94" );
			break;
		case 'evento_city':
			$city = get_post_meta( $post_id, '_evento_city', true );
			echo esc_html( $city ? $city : "\xE2\x80\x94" );
			break;
		case 'evento_price':
			$price = get_post_meta( $post_id, '_evento_price', true );
			echo esc_html( $price ? $price : "\xE2\x80\x94" );
			break;
	}
}

// Make evento date column sortable.
add_filter( 'manage_edit-evento_sortable_columns', 'sm_evento_sortable_columns' );

/**
 * Make evento_date column sortable.
 *
 * @param array $columns Sortable columns.
 * @return array Modified sortable columns.
 */
function sm_evento_sortable_columns( $columns ) {
	$columns['evento_date'] = '_evento_date';
	return $columns;
}

add_action( 'pre_get_posts', 'sm_evento_orderby_date' );

/**
 * Handle sorting by evento date in admin.
 *
 * @param WP_Query $query The query object.
 */
function sm_evento_orderby_date( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'evento' !== $query->get( 'post_type' ) ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	if ( '_evento_date' === $orderby ) {
		$query->set( 'meta_key', '_evento_date' );
		$query->set( 'orderby', 'meta_value' );
	}
}
