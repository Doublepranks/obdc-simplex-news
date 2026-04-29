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
 * Each block includes an inline push() call. This is intentional:
 * when Auto Ads are enabled, Google's adsbygoogle.js auto-processes
 * ALL <ins> elements on load. If no push() is queued for a slot,
 * it gets marked "done" but unfilled. The inline push() ensures
 * each slot is properly queued before Google's auto-scan.
 *
 * @param string $client_id  AdSense publisher ID (e.g. ca-pub-XXX).
 * @param string $slot_id    Ad unit slot ID.
 * @param string $format     Ad format. Default 'auto'.
 * @param string $layout_key Optional layout key for In-Feed native ads.
 * @return string HTML for the ad unit.
 */
function obdc_simplex_news_adsense_block($client_id, $slot_id, $format = 'auto', $layout_key = '')
{
	if (empty($client_id) || empty($slot_id)) {
		return '';
	}

	$layout_attr = '';
	if ('fluid' === $format && !empty($layout_key)) {
		$layout_attr = sprintf(' data-ad-layout-key="%s"', esc_attr($layout_key));
	}

	// Use explicit width:100% on the ins tag so AdSense can measure it.
	$ins_style = ('fluid' === $format)
		? 'display:block;width:100%'
		: 'display:block;width:100%;height:auto';

	return sprintf(
		'<div class="ad-container" aria-label="%s">'
		. '<ins class="adsbygoogle" style="%s" data-ad-client="%s" data-ad-slot="%s" data-ad-format="%s" data-full-width-responsive="true"%s></ins>'
		. '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>'
		. '</div>',
		esc_attr__('Publicidade', 'obdc-simplex-news'),
		esc_attr($ins_style),
		esc_attr($client_id),
		esc_attr($slot_id),
		esc_attr($format),
		$layout_attr
	);
}

/**
 * Get the next ad slot ID from a comma-separated list in Customizer.
 * Maintains an internal rotation counter per setting.
 *
 * @param string $setting_key The Customizer setting key.
 * @return string|false Slot ID or false if not configured.
 */
function obdc_simplex_news_get_next_ad_slot_id($setting_key)
{
	static $counters = array();

	if (!isset($counters[$setting_key])) {
		$counters[$setting_key] = 0;
	}

	$raw = get_theme_mod($setting_key, '');
	if (empty($raw)) {
		return false;
	}

	$slots = array_filter(array_map('trim', explode(',', $raw)));
	if (empty($slots)) {
		return false;
	}

	// Re-index array to ensure sequential keys
	$slots = array_values($slots);

	$slot_id = $slots[$counters[$setting_key] % count($slots)];
	$counters[$setting_key]++;

	return $slot_id;
}

/**
 * Inject AdSense ads between top-level block elements in single post content.
 *
 * Ads are placed only after top-level closing tags (</p>, </blockquote>,
 * </figure>, etc.) — never inside nested elements like blockquotes,
 * figures, lists, or tables.
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

	if (empty($client_id)) {
		return $content;
	}

	$frequency = absint(get_theme_mod('obdc_simplex_news_adsense_in_content_frequency', 3));
	if ($frequency < 2) {
		$frequency = 3;
	}

	/*
	 * Elements whose </closing> tags we should NEVER inject an ad
	 * immediately inside of. When we are inside one of these, $depth > 0
	 * and we skip injection points.
	 */
	$wrapper_tags = array('blockquote', 'figure', 'figcaption', 'ul', 'ol', 'li', 'table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th', 'details', 'summary', 'aside', 'nav', 'pre');

	/*
	 * Match every opening or closing tag for any of the wrapper elements,
	 * plus every </p> (our primary insertion marker).
	 * Self-closing tags (e.g. <img />) are ignored.
	 */
	$tag_names = implode('|', $wrapper_tags);
	$pattern = '#(<(?:' . $tag_names . ')[\s>])|(<\/(?:' . $tag_names . ')\s*>)|(<\/p\s*>)#i';

	// Split the content into alternating text / tag-match chunks.
	$pieces = preg_split($pattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

	if (empty($pieces)) {
		return $content;
	}

	$depth = 0;   // How many wrapper elements deep we are.
	$top_level_blocks = 0;   // Counter for top-level block elements passed.
	$output = '';

	foreach ($pieces as $piece) {
		// Is this piece an opening tag for a wrapper element?
		if (preg_match('#^<(' . $tag_names . ')[\s>]#i', $piece)) {
			$depth++;
			$output .= $piece;
			continue;
		}

		// Is this piece a closing tag for a wrapper element?
		if (preg_match('#^</(' . $tag_names . ')\s*>#i', $piece)) {
			$depth = max(0, $depth - 1);
			$output .= $piece;

			// If we just closed a top-level wrapper, count it as a block.
			if ($depth === 0) {
				$top_level_blocks++;

				if ($top_level_blocks % $frequency === 0) {
					$current_slot_id = obdc_simplex_news_get_next_ad_slot_id('obdc_simplex_news_adsense_slot_in_content');
					if ($current_slot_id) {
						$output .= obdc_simplex_news_adsense_block($client_id, $current_slot_id, 'auto');
					}
				}
			}
			continue;
		}

		// Is this piece a closing </p> tag?
		if (preg_match('#^</p\s*>#i', $piece)) {
			$output .= $piece;

			// Only inject after top-level paragraphs (depth 0).
			if ($depth === 0) {
				$top_level_blocks++;

				if ($top_level_blocks % $frequency === 0) {
					$current_slot_id = obdc_simplex_news_get_next_ad_slot_id('obdc_simplex_news_adsense_slot_in_content');
					if ($current_slot_id) {
						$output .= obdc_simplex_news_adsense_block($client_id, $current_slot_id, 'auto');
					}
				}
			}
			continue;
		}

		// Regular text / other HTML — pass through.
		$output .= $piece;
	}

	return $output;
}
add_filter('the_content', 'obdc_simplex_news_inject_in_content_ads', 20);