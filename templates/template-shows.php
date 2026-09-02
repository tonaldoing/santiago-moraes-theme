<?php
/**
 * Template Name: Shows
 * Template Post Type: page
 *
 * Full page for shows listing via Songkick widget.
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main page-shows">

	<header class="shows-header">
		<div class="shows-header__inner">
			<h1 class="shows-header__title"><?php esc_html_e( 'Shows', 'santiago-moraes' ); ?></h1>
		</div>
	</header>

	<section class="shows-content">
		<div class="shows-content__inner">
			<aside class="shows-content__side" aria-hidden="true">
				<img src="<?php echo esc_url( SM_THEME_URI . '/assets/images/ilu-manos.jpg' ); ?>" alt="" width="1200" height="675" loading="lazy" decoding="async">
			</aside>
			<div class="shows-content__widget">
				<?php sm_songkick_widget( array( 'theme' => 'light' ) ); ?>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
