<?php
/**
 * ObDC-simplex-news Helper Functions
 *
 * @package ObDC-simplex-news
 */

/**
 * Increment post view count.
 *
 * This is a fallback function if no plugin is used.
 * Call this function in single.php after the loop.
 */
function obdc_simplex_news_increment_post_views( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Get current count
	$count = (int) get_post_meta( $post_id, 'post_views', true );

	// Increment count
	$count++;

	// Update meta
	update_post_meta( $post_id, 'post_views', $count );
}


/**
 * Get the post view count.
 *
 * @param int $post_id The post ID.
 * @return int The view count.
 */
function obdc_simplex_news_get_post_views( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$count = (int) get_post_meta( $post_id, 'post_views', true );
	return $count;
}


/**
 * Get the category name for a post with fallback.
 *
 * @param int $post_id The post ID.
 * @return string The category name or empty string.
 */
function obdc_simplex_news_get_first_category_name( $post_id = null ) {
        if ( ! $post_id ) {
                $post_id = get_the_ID();
        }

        $categories = get_the_category( $post_id );
        if ( ! empty( $categories ) ) {
                return esc_html( $categories[0]->name );
        }

        return '';
}


/**
 * Get the structured list of posts used on the front page hero and highlights.
 *
 * @return array {
 *     @type int   $hero_id       The post ID used in the hero slot. Zero when unavailable.
 *     @type array $highlight_ids Post IDs used by the highlights (maximum of two).
 *     @type array $excluded_ids  Aggregated IDs that should be excluded from other queries.
 * }
 */
function obdc_simplex_news_get_front_page_featured_data() {
        static $cached_featured_data = null;

        if ( null !== $cached_featured_data ) {
                return $cached_featured_data;
        }

        $featured_data = array(
                'hero_id'       => 0,
                'highlight_ids' => array(),
                'excluded_ids'  => array(),
        );

        $sticky_post_ids = get_option( 'sticky_posts' );

        if ( ! empty( $sticky_post_ids ) ) {
                $sticky_query = new WP_Query(
                        array(
                                'post_type'           => 'post',
                                'post_status'         => 'publish',
                                'post__in'            => array_map( 'intval', $sticky_post_ids ),
                                'posts_per_page'      => 3,
                                'orderby'             => 'date',
                                'order'               => 'DESC',
                                'ignore_sticky_posts' => 1,
                        )
                );

                if ( $sticky_query->have_posts() ) {
                        $sticky_posts = $sticky_query->posts;

                        $hero_post = array_shift( $sticky_posts );

                        if ( $hero_post instanceof WP_Post ) {
                                $hero_id                      = (int) $hero_post->ID;
                                $featured_data['hero_id']     = $hero_id;
                                $featured_data['excluded_ids'][] = $hero_id;
                        }

                        foreach ( $sticky_posts as $sticky_post ) {
                                if ( count( $featured_data['highlight_ids'] ) >= 2 ) {
                                        break;
                                }

                                if ( $sticky_post instanceof WP_Post ) {
                                        $highlight_id = (int) $sticky_post->ID;
                                        $featured_data['highlight_ids'][] = $highlight_id;
                                        $featured_data['excluded_ids'][]  = $highlight_id;
                                }
                        }
                }

                wp_reset_postdata();
        }

        if ( ! $featured_data['hero_id'] ) {
                $hero_query = new WP_Query(
                        array(
                                'post_type'           => 'post',
                                'post_status'         => 'publish',
                                'posts_per_page'      => 1,
                                'orderby'             => 'date',
                                'order'               => 'DESC',
                                'ignore_sticky_posts' => 1,
                                'post__not_in'        => $featured_data['excluded_ids'],
                        )
                );

                if ( $hero_query->have_posts() ) {
                        $hero_post = $hero_query->posts[0];

                        if ( $hero_post instanceof WP_Post ) {
                                $hero_id                      = (int) $hero_post->ID;
                                $featured_data['hero_id']     = $hero_id;
                                $featured_data['excluded_ids'][] = $hero_id;
                        }
                }

                wp_reset_postdata();
        }

        $highlight_needed = 2 - count( $featured_data['highlight_ids'] );

        if ( $highlight_needed > 0 ) {
                $highlight_query = new WP_Query(
                        array(
                                'post_type'           => 'post',
                                'post_status'         => 'publish',
                                'posts_per_page'      => $highlight_needed,
                                'orderby'             => 'date',
                                'order'               => 'DESC',
                                'ignore_sticky_posts' => 1,
                                'post__not_in'        => $featured_data['excluded_ids'],
                        )
                );

                if ( $highlight_query->have_posts() ) {
                        foreach ( $highlight_query->posts as $highlight_post ) {
                                if ( $highlight_post instanceof WP_Post ) {
                                        $highlight_id = (int) $highlight_post->ID;
                                        $featured_data['highlight_ids'][] = $highlight_id;
                                        $featured_data['excluded_ids'][]  = $highlight_id;
                                }
                        }
                }

                wp_reset_postdata();
        }

        $featured_data['highlight_ids'] = array_values( array_unique( array_map( 'intval', $featured_data['highlight_ids'] ) ) );
        $featured_data['excluded_ids']  = array_values( array_unique( array_map( 'intval', $featured_data['excluded_ids'] ) ) );

        /**
         * Filter the featured posts used by the front page hero and highlights.
         *
         * @since 1.0.0
         *
         * @param array $featured_data {
         *     @type int   $hero_id       The hero post ID.
         *     @type array $highlight_ids Highlight post IDs.
         *     @type array $excluded_ids  IDs excluded from other queries.
         * }
         */
        $cached_featured_data = apply_filters( 'obdc_simplex_news_front_page_featured_data', $featured_data );

        return $cached_featured_data;
}

/**
 * Get the IDs of posts displayed in the hero and highlight areas on the front page.
 *
 * @return array List of post IDs to exclude from the feed loop.
 */
function obdc_simplex_news_get_front_page_excluded_post_ids() {
        $featured_data = obdc_simplex_news_get_front_page_featured_data();

        if ( empty( $featured_data['excluded_ids'] ) ) {
                return array();
        }

        return $featured_data['excluded_ids'];
}
