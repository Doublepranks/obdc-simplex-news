<?php
/**
 * The template for displaying all pages.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

get_header(); ?>

	<div class="wrap">

		<!-- Feed + sidebar -->
		<section class="content" aria-label="Últimas">
			<div class="feed">
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

			<aside class="sidebar" aria-label="Mais lidas">
				<?php get_template_part( 'template-parts/sidebar/most-read' ); ?>
			</aside>
		</section>

	</div>

<?php get_footer(); ?>