<?php
/**
 * Template for author archive pages.
 *
 * @package ObDC-simplex-news
 */

$author = get_queried_object();

if ( ! ( $author instanceof WP_User ) ) {
	return;
}

get_header();

$author_id    = $author->ID;
$author_name  = $author->display_name ? $author->display_name : __( 'Autor', 'obdc-simplex-news' );
$author_bio   = get_the_author_meta( 'description', $author_id );
$author_title = get_user_meta( $author_id, 'obdc_title', true );
$author_city  = get_user_meta( $author_id, 'obdc_city', true );

$global_query       = $wp_query;
$max_pages          = isset( $global_query->max_num_pages ) ? max( 1, (int) $global_query->max_num_pages ) : 1;
$author_feed_nonce  = wp_create_nonce( 'wp_rest' );
$load_more_text     = __( 'Carregar mais', 'obdc-simplex-news' );
$loading_text       = __( 'Carregando...', 'obdc-simplex-news' );
$auto_load_limit    = apply_filters( 'obdc_simplex_news_author_autoload_limit', 5);
$auto_load_limit    = max( 0, absint( $auto_load_limit ) );
$author_feed_route  = rest_url( 'obdc-simplex-news/v1/author-feed' );
$author_feed_params = array(
	'author' => $author_id,
);
$author_feed_endpoint = add_query_arg( $author_feed_params, $author_feed_route );

$meta_fallbacks = array(
	'author_title' => array( 'title', 'job_title' ),
	'author_city'  => array( 'location', 'cidade' ),
);

foreach ( $meta_fallbacks['author_title'] as $title_key ) {
	if ( ! $author_title ) {
		$author_title = get_user_meta( $author_id, $title_key, true );
	}
}

foreach ( $meta_fallbacks['author_city'] as $city_key ) {
	if ( ! $author_city ) {
		$author_city = get_user_meta( $author_id, $city_key, true );
	}
}

$social_networks = array(
	'x'         => array(
		'keys'  => array( 'user_twitter', 'twitter', 'x' ),
		'label' => __( 'X (Twitter)', 'obdc-simplex-news' ),
	),
	'instagram' => array(
		'keys'  => array( 'user_instagram', 'instagram' ),
		'label' => __( 'Instagram', 'obdc-simplex-news' ),
	),
	'facebook'  => array(
		'keys'  => array( 'user_facebook', 'facebook' ),
		'label' => __( 'Facebook', 'obdc-simplex-news' ),
	),
	'linkedin'  => array(
		'keys'  => array( 'user_linkedin', 'linkedin' ),
		'label' => __( 'LinkedIn', 'obdc-simplex-news' ),
	),
	'substack'  => array(
		'keys'  => array( 'user_substack', 'substack' ),
		'label' => __( 'Substack', 'obdc-simplex-news' ),
	),
);

$author_social_links = array();

foreach ( $social_networks as $network_slug => $network_data ) {
	$sanitized_url = '';

	foreach ( $network_data['keys'] as $meta_key ) {
		$value = get_user_meta( $author_id, $meta_key, true );

		if ( ! empty( $value ) ) {
			$candidate_url = esc_url( $value );

			if ( $candidate_url ) {
				$sanitized_url = $candidate_url;
				break;
			}
		}
	}

	if ( $sanitized_url ) {
		$author_social_links[ $network_slug ] = array(
			'url'   => $sanitized_url,
			'label' => $network_data['label'],
		);
	}
}
?>

