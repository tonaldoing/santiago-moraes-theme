<?php
/**
 * Footer template — Rebranding.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$logo_text = sm_get_option( 'sm_logo_text', 'Santiago Moraes' );

$footer_social = array(
	'spotify'    => array( 'url' => sm_get_option( 'sm_social_spotify', 'https://open.spotify.com/artist/2pfLPT9ZTkPrLd8ZJiDBld' ), 'label' => 'Spotify' ),
	'youtube'    => array( 'url' => sm_get_option( 'sm_social_youtube', 'https://www.youtube.com/@SantiagoMoraesMusica' ), 'label' => 'YouTube' ),
	'bandcamp'   => array( 'url' => sm_get_option( 'sm_social_bandcamp', 'https://santiagomoraes.bandcamp.com/' ), 'label' => 'Bandcamp' ),
	'instagram'  => array( 'url' => sm_get_option( 'sm_social_instagram', 'https://www.instagram.com/santiagomoraes_' ), 'label' => 'Instagram' ),
	'soundcloud' => array( 'url' => sm_get_option( 'sm_social_soundcloud', 'https://soundcloud.com/santiago-moraes' ), 'label' => 'SoundCloud' ),
);

$active_social = array_filter( $footer_social, function ( $data ) {
	return ! empty( $data['url'] );
} );
?>

<footer class="site-footer" id="site-footer">
	<div class="site-footer__inner">

		<div class="site-footer__brand">
			<p class="site-footer__name">Santiago<br>Moraes</p>
			<p class="site-footer__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> &middot; <?php esc_html_e( 'Todos los derechos reservados', 'santiago-moraes' ); ?></p>
		</div>

		<?php if ( $active_social ) : ?>
			<div class="footer-social">
				<?php foreach ( $active_social as $key => $data ) : ?>
					<a href="<?php echo esc_url( $data['url'] ); ?>" class="footer-social__link footer-social__link--<?php echo esc_attr( $key ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $data['label'] ); ?>">
						<?php echo sm_social_icon( $key, 20 ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
