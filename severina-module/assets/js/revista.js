function trackEvent(eventName, data = {}) {

    if (typeof gtag === 'function') {
        gtag('event', eventName, data);
    }

}

document.addEventListener('DOMContentLoaded', async () => {

    /* =====================================
       HEADER
    ===================================== */

    const header = document.querySelector('.site-header');

    if (header) {

        const updateHeader = () => {

            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }

        };

        updateHeader();

        window.addEventListener('scroll', updateHeader);

    }

    /* =====================================
       ANIMAÇÕES
    ===================================== */

    const observer = new IntersectionObserver((entries) => {

        entries.forEach(entry => {

            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }

        });

    }, {
        threshold: 0.2
    });

    document.querySelectorAll('.fade-up').forEach(el => {
        observer.observe(el);
    });

    /* =====================================
       TRACKING DE INTERAÇÕES
    ===================================== */

    document
        .querySelectorAll('.btn-read-revista')
        .forEach(button => {

            button.addEventListener('click', () => {

                trackEvent('ler_revista', {
                    revista: window.location.pathname
                });

            });

        });

    document
        .querySelectorAll('.btn-download-pdf')
        .forEach(button => {

            button.addEventListener('click', () => {

                trackEvent('download_pdf', {
                    revista: window.location.pathname
                });

            });

        });

    document
        .querySelectorAll('.btn-open-edition')
        .forEach(button => {

            button.addEventListener('click', () => {

                trackEvent('abrir_edicao', {
                    destino: button.href
                });

            });

        });

    /* =====================================
       PDF
    ===================================== */

    const viewer = document.getElementById('pdf-viewer');

    if (!viewer) {
        return;
    }

    try {

        const pdfUrl = viewer.dataset.pdf;

        pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdf = await pdfjsLib.getDocument(pdfUrl).promise;

        trackEvent('abrir_revista', {
            revista: window.location.pathname
        });

        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {

            const page = await pdf.getPage(pageNum);

            const scale = 1.4;

            const viewport = page.getViewport({ scale });

            const canvas = document.createElement('canvas');

            canvas.dataset.page = pageNum;

            const context = canvas.getContext('2d');

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            viewer.appendChild(canvas);

            await page.render({
                canvasContext: context,
                viewport: viewport
            }).promise;

        }

        const paginasLidas = new Set();

        const leituraObserver = new IntersectionObserver((entries) => {

            entries.forEach(entry => {

                if (!entry.isIntersecting) return;

                const pagina = entry.target.dataset.page;

                if (paginasLidas.has(pagina)) return;

                paginasLidas.add(pagina);

                trackEvent('pagina_lida', {
                    pagina: Number(pagina),
                    revista: window.location.pathname
                });

                console.log(`Página ${pagina} lida`);

            });

        }, {
            threshold: 0.5
        });

        document
            .querySelectorAll('#pdf-viewer canvas')
            .forEach(canvas => leituraObserver.observe(canvas));

    } catch (error) {

        console.error('Erro ao carregar PDF:', error);

    }

});