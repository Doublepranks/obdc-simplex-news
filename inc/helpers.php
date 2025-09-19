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
 * Get the IDs of posts displayed in the hero and highlight areas on the front page.
 *
 * @return array List of post IDs to exclude from the feed loop.
 */
function obdc_simplex_news_get_front_page_excluded_post_ids() {
        $excluded_post_ids = array();
        $hero_post_id      = null;

        // Attempt to fetch a featured hero post first.
        $featured_hero_query = new WP_Query(
                array(
                        'posts_per_page' => 1,
                        'meta_key'       => '_featured_on_home',
                        'meta_value'     => '1',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'fields'         => 'ids',
                )
        );

        if ( ! empty( $featured_hero_query->posts ) ) {
                $hero_post_id = (int) $featured_hero_query->posts[0];
        }

        wp_reset_postdata();

        // Fallback to the latest post if no featured hero exists.
        if ( ! $hero_post_id ) {
                $latest_hero_query = new WP_Query(
                        array(
                                'posts_per_page' => 1,
                                'orderby'        => 'date',
                                'order'          => 'DESC',
                                'fields'         => 'ids',
                        )
                );

                if ( ! empty( $latest_hero_query->posts ) ) {
                        $hero_post_id = (int) $latest_hero_query->posts[0];
                }

                wp_reset_postdata();
        }

        if ( $hero_post_id ) {
                $excluded_post_ids[] = $hero_post_id;
        }

        // Fetch highlight posts, excluding the hero when available.
        $highlights_args = array(
                'posts_per_page' => 2,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'fields'         => 'ids',
                'post_status'    => 'publish',
        );

        if ( $hero_post_id ) {
                $highlights_args['post__not_in'] = array( $hero_post_id );
        }

        $highlights_query = new WP_Query( $highlights_args );

        if ( ! empty( $highlights_query->posts ) ) {
                $excluded_post_ids = array_merge( $excluded_post_ids, array_map( 'intval', $highlights_query->posts ) );
        }

        wp_reset_postdata();

        return array_values( array_unique( $excluded_post_ids ) );
}