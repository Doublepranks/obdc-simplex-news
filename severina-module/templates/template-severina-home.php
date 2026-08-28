<?php
/*
Template Name: Home Revista Severina
*/
?>

<?php

$severina_assets = get_template_directory_uri()
. '/severina-module/assets';
?>

<?php include __DIR__ . '/../partials/header-severina.php'; ?>

<main class="home-severina">

    <!-- HERO -->

    <section class="hero-severina">

        <div class="container">

            <div class="hero-grid">

                <div class="hero-content">

                    <span class="rv-span">
                        Revista Digital
                    </span>

                    <h1>
                        Severina
                    </h1>

                    <p>
                        Narrativas, cultura, política e profundidade editorial
                        em uma experiência digital
                    </p>

                    <a 
                        href="<?php echo home_url('/revista'); ?>"
                        class="primary-button"
                    >
                        Explorar Edições
                    </a>

                </div>

                <div class="hero-image">

                    <img 
                        src="<?php echo SEVERINA_ASSETS_URL; ?>/images/hero-revista-1-compress.png"
                        alt="Revista Severina"
                    >

                </div>

            </div>

        </div>

    </section>

    <!-- MARQUEE -->

    <section class="marquee-section">

        <div class="marquee-track">

            <?php for($i = 0; $i < 20; $i++) : ?>

                <span>
                    Revista Severina <span id="bullet">•</span>
                </span>

            <?php endfor; ?>

        </div>

    </section>

    <!-- ÚLTIMAS EDIÇÕES -->

    <section class="ultimas-edicoes">

        <div class="container">

            <div class="section-header">

                <h2>
                    Últimas edições
                </h2>

            </div>

            <div class="edicoes-grid">

                <?php
                    $revistas = new WP_Query([
                        'post_type' => 'revista',
                        'posts_per_page' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ]);
                ?>

                <?php if($revistas->have_posts()) : ?>

                    <?php while($revistas->have_posts()) : $revistas->the_post(); ?>

                        <?php
                            $capa = get_field('capa_revista');
                        ?>

                        <article class="edicao-card fade-up">

                            <a href="<?php the_permalink(); ?>">

                                <?php if($capa) : ?>

                                    <img 
                                        src="<?php echo esc_url($capa['url']); ?>"
                                        alt="<?php the_title(); ?>"
                                    >

                                <?php endif; ?>

                            </a>

                            <h3>
                                <?php the_title(); ?>
                            </h3>

                        </article>

                    <?php endwhile; ?>

                    <?php wp_reset_postdata(); ?>

                <?php endif; ?>

            </div>

            <div class="center-button">

                <a 
                    href="<?php echo home_url('/revista'); ?>"
                    class="primary-button"
                >
                    Ver todas as edições
                </a>

            </div>

        </div>

    </section>

    <!-- SOBRE -->

    <section 
        class="sobre-section"
        id="sobre"
        style="
            background-image: url('<?php echo SEVERINA_ASSETS_URL; ?>/images/bg-sobre.png');
        "
    >

        <div class="container" id="sobre-container">

            <div class="sobre-content fade-up">

                <h2>
                    Sobre a Revista Severina
                </h2>

                <p>
                    O Brasil de Cima surgiu do desejo que três amigos inquietos tinham de colocar o Norte e o Nordeste do Brasil no mapa. <br>
                    Logo, mais e mais amigos foram se juntando ao projeto. Aqueles que estão conosco até hoje e aos que, por qualquer motivo, estão longe agora, saibam: nós amamos o que fizemos juntos. <br>
                    A Revista Severina é uma coleção de tudo o que temos de melhor.
                </p>

            </div>

            <div class="sobre-content fade-up" id="sv-sobre-img">

                <img 
                    src="<?php echo SEVERINA_ASSETS_URL; ?>/images/severina-3d.png"
                     alt="Revista Severina"
                >

            </div>

        </div>

    </section>

    <!-- EQUIPE -->

    <section 
        class="equipe-section"
        id="equipe"
        style="
            background-image: url('<?php echo SEVERINA_ASSETS_URL; ?>/images/bg-sobre.png');
        "
    >

        <div class="container">

            <div class="section-header white">

                <h2>
                    Equipe Editorial
                </h2>

                <p>
                    As mentes por trás da Revista Severina
                </p>

            </div>

            <div class="equipe-grid">

        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('Roberta.jpg'); ?>"
                    alt="Roberta da Cruz"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        Roberta da Cruz
                    </h3>

                    <p>
                        Coordenadora
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/robertadacruzmbl?igsh=MWdvZGtrbzEzOHZucg=="
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>

                    <a 
                        href="https://x.com/robertadombl?s=11"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/x-black.png"
                            alt="X" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>

        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('Sam Pantoja.webp'); ?>"
                    alt="Sam Pantoja"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        Sam Pantoja
                    </h3>

                    <p>
                        Diretor
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/sampantojapa?igsh=MXJ3YjhzZjVsbTNsdQ=="
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>

                    <a 
                        href="https://x.com/sampantojapa?s=11"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/x-black.png"
                            alt="X" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>

        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('jose-lucas.jpeg'); ?>"
                    alt="José Lucas Rodrigues"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        José Lucas Rodrigues
                    </h3>

                    <p>
                        Diagramador
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/ze_sb?igsh=cXN2YjFqMGV6OTEz&utm_source=qr"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>

                    <a 
                        href="https://x.com/joselucasskt?s=21"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/x-black.png"
                            alt="X" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>
        
        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('Lucas-Mariano.webp'); ?>"
                    alt="Lucas Mariano"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        Lucas Mariano
                    </h3>

                    <p>
                        Autor
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/revistaseverina?igsh=eHl2cXQzcXZqaHRi"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>
        
        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('cris-navarro.jpeg'); ?>"
                    alt="Cris Navarro"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        Cris Navarro
                    </h3>

                    <p>
                        Autora
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/revistaseverina?igsh=eHl2cXQzcXZqaHRi"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>

        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('joao-garcia.webp'); ?>"
                    alt="João Garcia"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        João Garcia
                    </h3>

                    <p>
                        Autor
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/revistaseverina?igsh=eHl2cXQzcXZqaHRi"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>   

        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('John.jpg'); ?>"
                    alt="John Robert"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        John Robert
                    </h3>

                    <p>
                        Autor
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/johnrobertpa?igsh=dTd4eGoxaGQzc29t"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>   

        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('aureliano-duarte-2.jpeg'); ?>"
                    alt="Aureliano Duarte"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        Aureliano Duarte
                    </h3>

                    <p>
                        Autor
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/revistaseverina?igsh=eHl2cXQzcXZqaHRi"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>   

        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('Gabriel Lacerda.jpeg'); ?>"
                    alt="Gabriel Lacerda"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        Gabriel Lacerda
                    </h3>

                    <p>
                        Autor
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/glacerdasa?igsh=dWdsNWxzbXJvamFo&utm_source=qr"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>

                    <a 
                        href="https://x.com/1838dc?s=21&t=jMrnz2fy1YgcAonW7zr6GA"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/x-black.png"
                            alt="X" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>   

        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('Max Avelar.jpeg'); ?>"
                    alt="Max Avelar"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        Max Avelar
                    </h3>

                    <p>
                        Autor
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/revistaseverina?igsh=eHl2cXQzcXZqaHRi"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>   

        <article class="membro-card fade-up">

            <div class="membro-image">

                <img 
                    src="<?php echo severina_foto_autor('bruno-macedo.jpeg'); ?>"
                    alt="Bruno Macêdo"
                >

            </div>

            <div class="membro-info">

                <div class="membro-text">

                    <h3>
                        Bruno Macêdo
                    </h3>

                    <p>
                        Autor
                    </p>

                </div>

                <div class="membro-socials">

                    <a 
                        href="https://www.instagram.com/revistaseverina?igsh=eHl2cXQzcXZqaHRi"
                        target="_blank"
                        >

                        <img 
                            src="<?php echo SEVERINA_ASSETS_URL; ?>/images/instagram-black.png"
                            alt="Instagram" class="social-icons"
                        >

                    </a>


                </div>

            </div>

        </article>   

    </div>

    </div>

</section>
</main>

<?php include __DIR__ . '/../partials/footer-severina.php'; ?>