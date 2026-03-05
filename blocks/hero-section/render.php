<?php
/**
 * Render: sm/hero-section
 *
 * Server-side rendered hero block. Reuses .hero BEM classes.
 *
 * @package Santiago_Moraes
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    InnerBlocks content (unused).
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$bg_style   = $attributes['bgStyle'] ?? 'dark';
$line1      = $attributes['headingLine1'] ?? 'Santiago';
$line2      = $attributes['headingLine2'] ?? 'Moraes';
$desc       = $attributes['heroDescription'] ?? '';
$image_id   = $attributes['imageId'] ?? 0;
$image_url  = $attributes['imageUrl'] ?? '';
$btn1_text  = $attributes['button1Text'] ?? '';
$btn1_url   = $attributes['button1Url'] ?? '';
$btn2_text  = $attributes['button2Text'] ?? '';
$btn2_url   = $attributes['button2Url'] ?? '';

// Build hero image tag.
if ( $image_id ) {
	$hero_image_tag = wp_get_attachment_image( $image_id, 'full', false, array(
		'loading'  => 'eager',
		'decoding' => 'async',
		'alt'      => esc_attr( $line1 . ' ' . $line2 ),
	) );
} elseif ( $image_url ) {
	$hero_image_tag = '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $line1 . ' ' . $line2 ) . '" loading="eager" decoding="async">';
} else {
	$hero_image_tag = '<img src="' . esc_url( SM_THEME_URI . '/assets/images/hero-placeholder.webp' ) . '" alt="' . esc_attr( $line1 . ' ' . $line2 ) . '" width="1000" height="741" loading="eager">';
}

$section_class = 'hero';
if ( 'light' === $bg_style ) {
	$section_class .= ' hero--light';
}

$anchor = ! empty( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '';
?>

<section class="<?php echo esc_attr( $section_class ); ?>"<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="hero__inner">

		<div class="hero__content">
			<h1 class="hero__title">
				<?php echo esc_html( $line1 ); ?>
				<span class="hero__accent"><?php echo esc_html( $line2 ); ?></span>
			</h1>

			<?php if ( $desc ) : ?>
				<p class="hero__description text-description"><?php echo esc_html( $desc ); ?></p>
			<?php endif; ?>

			<div class="hero__buttons">
				<?php if ( $btn1_text && $btn1_url ) : ?>
					<a href="<?php echo esc_url( $btn1_url ); ?>" class="btn btn--primary" <?php echo ( false === strpos( $btn1_url, '#' ) ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
						<span class="btn__icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512" fill="currentColor"><path d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM188.3 147.1c7.6-4.2 16.8-4.1 24.3 .5l144 88c7.1 4.4 11.5 12.1 11.5 20.5s-4.4 16.1-11.5 20.5l-144 88c-7.4 4.5-16.7 4.7-24.3 .5s-12.3-12.2-12.3-20.9V168c0-8.7 4.7-16.7 12.3-20.9z"/></svg>
						</span>
						<?php echo esc_html( $btn1_text ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $btn2_text && $btn2_url ) : ?>
					<a href="<?php echo esc_url( $btn2_url ); ?>" class="btn btn--ghost">
						<?php echo esc_html( $btn2_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="hero__image">
			<?php echo $hero_image_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

	</div>
</section>
