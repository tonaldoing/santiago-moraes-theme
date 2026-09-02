<?php
/**
 * Header template — Rebranding.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$logo_text = sm_get_option( 'sm_logo_text', 'Santiago Moraes' );

$header_social = array(
	'spotify'   => array(
		'url'   => sm_get_option( 'sm_social_spotify', 'https://open.spotify.com/artist/2pfLPT9ZTkPrLd8ZJiDBld' ),
		'label' => 'Spotify',
	),
	'youtube'   => array(
		'url'   => sm_get_option( 'sm_social_youtube', 'https://www.youtube.com/@SantiagoMoraesMusica' ),
		'label' => 'YouTube',
	),
	'bandcamp'  => array(
		'url'   => sm_get_option( 'sm_social_bandcamp', 'https://santiagomoraes.bandcamp.com/' ),
		'label' => 'Bandcamp',
	),
	'instagram' => array(
		'url'   => sm_get_option( 'sm_social_instagram', 'https://www.instagram.com/santiagomoraes_' ),
		'label' => 'Instagram',
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

		<div class="site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<img class="site-title__mark" src="<?php echo esc_url( SM_THEME_URI . '/assets/images/logo-mark.png' ); ?>" alt="" width="34" height="34" aria-hidden="true">
				<img class="site-title__wordmark" src="<?php echo esc_url( SM_THEME_URI . '/assets/images/logo-cream.svg' ); ?>" alt="<?php echo esc_attr( $logo_text ); ?>" width="137" height="30">
			</a>
		</div>

		<nav class="main-nav" aria-label="<?php esc_attr_e( 'Menu principal', 'santiago-moraes' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) :
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'main-nav__list',
						'depth'          => 2,
						'walker'         => new SM_Nav_Walker(),
					)
				);
			else :
				?>
				<ul class="main-nav__list">
					<li class="menu-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="main-nav__link"><?php esc_html_e( 'Inicio', 'santiago-moraes' ); ?></a></li>
					<li class="menu-item"><a href="<?php echo esc_url( home_url( '/shows/' ) ); ?>" class="main-nav__link"><?php esc_html_e( 'Shows', 'santiago-moraes' ); ?></a></li>
					<li class="menu-item"><a href="<?php echo esc_url( home_url( '/musica/' ) ); ?>" class="main-nav__link"><?php esc_html_e( 'Discografía', 'santiago-moraes' ); ?></a></li>
					<li class="menu-item"><a href="<?php echo esc_url( home_url( '/acordes/' ) ); ?>" class="main-nav__link"><?php esc_html_e( 'Acordes', 'santiago-moraes' ); ?></a></li>
				</ul>
				<?php
			endif;
			?>
		</nav>

		<div class="header-social">
			<?php foreach ( $header_social as $key => $data ) :
				if ( ! $data['url'] ) continue;
			?>
				<a href="<?php echo esc_url( $data['url'] ); ?>" class="header-social__link header-social__link--<?php echo esc_attr( $key ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $data['label'] ); ?>">
					<?php echo sm_social_icon( $key, 18 ); ?>
				</a>
			<?php endforeach; ?>
		</div>

		<button class="menu-toggle" id="menu-toggle" aria-controls="mobile-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'santiago-moraes' ); ?>">
			<svg class="menu-toggle__open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor"><path d="M0 96C0 78.3 14.3 64 32 64h384c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zm0 160c0-17.7 14.3-32 32-32h384c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zm448 160c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32h384c17.7 0 32 14.3 32 32z"/></svg>
			<svg class="menu-toggle__close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor" style="display:none;"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3l105.4 105.3c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256l105.3-105.4z"/></svg>
		</button>

	</div>
</header>

<div class="mobile-menu" id="mobile-menu" aria-hidden="true">
	<?php
	if ( has_nav_menu( 'primary' ) ) :
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mobile-menu__list',
				'depth'          => 2,
				'walker'         => new SM_Nav_Walker(),
			)
		);
	else :
		?>
		<ul class="mobile-menu__list">
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'santiago-moraes' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/shows/' ) ); ?>"><?php esc_html_e( 'Shows', 'santiago-moraes' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/musica/' ) ); ?>"><?php esc_html_e( 'Discografía', 'santiago-moraes' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/acordes/' ) ); ?>"><?php esc_html_e( 'Acordes', 'santiago-moraes' ); ?></a></li>
		</ul>
		<?php
	endif;
	?>

	<div class="mobile-menu__social">
		<?php foreach ( $header_social as $key => $data ) :
			if ( ! $data['url'] ) continue;
		?>
			<a href="<?php echo esc_url( $data['url'] ); ?>" class="mobile-menu__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $data['label'] ); ?>">
				<?php echo sm_social_icon( $key, 20 ); ?>
			</a>
		<?php endforeach; ?>
	</div>
</div>
