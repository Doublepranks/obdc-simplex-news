// ObDC-simplex-news JavaScript

jQuery(document).ready(function($) {
	// Load more posts via AJAX
	$('.loadmore').on('click', function(e) {
		e.preventDefault();
		var $button = $(this);
		$button.text('Carregando...');
		
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: {
				action: 'obdc_load_more_posts',
				nonce: ajax_object.nonce,
				page: ajax_object.current_page + 1
			},
			success: function(response) {
				if (response.success) {
					$('.feed').append(response.data.html);
					ajax_object.current_page++;
					if (response.data.no_more_posts) {
						$button.hide();
					}
				} else {
					$button.text('Erro ao carregar');
				}
			},
			error: function() {
				$button.text('Erro ao carregar');
			}
		});
	});
});