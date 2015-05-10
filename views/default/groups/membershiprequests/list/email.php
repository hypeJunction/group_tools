<?php

$annotation = elgg_extract('annotation', $vars);
$group = elgg_extract('group', $vars);

$menu = elgg_view_menu('group_tools_requests', array(
	'annotation' => $annotation,
	'group' => $group,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
		));

list(, $email) = explode('|', $annotation->value);

$body = elgg_view('output/email', array('value' => $email));

echo elgg_view_image_block('', $body, array(
	'image_alt' => $menu,
));
