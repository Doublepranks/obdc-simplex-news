<?php
/**
 * Template part for displaying a single author archive card.
 *
 * @package ObDC-simplex-news
 */

$author_related_category = obdc_simplex_news_get_first_category_name( get_the_ID() );

?>
<article class="single-related__card">
	<a href="<?php the_permalink(); ?>" class="single-related__image">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				'card',
				array(
					'alt'     => esc_attr( get_the_title() ),
					'loading' => 'lazy',
				)
			);
		} else {
			printf(
				'<div class="img-placeholder">%s</div>',
				esc_html__( 'Sem imagem', 'obdc-simplex-news' )
			);
		}
		?>
	</a>
	<div class="single-related__content">
		<?php if ( $author_related_category ) : ?>
			<p class="single-related__kicker"><?php echo esc_html( $author_related_category ); ?></p>
		<?php endif; ?>
		<h3 class="single-related__headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	</div>
</article>
