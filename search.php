<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 */

get_header(); ?>

<main id="main" class="site-main">
	<div class="wrap">

		<header class="page-header search-results__header">
			<?php
			$search_term = get_search_query();
			?>
			<h1 class="search-results__title">
				<?php
				printf(
					'%1$s <span class="search-results__term">“%2$s”</span>',
					esc_html__( 'Resultados para', 'obdc-simplex-news' ),
					esc_html( $search_term )
				);
				?>
			</h1>
			<p class="search-results__subtitle">
				<?php esc_html_e( 'Confira os conteúdos que mais se aproximam da sua busca.', 'obdc-simplex-news' ); ?>
			</p>
		</header><!-- .page-header -->

		<!-- Feed -->
		<section class="search-results-area" aria-label="<?php esc_attr_e( 'Resultados da busca', 'obdc-simplex-news' ); ?>">
			<div class="feed">
			<?php if ( have_posts() ) : ?>
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/card' );
				endwhile;
				?>
			<?php else : ?>
				<div class="search-results__empty">
					<p><?php esc_html_e( 'Nenhum resultado encontrado para a sua busca.', 'obdc-simplex-news' ); ?></p>
				</div>
			<?php endif; ?>
			</div>
		</section>

		<?php
		$next_link = get_next_posts_link( __( 'Carregar mais', 'obdc-simplex-news' ) );
		if ( $next_link ) :
			?>
			<div class="search-results__loadmore">
				<?php
				// Inject loadmore class into the anchor tag.
				echo str_replace( '<a', '<a class="loadmore"', $next_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		<?php endif; ?>

	</div><!-- .wrap -->

</main><!-- #main -->

<?php get_footer(); ?>
