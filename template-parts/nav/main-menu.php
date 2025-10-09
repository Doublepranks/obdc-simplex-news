<?php
/**
 * Persistent site-wide navigation rendered after the masthead.
 *
 * @package ObDC-simplex-news
 */

$menu_markup = '';

if ( has_nav_menu( 'main' ) ) {
	$menu_markup = wp_nav_menu(
		array(
			'theme_location' => 'main',
			'menu_class'     => 'main-menu',
			'container'      => false,
			'echo'           => false,
			'depth'          => 1,
		)
	);
} elseif ( current_user_can( 'edit_theme_options' ) ) {
	$manage_url   = admin_url( 'nav-menus.php?action=locations' );
	$menu_markup  = '<ul class="main-menu main-menu--fallback">';
	$menu_markup .= '<li><a href="' . esc_url( $manage_url ) . '">' . esc_html__( 'Defina o menu principal', 'obdc-simplex-news' ) . '</a></li>';
	$menu_markup .= '</ul>';
}

if ( empty( $menu_markup ) ) {
	return;
}
?>

<nav class="site-main-menu" aria-label="<?php esc_attr_e( 'Menu principal', 'obdc-simplex-news' ); ?>">
	<div class="wrap site-main-menu__inner">
		<?php echo $menu_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</nav>
