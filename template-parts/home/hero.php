<?php
/**
 * Homepage Hero — compact band.
 *
 * Ochre strip with name + tagline + two CTAs. Text and buttons are editable
 * from Apariencia > Santiago Moraes > Hero.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$hero_tag   = sm_get_option( 'sm_hero_tag', __( 'Letras y acordes de todas las canciones', 'santiago-moraes' ) );
$hero_line1 = sm_get_option( 'sm_hero_line1', 'Santiago' );
$hero_line2 = sm_get_option( 'sm_hero_line2', 'Moraes' );
$btn1_text  = sm_get_option( 'sm_hero_btn1_text', __( 'Cancionero', 'santiago-moraes' ) );
$btn1_url   = sm_get_option( 'sm_hero_btn1_url', sm_cancionero_url() );
$btn2_text  = sm_get_option( 'sm_hero_btn2_text', __( 'Escuchar', 'santiago-moraes' ) );
$btn2_url   = sm_get_option( 'sm_hero_btn2_url', sm_get_option( 'sm_social_spotify', 'https://open.spotify.com/artist/2pfLPT9ZTkPrLd8ZJiDBld' ) );

$is_external = fn( $url ) => 0 === strpos( $url, 'http' ) && false === strpos( $url, home_url() );
?>

<section class="hero" id="hero">
	<div class="hero__inner">
		<div class="hero__text">
			<h1 class="hero__title">
				<?php echo esc_html( $hero_line1 ); ?>
				<span class="hero__title-outline"><?php echo esc_html( $hero_line2 ); ?></span>
			</h1>
			<?php if ( $hero_tag ) : ?>
				<p class="hero__tag mono-label"><?php echo esc_html( $hero_tag ); ?></p>
			<?php endif; ?>
		</div>

		<div class="hero__buttons">
			<?php if ( $btn1_text && $btn1_url ) : ?>
				<a href="<?php echo esc_url( $btn1_url ); ?>" class="btn btn--primary btn--sm" <?php echo $is_external( $btn1_url ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
					<?php echo esc_html( $btn1_text ); ?>
				</a>
			<?php endif; ?>

			<?php if ( $btn2_text && $btn2_url ) : ?>
				<a href="<?php echo esc_url( $btn2_url ); ?>" class="btn btn--ghost btn--sm" <?php echo $is_external( $btn2_url ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
					<?php echo esc_html( $btn2_text ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
