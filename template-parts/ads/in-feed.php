<?php
/**
 * Template part for displaying the in-feed ad slot.
 *
 * @package ObDC-simplex-news
 */

// Subscriber: no ads.
if (function_exists('obdc_simplex_news_should_hide_ads') && obdc_simplex_news_should_hide_ads()) {
	return;
}

// Display widget area for in-feed ad
if (is_active_sidebar('in_feed')) {
	dynamic_sidebar('in_feed');
} else {
	// Fallback if no widget is set
	?>
	<div class="ad ad--in-feed" aria-label="Espaço publicitário">
		AD SLOT — in_feed (banner responsivo)
	</div>
	<?php
}
