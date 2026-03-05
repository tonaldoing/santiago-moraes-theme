<?php
/**
 * Generic page template.
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main page-generic">
	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<header class="page-header">
			<div class="page-header__inner">
				<h1 class="page-header__title"><?php the_title(); ?></h1>
			</div>
		</header>

		<article class="page-content">
			<div class="page-content__inner">
				<?php
				if ( has_post_thumbnail() ) :
					?>
					<div class="page-content__thumbnail">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
					<?php
				endif;

				the_content();

				wp_link_pages(
					array(
						'before' => '<nav class="page-links">' . __( 'Paginas:', 'santiago-moraes' ),
						'after'  => '</nav>',
					)
				);
				?>
			</div>
		</article>

		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
