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
			<?php sm_songkick_widget( array( 'theme' => 'light' ) ); ?>
		</div>
	</section>

</main>

<?php
get_footer();
