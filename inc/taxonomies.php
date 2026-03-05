<?php
/**
 * Custom Taxonomies: Genero and Album.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'sm_register_taxonomies' );

/**
 * Register custom taxonomies for the Cancion post type.
 */
function sm_register_taxonomies() {

	// --- Genero (Genre) ---
	register_taxonomy(
		'genero',
		'cancion',
		array(
			'labels'            => array(
				'name'                       => __( 'Generos', 'santiago-moraes' ),
				'singular_name'              => __( 'Genero', 'santiago-moraes' ),
				'search_items'               => __( 'Buscar Generos', 'santiago-moraes' ),
				'popular_items'              => __( 'Generos Populares', 'santiago-moraes' ),
				'all_items'                  => __( 'Todos los Generos', 'santiago-moraes' ),
				'edit_item'                  => __( 'Editar Genero', 'santiago-moraes' ),
				'update_item'                => __( 'Actualizar Genero', 'santiago-moraes' ),
				'add_new_item'               => __( 'Agregar Genero', 'santiago-moraes' ),
				'new_item_name'              => __( 'Nuevo Genero', 'santiago-moraes' ),
				'separate_items_with_commas' => __( 'Separar generos con comas', 'santiago-moraes' ),
				'add_or_remove_items'        => __( 'Agregar o quitar generos', 'santiago-moraes' ),
				'choose_from_most_used'      => __( 'Elegir de los generos mas usados', 'santiago-moraes' ),
				'not_found'                  => __( 'No se encontraron generos', 'santiago-moraes' ),
				'menu_name'                  => __( 'Generos', 'santiago-moraes' ),
			),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'genero' ),
		)
	);

	// --- Album ---
	register_taxonomy(
		'album',
		'cancion',
		array(
			'labels'            => array(
				'name'                       => __( 'Albums', 'santiago-moraes' ),
				'singular_name'              => __( 'Album', 'santiago-moraes' ),
				'search_items'               => __( 'Buscar Albums', 'santiago-moraes' ),
				'popular_items'              => __( 'Albums Populares', 'santiago-moraes' ),
				'all_items'                  => __( 'Todos los Albums', 'santiago-moraes' ),
				'edit_item'                  => __( 'Editar Album', 'santiago-moraes' ),
				'update_item'                => __( 'Actualizar Album', 'santiago-moraes' ),
				'add_new_item'               => __( 'Agregar Album', 'santiago-moraes' ),
				'new_item_name'              => __( 'Nuevo Album', 'santiago-moraes' ),
				'separate_items_with_commas' => __( 'Separar albums con comas', 'santiago-moraes' ),
				'add_or_remove_items'        => __( 'Agregar o quitar albums', 'santiago-moraes' ),
				'choose_from_most_used'      => __( 'Elegir de los albums mas usados', 'santiago-moraes' ),
				'not_found'                  => __( 'No se encontraron albums', 'santiago-moraes' ),
				'menu_name'                  => __( 'Albums', 'santiago-moraes' ),
			),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'album' ),
		)
	);
}

// =========================================================================
// Album term meta — cover image, year, description, streaming links.
// =========================================================================

add_action( 'init', 'sm_register_album_term_meta' );

/**
 * Register term meta fields for the album taxonomy.
 */
function sm_register_album_term_meta() {
	$meta_fields = array(
		'_album_cover_id'      => 'absint',
		'_album_year'          => 'absint',
		'_album_description'   => 'sanitize_textarea_field',
		'_album_is_demo'       => 'absint',
		'_album_spotify_url'   => 'esc_url_raw',
		'_album_bandcamp_url'  => 'esc_url_raw',
		'_album_youtube_url'   => 'esc_url_raw',
		'_album_vinyl_url'     => 'esc_url_raw',
	);

	foreach ( $meta_fields as $key => $sanitize ) {
		register_term_meta(
			'album',
			$key,
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => $sanitize,
			)
		);
	}
}

// =========================================================================
// Album edit form fields (admin UI).
// =========================================================================

add_action( 'album_add_form_fields', 'sm_album_add_form_fields' );
add_action( 'album_edit_form_fields', 'sm_album_edit_form_fields', 10, 2 );
add_action( 'created_album', 'sm_save_album_term_meta' );
add_action( 'edited_album', 'sm_save_album_term_meta' );

/**
 * Add fields to the "Add Album" form.
 */
function sm_album_add_form_fields() {
	wp_nonce_field( 'sm_album_meta', 'sm_album_meta_nonce' );
	?>
	<div class="form-field">
		<label for="album-year"><?php esc_html_e( 'Ano', 'santiago-moraes' ); ?></label>
		<input type="number" name="_album_year" id="album-year" min="1900" max="2100">
	</div>
	<div class="form-field">
		<label for="album-description"><?php esc_html_e( 'Descripcion', 'santiago-moraes' ); ?></label>
		<textarea name="_album_description" id="album-description" rows="3"></textarea>
	</div>
	<div class="form-field">
		<label for="album-cover-id"><?php esc_html_e( 'ID de imagen de portada', 'santiago-moraes' ); ?></label>
		<input type="number" name="_album_cover_id" id="album-cover-id">
		<p class="description"><?php esc_html_e( 'ID de la imagen en la biblioteca de medios.', 'santiago-moraes' ); ?></p>
	</div>
	<div class="form-field">
		<label for="album-is-demo">
			<input type="checkbox" name="_album_is_demo" id="album-is-demo" value="1">
			<?php esc_html_e( 'Es demo / descarte', 'santiago-moraes' ); ?>
		</label>
	</div>
	<div class="form-field">
		<label for="album-spotify"><?php esc_html_e( 'Spotify URL', 'santiago-moraes' ); ?></label>
		<input type="url" name="_album_spotify_url" id="album-spotify">
	</div>
	<div class="form-field">
		<label for="album-bandcamp"><?php esc_html_e( 'Bandcamp URL', 'santiago-moraes' ); ?></label>
		<input type="url" name="_album_bandcamp_url" id="album-bandcamp">
	</div>
	<div class="form-field">
		<label for="album-youtube"><?php esc_html_e( 'YouTube URL', 'santiago-moraes' ); ?></label>
		<input type="url" name="_album_youtube_url" id="album-youtube">
	</div>
	<div class="form-field">
		<label for="album-vinyl"><?php esc_html_e( 'Vinilo URL', 'santiago-moraes' ); ?></label>
		<input type="url" name="_album_vinyl_url" id="album-vinyl">
	</div>
	<?php
}

