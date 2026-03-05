<?php
/**
 * Header template.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

// Logo settings.
$logo_type  = get_theme_mod( 'sm_logo_type', 'text' );
$logo_text  = get_theme_mod( 'sm_logo_text', 'Santiago Moraes' );
$logo_image = get_theme_mod( 'sm_logo_image', '' );

// Social URLs from Customizer.
$social_links = array(
	'spotify'    => array(
		'url'   => get_theme_mod( 'sm_social_spotify', '' ),
		'label' => 'Spotify',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>',
	),
	'bandcamp'   => array(
		'url'   => get_theme_mod( 'sm_social_bandcamp', '' ),
		'label' => 'Bandcamp',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256S119 504 256 504 504 393 504 256 393 8 256 8zm48.2 326.1h-181L207.9 178h181z"/></svg>',
	),
	'youtube'    => array(
		'url'   => get_theme_mod( 'sm_social_youtube', '' ),
		'label' => 'YouTube',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>',
	),
	'instagram'  => array(
		'url'   => get_theme_mod( 'sm_social_instagram', '' ),
		'label' => 'Instagram',
		'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>',
	),
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Saltar al contenido', 'santiago-moraes' ); ?></a>

<header class="site-header" id="site-header">
	<div class="site-header__inner">

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

		<?php // Desktop navigation. ?>
		<nav class="main-nav" aria-label="<?php esc_attr_e( 'Menu principal', 'santiago-moraes' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) :
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'main-nav__list',
						'depth'          => 0,
						'walker'         => new SM_Nav_Walker(),
					)
				);
			else :
				?>
				<ul class="main-nav__list">
					<li><a href="#shows" class="main-nav__link"><?php esc_html_e( 'Shows', 'santiago-moraes' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/musica' ) ); ?>" class="main-nav__link"><?php esc_html_e( 'Musica', 'santiago-moraes' ); ?></a></li>
					<li><a href="#contacto" class="main-nav__link"><?php esc_html_e( 'Contacto', 'santiago-moraes' ); ?></a></li>
				</ul>
				<?php
			endif;
			?>
		</nav>

		<?php
		// Social icons (header) — dynamic from Customizer.
		$has_social = false;
		foreach ( $social_links as $data ) {
			if ( $data['url'] ) {
				$has_social = true;
				break;
			}
		}
		if ( $has_social ) :
		?>
		<div class="header-social">
			<?php foreach ( $social_links as $network => $data ) :
				if ( ! $data['url'] ) continue;
			?>
				<a href="<?php echo esc_url( $data['url'] ); ?>" class="header-social__link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $data['label'] ); ?>">
					<?php echo $data['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php // Mobile menu toggle. ?>
		<button class="menu-toggle" id="menu-toggle" aria-controls="mobile-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'santiago-moraes' ); ?>">
			<svg class="menu-toggle__open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor"><path d="M0 96C0 78.3 14.3 64 32 64h384c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zm0 160c0-17.7 14.3-32 32-32h384c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zm448 160c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32h384c17.7 0 32 14.3 32 32z"/></svg>
			<svg class="menu-toggle__close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor" style="display:none;"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3l105.4 105.3c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256l105.3-105.4z"/></svg>
		</button>

	</div>
</header>

<?php // Mobile menu overlay. ?>
<div class="mobile-menu" id="mobile-menu" aria-hidden="true">
	<?php
	if ( has_nav_menu( 'primary' ) ) :
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mobile-menu__list',
				'depth'          => 0,
				'walker'         => new SM_Nav_Walker(),
			)
		);
	else :
		?>
		<ul class="mobile-menu__list">
			<li><a href="#shows"><?php esc_html_e( 'Shows', 'santiago-moraes' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/musica' ) ); ?>"><?php esc_html_e( 'Musica', 'santiago-moraes' ); ?></a></li>
			<li><a href="#contacto"><?php esc_html_e( 'Contacto', 'santiago-moraes' ); ?></a></li>
		</ul>
		<?php
	endif;
	?>
</div>
