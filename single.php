<?php
/**
 * Template for displaying single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

global $post;

get_header(); ?>

<?php
// Ajusta a altura mínima dos iframes do plugin BDC Safe Embed nas singles
if (function_exists('add_filter')) {
    add_filter('bdcse_options', function($opts){
        // Defina aqui a altura mínima desejada para embeds na single
        $opts['iframe_min_height'] = 480;
        return $opts;
    });
}
?>

<main id="main" class="site-main">
	<div class="wrap single-layout">
	<?php while ( have_posts() ) :
		the_post();
		obdc_simplex_news_increment_post_views();
		$share_data      = obdc_simplex_news_get_share_data( get_the_ID() );
		$share_links     = isset( $share_data['urls'] ) && is_array( $share_data['urls'] ) ? $share_data['urls'] : array();
		$share_copy_text = isset( $share_data['share_text'] ) ? $share_data['share_text'] : '';
		$share_title     = isset( $share_data['title'] ) ? $share_data['title'] : '';
		$share_permalink = isset( $share_data['permalink'] ) ? $share_data['permalink'] : '';
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
					<?php
					$date_format = get_option( 'date_format' );
					$time_format = get_option( 'time_format' );

					if ( empty( $date_format ) ) {
						$date_format = 'd/m/Y';
					}

					if ( empty( $time_format ) ) {
						$time_format = 'H:i';
					}

					$meta_items       = array();
					$author_name      = get_the_author();
					$author_url       = get_author_posts_url( get_the_author_meta( 'ID' ) );
					$published_parts  = array_filter(
						array(
							get_the_time( $date_format ),
							get_the_time( $time_format ),
						)
					);
					$published_text   = implode( ' ', $published_parts );
					$published_attr   = get_the_time( DATE_W3C );
					$modified_parts   = array_filter(
						array(
							get_the_modified_time( $date_format ),
							get_the_modified_time( $time_format ),
						)
					);
					$modified_text    = implode( ' ', $modified_parts );
					$modified_attr    = get_the_modified_time( DATE_W3C );
					$has_been_updated = get_the_modified_time( 'U' ) && get_the_modified_time( 'U' ) !== get_the_time( 'U' );
					$city             = get_post_meta( get_the_ID(), 'cidade', true );

					if ( $author_name ) {
						$meta_items[] = sprintf(
							'%s <a class="single-hero__author" href="%s" rel="author"><strong>%s</strong></a>',
							esc_html__( 'Por', 'obdc-simplex-news' ),
							esc_url( $author_url ),
							esc_html( $author_name )
						);
					}

					if ( $published_text ) {
						$meta_items[] = sprintf(
							'<time datetime="%s">%s</time>',
							esc_attr( $published_attr ),
							esc_html( $published_text )
						);
					}

					if ( $has_been_updated && $modified_text ) {
						$meta_items[] = sprintf(
							'%s <time datetime="%s">%s</time>',
							esc_html__( 'Atualizado em', 'obdc-simplex-news' ),
							esc_attr( $modified_attr ),
							esc_html( $modified_text )
						);
					}

					if ( $city ) {
						$meta_items[] = esc_html( $city );
					}

					if ( ! empty( $meta_items ) ) :
					?>
					<div class="single-hero__meta-items">
						<?php foreach ( $meta_items as $index => $meta_item ) : ?>
							<?php if ( $index > 0 ) : ?>
								<span class="single-hero__meta-sep">&bull;</span>
							<?php endif; ?>
							<span class="single-hero__meta-item"><?php echo wp_kses_post( $meta_item ); ?></span>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

				<div class="single-share-inline" aria-label="<?php esc_attr_e( 'Compartilhar este conteúdo', 'obdc-simplex-news' ); ?>">
						<?php
						$share_items = array(
							'share'     => array(
								'label' => __( 'Compartilhar', 'obdc-simplex-news' ),
								'icon'  => obdc_simplex_news_get_social_icon_svg( 'share' ),
								'class' => 'single-share-inline__button single-share-inline__button--native-share',
							),
							'x'         => array(
								'label' => __( 'Compartilhar no X', 'obdc-simplex-news' ),
								'icon'  => obdc_simplex_news_get_social_icon_svg( 'x' ),
							),
							'instagram' => array(
								'label' => __( 'Copiar link para usar no Instagram (desktop) ou abrir o aplicativo (mobile)', 'obdc-simplex-news' ),
								'icon'  => obdc_simplex_news_get_social_icon_svg( 'instagram' ),
								'class' => 'single-share-inline__button single-share-inline__button--instagram',
							),
							'facebook'  => array(
								'label' => __( 'Compartilhar no Facebook', 'obdc-simplex-news' ),
								'icon'  => obdc_simplex_news_get_social_icon_svg( 'facebook' ),
							),
							'whatsapp'  => array(
								'label' => __( 'Compartilhar no WhatsApp', 'obdc-simplex-news' ),
								'icon'  => obdc_simplex_news_get_social_icon_svg( 'whatsapp' ),
							),
							'linkedin'  => array(
								'label' => __( 'Compartilhar no LinkedIn', 'obdc-simplex-news' ),
								'icon'  => obdc_simplex_news_get_social_icon_svg( 'linkedin' ),
							),
						);
						?>
						<ul class="single-share-inline__list" role="list">
						<?php foreach ( $share_items as $network => $item ) : ?>
							<?php
							$url = isset( $share_links[ $network ] ) ? $share_links[ $network ] : '';
							if ( empty( $url ) && ! in_array( $network, array( 'instagram', 'share' ), true ) ) {
								continue;
							}

							$button_classes = 'single-share-inline__button';
							if ( ! empty( $item['class'] ) ) {
								$button_classes = $item['class'];
							}

							$icon  = isset( $item['icon'] ) ? $item['icon'] : '';
							$label = isset( $item['label'] ) ? $item['label'] : '';

							$href = '#';
							if ( ! empty( $url ) ) {
								$href = ( 'instagram' === $network ) ? esc_attr( $url ) : esc_url( $url );
							}

							$extra_attributes = '';
							if ( 'share' === $network ) {
								if ( ! empty( $share_title ) ) {
									$extra_attributes .= ' data-share-title="' . esc_attr( $share_title ) . '"';
								}
								if ( ! empty( $share_copy_text ) ) {
									$extra_attributes .= ' data-share-text="' . esc_attr( $share_copy_text ) . '"';
								}
								if ( ! empty( $share_permalink ) ) {
									$extra_attributes .= ' data-share-url="' . esc_attr( $share_permalink ) . '"';
								}
								$extra_attributes .= ' data-share-success="' . esc_attr__( 'Link pronto para compartilhar.', 'obdc-simplex-news' ) . '"';
								$extra_attributes .= ' data-share-error="' . esc_attr__( 'Não foi possível compartilhar automaticamente. Use o link copiado.', 'obdc-simplex-news' ) . '"';
							}
							if ( 'instagram' === $network ) {
								if ( ! empty( $share_copy_text ) ) {
									$extra_attributes .= ' data-share-text="' . esc_attr( $share_copy_text ) . '"';
								}
								$extra_attributes .= ' data-copy-success="' . esc_attr__( 'Link copiado para a área de transferência.', 'obdc-simplex-news' ) . '"';
								$extra_attributes .= ' data-copy-error="' . esc_attr__( 'Não foi possível copiar automaticamente. Use o link manualmente.', 'obdc-simplex-news' ) . '"';
							}
							?>
							<li>
								<a class="<?php echo esc_attr( $button_classes ); ?>" rel="noopener noreferrer" target="_blank" href="<?php echo $href; ?>" aria-label="<?php echo esc_attr( $label ); ?>"<?php echo $extra_attributes; ?>>
									<span class="sr-only"><?php echo esc_html( $label ); ?></span>
									<?php echo $icon; ?>
								</a>
							</li>
						<?php endforeach; ?>
						</ul>
						<div class="single-share-inline__feedback sr-only" role="status" aria-live="polite"></div>
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
				<div class="single-author__avatar"><?php echo '<a class="single-author__avatar-link" href="' . esc_url( $author_url ) . '" rel="author">' . get_avatar( get_the_author_meta( 'ID' ), 80, '', get_the_author() ) . '</a>'; ?></div>
				<div>
					<p class="single-author__label"><?php esc_html_e( 'Autor', 'obdc-simplex-news' ); ?></p>
					<h3 class="single-author__name"><?php echo '<a class="single-author__name-link" href="' . esc_url( $author_url ) . '" rel="author">' . esc_html( get_the_author() ) . '</a>'; ?></h3>
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






















