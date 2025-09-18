<?php
/**
 * Arquivo de configuração do Customizer para o tema ObDC-simplex-news.
 *
 * @package ObDC-simplex-news
 * @subpackage Inc
 * @since 1.0.0
 */

// As opções principais do Customizer estão definidas em inc/customizer-options.php.
// Este arquivo existe apenas para evitar erros fatais e carregar o script de preview.

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function obdc_simplex_news_customize_preview_js() {
	wp_enqueue_script( 'obdc_simplex_news_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'obdc_simplex_news_customize_preview_js' );