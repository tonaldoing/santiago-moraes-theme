<?php
/**
 * The main template file.
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
	else :
		?>
		<section class="no-results">
			<div class="no-results__inner" style="max-width:1140px;margin:0 auto;padding:5vw 20px;">
				<h2><?php esc_html_e( 'No se encontraron resultados', 'santiago-moraes' ); ?></h2>
				<p><?php esc_html_e( 'No hay contenido que mostrar.', 'santiago-moraes' ); ?></p>
			</div>
		</section>
		<?php
	endif;
	?>
</main>

<?php
get_footer();
