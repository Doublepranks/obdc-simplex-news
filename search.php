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
			<div class="feed" data-feed>
				<?php if ( have_posts() ) : ?>
					<?php
					global $wp_query;

					$max_pages        = isset( $wp_query->max_num_pages ) ? max( 1, (int) $wp_query->max_num_pages ) : 1;
					$load_more_text   = __( 'Carregar mais', 'obdc-simplex-news' );
					$loading_text     = __( 'Carregando...', 'obdc-simplex-news' );
					$rest_nonce       = wp_create_nonce( 'wp_rest' );
					$search_endpoint  = add_query_arg(
						array(
							'search' => sanitize_text_field( get_search_query( false ) ),
						),
						rest_url( 'obdc-simplex-news/v1/search-feed' )
					);
					$auto_load_limit  = apply_filters( 'obdc_simplex_news_search_autoload_limit', 3 );
					$auto_load_limit  = max( 0, absint( $auto_load_limit ) );
					$button_disabled  = $max_pages <= 1;
					$button_classes   = 'loadmore' . ( $button_disabled ? ' is-disabled' : '' );
					$button_attribute = $button_disabled ? ' disabled aria-disabled="true"' : ' aria-disabled="false"';
					?>
					<div class="feed__items" data-feed-items>
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content/card' );
						endwhile;
						?>
					</div>

					<div class="feed__sentinel" data-feed-sentinel aria-hidden="true"></div>

					<button
						class="<?php echo esc_attr( $button_classes ); ?>"
						type="button"
						aria-label="<?php echo esc_attr( $load_more_text ); ?>"
						data-endpoint="<?php echo esc_url( $search_endpoint ); ?>"
						data-nonce="<?php echo esc_attr( $rest_nonce ); ?>"
						data-current-page="1"
						data-max-pages="<?php echo esc_attr( $max_pages ); ?>"
						data-button-text="<?php echo esc_attr( $load_more_text ); ?>"
						data-loading-text="<?php echo esc_attr( $loading_text ); ?>"
						data-autoload-limit="<?php echo esc_attr( $auto_load_limit ); ?>"
						<?php echo $button_attribute; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					>
						<?php echo esc_html( $load_more_text ); ?>
					</button>
				<?php else : ?>
					<div class="search-results__empty">
						<p><?php esc_html_e( 'Nenhum resultado encontrado para a sua busca.', 'obdc-simplex-news' ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</section>

	</div><!-- .wrap -->

</main><!-- #main -->

<?php get_footer(); ?>
