<?php
/**
 * Homepage — Contacto section — Rebranding.
 *
 * Brick red background with 2-column layout: text left, form right.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$contact_email = sm_get_option( 'sm_contact_email', 'hola@santiagomoraes.com.ar' );
?>

<section class="home-contact" id="contacto">
	<div class="home-contact__inner">

		<div class="home-contact__info">
			<h2 class="home-contact__title"><?php esc_html_e( 'Contacto', 'santiago-moraes' ); ?></h2>
			<p class="home-contact__description">
				<?php esc_html_e( 'Fechas, prensa, o simplemente para decir algo sobre una canción.', 'santiago-moraes' ); ?>
			</p>
			<?php if ( $contact_email ) : ?>
				<p class="home-contact__email mono-label"><?php echo esc_html( $contact_email ); ?></p>
			<?php endif; ?>
		</div>

		<form class="home-contact__form" id="sm-home-contact-form" method="post">
			<input type="hidden" name="action" value="sm_contact_form">
			<?php wp_nonce_field( 'sm_contact_form', 'sm_contact_nonce' ); ?>

			<div class="contact-form__hp" aria-hidden="true">
				<label for="sm_hp_website">Website</label>
				<input type="text" name="sm_website" id="sm_hp_website" tabindex="-1" autocomplete="off">
			</div>

			<label for="sm-home-name" class="sr-only"><?php esc_html_e( 'Tu nombre', 'santiago-moraes' ); ?></label>
			<input
				type="text"
				name="sm_name"
				id="sm-home-name"
				class="home-contact__input"
				placeholder="<?php esc_attr_e( 'Tu nombre', 'santiago-moraes' ); ?>"
				required
			>

			<label for="sm-home-email" class="sr-only"><?php esc_html_e( 'Tu email', 'santiago-moraes' ); ?></label>
			<input
				type="email"
				name="sm_email"
				id="sm-home-email"
				class="home-contact__input"
				placeholder="<?php esc_attr_e( 'Tu email', 'santiago-moraes' ); ?>"
				required
			>

			<label for="sm-home-message" class="sr-only"><?php esc_html_e( 'Tu mensaje', 'santiago-moraes' ); ?></label>
			<textarea
				name="sm_message"
				id="sm-home-message"
				class="home-contact__input"
				rows="4"
				placeholder="<?php esc_attr_e( 'Tu mensaje', 'santiago-moraes' ); ?>"
				required
			></textarea>

			<button type="submit" class="home-contact__submit" id="sm-home-contact-submit">
				<?php esc_html_e( 'Enviar', 'santiago-moraes' ); ?>
			</button>

			<div class="home-contact__status" id="sm-home-contact-status" role="alert"></div>
		</form>

	</div>
</section>
