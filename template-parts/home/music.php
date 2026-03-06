<?php
/**
 * Homepage Latest Release / Music section.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

// Find the album cover by searching the media library.
$album_attachment = get_posts(
	array(
		'post_type'      => 'attachment',
		'posts_per_page' => 1,
		'post_status'    => 'inherit',
		'post_mime_type' => 'image',
		's'              => 'hogar-album-portada',
		'fields'         => 'ids',
	)
);

if ( $album_attachment ) {
	$album_image_tag = wp_get_attachment_image(
		$album_attachment[0],
		'sm-album-cover',
		false,
		array(
			'loading' => 'lazy',
			'alt'     => __( 'Hogar - Santiago Moraes', 'santiago-moraes' ),
		)
	);
} else {
	$album_image_tag = '<img src="' . esc_url( SM_THEME_URI . '/assets/images/album-placeholder.webp' ) . '" alt="' . esc_attr__( 'Hogar - Santiago Moraes', 'santiago-moraes' ) . '" width="600" height="600" loading="lazy">';
}
?>

<section class="latest-release" id="musica">
	<div class="latest-release__inner">

		<div class="latest-release__cover">
			<?php echo $album_image_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image output. ?>
		</div>

		<div class="latest-release__info">
			<span class="latest-release__label"><?php esc_html_e( 'Último lanzamiento', 'santiago-moraes' ); ?></span>
			<h2 class="latest-release__album-title">Hogar</h2>
			<p class="latest-release__artist">Santiago Moraes &middot; 2022</p>

			<p class="latest-release__description text-description">
				<?php echo esc_html__( 'Hogar esta formado por nueve temas en los que Moraes retorna a su lirica deudora de Javier Martinez y logra mezclarla con sonidos populares que miran hacia Uruguay, la tierra de sus padres.', 'santiago-moraes' ); ?>
			</p>

			<div class="platform-grid">

				<?php // Spotify. ?>
				<a href="https://open.spotify.com/album/26NInlEZ66aKG9MMguyEpT" class="platform-card platform-card--spotify" target="_blank" rel="noopener noreferrer">
					<span class="platform-card__icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>
					</span>
					<span class="platform-card__label">Spotify</span>
					<span class="platform-card__external">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
					</span>
				</a>

				<?php // Bandcamp. ?>
				<a href="https://santiagomoraes.bandcamp.com/" class="platform-card platform-card--bandcamp" target="_blank" rel="noopener noreferrer">
					<span class="platform-card__icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256S119 504 256 504 504 393 504 256 393 8 256 8zm48.2 326.1h-181L207.9 178h181z"/></svg>
					</span>
					<span class="platform-card__label">Bandcamp</span>
					<span class="platform-card__external">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
					</span>
				</a>

				<?php // YouTube. ?>
				<a href="https://www.youtube.com/@SantiagoMoraesMusica" class="platform-card platform-card--youtube" target="_blank" rel="noopener noreferrer">
					<span class="platform-card__icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
					</span>
					<span class="platform-card__label">YouTube</span>
					<span class="platform-card__external">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
					</span>
				</a>

				<?php // Vinyl. ?>
				<a href="https://littlebutterflyrecords.com/collections/catalogo/products/santiago-moraes-hogar-2022" class="platform-card platform-card--vinyl" target="_blank" rel="noopener noreferrer">
					<span class="platform-card__icon">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256-96a96 96 0 1 1 0 192 96 96 0 1 1 0-192zm0 224a128 128 0 1 0 0-256 128 128 0 1 0 0 256zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/></svg>
					</span>
					<span class="platform-card__label">Vinilo</span>
					<span class="platform-card__external">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
					</span>
				</a>

			</div>

			<?php
			// Spotify embed player on homepage.
			$show_player = sm_get_option( 'sm_player_homepage', true );
			if ( $show_player ) :
				$spotify_url   = sm_get_default_spotify_url();
				$spotify_embed = sm_spotify_embed_url( $spotify_url );
				if ( $spotify_embed ) :
					?>
					<div class="latest-release__player">
						<div class="spotify-menu-blocker" aria-hidden="true"></div>
						<iframe
							src="<?php echo esc_url( $spotify_embed ); ?>"
							width="100%"
							height="380"
							style="border:0;"
							allow="encrypted-media"
							loading="lazy"
							title="Spotify Player"
						></iframe>
					</div>
					<?php
				endif;
			endif;
			?>
		</div>

	</div>
</section>
