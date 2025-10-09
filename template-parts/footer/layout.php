<?php
/**
 * Footer layout template part.
 *
 * @package ObDC-simplex-news
 */

$menu_sections = array(
	'footer-news'          => esc_html__( 'Notícias', 'obdc-simplex-news' ),
	'footer-brazil'        => esc_html__( 'Brasil', 'obdc-simplex-news' ),
	'footer-site'          => esc_html__( 'Site', 'obdc-simplex-news' ),
	'footer-opinion'       => esc_html__( 'Opinião', 'obdc-simplex-news' ),
	'footer-sports'        => esc_html__( 'Esportes', 'obdc-simplex-news' ),
	'footer-entertainment' => esc_html__( 'Entretenimento', 'obdc-simplex-news' ),
);

$social_heading = esc_html__( 'Siga o Brasil de Cima', 'obdc-simplex-news' );
$cnpj           = get_theme_mod( 'obdc_simplex_news_cnpj', '00.000.000/0001-00' );
$city           = get_theme_mod( 'obdc_simplex_news_city', 'Belém, PA' );
?>

<footer id="colophon" class="site-footer" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">
	<div class="wrap">
		<div class="footer-main">
			<div class="footer-branding">
				<?php
				if ( is_active_sidebar( 'footer-branding' ) ) {
					dynamic_sidebar( 'footer-branding' );
				} elseif ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
					the_custom_logo();
					?>
					<p class="footer-tagline"><?php bloginfo( 'description' ); ?></p>
					<?php
				} else {
					?>
					<p class="footer-site-title"><?php bloginfo( 'name' ); ?></p>
					<p class="footer-tagline"><?php bloginfo( 'description' ); ?></p>
					<?php
				}
				?>
			</div>

			<div class="footer-columns">
				<?php foreach ( $menu_sections as $location => $label ) : ?>
					<?php if ( has_nav_menu( $location ) ) : ?>
						<nav class="footer-column" aria-label="<?php echo esc_attr( sprintf( __( 'Rodapé: %s', 'obdc-simplex-news' ), $label ) ); ?>">
							<h2 class="footer-heading"><?php echo esc_html( $label ); ?></h2>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => $location,
									'depth'          => 1,
									'container'      => false,
									'menu_class'     => 'footer-menu',
									'fallback_cb'    => false,
								)
							);
							?>
						</nav>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php
				if ( has_nav_menu( 'footer-social' ) ) :
					$locations    = get_nav_menu_locations();
					$social_menu  = isset( $locations['footer-social'] ) ? wp_get_nav_menu_object( $locations['footer-social'] ) : null;
					$social_items = $social_menu ? wp_get_nav_menu_items( $social_menu ) : array();

					if ( ! empty( $social_items ) ) :
						?>
						<nav class="footer-column footer-column-social" aria-label="<?php echo esc_attr( sprintf( __( 'Rodapé: %s', 'obdc-simplex-news' ), $social_heading ) ); ?>">
							<h2 class="footer-heading"><?php echo esc_html( $social_heading ); ?></h2>
							<ul class="footer-social-list">
								<?php foreach ( $social_items as $item ) : ?>
									<?php
									$label       = $item->title ? $item->title : '';
									$label_text  = $label ? wp_strip_all_tags( $label ) : '';
									$network_key = $label_text ? $label_text : $item->post_name;
									$network     = sanitize_key( $network_key );
									$icon        = obdc_simplex_news_get_social_icon_svg( $network );
									$url         = $item->url ? $item->url : '#';
									$target      = '_blank';
									$extra_rel   = trim( $item->xfn );

									$rel_parts = array();
									if ( ! empty( $extra_rel ) ) {
										$rel_parts[] = $extra_rel;
									}
									$rel_parts[] = 'noopener';
									$rel_parts[] = 'noreferrer';
									$rel = implode( ' ', array_filter( array_unique( $rel_parts ) ) );
									?>
									<li class="footer-social-item">
										<a
											class="footer-social-link"
											href="<?php echo esc_url( $url ); ?>"
											<?php if ( '_self' !== $target ) : ?>
												target="<?php echo esc_attr( $target ); ?>"
											<?php endif; ?>
											<?php if ( ! empty( $rel ) ) : ?>
												rel="<?php echo esc_attr( $rel ); ?>"
											<?php endif; ?>
											aria-label="<?php echo esc_attr( sprintf( __( 'Abrir %s', 'obdc-simplex-news' ), $label_text ? $label_text : __( 'rede social', 'obdc-simplex-news' ) ) ); ?>"
										>
											<span class="footer-social-icon" aria-hidden="true">
												<?php echo $icon ? $icon : '<span class="footer-social-fallback">' . esc_html( $label_text ) . '</span>'; ?>
											</span>
											<span class="footer-social-label"><?php echo esc_html( $label_text ); ?></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</nav>
						<?php
					endif;
				endif;
				?>
			</div>
		</div>

		<div class="footer-bottom">
			<p class="footer-copy">
				<?php
				printf(
					/* translators: %s: Site name. */
					esc_html__( '&copy; %1$s %2$s. Todos os direitos reservados.', 'obdc-simplex-news' ),
					esc_html( date_i18n( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</p>
			<?php if ( $cnpj || $city ) : ?>
				<p class="footer-meta">
					<?php
					if ( $cnpj ) {
						echo esc_html( $cnpj );
					}

					if ( $cnpj && $city ) {
						echo ' &bull; ';
					}

					if ( $city ) {
						echo esc_html( $city );
					}
					?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</footer>
