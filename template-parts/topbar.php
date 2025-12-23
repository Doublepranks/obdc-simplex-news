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
		: __( 'Um Brasil que pensa, começa de cima.', 'obdc-simplex-news' );
	?>
	<div class="topbar" data-topbar>
		<div class="wrap">
			<div class="topbar__inner">
				<?php if ( $is_youtube_enabled && $has_live && ! empty( $youtube_banner['video_url'] ) ) : ?>
					
					<div class="live-badge" title="<?php esc_attr_e( 'AO VIVO', 'obdc-simplex-news' ); ?>">
						<span class="live-dot"></span>
						<span><?php esc_html_e( 'AO VIVO', 'obdc-simplex-news' ); ?></span>
					</div>

					<div class="live-content">
						<?php echo esc_html( $youtube_banner['video_title'] ); ?>
					</div>

					<a href="<?php echo esc_url( $youtube_banner['video_url'] ); ?>" class="watch-btn" target="_blank" rel="noopener noreferrer">
						<span><?php esc_html_e( 'Assistir', 'obdc-simplex-news' ); ?></span>
					</a>

				<?php else : ?>
					
					<div class="live-content" style="text-align: center; width: 100%;">
						<?php echo esc_html( $fallback_text ); ?>
					</div>

				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
