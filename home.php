<?php
/**
 * The template for displaying home page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ObDC-simplex-news
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="wrap">

		<!-- Feed + sidebar -->
		<section class="content" aria-label="Últimas">
			<div class="feed">
				<!-- Feed of cards -->
				<?php
				// Standard loop for latest posts
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content/card' );
					endwhile;
				endif;
				?>

				<!-- Load more button -->
				<button class="loadmore" type="button" aria-label="Carregar mais">Carregar mais</button>
			</div>

			<!-- Sidebar - Mais lidas -->
			<aside class="sidebar" aria-label="Mais lidas">
				<?php get_template_part( 'template-parts/sidebar/most-read' ); ?>
			</aside>
		</section>

	</div><!-- .wrap -->
</main><!-- #main -->

<?php
get_footer();