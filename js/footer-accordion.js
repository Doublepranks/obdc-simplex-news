(function () {
	'use strict';

	if ( typeof window.matchMedia !== 'function' ) {
		return;
	}

	var sections = Array.prototype.slice.call( document.querySelectorAll( '[data-footer-accordion]' ) );
	if ( ! sections.length ) {
		return;
	}

	var mql = window.matchMedia( '(max-width: 640px)' );
	var toggleHandlers = new WeakMap();
	var isEnabled = false;

	function setPanelState( section, toggle, panel, expanded, persist ) {
		var isExpanded = !! expanded;
		toggle.setAttribute( 'aria-expanded', isExpanded ? 'true' : 'false' );
		panel.setAttribute( 'aria-hidden', isExpanded ? 'false' : 'true' );

		if ( isExpanded ) {
			panel.removeAttribute( 'hidden' );
		} else {
			panel.setAttribute( 'hidden', '' );
		}

		if ( persist ) {
			section.dataset.footerExpanded = isExpanded ? 'true' : 'false';
		}
	}

	function handleToggle( event ) {
		event.preventDefault();
		var toggle = event.currentTarget;
		var record = toggleHandlers.get( toggle );

		if ( ! record ) {
			return;
		}

		var expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
		setPanelState( record.section, toggle, record.panel, ! expanded, true );
	}

	function enableAccordion() {
		if ( isEnabled ) {
			return;
		}

		sections.forEach( function ( section, index ) {
		var toggle = section.querySelector( '[data-footer-toggle]' );
		var panel = section.querySelector( '[data-footer-panel]' );

		if ( ! toggle || ! panel ) {
			return;
		}

		var stored = section.dataset.footerExpanded;
		var defaultOpenAttr = toggle.getAttribute( 'data-accordion-open' );
		var hasDefaultOpen = typeof defaultOpenAttr === 'string';
		var initialExpanded;

		if ( stored === 'true' ) {
			initialExpanded = true;
		} else if ( stored === 'false' ) {
			initialExpanded = false;
		} else if ( hasDefaultOpen ) {
			initialExpanded = defaultOpenAttr !== 'false';
		} else {
			initialExpanded = false;
		}

		setPanelState( section, toggle, panel, initialExpanded, true );

			var handler = handleToggle.bind( null );
			toggle.addEventListener( 'click', handler );
			toggle.classList.add( 'footer-section-toggle--collapsible' );

			toggleHandlers.set( toggle, {
				handler: handler,
				panel: panel,
				section: section,
			} );
		} );

		isEnabled = true;
	}

	function disableAccordion() {
		if ( ! isEnabled ) {
			sections.forEach( function ( section ) {
				delete section.dataset.footerExpanded;
			} );
			return;
		}

		sections.forEach( function ( section ) {
			var toggle = section.querySelector( '[data-footer-toggle]' );
			var panel = section.querySelector( '[data-footer-panel]' );

			if ( ! toggle || ! panel ) {
				return;
			}

			var record = toggleHandlers.get( toggle );
			if ( record ) {
				toggle.removeEventListener( 'click', record.handler );
				toggleHandlers.delete( toggle );
			}

			setPanelState( section, toggle, panel, true, false );
			delete section.dataset.footerExpanded;
			toggle.classList.remove( 'footer-section-toggle--collapsible' );
		} );

		isEnabled = false;
	}

	function handleBreakpoint( event ) {
		if ( event.matches ) {
			enableAccordion();
		} else {
			disableAccordion();
		}
	}

	if ( mql.matches ) {
		enableAccordion();
	} else {
		disableAccordion();
	}

	if ( typeof mql.addEventListener === 'function' ) {
		mql.addEventListener( 'change', handleBreakpoint );
	} else {
		mql.addListener( handleBreakpoint );
	}
}());
