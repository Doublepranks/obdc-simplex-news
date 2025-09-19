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

$excluded_post_ids = array();

if ( function_exists( 'obdc_simplex_news_get_front_page_excluded_post_ids' ) ) {
        $excluded_post_ids = obdc_simplex_news_get_front_page_excluded_post_ids();
}

$feed_endpoint   = rest_url( 'obdc-simplex-news/v1/front-page-feed' );
$rest_nonce      = wp_create_nonce( 'wp_rest' );
$load_more_text  = __( 'Carregar mais', 'obdc-simplex-news' );
$loading_text    = __( 'Carregando…', 'obdc-simplex-news' );

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
                        <div class="feed" data-feed>
                                <div class="feed__items" data-feed-items>
                                        <?php
                                        // Standard loop for latest posts, excluding Hero and Highlights.
                                        $args = array(
                                                'posts_per_page' => get_option( 'posts_per_page' ),
                                                'post_status'    => 'publish',
                                                'orderby'        => 'date',
                                                'order'          => 'DESC',
                                                'paged'          => 1,
                                                'no_found_rows'  => false,
                                        );

                                        if ( ! empty( $excluded_post_ids ) ) {
                                                $args['post__not_in'] = $excluded_post_ids;
                                        }

                                        $latest_posts = new WP_Query( $args );
                                        $max_pages    = max( 1, (int) $latest_posts->max_num_pages );

                                        if ( $latest_posts->have_posts() ) :
                                                while ( $latest_posts->have_posts() ) :
                                                        $latest_posts->the_post();
                                                        get_template_part( 'template-parts/content/card' );
                                                endwhile;
                                        endif;

                                        wp_reset_postdata();
                                        ?>
                                </div>

                                <?php
                                $button_disabled   = $max_pages <= 1;
                                $button_classes    = 'loadmore' . ( $button_disabled ? ' is-disabled' : '' );
                                $button_attributes = $button_disabled ? ' disabled aria-disabled="true"' : ' aria-disabled="false"';
                                ?>

                                <!-- Load more button -->
                                <button
                                        class="<?php echo esc_attr( $button_classes ); ?>"
                                        type="button"
                                        aria-label="<?php echo esc_attr( $load_more_text ); ?>"
                                        data-endpoint="<?php echo esc_url( $feed_endpoint ); ?>"
                                        data-nonce="<?php echo esc_attr( $rest_nonce ); ?>"
                                        data-current-page="1"
                                        data-max-pages="<?php echo esc_attr( $max_pages ); ?>"
                                        data-button-text="<?php echo esc_attr( $load_more_text ); ?>"
                                        data-loading-text="<?php echo esc_attr( $loading_text ); ?>"
                                        <?php echo $button_attributes; ?>
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