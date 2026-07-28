<?php
/**
 * Homepage Hero section — Rebranding.
 *
 * Orange background, 2-column layout with album art / YouTube façade player.
 * All text, image and video fields are editable from the admin panel
 * (Apariencia > Santiago Moraes > Hero).
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$hero_tag       = sm_get_option( 'sm_hero_tag', __( 'Canción rioplatense · Buenos Aires', 'santiago-moraes' ) );
$hero_line1     = sm_get_option( 'sm_hero_line1', 'Santiago' );
$hero_line2     = sm_get_option( 'sm_hero_line2', 'Moraes' );
$hero_desc      = sm_get_option( 'sm_hero_description', __( 'Canciones de patio y de vereda, con la lírica cerca del hueso y la guitarra mirando al otro lado del río.', 'santiago-moraes' ) );
$btn1_text      = sm_get_option( 'sm_hero_btn1_text', __( 'Escuchar ahora', 'santiago-moraes' ) );
$btn1_url       = sm_get_option( 'sm_hero_btn1_url', 'https://open.spotify.com/album/26NInlEZ66aKG9MMguyEpT' );
$btn2_text      = sm_get_option( 'sm_hero_btn2_text', __( 'Próximos shows', 'santiago-moraes' ) );
$btn2_url       = sm_get_option( 'sm_hero_btn2_url', '#shows' );
$album_label    = sm_get_option( 'sm_hero_album_label', __( 'Nuevo · Hogar', 'santiago-moraes' ) );
$hero_video_url = sm_get_option( 'sm_hero_video_url', '' );

// Album art: use the admin-set image, else fall back to theme asset.
$hero_img_url = sm_get_option( 'sm_hero_image', '' );
if ( ! $hero_img_url ) {
	$hero_img_url = SM_THEME_URI . '/assets/images/las-siete-menos-diez.jpg';
}

$has_video = ! empty( $hero_video_url );
?>

<section class="hero" id="hero">
	<div class="hero__inner">
		<div class="hero__text">
			<?php if ( $hero_tag ) : ?>
				<p class="hero__tag mono-label"><?php echo esc_html( $hero_tag ); ?></p>
			<?php endif; ?>

			<h1 class="hero__title">
				<?php echo esc_html( $hero_line1 ); ?><br>
				<span class="hero__title-outline"><?php echo esc_html( $hero_line2 ); ?></span>
			</h1>

			<?php if ( $hero_desc ) : ?>
				<p class="hero__description"><?php echo esc_html( $hero_desc ); ?></p>
			<?php endif; ?>

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
			<?php if ( $album_label ) : ?>
				<p class="hero__album-tag mono-label">&darr; <?php echo esc_html( $album_label ); ?></p>
			<?php endif; ?>
			<div class="hero__album-frame<?php echo $has_video ? ' hero__album-frame--has-video' : ''; ?>"
				<?php if ( $has_video ) : ?>
					data-video-url="<?php echo esc_url( $hero_video_url ); ?>"
				<?php endif; ?>>
				<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="<?php echo esc_attr( $album_label ?: 'Santiago Moraes' ); ?>" width="600" height="600" loading="eager" decoding="async">
				<?php if ( $has_video ) : ?>
					<button class="hero__play-btn" type="button" aria-label="<?php esc_attr_e( 'Reproducir', 'santiago-moraes' ); ?>">
						<svg width="68" height="68" viewBox="0 0 68 68" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<circle cx="34" cy="34" r="33" stroke="currentColor" stroke-width="2" fill="rgba(0,0,0,0.45)"/>
							<polygon points="27,20 27,48 50,34" fill="currentColor"/>
						</svg>
					</button>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
