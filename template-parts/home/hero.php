<?php
/**
 * Template part for displaying the lead hero story.
 *
 * @package ObDC-simplex-news
 */

// For the hero, we want the latest post marked as 'Featured on Home'
$featured_args = array(
	'posts_per_page' => 1,
	'meta_key'       => '_featured_on_home',
	'meta_value'     => '1',
	'orderby'        => 'date',
	'order'          => 'DESC',
);

$featured_query = new WP_Query( $featured_args );

// If no featured post is found, get the latest post
if ( ! $featured_query->have_posts() ) {
	// Reset args to get the latest post
	$featured_args = array(
		'posts_per_page' => 1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);
	$featured_query = new WP_Query( $featured_args );
}

if ( $featured_query->have_posts() ) :
	$featured_query->the_post();
?>
<article class="hero-card" aria-labelledby="lead-title">
	<a href="<?php the_permalink(); ?>" class="media">
		<?php the_post_thumbnail( 'hero', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
	</a>
	<div class="body">
		<div class="kicker">
			<?php 
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				echo esc_html( $categories[0]->name );
			}
			?>
		</div>
		<h2 id="lead-title" class="title-xl"><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h2>
		<p class="excerpt-hero"> <?php echo wp_trim_words( get_the_excerpt(), 30 ); ?> </p>
		<p class="meta">Por <?php the_author(); ?> • <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?> atrás • <?php echo esc_html( get_post_meta( get_the_ID(), 'cidade', true ) ); ?></p>
	</div>
</article>
<?php
	wp_reset_postdata();
endif;
?>