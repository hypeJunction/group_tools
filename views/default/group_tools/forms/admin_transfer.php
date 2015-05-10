<?php

$entity = elgg_extract('entity', $vars);
$user = elgg_get_logged_in_user_entity();

if (!elgg_instanceof($entity, 'group') || !$entity->canEdit()) {
	return true;
}

// check plugin settings
$admin_transfer = elgg_get_plugin_setting('admin_transfer', 'group_tools');
if ($admin_transfer == 'no') {
	return true;
} else if ($admin_transfer == 'admin' && !$user->isAdmin()) {
	return true;
} else if ($admin_transfer == 'owner' && $entity->owner_guid != $user->guid && !$user->isAdmin()) {
	return true;
}

$form = elgg_view_form('group_tools/admin_transfer', array(
	'class' => 'gt-tools-form',
	'data-confirm' => elgg_echo('group_tools:admin_transfer:confirm'),
		), $vars);

echo elgg_view_module('info', elgg_echo('group_tools:admin_transfer:title'), $form);
