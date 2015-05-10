<?php

$group = elgg_extract('entity', $vars);

$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethods();

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

$member_count = '<span data-counter data-count="notifications-member">' . $group->getMembers(0, 0, true) . '</span>';
$notification_count = '<span data-counter data-count="notifications-active">' . elgg_get_entities($notification_options) . '</span>';

echo '<div class="gt-form-field">';
echo '<label>' . elgg_echo('group_tools:notifications:title') . '</label>';
echo '<span class="elgg-text-help">';
echo elgg_echo('group_tools:notifications:description', array($member_count, $notification_count));
echo elgg_echo('group_tools:notifications:disclaimer');
echo '</span>';
echo elgg_view('input/radio', array(
	'name' => 'toggle',
	'value' => 'enable',
	'options' => array(
		elgg_echo('group_tools:notifications:enable') => 'enable',
		elgg_echo('group_tools:notifications:disable') => 'disable',
	)
));
echo '</div>';

echo '<div class="gt-form-field">';
echo '<label>' . elgg_echo('group_tools:notifications:methods') . '</label>';
echo elgg_view('input/checkboxes', array(
	'name' => 'methods',
	'default' => false,
	'value' => array_values($methods),
	'options' => $methods,
));
echo '</div>';

echo '<div class="elgg-foot">';
echo elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $group->guid,
));
echo elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
));
echo '</div>';
