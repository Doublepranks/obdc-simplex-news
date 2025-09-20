(function () {
        const onReady = (callback) => {
                if ( document.readyState !== 'loading' ) {
                        callback();
                        return;
                }

                document.addEventListener('DOMContentLoaded', callback);
        };

        onReady(() => {
                const loadMoreButton = document.querySelector('.loadmore');

                if ( ! loadMoreButton ) {
                        return;
                }

                const feedContainer = loadMoreButton.closest('.feed');

                if ( ! feedContainer ) {
                        return;
                }

                const postsContainer = feedContainer.querySelector('[data-feed-items]');
                let sentinel = feedContainer.querySelector('[data-feed-sentinel]');
                const fallbackPagination = feedContainer.querySelector('[data-feed-pagination-fallback]');
                const endpoint = loadMoreButton.dataset.endpoint;
                const defaultText = loadMoreButton.dataset.buttonText || loadMoreButton.textContent;
                const loadingText = loadMoreButton.dataset.loadingText || defaultText;
                const autoLoadLimit = Math.max(0, parseInt(loadMoreButton.dataset.autoLoadLimit || '0', 10));
                let isLoading = false;
                let autoLoadCount = 0;
                let observer = null;

                if ( ! sentinel ) {
                        sentinel = document.createElement('div');
                        sentinel.setAttribute('data-feed-sentinel', '');
                        sentinel.setAttribute('aria-hidden', 'true');

                        if ( postsContainer ) {
                                postsContainer.appendChild(sentinel);
                        } else {
                                feedContainer.appendChild(sentinel);
                        }
                }

                if ( sentinel && ! sentinel.style.height ) {
                        sentinel.style.height = '1px';
                }

                const hideFallbackPagination = () => {
                        if ( fallbackPagination ) {
                                fallbackPagination.setAttribute('hidden', '');
                                fallbackPagination.setAttribute('aria-hidden', 'true');
                        }
                };

                const disableButton = () => {
                        loadMoreButton.disabled = true;
                        loadMoreButton.setAttribute('aria-disabled', 'true');
                        loadMoreButton.classList.add('is-disabled');
                        loadMoreButton.classList.remove('is-loading');
                        loadMoreButton.textContent = defaultText;
                };

                const enableButton = () => {
                        loadMoreButton.disabled = false;
                        loadMoreButton.removeAttribute('disabled');
                        loadMoreButton.setAttribute('aria-disabled', 'false');
                        loadMoreButton.classList.remove('is-disabled');
                        loadMoreButton.textContent = defaultText;
                };

                const hideButton = () => {
                        loadMoreButton.setAttribute('hidden', '');
                        loadMoreButton.setAttribute('aria-hidden', 'true');
                };

                const showButton = () => {
                        loadMoreButton.removeAttribute('hidden');
                        loadMoreButton.setAttribute('aria-hidden', 'false');
                };

                const hasMorePages = () => {
                        const currentPage = parseInt(loadMoreButton.dataset.currentPage || '1', 10);
                        const maxPages = parseInt(loadMoreButton.dataset.maxPages || '1', 10);

                        return currentPage < maxPages;
                };

                const stopObserver = () => {
                        if ( observer ) {
                                observer.disconnect();
                                observer = null;
                        }
                };

                const loadMore = ({ isAuto = false } = {}) => {
                        if ( isLoading ) {
                                return;
                        }

                        const currentPage = parseInt(loadMoreButton.dataset.currentPage || '1', 10);
                        const maxPages = parseInt(loadMoreButton.dataset.maxPages || '1', 10);
                        const nextPage = currentPage + 1;

                        if ( nextPage > maxPages ) {
                                disableButton();
                                hideButton();
                                stopObserver();
                                return;
                        }

                        if ( isAuto && autoLoadLimit > 0 && autoLoadCount >= autoLoadLimit ) {
                                stopObserver();
                                enableButton();
                                showButton();
                                return;
                        }

                        isLoading = true;

                        if ( ! isAuto ) {
                                loadMoreButton.classList.add('is-loading');
                                loadMoreButton.textContent = loadingText;
                        }

                        loadMoreButton.disabled = true;
                        loadMoreButton.setAttribute('aria-disabled', 'true');

                        let requestUrl;

                        try {
                                const url = new window.URL(endpoint);
                                url.searchParams.set('page', String(nextPage));
                                requestUrl = url.toString();
                        } catch ( error ) {
                                const separator = endpoint.indexOf('?') === -1 ? '?' : '&';
                                requestUrl = endpoint + separator + 'page=' + nextPage;
                        }

                        const headers = { Accept: 'application/json' };

                        if ( loadMoreButton.dataset.nonce ) {
                                headers['X-WP-Nonce'] = loadMoreButton.dataset.nonce;
                        }

                        window
                                .fetch(requestUrl, {
                                        method: 'GET',
                                        headers,
                                        credentials: 'same-origin',
                                })
                                .then((response) => {
                                        if ( ! response.ok ) {
                                                throw new Error('Request failed with status ' + response.status);
                                        }

                                        return response.json();
                                })
                                .then((data) => {
                                        if ( data && typeof data.maxPages !== 'undefined' ) {
                                                loadMoreButton.dataset.maxPages = data.maxPages;
                                        }

                                        const hasHtml = data && typeof data.html === 'string' && data.html.trim() !== '';

                                        if ( hasHtml ) {
                                                if ( postsContainer ) {
                                                        postsContainer.insertAdjacentHTML('beforeend', data.html);
                                                } else {
                                                        loadMoreButton.insertAdjacentHTML('beforebegin', data.html);
                                                }
                                        }

                                        loadMoreButton.dataset.currentPage = String(nextPage);

                                        const morePagesAvailable = hasMorePages();

                                        if ( isAuto && autoLoadLimit > 0 && hasHtml ) {
                                                autoLoadCount += 1;
                                        }

                                        if ( ! hasHtml || ! morePagesAvailable ) {
                                                disableButton();
                                                hideButton();
                                                stopObserver();
                                                return;
                                        }

                                        enableButton();

                                        if ( isAuto && autoLoadLimit > 0 && autoLoadCount < autoLoadLimit ) {
                                                hideButton();
                                                return;
                                        }

                                        if ( isAuto && autoLoadLimit > 0 && autoLoadCount >= autoLoadLimit ) {
                                                stopObserver();
                                        }

                                        showButton();
                                })
                                .catch((error) => {
                                        // eslint-disable-next-line no-console
                                        console.error('Load more request failed:', error);
                                        enableButton();
                                        showButton();
                                        stopObserver();
                                })
                                .finally(() => {
                                        loadMoreButton.classList.remove('is-loading');
                                        loadMoreButton.textContent = defaultText;
                                        isLoading = false;
                                });
                };

                const setupObserver = () => {
                        if ( autoLoadLimit <= 0 ) {
                                showButton();
                                return;
                        }

                        if ( ! sentinel || ! ( 'IntersectionObserver' in window ) ) {
                                showButton();
                                return;
                        }

                        observer = new window.IntersectionObserver(
                                (entries) => {
                                        entries.forEach((entry) => {
                                                if ( entry.isIntersecting ) {
                                                        loadMore({ isAuto: true });
                                                }
                                        });
                                },
                                {
                                        rootMargin: '0px 0px 400px 0px',
                                }
                        );

                        observer.observe(sentinel);
                };

                const initialiseState = () => {
                        hideFallbackPagination();

                        if ( ! endpoint || ! hasMorePages() ) {
                                disableButton();
                                hideButton();
                                stopObserver();
                                return false;
                        }

                        enableButton();

                        if ( autoLoadLimit > 0 ) {
                                hideButton();
                        } else {
                                showButton();
                        }

                        setupObserver();

                        if ( autoLoadLimit <= 0 || ! observer ) {
                                showButton();
                        }

                        return true;
                };

                if ( ! initialiseState() ) {
                        return;
                }

                loadMoreButton.addEventListener('click', () => {
                        if ( isLoading || loadMoreButton.disabled ) {
                                return;
                        }

                        showButton();
                        loadMore({ isAuto: false });
                });
        });
})();
