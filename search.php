<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 */

get_header(); ?>

<main id="main" class="site-main">
	<div class="wrap">

		<header class="page-header">
			<h1 class="page-title"> <?php printf( __( 'Search Results for: %s', 'obdc-simplex-news' ), '<span>' . get_search_query() . '</span>' ); ?> </h1>
		</header><!-- .page-header -->

		<!-- Feed -->
		<section class="content" aria-label="Resultados da busca">
			<div class="feed">
				<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content/card' );
					endwhile;
				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;
				?>
			</div>
		</section>

	</div><!-- .wrap -->

</main><!-- #main -->

<?php get_footer(); ?>
