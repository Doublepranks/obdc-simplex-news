(function () {
	'use strict';

	const topbar = document.querySelector('[data-topbar]');
	if ( ! topbar ) {
		return;
	}

	const title = topbar.querySelector('[data-live-title]');
	if ( ! title ) {
		return;
	}

	let resizeTimer = null;

	const checkOverflow = () => {
		title.classList.remove('is-overflowing');
		if ( title.scrollWidth > title.clientWidth + 2 ) {
			title.classList.add('is-overflowing');
		}
	};

	const onResize = () => {
		if ( resizeTimer ) {
			window.clearTimeout(resizeTimer);
		}
		resizeTimer = window.setTimeout(checkOverflow, 150);
	};

	checkOverflow();
	window.addEventListener('resize', onResize);
})();
