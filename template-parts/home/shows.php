<?php
/**
 * Homepage Shows section — real evento CPT query.
 *
 * @package Santiago_Moraes
 */

defined( 'ABSPATH' ) || exit;

$today = gmdate( 'Y-m-d' );
$shows = new WP_Query( array(
	'post_type'      => 'evento',
	'posts_per_page' => 4,
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
) );

// Spanish month abbreviations for date display.
$months_short = array(
	'', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
	'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic',
);
?>

<section class="shows" id="shows">
	<div class="shows__inner">

		<h2 class="shows__title"><?php esc_html_e( 'Próximos Shows', 'santiago-moraes' ); ?></h2>

		<?php if ( $shows->have_posts() ) : ?>
			<div class="shows__list">
				<?php
				while ( $shows->have_posts() ) :
					$shows->the_post();

					$event_date  = get_post_meta( get_the_ID(), '_evento_date', true );
					$venue       = get_post_meta( get_the_ID(), '_evento_venue', true );
					$city        = get_post_meta( get_the_ID(), '_evento_city', true );
					$ticket_link = get_post_meta( get_the_ID(), '_evento_ticket_link', true );

					// Parse date parts.
					$date_obj = DateTime::createFromFormat( 'Y-m-d', $event_date );
					if ( $date_obj ) {
						$day   = $date_obj->format( 'j' );
						$month = $months_short[ (int) $date_obj->format( 'n' ) ];
					} else {
						$day   = '';
						$month = $event_date;
					}
					?>
					<article class="show-card">
						<div class="show-card__date">
							<span class="show-card__day"><?php echo esc_html( $day ); ?></span>
							<span class="show-card__month"><?php echo esc_html( $month ); ?></span>
						</div>
						<div class="show-card__info">
							<span class="show-card__venue"><?php echo esc_html( $venue ); ?></span>
							<span class="show-card__city"><?php echo esc_html( $city ); ?></span>
						</div>
						<div class="show-card__actions">
							<a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn--ghost btn--sm">
								<?php esc_html_e( 'Info', 'santiago-moraes' ); ?>
							</a>
							<?php if ( $ticket_link ) : ?>
								<a href="<?php echo esc_url( $ticket_link ); ?>" class="btn btn--outline btn--sm" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Entradas', 'santiago-moraes' ); ?>
								</a>
							<?php endif; ?>
						</div>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>

			<div class="shows__footer">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'evento' ) ); ?>" class="shows__view-all">
					<?php esc_html_e( 'Ver todos los shows', 'santiago-moraes' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 448 512" fill="currentColor"><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h306.7L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
				</a>
			</div>

		<?php else : ?>
			<p class="shows__empty"><?php esc_html_e( 'Proximos shows por confirmar', 'santiago-moraes' ); ?></p>
		<?php endif; ?>

	</div>
</section>
