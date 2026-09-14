<?php
/**
 * Compact album card — cover + "Acordes" stamp + name + meta.
 *
 * Usage: get_template_part( 'template-parts/music/album-card', null, array( 'term' => $term, 'eager' => true ) );
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$album_term = $args['term'] ?? null;
$eager      = ! empty( $args['eager'] );

if ( ! $album_term instanceof WP_Term ) {
	return;
}

$link = get_term_link( $album_term );

if ( is_wp_error( $link ) ) {
	return;
}

$cover_id   = get_term_meta( $album_term->term_id, '_album_cover_id', true );
$year       = get_term_meta( $album_term->term_id, '_album_year', true );
$is_demo    = (bool) get_term_meta( $album_term->term_id, '_album_is_demo', true );
$has_chords = sm_album_has_chords( $album_term->term_id );

$aria = $album_term->name . ( $has_chords ? ' — ' . __( 'con acordes', 'santiago-moraes' ) : '' );
$meta = array_filter( array( $is_demo ? __( 'Descartes', 'santiago-moraes' ) : '', $year ) );
?>

<a href="<?php echo esc_url( $link ); ?>" class="disco-card disco-card--compact<?php echo $has_chords ? ' disco-card--has-chords' : ''; ?>" aria-label="<?php echo esc_attr( $aria ); ?>">
	<span class="disco-card__cover">
		<?php if ( $cover_id ) : ?>
			<?php
			echo wp_get_attachment_image(
				(int) $cover_id,
				'medium',
				false,
				array(
					'loading' => $eager ? 'eager' : 'lazy',
					'alt'     => '',
					'class'   => 'disco-card__img',
				)
			);
			?>
		<?php else : ?>
			<span class="disco-card__placeholder">
				<span class="mono-label"><?php echo esc_html( $album_term->name ); ?></span>
			</span>
		<?php endif; ?>

		<?php if ( $has_chords ) : ?>
			<span class="disco-card__stamp" aria-hidden="true"><?php esc_html_e( 'Acordes', 'santiago-moraes' ); ?></span>
		<?php endif; ?>
	</span>

	<span class="disco-card__name"><?php echo esc_html( $album_term->name ); ?></span>
	<?php if ( $meta ) : ?>
		<span class="disco-card__meta mono-label"><?php echo esc_html( implode( ' · ', $meta ) ); ?></span>
	<?php endif; ?>
</a>
