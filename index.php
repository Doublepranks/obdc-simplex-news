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
 *
 * @package ObDC-simplex-news
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="wrap">

		<!-- Feed + sidebar -->
		<section class="content" aria-label="�sltimas">
			<div class="feed">
				<?php
				// Standard loop for latest posts.
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content/card' );
					endwhile;
				endif;

				global $wp_query;
				$max_pages         = isset( $wp_query->max_num_pages ) ? max( 1, (int) $wp_query->max_num_pages ) : 1;
				$load_more_text    = __( 'Carregar mais', 'obdc-simplex-news' );
				$button_disabled   = $max_pages <= 1;
				$button_classes    = 'loadmore' . ( $button_disabled ? ' is-disabled' : '' );
				$button_attr_state = $button_disabled ? ' disabled aria-disabled="true"' : ' aria-disabled="false"';
				?>

				<!-- Load more button (static, no AJAX on index/archives yet) -->
				<button
					class="<?php echo esc_attr( $button_classes ); ?>"
					type="button"
					aria-label="<?php echo esc_attr( $load_more_text ); ?>"
					<?php echo $button_attr_state; ?>
				>
					<?php echo esc_html( $load_more_text ); ?>
				</button>
			</div>

			<aside class="sidebar" aria-label="Mais lidas">
				<?php get_template_part( 'template-parts/sidebar/most-read' ); ?>
			</aside>
		</section>

	</div><!-- .wrap -->

</main><!-- #main -->

<?php
get_footer();

