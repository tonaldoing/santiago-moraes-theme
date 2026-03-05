<?php
/**
 * Album Header (compact) — Smaller dark header for older/secondary albums.
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

$year     = get_term_meta( $album_term->term_id, '_album_year', true );
$cover_id = get_term_meta( $album_term->term_id, '_album_cover_id', true );
$spotify  = get_term_meta( $album_term->term_id, '_album_spotify_url', true );
$bandcamp = get_term_meta( $album_term->term_id, '_album_bandcamp_url', true );
$youtube  = get_term_meta( $album_term->term_id, '_album_youtube_url', true );

// Cover image (smaller).
$cover_tag = '';
if ( $cover_id ) {
	$cover_tag = wp_get_attachment_image(
		(int) $cover_id,
		'medium',
		false,
		array(
			'loading' => 'lazy',
			'alt'     => esc_attr( $album_term->name . ' - Santiago Moraes' ),
			'class'   => 'album-compact__cover-img',
		)
	);
}
?>

<section class="album-compact">
	<div class="album-compact__inner">
		<?php if ( $cover_tag ) : ?>
			<div class="album-compact__cover">
				<?php echo $cover_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>

		<div class="album-compact__info">
			<h2 class="album-compact__title">
				<?php echo esc_html( $album_term->name ); ?>
				<?php if ( $year ) : ?>
					<span class="album-compact__year">(<?php echo esc_html( $year ); ?>)</span>
				<?php endif; ?>
			</h2>

			<?php if ( $spotify || $bandcamp || $youtube ) : ?>
				<div class="album-compact__links">
					<?php if ( $spotify ) : ?>
						<a href="<?php echo esc_url( $spotify ); ?>" class="album-compact__link" target="_blank" rel="noopener noreferrer" title="Spotify">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" width="20" height="20"><path fill="#1DB954" d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>
						</a>
					<?php endif; ?>
					<?php if ( $bandcamp ) : ?>
						<a href="<?php echo esc_url( $bandcamp ); ?>" class="album-compact__link" target="_blank" rel="noopener noreferrer" title="Bandcamp">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path fill="#629AA9" d="M256 8C119 8 8 119 8 256S119 504 256 504 504 393 504 256 393 8 256 8zm48.2 326.1h-181L207.9 178h181z"/></svg>
						</a>
					<?php endif; ?>
					<?php if ( $youtube ) : ?>
						<a href="<?php echo esc_url( $youtube ); ?>" class="album-compact__link" target="_blank" rel="noopener noreferrer" title="YouTube">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="20" height="20"><path fill="#FF0033" d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
