(function () {
	'use strict';

	var MOBILE_UA_REGEX = /(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini)/i;

	function isMobile() {
		return MOBILE_UA_REGEX.test( window.navigator.userAgent || '' );
	}

	function fallbackCopy( text ) {
		if ( ! text ) {
			return false;
		}

		var textarea = document.createElement( 'textarea' );
		textarea.value = text;
		textarea.setAttribute( 'readonly', '' );
		textarea.style.position = 'fixed';
		textarea.style.top = '-9999px';
		document.body.appendChild( textarea );

		var successful = false;

		try {
			textarea.focus();
			textarea.select();
			successful = document.execCommand( 'copy' );
		} catch ( err ) {
			successful = false;
		}

		document.body.removeChild( textarea );

		return successful;
	}

	function attemptCopy( text ) {
		if ( ! text ) {
			return Promise.resolve( false );
		}

		if ( navigator.clipboard && typeof navigator.clipboard.writeText === 'function' ) {
			return navigator.clipboard.writeText( text ).then( function () {
				return true;
			} ).catch( function () {
				return fallbackCopy( text );
			} );
		}

		return Promise.resolve( fallbackCopy( text ) );
	}

	function updateStatusMessage( element, message ) {
		if ( ! element ) {
			return;
		}

		element.textContent = message || '';
	}

	function setupNativeShare() {
		var shareButton = document.querySelector( '.single-share-inline__button--native-share' );

		if ( ! shareButton ) {
			return;
		}

		var statusElement = document.querySelector( '.single-share-inline__feedback' );
		var successMessage = shareButton.getAttribute( 'data-share-success' ) || '';
		var errorMessage = shareButton.getAttribute( 'data-share-error' ) || '';
		var cancelMessage = shareButton.getAttribute( 'data-share-cancel' ) || '';
		var shareTitle = shareButton.getAttribute( 'data-share-title' ) || '';
		var shareText = shareButton.getAttribute( 'data-share-text' ) || '';
		var shareUrl = shareButton.getAttribute( 'data-share-url' ) || '';

		shareButton.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			updateStatusMessage( statusElement, '' );
			shareButton.dataset.shareState = '';

			var fallbackText = shareText || ( shareTitle ? shareTitle + ' ' + shareUrl : shareUrl );

			var handleCopyFallback = function () {
				return attemptCopy( fallbackText ).then( function ( copied ) {
					if ( copied ) {
						shareButton.dataset.shareState = 'copied';
						if ( successMessage ) {
							updateStatusMessage( statusElement, successMessage );
						}
					} else {
						shareButton.dataset.shareState = 'error';
						if ( errorMessage ) {
							updateStatusMessage( statusElement, errorMessage );
						}
					}
				} );
			};

			if ( navigator.share && typeof navigator.share === 'function' ) {
				var shareData = {};

				if ( shareTitle ) {
					shareData.title = shareTitle;
				}
				if ( shareText ) {
					shareData.text = shareText;
				}
				if ( shareUrl ) {
					shareData.url = shareUrl;
				}

				navigator.share( shareData ).then( function () {
					shareButton.dataset.shareState = 'shared';
					if ( successMessage ) {
						updateStatusMessage( statusElement, successMessage );
					}
				} ).catch( function ( err ) {
					if ( err && err.name === 'AbortError' ) {
						if ( cancelMessage ) {
							updateStatusMessage( statusElement, cancelMessage );
						}
						return;
					}
					handleCopyFallback();
				} );

				return;
			}

			handleCopyFallback();
		} );
	}

	function setupInstagramCopy() {
		var instagramButton = document.querySelector( '.single-share-inline__button--instagram' );

		if ( ! instagramButton ) {
			return;
		}

		if ( isMobile() ) {
			return;
		}

		var shareText = instagramButton.getAttribute( 'data-share-text' );

		if ( ! shareText ) {
			return;
		}

		var statusElement = document.querySelector( '.single-share-inline__feedback' );
		var successMessage = instagramButton.getAttribute( 'data-copy-success' ) || '';
		var errorMessage = instagramButton.getAttribute( 'data-copy-error' ) || '';

		instagramButton.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			updateStatusMessage( statusElement, '' );
			instagramButton.dataset.copyState = '';

			attemptCopy( shareText ).then( function ( copied ) {
				if ( copied ) {
					instagramButton.dataset.copyState = 'copied';
					if ( successMessage ) {
						updateStatusMessage( statusElement, successMessage );
					}
				} else {
					instagramButton.dataset.copyState = 'error';
					if ( errorMessage ) {
						updateStatusMessage( statusElement, errorMessage );
					}
				}
			} );
		} );
	}

	function initShareEnhancements() {
		setupNativeShare();
		setupInstagramCopy();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initShareEnhancements );
	} else {
		initShareEnhancements();
	}
}());
