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

$today              = current_time( 'Ymd' );
$hero_post_id       = 0;
$hero_is_pinned     = false;
$highlight_post_ids = array();
$excluded_post_ids  = array();

// Determine the hero post ID (pinned by data_destaque or fallback to latest post).
$hero_args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 1,
        'meta_key'            => 'data_destaque',
        'meta_query'          => array(
                array(
                        'key'     => 'data_destaque',
                        'value'   => $today,
                        'compare' => '>=',
                        'type'    => 'NUMERIC',
                ),
        ),
        'orderby'             => array(
                'meta_value_num' => 'DESC',
                'date'           => 'DESC',
        ),
        'fields'              => 'ids',
        'no_found_rows'       => true,
        'ignore_sticky_posts' => true,
);

$hero_query = new WP_Query( $hero_args );

if ( $hero_query->have_posts() ) {
        $hero_post_id   = (int) $hero_query->posts[0];
        $hero_is_pinned = true;
}

if ( ! $hero_post_id ) {
        $latest_query = new WP_Query(
                array(
                        'post_type'           => 'post',
                        'post_status'         => 'publish',
                        'posts_per_page'      => 1,
                        'orderby'             => 'date',
                        'order'               => 'DESC',
                        'fields'              => 'ids',
                        'no_found_rows'       => true,
                        'ignore_sticky_posts' => true,
                )
        );

        if ( $latest_query->have_posts() ) {
                $hero_post_id = (int) $latest_query->posts[0];
        }
}

if ( $hero_post_id ) {
        $excluded_post_ids[] = $hero_post_id;
}

// Determine highlights (prioritise other pinned posts when hero is pinned).
if ( $hero_is_pinned && $hero_post_id ) {
        $pinned_highlights_query = new WP_Query(
                array(
                        'post_type'           => 'post',
                        'post_status'         => 'publish',
                        'posts_per_page'      => 2,
                        'meta_key'            => 'data_destaque',
                        'meta_query'          => array(
                                array(
                                        'key'     => 'data_destaque',
                                        'value'   => $today,
                                        'compare' => '>=',
                                        'type'    => 'NUMERIC',
                                ),
                        ),
                        'post__not_in'        => array( $hero_post_id ),
                        'orderby'             => array(
                                'meta_value_num' => 'DESC',
                                'date'           => 'DESC',
                        ),
                        'fields'              => 'ids',
                        'no_found_rows'       => true,
                        'ignore_sticky_posts' => true,
                )
        );

        if ( $pinned_highlights_query->have_posts() ) {
                $highlight_post_ids = array_map( 'intval', $pinned_highlights_query->posts );
        }
}

$needed_highlights = max( 0, 2 - count( $highlight_post_ids ) );

if ( $needed_highlights > 0 ) {
        $fallback_exclusions = array_filter(
                array_map(
                        'intval',
                        array_merge( array( $hero_post_id ), $highlight_post_ids )
                )
        );

        $fallback_args = array(
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'posts_per_page'      => $needed_highlights,
                'orderby'             => 'date',
                'order'               => 'DESC',
                'fields'              => 'ids',
                'no_found_rows'       => true,
                'ignore_sticky_posts' => true,
        );

        if ( ! empty( $fallback_exclusions ) ) {
                $fallback_args['post__not_in'] = $fallback_exclusions;
        }

        $fallback_query = new WP_Query( $fallback_args );

        if ( $fallback_query->have_posts() ) {
                $highlight_post_ids = array_merge(
                        $highlight_post_ids,
                        array_map( 'intval', $fallback_query->posts )
                );
        }
}

if ( ! empty( $highlight_post_ids ) ) {
        $excluded_post_ids = array_merge( $excluded_post_ids, $highlight_post_ids );
}

$excluded_post_ids = array_values( array_unique( array_filter( array_map( 'intval', $excluded_post_ids ) ) ) );

$paged = get_query_var( 'paged' );
if ( ! $paged ) {
        $paged = get_query_var( 'page' );
}

$paged            = $paged ? (int) $paged : 1;
$paged            = max( 1, $paged );
$max_feed_pages   = 3; // Limit feed pagination to the first three pages (current + two extra).
$paged            = min( $paged, $max_feed_pages );
?>

