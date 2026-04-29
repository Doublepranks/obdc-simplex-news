<?php
/**
 * Template part for displaying a standard post card in the feed.
 *
 * @package ObDC-simplex-news
 */



?>
<article class="card">
	<a href="<?php the_permalink(); ?>" class="thumb">
		<?php the_post_thumbnail('card', array('alt' => esc_attr(get_the_title()))); ?>
	</a>
	<div>
		<div class="kicker">
			<?php
			if (function_exists('obdc_simplex_news_get_first_category_name')) {
				$category_name = obdc_simplex_news_get_first_category_name(get_the_ID());
				if ($category_name) {
					echo esc_html($category_name);
				}
			}
			?>
		</div>
		<h3><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
		<p class="excerpt"> <?php echo wp_trim_words(get_the_excerpt(), 24); ?> </p>
		<p class="meta"> <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> atrás</p>
	</div>
</article>