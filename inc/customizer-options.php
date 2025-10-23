<?php
/**
 * ObDC-simplex-news Customizer Options.
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

	// YouTube LIVE integration toggle.
	$wp_customize->add_setting(
		'obdc_simplex_news_youtube_live_enabled',
		array(
			'default'           => false,
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'obdc_simplex_news_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'obdc_simplex_news_youtube_live_enabled',
		array(
			'label'       => __( 'Usar ticker automatico do YouTube', 'obdc-simplex-news' ),
			'description' => __( 'Ative para exibir o status da transmissao ao vivo do canal configurado. Quando off-line, o texto manual sera exibido.', 'obdc-simplex-news' ),
			'section'     => 'obdc_simplex_news_theme_settings',
			'type'        => 'checkbox',
		)
	);

	// YouTube API key.
	$wp_customize->add_setting(
		'obdc_simplex_news_youtube_api_key',
		array(
			'default'           => '',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'obdc_simplex_news_sanitize_api_key',
		)
	);

	$wp_customize->add_control(
		'obdc_simplex_news_youtube_api_key',
		array(
			'label'       => __( 'YouTube API Key', 'obdc-simplex-news' ),
			'description' => __( 'Chave usada para consultar o status das lives. Guarde em local seguro.', 'obdc-simplex-news' ),
			'section'     => 'obdc_simplex_news_theme_settings',
			'type'        => 'text',
			'input_attrs' => array(
				'autocomplete' => 'off',
				'placeholder'  => __( 'AIza...', 'obdc-simplex-news' ),
			),
		)
	);

	// YouTube channel ID.
	$wp_customize->add_setting(
		'obdc_simplex_news_youtube_channel_id',
		array(
			'default'           => 'UCQ9rCTknypukp6KgQPkZC8Q',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'obdc_simplex_news_sanitize_channel_id',
		)
	);

	$wp_customize->add_control(
		'obdc_simplex_news_youtube_channel_id',
		array(
			'label'       => __( 'YouTube Channel ID', 'obdc-simplex-news' ),
			'description' => __( 'ID do canal que deve ser monitorado para transmissao ao vivo.', 'obdc-simplex-news' ),
			'section'     => 'obdc_simplex_news_theme_settings',
			'type'        => 'text',
			'input_attrs' => array(
				'placeholder' => __( 'UCxxxxxxxxxxxxxxxxxxxxxx', 'obdc-simplex-news' ),
			),
		)
	);

	// Live fallback text.
	$wp_customize->add_setting(
		'obdc_simplex_news_youtube_fallback_text',
		array(
			'default'           => __( 'Um Brasil que pensa, comeca de cima.', 'obdc-simplex-news' ),
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'obdc_simplex_news_sanitize_fallback_text',
		)
	);

	$wp_customize->add_control(
		'obdc_simplex_news_youtube_fallback_text',
		array(
			'label'       => __( 'Texto quando nao ha live', 'obdc-simplex-news' ),
			'description' => __( 'Mensagem exibida na barra quando nao houver transmissao ao vivo.', 'obdc-simplex-news' ),
			'section'     => 'obdc_simplex_news_theme_settings',
			'type'        => 'text',
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

	// Footer panel (pre-existing).
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

	// Authors panel.
	$wp_customize->add_panel(
		'obdc_simplex_news_authors_panel',
		array(
			'title'       => __( 'Autores', 'obdc-simplex-news' ),
			'description' => __( 'Configure os autores em destaque na página inicial.', 'obdc-simplex-news' ),
			'priority'    => 120,
		)
	);

	$wp_customize->add_section(
		'obdc_simplex_news_featured_authors_section',
		array(
			'title' => __( 'Autores em destaque', 'obdc-simplex-news' ),
			'panel' => 'obdc_simplex_news_authors_panel',
		)
	);

	$available_roles = obdc_simplex_news_get_available_author_roles();
	$role_choices    = array( '__fallback__' => __( 'Fallback automático (todos os papéis permitidos)', 'obdc-simplex-news' ) ) + $available_roles;

	$wp_customize->add_setting(
		'obdc_simplex_news_featured_author_roles',
		array(
			'default'           => array_keys( $available_roles ),
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'obdc_simplex_news_sanitize_roles',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'obdc_simplex_news_featured_author_roles',
			array(
				'label'       => __( 'Papéis elegíveis', 'obdc-simplex-news' ),
				'section'     => 'obdc_simplex_news_featured_authors_section',
				'type'        => 'select',
				'choices'     => $role_choices,
				'description' => __( 'Selecione quais tipos de usuários podem aparecer no mural (escolha "Fallback automático" para usar todos).', 'obdc-simplex-news' ),
				'input_attrs' => array(
					'multiple' => 'multiple',
					'size'     => min( 8, count( $role_choices ) ),
					'style'    => 'height:auto;',
				),
			)
		)
	);

	// Manual authors.
	$wp_customize->add_setting(
		'obdc_simplex_news_featured_authors',
		array(
			'default'           => array(),
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'obdc_simplex_news_sanitize_author_ids',
		)
	);

	$roles_for_query = obdc_simplex_news_get_featured_author_roles_setting();

	$user_query = new WP_User_Query(
		array(
			'role__in' => $roles_for_query,
			'orderby'  => 'display_name',
			'order'    => 'ASC',
			'fields'   => array( 'ID', 'display_name' ),
			'number'   => 300,
		)
	);

	$author_choices = array(
		'__fallback__' => __( 'Usar fallback automático (autores mais produtivos)', 'obdc-simplex-news' ),
	);

	if ( ! empty( $user_query->results ) ) {
		foreach ( $user_query->results as $user ) {
			$author_choices[ $user->ID ] = $user->display_name;
		}
	}

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'obdc_simplex_news_featured_authors',
			array(
				'label'       => __( 'Selecionar autores manualmente', 'obdc-simplex-news' ),
				'section'     => 'obdc_simplex_news_featured_authors_section',
				'type'        => 'select',
				'choices'     => $author_choices,
				'description' => __( 'Autores selecionados aparecem primeiro no mural. Use Ctrl/Cmd ou Shift para marcar vários. Se não escolher ninguém, o sistema usará o fallback automático.', 'obdc-simplex-news' ),
				'input_attrs' => array(
					'multiple' => 'multiple',
					'size'     => min( 18, max( 8, count( $author_choices ) ) ),
					'style'    => 'height:auto;',
				),
			)
		)
	);

	// Fallback period.
	$wp_customize->add_setting(
		'obdc_simplex_news_featured_authors_period',
		array(
			'default'           => 30,
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'obdc_simplex_news_sanitize_period',
		)
	);

	$wp_customize->add_control(
		'obdc_simplex_news_featured_authors_period',
		array(
			'label'       => __( 'Período para o fallback automático', 'obdc-simplex-news' ),
			'section'     => 'obdc_simplex_news_featured_authors_section',
			'type'        => 'select',
			'choices'     => array(
				7  => __( 'Últimos 7 dias', 'obdc-simplex-news' ),
				30 => __( 'Últimos 30 dias', 'obdc-simplex-news' ),
				90 => __( 'Últimos 90 dias', 'obdc-simplex-news' ),
			),
			'description' => __( 'Quando não houver autores selecionados, o mural exibirá os usuários mais produtivos dentro deste intervalo.', 'obdc-simplex-news' ),
		)
	);
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
 * Sanitize a list of role slugs.
 *
 * @param mixed $roles Raw value from the Customizer.
 * @return array Sanitized role slugs.
 */
