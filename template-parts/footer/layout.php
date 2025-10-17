<?php
/**
 * Footer layout template part.
 *
 * @package ObDC-simplex-news
 */

$menu_sections = array(
	'footer-news',
	'footer-brazil',
	'footer-site',
	'footer-opinion',
	'footer-sports',
	'footer-entertainment',
);

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
				<?php foreach ( $menu_sections as $location ) : ?>
					<?php if ( has_nav_menu( $location ) ) : ?>
						<?php
						$label       = obdc_simplex_news_get_footer_section_label( $location );
						$section_key = sanitize_html_class( $location );
						$toggle_id   = 'footer-toggle-' . $section_key;
						$panel_id    = 'footer-panel-' . $section_key;
						$is_open     = obdc_simplex_news_is_footer_section_open_mobile( $location );
						?>
						<div class="footer-section" data-footer-accordion>
							<button
								type="button"
								class="footer-section-toggle"
								id="<?php echo esc_attr( $toggle_id ); ?>"
								aria-expanded="true"
								aria-controls="<?php echo esc_attr( $panel_id ); ?>"
								data-footer-toggle
								<?php if ( $is_open ) : ?>
									data-accordion-open="true"
								<?php endif; ?>
							>
								<span class="footer-section-title"><?php echo esc_html( $label ); ?></span>
								<span class="footer-section-indicator" aria-hidden="true"></span>
							</button>
							<div
								id="<?php echo esc_attr( $panel_id ); ?>"
								class="footer-section-panel"
								role="region"
								aria-labelledby="<?php echo esc_attr( $toggle_id ); ?>"
								data-footer-panel
							>
								<nav class="footer-column" aria-label="<?php echo esc_attr( sprintf( __( 'Rodapé: %s', 'obdc-simplex-news' ), $label ) ); ?>">
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
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php
				if ( has_nav_menu( 'footer-social' ) ) :
					$locations    = get_nav_menu_locations();
					$social_menu  = isset( $locations['footer-social'] ) ? wp_get_nav_menu_object( $locations['footer-social'] ) : null;
					$social_items = $social_menu ? wp_get_nav_menu_items( $social_menu ) : array();

					if ( ! empty( $social_items ) ) :
						$section_key = 'footer-social';
						$toggle_id   = 'footer-toggle-' . $section_key;
						$panel_id    = 'footer-panel-' . $section_key;
						$social_heading = obdc_simplex_news_get_footer_section_label( 'footer-social' );
						$social_open    = obdc_simplex_news_is_footer_section_open_mobile( 'footer-social' );
						?>
						<div class="footer-section" data-footer-accordion>
							<button
								type="button"
								class="footer-section-toggle"
								id="<?php echo esc_attr( $toggle_id ); ?>"
								aria-expanded="true"
								aria-controls="<?php echo esc_attr( $panel_id ); ?>"
								data-footer-toggle
								<?php if ( $social_open ) : ?>
									data-accordion-open="true"
								<?php endif; ?>
							>
								<span class="footer-section-title"><?php echo esc_html( $social_heading ); ?></span>
								<span class="footer-section-indicator" aria-hidden="true"></span>
							</button>
							<div
								id="<?php echo esc_attr( $panel_id ); ?>"
								class="footer-section-panel"
								role="region"
								aria-labelledby="<?php echo esc_attr( $toggle_id ); ?>"
								data-footer-panel
							>
								<nav class="footer-column footer-column-social" aria-label="<?php echo esc_attr( sprintf( __( 'Rodapé: %s', 'obdc-simplex-news' ), $social_heading ) ); ?>">
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
							</div>
						</div>
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
