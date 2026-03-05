<?php
/**
 * Front page template.
 *
 * Section order: Hero → Shows → Música → Últimas Canciones → Contacto
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main">

	<?php get_template_part( 'template-parts/home/hero' ); ?>

	<?php get_template_part( 'template-parts/home/shows' ); ?>

	<?php get_template_part( 'template-parts/home/music' ); ?>

	<?php get_template_part( 'template-parts/home/latest-songs' ); ?>

	<?php get_template_part( 'template-parts/home/contact' ); ?>

</main>

<?php
get_footer();
