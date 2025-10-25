<?php
/**
 * Template Name: Categories Index
 *
 * Displays an alphabetical list of categories in horizontal cards.
 *
 * @package ObDC-simplex-news
 */

get_header();

$categories = get_terms(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>

<main id="main" class="site-main">
	<div class="wrap">
		<section class="categories-index" aria-labelledby="categories-index-title">
			<header class="categories-index__header">
				<h1 id="categories-index-title" class="categories-index__title">
					<?php esc_html_e( 'Navegue por categorias', 'obdc-simplex-news' ); ?>
				</h1>
				<p class="categories-index__subtitle">
					<?php esc_html_e( 'Encontre rapidamente tudo o que já publicamos sobre cada editoria.', 'obdc-simplex-news' ); ?>
				</p>
			</header>

			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
				<div class="categories-index__grid">
					<?php foreach ( $categories as $category ) : ?>
						<?php
						$category_link = get_term_link( $category );
						if ( is_wp_error( $category_link ) ) {
							continue;
						}
						?>
						<a class="categories-index__card" href="<?php echo esc_url( $category_link ); ?>">
							<span class="categories-index__card-name"><?php echo esc_html( $category->name ); ?></span>
							<?php if ( ! empty( $category->description ) ) : ?>
								<span class="categories-index__card-desc"><?php echo esc_html( wp_trim_words( $category->description, 18 ) ); ?></span>
							<?php endif; ?>
							<span class="categories-index__card-meta">
								<?php
								printf(
									/* translators: %d: number of posts in category. */
									esc_html( _n( '%d post', '%d posts', (int) $category->count, 'obdc-simplex-news' ) ),
									(int) $category->count
								);
								?>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="categories-index__empty">
					<?php esc_html_e( 'Ainda não há categorias cadastradas.', 'obdc-simplex-news' ); ?>
				</p>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php
get_footer();
