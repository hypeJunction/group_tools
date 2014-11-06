<?php

$entity = elgg_extract('entity', $vars);
$vars['callback'] = 'group_tools_admin_transfer_user_search';
$vars['query'] = array(
	'group_guid' => $entity->guid
);

echo elgg_view('input/tokeninput', $vars);