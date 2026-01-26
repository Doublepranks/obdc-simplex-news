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
