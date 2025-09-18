<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Top live ticker / login notice -->
<?php get_template_part( 'template-parts/topbar' ); ?>

<!-- Masthead -->
<header class="masthead" itemscope itemtype="https://schema.org/WPHeader">
	<div class="wrap row">
		<button aria-label="<?php esc_attr_e( 'Abrir menu', 'obdc-simplex-news' ); ?>" title="<?php esc_attr_e( 'Abrir menu', 'obdc-simplex-news' ); ?>" class="menu-toggle" type="button">
			<span class="screen-reader-text">Menu</span>
			☰
		</button>
		
		<?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) : ?>
			<div class="logo" itemprop="headline">
				<?php the_custom_logo(); ?>
			</div>
		<?php else : ?>
			<h1 class="logo" itemprop="headline"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">O Brasil de Cima</a></h1>
		<?php endif; ?>
		
		<div class="search">
			<label for="q" class="screen-reader-text">Busca</label>
			<?php get_search_form(); ?>
			<nav class="auth" aria-label="Acesso">
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Sair</a>
					<span aria-hidden="true">•</span>
					<a href="<?php echo esc_url( get_author_posts_url( get_current_user_id() ) ); ?>" title="Meu perfil">Perfil</a>
				<?php else : ?>
					<a href="<?php echo esc_url( wp_login_url() ); ?>">Entrar</a>
					<span aria-hidden="true">•</span>
					<a href="<?php echo esc_url( wp_registration_url() ); ?>">Cadastrar</a>
				<?php endif; ?>
			</nav>
		</div>
	</div>
</header>

<!-- Categories nav -->
<nav class="categories" aria-label="Seções">
	<div class="wrap">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'menu_class'     => 'category-pills',
			'fallback_cb'    => false,
		) );
		?>
	</div>
</nav>