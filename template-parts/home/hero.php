<?php
/**
 * Homepage Hero section — uses Customizer settings.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$line1     = get_theme_mod( 'sm_hero_line1', 'Santiago' );
$line2     = get_theme_mod( 'sm_hero_line2', 'Moraes' );
$btn1_text = get_theme_mod( 'sm_hero_btn1_text', __( 'Escuchar ahora', 'santiago-moraes' ) );
$btn1_url  = get_theme_mod( 'sm_hero_btn1_url', 'https://open.spotify.com/artist/2pfLPT9ZTkPrLd8ZJiDBld' );
$btn2_text = get_theme_mod( 'sm_hero_btn2_text', __( 'Proximos Shows', 'santiago-moraes' ) );
$btn2_url  = get_theme_mod( 'sm_hero_btn2_url', '#shows' );
$hero_img  = get_theme_mod( 'sm_hero_image', '' );

// Hero image: Customizer > media library search > placeholder.
if ( $hero_img ) {
	$hero_image_tag = '<img src="' . esc_url( $hero_img ) . '" alt="Santiago Moraes" loading="eager" decoding="async">';
} else {
	$hero_attachment = get_posts( array(
		'post_type'      => 'attachment',
		'posts_per_page' => 1,
		'post_status'    => 'inherit',
		'post_mime_type' => 'image',
		's'              => 'santiago-moraes-hero-image',
		'fields'         => 'ids',
	) );

	if ( $hero_attachment ) {
		$hero_image_tag = wp_get_attachment_image( $hero_attachment[0], 'full', false, array(
			'loading'  => 'eager',
			'decoding' => 'async',
			'alt'      => 'Santiago Moraes',
		) );
	} else {
		$hero_image_tag = '<img src="' . esc_url( SM_THEME_URI . '/assets/images/hero-placeholder.webp' ) . '" alt="Santiago Moraes" width="1000" height="741" loading="eager">';
	}
}
?>

<section class="hero" id="hero">
	<div class="hero__inner">

		<div class="hero__content">
			<h1 class="hero__title">
				<?php echo esc_html( $line1 ); ?>
				<span class="hero__accent"><?php echo esc_html( $line2 ); ?></span>
			</h1>

			<div class="hero__buttons">
				<?php if ( $btn1_text && $btn1_url ) : ?>
					<a href="<?php echo esc_url( $btn1_url ); ?>" class="btn btn--primary" <?php echo ( false === strpos( $btn1_url, '#' ) ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
						<span class="btn__icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512" fill="currentColor"><path d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c7.6-4.2 16.8-4.1 24.3 .5l144 88c7.1 4.4 11.5 12.1 11.5 20.5s-4.4 16.1-11.5 20.5l-144 88c-7.4 4.5-16.7 4.7-24.3 .5s-12.3-12.2-12.3-20.9V168c0-8.7 4.7-16.7 12.3-20.9z"/></svg>
						</span>
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

		<div class="hero__image">
			<?php echo $hero_image_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

	</div>
</section>
