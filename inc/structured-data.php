<?php
/**
 * ObDC-simplex-news Structured Data (Schema.org)
 *
 * @package ObDC-simplex-news
 */

/**
 * Output structured data for the current page.
 */
function obdc_simplex_news_output_structured_data() {
	if ( is_single() && in_array( get_post_type(), array( 'post' ) ) ) {
		// NewsArticle schema for single posts
		$post = get_post();
		$author = get_userdata( $post->post_author );
		$category = get_the_category();
		$first_cat = ! empty( $category ) ? $category[0] : null;

		// Get tags safely
		$post_tags = get_the_tags();
		$keywords = '';
		if ( $post_tags && is_array( $post_tags ) ) {
			$tag_names = array_map( function( $tag ) { return $tag->name; }, $post_tags );
			$keywords = implode( ',', $tag_names );
		}

		$structured_data = array(
			'@context' => 'https://schema.org',
			'@type' => 'NewsArticle',
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id' => get_permalink(),
			),
			'headline' => get_the_title(),
			'description' => wp_trim_words( get_the_excerpt(), 30 ),
			'image' => get_the_post_thumbnail_url( null, 'hero' ),
			'genre' => $first_cat ? esc_attr( $first_cat->name ) : '',
			'keywords' => $keywords, // Usando a variável $keywords segura
			'wordCount' => str_word_count( strip_tags( $post->post_content ) ),
			'publisher' => array(
				'@type' => 'Organization',
				'name' => 'O Brasil de Cima',
				'logo' => array(
					'@type' => 'ImageObject',
					'url' => get_template_directory_uri() . '/images/logo.png',
				),
			),
			'author' => array(
				'@type' => 'Person',
				'name' => $author ? esc_html( $author->display_name ) : 'Redação',
			),
			'datePublished' => get_the_date( 'c' ),
			'dateModified' => get_the_modified_date( 'c' ),
		);

		echo '<script type="application/ld+json">' . json_encode( $structured_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>';
	}

	if ( is_front_page() || is_home() ) {
		// Organization schema for homepage
		$structured_data = array(
			'@context' => 'https://schema.org',
			'@type' => 'Organization',
			'name' => 'O Brasil de Cima',
			'url' => home_url(),
			'logo' => array(
				'@type' => 'ImageObject',
				'url' => get_template_directory_uri() . '/images/logo.png',
			),
		);

		echo '<script type="application/ld+json">' . json_encode( $structured_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>';
	}
}
add_action( 'wp_head', 'obdc_simplex_news_output_structured_data', 5 );