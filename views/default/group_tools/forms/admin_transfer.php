<?php

elgg_load_js('group_tools.tools');

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity, 'group') || !$group->canEdit()) {
	return true;
}

$form = elgg_view_form('group_tools/admin_transfer', array(
	'class' => 'gt-tools-form',
	'data-confirm' => elgg_echo('group_tools:admin_transfer:confirm'),
		), $vars);

echo elgg_view_module('info', elgg_echo('group_tools:admin_transfer:title'), $form);