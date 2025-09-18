<?php
/**
 * Implementa o recurso de Cabeçalho Customizado.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package ObDC-simplex-news
 */

// Este tema não utiliza o recurso de Cabeçalho Customizado padrão do WordPress.
// Esta função existe apenas para evitar erros fatais caso outros plugins ou código o invoquem.
// Se você quiser implementar um cabeçalho customizado real, pode fazê-lo aqui.

if ( ! function_exists( 'obdc_simplex_news_custom_header_setup' ) ) {
	function obdc_simplex_news_custom_header_setup() {
		// Não há configurações padrão sendo adicionadas neste momento.
		// Se precisar, adicione add_theme_support( 'custom-header', $args ); aqui.
	}
}
add_action( 'after_setup_theme', 'obdc_simplex_news_custom_header_setup' );