function obdc_simplex_news_sanitize_roles( $roles ) {
	$available = array_keys( obdc_simplex_news_get_available_author_roles() );

	if ( is_string( $roles ) ) {
		$roles = array_map( 'trim', explode( ',', $roles ) );
	}

	if ( ! is_array( $roles ) ) {
		return $available;
	}

	$roles = array_map( 'sanitize_key', $roles );
	$use_fallback = in_array( '__fallback__', $roles, true );
	$roles        = array_diff( $roles, array( '__fallback__' ) );
	$roles        = array_intersect( $roles, $available );

	if ( $use_fallback ) {
		return array();
	}

	return array_values( $roles );
}

/**
 * Sanitize a list of author IDs.
 *
 * @param mixed $ids Raw value from the Customizer.
 * @return array Sanitized IDs.
 */
function obdc_simplex_news_sanitize_author_ids( $ids ) {
	if ( empty( $ids ) ) {
		return array();
	}

	if ( is_string( $ids ) ) {
		$ids = array_map( 'trim', explode( ',', $ids ) );
	}

	if ( ! is_array( $ids ) ) {
		return array();
	}

	$use_fallback = in_array( '__fallback__', $ids, true );
	$ids          = array_diff( $ids, array( '__fallback__' ) );

	$ids = array_map( 'absint', $ids );
	$ids = array_filter( $ids );

	if ( $use_fallback ) {
		return array();
	}

	return array_values( array_unique( $ids ) );
}

/**
 * Sanitize the fallback period value.
 *
 * @param mixed $value Raw period value.
 * @return int Valid number of days (7, 30 or 90).
 */
function obdc_simplex_news_sanitize_period( $value ) {
	$value = absint( $value );

	if ( ! in_array( $value, array( 7, 30, 90 ), true ) ) {
		$value = 30;
	}

	return $value;
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

/**
 * Sanitize YouTube API key.
 *
 * @param string $input Raw API key.
 * @return string Sanitized key.
 */
function obdc_simplex_news_sanitize_api_key( $input ) {
	if ( ! is_string( $input ) ) {
		return '';
	}

	$input = trim( $input );
	$input = sanitize_text_field( $input );
	return substr( $input, 0, 128 );
}

/**
 * Sanitize YouTube channel ID.
 *
 * @param string $input Raw channel ID.
 * @return string Sanitized channel ID.
 */
function obdc_simplex_news_sanitize_channel_id( $input ) {
	if ( ! is_string( $input ) ) {
		return '';
	}

	$input = trim( $input );
	$input = sanitize_text_field( $input );
	return substr( $input, 0, 64 );
}

/**
 * Sanitize fallback text for the LIVE bar.
 *
 * @param string $input Raw fallback text.
 * @return string Sanitized fallback text.
 */
function obdc_simplex_news_sanitize_fallback_text( $input ) {
	$input = sanitize_text_field( $input );
	return substr( $input, 0, 150 );
}

// Note: The function obdc_simplex_news_customize_preview_js() was moved to inc/customizer.php
// to avoid conflicts and follow standard WordPress practices for Customizer scripts.
