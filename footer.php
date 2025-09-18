<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

?>

	</div><!-- .wrap -->
</main><!-- #main -->

<!-- Footer -->
<footer id="colophon" class="site-footer" itemscope itemtype="https://schema.org/WPFooter">
	<div class="wrap">
		<div class="footer-content">
			<?php if ( is_active_sidebar( 'footer_1' ) ) : ?>
				<div class="footer-section">
					<h5><?php esc_html_e( 'Seções', 'obdc-simplex-news' ); ?></h5>
					<?php dynamic_sidebar( 'footer_1' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer_2' ) ) : ?>
				<div class="footer-section">
					<h5><?php esc_html_e( 'Serviços', 'obdc-simplex-news' ); ?></h5>
					<?php dynamic_sidebar( 'footer_2' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer_3' ) ) : ?>
				<div class="footer-section">
					<h5><?php esc_html_e( 'Institucional', 'obdc-simplex-news' ); ?></h5>
					<?php dynamic_sidebar( 'footer_3' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer_4' ) ) : ?>
				<div class="footer-section">
					<h5><?php esc_html_e( 'Legal', 'obdc-simplex-news' ); ?></h5>
					<?php dynamic_sidebar( 'footer_4' ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="footer-bottom">
			<div>
				<p>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> O Brasil de Cima. Todos os direitos reservados.</p>
				<p style="font-size:.8rem;color:#6b7280;margin-top:5px">
					<?php 
					// Get customizer setting for CNPJ and city
					$cnpj = get_theme_mod( 'obdc_simplex_news_cnpj', '00.000.000/0001-00' );
					$city = get_theme_mod( 'obdc_simplex_news_city', 'Belém, PA' );
					echo esc_html( $cnpj ) . ' • ' . esc_html( $city );
					?>
				</p>
			</div>
			<div class="social">
				<a href="#" aria-label="Facebook" title="Facebook">F</a>
				<a href="#" aria-label="Twitter" title="Twitter">X</a>
				<a href="#" aria-label="Instagram" title="Instagram">IG</a>
				<a href="#" aria-label="YouTube" title="YouTube">YT</a>
				<a href="#" aria-label="LinkedIn" title="LinkedIn">IN</a>
			</div>
		</div>
	</div>
</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>
</html>