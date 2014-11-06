//<script>

	elgg.provide('elgg.group_tools.tools');

	elgg.group_tools.tools.init = function () {

		$('.gt-tools-form[data-confirm]').live('submit', function (e) {
			var $form = $(this);
			
			var msg = $(this).data('confirm');
			if (!confirm(msg)) {
				return false;
			}
			
			elgg.action($form.attr('action'), {
				data: $form.serialize()
			});
			
			return false;
		});

		$('#gt-admin-transfer-assume').live('change', function (e) {
			if ($(this).prop('checked') === true) {
				$('#gt-admin-transfer-owner').addClass('hidden');
			} else {
				$('#gt-admin-transfer-owner').removeClass('hidden');
			}
		});
	};

	elgg.register_hook_handler('init', 'system', elgg.group_tools.tools.init);
	