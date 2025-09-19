<?php
/**
 * Template part for displaying the lead hero story.
 *
 * @package ObDC-simplex-news
 */

$featured_data = get_query_var( 'obdc_featured_data' );

if ( empty( $featured_data ) && function_exists( 'obdc_simplex_news_get_front_page_featured_data' ) ) {
        $featured_data = obdc_simplex_news_get_front_page_featured_data();
}

$hero_id = isset( $featured_data['hero_id'] ) ? (int) $featured_data['hero_id'] : 0;

if ( $hero_id ) :
        $title        = get_the_title( $hero_id );
        $permalink    = get_permalink( $hero_id );
        $thumbnail    = get_the_post_thumbnail( $hero_id, 'hero', array( 'alt' => esc_attr( $title ) ) );
        $categories   = get_the_category( $hero_id );
        $category     = ! empty( $categories ) ? $categories[0]->name : '';
        $excerpt      = wp_trim_words( wp_strip_all_tags( get_the_excerpt( $hero_id ) ), 30 );
        $author_id    = (int) get_post_field( 'post_author', $hero_id );
        $author_name  = get_the_author_meta( 'display_name', $author_id );
        $published_at = human_time_diff( get_post_time( 'U', false, $hero_id ), current_time( 'timestamp' ) );
        $cidade       = get_post_meta( $hero_id, 'cidade', true );
?>
<article class="hero-card" aria-labelledby="lead-title">
        <a href="<?php echo esc_url( $permalink ); ?>" class="media">
                <?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </a>
        <div class="body">
                <div class="kicker">
                        <?php if ( $category ) : ?>
                                <?php echo esc_html( $category ); ?>
                        <?php endif; ?>
                </div>
                <h2 id="lead-title" class="title-xl"><a href="<?php echo esc_url( $permalink ); ?>"> <?php echo esc_html( $title ); ?></a></h2>
                <?php if ( $excerpt ) : ?>
                        <p class="excerpt-hero"> <?php echo esc_html( $excerpt ); ?> </p>
                <?php endif; ?>
                <p class="meta">
                        <?php
                        $meta_parts = array();

                        if ( $author_name ) {
                                $meta_parts[] = sprintf(
                                        /* translators: %s: author name */
                                        esc_html__( 'Por %s', 'obdc-simplex-news' ),
                                        esc_html( $author_name )
                                );
                        }

                        if ( $published_at ) {
                                /* translators: %s: human-readable time difference (e.g. "2 horas"). */
                                $meta_parts[] = sprintf( esc_html__( '%s atrás', 'obdc-simplex-news' ), esc_html( $published_at ) );
                        }

                        if ( $cidade ) {
                                $meta_parts[] = esc_html( $cidade );
                        }

                        echo implode( ' • ', array_filter( $meta_parts ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                </p>
        </div>
</article>
<?php endif; ?>