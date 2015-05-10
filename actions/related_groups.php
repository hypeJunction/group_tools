<?php

/**
 * Action to save a new related group
 */
$group_guid = (int) get_input('group_guid');
$guid = get_input('guid');

if (empty($group_guid) || empty($guid)) {
	register_error(elgg_echo('InvalidParameterException:MissingParameter'));
	forward(REFERER);
}


$guid = (is_array($guid)) ? $guid[0] : $guid;

$group = get_entity($group_guid);
$related = get_entity($guid);

// do we have groups
if (!elgg_instanceof($group, 'group') || !elgg_instanceof($related, 'group')) {
	register_error(elgg_echo('groups:notfound:details'));
	forward(REFERER);
}

if (!$group->canEdit()) {
	register_error(elgg_echo('groups:cantedit'));
	forward(REFERER);
}

if ($group->guid == $related->guid) {
	register_error(elgg_echo('group_tools:action:related_groups:error:same'));
	forward(REFERER);
}

// not already related?
if (check_entity_relationship($group->guid, 'related_group', $related->guid)) {
	register_error(elgg_echo('group_tools:action:related_groups:error:already'));
	foward(REFERER);
}

if (add_entity_relationship($group->guid, 'related_group', $related->guid)) {
	// notify the other owner about this
	if ($group->owner_guid != $related->owner_guid) {
		$subject = elgg_echo('group_tools:related_groups:notify:owner:subject');
		$message = elgg_echo('group_tools:related_groups:notify:owner:message', array(
			$related->getOwnerEntity()->name,
			elgg_get_logged_in_user_entity()->name,
			$related->name,
			$group->name
		));

		notify_user($related->owner_guid, $group->owner_guid, $subject, $message);
	}

	system_message(elgg_echo('group_tools:action:related_groups:success'));
} else {
	register_error(elgg_echo('group_tools:action:related_groups:error:add'));
}

forward(REFERER);
