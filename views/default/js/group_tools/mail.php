//<script>

	elgg.provide('elgg.group_tools.invite');

	elgg.group_tools.invite.init = function () {

		// toggle all friends
		$('#gt-mail-reicipients-toggle').live('change', function () {
			if ($(this).prop('checked') === true) {
				$('#gt-mail-recipients-friendspicker').find('input[name="user_guids[]"]').prop('checked', true);
			} else {
				$('#gt-mail-recipients-friendspicker').find('input[name="user_guids[]"]').prop('checked', false);
			}
		});

		$('#gt-mail-recipients-friendspicker input[name="user_guids[]"]').live('change', function () {
			var count = $('#gt-mail-recipients-friendspicker input[name="user_guids[]"]:checked').length;
			$('[data-counter="gt-mail-recipients-count"]').text(count);
		});

		$('#gt-mail-recipients-edit').live('click', function (e) {
			e.preventDefault();
			$(this).toggleClass('elgg-state-active');
			$('#gt-mail-recipients-list').toggleClass('hidden');
		});

	};

	elgg.register_hook_handler('init', 'system', elgg.group_tools.invite.init);
	