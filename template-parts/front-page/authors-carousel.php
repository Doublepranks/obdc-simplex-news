<?php
/**
 * Front page authors carousel.
 *
 * @package ObDC-simplex-news
 */

$authors = obdc_simplex_news_get_featured_authors();

if ( empty( $authors ) ) {
	return;
}

$heading = apply_filters(
	'obdc_simplex_news_authors_heading',
	__( 'Equipe editorial', 'obdc-simplex-news' )
);
$section_id = 'site-authors-title';

?>

<section class="site-authors" aria-labelledby="<?php echo esc_attr( $section_id ); ?>">
	<div class="wrap">
		<header class="site-authors__header">
			<h2 class="site-authors__title" id="<?php echo esc_attr( $section_id ); ?>">
				<?php echo esc_html( $heading ); ?>
			</h2>
		</header>

		<div class="site-authors__wrapper" data-authors-carousel>
			<div class="site-authors__track" data-authors-track aria-live="polite" aria-atomic="false">
				<?php foreach ( $authors as $author ) : ?>
					<article class="site-author-card" data-authors-card>
						<a class="site-author-card__link" href="<?php echo esc_url( $author['permalink'] ); ?>">
							<div class="site-author-card__avatar">
								<img
									src="<?php echo esc_url( $author['avatar'] ); ?>"
									alt="<?php echo esc_attr( $author['name'] ); ?>"
									loading="lazy"
									width="160"
									height="160"
								/>
							</div>
							<div class="site-author-card__content">
								<h3 class="site-author-card__name"><?php echo esc_html( $author['name'] ); ?></h3>
								<?php if ( ! empty( $author['role'] ) ) : ?>
									<p class="site-author-card__role"><?php echo esc_html( $author['role'] ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $author['bio'] ) ) : ?>
									<p class="site-author-card__bio"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $author['bio'] ), 20, '...' ) ); ?></p>
								<?php endif; ?>
							</div>
						</a>
					</article>
				<?php endforeach; ?>
			</div>

			<div class="site-authors__nav" aria-label="<?php esc_attr_e( 'Navegar pelo mural de autores', 'obdc-simplex-news' ); ?>">
				<button
					class="site-authors__nav-button site-authors__nav-button--prev"
					type="button"
					data-authors-prev
					aria-controls="<?php echo esc_attr( $section_id ); ?>"
					aria-label="<?php esc_attr_e( 'Mostrar autores anteriores', 'obdc-simplex-news' ); ?>"
				>
					<span aria-hidden="true">‹</span>
				</button>
				<button
					class="site-authors__nav-button site-authors__nav-button--next"
					type="button"
					data-authors-next
					aria-controls="<?php echo esc_attr( $section_id ); ?>"
					aria-label="<?php esc_attr_e( 'Mostrar próximos autores', 'obdc-simplex-news' ); ?>"
				>
					<span aria-hidden="true">›</span>
				</button>
			</div>
		</div>
	</div>
</section>
