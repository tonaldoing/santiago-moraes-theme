<?php
/**
 * Contact form processing — AJAX handler with honeypot, nonce, rate limiting.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_sm_contact_form', 'sm_handle_contact_form' );
add_action( 'wp_ajax_nopriv_sm_contact_form', 'sm_handle_contact_form' );

/**
 * Handle AJAX contact form submission.
 */
function sm_handle_contact_form() {
	// Verify nonce.
	if ( ! isset( $_POST['sm_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sm_contact_nonce'] ) ), 'sm_contact_form' ) ) {
		wp_send_json_error( array( 'message' => __( 'Error de seguridad. Recarga la pagina e intenta de nuevo.', 'santiago-moraes' ) ) );
	}

	// Honeypot check — if filled, it's a bot.
	if ( ! empty( $_POST['sm_website'] ) ) {
		// Silently succeed to not tip off bots.
		wp_send_json_success( array( 'message' => __( 'Mensaje enviado correctamente.', 'santiago-moraes' ) ) );
	}

	// Rate limiting — max 3 submissions per IP per hour.
	$ip = sm_get_client_ip();
	$transient_key = 'sm_contact_' . md5( $ip );
	$count = (int) get_transient( $transient_key );

	if ( $count >= 3 ) {
		wp_send_json_error( array( 'message' => __( 'Demasiados mensajes enviados. Intenta de nuevo mas tarde.', 'santiago-moraes' ) ) );
	}

	// Validate fields.
	$name    = isset( $_POST['sm_name'] ) ? sanitize_text_field( wp_unslash( $_POST['sm_name'] ) ) : '';
	$email   = isset( $_POST['sm_email'] ) ? sanitize_email( wp_unslash( $_POST['sm_email'] ) ) : '';
	$subject = isset( $_POST['sm_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['sm_subject'] ) ) : '';
	$message = isset( $_POST['sm_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sm_message'] ) ) : '';

	if ( empty( $name ) ) {
		wp_send_json_error( array( 'message' => __( 'El nombre es obligatorio.', 'santiago-moraes' ) ) );
	}

	if ( empty( $email ) || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Ingresa un email valido.', 'santiago-moraes' ) ) );
	}

	if ( empty( $message ) ) {
		wp_send_json_error( array( 'message' => __( 'El mensaje es obligatorio.', 'santiago-moraes' ) ) );
	}

	// Recipient — from Theme Options or fallback to admin email.
	$to = sm_get_option( 'sm_contact_email', get_option( 'admin_email' ) );

	// Build email.
	$email_subject = ! empty( $subject )
		? sprintf( '[Santiago Moraes] %s', $subject )
		: sprintf( '[Santiago Moraes] %s', __( 'Mensaje de contacto', 'santiago-moraes' ) );

	$body  = sprintf( __( 'Nombre: %s', 'santiago-moraes' ), $name ) . "\n";
	$body .= sprintf( __( 'Email: %s', 'santiago-moraes' ), $email ) . "\n";
	if ( $subject ) {
		$body .= sprintf( __( 'Asunto: %s', 'santiago-moraes' ), $subject ) . "\n";
	}
	$body .= "\n" . __( 'Mensaje:', 'santiago-moraes' ) . "\n" . $message;

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	$sent = wp_mail( $to, $email_subject, $body, $headers );

	if ( $sent ) {
		// Increment rate limit counter.
		set_transient( $transient_key, $count + 1, HOUR_IN_SECONDS );
		wp_send_json_success( array( 'message' => __( 'Mensaje enviado correctamente. Te responderemos pronto.', 'santiago-moraes' ) ) );
	} else {
		wp_send_json_error( array( 'message' => __( 'Error al enviar el mensaje. Intenta de nuevo.', 'santiago-moraes' ) ) );
	}
}

/**
 * Get client IP address.
 *
 * @return string
 */
function sm_get_client_ip() {
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ips = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
		return trim( $ips[0] );
	}

	return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '127.0.0.1';
}
