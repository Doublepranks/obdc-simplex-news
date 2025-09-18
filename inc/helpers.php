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