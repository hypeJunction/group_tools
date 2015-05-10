<?php

/**
 * Set notification settings of group members
 */
$entity = elgg_extract('entity', $vars);
$user = elgg_get_logged_in_user_entity();

if (!elgg_instanceof($entity, 'group')) {
	return true;
}

if (!$user->isAdmin()) {
	return true;
}

$form = elgg_view_form('group_tools/notifications', array(
	'class' => 'gt-tools-form',
		), $vars);

echo elgg_view_module('info', elgg_echo('group_tools:notifications:title'), $form);
