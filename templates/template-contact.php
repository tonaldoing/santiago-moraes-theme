<?php
/**
 * Template Name: Contacto
 * Template Post Type: page
 *
 * Contact page with form, social links, and optional map.
 *
 * @package Santiago_Moraes
 */

get_header();

$contact_phone = sm_get_option( 'sm_contact_phone', '' );
$contact_addr  = sm_get_option( 'sm_contact_address', '' );
$maps_url      = sm_get_option( 'sm_contact_maps_url', '' );
?>

<main id="main" class="site-main page-contact">

	<header class="contact-header">
		<div class="contact-header__inner">
			<h1 class="contact-header__title"><?php the_title(); ?></h1>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php if ( get_the_content() ) : ?>
					<div class="contact-header__intro"><?php the_content(); ?></div>
				<?php endif; ?>
			<?php endwhile; endif; ?>
		</div>
	</header>

	<section class="contact-form-section">
		<div class="contact-form-section__inner">

			<form class="contact-form" id="sm-contact-form" method="post" novalidate>
				<?php wp_nonce_field( 'sm_contact_form', 'sm_contact_nonce' ); ?>
				<input type="hidden" name="action" value="sm_contact_form">

				<!-- Honeypot — hidden from humans, bots fill it -->
				<div class="contact-form__hp" aria-hidden="true">
					<label for="sm_website">Website</label>
					<input type="text" name="sm_website" id="sm_website" tabindex="-1" autocomplete="off">
				</div>

				<div class="contact-form__field">
					<label for="sm_name" class="contact-form__label"><?php esc_html_e( 'Nombre', 'santiago-moraes' ); ?> *</label>
					<input type="text" name="sm_name" id="sm_name" class="contact-form__input" required>
				</div>

				<div class="contact-form__field">
					<label for="sm_email" class="contact-form__label"><?php esc_html_e( 'Email', 'santiago-moraes' ); ?> *</label>
					<input type="email" name="sm_email" id="sm_email" class="contact-form__input" required>
				</div>

				<div class="contact-form__field">
					<label for="sm_subject" class="contact-form__label"><?php esc_html_e( 'Asunto', 'santiago-moraes' ); ?></label>
					<input type="text" name="sm_subject" id="sm_subject" class="contact-form__input">
				</div>

				<div class="contact-form__field">
					<label for="sm_message" class="contact-form__label"><?php esc_html_e( 'Mensaje', 'santiago-moraes' ); ?> *</label>
					<textarea name="sm_message" id="sm_message" class="contact-form__textarea" rows="6" required></textarea>
				</div>

				<div class="contact-form__status" id="sm-contact-status" role="alert"></div>

				<button type="submit" class="btn btn--primary contact-form__submit" id="sm-contact-submit">
					<?php esc_html_e( 'Enviar Mensaje', 'santiago-moraes' ); ?>
				</button>
			</form>

			<div class="contact-info">
				<?php if ( $contact_phone ) : ?>
					<div class="contact-info__item">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20" fill="currentColor"><path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>"><?php echo esc_html( $contact_phone ); ?></a>
					</div>
				<?php endif; ?>

				<?php if ( $contact_addr ) : ?>
					<div class="contact-info__item">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="20" fill="currentColor"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
						<span><?php echo esc_html( $contact_addr ); ?></span>
					</div>
				<?php endif; ?>

				<?php
				$has_contact_social = false;
				foreach ( $social_links as $mod_key => $info ) {
					if ( sm_get_option( $mod_key, '' ) ) {
						$has_contact_social = true;
						break;
					}
				}
				if ( $has_contact_social ) :
				?>
				<div class="contact-social">
					<h3 class="contact-social__title"><?php esc_html_e( 'Seguinos', 'santiago-moraes' ); ?></h3>
					<div class="contact-social__links">
						<?php
						$social_links = array(
							'sm_social_instagram' => array( 'label' => 'Instagram', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>' ),
							'sm_social_spotify'   => array( 'label' => 'Spotify', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"/></svg>' ),
							'sm_social_youtube'   => array( 'label' => 'YouTube', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>' ),
							'sm_social_bandcamp'  => array( 'label' => 'Bandcamp', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256S119 504 256 504 504 393 504 256 393 8 256 8zm48.2 326.1h-181L207.9 178h181z"/></svg>' ),
						);

						foreach ( $social_links as $mod_key => $info ) :
							$url = sm_get_option( $mod_key, '' );
							if ( $url ) :
								?>
								<a href="<?php echo esc_url( $url ); ?>" class="contact-social__link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $info['label'] ); ?>">
									<?php echo $info['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG. ?>
								</a>
								<?php
							endif;
						endforeach;
						?>
					</div>
				</div>
				<?php endif; ?>
			</div>

		</div>
	</section>

	<?php if ( $maps_url ) : ?>
		<section class="contact-map">
			<iframe src="<?php echo esc_url( $maps_url ); ?>" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php esc_attr_e( 'Ubicación', 'santiago-moraes' ); ?>"></iframe>
		</section>
	<?php endif; ?>

</main>

<?php
get_footer();
