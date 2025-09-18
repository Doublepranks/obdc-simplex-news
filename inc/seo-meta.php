<?php
/**
 * ObDC-simplex-news SEO Meta Tags
 *
 * @package ObDC-simplex-news
 */

/**
 * Add Open Graph and Twitter Card meta tags.
 */
function obdc_simplex_news_add_og_tags() {
	if ( is_single() ) {
		global $post;

		// Open Graph
		echo '<meta property="og:type" content="article" />' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( wp_strip_all_tags( get_the_title() ) ) . '" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( wp_trim_words( get_the_excerpt(), 30 ) ) . '" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";

		// Featured image
		$thumbnail = get_the_post_thumbnail_url( null, 'hero' );
		if ( $thumbnail ) {
			echo '<meta property="og:image" content="' . esc_url( $thumbnail ) . '" />' . "\n";
		} else {
			// Fallback image
			echo '<meta property="og:image" content="' . esc_url( get_template_directory_uri() . '/images/og-default.jpg' ) . '" />' . "\n";
		}

		// Twitter Card
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
		echo '<meta name="twitter:title" content="' . esc_attr( wp_strip_all_tags( get_the_title() ) ) . '" />' . "\n";
		echo '<meta name="twitter:description" content="' . esc_attr( wp_trim_words( get_the_excerpt(), 30 ) ) . '" />' . "\n";
		echo '<meta name="twitter:site" content="@obrasildecima" />' . "\n";
		if ( $thumbnail ) {
			echo '<meta name="twitter:image" content="' . esc_url( $thumbnail ) . '" />' . "\n";
		} else {
			echo '<meta name="twitter:image" content="' . esc_url( get_template_directory_uri() . '/images/og-default.jpg' ) . '" />' . "\n";
		}
	}

	if ( is_home() || is_front_page() ) {
		// Homepage OG/Twitter
		echo '<meta property="og:type" content="website" />' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( home_url() ) . '" />' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
		echo '<meta name="twitter:card" content="summary" />' . "\n";
		echo '<meta name="twitter:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
		echo '<meta name="twitter:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '" />' . "\n";
		echo '<meta name="twitter:site" content="@obrasildecima" />' . "\n";
		// Fallback image for homepage
		echo '<meta property="og:image" content="' . esc_url( get_template_directory_uri() . '/images/og-default.jpg' ) . '" />' . "\n";
		echo '<meta name="twitter:image" content="' . esc_url( get_template_directory_uri() . '/images/og-default.jpg' ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'obdc_simplex_news_add_og_tags', 5 );


/**
 * Add canonical URL.
 */
function obdc_simplex_news_canonical_link() {
	if ( ! is_front_page() && ! is_home() ) {
		echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'obdc_simplex_news_canonical_link', 5 );


/**
 * Add robots meta tag.
 */
function obdc_simplex_news_robots_meta() {
	if ( is_home() || is_front_page() ) {
		echo '<meta name="robots" content="index, follow" />' . "\n";
	} elseif ( is_search() || is_404() ) {
		echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
	} else {
		echo '<meta name="robots" content="index, follow" />' . "\n";
	}
}
add_action( 'wp_head', 'obdc_simplex_news_robots_meta', 5 );