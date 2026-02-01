<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section
 * and everything up until <div id="content">.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ObDC-simplex-news
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<!-- Top live ticker / login notice -->
	<?php get_template_part('template-parts/topbar'); ?>

	<!-- Masthead -->
	<header class="masthead" itemscope itemtype="https://schema.org/WPHeader">
		<div class="wrap row">
			<button aria-label="<?php esc_attr_e('Abrir menu', 'obdc-simplex-news'); ?>"
				title="<?php esc_attr_e('Abrir menu', 'obdc-simplex-news'); ?>" class="menu-toggle" type="button"
				aria-expanded="false" aria-controls="site-drawer" data-menu-toggle>
				<span class="menu-toggle__icon" aria-hidden="true">
					<span></span>
					<span></span>
					<span></span>
				</span>
				<span class="screen-reader-text"><?php esc_html_e('Menu', 'obdc-simplex-news'); ?></span>
			</button>

			<?php if (function_exists('the_custom_logo') && has_custom_logo()): ?>
				<div class="logo" itemprop="headline">
					<?php the_custom_logo(); ?>
				</div>
			<?php else: ?>
				<h1 class="logo" itemprop="headline">
					<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">O Brasil de Cima</a>
				</h1>
			<?php endif; ?>

			<div class="masthead-search">
				<div class="masthead-search__form">
					<?php
					if (shortcode_exists('wpdreams_ajaxsearchlite')) {
						echo do_shortcode('[wpdreams_ajaxsearchlite]');
					} else {
						get_search_form();
					}
					?>
				</div>
				<?php get_template_part('template-parts/header/auth', null, array('context' => 'desktop')); ?>
			</div>
		</div>
	</header>

	<div class="site-drawer" id="site-drawer" hidden data-site-drawer>
		<aside class="site-drawer__panel" role="dialog" aria-modal="true" aria-labelledby="site-drawer-title">
			<button class="site-drawer__close" type="button"
				aria-label="<?php esc_attr_e('Fechar menu', 'obdc-simplex-news'); ?>" data-menu-close>
				<span aria-hidden="true">&times;</span>
			</button>
			<nav class="site-drawer__nav" aria-labelledby="site-drawer-title">
				<?php get_template_part('template-parts/header/auth', null, array('context' => 'drawer')); ?>

				<p id="site-drawer-title" class="site-drawer__title">
					<?php esc_html_e('Menu', 'obdc-simplex-news'); ?>
				</p>

				<?php
				$drawer_menu_location = '';

				if (has_nav_menu('drawer')) {
					$drawer_menu_location = 'drawer';
				} elseif (has_nav_menu('main')) {
					$drawer_menu_location = 'main';
				}

				if ($drawer_menu_location) {
					wp_nav_menu(
						array(
							'theme_location' => $drawer_menu_location,
							'menu_class' => 'site-drawer__list',
							'container' => '',
							'depth' => 2,
						)
					);
				} elseif (current_user_can('edit_theme_options')) {
					$manage_url = admin_url('nav-menus.php?action=locations');
					?>
					<ul class="site-drawer__list site-drawer__list--fallback">
						<li>
							<a href="<?php echo esc_url($manage_url); ?>">
								<?php esc_html_e('Configure o menu móvel', 'obdc-simplex-news'); ?>
							</a>
						</li>
					</ul>
					<?php
				}
				?>

				<div class="site-drawer__social">
					<p class="site-drawer__subtitle">
						<?php esc_html_e('Nos siga nas redes sociais', 'obdc-simplex-news'); ?></p>
					<ul class="site-drawer__social-list"
						aria-label="<?php esc_attr_e('Redes sociais', 'obdc-simplex-news'); ?>">
						<li>
							<a href="https://instagram.com/" target="_blank" rel="noopener" aria-label="Instagram">
								<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
									<path
										d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zm0 2a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm6.25-.88a1.12 1.12 0 1 1-2.24 0 1.12 1.12 0 0 1 2.24 0z" />
								</svg>
							</a>
						</li>
						<li>
							<a href="https://twitter.com/" target="_blank" rel="noopener" aria-label="X (Twitter)">
								<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
									<path
										d="M3 5.5h3.3l5 6.4 4.4-6.4H21l-6.4 9.3 5.5 7.2h-3.3l-4.6-6-4.1 6H3l6.7-9.6z" />
								</svg>
							</a>
						</li>
						<li>
							<a href="https://youtube.com/" target="_blank" rel="noopener" aria-label="YouTube">
								<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
									<path
										d="M21.6 7.2a2.4 2.4 0 0 0-1.7-1.7C18.2 5 12 5 12 5s-6.2 0-7.9.5a2.4 2.4 0 0 0-1.7 1.7C2 9 2 12 2 12s0 3 .4 4.8a2.4 2.4 0 0 0 1.7 1.7C5.8 19 12 19 12 19s6.2 0 7.9-.5a2.4 2.4 0 0 0 1.7-1.7C22 15 22 12 22 12s0-3-.4-4.8zM10 15.5v-7l6 3.5z" />
								</svg>
							</a>
						</li>
						<li>
							<a href="https://www.tiktok.com/" target="_blank" rel="noopener" aria-label="TikTok">
								<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
									<path
										d="M15.5 3.5c1 1.5 2.5 2.4 4.3 2.5v3.5c-1.5-.03-2.9-.4-4.3-1v6.1a5.4 5.4 0 1 1-5.4-5.4c.3 0 .6 0 .9.1v3.4a2 2 0 1 0 1.4 1.9V2.5h3.1z" />
								</svg>
							</a>
						</li>
					</ul>
				</div>
			</nav>
		</aside>
		<div class="site-drawer__overlay" data-menu-close aria-hidden="true"></div>
	</div>

	<!-- Main navigation -->
	<?php get_template_part('template-parts/nav/main-menu'); ?>