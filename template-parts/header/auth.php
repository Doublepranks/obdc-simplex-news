<?php
/**
 * Header auth navigation (login/logout).
 *
 * @package ObDC-simplex-news
 */

if ( ! isset( $args ) || ! is_array( $args ) ) {
	$args = array();
}

$context = isset( $args['context'] ) ? sanitize_key( $args['context'] ) : 'desktop';

if ( is_user_logged_in() ) {
    $user          = wp_get_current_user();
    $display_name  = $user->display_name ? $user->display_name : $user->user_login;
    $profile_url   = get_author_posts_url( $user->ID );
    $edit_profile_url = get_edit_profile_url( $user->ID );
    $avatar_markup = get_avatar( $user->ID, 40, '', esc_attr( $display_name ), array( 'class' => 'auth__avatar' ) );
}

if ( 'drawer' === $context ) :
	?>
	<div class="drawer-auth" role="navigation" aria-label="<?php esc_attr_e( 'Acesso', 'obdc-simplex-news' ); ?>">
        <div class="drawer-auth__row">
            <?php if ( is_user_logged_in() ) : ?>
                <?php if ( ! empty( $avatar_markup ) ) : ?>
                    <?php echo $avatar_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php endif; ?>
                <a class="drawer-auth__name" href="<?php echo esc_url( $profile_url ); ?>"><?php echo esc_html( $display_name ); ?></a>
                <span class="drawer-auth__divider" aria-hidden="true"></span>
                <a class="drawer-auth__action drawer-auth__edit" href="<?php echo esc_url( $edit_profile_url ); ?>"><?php esc_html_e( 'Editar', 'obdc-simplex-news' ); ?></a>
                <span class="drawer-auth__divider" aria-hidden="true"></span>
                <a class="drawer-auth__action drawer-auth__logout" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Sair', 'obdc-simplex-news' ); ?></a>
            <?php else : ?>
                <a class="drawer-auth__action" href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Entrar', 'obdc-simplex-news' ); ?></a>
                <span class="drawer-auth__divider" aria-hidden="true"></span>
                <a class="drawer-auth__action" href="<?php echo esc_url( wp_registration_url() ); ?>"><?php esc_html_e( 'Cadastrar', 'obdc-simplex-news' ); ?></a>
			<?php endif; ?>
		</div>
		<div class="drawer-search">
			<div class="drawer-search__form">
			<?php
			if ( shortcode_exists( 'wpdreams_ajaxsearchlite' ) ) {
				echo do_shortcode( '[wpdreams_ajaxsearchlite]' );
			} else {
				get_search_form();
			}
			?>
			</div>
		</div>
	</div>
	<?php
	return;
endif;

$classes = array( 'auth', 'auth--' . $context );
?>

<nav class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $classes ) ) ); ?>" aria-label="<?php esc_attr_e( 'Acesso', 'obdc-simplex-news' ); ?>">
    <?php if ( is_user_logged_in() ) : ?>
        <a class="auth__profile" href="<?php echo esc_url( $profile_url ); ?>">
            <?php echo $avatar_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <span class="auth__name"><?php echo esc_html( $display_name ); ?></span>
        </a>
        <a class="auth__link auth__edit" href="<?php echo esc_url( $edit_profile_url ); ?>"><?php esc_html_e( 'Editar', 'obdc-simplex-news' ); ?></a>
        <a class="auth__link auth__logout" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Sair', 'obdc-simplex-news' ); ?></a>
    <?php else : ?>
        <a class="auth__link auth__login" href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Entrar', 'obdc-simplex-news' ); ?></a>
        <a class="auth__link auth__register" href="<?php echo esc_url( wp_registration_url() ); ?>"><?php esc_html_e( 'Cadastrar', 'obdc-simplex-news' ); ?></a>
    <?php endif; ?>
</nav>
