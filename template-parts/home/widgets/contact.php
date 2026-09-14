<?php
/**
 * Sidebar widget — contact.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$contact_email = sm_get_option( 'sm_contact_email', '' );
?>

<section class="widget widget--contact" id="contacto">
	<h2 class="widget__title"><?php esc_html_e( 'Contacto', 'santiago-moraes' ); ?></h2>

	<p class="widget__text"><?php esc_html_e( 'Fechas, prensa, o simplemente para decir algo sobre una canción.', 'santiago-moraes' ); ?></p>

	<?php if ( $contact_email ) : ?>
		<a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="widget__email mono-label"><?php echo esc_html( $contact_email ); ?></a>
	<?php endif; ?>

	<a href="<?php echo esc_url( sm_contact_url() ); ?>" class="link-arrow widget__more">
		<?php esc_html_e( 'Escribir →', 'santiago-moraes' ); ?>
	</a>
</section>
