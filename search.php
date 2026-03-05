<?php
/**
 * Search results template.
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main page-search">
	<header class="search-header">
		<div class="search-header__inner">
			<h1 class="search-header__title">
				<?php
				/* translators: %s: search query */
				printf( esc_html__( 'Resultados para: "%s"', 'santiago-moraes' ), esc_html( get_search_query() ) );
				?>
			</h1>
			<p class="search-header__count">
				<?php
				/* translators: %d: number of results */
				printf( esc_html( _n( '%d resultado encontrado', '%d resultados encontrados', (int) $wp_query->found_posts, 'santiago-moraes' ) ), (int) $wp_query->found_posts );
				?>
			</p>
		</div>
	</header>

	<section class="search-results">
		<div class="search-results__inner">
			<?php if ( have_posts() ) : ?>

				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="search-card">
						<h2 class="search-card__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<?php if ( get_post_type() !== 'page' ) : ?>
							<span class="search-card__type"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
						<?php endif; ?>
						<?php if ( has_excerpt() || get_the_content() ) : ?>
							<p class="search-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
						<?php endif; ?>
						<a href="<?php the_permalink(); ?>" class="search-card__link"><?php esc_html_e( 'Ver mas', 'santiago-moraes' ); ?> &rarr;</a>
					</article>
				<?php endwhile; ?>

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

				<div class="search-empty">
					<p class="search-empty__text"><?php esc_html_e( 'No se encontraron resultados. Intenta con otros terminos.', 'santiago-moraes' ); ?></p>

					<form role="search" method="get" class="search-empty__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" class="search-empty__input" placeholder="<?php esc_attr_e( 'Buscar...', 'santiago-moraes' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
						<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Buscar', 'santiago-moraes' ); ?></button>
					</form>

					<div class="search-empty__suggestions">
						<p><?php esc_html_e( 'Sugerencias:', 'santiago-moraes' ); ?></p>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/musica' ) ); ?>"><?php esc_html_e( 'Discografia', 'santiago-moraes' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'santiago-moraes' ); ?></a></li>
						</ul>
					</div>
				</div>

			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
