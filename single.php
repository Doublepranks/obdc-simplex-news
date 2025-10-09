<?php
/**
 * Template for displaying single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

global $post;

get_header(); ?>

<main id="main" class="site-main">
	<div class="wrap single-layout">
	<?php while ( have_posts() ) :
		the_post();
		obdc_simplex_news_increment_post_views();
		$permalink_encoded = rawurlencode( get_permalink() );
		$title_encoded     = rawurlencode( html_entity_decode( get_the_title(), ENT_QUOTES, get_bloginfo( 'charset' ) ) );
		$share_links       = array(
			'instagram' => 'https://www.instagram.com/obrasildecima/?utm_source=share&utm_medium=web&url=' . $permalink_encoded,
			'x'         => sprintf( 'https://twitter.com/intent/tweet?url=%1$s&text=%2$s', $permalink_encoded, $title_encoded ),
			'facebook'  => sprintf( 'https://www.facebook.com/sharer/sharer.php?u=%s', $permalink_encoded ),
			'whatsapp'  => sprintf( 'https://api.whatsapp.com/send?text=%2$s%%20%1$s', $permalink_encoded, $title_encoded ),
			'linkedin'  => sprintf( 'https://www.linkedin.com/sharing/share-offsite/?url=%s', $permalink_encoded ),
			'substack'  => 'https://obrasildecima.substack.com/?utm_source=share&utm_medium=web&utm_campaign=site-share&url=' . $permalink_encoded,
		);
	?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article' ); ?>>
			<header class="single-hero">
				<?php $primary_category = obdc_simplex_news_get_first_category_name(); ?>
				<?php if ( $primary_category ) : ?>
					<p class="single-hero__label"><?php echo esc_html( $primary_category ); ?></p>
				<?php endif; ?>

				<h1 class="single-hero__title"><?php the_title(); ?></h1>
				<?php
					$single_excerpt = get_the_excerpt();
					if ( $single_excerpt ) :
				?>
					<p class="single-hero__excerpt"><?php echo wp_kses_post( $single_excerpt ); ?></p>
				<?php endif; ?>

				<div class="single-hero__meta">
					<div class="single-hero__meta-items">
						<span class="single-hero__meta-item"><?php esc_html_e( 'Por', 'obdc-simplex-news' ); ?> <strong><?php echo esc_html( get_the_author() ); ?></strong></span>
						<span class="single-hero__meta-sep">&bull;</span>
					<span class="single-hero__meta-item"><?php echo esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?> <?php esc_html_e( 'atrás', 'obdc-simplex-news' ); ?></span>
						<?php $reading_time = obdc_simplex_news_get_reading_time(); ?>
						<?php if ( $reading_time ) : ?>
							<span class="single-hero__meta-sep">&bull;</span>
							<span class="single-hero__meta-item"><?php echo esc_html( $reading_time ); ?></span>
						<?php endif; ?>
						<?php $city = get_post_meta( get_the_ID(), 'cidade', true ); ?>
						<?php if ( $city ) : ?>
							<span class="single-hero__meta-sep">&bull;</span>
							<span class="single-hero__meta-item"><?php echo esc_html( $city ); ?></span>
						<?php endif; ?>
					</div>

					<div class="single-share-inline" aria-label="<?php esc_attr_e( 'Compartilhar este conteúdo', 'obdc-simplex-news' ); ?>">
						<ul class="single-share-inline__list" role="list">
							<li><a class="single-share-inline__button" rel="noopener noreferrer" target="_blank" href="<?php echo esc_url( $share_links['x'] ); ?>"><span class="sr-only"><?php esc_html_e( 'Compartilhar no X', 'obdc-simplex-news' ); ?></span><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 5.5h3.3l5 6.4 4.4-6.4H21l-6.4 9.3 5.5 7.2h-3.3l-4.6-6-4.1 6H3l6.7-9.6z"/></svg></a></li>
							<li><a class="single-share-inline__button" rel="noopener noreferrer" target="_blank" href="<?php echo esc_url( $share_links['instagram'] ); ?>"><span class="sr-only"><?php esc_html_e( 'Abrir Instagram', 'obdc-simplex-news' ); ?></span><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zm0 2a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm6.25-.88a1.12 1.12 0 1 1-2.24 0 1.12 1.12 0 0 1 2.24 0z"/></svg></a></li>
							<li><a class="single-share-inline__button" rel="noopener noreferrer" target="_blank" href="<?php echo esc_url( $share_links['facebook'] ); ?>"><span class="sr-only"><?php esc_html_e( 'Compartilhar no Facebook', 'obdc-simplex-news' ); ?></span><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 22H9v-8H6v-4h3V6.7C9 4 10.6 2 13.9 2H18v4h-2.7c-1 0-1.3.4-1.3 1.2V10h4l-.6 4h-3.4z"/></svg></a></li>
							<li><a class="single-share-inline__button" rel="noopener noreferrer" target="_blank" href="<?php echo esc_url( $share_links['whatsapp'] ); ?>"><span class="sr-only"><?php esc_html_e( 'Compartilhar no WhatsApp', 'obdc-simplex-news' ); ?></span><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 2.118.553 4.154 1.602 5.957L0 24l6.267-1.643A11.94 11.94 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0Zm5.746 17.266c-.246.7-1.454 1.29-2.016 1.377-.516.08-1.187.113-1.918-.12-.441-.14-1.004-.327-1.733-.64-3.056-1.306-5.05-4.333-5.2-4.536-.153-.204-1.244-1.652-1.244-3.155 0-1.504.79-2.24 1.07-2.55.246-.27.54-.34.72-.34.18 0 .36.003.517.01.165.007.389-.062.61.467.246.59.84 2.045.915 2.19.073.145.12.316.02.51-.096.203-.146.316-.292.486-.146.17-.31.38-.442.51-.146.146-.298.305-.128.595.17.29.755 1.24 1.622 2.005 1.115.996 2.056 1.304 2.346 1.45.29.145.456.122.62-.073.165-.195.71-.827.902-1.112.19-.284.38-.238.63-.145.246.086 1.558.735 1.825.868.27.133.45.2.52.31.073.11.073.7-.173 1.403Z"/></svg></a></li>
							<li><a class="single-share-inline__button" rel="noopener noreferrer" target="_blank" href="<?php echo esc_url( $share_links['linkedin'] ); ?>"><span class="sr-only"><?php esc_html_e( 'Compartilhar no LinkedIn', 'obdc-simplex-news' ); ?></span><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5A2.5 2.5 0 1 1 0 3.5a2.5 2.5 0 0 1 4.98 0zM0 8.82h4.95V24H0zm7.98 0H12v2.1h.05c.6-1.1 2-2.2 4.1-2.2 3.9 0 4.9 2.5 4.9 5.8V24h-4.95v-5.6c0-1.3 0-3-1.9-3s-2.2 1.4-2.2 2.9V24H7.98z"/></svg></a></li>
							<li><a class="single-share-inline__button" rel="noopener noreferrer" target="_blank" href="<?php echo esc_url( $share_links['substack'] ); ?>"><span class="sr-only"><?php esc_html_e( 'Abrir Substack', 'obdc-simplex-news' ); ?></span><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4h18v3H3V4zm0 5h18v12l-9-3-9 3V9z"/></svg></a></li>
						</ul>
					</div>
				</div>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="single-hero__media">
						<?php
						$thumbnail_id          = get_post_thumbnail_id();
						$thumbnail_caption     = $thumbnail_id ? wp_get_attachment_caption( $thumbnail_id ) : '';
						$thumbnail_description = '';
						if ( $thumbnail_id ) {
							$attachment_post = get_post( $thumbnail_id );
							if ( $attachment_post && ! empty( $attachment_post->post_content ) ) {
								$thumbnail_description = $attachment_post->post_content;
							}
						}
						$featured_caption = $thumbnail_caption ? $thumbnail_caption : $thumbnail_description;
						$featured_alt     = $thumbnail_id ? get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) : '';
						if ( empty( $featured_alt ) ) {
							$featured_alt = get_the_title();
						}
						echo wp_get_attachment_image(
							$thumbnail_id,
							'hero',
							false,
							array(
								'class' => 'single-hero__image',
								'alt'   => esc_attr( $featured_alt ),
							)
						);
						if ( $featured_caption ) :
						?>
						<figcaption class="single-hero__caption"><?php echo wp_kses_post( $featured_caption ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>
			</header>

			<div class="single-content entry-content">
				<?php the_content(); ?>
			</div>

			<section class="single-cta" aria-label="<?php esc_attr_e( 'Assine nossa newsletter', 'obdc-simplex-news' ); ?>">
				<div>
					<h2 class="single-cta__title"><?php esc_html_e( 'Receba alertas das principais notícias', 'obdc-simplex-news' ); ?></h2>
					<p class="single-cta__text"><?php esc_html_e( 'Assine nossa newsletter e acompanhe cada desdobramento em primeira mão.', 'obdc-simplex-news' ); ?></p>
				</div>
				<a class="single-cta__button" href="<?php echo esc_url( home_url( '/newsletter' ) ); ?>"><?php esc_html_e( 'Assinar agora', 'obdc-simplex-news' ); ?></a>
			</section>

			<section class="single-author" aria-label="<?php esc_attr_e( 'Sobre o autor', 'obdc-simplex-news' ); ?>">
				<div class="single-author__avatar"><?php echo get_avatar( get_the_author_meta( 'ID' ), 80, '', get_the_author() ); ?></div>
				<div>
					<p class="single-author__label"><?php esc_html_e( 'Autor', 'obdc-simplex-news' ); ?></p>
					<h3 class="single-author__name"><?php echo esc_html( get_the_author() ); ?></h3>
					<?php
					$author_bio = get_the_author_meta( 'description' );
					if ( $author_bio ) {
						echo '<p class="single-author__bio">' . esc_html( $author_bio ) . '</p>';
					} else {
						echo '<p class="single-author__bio">' . esc_html__( 'Jornalista responsável pela cobertura deste tema.', 'obdc-simplex-news' ) . '</p>';
					}
					?>
				</div>
			</section>


			<?php $post_tags = get_the_tags(); ?>
			<?php if ( $post_tags ) : ?>
			<section class="single-tags sr-only" aria-label="<?php esc_attr_e( 'Tags', 'obdc-simplex-news' ); ?>">
				<h2 class="single-tags__title"><?php esc_html_e( 'Tags', 'obdc-simplex-news' ); ?></h2>
				<ul class="single-tags__list">
				<?php foreach ( $post_tags as $tag ) : ?>
					<li><a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a></li>
				<?php endforeach; ?>
				</ul>
			</section>
			<?php endif; ?>


			<?php
			$related_args = array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => 3,
				'post__not_in'        => array( get_the_ID() ),
				'ignore_sticky_posts' => 1,
				'orderby'             => 'date',
			);

			$categories = wp_get_post_categories( get_the_ID() );
			if ( ! empty( $categories ) ) {
				$related_args['category__in'] = $categories;
			}

			$related_query = new WP_Query( $related_args );
			if ( $related_query->have_posts() ) :
			?>
			<section class="single-related" aria-label="<?php esc_attr_e( 'Conteúdo relacionado', 'obdc-simplex-news' ); ?>">
				<div class="single-related__header">
				<h2 class="single-related__title"><?php esc_html_e( 'Você pode gostar também', 'obdc-simplex-news' ); ?></h2>
					<a class="single-related__more" href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php esc_html_e( 'Ver mais', 'obdc-simplex-news' ); ?></a>
				</div>
				<div class="single-related__grid">
				<?php
				while ( $related_query->have_posts() ) :
					$related_query->the_post();
					$related_id = get_the_ID();
					$related_category = obdc_simplex_news_get_first_category_name( $related_id );
				?>
					<article class="single-related__card">
						<a href="<?php the_permalink(); ?>" class="single-related__image">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'card', array( 'alt' => esc_attr( get_the_title() ) ) );
						} else {
							echo '<div class="img-placeholder">' . esc_html__( 'Sem imagem', 'obdc-simplex-news' ) . '</div>';
						}
						?>
						</a>
						<div class="single-related__content">
						<?php if ( $related_category ) : ?>
							<p class="single-related__kicker"><?php echo esc_html( $related_category ); ?></p>
						<?php endif; ?>
							<h3 class="single-related__headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						</div>
					</article>
				<?php endwhile; ?>
				</div>
			</section>
			<?php endif;
			wp_reset_postdata();
			?>
		</article>

		<?php
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		?>
	<?php endwhile; ?>
	</div><!-- .wrap -->
</main><!-- #main -->

<?php get_footer(); ?>






