<main id="main" class="site-main">
	<div class="wrap author-archive">
		<header class="author-hero" aria-labelledby="author-title">
			<div class="author-hero__avatar">
				<?php
				echo get_avatar(
					$author_id,
					160,
					'',
					$author_name,
					array(
						'class'   => 'author-hero__avatar-img',
						'loading' => 'lazy',
					)
				);
				?>
			</div>
			<div class="author-hero__content">
				<p class="author-hero__label"><?php esc_html_e( 'Autor', 'obdc-simplex-news' ); ?></p>
				<h1 id="author-title" class="author-hero__name"><?php echo esc_html( $author_name ); ?></h1>
				<?php if ( $author_title || $author_city ) : ?>
				<ul class="author-hero__meta" role="list">
					<?php if ( $author_title ) : ?>
						<li class="author-hero__meta-item"><?php echo esc_html( $author_title ); ?></li>
					<?php endif; ?>
					<?php if ( $author_city ) : ?>
						<li class="author-hero__meta-item"><?php echo esc_html( $author_city ); ?></li>
					<?php endif; ?>
				</ul>
				<?php endif; ?>
				<?php if ( $author_bio ) : ?>
				<div class="author-hero__bio">
					<?php echo wp_kses_post( wpautop( $author_bio ) ); ?>
				</div>
				<?php else : ?>
				<p class="author-hero__bio"><?php esc_html_e( 'Jornalista responsavel pela cobertura de temas-chave no Brasil de Cima.', 'obdc-simplex-news' ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $author_social_links ) ) : ?>
				<nav class="author-hero__social" aria-label="<?php esc_attr_e( 'Redes sociais do autor', 'obdc-simplex-news' ); ?>">
					<ul class="author-hero__social-list" role="list">
					<?php
					foreach ( $author_social_links as $network_slug => $link_data ) :
						$icon_markup = '';

						switch ( $network_slug ) {
							case 'x':
								$icon_markup = '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 5.5h3.3l5 6.4 4.4-6.4H21l-6.4 9.3 5.5 7.2h-3.3l-4.6-6-4.1 6H3l6.7-9.6z"/></svg>';
								break;
							case 'instagram':
								$icon_markup = '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zm0 2a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm6.25-.88a1.12 1.12 0 1 1-2.24 0 1.12 1.12 0 0 1 2.24 0z"/></svg>';
								break;
							case 'facebook':
								$icon_markup = '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 22H9v-8H6v-4h3V6.7C9 4 10.6 2 13.9 2H18v4h-2.7c-1 0-1.3.4-1.3 1.2V10h4l-.6 4h-3.4z"/></svg>';
								break;
							case 'linkedin':
								$icon_markup = '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5A2.5 2.5 0 1 1 0 3.5a2.5 2.5 0 0 1 4.98 0zM0 8.82h4.95V24H0zm7.98 0H12v2.1h.05c.6-1.1 2-2.2 4.1-2.2 3.9 0 4.9 2.5 4.9 5.8V24h-4.95v-5.6c0-1.3 0-3-1.9-3s-2.2 1.4-2.2 2.9V24H7.98z"/></svg>';
								break;
							case 'substack':
								$icon_markup = '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4h18v3H3V4zm0 5h18v12l-9-3-9 3V9z"/></svg>';
								break;
						}
					?>
						<li>
							<a class="author-hero__social-link" href="<?php echo $link_data['url']; ?>" rel="me noopener noreferrer" target="_blank">
								<span class="sr-only"><?php echo esc_html( $link_data['label'] ); ?></span>
								<?php echo $icon_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</li>
					<?php endforeach; ?>
					</ul>
				</nav>
				<?php endif; ?>
			</div>
		</header>

		<section class="author-ad" aria-label="<?php esc_attr_e( 'Publicidade', 'obdc-simplex-news' ); ?>">
			<?php get_template_part( 'template-parts/ads/top-home' ); ?>
		</section>

		<section class="author-feed" aria-label="<?php esc_attr_e( 'Publicacoes do autor', 'obdc-simplex-news' ); ?>">
			<h2 class="author-feed__title"><?php esc_html_e( 'Posts recentes', 'obdc-simplex-news' ); ?></h2>

			<?php if ( have_posts() ) : ?>
				<div class="feed" data-feed>
					<div class="author-feed__grid single-related__grid" data-feed-items>
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/author/card' );
						endwhile;
						?>
					</div>

					<?php
					$button_disabled   = $max_pages <= 1;
					$button_classes    = 'loadmore' . ( $button_disabled ? ' is-disabled' : '' );
					$button_attributes = $button_disabled ? ' disabled aria-disabled="true"' : ' aria-disabled="false"';
					?>

					<div class="feed__sentinel" data-feed-sentinel aria-hidden="true"></div>

					<button
						class="<?php echo esc_attr( $button_classes ); ?>"
						type="button"
						aria-label="<?php echo esc_attr( $load_more_text ); ?>"
						data-endpoint="<?php echo esc_url( $author_feed_endpoint ); ?>"
						data-nonce="<?php echo esc_attr( $author_feed_nonce ); ?>"
						data-current-page="1"
						data-max-pages="<?php echo esc_attr( $max_pages ); ?>"
						data-button-text="<?php echo esc_attr( $load_more_text ); ?>"
						data-loading-text="<?php echo esc_attr( $loading_text ); ?>"
						data-autoload-limit="<?php echo esc_attr( $auto_load_limit ); ?>"
						<?php echo $button_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					>
						<?php echo esc_html( $load_more_text ); ?>
					</button>
				</div>
				<noscript>
					<?php
					the_posts_pagination(
						array(
							'prev_text'          => esc_html__( 'Pagina anterior', 'obdc-simplex-news' ),
							'next_text'          => esc_html__( 'Proxima pagina', 'obdc-simplex-news' ),
							'screen_reader_text' => esc_html__( 'Navegacao de paginas do autor', 'obdc-simplex-news' ),
						)
					);
					?>
				</noscript>
			<?php else : ?>
				<p class="author-feed__empty"><?php esc_html_e( 'Ainda nao ha publicacoes deste autor.', 'obdc-simplex-news' ); ?></p>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php
get_footer();
