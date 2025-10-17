<?php
/**
 * ObDC-simplex-news Customizer Options
 *
 * @package ObDC-simplex-news
 */

/**
 * Register theme customization options.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function obdc_simplex_news_customize_register( $wp_customize ) {
	// Theme settings section.
	$wp_customize->add_section(
		'obdc_simplex_news_theme_settings',
		array(
			'title'    => __( 'Configurações do Tema', 'obdc-simplex-news' ),
			'priority' => 100,
		)
	);

	// Live status toggle.
	$wp_customize->add_setting(
		'obdc_simplex_news_live_status',
		array(
			'default'           => 'on',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'obdc_simplex_news_sanitize_select',
		)
	);

	$wp_customize->add_control(
		'obdc_simplex_news_live_status',
		array(
			'label'   => __( 'Status do Ticker LIVE', 'obdc-simplex-news' ),
			'section' => 'obdc_simplex_news_theme_settings',
			'type'    => 'select',
			'choices' => array(
				'on'  => __( 'Ativado', 'obdc-simplex-news' ),
				'off' => __( 'Desativado', 'obdc-simplex-news' ),
			),
		)
	);

	// Live text.
	$wp_customize->add_setting(
		'obdc_simplex_news_live_text',
		array(
			'default'           => __( 'Sinal ao vivo quando o canal estiver no ar', 'obdc-simplex-news' ),
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'obdc_simplex_news_sanitize_live_text',
		)
	);

	$wp_customize->add_control(
		'obdc_simplex_news_live_text',
		array(
			'label'   => __( 'Texto do Ticker LIVE', 'obdc-simplex-news' ),
			'section' => 'obdc_simplex_news_theme_settings',
			'type'    => 'text',
		)
	);

	// CNPJ.
	$wp_customize->add_setting(
		'obdc_simplex_news_cnpj',
		array(
			'default'           => '00.000.000/0001-00',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'obdc_simplex_news_cnpj',
		array(
			'label'   => __( 'CNPJ da Empresa', 'obdc-simplex-news' ),
			'section' => 'obdc_simplex_news_theme_settings',
			'type'    => 'text',
		)
	);

	// City.
	$wp_customize->add_setting(
		'obdc_simplex_news_city',
		array(
			'default'           => 'Belém, PA',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'obdc_simplex_news_city',
		array(
			'label'   => __( 'Cidade da Sede', 'obdc-simplex-news' ),
			'section' => 'obdc_simplex_news_theme_settings',
			'type'    => 'text',
		)
	);

	// Footer panel.
	$wp_customize->add_panel(
		'obdc_simplex_news_footer_panel',
		array(
			'title'       => __( 'Rodapé', 'obdc-simplex-news' ),
			'description' => __( 'Personalize os títulos das colunas e o comportamento do acordeão no mobile.', 'obdc-simplex-news' ),
			'priority'    => 110,
		)
	);

	$wp_customize->add_section(
		'obdc_simplex_news_footer_sections',
		array(
			'title' => __( 'Seções do rodapé', 'obdc-simplex-news' ),
			'panel' => 'obdc_simplex_news_footer_panel',
		)
	);

	$footer_sections = obdc_simplex_news_get_footer_section_defaults();

	foreach ( $footer_sections as $location => $default_label ) {
		$label_setting_id = obdc_simplex_news_get_footer_section_label_mod_key( $location );
		$open_setting_id  = obdc_simplex_news_get_footer_section_open_mod_key( $location );

		$wp_customize->add_setting(
			$label_setting_id,
			array(
				'default'           => $default_label,
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			$label_setting_id,
			array(
				/* translators: %s: default footer section title. */
				'label'   => sprintf( __( 'Título da seção (%s)', 'obdc-simplex-news' ), $default_label ),
				'section' => 'obdc_simplex_news_footer_sections',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			$open_setting_id,
			array(
				'default'           => false,
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'obdc_simplex_news_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			$open_setting_id,
			array(
				/* translators: %s: footer section label. */
				'label'   => sprintf( __( 'Abrir "%s" por padrão no mobile', 'obdc-simplex-news' ), $default_label ),
				'section' => 'obdc_simplex_news_footer_sections',
				'type'    => 'checkbox',
			)
		);
	}
}
add_action( 'customize_register', 'obdc_simplex_news_customize_register' );

/**
 * Sanitize select values for customizer settings.
 *
 * @param string $input The input value.
 * @return string The sanitized value.
 */
function obdc_simplex_news_sanitize_select( $input ) {
	$valid_keys = array(
		'on'  => 'on',
		'off' => 'off',
	);

	if ( array_key_exists( $input, $valid_keys ) ) {
		return $input;
	}

	return '';
}

/**
 * Sanitize checkbox values.
 *
 * @param mixed $value Raw checkbox value.
 * @return bool Sanitized boolean flag.
 */
function obdc_simplex_news_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Sanitize live text with a character limit (150 chars).
 *
 * @param string $input The input value.
 * @return string The sanitized value.
 */
function obdc_simplex_news_sanitize_live_text( $input ) {
	$input = sanitize_text_field( $input );
	$input = substr( $input, 0, 150 );
	return $input;
}

// Note: The function obdc_simplex_news_customize_preview_js() was moved to inc/customizer.php
// to avoid conflicts and follow standard WordPress practices for Customizer scripts.
