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


/**
 * Estimate reading time for a post.
 *
 * @param int $post_id Optional post ID.
 * @return string Formatted reading time.
 */
function obdc_simplex_news_get_reading_time( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$content = get_post_field( 'post_content', $post_id );
	if ( empty( $content ) ) {
		return '';
	}

	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	$minutes    = max( 1, (int) ceil( $word_count / 200 ) );

	return sprintf(
		_n( '%s minuto de leitura', '%s minutos de leitura', $minutes, 'obdc-simplex-news' ),
		number_format_i18n( $minutes )
	);
}

/**
 * Retrieve SVG markup for a supported social network.
 *
 * @param string $network Social network slug.
 * @return string SVG markup or empty string when unavailable.
 */
function obdc_simplex_news_get_social_icon_svg( $network ) {
	$network = sanitize_key( $network );

	$icons = array(
		'facebook'  => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 22H9v-8H6v-4h3V6.7C9 4 10.6 2 13.9 2H18v4h-2.7c-1 0-1.3.4-1.3 1.2V10h4l-.6 4h-3.4z"/></svg>',
		'x'         => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 5.5h3.3l5 6.4 4.4-6.4H21l-6.4 9.3 5.5 7.2h-3.3l-4.6-6-4.1 6H3l6.7-9.6z"/></svg>',
		'twitter'   => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 5.5h3.3l5 6.4 4.4-6.4H21l-6.4 9.3 5.5 7.2h-3.3l-4.6-6-4.1 6H3l6.7-9.6z"/></svg>',
		'instagram' => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zm0 2a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm6.25-.88a1.12 1.12 0 1 1-2.24 0 1.12 1.12 0 0 1 2.24 0z"/></svg>',
		'youtube'   => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M21.6 7.2a2.4 2.4 0 0 0-1.7-1.7C18.2 5 12 5 12 5s-6.2 0-7.9.5a2.4 2.4 0 0 0-1.7 1.7C2 9 2 12 2 12s0 3 .4 4.8a2.4 2.4 0 0 0 1.7 1.7C5.8 19 12 19 12 19s6.2 0 7.9-.5a2.4 2.4 0 0 0 1.7-1.7C22 15 22 12 22 12s0-3-.4-4.8zM10 15.5v-7l6 3.5z"/></svg>',
		'whatsapp'  => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 2.118.553 4.154 1.602 5.957L0 24l6.267-1.643A11.94 11.94 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0Zm5.746 17.266c-.246.7-1.454 1.29-2.016 1.377-.516.08-1.187.113-1.918-.12-.441-.14-1.004-.327-1.733-.64-3.056-1.306-5.05-4.333-5.2-4.536-.153-.204-1.244-1.652-1.244-3.155 0-1.504.79-2.24 1.07-2.55.246-.27.54-.34.72-.34.18 0 .36.003.517.01.165.007.389-.062.61.467.246.59.84 2.045.915 2.19.073.145.12.316.02.51-.096.203-.146.316-.292.486-.146.17-.31.38-.442.51-.146.146-.298.305-.128.595.17.29.755 1.24 1.622 2.005 1.115.996 2.056 1.304 2.346 1.45.29.145.456.122.62-.073.165-.195.71-.827.902-1.112.19-.284.38-.238.63-.145.246.086 1.558.735 1.825.868.27.133.45.2.52.31.073.11.073.7-.173 1.403Z"/></svg>',
		'linkedin'  => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5A2.5 2.5 0 1 1 0 3.5a2.5 2.5 0 0 1 4.98 0zM0 8.82h4.95V24H0zm7.98 0H12v2.1h.05c.6-1.1 2-2.2 4.1-2.2 3.9 0 4.9 2.5 4.9 5.8V24h-4.95v-5.6c0-1.3 0-3-1.9-3s-2.2 1.4-2.2 2.9V24H7.98z"/></svg>',
		'tiktok'    => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 3.5c1 1.5 2.5 2.4 4.3 2.5v3.5c-1.5-.03-2.9-.4-4.3-1v6.1a5.4 5.4 0 1 1-5.4-5.4c.3 0 .6 0 .9.1v3.4a2 2 0 1 0 1.4 1.9V2.5h3.1z"/></svg>',
		'kwai'      => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M8.4 2.2c1.7-.6 3.5-.6 5.2 0l6.1 2.2c1.2.4 2.3 1.9 2.3 3.3v8.6c0 1.5-1.1 2.9-2.3 3.3l-6.1 2.2c-1.7.6-3.5.6-5.2 0l-6.1-2.2C1.2 19.2.1 17.8.1 16.3V7.7c0-1.4 1.1-2.9 2.3-3.3zm-.4 5.5a2.3 2.3 0 1 0 0 4.6 2.3 2.3 0 0 0 0-4.6zm7 4.7-2.5-1.6 2.5-1.6a2.3 2.3 0 0 0 3.3-2 2.3 2.3 0 0 0-3.3-2l-5.8 3.7a2.3 2.3 0 0 0 0 3.9l5.8 3.7a2.3 2.3 0 0 0 3.3-2 2.3 2.3 0 0 0-3.3-2z"/></svg>',
	);

	/**
	 * Allow filtering of the available social icons.
	 *
	 * @param array $icons Icon map keyed by social slug.
	 */
	$icons = apply_filters( 'obdc_simplex_news_social_icons', $icons );

	return isset( $icons[ $network ] ) ? $icons[ $network ] : '';
}
