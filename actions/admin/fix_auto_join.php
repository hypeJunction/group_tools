<?php

/**
 * Make sure everyone is a member of the autojoin group
 */

// this could take a while ;)
set_time_limit(0);

$group_guid = get_input('group_guid');
if (!$group_guid) {
	register_error(elgg_echo('group_tools:action:error:input'));
	forward(REFERER);
}

$group = get_entity($group_guid);
if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group_tools:action:error:entity'));
	forward(REFERER);
}

$success = group_tools_auto_join($group);
system_message(elgg_echo('group_tools:action:fix_auto_join:success', array($success)));

forward(REFERER);
