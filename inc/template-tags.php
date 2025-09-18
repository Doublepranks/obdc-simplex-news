<?php
/**
 * Funções personalizadas para este tema.
 *
 * @package ObDC-simplex-news
 */

if ( ! function_exists( 'obdc_simplex_news_posted_on' ) ) :
	/**
	 * Imprime o HTML para o meta de data/hora de publicação.
	 */
	function obdc_simplex_news_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		printf(
			'<span class="posted-on">Publicado em %1$s</span>',
			$time_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
endif;

if ( ! function_exists( 'obdc_simplex_news_posted_by' ) ) :
	/**
	 * Imprime o HTML para o autor do post.
	 */
	function obdc_simplex_news_posted_by() {
		printf(
			'<span class="byline"> por <span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}
endif;

/**
 * Determina se o post tem mais de uma categoria.
 *
 * @return bool
 */
function obdc_simplex_news_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'obdc_simplex_news_categories' ) ) ) {
		// Cria uma consulta para verificar categorias.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// Precisamos saber se há mais de uma categoria.
			'number'     => 2,
		) );

		// Conta o número de categorias que estão publicadas.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'obdc_simplex_news_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// Este blog tem mais de uma categoria, então obdc_simplex_news_categorized_blog deve retornar true.
		return true;
	} else {
		// Este blog tem apenas uma categoria, então obdc_simplex_news_categorized_blog deve retornar false.
		return false;
	}
}

/**
 * Limpa o transiente de categorias usado em obdc_simplex_news_categorized_blog.
 */
function obdc_simplex_news_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Como estamos salvando, podemos limpar o transiente.
	delete_transient( 'obdc_simplex_news_categories' );
}
add_action( 'edit_category', 'obdc_simplex_news_category_transient_flusher' );
add_action( 'save_post', 'obdc_simplex_news_category_transient_flusher' );