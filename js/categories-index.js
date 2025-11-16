// Live filter for the Categories Index template
(function($) {
	$(function() {
		var $filter = $('#categories-filter');
		var $cards  = $('.categories-index__card');

		if (!$filter.length || !$cards.length) {
			return;
		}

		var $grid = $('.categories-index__grid');
		var noResultsText = $filter.data('no-results') || 'Nenhuma categoria encontrada.';
		var $emptyMsg = $('<p/>', {
			'class': 'categories-index__empty categories-index__empty--filtered',
			'text': noResultsText
		}).hide();

		if ($grid.length) {
			$grid.after($emptyMsg);
		}

		function normalize(str) {
			str = (str || '').toString().toLowerCase();
			if (str.normalize) {
				str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
			}
			return str;
		}

		$filter.on('input', function() {
			var query = normalize($filter.val());
			var anyVisible = false;

			$cards.each(function() {
				var $card = $(this);
				var name = $card.find('.categories-index__card-name').text();
				var desc = $card.find('.categories-index__card-desc').text();
				var haystack = normalize(name + ' ' + desc);

				if (!query || haystack.indexOf(query) !== -1) {
					$card.show();
					anyVisible = true;
				} else {
					$card.hide();
				}
			});

			if ($emptyMsg.length) {
				if (!anyVisible && query) {
					$emptyMsg.show();
				} else {
					$emptyMsg.hide();
				}
			}
		});
	});
})(jQuery);

