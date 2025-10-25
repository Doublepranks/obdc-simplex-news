<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package ObDC-simplex-news
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="wrap">
		<section class="page-404" aria-labelledby="page-404-title">
			<div class="page-404__emoji" aria-hidden="true">😕</div>
			<h1 class="page-404__title" id="page-404-title"><?php esc_html_e( 'Não encontramos esta página', 'obdc-simplex-news' ); ?></h1>
			<p class="page-404__subtitle">
				<?php esc_html_e( 'O conteúdo pode ter sido removido ou o link está incorreto. Experimente voltar para a página inicial ou realizar uma busca.', 'obdc-simplex-news' ); ?>
			</p>

			<div class="page-404__actions">
				<a class="page-404__button" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php esc_html_e( 'Ir para a página inicial', 'obdc-simplex-news' ); ?>
				</a>
			</div>

			<div class="page-404__search">
				<?php get_search_form(); ?>
			</div>
		</section>
	</div>
</main>

<?php
get_footer();
