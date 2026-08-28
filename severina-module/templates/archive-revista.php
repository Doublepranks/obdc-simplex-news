<?php include __DIR__ . '/../partials/header-severina.php'; ?>

<main class="archive-revista-page">

    <section class="archive-hero">

        <div class="container">

            <div class="archive-heading">

                <span class="rv-span">
                    Acervo Digital
                </span>

                <h1>
                    Todas as Edições
                </h1>

            </div>

        </div>

    </section>

    <section class="archive-grid-section">

        <div class="container">

            <div class="archive-grid">

                <?php if(have_posts()) : ?>

                    <?php while(have_posts()) : the_post(); ?>

                        <?php
                            $capa = get_field('capa_revista');
                        ?>

                        <article class="archive-card fade-up">

                            <a 
                                href="<?php the_permalink(); ?>"
                                class="btn-open-edition"
                            >

                                <?php if($capa) : ?>

                                    <img 
                                        src="<?php echo esc_url($capa['url']); ?>"
                                        alt="<?php the_title(); ?>"
                                    >

                                <?php endif; ?>

                            </a>

                            <h2>
                                <?php the_title(); ?>
                            </h2>

                        </article>

                    <?php endwhile; ?>

                <?php endif; ?>

            </div>

        </div>

    </section>

</main>

<?php include __DIR__ . '/../partials/footer-severina.php'; ?>