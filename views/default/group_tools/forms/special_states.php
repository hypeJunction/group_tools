<?php

/**
 * Here we will show is a group has one or more of the following special states
 * - featured
 * - autojoin
 * - suggested
 *
 * Also these states can be toggled
 */
$entity = elgg_extract('entity', $vars);
$user = elgg_get_logged_in_user_entity();

if (!elgg_instanceof($entity, 'group')) {
	return true;
}

if (!$user->isAdmin()) {
	return true;
}

$form = elgg_view_form('group_tools/toggle_special_state', array(
	'class' => 'gt-tools-form',
		), $vars);

echo elgg_view_module('info', elgg_echo('group_tools:special_states:title'), $form);
