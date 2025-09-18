// Customizer preview script for ObDC-simplex-news
( function( $ ) {
	// Live preview for live status toggle
	wp.customize( 'obdc_simplex_news_live_status', function( value ) {
		value.bind( function( to ) {
			if ( to === 'on' ) {
				$( '.topbar' ).show();
			} else {
				$( '.topbar' ).hide();
			}
		} );
	} );

	// Live preview for live text
	wp.customize( 'obdc_simplex_news_live_text', function( value ) {
		value.bind( function( to ) {
			if ( to && to.length > 0 ) {
				$( '.topbar .ticker span' ).not( ':first-child' ).text( to );
			} else {
				$( '.topbar .ticker span' ).not( ':first-child' ).text( '' );
			}
		} );
	} );

	// Live preview for CNPJ and city
	wp.customize( 'obdc_simplex_news_cnpj', function( value ) {
		value.bind( function( to ) {
			if ( to && to.length > 0 ) {
				$( '.footer-bottom p:last-of-type' ).text( to + ' • ' + $( '.footer-bottom p:last-of-type' ).text().split(' • ')[1] );
			} else {
				$( '.footer-bottom p:last-of-type' ).text( '• ' + $( '.footer-bottom p:last-of-type' ).text().split(' • ')[1] );
			}
		} );
	} );

	wp.customize( 'obdc_simplex_news_city', function( value ) {
		value.bind( function( to ) {
			if ( to && to.length > 0 ) {
				$( '.footer-bottom p:last-of-type' ).text( $( '.footer-bottom p:last-of-type' ).text().split(' • ')[0] + ' • ' + to );
			} else {
				$( '.footer-bottom p:last-of-type' ).text( $( '.footer-bottom p:last-of-type' ).text().split(' • ')[0] + ' • ' );
			}
		} );
	} );
} )( jQuery );