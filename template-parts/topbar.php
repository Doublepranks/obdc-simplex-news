<?php
/**
 * Template part for displaying the top live ticker.
 *
 * @package ObDC-simplex-news
 */

$live_status    = get_theme_mod( 'obdc_simplex_news_live_status', 'on' );
$youtube_banner = function_exists( 'obdc_simplex_news_get_youtube_live_banner_data' )
	? obdc_simplex_news_get_youtube_live_banner_data()
	: array(
		'enabled'       => false,
		'live'          => false,
		'video_title'   => '',
		'video_url'     => '',
		'fallback_text' => '',
	);

if ( 'on' === $live_status ) :
	$is_youtube_enabled = ! empty( $youtube_banner['enabled'] );
	$has_live           = ! empty( $youtube_banner['live'] );
	$fallback_text      = ! empty( $youtube_banner['fallback_text'] )
		? $youtube_banner['fallback_text']
		: __( 'Um Brasil que pensa, comeca de cima.', 'obdc-simplex-news' );
	?>
	<div class="topbar" data-topbar>
		<div class="wrap">
			<?php if ( $is_youtube_enabled ) : ?>
				<?php if ( $has_live && ! empty( $youtube_banner['video_url'] ) ) : ?>
					<a
						class="topbar__ticker topbar__ticker--live"
						href="<?php echo esc_url( $youtube_banner['video_url'] ); ?>"
						target="_blank"
						rel="noopener noreferrer"
						aria-live="polite"
					>
						<span class="topbar__live-indicator">
							<span class="topbar__live-dot" aria-hidden="true"></span>
							<span class="topbar__live-label"><?php esc_html_e( 'AO VIVO', 'obdc-simplex-news' ); ?></span>
						</span>
						<span class="topbar__live-title" data-live-title>
							<?php echo esc_html( $youtube_banner['video_title'] ); ?>
						</span>
					</a>
				<?php else : ?>
					<div class="topbar__ticker topbar__ticker--fallback" aria-live="polite">
						<span class="topbar__fallback-text"><?php echo esc_html( $fallback_text ); ?></span>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="topbar__ticker topbar__ticker--fallback" aria-live="polite">
					<span class="topbar__fallback-text"><?php echo esc_html( $fallback_text ); ?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
