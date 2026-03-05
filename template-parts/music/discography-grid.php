<?php
/**
 * Discography Grid — Album cards in a 2-column grid.
 *
 * Expects $sm_albums (array of WP_Term) and $sm_section_title (string)
 * to be set via set_query_var().
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$albums        = get_query_var( 'sm_albums' );
$section_title = get_query_var( 'sm_section_title' );
$is_demo       = get_query_var( 'sm_is_demo' );

if ( empty( $albums ) ) {
	return;
}
?>

<section class="discography<?php echo $is_demo ? ' discography--demos' : ''; ?>">
	<div class="discography__inner">
		<?php if ( $section_title ) : ?>
			<h2 class="discography__title"><?php echo esc_html( $section_title ); ?></h2>
		<?php endif; ?>

		<div class="discography__grid">
			<?php foreach ( $albums as $album_term ) :
				$year     = get_term_meta( $album_term->term_id, '_album_year', true );
				$cover_id = get_term_meta( $album_term->term_id, '_album_cover_id', true );
				$link     = get_term_link( $album_term );

				if ( is_wp_error( $link ) ) {
					continue;
				}

				// Cover image.
				if ( $cover_id ) {
					$cover_tag = wp_get_attachment_image(
						(int) $cover_id,
						'medium_large',
						false,
						array(
							'loading' => 'lazy',
							'alt'     => esc_attr( $album_term->name ),
							'class'   => 'album-card__img',
						)
					);
				} else {
					$cover_tag = '<img src="' . esc_url( SM_THEME_URI . '/assets/images/album-placeholder.webp' ) . '" alt="' . esc_attr( $album_term->name ) . '" width="600" height="600" loading="lazy" class="album-card__img">';
				}
				?>

				<a href="<?php echo esc_url( $link ); ?>" class="album-card">
					<div class="album-card__cover">
						<?php echo $cover_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<div class="album-card__overlay">
							<span class="album-card__cta"><?php esc_html_e( 'Ver album', 'santiago-moraes' ); ?></span>
						</div>
					</div>
					<div class="album-card__info">
						<h3 class="album-card__name"><?php echo esc_html( $album_term->name ); ?></h3>
						<?php if ( $year ) : ?>
							<span class="album-card__year"><?php echo esc_html( $year ); ?></span>
						<?php endif; ?>
					</div>
				</a>

			<?php endforeach; ?>
		</div>
	</div>
</section>
