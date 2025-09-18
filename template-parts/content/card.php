<?php
/**
 * Template part for displaying a standard post card in the feed.
 *
 * @package ObDC-simplex-news
 */

// Check if this is the 3rd post to insert an inline ad
$global_post_count = $GLOBALS['wp_query']->current_post + 1;
$inline_ad_frequency = apply_filters( 'obdc_simplex_news_inline_ad_frequency', 3 ); // Default every 3 posts

if ( $global_post_count > 0 && $global_post_count % $inline_ad_frequency === 0 ) :
	?>
	<div class="ad" aria-label="Espaço publicitário">
		AD SLOT — feed_inline (inserido automaticamente a cada N cards)
	</div>
	<?php
endif;

?>
<article class="card">
	<a href="<?php the_permalink(); ?>" class="thumb">
		<?php the_post_thumbnail( 'card', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
	</a>
	<div>
		<div class="kicker">
			<?php 
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				echo esc_html( $categories[0]->name );
			}
			?>
		</div>
		<h3><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
		<p class="excerpt"> <?php echo wp_trim_words( get_the_excerpt(), 24 ); ?> </p>
		<p class="meta"> <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?> atrás</p>
	</div>
</article>