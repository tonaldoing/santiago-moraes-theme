<?php
/**
 * Block Patterns — pre-built layouts using custom blocks.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'sm_register_block_patterns' );

/**
 * Register custom block pattern category and patterns.
 */
function sm_register_block_patterns() {
	register_block_pattern_category(
		'sm-layouts',
		array( 'label' => __( 'Santiago Moraes', 'santiago-moraes' ) )
	);

	// -------------------------------------------------------------------------
	// 1. Página de Artista / Bio.
	// -------------------------------------------------------------------------
	register_block_pattern(
		'sm/artist-bio',
		array(
			'title'       => __( 'Pagina de Artista / Bio', 'santiago-moraes' ),
			'description' => __( 'Hero claro, biografía, plataformas y redes sociales.', 'santiago-moraes' ),
			'categories'  => array( 'sm-layouts' ),
			'content'     => '<!-- wp:sm/hero-section {"bgStyle":"light","headingLine1":"Santiago","headingLine2":"Moraes","heroDescription":"Cantautor argentino radicado en La Plata.","button1Text":"Escuchar","button1Url":"#","button2Text":"Contacto","button2Url":"#contacto"} /-->

<!-- wp:sm/section {"bgColor":"light","padding":"normal","width":"narrow","anchor":"bio"} -->
<!-- wp:heading -->
<h2>Biografía</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Escribí aquí la biografía del artista. Podés agregar varios párrafos, imágenes y cualquier bloque de WordPress dentro de esta sección.</p>
<!-- /wp:paragraph -->
<!-- /wp:sm/section -->

<!-- wp:sm/section {"bgColor":"cream","padding":"compact","width":"normal"} -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Escuchá en</h2>
<!-- /wp:heading -->

<!-- wp:sm/platform-links {"links":[{"platform":"spotify","url":""},{"platform":"bandcamp","url":""},{"platform":"youtube","url":""}]} /-->
<!-- /wp:sm/section -->

<!-- wp:sm/social-links {"size":"large","alignment":"center"} /-->',
		)
	);

	// -------------------------------------------------------------------------
	// 2. Página de Contacto.
	// -------------------------------------------------------------------------
	register_block_pattern(
		'sm/contact-page',
		array(
			'title'       => __( 'Pagina de Contacto', 'santiago-moraes' ),
			'description' => __( 'Sección clara con formulario completo y redes sociales.', 'santiago-moraes' ),
			'categories'  => array( 'sm-layouts' ),
			'content'     => '<!-- wp:sm/section {"bgColor":"light","padding":"normal","width":"narrow","anchor":"contacto"} -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Contacto</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">¿Querés contactarme para shows, colaboraciones o consultas? Completá el formulario.</p>
<!-- /wp:paragraph -->

<!-- wp:sm/contact-form {"formType":"full","showSocial":true} /-->
<!-- /wp:sm/section -->',
		)
	);

	// -------------------------------------------------------------------------
	// 3. Landing de Música.
	// -------------------------------------------------------------------------
	register_block_pattern(
		'sm/music-landing',
		array(
			'title'       => __( 'Landing de Musica', 'santiago-moraes' ),
			'description' => __( 'Hero oscuro, discografía y últimas canciones.', 'santiago-moraes' ),
			'categories'  => array( 'sm-layouts' ),
			'content'     => '<!-- wp:sm/hero-section {"bgStyle":"dark","headingLine1":"Mi","headingLine2":"Música","button1Text":"Spotify","button1Url":"#","button2Text":"Ver todo","button2Url":"#discografia"} /-->

<!-- wp:sm/section {"bgColor":"light","padding":"normal","width":"normal","anchor":"discografia"} -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Discografía</h2>
<!-- /wp:heading -->

<!-- wp:sm/album-grid {"columns":2,"showDemos":false,"showYear":true} /-->
<!-- /wp:sm/section -->

<!-- wp:sm/latest-songs {"count":4,"showChordsBadge":true} /-->',
		)
	);

	// -------------------------------------------------------------------------
	// 4. Sección de Shows + Contacto.
	// -------------------------------------------------------------------------
	register_block_pattern(
		'sm/shows-contact',
		array(
			'title'       => __( 'Shows + Contacto', 'santiago-moraes' ),
			'description' => __( 'Próximos shows seguidos de formulario de contacto simple sobre fondo oscuro.', 'santiago-moraes' ),
			'categories'  => array( 'sm-layouts' ),
			'content'     => '<!-- wp:sm/upcoming-shows {"count":4,"showViewAll":true} /-->

<!-- wp:sm/section {"bgColor":"dark","padding":"normal","width":"narrow","anchor":"contacto"} -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Contacto</h2>
<!-- /wp:heading -->

<!-- wp:sm/contact-form {"formType":"simple","showSocial":true} /-->
<!-- /wp:sm/section -->',
		)
	);

	// -------------------------------------------------------------------------
	// 5. Página Genérica.
	// -------------------------------------------------------------------------
	register_block_pattern(
		'sm/generic-page',
		array(
			'title'       => __( 'Pagina Generica', 'santiago-moraes' ),
			'description' => __( 'Sección clara con título y contenido libre.', 'santiago-moraes' ),
			'categories'  => array( 'sm-layouts' ),
			'content'     => '<!-- wp:sm/section {"bgColor":"light","padding":"normal","width":"narrow"} -->
<!-- wp:heading -->
<h2>Título de la sección</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Contenido de la página. Podés agregar texto, imágenes, videos y cualquier bloque de WordPress.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Agregá más bloques según necesites.</p>
<!-- /wp:paragraph -->
<!-- /wp:sm/section -->',
		)
	);
}
