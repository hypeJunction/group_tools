<?php

/**
 * Mail select members of a group
 */
$group_guid = (int) get_input('group_guid', 0);
$group = get_entity($group_guid);

if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group_tools:action:error:entity'));
	forward(REFERER);
}

$user_guids = get_input('user_guids', array());

$subject = get_input('title');
$body = get_input('description');
$method = get_input('method', '');

if (empty($user_guids) || empty($body)) {
	register_error(elgg_echo('group_tools:action:error:input'));
	forward(REFERER);
}

if (!$group->canEdit()) {
	register_error(elgg_echo('group_tools:action:error:edit'));
	forward(REFERER);
}

set_time_limit(0);

$members = new ElggBatch('elgg_get_entities_from_relationship', array(
	'guids' => $user_guids,
	'types' => 'user',
	'relationship' => 'member',
	'relationship_guid' => $group->guid,
	'inverse_relationship' => true,
	'limit' => 0,
	'callback' => false,
		));

$body .= PHP_EOL . PHP_EOL;
$body .= elgg_echo('group_tools:mail:message:from') . ': ' . $group->name . ' [' . $group->getURL() . ']';

$success = 0;
foreach ($members as $member) {
	if (notify_user($member->guid, $group->guid, $subject, $body, NULL, $method)) {
		$success++;
	}
}

system_message(elgg_echo('group_tools:action:mail:success', array($success, count($user_guids))));
forward(REFERER);
