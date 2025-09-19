<?php
/**
 * The template for displaying the front page.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ObDC-simplex-news
 */

get_header();

// --- Logic to get Hero and Highlights post IDs for exclusion ---
$excluded_post_ids = array();

// 1. Get the ID of the HERO post (featured or latest)
$hero_post_id = null;
$featured_hero_query = new WP_Query( array(
	'posts_per_page' => 1,
	'meta_key'       => '_featured_on_home',
	'meta_value'     => '1',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'fields'         => 'ids', // Only get the ID
) );

if ( $featured_hero_query->have_posts() ) {
	$hero_post_id = $featured_hero_query->posts[0];
} else {
	// Fallback: Get the latest post ID
	$latest_hero_query = new WP_Query( array(
		'posts_per_page' => 1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'fields'         => 'ids',
	) );
	if ( $latest_hero_query->have_posts() ) {
		$hero_post_id = $latest_hero_query->posts[0];
	}
}
wp_reset_postdata(); // Good practice after WP_Query

if ( $hero_post_id ) {
	$excluded_post_ids[] = $hero_post_id;
}

// 2. Get the IDs of the HIGHLIGHTS posts (2 latest, excluding the HERO)
$highlights_post_ids = array();
$highlights_args = array(
	'posts_per_page' => 2,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'fields'         => 'ids', // Only get the IDs
	'post_status'    => 'publish',
);

if ( ! empty( $hero_post_id ) ) {
	$highlights_args['post__not_in'] = array( $hero_post_id );
}

$highlights_query = new WP_Query( $highlights_args );
if ( $highlights_query->have_posts() ) {
	$highlights_post_ids = $highlights_query->posts;
	$excluded_post_ids = array_merge( $excluded_post_ids, $highlights_post_ids );
}
wp_reset_postdata(); // Good practice after WP_Query

// Remove any potential duplicates and ensure IDs are integers
$excluded_post_ids = array_unique( array_map( 'intval', $excluded_post_ids ) );

// --- End of exclusion logic ---

?>

<main id="main" class="site-main">
	<div class="wrap">

		<!-- HERO + highlights + ad -->
		<section class="grid" aria-label="Destaques">
			<div class="hero">
				<!-- Lead story -->
				<?php get_template_part( 'template-parts/home/hero' ); ?>
			</div>
			<aside class="stack" aria-label="Complementos">
				<!-- Secondary highlights -->
				<?php get_template_part( 'template-parts/home/highlights' ); ?>
			</aside>
		</section>

		<!-- Ad slot topo -->
		<?php get_template_part( 'template-parts/ads/top-home' ); ?>

		<!-- Feed + sidebar -->
		<section class="content" aria-label="Últimas">
			<div class="feed">
				<!-- Feed of cards -->
				<?php
				// Standard loop for latest posts, excluding Hero and Highlights
				$args = array(
					'posts_per_page' => get_option( 'posts_per_page' ), // Use default posts per page setting
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'DESC',
				);

				if ( ! empty( $excluded_post_ids ) ) {
					$args['post__not_in'] = $excluded_post_ids;
				}

				$latest_posts = new WP_Query( $args );

				if ( $latest_posts->have_posts() ) :
					while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
						get_template_part( 'template-parts/content/card' );
					endwhile;
				endif;

				wp_reset_postdata();
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