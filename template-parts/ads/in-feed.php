<?php
/**
 * Template part for displaying the in-feed ad slot.
 *
 * Uses rotation helper to cycle through multiple slot IDs.
 * Priority: AdSense (if configured) > Widget Area > Placeholder.
 *
 * @package ObDC-simplex-news
 */

// Subscriber: no ads.
if (function_exists('obdc_simplex_news_should_hide_ads') && obdc_simplex_news_should_hide_ads()) {
	return;
}

$client_id = get_theme_mod('obdc_simplex_news_adsense_client_id', '');
$layout_key = get_theme_mod('obdc_simplex_news_adsense_layout_key_feed', '');
$slot_id = function_exists('obdc_simplex_news_get_next_ad_slot_id')
	? obdc_simplex_news_get_next_ad_slot_id('obdc_simplex_news_adsense_slot_feed')
	: false;

// 1. AdSense with rotation (if both client and slot are configured).
if (!empty($client_id) && !empty($slot_id) && function_exists('obdc_simplex_news_adsense_block')) {
	if (!empty($layout_key)) {
		echo obdc_simplex_news_adsense_block($client_id, $slot_id, 'fluid', $layout_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped internally.
	} else {
		echo obdc_simplex_news_adsense_block($client_id, $slot_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped internally.
	}
	return;
}

// 2. Widget area fallback.
if (is_active_sidebar('in_feed')) {
	dynamic_sidebar('in_feed');
	return;
}

// 3. Placeholder fallback (dev only).
?>
<div class="ad ad--in-feed" aria-label="Espaço publicitário">
	AD SLOT — in_feed (banner responsivo)
</div>