<?php
/**
 * Single Evento (Event) template.
 *
 * Dark hero with event poster, details, and CTA. Light content below.
 *
 * @package Santiago_Moraes
 */

get_header();
?>

<main id="main" class="site-main single-evento">
	<?php
	while ( have_posts() ) :
		the_post();

		$date        = get_post_meta( get_the_ID(), '_evento_date', true );
		$time        = get_post_meta( get_the_ID(), '_evento_time', true );
		$venue       = get_post_meta( get_the_ID(), '_evento_venue', true );
		$city        = get_post_meta( get_the_ID(), '_evento_city', true );
		$ticket_link = get_post_meta( get_the_ID(), '_evento_ticket_link', true );
		$price       = get_post_meta( get_the_ID(), '_evento_price', true );

		// Format date nicely in Spanish.
		$formatted_date = '';
		if ( $date ) {
			$timestamp = strtotime( $date );
			if ( $timestamp ) {
				$days   = array( 'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado' );
				$months = array( '', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' );

				$day_name   = $days[ (int) gmdate( 'w', $timestamp ) ];
				$day_num    = gmdate( 'j', $timestamp );
				$month_name = $months[ (int) gmdate( 'n', $timestamp ) ];
				$year       = gmdate( 'Y', $timestamp );

				$formatted_date = "$day_name $day_num de $month_name, $year";
			}
		}

		// Hero background image.
		$has_thumb = has_post_thumbnail();
		$thumb_url = $has_thumb ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
		?>

		<section class="evento-hero<?php echo $has_thumb ? ' evento-hero--has-image' : ''; ?>"
			<?php if ( $thumb_url ) : ?>
				style="background-image: url('<?php echo esc_url( $thumb_url ); ?>');"
			<?php endif; ?>
		>
			<div class="evento-hero__overlay"></div>
			<div class="evento-hero__inner">

				<?php if ( $formatted_date ) : ?>
					<span class="evento-hero__date"><?php echo esc_html( $formatted_date ); ?></span>
				<?php endif; ?>

				<h1 class="evento-hero__title"><?php the_title(); ?></h1>

				<div class="evento-hero__meta">
					<?php if ( $time ) : ?>
						<span class="evento-hero__detail">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16" fill="currentColor"><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>
							<?php echo esc_html( $time ); ?> hs
						</span>
					<?php endif; ?>

					<?php if ( $venue ) : ?>
						<span class="evento-hero__detail">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="14" height="16" fill="currentColor"><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
							<?php echo esc_html( $venue ); ?><?php echo $city ? ', ' . esc_html( $city ) : ''; ?>
						</span>
					<?php endif; ?>

					<?php if ( $price ) : ?>
						<span class="evento-hero__detail">
							<?php echo esc_html( $price ); ?>
						</span>
					<?php endif; ?>
				</div>

				<div class="evento-hero__actions">
					<?php if ( $ticket_link ) : ?>
						<a href="<?php echo esc_url( $ticket_link ); ?>" class="btn btn--primary btn--lg" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Comprar Entradas', 'santiago-moraes' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( $venue && $city ) : ?>
						<a href="<?php echo esc_url( 'https://www.google.com/maps/search/' . rawurlencode( $venue . ', ' . $city ) ); ?>" class="btn btn--outline btn--lg evento-hero__map-btn" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Ver en Mapa', 'santiago-moraes' ); ?>
						</a>
					<?php endif; ?>
				</div>

			</div>
		</section>

		<?php if ( get_the_content() ) : ?>
			<article class="evento-content">
				<div class="evento-content__inner">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endif; ?>

		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
