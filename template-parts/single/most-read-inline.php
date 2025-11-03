<?php
/**
 * Inline "Most Read" list for single posts (discreet, full-width).
 *
 * @package ObDC-simplex-news
 */

// Build query for most read posts, excluding current post.
$current_id  = get_the_ID();
$items_count = (int) apply_filters( 'obdc_simplex_news_most_read_single_count', 10 );

$args = array(
    'posts_per_page' => $items_count,
    'post__not_in'   => $current_id ? array( $current_id ) : array(),
    'meta_key'       => 'post_views',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
    'post_status'    => 'publish',
);

$most_read_single = new WP_Query( $args );

if ( $most_read_single->have_posts() ) : ?>
    <section class="single-most-read" aria-label="<?php esc_attr_e( 'Mais lidas', 'obdc-simplex-news' ); ?>">
        <h2 class="single-most-read__title"><?php esc_html_e( 'Mais lidas', 'obdc-simplex-news' ); ?></h2>
        <ol class="single-most-read__list" role="list">
            <?php while ( $most_read_single->have_posts() ) : $most_read_single->the_post(); ?>
                <li class="single-most-read__item">
                    <a class="single-most-read__link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>
            <?php endwhile; ?>
        </ol>
    </section>
<?php
    wp_reset_postdata();
endif;
