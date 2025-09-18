<?php
/**
 * Template part for displaying the secondary highlights (two cards).
 *
 * @package ObDC-simplex-news
 */

// Get the ID of the featured post if it exists
$featured_post_id = '';
$featured_query = new WP_Query( array(
	'posts_per_page' => 1,
	'meta_key'       => '_featured_on_home',
	'meta_value'     => '1',
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

if ( $featured_query->have_posts() ) {
	$featured_query->the_post();
	$featured_post_id = get_the_ID();
	wp_reset_postdata();
}

// Get the next two latest posts after the featured one
$secondary_query = new WP_Query( array(
	'posts_per_page' => 2,
	'post__not_in'   => array( $featured_post_id ),
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
) );

if ( $secondary_query->have_posts() ) :
	while ( $secondary_query->have_posts() ) : $secondary_query->the_post();
?>
<article class="hero-card">
	<a href="<?php the_permalink(); ?>" class="media">
		<?php the_post_thumbnail( 'card', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
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
		<h3 class="title-md"><a href="<?php the_permalink(); ?>"> <?php the_title(); ?></a></h3>
		<p class="meta"> <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?> atrás </p>
	</div>
</article>
<?php
	endwhile;
	wp_reset_postdata();
endif;