/**
 * Edit fields on the "Edit Album" form.
 *
 * @param WP_Term $term Current taxonomy term object.
 */
function sm_album_edit_form_fields( $term ) {
	wp_nonce_field( 'sm_album_meta', 'sm_album_meta_nonce' );

	$year        = get_term_meta( $term->term_id, '_album_year', true );
	$description = get_term_meta( $term->term_id, '_album_description', true );
	$cover_id    = get_term_meta( $term->term_id, '_album_cover_id', true );
	$spotify     = get_term_meta( $term->term_id, '_album_spotify_url', true );
	$bandcamp    = get_term_meta( $term->term_id, '_album_bandcamp_url', true );
	$youtube     = get_term_meta( $term->term_id, '_album_youtube_url', true );
	$vinyl       = get_term_meta( $term->term_id, '_album_vinyl_url', true );
	$is_demo     = get_term_meta( $term->term_id, '_album_is_demo', true );
	?>
	<tr class="form-field">
		<th><label for="album-year"><?php esc_html_e( 'Ano', 'santiago-moraes' ); ?></label></th>
		<td><input type="number" name="_album_year" id="album-year" value="<?php echo esc_attr( $year ); ?>" min="1900" max="2100"></td>
	</tr>
	<tr class="form-field">
		<th><label for="album-description"><?php esc_html_e( 'Descripcion', 'santiago-moraes' ); ?></label></th>
		<td><textarea name="_album_description" id="album-description" rows="3"><?php echo esc_textarea( $description ); ?></textarea></td>
	</tr>
	<tr class="form-field">
		<th><label for="album-cover-id"><?php esc_html_e( 'ID de imagen de portada', 'santiago-moraes' ); ?></label></th>
		<td>
			<input type="number" name="_album_cover_id" id="album-cover-id" value="<?php echo esc_attr( $cover_id ); ?>">
			<?php if ( $cover_id ) : ?>
				<p><?php echo wp_get_attachment_image( (int) $cover_id, 'thumbnail' ); ?></p>
			<?php endif; ?>
		</td>
	</tr>
	<tr class="form-field">
		<th><label for="album-is-demo"><?php esc_html_e( 'Tipo', 'santiago-moraes' ); ?></label></th>
		<td>
			<label>
				<input type="checkbox" name="_album_is_demo" id="album-is-demo" value="1" <?php checked( $is_demo, 1 ); ?>>
				<?php esc_html_e( 'Es demo / descarte', 'santiago-moraes' ); ?>
			</label>
		</td>
	</tr>
	<tr class="form-field">
		<th><label for="album-spotify"><?php esc_html_e( 'Spotify URL', 'santiago-moraes' ); ?></label></th>
		<td><input type="url" name="_album_spotify_url" id="album-spotify" value="<?php echo esc_url( $spotify ); ?>"></td>
	</tr>
	<tr class="form-field">
		<th><label for="album-bandcamp"><?php esc_html_e( 'Bandcamp URL', 'santiago-moraes' ); ?></label></th>
		<td><input type="url" name="_album_bandcamp_url" id="album-bandcamp" value="<?php echo esc_url( $bandcamp ); ?>"></td>
	</tr>
	<tr class="form-field">
		<th><label for="album-youtube"><?php esc_html_e( 'YouTube URL', 'santiago-moraes' ); ?></label></th>
		<td><input type="url" name="_album_youtube_url" id="album-youtube" value="<?php echo esc_url( $youtube ); ?>"></td>
	</tr>
	<tr class="form-field">
		<th><label for="album-vinyl"><?php esc_html_e( 'Vinilo URL', 'santiago-moraes' ); ?></label></th>
		<td><input type="url" name="_album_vinyl_url" id="album-vinyl" value="<?php echo esc_url( $vinyl ); ?>"></td>
	</tr>
	<?php
}

/**
 * Save album term meta fields.
 *
 * @param int $term_id Term ID.
 */
function sm_save_album_term_meta( $term_id ) {
	if ( ! isset( $_POST['sm_album_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sm_album_meta_nonce'] ) ), 'sm_album_meta' ) ) {
		return;
	}

	$fields = array(
		'_album_year'         => 'absint',
		'_album_description'  => 'sanitize_textarea_field',
		'_album_cover_id'     => 'absint',
		'_album_spotify_url'  => 'esc_url_raw',
		'_album_bandcamp_url' => 'esc_url_raw',
		'_album_youtube_url'  => 'esc_url_raw',
		'_album_vinyl_url'    => 'esc_url_raw',
	);

	foreach ( $fields as $key => $sanitize ) {
		if ( isset( $_POST[ $key ] ) ) {
			$value = call_user_func( $sanitize, wp_unslash( $_POST[ $key ] ) );
			update_term_meta( $term_id, $key, $value );
		}
	}

	// Checkbox: unchecked = absent from $_POST → save 0.
	update_term_meta( $term_id, '_album_is_demo', ! empty( $_POST['_album_is_demo'] ) ? 1 : 0 );
}
