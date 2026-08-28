<?php

$severina_home = home_url('/revista-severina/');

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<header class="site-header">

    <div class="container">

        <div class="header-wrapper">

            <button class="mobile-menu-button">

                <img 
                    src="<?php echo SEVERINA_ASSETS_URL; ?>/images/hamburguer-menu.png"
                    alt="Menu"
                >

            </button>

            <a href="<?php echo esc_url($severina_home); ?>" class="logo">

                <img 
                    src="<?php echo SEVERINA_ASSETS_URL; ?>/images/logo.png"
                    alt="Revista Severina"
                >

            </a>

            <nav class="main-nav">

                <ul>

                    <li>
                        <a href="<?php echo esc_url($severina_home); ?>">
                            Início
                        </a>
                    </li>

                    <li class="menu-dropdown">

                        <button class="dropdown-toggle">
                            Edições
                        </button>

                        <div class="dropdown-menu">

                            <a href="<?php echo esc_url( get_post_type_archive_link('revista')); ?>">
                    Ver todas
                           </a>

                            <?php
                                $revistas_menu = new WP_Query([
                                    'post_type' => 'revista',
                                    'posts_per_page' => -1,
                                    'orderby' => 'date',
                                    'order' => 'DESC'
                                ]);
                            ?>

                            <?php if ($revistas_menu->have_posts()) : ?>

                                <?php while ($revistas_menu->have_posts()) : $revistas_menu->the_post(); ?>

                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>

                                <?php endwhile; ?>

                                <?php wp_reset_postdata(); ?>

                            <?php endif; ?>

                        </div>

                    </li>

                    <li>
                        <a href="<?php echo esc_url($severina_home . '#sobre'); ?>">
                            Sobre
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo esc_url($severina_home . '#equipe'); ?>">
                            Equipe
                        </a>
                    </li>

                </ul>

            </nav>

            <a 
             href="<?php echo esc_url( get_post_type_archive_link('revista')              ); ?>"
             class="primary-button"
            >
             Revistas
            </a>

        </div>

    </div>

</header>

<!-- OVERLAY -->

<div class="menu-overlay"></div>

<!-- SIDEBAR MOBILE -->

<aside class="mobile-sidebar">

    <div class="mobile-sidebar-header">

        <img 
            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/logo.png"
            alt="Revista Severina"
        >

        <button class="close-sidebar">

            <img 
                src="<?php echo SEVERINA_ASSETS_URL; ?>/images/close.png"
                alt="Fechar"
            >

        </button>

    </div>

    <nav class="mobile-sidebar-nav">

        <a href="<?php echo esc_url($severina_home); ?>">
            Início
        </a>

        <div class="mobile-dropdown">

            <button class="mobile-dropdown-toggle">

                Edições

                <img 
                    class="dropdown-angle"
                    src="<?php echo SEVERINA_ASSETS_URL; ?>/images/angle.png" 
                    alt="Abrir menu"
                >

            </button>

            <div class="mobile-dropdown-menu">

                <a href="<?php echo esc_url( get_post_type_archive_link('revista')); ?>">
                    Ver todas
                </a>

                <?php
                    $revistas_mobile = new WP_Query([
                        'post_type' => 'revista',
                        'posts_per_page' => -1,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ]);
                ?>

                <?php if ($revistas_mobile->have_posts()) : ?>

                    <?php while ($revistas_mobile->have_posts()) : $revistas_mobile->the_post(); ?>

                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>

                    <?php endwhile; ?>

                    <?php wp_reset_postdata(); ?>

                <?php endif; ?>

            </div>

        </div>

        <a href="<?php echo esc_url($severina_home . '#sobre'); ?>">
            Sobre
        </a>

        <a href="<?php echo esc_url($severina_home . '#equipe'); ?>">
            Equipe
        </a>

    </nav>

</aside>