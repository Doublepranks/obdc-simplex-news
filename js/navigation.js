(function () {
	const drawer = document.querySelector('[data-site-drawer]');
	const toggles = document.querySelectorAll('[data-menu-toggle]');

	if (!drawer || !toggles.length) {
		return;
	}

	const closeTriggers = drawer.querySelectorAll('[data-menu-close]');
	const focusableSelector = 'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])';
	let previousFocused = null;
	let isOpen = false;
	let closeTimer = null;

	const setExpanded = (value) => {
		toggles.forEach((button) => {
			button.setAttribute('aria-expanded', value ? 'true' : 'false');
		});
	};

	const focusFirstItem = () => {
		const focusable = drawer.querySelectorAll(focusableSelector);
		if (focusable.length) {
			focusable[0].focus();
		}
	};

	const handleKeydown = (event) => {
		if (!isOpen) {
			return;
		}

		if (event.key === 'Escape') {
			event.preventDefault();
			closeDrawer();
			return;
		}

		if (event.key !== 'Tab') {
			return;
		}

		const focusable = Array.from(drawer.querySelectorAll(focusableSelector));
		if (!focusable.length) {
			event.preventDefault();
			return;
		}

		const first = focusable[0];
		const last = focusable[focusable.length - 1];

		if (event.shiftKey) {
			if (document.activeElement === first) {
				event.preventDefault();
				last.focus();
			}
			return;
		}

		if (document.activeElement === last) {
			event.preventDefault();
			first.focus();
		}
	};

	const clearCloseTimer = () => {
		if (closeTimer) {
			window.clearTimeout(closeTimer);
			closeTimer = null;
		}
	};

	const openDrawer = () => {
		if (isOpen) {
			return;
		}

		clearCloseTimer();
		previousFocused = document.activeElement;
		drawer.removeAttribute('hidden');
		drawer.classList.remove('is-closing');
		drawer.classList.add('is-open');
		document.body.classList.add('drawer-open');
		setExpanded(true);
		isOpen = true;
		window.requestAnimationFrame(focusFirstItem);
		document.addEventListener('keydown', handleKeydown);
	};

	const finishClose = () => {
		if (isOpen) {
			return;
		}

		clearCloseTimer();
		drawer.setAttribute('hidden', '');
		drawer.classList.remove('is-closing');
	};

	const closeDrawer = () => {
		if (!isOpen) {
			return;
		}

		clearCloseTimer();
		drawer.classList.remove('is-open');
		drawer.classList.add('is-closing');
		document.body.classList.remove('drawer-open');
		setExpanded(false);
		document.removeEventListener('keydown', handleKeydown);
		isOpen = false;
		closeTimer = window.setTimeout(finishClose, 300);

		if (previousFocused && typeof previousFocused.focus === 'function') {
			previousFocused.focus();
		}
	};

	toggles.forEach((button) => {
		button.addEventListener('click', () => {
			if (isOpen) {
				closeDrawer();
				return;
			}

			openDrawer();
		});
	});

	closeTriggers.forEach((trigger) => {
		trigger.addEventListener('click', closeDrawer);
	});

	drawer.addEventListener('transitionend', (event) => {
		if (event.target !== drawer) {
			return;
		}

		finishClose();
	});
})();
