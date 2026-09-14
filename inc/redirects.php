<?php
/**
 * Legacy URL redirects and the one-time "Cancionero" content migration.
 *
 * The home page is now the discography and the songs-with-chords page is
 * called "Cancionero", so /musica, /acordes and the old /shows page redirect.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Redirect legacy pages:
 * - /shows   → home shows widget
 * - /musica  → home discography
 * - /acordes → Cancionero page (once its slug has changed)
 */
function sm_legacy_redirects() {
	if ( is_admin() ) {
		return;
	}

	if ( is_page( 'shows' ) || is_page( 'musica' ) ) {
		wp_safe_redirect( home_url( is_page( 'shows' ) ? '/#shows' : '/#discos' ), 301 );
		exit;
	}

	if ( is_404() ) {
		$path = trim( (string) wp_parse_url( add_query_arg( array() ), PHP_URL_PATH ), '/' );

		if ( 'acordes' === $path ) {
			$target = sm_cancionero_url();
			if ( $target && false === strpos( $target, '/acordes/' ) ) {
				wp_safe_redirect( $target, 301 );
				exit;
			}
		}
	}
}
add_action( 'template_redirect', 'sm_legacy_redirects' );

/**
 * One-time migration (guarded by an option so it runs once per environment):
 * - The page using the Cancionero template gets the slug "cancionero" (if it still is "acordes").
 * - In every nav menu, the item pointing to that page is renamed "Cancionero" and moved to
 *   the top level; items pointing to /musica are removed, and a "Música" parent left without
 *   children is removed too.
 */
function sm_migrate_cancionero() {
	if ( get_option( 'sm_migration_cancionero' ) ) {
		return;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => 'templates/template-acordes.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);

	if ( empty( $pages ) ) {
		return;
	}

	$page = $pages[0];

	if ( 'acordes' === $page->post_name ) {
		wp_update_post(
			array(
				'ID'         => $page->ID,
				'post_name'  => 'cancionero',
				'post_title' => 'Cancionero',
			)
		);
	}

	foreach ( wp_get_nav_menus() as $menu ) {
		$items       = wp_get_nav_menu_items( $menu->term_id ) ?: array();
		$parents_hit = array();

		foreach ( $items as $item ) {
			$url = (string) $item->url;

			if ( 'page' === $item->object && (int) $item->object_id === $page->ID ) {
				if ( $item->menu_item_parent ) {
					$parents_hit[] = (int) $item->menu_item_parent;
				}
				wp_update_nav_menu_item(
					$menu->term_id,
					$item->ID,
					array(
						'menu-item-title'     => 'Cancionero',
						'menu-item-parent-id' => 0,
						'menu-item-position'  => 1,
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $page->ID,
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					)
				);
				wp_update_post( array( 'ID' => $item->ID, 'menu_order' => 0 ) );
				continue;
			}

			if ( preg_match( '#/musica/?$#', $url ) || preg_match( '#/acordes/?$#', $url ) ) {
				if ( $item->menu_item_parent ) {
					$parents_hit[] = (int) $item->menu_item_parent;
				}
				wp_delete_post( $item->ID, true );
			}
		}

		foreach ( array_unique( $parents_hit ) as $parent_id ) {
			$children = array_filter(
				wp_get_nav_menu_items( $menu->term_id ) ?: array(),
				fn( $i ) => (int) $i->menu_item_parent === $parent_id
			);
			if ( empty( $children ) ) {
				wp_delete_post( $parent_id, true );
			}
		}
	}

	update_option( 'sm_migration_cancionero', time(), false );
}
add_action( 'init', 'sm_migrate_cancionero', 20 );
