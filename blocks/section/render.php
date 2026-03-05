<?php
/**
 * Render: sm/section
 *
 * Generic section wrapper with configurable bg, padding, and width.
 * Supports InnerBlocks for nested content.
 *
 * @package Santiago_Moraes
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    InnerBlocks content.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$bg      = $attributes['bgColor'] ?? 'light';
$padding = $attributes['padding'] ?? 'normal';
$width   = $attributes['width'] ?? 'normal';

$section_classes = array( 'sm-section', 'sm-section--' . $bg );

if ( 'normal' !== $padding ) {
	$section_classes[] = 'sm-section--' . $padding;
}

$inner_classes = array( 'sm-section__inner' );

if ( 'narrow' === $width ) {
	$inner_classes[] = 'sm-section__inner--narrow';
} elseif ( 'full' === $width ) {
	$inner_classes[] = 'sm-section__inner--full';
}

$anchor = ! empty( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '';
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>"<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="<?php echo esc_attr( implode( ' ', $inner_classes ) ); ?>">
		<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- InnerBlocks content. ?>
	</div>
</section>
