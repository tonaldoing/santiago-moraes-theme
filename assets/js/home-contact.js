/**
 * Home contact form — compact AJAX submission.
 *
 * Reuses the same wp_ajax action (sm_contact_form) as the full contact page.
 *
 * @package Santiago_Moraes
 */
( function () {
	'use strict';

	var form   = document.getElementById( 'sm-home-contact-form' );
	var status = document.getElementById( 'sm-home-contact-status' );
	var submit = document.getElementById( 'sm-home-contact-submit' );

	if ( ! form || ! status || ! submit ) {
		return;
	}

	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();

		var email   = form.querySelector( '[name="sm_email"]' ).value.trim();
		var message = form.querySelector( '[name="sm_message"]' ).value.trim();

		if ( ! email || ! message ) {
			showStatus( 'Completa todos los campos.', 'error' );
			return;
		}

		submit.disabled = true;
		submit.textContent = 'Enviando...';
		status.textContent = '';
		status.className = 'home-contact__status';

		var data = new FormData( form );

		fetch( smContactData.ajaxUrl, {
			method: 'POST',
			body: data,
			credentials: 'same-origin',
		} )
		.then( function ( response ) {
			return response.json();
		} )
		.then( function ( result ) {
			if ( result.success ) {
				showStatus( result.data.message, 'success' );
				form.reset();
			} else {
				showStatus( result.data.message || 'Error al enviar.', 'error' );
			}
		} )
		.catch( function () {
			showStatus( 'Error de conexion. Intenta de nuevo.', 'error' );
		} )
		.finally( function () {
			submit.disabled = false;
			submit.textContent = 'Enviar';
		} );
	} );

	function showStatus( msg, type ) {
		status.textContent = msg;
		status.className = 'home-contact__status home-contact__status--' + type;
	}
} )();
