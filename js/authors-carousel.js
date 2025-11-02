/* global window, document */
(function () {
	'use strict';

	var carousel = document.querySelector( '[data-authors-carousel]' );
	if ( ! carousel ) {
		return;
	}

	var viewport = carousel.querySelector( '[data-authors-viewport]' );
	var track = carousel.querySelector( '[data-authors-track]' );
	var prevButton = carousel.querySelector( '[data-authors-prev]' );
	var nextButton = carousel.querySelector( '[data-authors-next]' );
	var cards = Array.prototype.slice.call( carousel.querySelectorAll( '[data-authors-card]' ) );

	if ( ! viewport || ! track || cards.length === 0 ) {
		if ( prevButton ) {
			prevButton.setAttribute( 'hidden', 'hidden' );
		}
		if ( nextButton ) {
			nextButton.setAttribute( 'hidden', 'hidden' );
		}
		return;
	}

	var desktopMedia = window.matchMedia( '(min-width: 1024px)' );
	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	var isDesktop = desktopMedia.matches;
	var currentPage = 0;
	var cardsPerPage = 1;
	var totalPages = 1;
	var animationDuration = reduceMotion.matches ? 0 : 400;

	function updateMetrics() {
		if ( ! isDesktop ) {
			return;
		}

		if ( cards.length === 0 ) {
			cardsPerPage = 1;
			totalPages = 1;
			return;
		}

		var firstCard = cards[0].getBoundingClientRect();
		var viewportWidth = viewport.getBoundingClientRect().width;
		var columnGap = 0;

		if ( typeof window.getComputedStyle === 'function' ) {
			var trackStyles = window.getComputedStyle( track );
			columnGap = parseFloat( trackStyles.columnGap || trackStyles.gridColumnGap || 0 );
		}

		var effectiveCardWidth = firstCard.width + columnGap;
		var cardsPerRow = Math.max( 1, Math.floor( viewportWidth / effectiveCardWidth ) );
		var rows = 1;
		cardsPerPage = Math.max( 1, cardsPerRow * rows );
		totalPages = Math.max( 1, Math.ceil( cards.length / cardsPerPage ) );
		currentPage = Math.min( currentPage, totalPages - 1 );
	}

	function updateButtons() {
		var canGoPrev = currentPage > 0;
		var canGoNext = currentPage < totalPages - 1;

		if ( prevButton ) {
			prevButton.disabled = ! canGoPrev;
			prevButton.setAttribute( 'aria-disabled', canGoPrev ? 'false' : 'true' );
		}

		if ( nextButton ) {
			nextButton.disabled = ! canGoNext;
			nextButton.setAttribute( 'aria-disabled', canGoNext ? 'false' : 'true' );
		}

		updateShadows();
	}

	function updateShadows() {
		var atStart = currentPage === 0;
		var atEnd = currentPage >= totalPages - 1;
		if ( viewport ) {
			if ( atStart ) {
				viewport.classList.add( 'is-at-start' );
			} else {
				viewport.classList.remove( 'is-at-start' );
			}
			if ( atEnd ) {
				viewport.classList.add( 'is-at-end' );
			} else {
				viewport.classList.remove( 'is-at-end' );
			}
		}
	}

	function emitChangeEvent() {
		var event = new CustomEvent( 'authorsCarousel:changed', {
			detail: {
				currentPage: currentPage,
				totalPages: totalPages,
			},
		} );
		carousel.dispatchEvent( event );
	}

	function goToPage( pageIndex ) {
		if ( ! isDesktop ) {
			return;
		}

		var newIndex = Math.max( 0, Math.min( pageIndex, totalPages - 1 ) );
		currentPage = newIndex;

		var offset = currentPage * cardsPerPage;
		var targetCard = cards[ offset ];

		if ( targetCard ) {
			var offsetLeft = targetCard.offsetLeft;
			var maxOffset = Math.max( 0, track.scrollWidth - viewport.clientWidth );
			var translate = Math.min( offsetLeft, maxOffset );

			if ( reduceMotion.matches ) {
				track.style.transitionDuration = '0ms';
			} else {
				track.style.transitionDuration = animationDuration + 'ms';
			}

			track.style.transform = 'translateX(' + ( -1 * translate ) + 'px)';
		}

		updateButtons();
		emitChangeEvent();
	}

	function handlePrevClick() {
		goToPage( currentPage - 1 );
	}

	function handleNextClick() {
		goToPage( currentPage + 1 );
	}

	function enableDesktopMode() {
		isDesktop = true;
		track.style.removeProperty( 'overflow' );
		track.style.removeProperty( 'overflowX' );
		track.style.removeProperty( 'scrollSnapType' );
		track.style.removeProperty( 'scrollBehavior' );
		track.style.transform = 'translateX(0)';

		viewport.style.overflow = 'hidden';
		viewport.style.removeProperty( 'overflowX' );
		viewport.style.removeProperty( 'scrollSnapType' );
		viewport.style.removeProperty( 'scrollBehavior' );
		viewport.scrollLeft = 0;

		updateMetrics();
		updateButtons();
		goToPage( currentPage );
		updateShadows();

		if ( prevButton ) {
			prevButton.removeAttribute( 'hidden' );
			prevButton.addEventListener( 'click', handlePrevClick );
		}
		if ( nextButton ) {
			nextButton.removeAttribute( 'hidden' );
			nextButton.addEventListener( 'click', handleNextClick );
		}
	}

	function enableMobileMode() {
		isDesktop = false;
		track.style.transform = 'none';
		track.style.transitionDuration = '0ms';
		track.style.removeProperty( 'overflow' );
		track.style.removeProperty( 'overflowX' );
		track.style.removeProperty( 'scrollSnapType' );
		track.style.removeProperty( 'scrollBehavior' );

		viewport.style.overflowX = 'auto';
		viewport.style.scrollSnapType = 'x mandatory';
		viewport.style.scrollBehavior = reduceMotion.matches ? 'auto' : 'smooth';

		if ( prevButton ) {
			prevButton.setAttribute( 'hidden', 'hidden' );
			prevButton.removeEventListener( 'click', handlePrevClick );
		}
		if ( nextButton ) {
			nextButton.setAttribute( 'hidden', 'hidden' );
			nextButton.removeEventListener( 'click', handleNextClick );
		}

		updateButtons();
		emitChangeEvent();
		updateShadows();
	}

	function handleBreakpointChange( event ) {
		if ( event.matches ) {
			enableDesktopMode();
		} else {
			enableMobileMode();
		}
	}

	function handleResize() {
		if ( ! isDesktop ) {
			return;
		}
		updateMetrics();
		goToPage( currentPage );
	}

	// Initialisation.
	if ( desktopMedia.matches ) {
		enableDesktopMode();
	} else {
		enableMobileMode();
	}

	if ( typeof desktopMedia.addEventListener === 'function' ) {
		desktopMedia.addEventListener( 'change', handleBreakpointChange );
	} else {
		desktopMedia.addListener( handleBreakpointChange );
	}

	window.addEventListener( 'resize', handleResize );
}());
