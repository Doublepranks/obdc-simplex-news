<?php
/**
 * Ads-related functionality.
 *
 * @package ObDC-simplex-news
 */

/**
 * Check if ads should be hidden for the current user.
 *
 * @return bool True if ads should NOT be displayed.
 */
function obdc_simplex_news_should_hide_ads()
{
	// Not logged in = show ads.
	if (!is_user_logged_in()) {
		return false;
	}

	$user = wp_get_current_user();

	/**
	 * Filter the roles that should NOT see ads.
	 *
	 * @param array $ad_free_roles Roles that get ad-free experience.
	 */
	$ad_free_roles = apply_filters(
		'obdc_simplex_news_ad_free_roles',
		array('subscriber', 'author', 'editor', 'administrator')
	);

	// Check if user has any of the ad-free roles
	foreach ((array) $user->roles as $role) {
		if (in_array($role, $ad_free_roles, true)) {
			return true;
		}
	}

	return false;
}

/**
 * Add ad-free class to body for subscribers.
 *
 * @param array $classes Existing body classes.
 * @return array Modified body classes.
 */
function obdc_simplex_news_add_ad_free_body_class($classes)
{
	if (function_exists('obdc_simplex_news_should_hide_ads') && obdc_simplex_news_should_hide_ads()) {
		$classes[] = 'obdc-ad-free';
	}
	return $classes;
}
add_filter('body_class', 'obdc_simplex_news_add_ad_free_body_class');

// ─── AdSense Helpers ──────────────────────────────────────────────────

/**
 * Build an AdSense responsive ad unit block.
 *
 * @param string $client_id AdSense publisher ID (e.g. ca-pub-XXX).
 * @param string $slot_id   Ad unit slot ID.
 * @param string $format    Ad format. Default 'auto'.
 * @return string HTML for the ad unit.
 */
function obdc_simplex_news_adsense_block($client_id, $slot_id, $format = 'auto')
{
	if (empty($client_id) || empty($slot_id)) {
		return '';
	}

	return sprintf(
		'<div class="ad-container" aria-label="%s">'
		. '<ins class="adsbygoogle" style="display:block" data-ad-client="%s" data-ad-slot="%s" data-ad-format="%s" data-full-width-responsive="true"></ins>'
		. '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>'
		. '</div>',
		esc_attr__('Publicidade', 'obdc-simplex-news'),
		esc_attr($client_id),
		esc_attr($slot_id),
		esc_attr($format)
	);
}

/**
 * Get the next feed ad slot ID, rotating through a comma-separated list.
 *
 * @return string|false Slot ID or false if not configured.
 */
function obdc_simplex_news_get_next_feed_ad_slot_id()
{
	static $counter = 0;

	$raw = get_theme_mod('obdc_simplex_news_adsense_slot_feed', '');
	if (empty($raw)) {
		return false;
	}

	$slots = array_filter(array_map('trim', explode(',', $raw)));
	if (empty($slots)) {
		return false;
	}

	$slot_id = $slots[$counter % count($slots)];
	$counter++;

	return $slot_id;
}

/**
 * Inject AdSense ads between paragraphs in single post content.
 *
 * @param string $content Post content.
 * @return string Modified content with ads inserted.
 */
function obdc_simplex_news_inject_in_content_ads($content)
{
	// Only on single posts in the main query.
	if (!is_singular('post') || !is_main_query()) {
		return $content;
	}

	// Don't show ads to subscribers.
	if (obdc_simplex_news_should_hide_ads()) {
		return $content;
	}

	$client_id = get_theme_mod('obdc_simplex_news_adsense_client_id', '');
	$slot_id = get_theme_mod('obdc_simplex_news_adsense_slot_in_content', '');

	if (empty($client_id) || empty($slot_id)) {
		return $content;
	}

	$frequency = absint(get_theme_mod('obdc_simplex_news_adsense_in_content_frequency', 3));
	if ($frequency < 2) {
		$frequency = 3;
	}

	// Split content on closing </p> tags to count paragraphs.
	$closing_tag = '</p>';
	$paragraphs = explode($closing_tag, $content);
	$total = count($paragraphs);

	// Need at least frequency + 2 paragraphs to inject an ad
	// (avoid injecting in very short posts or at the very end).
	if ($total < $frequency + 2) {
		return $content;
	}

	$ad_block = obdc_simplex_news_adsense_block($client_id, $slot_id, 'auto');
	$output = '';

	foreach ($paragraphs as $index => $paragraph) {
		$output .= $paragraph;

		// Re-add the closing tag (except for the last empty segment from explode).
		if ($index < $total - 1) {
			$output .= $closing_tag;
		}

		// Insert ad after every Nth paragraph (1-indexed), but not after the last.
		$paragraph_number = $index + 1;
		if ($paragraph_number % $frequency === 0 && $paragraph_number < $total - 1) {
			$output .= $ad_block;
		}
	}

	return $output;
}
add_filter('the_content', 'obdc_simplex_news_inject_in_content_ads', 20);
