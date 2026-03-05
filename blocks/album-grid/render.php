<?php
/**
 * Render: sm/album-grid
 *
 * Dynamic block — queries album taxonomy terms.
 * Reuses .discography / .album-card BEM classes.
 *
 * @package Santiago_Moraes
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    InnerBlocks content (unused).
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$columns    = absint( $attributes['columns'] ?? 2 );
$show_demos = $attributes['showDemos'] ?? false;
$show_year  = $attributes['showYear'] ?? true;

// Query album terms.
$args = array(
	'taxonomy'   => 'album',
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'ASC',
);

$albums = get_terms( $args );

if ( is_wp_error( $albums ) || empty( $albums ) ) {
	return;
}

// Filter by demo status if needed.
if ( ! $show_demos ) {
	$albums = array_filter( $albums, function ( $term ) {
		return ! get_term_meta( $term->term_id, '_album_is_demo', true );
	} );
}

if ( empty( $albums ) ) {
	return;
}

$grid_class = 'discography__grid';
if ( 3 === $columns ) {
	$grid_class .= ' discography__grid--3col';
}

$anchor = ! empty( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '';
?>

<section class="discography"<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="discography__inner">
		<div class="<?php echo esc_attr( $grid_class ); ?>">
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
						<?php if ( $show_year && $year ) : ?>
							<span class="album-card__year"><?php echo esc_html( $year ); ?></span>
						<?php endif; ?>
					</div>
				</a>

			<?php endforeach; ?>
		</div>
	</div>
</section>
