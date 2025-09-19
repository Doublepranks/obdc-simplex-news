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
                const endpoint = loadMoreButton.dataset.endpoint;
                const defaultText = loadMoreButton.dataset.buttonText || loadMoreButton.textContent;
                const loadingText = loadMoreButton.dataset.loadingText || defaultText;
                let isLoading = false;

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

                const hasMorePages = () => {
                        const currentPage = parseInt(loadMoreButton.dataset.currentPage || '1', 10);
                        const maxPages = parseInt(loadMoreButton.dataset.maxPages || '1', 10);

                        return currentPage < maxPages;
                };

                const initialiseState = () => {
                        if ( ! endpoint || ! hasMorePages() ) {
                                disableButton();
                                return false;
                        }

                        enableButton();
                        return true;
                };

                if ( ! initialiseState() ) {
                        return;
                }

                loadMoreButton.addEventListener('click', () => {
                        if ( isLoading || loadMoreButton.disabled ) {
                                return;
                        }

                        const currentPage = parseInt(loadMoreButton.dataset.currentPage || '1', 10);
                        const maxPages = parseInt(loadMoreButton.dataset.maxPages || '1', 10);
                        const nextPage = currentPage + 1;

                        if ( nextPage > maxPages ) {
                                disableButton();
                                return;
                        }

                        isLoading = true;
                        loadMoreButton.disabled = true;
                        loadMoreButton.setAttribute('aria-disabled', 'true');
                        loadMoreButton.classList.add('is-loading');
                        loadMoreButton.textContent = loadingText;

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

                                        if ( data && data.html ) {
                                                if ( postsContainer ) {
                                                        postsContainer.insertAdjacentHTML('beforeend', data.html);
                                                } else {
                                                        loadMoreButton.insertAdjacentHTML('beforebegin', data.html);
                                                }
                                        }

                                        loadMoreButton.dataset.currentPage = String(nextPage);

                                        if ( ! hasMorePages() || ! data || ! data.html ) {
                                                disableButton();
                                        } else {
                                                enableButton();
                                        }
                                })
                                .catch((error) => {
                                        // eslint-disable-next-line no-console
                                        console.error('Load more request failed:', error);
                                        enableButton();
                                })
                                .finally(() => {
                                        loadMoreButton.classList.remove('is-loading');
                                        loadMoreButton.textContent = defaultText;
                                        isLoading = false;
                                });
                });
        });
})();
