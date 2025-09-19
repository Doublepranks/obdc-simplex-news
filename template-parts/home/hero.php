<?php
/**
 * Template part for displaying the lead hero story.
 *
 * Expects to be used within a loop where the global $post is the hero entry.
 *
 * @package ObDC-simplex-news
 */

global $post;

if ( ! isset( $post ) ) {
        return;
}

$hero_title_id = 'lead-title-' . get_the_ID();
$hero_city     = get_post_meta( get_the_ID(), 'cidade', true );
?>

<article class="hero-card" aria-labelledby="<?php echo esc_attr( $hero_title_id ); ?>">
        <a href="<?php the_permalink(); ?>" class="media">
                <?php
                if ( has_post_thumbnail() ) {
                        the_post_thumbnail( 'hero', array( 'alt' => esc_attr( get_the_title() ) ) );
                }
                ?>
        </a>
        <div class="body">
                <div class="kicker">
                        <?php
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) {
                                echo esc_html( $categories[0]->name );
                        }
                        ?>
                </div>
                <h2 id="<?php echo esc_attr( $hero_title_id ); ?>" class="title-xl"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p class="excerpt-hero"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
                <p class="meta">
                        <?php
                        printf(
                                /* translators: 1: author name, 2: human readable time difference. */
                                esc_html__( 'Por %1$s • %2$s atrás', 'obdc-simplex-news' ),
                                esc_html( get_the_author() ),
                                esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) )
                        );

                        if ( ! empty( $hero_city ) ) {
                                printf(
                                        /* translators: %s: city name meta field. */
                                        esc_html__( ' • %s', 'obdc-simplex-news' ),
                                        esc_html( $hero_city )
                                );
                        }
                        ?>
                </p>
        </div>
</article>

