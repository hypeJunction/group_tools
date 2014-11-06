//<script>

	elgg.provide('elgg.group_tools.invite');

	elgg.group_tools.invite.init = function () {

		// tab filter
		$('.gt-invite-tab').live('click', function (e) {
			e.preventDefault();
			
			var $elem = $(this);
			var target = $elem.find('a').eq(0).attr('href');
			
			$elem.siblings('.gt-invite-tab').andSelf().removeClass('elgg-state-selected');
			$elem.addClass('elgg-state-selected');
			
			$(target).siblings('.gt-invite-form').andSelf().removeClass('elgg-state-active').addClass('hidden');
			$(target).addClass('elgg-state-active').removeClass('hidden');
		});
		
		// toggle all friends
		$('#gt-friends-toggle').live('change', function(e) {
			if ($(this).prop('checked') === true) {
				$('#gt-invite-friends-friendspicker').find('input[name="friends[]"]').prop('checked', true);
			} else {
				$('#gt-invite-friends-friendspicker').find('input[name="friends[]"]').prop('checked', false);
			}
		});
		
		$('.elgg-menu-item-addtogroup a, .elgg-menu-item-killrequest a, .elgg-menu-item-killinvitation a, .elgg-menu-item-revoke-email-invitation a').live('click', function(e) {
			e.preventDefault();
			var $elem = $(this);
			elgg.action($elem.attr('href'), {
				success: function(response) {
					if (response.status >= 0) {
						$elem.closest('.elgg-item').remove();
					}
				}
			});
		});
	};

	elgg.register_hook_handler('init', 'system', elgg.group_tools.invite.init);
	