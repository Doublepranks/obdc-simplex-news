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

	// Yoast SEO primary category support (only when plugin is active).
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'WPSEO_Primary_Term' ) ) {
		$primary_cat_id = (int) get_post_meta( $post_id, '_yoast_wpseo_primary_category', true );
		if ( $primary_cat_id ) {
			$primary_term = get_term( $primary_cat_id, 'category' );
			if ( $primary_term && ! is_wp_error( $primary_term ) ) {
				return esc_html( $primary_term->name );
			}
		}
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
 * Build the share payload (encoded URLs and clipboard text) for a post.
 *
 * @param int|WP_Post|null $post Optional. Post object or ID. Defaults to current post in the loop.
 * @return array {
 *     @type array  $urls       Map of social network slug to fully encoded share URL.
 *     @type string $share_text Clipboard-ready text combining title and permalink.
 *     @type string $title      Post title decoded for sharing contexts.
 *     @type string $permalink  Absolute permalink for the post.
 * }
 */
function obdc_simplex_news_get_share_data( $post = null ) {
	static $cache = array();

	$post = get_post( $post );
	if ( ! $post instanceof WP_Post ) {
		return array(
			'urls'       => array(),
			'share_text' => '',
		);
	}

	$post_id = (int) $post->ID;
	if ( isset( $cache[ $post_id ] ) ) {
		return $cache[ $post_id ];
	}

	$permalink = get_permalink( $post_id );
	if ( ! $permalink ) {
		return array(
			'urls'       => array(),
			'share_text' => '',
		);
	}

	$charset      = get_bloginfo( 'charset' );
	$title        = html_entity_decode( get_the_title( $post_id ), ENT_QUOTES, $charset ? $charset : 'UTF-8' );
	$share_text   = trim( $title . ' ' . $permalink );
	$encoded_url  = rawurlencode( $permalink );
	$encoded_title = rawurlencode( $title );
	$encoded_text = rawurlencode( $share_text );

	$urls = array(
		'x'         => sprintf( 'https://twitter.com/intent/tweet?url=%1$s&text=%2$s', $encoded_url, $encoded_title ),
		'facebook'  => sprintf( 'https://www.facebook.com/sharer/sharer.php?u=%s', $encoded_url ),
		'whatsapp'  => sprintf( 'https://api.whatsapp.com/send?text=%s', $encoded_text ),
		'linkedin'  => sprintf( 'https://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s', $encoded_url, $encoded_title ),
		'instagram' => 'instagram://app',
	);

	$payload = array(
		'urls'       => apply_filters( 'obdc_simplex_news_share_urls', $urls, $post ),
		'share_text' => $share_text,
		'title'      => $title,
		'permalink'  => $permalink,
	);

	$cache[ $post_id ] = $payload;

	return $payload;
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
		'substack'  => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4h18v3H3V4zm0 5h18v12l-9-3-9 3V9z"/></svg>',
		'share'     => '<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 16a3 3 0 0 0-2.4 1.2l-6.3-3.2a3 3 0 0 0 0-2l6.3-3.2A3 3 0 1 0 15 6a3 3 0 0 0 .05.55l-6.3 3.2a3 3 0 1 0 0 4.5l6.3 3.2A3 3 0 1 0 18 16Z"/></svg>',
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

/**
 * Get default footer section labels keyed by menu location.
 *
 * @return array<string, string> Section defaults.
 */
function obdc_simplex_news_get_footer_section_defaults() {
	$defaults = array(
		'footer-news'          => esc_html__( 'Notícias', 'obdc-simplex-news' ),
		'footer-brazil'        => esc_html__( 'Brasil', 'obdc-simplex-news' ),
		'footer-site'          => esc_html__( 'Site', 'obdc-simplex-news' ),
		'footer-opinion'       => esc_html__( 'Opinião', 'obdc-simplex-news' ),
		'footer-sports'        => esc_html__( 'Esportes', 'obdc-simplex-news' ),
		'footer-entertainment' => esc_html__( 'Entretenimento', 'obdc-simplex-news' ),
		'footer-social'        => esc_html__( 'Siga o Brasil de Cima', 'obdc-simplex-news' ),
	);

	/**
	 * Filter the default footer section labels.
	 *
	 * @param array<string, string> $defaults Default labels keyed by location.
	 */
	return apply_filters( 'obdc_simplex_news_footer_section_defaults', $defaults );
}

/**
 * Get the theme_mod key for a footer section label.
 *
 * @param string $location Menu location.
 * @return string Theme mod key.
 */
function obdc_simplex_news_get_footer_section_label_mod_key( $location ) {
	$location_key = sanitize_key( str_replace( '-', '_', $location ) );
	return "obdc_simplex_news_footer_label_{$location_key}";
}

/**
 * Get the theme_mod key for a footer section initial state.
 *
 * @param string $location Menu location.
 * @return string Theme mod key.
 */
function obdc_simplex_news_get_footer_section_open_mod_key( $location ) {
	$location_key = sanitize_key( str_replace( '-', '_', $location ) );
	return "obdc_simplex_news_footer_open_{$location_key}";
}

/**
 * Retrieve the label for a footer section respecting Customizer overrides.
 *
 * @param string $location Menu location.
 * @return string Resolved label.
 */
function obdc_simplex_news_get_footer_section_label( $location ) {
	$defaults = obdc_simplex_news_get_footer_section_defaults();
	$default  = isset( $defaults[ $location ] ) ? $defaults[ $location ] : '';
	$mod_key  = obdc_simplex_news_get_footer_section_label_mod_key( $location );
	$label    = get_theme_mod( $mod_key, '' );

	if ( empty( $label ) ) {
		$label = $default;
	}

	/**
	 * Filter the footer section label after Theme Mod lookup.
	 *
	 * @param string $label    Resolved label.
	 * @param string $location Menu location.
	 * @param string $default  Default label.
	 */
	return apply_filters( 'obdc_simplex_news_footer_section_label', $label, $location, $default );
}

/**
 * Determine if a footer section should open by default on mobile.
 *
 * @param string $location Menu location.
 * @return bool True when the section should start expanded.
 */
function obdc_simplex_news_is_footer_section_open_mobile( $location ) {
	$mod_key = obdc_simplex_news_get_footer_section_open_mod_key( $location );
	$value   = get_theme_mod( $mod_key, false );
	$is_open = (bool) $value;

	/**
	 * Filter whether a footer section starts open on mobile.
	 *
	 * @param bool   $is_open  Current resolved value.
	 * @param string $location Menu location.
	 */
	return (bool) apply_filters( 'obdc_simplex_news_footer_section_open_mobile', $is_open, $location );
}

/**
 * Get a map of available author roles that can populate the mural.
 *
 * @return array<string, string> Role key => human-readable label.
 */
function obdc_simplex_news_get_available_author_roles() {
	$roles = array(
		// TODO: Allow subscribers when checkbox control is fixed (beta).
		'contributor'=> __( 'Colaborador', 'obdc-simplex-news' ),
		'author'     => __( 'Autor', 'obdc-simplex-news' ),
		'editor'     => __( 'Editor', 'obdc-simplex-news' ),
		'administrator' => __( 'Administrador', 'obdc-simplex-news' ),
	);

	/**
	 * Allow filtering of the available roles for the authors mural.
	 *
	 * @param array<string, string> $roles Role labels keyed by slug.
	 */
	return apply_filters( 'obdc_simplex_news_available_author_roles', $roles );
}

/**
 * Retrieve the theme setting with author IDs selected in the Customizer.
 *
 * @return int[] Ordered list of user IDs.
 */
function obdc_simplex_news_get_featured_author_ids_setting() {
	$ids = get_theme_mod( 'obdc_simplex_news_featured_authors', array() );

	if ( is_string( $ids ) ) {
		$ids = array_map( 'trim', explode( ',', $ids ) );
	}

	if ( empty( $ids ) ) {
		return array();
	}

	return array_values(
		array_unique(
			array_map( 'absint', array_filter( (array) $ids ) )
		)
	);
}

/**
 * Retrieve the list of roles enabled for the authors mural.
 *
 * @return string[] Role slugs.
 */
function obdc_simplex_news_get_featured_author_roles_setting() {
	$default_roles = array_keys( obdc_simplex_news_get_available_author_roles() );
	$roles         = get_theme_mod( 'obdc_simplex_news_featured_author_roles', $default_roles );

	if ( is_string( $roles ) ) {
		$roles = array_map( 'trim', explode( ',', $roles ) );
	}

	if ( empty( $roles ) || ! is_array( $roles ) ) {
		return $default_roles;
	}

	$roles = array_map( 'sanitize_key', $roles );
	$roles = array_values( array_intersect( $roles, $default_roles ) );

	return ! empty( $roles ) ? $roles : $default_roles;
}

/**
 * Retrieve the selected timeframe for fallback author ranking.
 *
 * @return int Number of days (7, 30, 90).
 */
function obdc_simplex_news_get_featured_author_period_setting() {
	$period = (int) get_theme_mod( 'obdc_simplex_news_featured_authors_period', 30 );

	if ( ! in_array( $period, array( 7, 30, 90 ), true ) ) {
		$period = 30;
	}

	return $period;
}

/**
 * Get a human-readable label for the first allowed role assigned to a user.
 *
 * @param WP_User $user          User object.
 * @param array   $roles_allowed Allowed role slugs.
 * @return string Human label or empty string.
 */
function obdc_simplex_news_get_user_role_label( WP_User $user, $roles_allowed ) {
	$available = obdc_simplex_news_get_available_author_roles();

	foreach ( (array) $user->roles as $role ) {
		if ( in_array( $role, $roles_allowed, true ) && isset( $available[ $role ] ) ) {
			return $available[ $role ];
		}
	}

	return '';
}

/**
 * Get the avatar URL for a given user, preferring custom meta when available.
 *
 * @param int $user_id User ID.
 * @return string Avatar URL.
 */
function obdc_simplex_news_get_user_avatar_url( $user_id ) {
	$custom_avatar = get_user_meta( $user_id, 'avatar', true );

	if ( is_string( $custom_avatar ) && ! empty( $custom_avatar ) ) {
		return esc_url_raw( $custom_avatar );
	}

	return get_avatar_url( $user_id, array( 'size' => 256 ) );
}

/**
 * Prepare the data payload for a single author card.
 *
 * @param WP_User $user          User object.
 * @param array   $roles_allowed Allowed role slugs.
 * @return array<string, mixed> Structured data for rendering.
 */
function obdc_simplex_news_prepare_author_card_data( WP_User $user, $roles_allowed ) {
	$role_label = obdc_simplex_news_get_user_role_label( $user, $roles_allowed );

	return array(
		'id'       => (int) $user->ID,
		'name'     => $user->display_name,
		'role'     => $role_label,
		'bio'      => get_the_author_meta( 'description', $user->ID ),
		'avatar'   => obdc_simplex_news_get_user_avatar_url( $user->ID ),
		'permalink'=> get_author_posts_url( $user->ID ),
	);
}

/**
 * Retrieve the list of authors to highlight in the front-page mural.
 *
 * @param int $limit Maximum number of authors.
 * @return array<int, array<string, mixed>> Structured author data.
 */
function obdc_simplex_news_get_featured_authors( $limit = 12 ) {
	$limit = max( 1, absint( $limit ) );
	$roles_allowed = obdc_simplex_news_get_featured_author_roles_setting();
	$selected_ids  = obdc_simplex_news_get_featured_author_ids_setting();
	$period_days   = obdc_simplex_news_get_featured_author_period_setting();

	$context_hash = md5(
		wp_json_encode(
			array(
				'limit'    => $limit,
				'roles'    => $roles_allowed,
				'selected' => $selected_ids,
				'period'   => $period_days,
			)
		)
	);

	$cache_key = sprintf( 'obdc_featured_authors_%s', $context_hash );
	$cached    = wp_cache_get( $cache_key, 'obdc_simplex_news' );

	if ( false !== $cached ) {
		return $cached;
	}

	$authors      = array();
	$used_ids     = array();

	if ( ! empty( $selected_ids ) ) {
		$user_query = get_users(
			array(
				'include' => $selected_ids,
				'orderby' => 'include',
				'number'  => $limit,
			)
		);

		foreach ( $user_query as $user ) {
			if ( empty( array_intersect( $roles_allowed, (array) $user->roles ) ) ) {
				continue;
			}

			if ( in_array( $user->ID, $used_ids, true ) ) {
				continue;
			}

			$authors[] = obdc_simplex_news_prepare_author_card_data( $user, $roles_allowed );
			$used_ids[] = (int) $user->ID;
			if ( count( $authors ) >= $limit ) {
				break;
			}
		}
	}

	// Fallback: authors ordered by recent activity.
	if ( count( $authors ) < $limit ) {
		$post_query  = new WP_Query(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'date_query'     => array(
					array(
						'after' => sprintf( '%d days ago', $period_days ),
					),
				),
				'no_found_rows'  => true,
			)
		);

		$author_counts = array();

		if ( $post_query->have_posts() ) {
			foreach ( $post_query->posts as $post_id ) {
				$author_id = (int) get_post_field( 'post_author', $post_id );
				if ( ! $author_id ) {
					continue;
				}
				if ( ! isset( $author_counts[ $author_id ] ) ) {
					$author_counts[ $author_id ] = 0;
				}
				$author_counts[ $author_id ]++;
			}
		}

		wp_reset_postdata();

		if ( ! empty( $author_counts ) ) {
			arsort( $author_counts );
			$ordered_ids = array_keys( $author_counts );
		} else {
			// Fallback to users with allowed roles when no recent posts exist.
			$ordered_ids = get_users(
				array(
					'role__in' => $roles_allowed,
					'fields'   => 'ID',
					'orderby'  => 'display_name',
					'order'    => 'ASC',
				)
			);
		}

		foreach ( $ordered_ids as $user_id ) {
			$user = get_user_by( 'id', $user_id );
			if ( ! $user instanceof WP_User ) {
				continue;
			}

			if ( empty( array_intersect( $roles_allowed, (array) $user->roles ) ) ) {
				continue;
			}

			if ( in_array( $user->ID, $used_ids, true ) ) {
				continue;
			}

			$authors[] = obdc_simplex_news_prepare_author_card_data( $user, $roles_allowed );
			$used_ids[] = (int) $user->ID;
			if ( count( $authors ) >= $limit ) {
				break;
			}
		}
	}

	$authors = array_slice( $authors, 0, $limit );

	/**
	 * Filter the featured authors list before caching.
	 *
	 * @param array $authors Prepared author data.
	 * @param int   $limit   Requested limit.
	 */
	$authors = apply_filters( 'obdc_simplex_news_featured_authors', $authors, $limit );

	wp_cache_set( $cache_key, $authors, 'obdc_simplex_news', HOUR_IN_SECONDS );

	return $authors;
}

/**
 * Whether there are featured authors for the mural.
 *
 * @return bool True when the authors carousel should render.
 */
function obdc_simplex_news_has_featured_authors() {
	$authors = obdc_simplex_news_get_featured_authors( 1 );
	return ! empty( $authors );
}

/**
 * Retrieve YouTube LIVE banner data based on theme settings.
 *
 * @return array{
 *     enabled: bool,
 *     live: bool,
 *     video_title: string,
 *     video_url: string,
 *     fallback_text: string
 * } Prepared data for the top bar.
 */
function obdc_simplex_news_get_youtube_live_banner_data() {
	static $cached = null;

	if ( null !== $cached ) {
		return $cached;
	}

	$enabled       = (bool) get_theme_mod( 'obdc_simplex_news_youtube_live_enabled', false );
	$api_key       = trim( (string) get_theme_mod( 'obdc_simplex_news_youtube_api_key', '' ) );
	$channel_id    = trim( (string) get_theme_mod( 'obdc_simplex_news_youtube_channel_id', '' ) );
	$fallback_text = get_theme_mod(
		'obdc_simplex_news_youtube_fallback_text',
		__( 'Um Brasil que pensa, comeca de cima.', 'obdc-simplex-news' )
	);
	$fallback_text = sanitize_text_field( $fallback_text );

	$default = array(
		'enabled'       => false,
		'live'          => false,
		'video_title'   => '',
		'video_url'     => '',
		'fallback_text' => $fallback_text,
	);

	if ( ! $enabled || '' === $api_key || '' === $channel_id ) {
		$cached = $default;
		return $cached;
	}

	$transient_key = sprintf( 'obdc_simplex_news_yt_live_%s', md5( $channel_id ) );
	$live_data     = get_transient( $transient_key );

	if ( false === $live_data ) {
		$query_args = array(
			'part'       => 'snippet',
			'channelId'  => $channel_id,
			'eventType'  => 'live',
			'type'       => 'video',
			'maxResults' => 1,
			'key'        => $api_key,
		);

		$request_url = add_query_arg( $query_args, 'https://www.googleapis.com/youtube/v3/search' );
		$response    = wp_remote_get(
			$request_url,
			array(
				'timeout' => 10,
			)
		);

		if ( is_wp_error( $response ) ) {
			$live_data = array(
				'live' => false,
			);
		} else {
			$body = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( ! empty( $body['items'] ) && isset( $body['items'][0]['id']['videoId'] ) ) {
				$item         = $body['items'][0];
				$video_id     = sanitize_text_field( $item['id']['videoId'] );
				$video_title  = isset( $item['snippet']['title'] ) ? sanitize_text_field( $item['snippet']['title'] ) : '';
				$live_data    = array(
					'live'        => true,
					'video_id'    => $video_id,
					'video_title' => $video_title,
				);
			} else {
				$live_data = array(
					'live' => false,
				);
			}
		}

		$cache_ttl = (int) apply_filters( 'obdc_simplex_news_youtube_live_cache_ttl', 10 * MINUTE_IN_SECONDS );
		set_transient( $transient_key, $live_data, max( 60, $cache_ttl ) );
	}

	if ( ! empty( $live_data['live'] ) && ! empty( $live_data['video_id'] ) ) {
		$cached = array(
			'enabled'       => true,
			'live'          => true,
			'video_title'   => $live_data['video_title'],
			'video_url'     => sprintf( 'https://www.youtube.com/watch?v=%s', rawurlencode( $live_data['video_id'] ) ),
			'fallback_text' => $fallback_text,
		);
	} else {
		$cached = array(
			'enabled'       => true,
			'live'          => false,
			'video_title'   => '',
			'video_url'     => '',
			'fallback_text' => $fallback_text,
		);
	}

	return $cached;
}
