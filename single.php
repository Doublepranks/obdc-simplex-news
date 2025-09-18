<?php
/**
 * The Template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

get_header(); ?>

	<div class="wrap">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_category(); ?>
				<h1 class="entry-title"> <?php the_title(); ?> </h1>
				<div class="meta">
					Por <?php the_author(); ?> • 
					<?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ); ?> atrás • 
					<?php echo esc_html( get_post_meta( get_the_ID(), 'cidade', true ) ); ?>
				</div>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php the_post_thumbnail( 'hero' ); ?>
				<?php the_content(); ?>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php // Add tags, share buttons, etc. ?>
			</footer><!-- .entry-footer -->
		</article><!-- #post-<?php the_ID(); ?> -->

		<!-- Comments -->
		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		?>

	</div>

<?php get_footer(); ?>