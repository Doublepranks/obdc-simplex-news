<?php
/**
 * Template part for displaying the 'Most Read' sidebar widget.
 *
 * @package ObDC-simplex-news
 */

// Prevent overlap with hero/highlight posts when rendered on the front page.
$excluded_featured_ids = array();

if ( is_front_page() ) {
        $featured_data = get_query_var( 'obdc_featured_data' );

        if ( empty( $featured_data ) && function_exists( 'obdc_simplex_news_get_front_page_featured_data' ) ) {
                $featured_data = obdc_simplex_news_get_front_page_featured_data();
        }

        if ( ! empty( $featured_data['excluded_ids'] ) ) {
                $excluded_featured_ids = array_map( 'intval', (array) $featured_data['excluded_ids'] );
        }
}

// Get most viewed posts (requires plugin like 'Post Views Counter' or custom table)
// Using meta_key 'post_views' as specified in ObDC documentation
$most_query_args = array(
        'posts_per_page' => 6,
        'meta_key'       => 'post_views',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'post_status'    => 'publish',
);

if ( ! empty( $excluded_featured_ids ) ) {
        $most_query_args['post__not_in'] = $excluded_featured_ids;
}

$most = new WP_Query( $most_query_args );

if ( $most->have_posts() ) : ?>
	<div class="box">
		<h4>Mais lidas</h4>
		<div class="top-list">
			<?php while ( $most->have_posts() ) : $most->the_post(); ?>
				<a class="top-item" href="<?php the_permalink(); ?>" aria-label="Leia: <?php the_title_attribute(); ?>">
					<div class="top-thumb">
						<?php the_post_thumbnail( 'thumb72', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
					</div>
					<div class="content">
						<div class="kicker">
							<?php 
							$categories = get_the_category();
							if ( ! empty( $categories ) ) {
								echo esc_html( $categories[0]->name );
							}
							?>
						</div>
						<h5><?php the_title(); ?></h5>
					</div>
				</a>
			<?php endwhile; ?>
		</div>
	</div>
<?php 
	wp_reset_postdata();
else :
	// Fallback if no posts found
	?>
	<div class="box">
		<h4>Mais lidas</h4>
		<p class="no-posts">Nenhuma postagem encontrada.</p>
	</div>
	<?php
endif;
