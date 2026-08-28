<?php
function revista_enqueue_assets() {
    if (
        is_page('revista-severina')
        || is_singular('revista')
        || is_post_type_archive('revista')
    ) {
        wp_enqueue_style(
            'revista-style',
            SEVERINA_ASSETS_URL . '/css/revista.css',
            [],
            '1.0'
        );

        wp_enqueue_script(
            'pdfjs',
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js',
            [],
            null,
            true
        );

        wp_enqueue_script(
            'revista-script',
            SEVERINA_ASSETS_URL . '/js/revista.js',
            ['pdfjs'],
            file_exists(get_theme_file_path('/js/revista.js'))
                ? filemtime(get_theme_file_path('/js/revista.js'))
                : '1.2',
            true
        );

        wp_enqueue_script(
            'mobile-menu',
            SEVERINA_ASSETS_URL . '/js/mobile-menu.js',
            [],
            '1.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'revista_enqueue_assets');