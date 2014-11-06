//<script>

	elgg.provide('elgg.group_tools.tools');

	elgg.group_tools.tools.init = function () {

		$('.gt-tools-form').live('submit', function (e) {
			var $form = $(this);
			if ($form.is('[data-confirm]')) {
				var msg = $(this).data('confirm');
				if (!confirm(msg)) {
					return false;
				}
			}
			elgg.action($form.attr('action'), {
				data: $form.serialize(),
				beforeSend: function () {
					$form.find('input[type="submit"]').each(function () {
						var val = $(this).val();
						$(this).prop('disabled', true).addClass('elgg-state-disabled').data('before', val).val(elgg.echo('group_tools:forms:saving'));
					});
				},
				success: function (response) {
					if (response.output && response.output.counters) {
						$.each(response.output.counters, function(key, value) {
							$('[data-counter][data-count="' + key +'"]').text(value);
						});
					}
				},
				complete: function () {
					$form.find('input[type="submit"]').each(function () {
						var val = $(this).data('before');
						$(this).prop('disabled', false).removeClass('elgg-state-disabled').data('before', null).val(val);
					});
				}
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

		$('[name="special_states[]"][value="auto_join"]').live('change', function (e) {
			var $fix = $('[name="auto_join_fix"]');
			if ($(this).prop('checked') === true) {
				$fix.closest('.gt-form-field').removeClass('hidden');
				$fix.prop('checked', true);
			} else {
				$fix.closest('.gt-form-field').addClass('hidden');
				$fix.prop('checked', false);
			}
		});
	};

	elgg.register_hook_handler('init', 'system', elgg.group_tools.tools.init);
	