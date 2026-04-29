<?php
/**
 * Template part for displaying the top home ad slot.
 *
 * Priority: AdSense (if configured) > Widget Area > Placeholder.
 *
 * @package ObDC-simplex-news
 */

// Subscriber: no ads.
if (function_exists('obdc_simplex_news_should_hide_ads') && obdc_simplex_news_should_hide_ads()) {
	return;
}

$client_id = get_theme_mod('obdc_simplex_news_adsense_client_id', '');
$slot_id = function_exists('obdc_simplex_news_get_next_ad_slot_id')
	? obdc_simplex_news_get_next_ad_slot_id('obdc_simplex_news_adsense_slot_top')
	: false;

// 1. AdSense (if both client and slot are configured).
if (!empty($client_id) && !empty($slot_id) && function_exists('obdc_simplex_news_adsense_block')) {
	echo obdc_simplex_news_adsense_block($client_id, $slot_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped internally.
	return;
}

// 2. Widget area fallback.
if (is_active_sidebar('top_home')) {
	dynamic_sidebar('top_home');
	return;
}

// 3. Placeholder fallback (dev only).
?>
<div class="ad" aria-label="Espaço publicitário">
	AD SLOT — top_home (banner responsivo / 970×250, 728×90, 320×100)
</div>