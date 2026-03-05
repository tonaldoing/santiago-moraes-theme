<?php
/**
 * Archive Evento — Events listing with upcoming and past shows.
 *
 * @package Santiago_Moraes
 */

get_header();

$today = gmdate( 'Y-m-d' );

// Upcoming events (date >= today), chronological.
$upcoming = new WP_Query(
	array(
		'post_type'      => 'evento',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => '_evento_date', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_evento_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE',
			),
		),
	)
);

// Past events (date < today), reverse chronological.
$past = new WP_Query(
	array(
		'post_type'      => 'evento',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => '_evento_date', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_evento_date',
				'value'   => $today,
				'compare' => '<',
				'type'    => 'DATE',
			),
		),
	)
);
?>

<main id="main" class="site-main page-events">

	<header class="events-header">
		<div class="events-header__inner">
			<h1 class="events-header__title"><?php esc_html_e( 'Shows', 'santiago-moraes' ); ?></h1>
		</div>
	</header>

	<!-- ── Upcoming Shows ── -->
	<section class="events-section events-section--upcoming">
		<div class="events-section__inner">
			<h2 class="events-section__title"><?php esc_html_e( 'Próximos Shows', 'santiago-moraes' ); ?></h2>

			<?php if ( $upcoming->have_posts() ) : ?>
				<div class="events-list">
					<?php
					while ( $upcoming->have_posts() ) :
						$upcoming->the_post();

						$date        = get_post_meta( get_the_ID(), '_evento_date', true );
						$time        = get_post_meta( get_the_ID(), '_evento_time', true );
						$venue       = get_post_meta( get_the_ID(), '_evento_venue', true );
						$city        = get_post_meta( get_the_ID(), '_evento_city', true );
						$ticket_link = get_post_meta( get_the_ID(), '_evento_ticket_link', true );
						$price       = get_post_meta( get_the_ID(), '_evento_price', true );

						// Parse date.
						$day = '';
						$month_short = '';
						if ( $date ) {
							$ts = strtotime( $date );
							if ( $ts ) {
								$day = gmdate( 'j', $ts );
								$months_arr = array( '', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic' );
								$month_short = $months_arr[ (int) gmdate( 'n', $ts ) ];
							}
						}
						?>

						<article class="event-card">
							<div class="event-card__date-badge">
								<span class="event-card__day"><?php echo esc_html( $day ); ?></span>
								<span class="event-card__month"><?php echo esc_html( $month_short ); ?></span>
							</div>

							<div class="event-card__info">
								<h3 class="event-card__title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
								<div class="event-card__details">
									<?php if ( $venue ) : ?>
										<span class="event-card__venue"><?php echo esc_html( $venue ); ?></span>
									<?php endif; ?>
									<?php if ( $city ) : ?>
										<span class="event-card__city"><?php echo esc_html( $city ); ?></span>
									<?php endif; ?>
									<?php if ( $time ) : ?>
										<span class="event-card__time"><?php echo esc_html( $time ); ?> hs</span>
									<?php endif; ?>
								</div>
							</div>

							<div class="event-card__actions">
								<?php if ( $price ) : ?>
									<span class="event-card__price"><?php echo esc_html( $price ); ?></span>
								<?php endif; ?>
								<?php if ( $ticket_link ) : ?>
									<a href="<?php echo esc_url( $ticket_link ); ?>" class="btn btn--primary btn--sm" target="_blank" rel="noopener noreferrer">
										<?php esc_html_e( 'Entradas', 'santiago-moraes' ); ?>
									</a>
								<?php else : ?>
									<a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm">
										<?php esc_html_e( 'Ver más', 'santiago-moraes' ); ?>
									</a>
								<?php endif; ?>
							</div>
						</article>

					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			<?php else : ?>
				<p class="events-section__empty"><?php esc_html_e( 'No hay shows programados por el momento. Seguinos en redes para enterarte primero.', 'santiago-moraes' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- ── Past Shows ── -->
	<?php if ( $past->have_posts() ) : ?>
		<section class="events-section events-section--past">
			<div class="events-section__inner">
				<button class="events-toggle" id="events-toggle" type="button" aria-expanded="false">
					<?php esc_html_e( 'Ver shows pasados', 'santiago-moraes' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="14" fill="currentColor"><path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>
				</button>

				<div class="events-past-list" id="events-past-list" hidden>
					<?php
					while ( $past->have_posts() ) :
						$past->the_post();

						$date  = get_post_meta( get_the_ID(), '_evento_date', true );
						$venue = get_post_meta( get_the_ID(), '_evento_venue', true );
						$city  = get_post_meta( get_the_ID(), '_evento_city', true );

						$date_display = '';
						if ( $date ) {
							$ts = strtotime( $date );
							if ( $ts ) {
								$date_display = gmdate( 'd/m/Y', $ts );
							}
						}
						?>

						<div class="event-card event-card--past">
							<div class="event-card__info">
								<span class="event-card__title event-card__title--past"><?php the_title(); ?></span>
								<div class="event-card__details">
									<?php if ( $date_display ) : ?>
										<span class="event-card__date-text"><?php echo esc_html( $date_display ); ?></span>
									<?php endif; ?>
									<?php if ( $venue ) : ?>
										<span class="event-card__venue"><?php echo esc_html( $venue ); ?></span>
									<?php endif; ?>
									<?php if ( $city ) : ?>
										<span class="event-card__city"><?php echo esc_html( $city ); ?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>

					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

</main>

<?php
get_footer();
