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

				global $wp_query;
				$max_pages        = isset( $wp_query->max_num_pages ) ? max( 1, (int) $wp_query->max_num_pages ) : 1;
				$load_more_text   = __( 'Carregar mais', 'obdc-simplex-news' );
				$button_disabled  = $max_pages <= 1;
				$button_classes   = 'loadmore' . ( $button_disabled ? ' is-disabled' : '' );
				$button_attr_state = $button_disabled ? ' disabled aria-disabled="true"' : ' aria-disabled="false"';
				?>

				<!-- Load more button (static, no AJAX on home yet) -->
				<button
					class="<?php echo esc_attr( $button_classes ); ?>"
					type="button"
					aria-label="<?php echo esc_attr( $load_more_text ); ?>"
					<?php echo $button_attr_state; ?>
				>
					<?php echo esc_html( $load_more_text ); ?>
				</button>
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
