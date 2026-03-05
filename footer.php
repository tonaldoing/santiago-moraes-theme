<?php
/**
 * Footer template.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

// Logo settings.
$logo_type  = get_theme_mod( 'sm_logo_type', 'text' );
$logo_text  = get_theme_mod( 'sm_logo_text', 'Santiago Moraes' );
$logo_image = get_theme_mod( 'sm_logo_image', '' );

// Footer settings.
$copyright  = get_theme_mod( 'sm_footer_copyright', '' );
$credits    = get_theme_mod( 'sm_footer_credits', 'Designed with FeeloLab' );
$scroll_top = get_theme_mod( 'sm_footer_scroll_top', true );

// Social URLs from Customizer.
$footer_social = array(
	'instagram'  => array(
		'url'   => get_theme_mod( 'sm_social_instagram', '' ),
		'label' => 'Instagram',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>',
	),
	'spotify'    => array(
		'url'   => get_theme_mod( 'sm_social_spotify', '' ),
		'label' => 'Spotify',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>',
	),
	'youtube'    => array(
		'url'   => get_theme_mod( 'sm_social_youtube', '' ),
		'label' => 'YouTube',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>',
	),
	'bandcamp'   => array(
		'url'   => get_theme_mod( 'sm_social_bandcamp', '' ),
		'label' => 'Bandcamp',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256S119 504 256 504 504 393 504 256 393 8 256 8zm48.2 326.1h-181L207.9 178h181z"/></svg>',
	),
	'soundcloud' => array(
		'url'   => get_theme_mod( 'sm_social_soundcloud', '' ),
		'label' => 'SoundCloud',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M111.4 256.3l5.8 65-5.8 68.3c-.3 2.5-2.2 4.4-4.4 4.4s-4.2-1.9-4.2-4.4l-5.6-68.3 5.6-65c0-2.2 1.9-4.2 4.2-4.2 2.2 0 4.1 2 4.4 4.2zm21.4-45.6c-2.8 0-4.7 2.2-5 5l-5 105.6 5 68.3c.3 2.8 2.2 5 5 5 2.5 0 4.7-2.2 4.7-5l5.6-68.3-5.6-105.6c0-2.8-2.2-5-4.7-5zm25.5-24.1c-3.1 0-5.3 2.2-5.6 5.3l-4.4 130 4.4 67.8c.3 3.1 2.5 5.3 5.6 5.3 2.8 0 5.3-2.2 5.3-5.3l5-67.8-5-130c0-3.1-2.5-5.3-5.3-5.3zM7.2 283.2c-1.4 0-2.2 1.1-2.5 2.5L0 321.3l4.7 35c.3 1.4 1.1 2.5 2.5 2.5s2.2-1.1 2.5-2.5l5.6-35-5.6-35.6c-.3-1.4-1.1-2.5-2.5-2.5zm23.6-21.2c-1.7 0-2.8 1.4-2.8 2.8l-4.4 55.6 4.4 44.7c0 1.7 1.1 2.8 2.8 2.8 1.4 0 2.8-1.4 2.8-2.8l5-44.7-5-55.6c0-1.7-1.4-2.8-2.8-2.8zm38.8 0c-1.7 0-3.1 1.4-3.1 3.1L51 321.3l4.7 43.6c0 1.7 1.4 3.1 3.1 3.1 1.7 0 2.8-1.4 3.1-3.1l5-43.6-5-55.9c-.3-1.7-1.4-3.1-3.1-3.1zM640 217.9C640 171 599.6 133.3 552.8 133.3c-9.2 0-18.1 1.7-26.4 4.7C519.5 73.1 461 24 392.2 24c-19.7 0-39.2 4.4-56.9 12.5-7.2 3.3-9.2 6.7-9.2 13.1V361c0 6.9 5.3 12.8 12.2 13.3h302.2c48.3 0 87.2-39.1 87.2-87.4 0-48.2-38.9-69-87.7-69z"/></svg>',
	),
	'facebook'   => array(
		'url'   => get_theme_mod( 'sm_social_facebook', '' ),
		'label' => 'Facebook',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256c0 120 82.7 220.8 194.2 248.5V334.2h-56.6v-78.2h56.6v-61.3c0-56.4 33.5-87.3 84.6-87.3 24.5 0 50.2 4.4 50.2 4.4v55.4h-28.3c-27.8 0-36.5 17.3-36.5 35.1v42.2h62.4l-10 78.2h-52.4v170.3C429.3 476.8 512 376 512 256z"/></svg>',
	),
	'twitter'    => array(
		'url'   => get_theme_mod( 'sm_social_twitter', '' ),
		'label' => 'Twitter/X',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>',
	),
);
?>

<footer class="site-footer" id="site-footer">
	<div class="site-footer__inner">

		<?php // Site title / logo. ?>
		<div class="site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( 'image' === $logo_type && $logo_image ) : ?>
					<img src="<?php echo esc_url( $logo_image ); ?>" alt="<?php echo esc_attr( $logo_text ); ?>" class="site-title__logo">
				<?php else : ?>
					<?php echo esc_html( $logo_text ); ?>
				<?php endif; ?>
			</a>
		</div>

		<?php
		// Social icons — dynamic from Customizer.
		$has_footer_social = false;
		foreach ( $footer_social as $data ) {
			if ( $data['url'] ) {
				$has_footer_social = true;
				break;
			}
		}
		if ( $has_footer_social ) :
		?>
		<div class="footer-social">
			<?php foreach ( $footer_social as $network => $data ) :
				if ( ! $data['url'] ) continue;
			?>
				<a href="<?php echo esc_url( $data['url'] ); ?>" class="footer-social__link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $data['label'] ); ?>">
					<?php echo $data['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php // Copyright. ?>
		<p class="site-copyright">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> | <?php echo esc_html( $logo_text ); ?>
			<?php if ( $copyright ) : ?>
				| <?php echo esc_html( $copyright ); ?>
			<?php endif; ?>
		</p>

		<?php if ( $credits ) : ?>
			<p class="site-credits"><?php echo esc_html( $credits ); ?></p>
		<?php endif; ?>

	</div>
</footer>

<?php get_template_part( 'template-parts/components/sticky-player' ); ?>

<?php if ( $scroll_top ) : ?>
	<button class="scroll-top" id="scroll-top" aria-label="<?php esc_attr_e( 'Volver arriba', 'santiago-moraes' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor"><path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3 329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"/></svg>
	</button>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
