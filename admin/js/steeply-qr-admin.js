(function( $ ) {
	'use strict';

	$(document).ready(function () {

		$('#sqr_generate_all').click(function () {

			$(this).text('Generating QR`s... Wait...');

			$.ajax({
				type: 'GET',
				url: '/wp-admin/admin-ajax.php',
				data: {
					action: 'sqr_generate_all',
					sqr_generate_all: true
				},
				success: function(response){
					if(response.success) {
						$('#sqr_generate_all').text('QR`s Generated!')
					}
				}
			});

		});

		$('#sqr_generate_manual').click(function () {

			let post_type = $('#sqr_manual_post_type').val();

			if (!post_type || post_type.length < 3) {
				return;
			}

			$(this).text('Generating QR`s... Wait...');

			$.ajax({
				type: 'GET',
				url: '/wp-admin/admin-ajax.php',
				data: {
					action: 'sqr_generate_manual',
					sqr_generate_manual: true,
					post_type: post_type
				},
				success: function(response){
					if(response.success) {
						$('#sqr_generate_manual').text('QR`s Generated!')
					}
				}
			});

		});

		$('#sqr_regenerate_one').click(function () {

			let sqr_post_id = $(this).data('sqr_post_id');

			$(this).text('Regenerating QR...');

			$.ajax({
				type: 'GET',
				url: '/wp-admin/admin-ajax.php',
				data: {
					action: 'sqr_regenerate_one',
					sqr_post_id: sqr_post_id
				},
				success: function(response){
					if(response.success) {
						$('#sqr_regenerate_one').text('QR Updated! Update Page!')
					}
				}
			});

		});

	});

})( jQuery );
