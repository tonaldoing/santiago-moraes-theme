<?php
/**
 * Sidebar widget — shop link (Theme Options > Musica, falls back to an album's vinyl URL).
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$shop_url = sm_get_shop_url();

if ( ! $shop_url ) {
	return;
}

$shop_label = sm_get_option( 'sm_shop_label', __( 'Vinilo', 'santiago-moraes' ) );
$shop_text  = sm_get_option( 'sm_shop_text', __( 'Discos físicos y merch.', 'santiago-moraes' ) );
?>

<section class="widget widget--shop">
	<h2 class="widget__title"><?php esc_html_e( 'Tienda', 'santiago-moraes' ); ?></h2>

	<?php if ( $shop_text ) : ?>
		<p class="widget__text"><?php echo esc_html( $shop_text ); ?></p>
	<?php endif; ?>

	<a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn--brick" target="_blank" rel="noopener noreferrer">
		<?php echo esc_html( $shop_label ); ?> &rarr;
	</a>
</section>
