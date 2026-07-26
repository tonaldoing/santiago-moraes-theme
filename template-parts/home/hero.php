<?php
/**
 * Homepage Hero section — Rebranding.
 *
 * Orange background, 2-column layout with album art.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$btn1_text = sm_get_option( 'sm_hero_btn1_text', __( 'Escuchar ahora', 'santiago-moraes' ) );
$btn1_url  = sm_get_option( 'sm_hero_btn1_url', 'https://open.spotify.com/album/26NInlEZ66aKG9MMguyEpT' );
$btn2_text = sm_get_option( 'sm_hero_btn2_text', __( 'Próximos shows', 'santiago-moraes' ) );
$btn2_url  = sm_get_option( 'sm_hero_btn2_url', '#shows' );

// Album art image.
$album_img = SM_THEME_URI . '/assets/images/las-siete-menos-diez.jpg';
$album_attachment = get_posts( array(
	'post_type'      => 'attachment',
	'posts_per_page' => 1,
	'post_status'    => 'inherit',
	'post_mime_type' => 'image',
	's'              => 'las-siete-menos-diez',
	'fields'         => 'ids',
) );
if ( $album_attachment ) {
	$album_img = wp_get_attachment_url( $album_attachment[0] );
}
?>

<section class="hero" id="hero">
	<div class="hero__inner">
		<div class="hero__text">
			<p class="hero__tag mono-label"><?php esc_html_e( 'Canción rioplatense · Buenos Aires', 'santiago-moraes' ); ?></p>

			<h1 class="hero__title">
				<?php echo esc_html( sm_get_option( 'sm_hero_line1', 'Santiago' ) ); ?><br>
				<span class="hero__title-outline"><?php echo esc_html( sm_get_option( 'sm_hero_line2', 'Moraes' ) ); ?></span>
			</h1>

			<p class="hero__description">
				<?php echo esc_html( sm_get_option( 'sm_hero_description', 'Canciones de patio y de vereda, con la lírica cerca del hueso y la guitarra mirando al otro lado del río.' ) ); ?>
			</p>

			<div class="hero__buttons">
				<?php if ( $btn1_text && $btn1_url ) : ?>
					<a href="<?php echo esc_url( $btn1_url ); ?>" class="btn btn--primary" <?php echo ( false === strpos( $btn1_url, '#' ) ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
						<?php echo esc_html( $btn1_text ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $btn2_text && $btn2_url ) : ?>
					<a href="<?php echo esc_url( $btn2_url ); ?>" class="btn btn--ghost">
						<?php echo esc_html( $btn2_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="hero__album">
			<p class="hero__album-tag mono-label">&darr; <?php esc_html_e( 'Nuevo · Las siete menos diez', 'santiago-moraes' ); ?></p>
			<div class="hero__album-frame">
				<img src="<?php echo esc_url( $album_img ); ?>" alt="<?php esc_attr_e( 'Las siete menos diez — Santiago Moraes & La Nafta', 'santiago-moraes' ); ?>" width="600" height="600" loading="eager" decoding="async">
			</div>
		</div>
	</div>
</section>
