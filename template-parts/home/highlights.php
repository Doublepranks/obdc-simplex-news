<?php
/**
 * Template part for displaying the secondary highlights (two cards).
 *
 * @package ObDC-simplex-news
 */

$featured_data = get_query_var( 'obdc_featured_data' );

if ( empty( $featured_data ) && function_exists( 'obdc_simplex_news_get_front_page_featured_data' ) ) {
        $featured_data = obdc_simplex_news_get_front_page_featured_data();
}

$highlight_ids = array();

if ( ! empty( $featured_data['highlight_ids'] ) && is_array( $featured_data['highlight_ids'] ) ) {
        $highlight_ids = array_map( 'intval', $featured_data['highlight_ids'] );
}

if ( ! empty( $highlight_ids ) ) :
        foreach ( $highlight_ids as $highlight_id ) :
                $title        = get_the_title( $highlight_id );
                $permalink    = get_permalink( $highlight_id );
                $thumbnail    = get_the_post_thumbnail( $highlight_id, 'card', array( 'alt' => esc_attr( $title ) ) );
                $categories   = get_the_category( $highlight_id );
                $category     = ! empty( $categories ) ? $categories[0]->name : '';
                $published_at = human_time_diff( get_post_time( 'U', false, $highlight_id ), current_time( 'timestamp' ) );
        ?>
        <article class="hero-card">
                <a href="<?php echo esc_url( $permalink ); ?>" class="media">
                        <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </a>
                <div class="body">
                        <div class="kicker">
                                <?php if ( $category ) : ?>
                                        <?php echo esc_html( $category ); ?>
                                <?php endif; ?>
                        </div>
                        <h3 class="title-md"><a href="<?php echo esc_url( $permalink ); ?>"> <?php echo esc_html( $title ); ?></a></h3>
                        <?php if ( $published_at ) : ?>
                                <p class="meta"> <?php printf( esc_html__( '%s atrás', 'obdc-simplex-news' ), esc_html( $published_at ) ); ?> </p>
                        <?php endif; ?>
                </div>
        </article>
        <?php endforeach; ?>
<?php endif; ?>
