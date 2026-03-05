<?php
/**
 * Album Hero — Featured album with large cover, info, and streaming links.
 *
 * Expects $sm_album_term (WP_Term) to be set via set_query_var().
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$album_term = get_query_var( 'sm_album_term' );

if ( ! $album_term instanceof WP_Term ) {
	return;
}

$year        = get_term_meta( $album_term->term_id, '_album_year', true );
$description = get_term_meta( $album_term->term_id, '_album_description', true );
$cover_id    = get_term_meta( $album_term->term_id, '_album_cover_id', true );
$spotify     = get_term_meta( $album_term->term_id, '_album_spotify_url', true );
$bandcamp    = get_term_meta( $album_term->term_id, '_album_bandcamp_url', true );
$youtube     = get_term_meta( $album_term->term_id, '_album_youtube_url', true );
$vinyl       = get_term_meta( $album_term->term_id, '_album_vinyl_url', true );

// Cover image.
if ( $cover_id ) {
	$cover_tag = wp_get_attachment_image(
		(int) $cover_id,
		'large',
		false,
		array(
			'loading' => 'eager',
			'alt'     => esc_attr( $album_term->name . ' - Santiago Moraes' ),
			'class'   => 'album-hero__cover-img',
		)
	);
} else {
	$cover_tag = '<img src="' . esc_url( SM_THEME_URI . '/assets/images/album-placeholder.webp' ) . '" alt="' . esc_attr( $album_term->name ) . '" width="600" height="600" loading="eager" class="album-hero__cover-img">';
}
?>

<section class="album-hero">
	<div class="album-hero__inner">

		<div class="album-hero__cover">
			<?php echo $cover_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image output. ?>
		</div>

		<div class="album-hero__info">
			<?php if ( $year ) : ?>
				<span class="album-hero__year"><?php echo esc_html( $year ); ?></span>
			<?php endif; ?>

			<h1 class="album-hero__title"><?php echo esc_html( $album_term->name ); ?></h1>

			<?php if ( $description ) : ?>
				<p class="album-hero__description"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<?php if ( $spotify || $bandcamp || $youtube || $vinyl ) : ?>
				<div class="platform-grid">
					<?php if ( $spotify ) : ?>
						<a href="<?php echo esc_url( $spotify ); ?>" class="platform-card platform-card--spotify" target="_blank" rel="noopener noreferrer">
							<span class="platform-card__icon">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>
							</span>
							<span class="platform-card__label">Spotify</span>
							<span class="platform-card__external">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
							</span>
						</a>
					<?php endif; ?>

					<?php if ( $bandcamp ) : ?>
						<a href="<?php echo esc_url( $bandcamp ); ?>" class="platform-card platform-card--bandcamp" target="_blank" rel="noopener noreferrer">
							<span class="platform-card__icon">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256S119 504 256 504 504 393 504 256 393 8 256 8zm48.2 326.1h-181L207.9 178h181z"/></svg>
							</span>
							<span class="platform-card__label">Bandcamp</span>
							<span class="platform-card__external">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
							</span>
						</a>
					<?php endif; ?>

					<?php if ( $youtube ) : ?>
						<a href="<?php echo esc_url( $youtube ); ?>" class="platform-card platform-card--youtube" target="_blank" rel="noopener noreferrer">
							<span class="platform-card__icon">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
							</span>
							<span class="platform-card__label">YouTube</span>
							<span class="platform-card__external">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
							</span>
						</a>
					<?php endif; ?>

					<?php if ( $vinyl ) : ?>
						<a href="<?php echo esc_url( $vinyl ); ?>" class="platform-card platform-card--vinyl" target="_blank" rel="noopener noreferrer">
							<span class="platform-card__icon">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256-96a96 96 0 1 1 0 192 96 96 0 1 1 0-192zm0 224a128 128 0 1 0 0-256 128 128 0 1 0 0 256zm0-96a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"/></svg>
							</span>
							<span class="platform-card__label">Vinilo</span>
							<span class="platform-card__external">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"/></svg>
							</span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>
