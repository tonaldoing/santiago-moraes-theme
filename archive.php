<?php
/**
 * Generic archive template.
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main page-archive">
	<header class="archive-header">
		<div class="archive-header__inner">
			<?php the_archive_title( '<h1 class="archive-header__title">', '</h1>' ); ?>
			<?php the_archive_description( '<p class="archive-header__description">', '</p>' ); ?>
		</div>
	</header>

	<section class="archive-list">
		<div class="archive-list__inner">
			<?php if ( have_posts() ) : ?>

				<div class="archive-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article class="archive-card">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>" class="archive-card__thumb">
									<?php the_post_thumbnail( 'medium_large' ); ?>
								</a>
							<?php endif; ?>
							<div class="archive-card__body">
								<h2 class="archive-card__title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<time class="archive-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								<?php if ( has_excerpt() || get_the_content() ) : ?>
									<p class="archive-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
								<?php endif; ?>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 2,
						'prev_text' => '&larr; ' . __( 'Anterior', 'santiago-moraes' ),
						'next_text' => __( 'Siguiente', 'santiago-moraes' ) . ' &rarr;',
					)
				);
				?>

			<?php else : ?>
				<p class="archive-list__empty"><?php esc_html_e( 'No se encontraron entradas.', 'santiago-moraes' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
