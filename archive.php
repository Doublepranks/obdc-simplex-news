<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ObDC-simplex-news
 */

get_header();

// Build REST endpoint URL with archive parameters
$feed_endpoint = rest_url('obdc-simplex-news/v1/archive-feed');
$feed_endpoint = wp_make_link_relative($feed_endpoint);

// Add archive-specific parameters
$query_params = array();

if (is_category()) {
	$query_params['cat'] = get_queried_object_id();
} elseif (is_tag()) {
	$query_params['tag_id'] = get_queried_object_id();
} elseif (is_date()) {
	if (is_year()) {
		$query_params['year'] = get_query_var('year');
	} elseif (is_month()) {
		$query_params['year'] = get_query_var('year');
		$query_params['monthnum'] = get_query_var('monthnum');
	} elseif (is_day()) {
		$query_params['year'] = get_query_var('year');
		$query_params['monthnum'] = get_query_var('monthnum');
		$query_params['day'] = get_query_var('day');
	}
}

if (!empty($query_params)) {
	$feed_endpoint .= '?' . http_build_query($query_params);
}

$rest_nonce = wp_create_nonce('wp_rest');
$load_more_text = __('Carregar mais', 'obdc-simplex-news');
$loading_text = __('Carregando…', 'obdc-simplex-news');
$auto_load_limit = apply_filters('obdc_simplex_news_archive_autoload_limit', 0); // Disabled by default for archives
?>

<main id="main" class="site-main">
	<div class="wrap">

		<?php if (have_posts()): ?>
			<header class="archive-header">
				<?php
				the_archive_title('<h1 class="archive-title">', '</h1>');
				the_archive_description('<div class="archive-description">', '</div>');
				?>
			</header>
		<?php endif; ?>

		<!-- Feed + sidebar -->
		<section class="content" aria-label="Últimas">
			<div class="feed" data-feed>
				<div class="feed__items" data-feed-items>
					<?php
					if (have_posts()):
						$obdc_post_counter = 0;
						while (have_posts()):
							the_post();
							$obdc_post_counter++;
							get_template_part('template-parts/content/card');

							// Inject an ad every 3 posts
							if (0 === $obdc_post_counter % 3) {
								get_template_part('template-parts/ads/in-feed');
							}
						endwhile;
					else:
						?>
						<p><?php esc_html_e('Nenhum conteúdo encontrado.', 'obdc-simplex-news'); ?></p>
						<?php
					endif;

					global $wp_query;
					$max_pages = max(1, (int) $wp_query->max_num_pages);
					?>
				</div>

				<?php
				$button_disabled = $max_pages <= 1;
				$button_classes = 'loadmore' . ($button_disabled ? ' is-disabled' : '');
				$button_attributes = $button_disabled ? ' disabled aria-disabled="true"' : ' aria-disabled="false"';
				?>

				<!-- Load more button -->
				<div class="feed__sentinel" data-feed-sentinel aria-hidden="true"></div>

				<button class="<?php echo esc_attr($button_classes); ?>" type="button"
					aria-label="<?php echo esc_attr($load_more_text); ?>"
					data-endpoint="<?php echo esc_url($feed_endpoint); ?>"
					data-nonce="<?php echo esc_attr($rest_nonce); ?>" data-current-page="1"
					data-max-pages="<?php echo esc_attr($max_pages); ?>"
					data-button-text="<?php echo esc_attr($load_more_text); ?>"
					data-loading-text="<?php echo esc_attr($loading_text); ?>"
					data-autoload-limit="<?php echo esc_attr($auto_load_limit); ?>" <?php echo $button_attributes; ?>>
					<?php echo esc_html($load_more_text); ?>
				</button>
			</div>

			<aside class="sidebar" aria-label="Mais lidas">
				<?php get_template_part('template-parts/sidebar/most-read'); ?>
			</aside>
		</section>

	</div><!-- .wrap -->

</main><!-- #main -->

<?php
get_footer();
