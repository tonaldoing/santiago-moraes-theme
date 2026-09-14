<?php
/**
 * Sidebar widget — streaming links (from Theme Options > Redes).
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$platforms = array(
	'spotify'  => array( 'label' => 'Spotify', 'url' => sm_get_option( 'sm_social_spotify', '' ) ),
	'youtube'  => array( 'label' => 'YouTube', 'url' => sm_get_option( 'sm_social_youtube', '' ) ),
	'bandcamp' => array( 'label' => 'Bandcamp', 'url' => sm_get_option( 'sm_social_bandcamp', '' ) ),
);

$platforms = array_filter( $platforms, fn( $p ) => ! empty( $p['url'] ) );

if ( empty( $platforms ) ) {
	return;
}
?>

<section class="widget widget--listen">
	<h2 class="widget__title"><?php esc_html_e( 'Escuchar', 'santiago-moraes' ); ?></h2>

	<div class="widget-links">
		<?php foreach ( $platforms as $slug => $platform ) : ?>
			<a href="<?php echo esc_url( $platform['url'] ); ?>" class="widget-links__item widget-links__item--<?php echo esc_attr( $slug ); ?>" target="_blank" rel="noopener noreferrer">
				<?php echo sm_social_icon( $slug, 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php echo esc_html( $platform['label'] ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
