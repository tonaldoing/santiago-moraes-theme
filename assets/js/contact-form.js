/**
 * Contact form — AJAX submission.
 *
 * @package Santiago_Moraes
 */
( function () {
	'use strict';

	var form   = document.getElementById( 'sm-contact-form' );
	var status = document.getElementById( 'sm-contact-status' );
	var submit = document.getElementById( 'sm-contact-submit' );

	if ( ! form || ! status || ! submit ) {
		return;
	}

	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();

		// Basic client-side validation.
		var name    = form.querySelector( '[name="sm_name"]' ).value.trim();
		var email   = form.querySelector( '[name="sm_email"]' ).value.trim();
		var message = form.querySelector( '[name="sm_message"]' ).value.trim();

		if ( ! name || ! email || ! message ) {
			showStatus( 'Por favor completa todos los campos obligatorios.', 'error' );
			return;
		}

		// Disable button.
		submit.disabled = true;
		submit.textContent = 'Enviando...';
		status.textContent = '';
		status.className = 'contact-form__status';

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
			submit.textContent = 'Enviar Mensaje';
		} );
	} );

	function showStatus( msg, type ) {
		status.textContent = msg;
		status.className = 'contact-form__status contact-form__status--' + type;
	}
} )();
