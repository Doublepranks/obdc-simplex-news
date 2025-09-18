<?php
/**
 * Funções que melhoram o tema ao se conectar ao WordPress.
 *
 * @package ObDC-simplex-news
 */

/**
 * Adiciona classes personalizadas ao array de classes do body.
 *
 * @param array $classes Classes para o elemento body.
 * @return array
 */
function obdc_simplex_news_body_classes( $classes ) {
	// Adiciona uma classe se a barra lateral estiver ativa.
	if ( is_active_sidebar( 'sidebar-1' ) && ! is_page() ) {
		$classes[] = 'has-sidebar';
	}

	// Adiciona uma classe para telas pequenas.
	if ( wp_is_mobile() ) {
		$classes[] = 'mobile';
	}

	return $classes;
}
add_filter( 'body_class', 'obdc_simplex_news_body_classes' );

/**
 * Adiciona um `aria-label` aos links de paginação.
 *
 * @param string $attributes Os atributos HTML do link.
 * @return string Os atributos HTML modificados.
 */
function obdc_simplex_news_pagination_aria_label( $attributes ) {
	return str_replace( '<a href=', '<a aria-label="' . esc_attr__( 'Página', 'obdc-simplex-news' ) . ' ', $attributes );
}
add_filter( 'paginate_links', 'obdc_simplex_news_pagination_aria_label' );

/**
 * Corrige o foco do pular para o conteúdo com links de âncora suave.
 */
function obdc_simplex_news_skip_link_focus_fix() {
	// O script está embutido diretamente aqui para garantir que funcione mesmo sem JS externo.
	echo "<script>(function(){var a=document.querySelector('a[href^=\'#\']');if(a){a.href=a.href;}})();</script>";
}
add_action( 'wp_print_footer_scripts', 'obdc_simplex_news_skip_link_focus_fix' );

/**
 * Remove o prefixo padrão "Arquivo:" dos títulos de arquivos.
 *
 * @param string $title O título original.
 * @return string O título modificado.
 */
function obdc_simplex_news_remove_archive_title_prefix( $title ) {
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = '<span class="vcard">' . get_the_author() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	} elseif ( is_tax() ) {
		$title = single_term_title( '', false );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'obdc_simplex_news_remove_archive_title_prefix' );