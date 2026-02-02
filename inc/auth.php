<?php
/**
 * Authentication related customizations.
 *
 * @package ObDC-simplex-news
 */

/**
 * Filter the login URL to point to /calango.
 *
 * @param string $login_url    The login URL.
 * @param string $redirect     The path to redirect to on login, if any.
 * @param bool   $force_reauth Whether to force reauthorization.
 *
 * @return string Modified login URL.
 */
function obdc_simplex_news_custom_login_url( $login_url, $redirect, $force_reauth ) {
	$login_page = home_url( '/calango/' );

	if ( ! empty( $redirect ) ) {
		$login_page = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_page );
	}

	return $login_page;
}
add_filter( 'login_url', 'obdc_simplex_news_custom_login_url', 10, 3 );


/**
 * Filter the registration URL to point to /register.
 *
 * @param string $register_url The registration URL.
 *
 * @return string Modified registration URL.
 */
function obdc_simplex_news_custom_register_url( $register_url ) {
	return home_url( '/register/' );
}
add_filter( 'register_url', 'obdc_simplex_news_custom_register_url' );