<main id="main" class="site-main">
        <div class="wrap">

                <!-- HERO + highlights + ad -->
                <section class="grid" aria-label="Destaques">
                        <div class="hero">
                                <?php
                                if ( $hero_post_id ) :
                                        $hero_display_query = new WP_Query(
                                                array(
                                                        'post_type'           => 'post',
                                                        'post_status'         => 'publish',
                                                        'posts_per_page'      => 1,
                                                        'p'                   => $hero_post_id,
                                                        'no_found_rows'       => true,
                                                        'ignore_sticky_posts' => true,
                                                )
                                        );

                                        if ( $hero_display_query->have_posts() ) :
                                                while ( $hero_display_query->have_posts() ) :
                                                        $hero_display_query->the_post();
                                                        get_template_part( 'template-parts/home/hero' );
                                                endwhile;
                                        endif;

                                        wp_reset_postdata();
                                endif;
                                ?>
                        </div>
                        <aside class="stack" aria-label="Complementos">
                                <?php
                                if ( ! empty( $highlight_post_ids ) ) :
                                        $highlights_display_query = new WP_Query(
                                                array(
                                                        'post_type'           => 'post',
                                                        'post_status'         => 'publish',
                                                        'posts_per_page'      => count( $highlight_post_ids ),
                                                        'post__in'            => $highlight_post_ids,
                                                        'orderby'             => 'post__in',
                                                        'no_found_rows'       => true,
                                                        'ignore_sticky_posts' => true,
                                                )
                                        );

                                        if ( $highlights_display_query->have_posts() ) :
                                                global $wp_query;
                                                $previous_wp_query = $wp_query;
                                                $wp_query          = $highlights_display_query;

                                                while ( $highlights_display_query->have_posts() ) :
                                                        $highlights_display_query->the_post();
                                                        get_template_part( 'template-parts/home/highlights' );
                                                endwhile;

                                                $wp_query = $previous_wp_query;
                                        endif;

                                        wp_reset_postdata();
                                endif;
                                ?>
                        </aside>
                </section>

                <!-- Ad slot topo -->
                <?php get_template_part( 'template-parts/ads/top-home' ); ?>

                <!-- Feed + sidebar -->
                <section class="content" aria-label="Últimas">
                        <div class="feed">
                                <?php
                                $feed_args = array(
                                        'post_type'           => 'post',
                                        'post_status'         => 'publish',
                                        'posts_per_page'      => get_option( 'posts_per_page' ),
                                        'paged'               => $paged,
                                        'orderby'             => 'date',
                                        'order'               => 'DESC',
                                        'ignore_sticky_posts' => true,
                                );

                                if ( ! empty( $excluded_post_ids ) ) {
                                        $feed_args['post__not_in'] = $excluded_post_ids;
                                }

                                $feed_query = new WP_Query( $feed_args );

                                if ( $feed_query->have_posts() ) :
                                        while ( $feed_query->have_posts() ) :
                                                $feed_query->the_post();
                                                get_template_part( 'template-parts/content/card' );
                                        endwhile;
                                else :
                                        ?>
                                        <p><?php esc_html_e( 'Nenhuma publicação encontrada.', 'obdc-simplex-news' ); ?></p>
                                        <?php
                                endif;

                                $max_pages_raw = (int) $feed_query->max_num_pages;

                                wp_reset_postdata();

                                $max_allowed_pages  = min( $max_pages_raw, $max_feed_pages );
                                $loadmore_disabled  = ( $paged >= $max_allowed_pages );
                                $remaining_pages = max( 0, $max_allowed_pages - $paged );
                                ?>

                                <button
                                        class="loadmore"
                                        type="button"
                                        aria-label="Carregar mais"
                                        <?php disabled( $loadmore_disabled ); ?>
                                        data-current-page="<?php echo esc_attr( $paged ); ?>"
                                        data-max-pages="<?php echo esc_attr( $max_allowed_pages ); ?>"
                                        data-total-pages="<?php echo esc_attr( $max_pages_raw ); ?>"
                                        data-pages-remaining="<?php echo esc_attr( $remaining_pages ); ?>"
                                >
                                        <?php esc_html_e( 'Carregar mais', 'obdc-simplex-news' ); ?>
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
