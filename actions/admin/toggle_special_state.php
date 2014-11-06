<?php

/**
 * (un)Mark a group a as special
 */
$guid = get_input('group_guid');
if (!$guid) {
	register_error(elgg_echo('group_tools:action:error:input'));
	forward(REFERER);
}

$group = get_entity($guid);
if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group_tools:action:error:entity'));
	forward(REFERER);
}

$states = array('featured', 'auto_join', 'suggested');
$toggled_states = get_input('special_states');
if (!is_array($toggled_states)) {
	$toggled_states = array();
	if ($state = get_input('state')) {
		if ($state !== 'featured' && $group->featured_group == 'yes') {
			$toggled_states[] = 'featured';
		}
		if ($state !== 'auto_join' && $group->group_tools_auto_join) {
			$toggled_states[] = 'auto_join';
		}
		if ($state !== 'suggested' && $group->group_tools_suggested) {
			$toggled_states[] = 'suggested';
		}
	}
}

foreach ($states as $state) {
	switch ($state) {

		case 'featured' :
			$new_state = (in_array($state, $toggled_states)) ? 'yes' : 'no';
			if ($group->featured_group != $new_state) {
				$group->featured_group = $new_state;
				if ($new_state == 'yes') {
					system_message(elgg_echo('groups:featuredon', array($group->name)));
				} else {
					system_message(elgg_echo('groups:unfeatured', array($group->name)));
				}
			}
			break;

		case 'auto_join':
			$new_state = (in_array($state, $toggled_states));
			if ($group->group_tools_auto_join != $new_state) {
				$group->group_tools_auto_join = $new_state;
				system_message(elgg_echo('group_tools:action:toggle_special_state:auto_join'));

				if (get_input('auto_join_fix') == 'yes') {
					// fix auto joins
					$success = group_tools_auto_join($group);
					system_message(elgg_echo('group_tools:action:fix_auto_join:success', array($success)));
				}
			}
			break;

		case 'suggested':
			$new_state = (in_array($state, $toggled_states));
			if ($group->group_tools_suggested != $new_state) {
				$group->group_tools_suggested = $new_state;
				system_message(elgg_echo('group_tools:action:toggle_special_state:suggested'));
			}
			break;
	}
}

forward(REFERER);
