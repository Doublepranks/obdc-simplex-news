<?php include __DIR__ . '/../partials/header-severina.php'; ?>

<?php
    $capa = get_field('capa_revista');
    $resumo = get_field('resumo_revista');
    $pdf = get_field('pdf_revista');
    $subtitulo = get_field('subtitulo');
?>

<main class="single-revista-page">

    <section class="single-revista-hero">

        <div class="container">

            <div class="single-revista-grid">

                <div class="single-revista-cover fade-up">

                    <?php if ($capa) : ?>

                        <img 
                            src="<?php echo esc_url($capa['url']); ?>" 
                            alt="<?php the_title(); ?>"
                        >

                    <?php endif; ?>

                </div>

                <div class="single-revista-content fade-up">

                    <span class="rv-span">
                        Revista Digital
                    </span>

                    <h1>
                        <?php the_title(); ?>
                    </h1>

                    <?php if ($subtitulo) : ?>

                        <h3>
                            <?php echo esc_html($subtitulo); ?>
                        </h3>

                    <?php endif; ?>

                    <?php if ($resumo) : ?>

                        <p>
                            <?php echo esc_html($resumo); ?>
                        </p>

                    <?php endif; ?>

                    <div class="single-revista-buttons">

                        <?php if ($pdf) : ?>

                            <a 
                                href="?ler=true"
                                class="primary-button btn-read-revista"
                            >
                                Ler a revista
                            </a>

                            <a 
                                href="<?php echo esc_url($pdf['url']); ?>"
                                download
                                class="secondary-button btn-download-pdf"
                            >
                                Baixar PDF
                            </a>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <?php if ($pdf && isset($_GET['ler'])) : ?>

        <section class="revista-reader">

            <div class="container">

                <div 
                    id="pdf-viewer"
                    data-pdf="<?php echo esc_url($pdf['url']); ?>"
                ></div>

            </div>

        </section>

    <?php endif; ?>

</main>

<?php include __DIR__ . '/../partials/footer-severina.php'; ?>