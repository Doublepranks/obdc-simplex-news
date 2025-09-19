<?php
/**
 * Template part for displaying a highlight card.
 *
 * Expects to be used inside a loop configured by front-page.php.
 *
 * @package ObDC-simplex-news
 */

global $post;

if ( ! isset( $post ) ) {
        return;
}
?>

<article class="hero-card">
        <a href="<?php the_permalink(); ?>" class="media">
                <?php
                if ( has_post_thumbnail() ) {
                        the_post_thumbnail( 'card', array( 'alt' => esc_attr( get_the_title() ) ) );
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
                <h3 class="title-md"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="meta">
                        <?php
                        printf(
                                /* translators: %s: human readable time difference. */
                                esc_html__( '%s atrás', 'obdc-simplex-news' ),
                                esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) )
                        );
                        ?>
                </p>
        </div>
</article>

