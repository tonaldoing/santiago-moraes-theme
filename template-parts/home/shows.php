<?php
/**
 * Homepage Shows section — Rebranding.
 *
 * 2-column layout with title left, Songkick widget right.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="shows" id="shows">
	<div class="shows__inner">

		<div class="shows__header">
			<h2 class="shows__title"><?php esc_html_e( 'Próximos', 'santiago-moraes' ); ?><br><?php esc_html_e( 'shows', 'santiago-moraes' ); ?></h2>
			<p class="shows__meta mono-label"><?php esc_html_e( 'Agenda 2026 · Songkick', 'santiago-moraes' ); ?></p>
		</div>

		<div class="shows__list">
			<?php sm_songkick_widget( array( 'theme' => 'editorial' ) ); ?>

			<a href="<?php echo esc_url( SM_SONGKICK_ARTIST_URL ); ?>" class="link-arrow" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Ver todos los shows →', 'santiago-moraes' ); ?>
			</a>
		</div>

	</div>
</section>
