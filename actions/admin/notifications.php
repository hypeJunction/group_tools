<?php

/**
 * Enable or disable group notifications for all members
 */
$toggle = get_input('toggle');
$guid = get_input('group_guid');
$methods = get_input('methods', array());

if (!$guid || !in_array($toggle, array('enable', 'disable')) || !count($methods)) {
	register_error(elgg_echo('group_tools:action:error:input'));
	forward(REFERER);
}

$group = get_entity($guid);
if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group_tools:action:error:entity'));
	forward(REFERER);
}

$relationships = array();
foreach ($methods as $method) {
	$relationships[] = "'notify{$method}'";
}
$relationships_count = count($relationships);
$in_relationships = implode(',', $relationships);

$dbprefix = elgg_get_config('dbprefix');
$options = array(
	'type' => 'user',
	'joins' => array(
		"JOIN {$dbprefix}entity_relationships er1 ON er1.guid_one = e.guid",
	),
	'wheres' => array(
		"(er1.guid_two = $group->guid AND er1.relationship = 'member')",
		"((SELECT COUNT(DISTINCT(er2.relationship)) FROM {$dbprefix}entity_relationships er2 
			WHERE er2.guid_one = e.guid AND er2.guid_two = $group->guid AND er2.relationship IN ($in_relationships))" .
			(($toggle == 'enable') ? " < $relationships_count" : " > 0") . ')',
	),
	'limit' => 0,
);

$members = new ElggBatch('elgg_get_entities', $options);
$members->setIncrementOffset(false);

if ($toggle == 'enable') {
	// enable notification for everyone
	foreach ($members as $member) {
		foreach ($methods as $method) {
			add_entity_relationship($member->guid, "notify{$method}", $group->guid);
		}
	}

	system_message(elgg_echo('group_tools:action:notifications:success:enable'));
	$forward_url = $group->getURL();
} elseif ($toggle == 'disable') {
	// disable notification for everyone
	foreach ($members as $member) {
		foreach ($methods as $method) {
			remove_entity_relationship($member->guid, "notify{$method}", $group->guid);
		}
	}

	system_message(elgg_echo('group_tools:action:notifications:success:disable'));
	$forward_url = $group->getURL();
}

if (elgg_is_xhr()) {
	global $NOTIFICATION_HANDLERS;

	$relationships = array();
	foreach ($NOTIFICATION_HANDLERS as $method => $dummy) {
		$methods[elgg_echo("notification:method:$method")] = $method;
		$relationships[] = "'notify{$method}'";
	}
	$in_relationships = implode(',', $relationships);

	$dbprefix = elgg_get_config('dbprefix');
	$notification_options = array(
		'type' => 'user',
		'count' => true,
		'joins' => array(
			"JOIN {$dbprefix}entity_relationships er ON er.guid_one = e.guid",
		),
		'wheres' => array(
			"er.relationship IN ($in_relationships) AND er.guid_two = $group->guid"
		)
	);

	$member_count = $group->getMembers(0, 0, true);
	$notification_count = elgg_get_entities($notification_options);

	echo json_encode(array(
		'counters' => array(
			'notifications-members' => $member_count,
			'notifications-active' => $notification_count,
		)
	));
}

forward($forward_url);
