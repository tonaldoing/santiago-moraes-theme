<?php
/**
 * 404 page template.
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main page-404">
	<section class="error-404">
		<div class="error-404__inner">
			<span class="error-404__code">404</span>
			<h1 class="error-404__title"><?php esc_html_e( 'Página no encontrada', 'santiago-moraes' ); ?></h1>
			<p class="error-404__text"><?php esc_html_e( 'Lo sentimos, la página que buscás no existe o fue movida.', 'santiago-moraes' ); ?></p>

			<form role="search" method="get" class="error-404__search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label for="search-404" class="sr-only"><?php esc_html_e( 'Buscar', 'santiago-moraes' ); ?></label>
				<input type="search" id="search-404" class="error-404__search-input" placeholder="<?php esc_attr_e( 'Buscar...', 'santiago-moraes' ); ?>" value="" name="s">
				<button type="submit" class="btn btn--primary error-404__search-btn"><?php esc_html_e( 'Buscar', 'santiago-moraes' ); ?></button>
			</form>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--outline error-404__home-btn">
				<?php esc_html_e( 'Volver al inicio', 'santiago-moraes' ); ?>
			</a>
		</div>
	</section>
</main>

<?php
get_footer();
