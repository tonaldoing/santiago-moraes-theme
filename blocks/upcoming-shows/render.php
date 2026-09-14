<?php
/**
 * Render: sm/upcoming-shows (Bandsintown list)
 *
 * @package Santiago_Moraes
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    InnerBlocks content (unused).
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$anchor = ! empty( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '';
?>

<section class="shows shows--block"<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shows__inner">
		<h2 class="shows__title"><?php esc_html_e( 'Próximos Shows', 'santiago-moraes' ); ?></h2>
		<div class="shows__list">
			<?php sm_bandsintown_list( array( 'limit' => 0 ) ); ?>

			<a href="<?php echo esc_url( sm_bandsintown_artist_url() ); ?>" class="link-arrow" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Ver todos los shows →', 'santiago-moraes' ); ?>
			</a>
		</div>
	</div>
</section>
