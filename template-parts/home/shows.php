<?php
/**
 * Full-width Shows section (Bandsintown).
 *
 * Not used on the front page (the sidebar widget is), kept for reuse in other templates.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="shows" id="shows">
	<div class="shows__inner">

		<div class="shows__header">
			<h2 class="shows__title"><?php esc_html_e( 'Próximos', 'santiago-moraes' ); ?><br><?php esc_html_e( 'shows', 'santiago-moraes' ); ?></h2>
			<p class="shows__meta mono-label"><?php esc_html_e( 'Agenda · Bandsintown', 'santiago-moraes' ); ?></p>
		</div>

		<div class="shows__list">
			<?php sm_bandsintown_list( array( 'limit' => 0 ) ); ?>

			<a href="<?php echo esc_url( sm_bandsintown_artist_url() ); ?>" class="link-arrow" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Ver todos los shows →', 'santiago-moraes' ); ?>
			</a>
		</div>

	</div>
</section>
