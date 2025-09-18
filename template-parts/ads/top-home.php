<?php
/**
 * Template part for displaying the top home ad slot.
 *
 * @package ObDC-simplex-news
 */

// Display widget area for top home ad
if ( is_active_sidebar( 'top_home' ) ) {
	dynamic_sidebar( 'top_home' );
} else {
	// Fallback if no widget is set
	?>
	<div class="ad" aria-label="Espaço publicitário">
		AD SLOT — top_home (banner responsivo / 970×250, 728×90, 320×100)
	</div>
	<?php
}

// Note: In production, this would be replaced by a proper ad manager like AdSense or Ad Manager
// with lazy loading and capping logic. For now, it's a placeholder.
