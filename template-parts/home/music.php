<?php
/**
 * Homepage Music section — Rebranding.
 *
 * Latest album feature with album art and platform links.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

// Album cover.
$album_attachment = get_posts( array(
	'post_type'      => 'attachment',
	'posts_per_page' => 1,
	'post_status'    => 'inherit',
	'post_mime_type' => 'image',
	's'              => 'hogar-album-portada',
	'fields'         => 'ids',
) );

if ( $album_attachment ) {
	$album_image_tag = wp_get_attachment_image( $album_attachment[0], 'sm-album-cover', false, array(
		'loading' => 'lazy',
		'alt'     => __( 'Hogar - Santiago Moraes', 'santiago-moraes' ),
	) );
} else {
	$album_image_tag = '<div class="music__placeholder"><span class="mono-label">tapa · hogar (2022)<br>[soltar imagen aquí]</span></div>';
}

$platforms = array(
	array( 'label' => 'Spotify', 'url' => 'https://open.spotify.com/album/26NInlEZ66aKG9MMguyEpT' ),
	array( 'label' => 'Bandcamp', 'url' => 'https://santiagomoraes.bandcamp.com/' ),
	array( 'label' => 'YouTube', 'url' => 'https://www.youtube.com/@SantiagoMoraesMusica' ),
	array( 'label' => 'Vinilo', 'url' => 'https://littlebutterflyrecords.com/collections/catalogo/products/santiago-moraes-hogar-2022' ),
);
?>

<section class="music" id="musica">
	<div class="music__inner">

		<div class="music__cover">
			<?php echo $album_image_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<div class="music__info">
			<p class="music__tag mono-label"><?php esc_html_e( 'Último disco', 'santiago-moraes' ); ?></p>

			<h2 class="music__album-title">Hogar</h2>

			<p class="music__meta mono-label"><?php esc_html_e( 'Santiago Moraes · 2022 · 9 canciones', 'santiago-moraes' ); ?></p>

			<p class="music__description">
				<?php echo esc_html__( 'Nueve temas en los que Moraes retorna a su lírica deudora de Javier Martínez y la mezcla con sonidos populares que miran hacia el Uruguay, la tierra de sus padres.', 'santiago-moraes' ); ?>
			</p>

			<div class="music__platforms">
				<?php foreach ( $platforms as $platform ) : ?>
					<a href="<?php echo esc_url( $platform['url'] ); ?>" class="btn btn--mono" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( $platform['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</section>
