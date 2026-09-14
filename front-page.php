<?php
/**
 * Front page template — songbook-first layout.
 *
 * Compact hero → announcement bar → two columns:
 * discography grid + latest songs with chords (main) / sidebar widgets (search, shows, listen, shop, contact).
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main">

	<?php get_template_part( 'template-parts/home/hero' ); ?>

	<?php get_template_part( 'template-parts/home/marquee' ); ?>

	<div class="home-layout">
		<div class="home-layout__inner">

			<div class="home-layout__main">
				<?php get_template_part( 'template-parts/home/discography' ); ?>
				<?php get_template_part( 'template-parts/home/recent-songs' ); ?>
			</div>

			<?php get_template_part( 'template-parts/home/sidebar' ); ?>

		</div>
	</div>

</main>

<?php
get_footer();
