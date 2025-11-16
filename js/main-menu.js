// Enhance main menu accessibility for dropdowns
(function($) {
	$(function() {
		var $menu = $('.main-menu');
		if (!$menu.length) {
			return;
		}

		$menu.find('.menu-item-has-children > a').each(function() {
			var $link = $(this);
			$link.attr({
				'aria-haspopup': 'true',
				'aria-expanded': 'false'
			});
		});

		$menu.on('focusin', '.menu-item-has-children', function() {
			var $link = $(this).children('a').first();
			$link.attr('aria-expanded', 'true');
		});

		$menu.on('focusout', '.menu-item-has-children', function() {
			var $link = $(this).children('a').first();
			// small timeout to allow focus to move within submenu
			setTimeout(function() {
				if (!$link.parent().is(':focus-within')) {
					$link.attr('aria-expanded', 'false');
				}
			}, 50);
		});
	});
})(jQuery);

