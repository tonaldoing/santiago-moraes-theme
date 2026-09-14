<?php
/**
 * Homepage sidebar — stacked widgets (search, shows, recent songs, listen, shop, contact).
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;
?>

<aside class="home-sidebar" aria-label="<?php esc_attr_e( 'Secundario', 'santiago-moraes' ); ?>">
	<?php
	get_template_part( 'template-parts/home/widgets/search' );
	get_template_part( 'template-parts/home/widgets/shows' );
	get_template_part( 'template-parts/home/widgets/listen' );
	get_template_part( 'template-parts/home/widgets/shop' );
	get_template_part( 'template-parts/home/widgets/contact' );
	?>
</aside>
