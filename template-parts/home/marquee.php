<?php
/**
 * Homepage announcement bar.
 *
 * Replaces the old marquee ticker. Editable from admin:
 * Apariencia > Santiago Moraes > General > Barra de anuncio.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$text = sm_get_option( 'sm_announcement_text', '' );
$url  = sm_get_option( 'sm_announcement_url', '' );

if ( ! $text ) {
	return;
}

$tag       = $url ? 'a' : 'div';
$attrs     = $url ? ' href="' . esc_url( $url ) . '"' : '';
$is_external = $url && false === strpos( $url, home_url() );
if ( $is_external ) {
	$attrs .= ' target="_blank" rel="noopener noreferrer"';
}
?>

<section class="announcement">
	<<?php echo $tag; ?> class="announcement__inner"<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="announcement__text"><?php echo esc_html( $text ); ?></span>
		<?php if ( $url ) : ?>
			<span class="announcement__arrow">&rarr;</span>
		<?php endif; ?>
	</<?php echo $tag; ?>>
</section>
