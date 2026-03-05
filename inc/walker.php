<?php
/**
 * Custom Nav Menu Walker — adds submenu toggle buttons for parent items.
 *
 * Used by both desktop and mobile menus. Outputs a chevron <button>
 * after the link for items that have children, enabling JS-driven
 * accordion behavior on mobile and hover dropdowns on desktop.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Walker that adds a toggle button to menu items with children.
 */
class SM_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the element output.
	 *
	 * Adds `menu-item-has-children` detection and a chevron toggle button
	 * for parent items.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		// Let the parent class handle the standard output first.
		parent::start_el( $output, $item, $depth, $args, $id );

		// If this item has children, inject a toggle button after the link.
		if ( in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
			$chevron_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 320 512" fill="currentColor"><path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/></svg>';

			$output .= '<button class="submenu-toggle" aria-expanded="false" aria-label="' . esc_attr__( 'Abrir submenu', 'santiago-moraes' ) . '">';
			$output .= $chevron_svg;
			$output .= '</button>';
		}
	}
}
