(function () {
    'use strict';

    // Skip for logged-in subscribers (PHP adds this body class)
    if (document.body.classList.contains('obdc-ad-free')) {
        return;
    }

    // Create bait element - common ad classes
    var bait = document.createElement('div');
    bait.className = 'ad-banner ads adsbox doubleclick ad-placement';
    bait.style.cssText = 'position:absolute;left:-9999px;height:1px;width:1px;';
    document.body.appendChild(bait);

    // Check after short delay to allow protection to kick in
    // Check after short delay to allow protection to kick in
    setTimeout(function () {
        var blocked = false;

        // Check 1: Bait element styling
        if (!bait || bait.offsetHeight === 0 || bait.clientHeight === 0 || !document.body.contains(bait)) {
            blocked = true;
        }

        // Check 2: Bait script execution (window.obdc_can_run_ads should be true)
        if (typeof window.obdc_can_run_ads === 'undefined') {
            blocked = true;
        }

        // Log for debugging
        if (blocked) {
            console.log('[ObDC] AdBlock detected via: ' +
                (typeof window.obdc_can_run_ads === 'undefined' ? 'Script Block' : 'Element Hiding')
            );
            showAdBlockPopup();
        }

        // Cleanup
        if (bait && bait.parentNode) {
            bait.parentNode.removeChild(bait);
        }
    }, 600); // Increased delay to ensure ads.js has time to load (or fail)

    function showAdBlockPopup() {
        // Check if already dismissed this session
        if (sessionStorage.getItem('obdc_adblock_dismissed')) {
            return;
        }

        // Analytics hook opportunity:
        // if (typeof gtag === 'function') { gtag('event', 'adblock_detected'); }
        console.log('[ObDC] AdBlock detected');

        var popup = document.createElement('div');
        popup.className = 'adblock-popup';
        popup.innerHTML = `
            <div class="adblock-popup__overlay"></div>
            <div class="adblock-popup__dialog" role="dialog" aria-modal="true" aria-labelledby="adblock-title">
                <h2 id="adblock-title" class="adblock-popup__title">Apoiadores mantêm o jornalismo vivo</h2>
                <div class="adblock-popup__content">
                    <p class="adblock-popup__message">
                        Detectamos que você está usando um bloqueador de anúncios. 
                        Entendemos a escolha, mas a publicidade é essencial para manter o 
                        <strong>O Brasil de Cima</strong> operando com qualidade.
                    </p>
                    <p class="adblock-popup__message">
                        Considere desativar para nosso site ou, para uma experiência 
                        totalmente livre de anúncios, torne-se um assinante.
                    </p>
                </div>
                <div class="adblock-popup__actions">
                    <button type="button" class="adblock-popup__btn adblock-popup__btn--primary" data-adblock-close>
                        Entendi, continuar
                    </button>
                    <a href="/register" class="adblock-popup__link">
                        Assinar agora &rarr;
                    </a>
                </div>
            </div>
        `;

        document.body.appendChild(popup);
        document.body.style.overflow = 'hidden';

        function closePopup() {
            sessionStorage.setItem('obdc_adblock_dismissed', '1');
            // Analytics hook opportunity:
            // if (typeof gtag === 'function') { gtag('event', 'adblock_popup_dismissed'); }

            // Fade out effect
            popup.style.opacity = '0';
            setTimeout(() => {
                if (popup.parentNode) {
                    document.body.removeChild(popup);
                }
                document.body.style.overflow = '';
            }, 300);
        }

        // Close handlers
        var closeBtn = popup.querySelector('[data-adblock-close]');
        if (closeBtn) closeBtn.addEventListener('click', closePopup);

        var overlay = popup.querySelector('.adblock-popup__overlay');
        if (overlay) overlay.addEventListener('click', closePopup);
    }
})();
