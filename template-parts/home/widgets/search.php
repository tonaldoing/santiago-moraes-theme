<?php
/**
 * Sidebar widget — song search.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="widget widget--search">
	<form role="search" method="get" class="widget-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label for="widget-search-input" class="widget__title"><?php esc_html_e( 'Buscar canción', 'santiago-moraes' ); ?></label>
		<div class="widget-search__row">
			<input type="search" id="widget-search-input" class="widget-search__input" name="s" placeholder="<?php esc_attr_e( 'Título de la canción…', 'santiago-moraes' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
			<input type="hidden" name="post_type" value="cancion">
			<button type="submit" class="widget-search__btn" aria-label="<?php esc_attr_e( 'Buscar', 'santiago-moraes' ); ?>">&rarr;</button>
		</div>
	</form>
</section>
