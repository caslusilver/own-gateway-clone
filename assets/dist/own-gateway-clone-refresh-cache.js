jQuery(function ($) {
	'use strict';

	function wpNotice(message, type) {
		type = type || 'success';
		var noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
		var notice = $(
			'<div class="notice ' +
				noticeClass +
				' is-dismissible own-gateway-clone-refresh-notice" style="margin:5px 0 2px;padding:1px 12px;"><p>' +
				message +
				'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dispensar</span></button></div>'
		);

		$('.own-gateway-clone-refresh-notice').remove();
		var $target = $('.wp-header-end').length ? $('.wp-header-end') : $('.wrap h1').first();
		if ($target.length) {
			$target.after(notice);
		} else {
			$('.wrap').first().prepend(notice);
		}
	}

	$(document).on('click', '.gu-refresh-cache-btn', function (e) {
		e.preventDefault();

		var btn = $(this);
		var nonce = btn.data('nonce');
		var spinner = btn.find('.spinner');
		var refreshText = btn.find('.gu-refresh-text');

		btn.prop('disabled', true).addClass('disabled');
		spinner.css('visibility', 'visible').addClass('is-active');
		refreshText.text('Atualizando...');

		$.ajax({
			url: GURefreshCache.ajax_url,
			type: 'POST',
			data: { action: 'gu_refresh_cache', _ajax_nonce: nonce },
			success: function (response) {
				if (response && response.success) {
					wpNotice(response.data || 'Cache atualizado!', 'success');
				} else {
					wpNotice((response && response.data) || 'Erro ao atualizar cache.', 'error');
				}
			},
			error: function () {
				wpNotice('Erro ao atualizar cache.', 'error');
			},
			complete: function () {
				btn.prop('disabled', false).removeClass('disabled');
				spinner.css('visibility', 'hidden').removeClass('is-active');
				refreshText.text('Atualizar Cache');
			},
		});
	});
});
