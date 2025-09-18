<?php
/**
 * Arquivo de compatibilidade com Jetpack para o tema ObDC-simplex-news.
 *
 * @package ObDC-simplex-news
 * @subpackage Inc
 * @since 1.0.0
 */

/**
 * Esta função existe apenas para evitar erros fatais caso o plugin Jetpack esteja ativo.
 * O tema ObDC-simplex-news não requer funcionalidades específicas do Jetpack no momento.
 * Se você quiser integrar recursos do Jetpack (como Infinite Scroll), pode configurá-los aqui.
 */

// Exemplo de como ativar o Infinite Scroll (se desejado):
/*
function obdc_simplex_news_jetpack_setup() {
	add_theme_support(
		'infinite-scroll',
		array(
			'container' => 'main',
			'render'    => 'obdc_simplex_news_infinite_scroll_render',
			'footer'    => 'page',
		)
	);
}
add_action( 'after_setup_theme', 'obdc_simplex_news_jetpack_setup' );

function obdc_simplex_news_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
			get_template_part( 'template-parts/content', 'search' );
		else :
			get_template_part( 'template-parts/content', get_post_type() );
		endif;
	}
}
*/