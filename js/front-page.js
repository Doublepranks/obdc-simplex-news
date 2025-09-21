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
                const sentinel = feedContainer.querySelector('[data-feed-sentinel]');
                const endpoint = loadMoreButton.dataset.endpoint;
                const defaultText = loadMoreButton.dataset.buttonText || loadMoreButton.textContent;
                const loadingText = loadMoreButton.dataset.loadingText || defaultText;
                const autoLoadLimit = parseInt(loadMoreButton.dataset.autoloadLimit || '0', 10);
                const supportsIntersectionObserver = typeof window !== 'undefined' && 'IntersectionObserver' in window;
                const canUseAutoLoad = supportsIntersectionObserver && sentinel && autoLoadLimit > 0;

                let isLoading = false;
                let autoLoadCount = 0;
                let observer;

                const setButtonState = (state) => {
                        switch ( state ) {
                                case 'loading':
                                        loadMoreButton.disabled = true;
                                        loadMoreButton.setAttribute('aria-disabled', 'true');
                                        loadMoreButton.classList.add('is-loading');
                                        loadMoreButton.classList.remove('is-disabled');
                                        loadMoreButton.textContent = loadingText;
                                        break;
                                case 'disabled':
                                        loadMoreButton.disabled = true;
                                        loadMoreButton.setAttribute('aria-disabled', 'true');
                                        loadMoreButton.classList.add('is-disabled');
                                        loadMoreButton.classList.remove('is-loading');
                                        loadMoreButton.textContent = defaultText;
                                        break;
                                default:
                                        loadMoreButton.disabled = false;
                                        loadMoreButton.removeAttribute('disabled');
                                        loadMoreButton.setAttribute('aria-disabled', 'false');
                                        loadMoreButton.classList.remove('is-disabled');
                                        loadMoreButton.classList.remove('is-loading');
                                        loadMoreButton.textContent = defaultText;
                                        break;
                        }
                };

                const getCurrentPage = () => parseInt(loadMoreButton.dataset.currentPage || '1', 10);
                const getMaxPages = () => parseInt(loadMoreButton.dataset.maxPages || '1', 10);
                const hasMorePages = () => getCurrentPage() < getMaxPages();

                const hideButtonDuringAuto = () => {
                        if ( ! canUseAutoLoad ) {
                                return;
                        }

                        loadMoreButton.classList.add('is-hidden');
                        loadMoreButton.setAttribute('aria-hidden', 'true');
                        loadMoreButton.setAttribute('tabindex', '-1');
                };

                const showButtonAfterAuto = () => {
                        loadMoreButton.classList.remove('is-hidden');
                        loadMoreButton.removeAttribute('aria-hidden');
                        loadMoreButton.removeAttribute('tabindex');
                };

                const initialiseState = () => {
                        if ( ! endpoint || ! hasMorePages() ) {
                                setButtonState('disabled');
                                return false;
                        }

                        setButtonState('default');
                        return true;
                };

                const buildRequestUrl = (nextPage) => {
                        try {
                                const url = new window.URL(endpoint);
                                url.searchParams.set('page', String(nextPage));
                                return url.toString();
                        } catch ( error ) {
                                const separator = endpoint.indexOf('?') === -1 ? '?' : '&';
                                return endpoint + separator + 'page=' + nextPage;
                        }
                };

                const disconnectObserver = () => {
                        if ( observer ) {
                                observer.disconnect();
                                observer = null;
                        }
                };

                const observeSentinel = () => {
                        if ( observer && sentinel ) {
                                observer.observe(sentinel);
                        }
                };

                const loadNextPage = ({ source = 'manual' } = {}) => {
                        if ( isLoading || loadMoreButton.disabled ) {
                                return Promise.resolve(false);
                        }

                        const currentPage = getCurrentPage();
                        const maxPages = getMaxPages();
                        const nextPage = currentPage + 1;

                        if ( nextPage > maxPages ) {
                                setButtonState('disabled');
                                return Promise.resolve(false);
                        }

                        isLoading = true;
                        setButtonState('loading');

                        if ( source === 'auto' ) {
                                hideButtonDuringAuto();
                        }

                        const requestUrl = buildRequestUrl(nextPage);
                        const headers = { Accept: 'application/json' };

                        if ( loadMoreButton.dataset.nonce ) {
                                headers['X-WP-Nonce'] = loadMoreButton.dataset.nonce;
                        }

                        return window
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

                                        if ( data && data.html ) {
                                                if ( postsContainer ) {
                                                        postsContainer.insertAdjacentHTML('beforeend', data.html);
                                                } else {
                                                        loadMoreButton.insertAdjacentHTML('beforebegin', data.html);
                                                }
                                        }

                                        loadMoreButton.dataset.currentPage = String(nextPage);

                                        const morePagesAvailable = hasMorePages();
                                        const hasNewContent = Boolean(data && data.html);

                                        if ( source === 'auto' && hasNewContent ) {
                                                autoLoadCount += 1;
                                        }

                                        if ( ! morePagesAvailable || ! hasNewContent ) {
                                                setButtonState('disabled');
                                        } else if ( source === 'manual' ) {
                                                setButtonState('default');
                                        } else {
                                                setButtonState('default');
                                        }

                                        return true;
                                })
                                .catch((error) => {
                                        // eslint-disable-next-line no-console
                                        console.error('Load more request failed:', error);

                                        if ( source === 'manual' ) {
                                                setButtonState('default');
                                        } else {
                                                showButtonAfterAuto();
                                                setButtonState('default');
                                        }

                                        return false;
                                })
                                .finally(() => {
                                        isLoading = false;
                                });
                };

                if ( ! initialiseState() ) {
                        return;
                }

                loadMoreButton.addEventListener('click', () => {
                        loadNextPage({ source: 'manual' });
                });

                if ( canUseAutoLoad && hasMorePages() ) {
                        hideButtonDuringAuto();

                        observer = new window.IntersectionObserver((entries) => {
                                entries.forEach((entry) => {
                                        if ( ! entry.isIntersecting ) {
                                                return;
                                        }

                                        if ( isLoading || ! hasMorePages() || autoLoadCount >= autoLoadLimit ) {
                                                return;
                                        }

                                        observer.unobserve(entry.target);

                                        loadNextPage({ source: 'auto' }).then((success) => {
                                                if ( ! success ) {
                                                        disconnectObserver();
                                                        showButtonAfterAuto();
                                                        return;
                                                }

                                                if ( ! hasMorePages() || autoLoadCount >= autoLoadLimit ) {
                                                        disconnectObserver();
                                                        showButtonAfterAuto();

                                                        if ( ! hasMorePages() ) {
                                                                setButtonState('disabled');
                                                        }
                                                } else {
                                                        observeSentinel();
                                                }
                                        });
                                });
                        }, { rootMargin: '200px 0px' });

                        observeSentinel();
                } else {
                        showButtonAfterAuto();
                }
        });
})();
