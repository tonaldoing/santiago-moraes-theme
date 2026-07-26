<?php
/**
 * Render: sm/upcoming-shows (Songkick widget)
 *
 * @package Santiago_Moraes
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    InnerBlocks content (unused).
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$theme  = $attributes['theme'] ?? 'dark';
$anchor = ! empty( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '';

wp_enqueue_script( 'songkick-widget', SM_SONGKICK_WIDGET_JS, array(), null, true );
?>

<section class="shows shows--block"<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="shows__inner">
		<h2 class="shows__title"><?php esc_html_e( 'Próximos Shows', 'santiago-moraes' ); ?></h2>
		<div class="shows__widget">
			<?php sm_songkick_widget( array( 'theme' => $theme ) ); ?>
		</div>
	</div>
</section>
