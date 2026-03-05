/**
 * Events archive — toggle past shows visibility.
 *
 * @package Santiago_Moraes
 */
( function () {
	'use strict';

	var btn  = document.getElementById( 'events-toggle' );
	var list = document.getElementById( 'events-past-list' );

	if ( ! btn || ! list ) {
		return;
	}

	btn.addEventListener( 'click', function () {
		var expanded = btn.getAttribute( 'aria-expanded' ) === 'true';
		btn.setAttribute( 'aria-expanded', String( ! expanded ) );
		list.hidden = expanded;
		btn.textContent = expanded ? 'Ver shows pasados' : 'Ocultar shows pasados';
	} );
} )();
