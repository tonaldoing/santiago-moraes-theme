<?php
/**
 * Generic single post (blog) template.
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main single-post">
	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-hero">
				<?php the_post_thumbnail( 'full', array( 'class' => 'post-hero__img' ) ); ?>
			</div>
		<?php endif; ?>

		<article class="post-content">
			<div class="post-content__inner">
				<header class="post-content__header">
					<h1 class="post-content__title"><?php the_title(); ?></h1>
					<time class="post-content__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<?php echo esc_html( get_the_date() ); ?>
					</time>
				</header>

				<div class="post-content__body">
					<?php the_content(); ?>
				</div>

				<?php
				$tags = get_the_tags();
				if ( $tags ) :
					?>
					<div class="post-content__tags">
						<?php foreach ( $tags as $tag ) : ?>
							<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="post-tag"><?php echo esc_html( $tag->name ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</article>

		<nav class="post-nav">
			<div class="post-nav__inner">
				<?php
				$prev = get_previous_post();
				$next = get_next_post();
				?>
				<?php if ( $prev ) : ?>
					<a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="post-nav__link post-nav__link--prev">
						<span class="post-nav__arrow">&larr;</span>
						<span class="post-nav__label"><?php echo esc_html( $prev->post_title ); ?></span>
					</a>
				<?php else : ?>
					<span class="post-nav__link post-nav__link--empty"></span>
				<?php endif; ?>

				<?php if ( $next ) : ?>
					<a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="post-nav__link post-nav__link--next">
						<span class="post-nav__label"><?php echo esc_html( $next->post_title ); ?></span>
						<span class="post-nav__arrow">&rarr;</span>
					</a>
				<?php else : ?>
					<span class="post-nav__link post-nav__link--empty"></span>
				<?php endif; ?>
			</div>
		</nav>

		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
