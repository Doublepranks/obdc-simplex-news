<?php
/**
 * ObDC-simplex-news Functions and Definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function obdc_simplex_news_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on ObDC-simplex-news, use a find and replace
	 * to change 'obdc-simplex-news' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'obdc-simplex-news', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Set image sizes
	add_image_size( 'hero', 1280, 720, true ); // Hero image
	add_image_size( 'card', 640, 360, true ); // Card image
	add_image_size( 'thumb72', 72, 72, true ); // Most read thumbnail

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Register navigation menus
	register_nav_menus( array(
		'primary' => __( 'Categorias', 'obdc-simplex-news' ),
		'footer_1' => __( 'Rodapé: Seções', 'obdc-simplex-news' ),
		'footer_2' => __( 'Rodapé: Serviços', 'obdc-simplex-news' ),
		'footer_3' => __( 'Rodapé: Institucional', 'obdc-simplex-news' ),
		'footer_4' => __( 'Rodapé: Legal', 'obdc-simplex-news' ),
	) );

	// Register sidebar for "Top Home" ad slot
	register_sidebar( array(
		'name'          => __( 'Top Home', 'obdc-simplex-news' ),
		'id'            => 'top_home',
		'description'   => __( 'Widget area for the top home ad slot.', 'obdc-simplex-news' ),
		'before_widget' => '<div class="ad">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title screen-reader-text">',
		'after_title'   => '</h2>',
	) );

	// Add support for custom logo
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	) );
}
add_action( 'after_setup_theme', 'obdc_simplex_news_setup' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function obdc_simplex_news_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'obdc_simplex_news_content_width', 1160 );
}
add_action( 'after_setup_theme', 'obdc_simplex_news_content_width', 0 );


/**
 * Enqueue scripts and styles.
 */
function obdc_simplex_news_scripts() {
	// Load main stylesheet
	wp_enqueue_style( 'obdc-simplex-news-style', get_stylesheet_uri(), array(), _S_VERSION );

	// Preconnect to Google Fonts for performance
	wp_resource_hints( 'https://fonts.googleapis.com', array( 'as' => 'style', 'crossorigin' => '' ) );
	wp_resource_hints( 'https://fonts.gstatic.com', array( 'crossorigin' => '' ) );

	// Load Google Fonts with explicit font-display: swap
	wp_enqueue_style( 'obdc-simplex-news-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Merriweather:wght@700;900&display=swap', array(), null );

	// Load font-display swap (redundant but ensures it)
	wp_add_inline_style( 'obdc-simplex-news-style', '.font-inter { font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", Arial, sans-serif; } .font-merriweather { font-family: "Merriweather", Georgia, serif; }' );

        // Load JavaScript file if needed (e.g., for dynamic behavior)
        // wp_enqueue_script( 'obdc-simplex-news-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

        if ( is_front_page() ) {
                wp_enqueue_script(
                        'obdc-simplex-news-front-page',
                        get_template_directory_uri() . '/js/front-page.js',
                        array(),
                        _S_VERSION,
                        true
                );
        }

        // Add skip link focus fix
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
        }
}
add_action( 'wp_enqueue_scripts', 'obdc_simplex_news_scripts' );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';


/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


/**
 * Load Customizer Options for Theme Settings
 */
require get_template_directory() . '/inc/customizer-options.php';


/**
 * Include helper functions for theme-specific logic
 */
require get_template_directory() . '/inc/helpers.php';


/**
 * Load Schema.org structured data
 */
require get_template_directory() . '/inc/structured-data.php';


/**
 * Load SEO meta tags
 */
require get_template_directory() . '/inc/seo-meta.php';


/**
 * Register REST endpoint for loading additional front page posts.
 */
function obdc_simplex_news_register_front_page_feed_route() {
        register_rest_route(
                'obdc-simplex-news/v1',
                '/front-page-feed',
                array(
                        'methods'             => WP_REST_Server::READABLE,
                        'callback'            => 'obdc_simplex_news_front_page_feed_callback',
                        'permission_callback' => '__return_true',
                        'args'                => array(
                                'page' => array(
                                        'default'           => 1,
                                        'sanitize_callback' => 'absint',
                                ),
                        ),
                )
        );
}
add_action( 'rest_api_init', 'obdc_simplex_news_register_front_page_feed_route' );


/**
 * Handle REST requests for the front page feed pagination.
 *
 * @param WP_REST_Request $request The current REST request object.
 * @return WP_REST_Response|array Response containing rendered markup and pagination data.
 */
function obdc_simplex_news_front_page_feed_callback( WP_REST_Request $request ) {
        $page = max( 1, absint( $request->get_param( 'page' ) ) );

        $args = array(
                'posts_per_page' => (int) get_option( 'posts_per_page' ),
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
                'paged'          => $page,
                'no_found_rows'  => false,
        );

        if ( function_exists( 'obdc_simplex_news_get_front_page_excluded_post_ids' ) ) {
                $excluded_post_ids = obdc_simplex_news_get_front_page_excluded_post_ids();

                if ( ! empty( $excluded_post_ids ) ) {
                        $args['post__not_in'] = $excluded_post_ids;
                }
        }

        $query = new WP_Query( $args );

        ob_start();

        if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                        $query->the_post();
                        get_template_part( 'template-parts/content/card' );
                }
        }

        $html        = ob_get_clean();
        $max_pages   = (int) $query->max_num_pages;
        $found_posts = (int) $query->found_posts;

        wp_reset_postdata();

        return rest_ensure_response(
                array(
                        'html'       => $html,
                        'maxPages'   => $max_pages,
                        'foundPosts' => $found_posts,
                )
        );
}
