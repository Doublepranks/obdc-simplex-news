<?php
/**
 * Template part for displaying the top live ticker.
 *
 * @package ObDC-simplex-news
 */

// Get customizer setting for live status
$live_status = get_theme_mod( 'obdc_simplex_news_live_status', 'on' );
$live_text = get_theme_mod( 'obdc_simplex_news_live_text', __( 'Sinal ao vivo quando o canal estiver no ar', 'obdc-simplex-news' ) );

if ( $live_status === 'on' ) : ?>
<div class="topbar">
	<div class="wrap">
		<div class="ticker" aria-live="polite">
			<span class="dot" aria-hidden="true"></span>
			<span><strong>LIVE</strong> • <?php echo esc_html( $live_text ); ?></span>
		</div>
	</div>
</div>
<?php endif;