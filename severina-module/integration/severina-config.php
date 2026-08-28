<?php

if (!defined('SEVERINA_ASSETS_URL')) {

    define(
        'SEVERINA_ASSETS_URL',
        get_template_directory_uri() . '/severina-module/assets'
    );

}

if (!defined('SEVERINA_ASSETS_PATH')) {

    define(
        'SEVERINA_ASSETS_PATH',
        get_template_directory() . '/severina-module/assets'
    );

}

if (!defined('SEVERINA_FOTO_PADRAO')) {

    define('SEVERINA_FOTO_PADRAO', 'autor-padrao.svg');

}

if (!function_exists('severina_foto_autor')) {

    /**
     * Devolve a URL da foto de um autor, caindo na imagem padrao do tema
     * quando o arquivo nao existe.
     *
     * Os retratos de autores nao sao versionados (ver .gitignore): em um
     * clone limpo esta funcao entrega a imagem padrao em vez de um link
     * quebrado; no servidor, onde os arquivos existem, entrega a foto real.
     *
     * @param string $arquivo Nome do arquivo dentro de assets/images/.
     * @return string URL escapada, pronta para o atributo src.
     */
    function severina_foto_autor($arquivo = '')
    {
        $arquivo = ltrim((string) $arquivo, '/');

        $valido = $arquivo !== ''
            && strpos($arquivo, '..') === false
            && is_file(SEVERINA_ASSETS_PATH . '/images/' . $arquivo);

        if (!$valido) {
            $arquivo = SEVERINA_FOTO_PADRAO;
        }

        return esc_url(SEVERINA_ASSETS_URL . '/images/' . $arquivo);
    }

}
