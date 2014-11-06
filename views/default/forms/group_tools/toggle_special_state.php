<?php

$user = elgg_get_logged_in_user_entity();
$group = elgg_extract('entity', $vars);

$values = array();
if ($group->featured_group == 'yes') {
	$values[] = 'featured';
}
if ($group->group_tools_auto_join) {
	$values[] = 'auto_join';
}
if ($group->group_tools_suggested) {
	$values[] = 'suggested';
}

echo '<div class="gt-form-field">';
echo '<label>' . elgg_echo('group_tools:special_states:title') . '</label>';
echo '<span class="elgg-text-help">' . elgg_echo('group_tools:special_states:description') . '</span>';
echo elgg_view('input/checkboxes', array(
	'name' => 'special_states',
	'value' => $values,
	'options' => array(
		elgg_echo('group_tools:special_states:featured') => 'featured',
		elgg_echo('group_tools:special_states:suggested') => 'suggested',
		elgg_echo('group_tools:special_states:auto_join') => 'auto_join',
	)
));
echo '</div>';

if (!in_array('auto_join', $values)) {
	echo '<div class="gt-form-field hidden">';
	echo '<label>';
	echo elgg_view('input/checkbox', array(
		'name' => 'auto_join_fix',
		'default' => false,
		'value' => 'yes',
	));
	echo elgg_echo('group_tools:special_states:auto_join:fix');
	echo '</label>';
	echo '</div>';
}

echo '<div class="elgg-foot">';
echo elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $group->guid,
));
echo elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
));
echo '</div>';
