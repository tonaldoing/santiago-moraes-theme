<?php
/**
 * Sidebar widget — upcoming shows (Bandsintown).
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$limit = (int) sm_get_option( 'sm_shows_limit', 5 );
?>

<section class="widget widget--shows" id="shows">
	<h2 class="widget__title"><?php esc_html_e( 'Próximos shows', 'santiago-moraes' ); ?></h2>

	<div class="widget-shows__list">
		<?php sm_bandsintown_list( array( 'limit' => $limit, 'modifier' => 'compact' ) ); ?>
	</div>

	<a href="<?php echo esc_url( sm_bandsintown_artist_url() ); ?>" class="link-arrow widget__more" target="_blank" rel="noopener noreferrer">
		<?php esc_html_e( 'Todas las fechas →', 'santiago-moraes' ); ?>
	</a>
</section>
